<?php
/**
 * AbstractMessage.php
 *
 * @author   Ilya Sinyakin <sinyakin.ilya@gmail.com>
 */

namespace SunTechSoft\Blockchain\Message\Wallets;

abstract class AbstractMessage
{
    const MYSTICAL_SERVICE_ID_2 = 2; // it never changes and never passed to constructor

    const PACKED_HEADER_SIZE = 10; //length(networkId + protocolVersion + messageId + serviceId + payloadLength)

    protected $networkId = 0;
    protected $protocolVersion = 0;
    protected $serviceId;

    protected $payloadLength;
    protected $signature = null;

    protected $body = null;
    protected $seed = null;


    public function createMessage($secretKey)
    {
        return [
            'body'             => $this->getBody(),
            'network_id'       => $this->getNetworkId(),
            'protocol_version' => $this->getProtocolVersion(),
            'service_id'       => $this->getServiceId(),
            'message_id'       => $this->getMessageId(),
            'signature'        => $this->createSignature($secretKey)
        ];
    }

    public function createSignature($secretKey)
    {
        return \Sodium\bin2hex(\Sodium\crypto_sign_detached(
            $this->createMessageForSignature(),
            \Sodium\hex2bin($secretKey)
        ));
    }

    /**
     * @return int
     */
    public function getNetworkId()
    {
        return $this->networkId;
    }

    /**
     * @return int
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    protected function setSeed(int $seed = null)
    {
        //todo: нужно реализовать получение колиичство транзакции для кошелько подписывающего транзакцию.
        $this->seed = !is_int($seed) ? rand(0, 255) : $seed;

        return $this;
    }

    /**
     * @return string
     */
    protected function getSeed()
    {
        if (is_null($this->seed)) {
            $this->setSeed();
        }

        return (string)$this->seed;
    }

    /**
     * @return string
     */
    protected function getPackedHeader(): string
    {
        return pack('ccvv', $this->getNetworkId(), $this->getProtocolVersion(), $this->getMessageId(), $this->getServiceId());
    }

    /**
     * @return mixed
     */
    public function getServiceId()
    {
        self::MYSTICAL_SERVICE_ID_2;
    }

    public function isPost()
    {
        return true;
    }

    abstract public function createMessageForSignature();
    abstract public function getMessageId();
    abstract public function getMethodName();
    abstract public function getBody();

}