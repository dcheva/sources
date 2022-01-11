<?php

/**
 * Class Game
 * Adapter for 1xbet integration with the base game API 
 * @version 0.1.0
 * @author dmitry.cheva@gmail.com
 */
class Game
{
    private $response;
    private $operatorid;
    private $accountid;
    private $currency;
    private $session;
    private $txid;
    private $user = [];
    private $apitoken;
    private $token;
    private $config;

    /**
     * Game constructor.
     * @param $request
     */
    public function __construct($request)
    {
        $request = $this->xml2array($request);
        $this->config = parse_ini_file('game.config.ini', true);
        $this->accountid = $this->config['userId'];
        $params = ['apikey' => $this->config['apikey'], 'callbackUrl' => $this->config['callback']];
        $response = json_decode($this->getApiResponse('token', http_build_query($params)), true);
        if (!empty($response['data']['partnerToken'])) $this->apitoken = $response['data']['partnerToken'];

        $this->operatorid = $request['OPERATORID'];
        $this->session = $request['SESSIONID'] ?? password_hash($request['TOKEN'], PASSWORD_BCRYPT);
        $this->currency = $request['CURRENCY'];
        $this->token = $request['TOKEN'] ?? $this->config['token'];
        $this->amount = $request['AMOUNT'];
        $this->txid = $request['TXID'];
    }

    /**
     * @return string
     */
    public function getAccount()
    {
        // get user or create new
        $response = json_decode($this->getApiResponse("user/" . $this->token . "/remoteid", http_build_query(['partnerToken' => $this->apitoken])), true);
        var_dump($response);
        if ($response['success'] && isset($response['data']) && $response['data']['remoteId']) {
            $this->user['remoteid'] = $response['data']['remoteId'];
            $this->user['currency'] = $this->currency;
        } else {
            // create user
            $params = [
                'partnerToken' => $this->apitoken,
                "id" => $this->token,
                "balance" => $this->config['balance'],
                "currency" => $this->currency,
            ];

            $response = json_decode($this->getApiResponse("user/new", http_build_query($params)), true);
            var_dump($response);
            if (!empty($response['data']) && !empty($response['data']['remoteId']))
                $this->user['remoteid'] = $response['data']['remoteId'];
        }
        $this->response =
            [
                '@attributes' => [
                    'action' => "getaccount",
                    'rc' => isset($response['error']['message']) ? $response['error']['code'] : "0",
                    'msg' => isset($response['error']['message']) ? $response['error']['message'] : "",
                ],
                'OPERATORID' => $this->operatorid,
                'ACCOUNTID' => $this->accountid,
                'CURRENCY' => $this->currency,
                'SESSIONID' => $this->session,
//                'JURISDICTION' => 'MGA',
//                'CITY' => 'Berlin',
//                'COUNTRY' => 'DEU',
//                'SCREENNAME' => 'Berlin7',
        ];
        return $this->getResponse();
    }

    /**
     * @return string
     */
    public function getBalance()
    {
        // api/user/{id}/balance/get
        $response = json_decode($this->getApiResponse("user/" . $this->token . "/balance/get", http_build_query(['partnerToken' => $this->apitoken])), true);
        var_dump($response);
        if (!$response['success']) echo $response['message'];
        $this->response =
            [
                '@attributes' => [
                    'action' => "getbalance",
                    'rc' => isset($response['error']['message']) ? $response['error']['code'] : "0",
                    'msg' => isset($response['error']['message']) ? $response['error']['message'] : "",

                ],
                'OPERATORID' => $this->operatorid,
                'ACCOUNTID' => $this->accountid,
                'CURRENCY' => $response['data']['currency'],
                'BALANCE' => $response['data']['balance'],
            ];
        return $this->getResponse();
    }

    /**
     * @return string
     */
    public function withdraw()
    {
        // /api/user/{id}/balance/change
        $params = [
            'amount' => $this->amount,
            'txid' => $this->txid,
            'partnerToken' => $this->apitoken
        ];
        $response = json_decode($this->getApiResponse("user/" . $this->token . "/balance/change", http_build_query($params)), true);
        var_dump($response);
        if (!$response['success']) echo $response['message'];
        $this->response =
            [
                '@attributes' => [
                    'action' => "withdraw",
                    'rc' => isset($response['error']['message']) ? $response['error']['code'] : "0",
                    'msg' => isset($response['error']['message']) ? $response['error']['message'] : "",

                ],
                'OPERATORID' => $this->operatorid,
                'ACCOUNTID' => $this->accountid,
                'WALLETTXID' => $this->txid,
                'CURRENCY' => $response['data']['currency'],
                'BALANCE' => $response['data']['balance'],
                'REALMONEY' => $response['data']['balance'],
                'BONUSMONEY' => 0,
            ];
        return $this->getResponse();
    }

    /**
     * @return string
     */
    public function deposit()
    {
        /**
         * /api/user/{id}/balance/change
         * "amount: -10,
         * txid: 12345678"
         */
        $params = [
            'amount' => $this->amount,
            'txid' => $this->txid,
            'partnerToken' => $this->apitoken
        ];
        $response = json_decode($this->getApiResponse("user/" . $this->token . "/balance/change", http_build_query($params)), true);
        var_dump($response);
        if (!$response['success']) echo $response['message'];
        $this->response =
            [
                '@attributes' => [
                    'action' => "deposit",
                    'rc' => isset($response['error']['message']) ? $response['error']['code'] : "0",
                    'msg' => isset($response['error']['message']) ? $response['error']['message'] : "",
                ],
                'OPERATORID' => $this->operatorid,
                'ACCOUNTID' => $this->accountid,
                'WALLETTXID' => $this->txid,
                'CURRENCY' => $response['data']['currency'],
                'BALANCE' => $response['data']['balance'],
            ];
        return $this->getResponse();
    }

    /**
     * @return string
     */
    private function getResponse()
    {
        Array2XML::init('1.0', 'UTF-8');
        $response = Array2XML::createXML('RSP', $this->response);
        return $response->saveXML();
    }

    /**
     * Get API response
     * @param string $method API method
     * @param string $params encoded GET query string
     * @return mixed
     */
    public function getApiResponse($method, $params = '')
    {
        $get = $this->config['url'] . "api/$method?$params";
        var_dump($get);
        echo "---\n";
        $data = file_get_contents($get, true);
        return $data;
    }

    /**
     * @param $xmlObject
     * @param array $out
     * @return array
     */
    private function xml2array($xmlObject, $out = array())
    {
        foreach ((array)$xmlObject as $index => $node)
            $out[$index] = (is_object($node)) ? xml2array($node) : $node;

        return $out;
    }


}
