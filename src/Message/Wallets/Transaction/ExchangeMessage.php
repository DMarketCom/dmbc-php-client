<?php
/**
 * ExchangeMessage.php
 *
 * @author   Ilya Sinyakin <sinyakin.ilya@gmail.com>
 */

namespace SunTechSoft\Blockchain\Message\Wallets\Transaction;

use SunTechSoft\Blockchain\Helper\ExchangeOffer;

class ExchangeMessage extends AbstractMessage
{
    const MESSAGE_ID = 6;

    /**
     * @var ExchangeOffer
     */
    private $offer;

    public function __construct(ExchangeOffer $offer)
    {
        $this->offer = $offer;
    }

    /**
     * Exonum structure for Exchange's message
     *
     * body:
     *   offer:              ExchangeOffer      [00 => 08]
     *   seed:               u64                [08 => 16]
     *   sender_signature    &Signature         [16 => 80]
     **
     */
    public function createMessageForSignature()
    {
        $sizeBody = 80;
        $hashOffer = $this->offer->toHash();

        $this->payloadLength = self::PACKED_HEADER_SIZE + $sizeBody + strlen($hashOffer) + 64; // 64 - length(signature)

        $s = $this->getPackedHeader()
             . pack('V', $this->payloadLength)
             . pack('VV', self::PACKED_HEADER_SIZE + $sizeBody, strlen($hashOffer))
             . pack('P', (int)$this->getSeed())
             . \Sodium\hex2bin($this->getOffer()->getSignature())
             . $hashOffer
        ;

        return $s;
    }

    public function getBody()
    {
        if (is_null($this->body)) {
            $this->body = [
                'offer'            => $this->getOffer()->toArray(),
                'seed'             => $this->getSeed(),
                'sender_signature' => $this->getOffer()->getSignature()
            ];
        }

        return $this->body;
    }

    /**
     * @return ExchangeOffer
     */
    public function getOffer(): ExchangeOffer
    {
        return $this->offer;
    }

    /**
     * @param ExchangeOffer $offer
     *
     * @return ExchangeMessage
     */
    public function setOffer(ExchangeOffer $offer): ExchangeMessage
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return self::MESSAGE_ID;
    }
}
