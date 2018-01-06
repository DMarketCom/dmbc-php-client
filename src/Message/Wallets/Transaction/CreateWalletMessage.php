<?php
/**
 * CreateWalletMessage.php
 *
 * @author   Ilya Sinyakin <sinyakin.ilya@gmail.com>
 */

namespace SunTechSoft\Blockchain\Message\Wallets\Transaction;

final class CreateWalletMessage extends AbstractMessage
{
    const MESSAGE_ID = 1; //@todo rethink and move to common class with constants
    private $publicKey;

    public function __construct($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    public function createMessageForSignature()
    {
        $this->payloadLength = 106;

        $msg = '';
        $msg .= $this->getPackedHeader();
        $msg .= pack('V', $this->payloadLength);
        $msg .= \Sodium\hex2bin($this->publicKey);

        return $msg;
    }

    public function getBody()
    {
        return [
            'pub_key' => $this->publicKey,
        ];
    }


    public function getMessageId()
    {
        return self::MESSAGE_ID;
    }
}
