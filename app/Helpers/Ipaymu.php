<?php

namespace App\Helpers;

class Ipaymu
{
    protected $apiKey, $va, $notifyUrl;
    protected $buyer = array();
    protected $cart = array();

    public function __construct($apiKey, $va) {
        $this->apiKey = $apiKey;
        $this->va = $va;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getVa()
    {
        return $this->va;
    }

    public function getNotifyUrl()
    {
        if ($this->notifyUrl) {
            return $this->notifyUrl;
        }

        return url('/');
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }

    public function setBuyer($buyer)
    {
        $this->buyer = [
            'name'  => $buyer['name'],
            'phone' => $buyer['phone'],
            'email' => $buyer['email'],
        ];
    }

    public function setCart($cart)
    {
        $this->cart = [
            'amount'            => $cart['amount'],
            'description'       => $cart['description'],
            'paymentMethod'     => $cart['paymentMethod'],
            'paymentChannel'    => $cart['paymentChannel'],
            'referenceId'       => $cart['referenceId']
        ];
    }

    public function getBody()
    {
        $body = [
            'name'          => $this->buyer['name'],
            'email'         => $this->buyer['email'],
            'phone'         => $this->buyer['phone'],
            'amount'        => $this->cart['amount'],
            'paymentMethod' => $this->cart['paymentMethod'],
            'paymentChannel'=> $this->cart['paymentChannel'],
            'description'   => $this->cart['description'],
            'referenceId'   => $this->cart['referenceId'],
            'notifyUrl'     => $this->getNotifyUrl(),
            'expired'       => 24
        ];
        return $body;
    }

    public function config($url)
    {
        return 'https://my.ipaymu.com/api/v2/'.$url;
    }

    public function getSignature($data, $credentials)
    {
        $body = json_encode($data, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $body));
        $secret       = $credentials['apikey'];
        $va           = $credentials['va'];
        $stringToSign = 'POST:' . $va . ':' . $requestBody . ':' . $secret;
        $signature    = hash_hmac('sha256', $stringToSign, $secret);

        return $signature;
    }

    public function request($config, $body, $credentials)
    {
        $signature = $this->getSignature($body, $credentials);
        $timestamp = Date('YmdHis');
        $headers = array(
            'Content-Type: application/json',
            'va: ' . $credentials['va'],
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $config);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $request = curl_exec($ch);

        if ($request === false) {
            echo 'Curl Error: ' . curl_error($ch);
        } else {
            return json_decode($request, true);
        }

        curl_close($ch);
        exit;
    }

    /**
     * Check Saldo.
     */
    public function checkBalance()
    {
        $data = file_get_contents('https://my.ipaymu.com/api/saldo?key='.$this->getApiKey());
        return json_decode($data, true);
    }

    public function checkTransaction($id)
    {
        $response = $this->request(
            "https://my.ipaymu.com/api/transaksi",
            [
                'key' => $this->getApiKey(),
                'id' => $id,
                'format' => 'json'
            ],
            [
                'va' => $this->getVa(),
                'apikey' => $this->getApiKey(),
            ]
        );

        return $response;
    }

    public function checkout()
    {
        $response = $this->request(
            $this->config('payment/direct'),
            $this->getBody(),
            [
                'va' => $this->getVa(),
                'apikey' => $this->getApiKey(),
            ]
        );

        return $response;
    }
}
