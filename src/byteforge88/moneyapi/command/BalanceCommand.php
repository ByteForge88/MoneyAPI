<?php

declare(strict_types=1);

namespace byteforge88\moneyapi\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use byteforge88\moneyapi\MoneyAPI;

use byteforge88\moneyapi\utils\Message;

class BalanceCommand extends MoneyCommand {
    
    public function __construct(protected MoneyAPI $plugin) {
        parent::__construct("balance", $this->plugin);
        $this->setDescription("Checkout your current balance");
        $this->setAliases(["bal"]);
        $this->setPermission("moneyapi.balance");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage((string) new Message("not-ingame"));
            return;
        }
        
        $balance = $this->plugin->getBalance($sender);
        
        if ($balance === null) {
            //TODO: use Error::INVALID_BALANCE
            $sender->sendMessage("You don't have a balance, contact staff asap!");
            return;
        }
        
        $formatted_balance = $this->plugin->formatMoney($balance);
        
        $sender->sendMessage((string) new Message("user-balance", "{balance}", $formatted_balance));
    }
}