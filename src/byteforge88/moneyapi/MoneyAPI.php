<?php

declare(strict_types=1);

namespace byteforge88\moneyapi;

use pocketmine\plugin\PluginBase;

use pocketmine\player\Player;

use pocketmine\utils\Config;

use byteforge88\moneyapi\api\Money;

use byteforge88\moneyapi\database\Database;

use byteforge88\moneyapi\command\BalanceCommand;
use byteforge88\moneyapi\command\SeeBalanceCommand;
use byteforge88\moneyapi\command\PayMoneyCommand;
use byteforge88\moneyapi\command\AddMoneyCommand;
use byteforge88\moneyapi\command\RemoveMoneyCommand;
use byteforge88\moneyapi\command\SetBalanceCommand;

class MoneyAPI extends PluginBase {
    
    protected static self $instance;
    
    private Money $money;
    
    public Config $messages;
    
    protected function onLoad() : void{
        self::$instance = $this;
        $this->money = new Money($this);
    }
    
    protected function onEnable() : void{
        $server = $this->getServer();
        
        $this->saveDefaultConfig();
        $this->saveResource("messages.yml");
        
        $this->messages = new Config($this->getDataFolder() . "messages.yml");
        
        $server->getPluginManager()->registerEvents(new EventListener(), $this);
        
        $server->getCommandMap()->registerAll("MoneyAPI", [
            new BalanceCommand($this),
            new SeeBalanceCommand($this),
            new PayMoneyCommand($this),
            new AddMoneyCommand($this),
            new RemoveMoneyCommand($this),
            new SetBalanceCommand($this)
        ]);
    }
    
    public function onDisable() : void{
        Database::getInstance()->close();
    }
    
    public static function getInstance() : self{
        return self::$instance;
    }
    
    public function isNew(Player|string $player) : bool{
        return $this->money->isNew($player);
    }
    
    public function insertIntoDatabase(Player|string $player, int $starting_balance = 1000) : void{
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