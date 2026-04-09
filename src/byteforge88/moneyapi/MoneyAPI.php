<?php

declare(strict_types=1);

namespace byteforge88\moneyapi;

use pocketmine\plugin\PluginBase;

use byteforge88\moneyapi\api\Money;

use byteforge88\moneyapi\database\Database;

class MoneyAPI extends PluginBase {
    
    protected static self $instance;
    
    private Money $money;
    
    protected function onLoad() : void{
        self::$instance = $this;
        $this->money = new Money($this);
    }
    
    protected function onEnable() : void{

    }
    
    public function onDisable() : void{
        Database::getInstance()->close();
    }
    
    public static function getInstance() : self{
        return self::$instance;
    }
    
    public function isNew(Player|string $player) : bool{
        return $this->money->isNew($$player);
    }
    
    public function insertIntoDatabase(Player|string $player, string $starting_balance = 1000) : void{
        $this->money->insertIntoDatabase($player, $starting_balance);
    }
    
    public function getBalance(Player|string $player) : ?int{
        return $this->money->getBalance($player);
    }
    
    public function addMoney(Player|string $player, int $amount = 1) : void{
        $this->money->addMoney($player, $amount);
    }
    
    public function removeMoney(Player|string $player, int $amount = 1) : void{
        $this->money->removeMoney($player, $amount);
    }
    
    public function setBalance(Player|string $player, int $amount = 1) : void{
        $this->money->setBalance($player, $amount);
    }
    public function formatMoney(int $amount) : string{
        return $this->money->formatMoney($amount);
    }
}