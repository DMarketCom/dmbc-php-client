<?php
/**
 * AddAssetMessage.php
 *
 * @author   Ilya Sinyakin <sinyakin.ilya@gmail.com>
 */

namespace SunTechSoft\Blockchain\Message;

class AddAssetMessage extends AssetMessage
{
    public function __construct($publicKey, array $assets)
    {
        parent::__construct($publicKey, $assets, 3);
    }
}