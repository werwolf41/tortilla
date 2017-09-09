<?php

class ByteHand
{
    /** @var modX $modx */
    var $modx;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX $modx, array $config = array())
    {
        $this->modx = &$modx;
    }


    /**
     * @param $phone
     * @param $text
     *
     * @return bool
     */
    public function send($phone, $text)
    {
        $data = array(
            'id' => $this->modx->getOption('office_sms_id'),
            'key' => $this->modx->getOption('office_sms_key'),
            'to' => $phone,
            'text' => $text,
        );
        if ($from = trim($this->modx->getOption('office_sms_from'))) {
            $data['from'] = $from;
        }
        $link = 'http://bytehand.com:3800/send?' . http_build_query($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
            curl_close($ch);

            return true;
        }
        $this->modx->log(modX::LOG_LEVEL_ERROR, '[Office] Error sending SMS: ' . print_r(curl_getinfo($ch), true));
        curl_close($ch);
        $res = json_decode($res, true);

        return "{$res['description']}, status: {$res['status']}";
    }
}