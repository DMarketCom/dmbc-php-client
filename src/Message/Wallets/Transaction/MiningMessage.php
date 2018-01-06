<?php
/**
 * CreateWalletMessage.php
 *
 * @author   Ilya Sinyakin <sinyakin.ilya@gmail.com>
 */

namespace SunTechSoft\Blockchain\Message\Wallets\Transaction;

final class MiningMessage extends AbstractMessage
{
    const MESSAGE_ID = 7; //@todo rethink and move to common class with constants
    private $publicKey;

    public function __construct($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    public function createMessageForSignature()
    {
        $this->payloadLength = 114;
        $body = $this->getBody();
        $msg = '';
        $msg .= $this->getPackedHeader();
        $msg .= pack('V', $this->payloadLength);
        $msg .= \Sodium\hex2bin($body['pub_key']);
        $msg .= pack('P', (int)$body['seed']);

        return $msg;
    }

    public function getBody()
    {
        if (is_null($this->body)) {
            $this->body = [
                'pub_key' => $this->publicKey,
                'seed' => $this->getSeed()
            ];
        }

        return $this->body;
    }

    public function getMessageId()
    {
        return self::MESSAGE_ID;
    }
}
