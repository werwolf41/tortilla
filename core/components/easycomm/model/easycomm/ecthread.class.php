<?php
class ecThread extends xPDOSimpleObject {

    public function updateMessagesInfo() {
        $count = 0;
        $last = null;

        $ratingCount = 0;
        $ratingSum = 0;
        $ratingWilson = 0;
        $ratingSimple = 0;

        $q = $this->xpdo->newQuery('ecMessage', array('thread' => $this->get('id'), 'published' => 1, 'deleted' => 0));
        $q->select($this->xpdo->getSelectColumns('ecMessage', 'ecMessage', '', array('id', 'rating')));
        //$q->select('COUNT(`id`) as `count`, SUm(`rating`) as `rating_sum`');

        if ($q->prepare() && $q->stmt->execute()) {
            $messages = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

            $count = count($messages);
            foreach($messages as $message) {
                // Only messages that have a non-zero rating.
                if($message['rating'] > 0){
                    $ratingCount++;
                    $ratingSum += $message['rating'];
                }
            }
            $ratingWilson = $this->ratingWilson($ratingSum, $ratingCount, 1, $this->xpdo->getOption('ec_rating_max', null, 5));
            $ratingSimple = $this->ratingSimple($ratingSum, $ratingCount);
        }

        $qLast = $this->xpdo->newQuery('ecMessage', array('thread' => $this->get('id'), 'published' => 1, 'deleted' => 0));
        $qLast->sortby('date', 'DESC');
        $qLast->limit(1);
        $last = $this->xpdo->getObject('ecMessage', $qLast);

        if ($last) {
            $this->set('message_last', $last->get('id'));
            $this->set('message_last_date', $last->get('date'));
        }
        else {
            $this->set('message_last', 0);
            $this->set('message_last_date', null);
        }

        $this->set('count', $count);
        $this->set('rating_wilson', $ratingWilson);
        $this->set('rating_simple', $ratingSimple);

        $this->save();
    }

    /*
     * See http://habrahabr.ru/company/darudar/blog/143188/
     */
    private function ratingWilson($sum, $count, $minAllowedRating, $maxAllowedRating){
        if($count <= 0) {
            return 0;
        }
        //1.0 = 85%, 1.6 = 95%
        $z = floatval($this->xpdo->getOption('ec_rating_wilson_confidence', null, 1.6));

        $width = (float) $maxAllowedRating - $minAllowedRating;
        $c = (float) $count;
        $phat = ($sum - $c * $minAllowedRating) / $width / $c;
        $rating = ($phat + $z * $z/(2 * $c) - $z * sqrt(($phat * (1 - $phat) + $z * $z / (4 * $c))/$c))/(1 + $z * $z/$c);
        return $rating * $width + $minAllowedRating;
    }

    private function ratingSimple($sum, $count){
        if($count <= 0) {
            return 0;
        }
        return $sum / (float) $count;
    }
}
