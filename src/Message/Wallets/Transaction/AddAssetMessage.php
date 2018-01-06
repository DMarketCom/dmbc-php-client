<?php
/**
 * AddAssetMessage.php
 *
 * @author   Ilya Sinyakin <sinyakin.ilya@gmail.com>
 */

namespace SunTechSoft\Blockchain\Message\Wallets\Transaction;

class AddAssetMessage extends AssetMessage
{
    const MESSAGE_ID = 3; //@todo rethink and move to common class with constants

    public function getMessageId()
    {
        return self::MESSAGE_ID;
    }
}