<?php

namespace App\Utils;

class TextMessage
{
    private static $instance;
    private $curl;

    private function __construct()
    {
        $this->curl = curl_init();
    }

    public function __destruct()
    {
        if ($this->curl !== null) {
            curl_close($this->curl);
        }
    }

    public static function getInstance(): TextMessage
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function send($from, $message, $number)
    {
        curl_setopt_array(
            $this->curl,
            [
                CURLOPT_URL => 'https://3g4z6v.api.infobip.com/sms/2/text/advanced',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{"messages":[{"destinations":[{"to":"' . $number . '"}],"from":"' . $from . '","text":"' . $message . '"}]}',
                CURLOPT_HTTPHEADER => [
                    'Authorization: App c84365e839b76cba374839d02f391b59-676aec5d-b65e-45ae-8852-5f29e22bc160',
                    'Content-Type: application/json',
                    'Accept: application/json'
                ]
            ]
        );

        $response = curl_exec($this->curl);

        if ($response === false) {
            $errorMessage = curl_error($this->curl);
            throw new \Exception("cURL error: $errorMessage");
        }

        return $response;
    }
}
