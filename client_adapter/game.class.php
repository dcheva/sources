<?php

/**
 * Class Game
 * Adapter for client integration with the base game API 
 * @version 0.1.0
 * @author dmitry.cheva@gmail.com
 */
class Game
{
    private $config;
    private $token;
    private $remoteId;

    /**
     * Game constructor.
     * Creates token if not created yet.
     * @param string $token
     */
    public function __construct($token = '')
    {
        $this->config = parse_ini_file('game.config.ini', true);
        if (empty($token)) {
            $params = array(
                'apikey' => $this->config['apikey'],
                'callBackUrl' => $this->config['callBackUrl'],

            );
            $response = $this->getResponse('token', http_build_query($params));
            if (!empty($response['token'])) $this->token = $response['token'];
        } else $this->token = $token;
    }

    /**
     * Get current token
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * Get frame string
     * @return string
     */
    public function getFrame()
    {
        $params = $this->config;
        $params['token'] = $this->token;
        unset($params['apiKey'], $params['url']);
        $response = $this->getResponse('frame', http_build_query($params));
        return $response;

    }

    /**
     * Get games list
     * @return mixed
     */
    public function getGameList()
    {
        $params = array(
            'token' => $this->token,
        );
        $response = $this->getResponse('game/list', http_build_query($params));
        return $response;
    }

    /**
     * Get user list
     * @return mixed
     */
    public function getUserList()
    {
        $params = array(
            'token' => $this->token,
        );
        $response = $this->getResponse('user/list', http_build_query($params));
        return $response;
    }

    /**
     * Add|create new user with balance
     * @param $id
     * @param $balance
     * @param $currency
     * @return mixed
     */
    public function getUserNew($id, $balance, $currency)
    {
        $params = array(
            'token' => $this->token,
            'id' => $id,
            'balance' => $balance,
            'currency' => $currency,
        );
        $response = $this->getResponse('user/new', http_build_query($params));
        if (!empty($response['remoteId'])) $this->remoteId = $response['remoteId'];
        return $response;
    }

    /**
     * Get remote user id (partner's user id)
     * @return integer
     */
    public function getUser()
    {
        return $this->remoteId;
    }

    /**
     * Set remote user id (partner's user id)
     * @param $remoteId
     * @return integer
     */
    public function setUser($remoteId)
    {
        $this->remoteId = $remoteId;
        return $this->remoteId;
    }

    /**
     * Create bet
     * @param $gameId
     * @param $bet
     * @return mixed
     */
    public function getUserBet($gameId, $bet)
    {
        $params = array(
            'token' => $this->token,
            'gameId' => $gameId,
            'bet' => $bet,
        );
        $response = $this->getResponse('user/' . $this->remoteId . '/bet', http_build_query($params));
        return $response;
    }

    /**
     * Get user balance
     * @return mixed
     */
    public function getUserBalance()
    {
        $params = array(
            'token' => $this->token,
        );
        $response = $this->getResponse('user/' . $this->remoteId . '/balance/get', http_build_query($params));
        return $response;
    }

    /**
     * Change user balance
     * @param $balance
     * @param $currency
     * @return mixed
     */
    public function getUserBalanceChange($balance, $currency)
    {
        $params = array(
            'token' => $this->token,
            'balance' => $balance,
            'currency' => $currency,
        );
        $response = $this->getResponse('user/' . $this->remoteId . '/balance/change', http_build_query($params));
        return $response;
    }

    /**
     * Get API response
     * @param string $method API method
     * @param string $params encoded GET query string
     * @return mixed
     */
    public function getResponse($method = 'frame', $params = '')
    {
        $get = $this->config['url'] . "api/$method?$params";
        // var_dump($get);
        $data = json_decode(file_get_contents($get, true), true);
        if (!empty($data['success']) && $data['success']) {
            return $data['data'];
        } else return !empty($data['error']['message']) ? $data['error']['message'] : 'Bad response';
    }

}
