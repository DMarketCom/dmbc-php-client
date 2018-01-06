<?php
/**
 * dmbc-php-client
 *
 * @author Serhii Borodai <s.borodai@globalgames.net>
 */

namespace SunTechSoft\Blockchain\Message\Wallets;


class ListMessage extends AbstractMessage
{

    public function getMessageId()
    {
        return crc32(self::class);
    }

    public function createMessageForSignature()
    {
        return [];
    }

    public function getBody()
    {
        return [];
    }


    public function getMethodName()
    {
        return 'wallets';
    }

    public function isPost()
    {
        return false;
    }
}