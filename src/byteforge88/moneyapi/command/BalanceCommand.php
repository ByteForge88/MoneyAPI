<?php

declare(strict_types=1);

namespace byteforge88\moneyapi\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\moneyapi\MoneyAPI;

use byteforge88\moneyapi\database\Database;

use byteforge88\moneyapi\command\MoneyCommand;

class BalanceCommand extends MoneyCommand {
    
    public function __construct(protected MoneyAPI $plugin) {
        parent::__construct("balance", $this->plugin);
        $this->setDescription("Checkout your current balance");
        $this->setPermission("moneyapi.balance");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if (!$player instanceof Player) {
            $sender->sendMessage("use this command in-game!");
            return;
        }
        
        $balance = $this->plugin->getBalance($sender);
        
        if ($balance === null) {
            $sender->sendMessage("You don't have a balance, contact staff asap!");
            return;
        }
        
        $formatted_balance = $this->plugin->formatMoney($balance);
        
        $sender->sendMessage("You currently have {$formatted_balance}!");
    }
}