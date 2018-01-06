<?php
/**
 * Client.php
 *
 * @author   Ilya Sinyakin <sinyakin.ilya@gmail.com>
 */

namespace SunTechSoft\Blockchain;

use SunTechSoft\Blockchain\Exception\CallException;
use SunTechSoft\Blockchain\Message\Wallets\AbstractMessage;

class Client
{
    private $domain;
    private $protocol;
    /**
     * @var string
     */
    private $version;
    /**
     * @var string
     */
    private $baseUrl;

    public function __construct($domain, $protocol = 'https', $version = 'v1', $baseUrl = '/api/services/cryptocurrency/')
    {
        $this->domain = $domain;
        $this->protocol = $protocol;
        $this->version = $version;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param $message
     * @return mixed
     * @throws \Exception
     */
    public function callMethod(AbstractMessage $message)
    {
        $responseData = $this->getResponse($message);
        switch (true) {
            case $responseData !== '':
                $response = json_decode($responseData, true);
                if (json_last_error() > 0) {
                    throw new CallException(json_last_error_msg(), 0, $responseData);
                }
                break;
            default:
                $response = '';
                break;
        }

        return $response;
    }

    /**
     * @param AbstractMessage $message
     * @return string
     * @throws \Exception
     */
    private function getResponse(AbstractMessage $message)
    {
        $curl = curl_init($this->getUrl($message));
        $options = [
            CURLOPT_HTTPHEADER => $this->getHeaders(),
            CURLOPT_RETURNTRANSFER => true,
        ];
        if ($message->isPost()) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $message->getBody();
        }

        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        if ($result === false) {
            throw new \Exception(curl_error($curl));
        }
        curl_close($curl);
        return $result;
    }

    /**
     * @return string
     */
    private function getUrl(AbstractMessage $message)
    {
       return $this->protocol . '://' . $this->domain . $this->baseUrl . $this->version . '/' . $message->getMethodName();
    }

    private function getHeaders()
    {
        return ['Content-Type' => 'application/json'];
    }

}