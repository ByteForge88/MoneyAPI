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
        $this->setDescription("");
        $this->setPermission("");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        
    }
}