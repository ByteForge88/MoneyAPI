<?php

declare(strioct_types=1);

namespace byteforge88\moneyapi\utils;

use byteforge88\moneyapi\MoneyAPI;

class Utils {
    
    public static function getStartingBalance() : int{
        return MoneyAPI::getInstance()->getConfig()->get("starting-balance");
    }
    
    public static function getCurrencySymbol() : string{
        return MoneyAPI::getInstance()->getConfig()->get("currency-symbol");
    }
}