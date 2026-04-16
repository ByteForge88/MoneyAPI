<?php

declare(strict_types=1);

namespace byteforge88\moneyapi\utils;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use byteforge88\moneyapi\MoneyAPI;

class Message {

    protected string $message;

    public function __construct(string $msgKey, array|string|null $tags = null, array|string|null $replacements = null) {
        $msg = MoneyAPI::getInstance()->messages->get($msgKey);

        if ($tags !== null && $replacements !== null) {
            $tags = (array) $tags;
            $replacements = (array) $replacements;

            $msg = str_replace($tags, $replacements, $msg);
        }

        $this->message = TextFormat::colorize($msg);
    }

    public function __toString() : string{
        return $this->message;
    }
}