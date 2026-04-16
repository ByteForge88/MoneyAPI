<?php

declare(strict_types=1);

namespace byteforge88\moneyapi\api;

use pocketmine\player\Player;

use byteforge88\moneyapi\MoneyAPI;

use byteforge88\moneyapi\database\Database;

use byteforge88\moneyapi\utils\Utils;

class Money {
    
    public function __construct(protected MoneyAPI $plugin) {
        //i dunno
    }
    
    public function isNew(Player|string $player) : bool{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT * FROM balances WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);
            
            $result->finalize();
            
            return $data === false ? true : false;
        } finally {
            $stmt->close();
        }
    }
    
    public function insertIntoDatabase(Player|string $player, int $starting_balance) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("INSERT INTO balances (player, balance) VALUES (:player, :balance);");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":balance", $starting_balance, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
        }
    }
    
    public function getBalance(Player|string $player) : ?int{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("SELECT balance FROM balances WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            
            $result = $stmt->execute();
            $data = $result->fetchArray(SQLITE3_ASSOC);
            
            $result->finalize();
            
            return $data === false ? null : (int) $data["balance"];
        } finally {
            $stmt->close();
        }
    }
    
    public function addMoney(Player|string $player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE balances SET balance = balance + :amount WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
        }
    }
    
    public function removeMoney(Player|string $player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE balances SET balance = balance - :amount WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
        }
    }
    
    public function setBalance(Player|string $player, int $amount) : void{
        $player = $player instanceof Player ? $player->getName() : $player;
        $stmt = Database::getInstance()->getSQL()->prepare("UPDATE balances SET balance = :amount WHERE player = :player;");
        
        try {
            $stmt->bindValue(":player", $player, SQLITE3_TEXT);
            $stmt->bindValue(":amount", $amount, SQLITE3_INTEGER);
            
            $result = $stmt->execute();
            
            $result->finalize();
        } finally {
            $stmt->close();
        }
    }
    
    public function formatMoney(int $amount) : string{
        $str = number_format($amount);
        return Utils::getCurrencySymbol() . $str;
    }
}