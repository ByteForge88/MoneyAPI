<?php

declare(strict_types=1);

namespace byteforge88\moneyapi\database;

use SQLite3;

use pocketmine\utils\SingletonTrait;

use byteforge88\moneyapi\MoneyAPI;

class Database {
    use SingletonTrait;
    
    protected SQLite3 $sql;
    
    private function __construct() {
        $folder = MoneyAPI::getInstance()->getDataFolder() . "database/";
        
        @mkdir($folder);
        
        $this->sql = new SQLite3($folder . "database.db");
        
        $this->sql->exec("CREATE TABLE IF NOT EXISTS balances (player TEXT PRIMARY KEY, balance INT);");
    }
    
    public function close() : void{
        $this->sql->close();
    }
    
    public function getSQL() : SQLite3{
        return $this->sql;
    }
}