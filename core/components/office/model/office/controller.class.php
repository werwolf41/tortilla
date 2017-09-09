<?php

abstract class officeDefaultController
{
    /** @var modX $modx */
    public $modx;
    /** @var Office $office */
    public $office;
    public $config;


    /**
     * @param Office $office
     * @param array $config
     */
    public function __construct(Office $office, array $config = array())
    {
        $this->modx = &$office->modx;
        $this->office = &$office;
        $this->setDefault($config);
        $topics = $this->getLanguageTopics();
        foreach ($topics as $topic) {
            $this->modx->lexicon->load($topic);
        }
    }


    /**
     * @param array $config
     */
    public function setDefault($config = array())
    {
        $this->config = $config;
    }


    /**
     * @param string $ctx
     *
     * @return bool
     */
    public function initialize($ctx = 'web')
    {
        return true;
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array();
    }


    /**
     * @return string
     */
    public function getDefaultAction()
    {
        return 'defaultAction';
    }


    /**
     * @return string
     */
    public function defaultAction()
    {
        return 'Default action of default controller';
    }


    /**
     * This method returns an error response
     *
     * @param string $message A lexicon key for error message
     * @param array $data Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     * */
    public function error($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => false,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        );

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }


    /**
     * This method returns an success response
     *
     * @param string $message A lexicon key for success message
     * @param array $data Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     * */
    public function success($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => true,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        );

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }

}