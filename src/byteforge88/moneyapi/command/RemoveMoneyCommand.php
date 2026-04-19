<?php

declare(strict_types=1);

namespace byteforge88\moneyapi\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

use pocketmine\player\Player;

use byteforge88\moneyapi\MoneyAPI;

use byteforge88\moneyapi\utils\Message;

class RemoveMoneyCommand extends MoneyCommand {
    
    public function __construct(protected MoneyAPI $plugin) {
        parent::__construct("removemoney", $this->plugin);
        $this->setDescription("Remove money from a player's balance");
        $this->setUsage("/removemoney <player> <amount>");
        $this->setPermission("moneyapi.removemoney");
    }
    
    public function execute(CommandSender $sender, string $commandSender, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage((string) new Message("not-ingame"));
            return;
        }
        
        if (!isset($args[0]) || !isset($args[1])) {
            throw new InvalidCommandSyntaxException();
            return;
        }
        
        $money = MoneyAPI::getInstance();
        $amount = (int) $amount;
        
        if ($money->isNew($args[0])) {
            $sender->sendMessage((string) new Message("player-not-found"));
            return;
        }
        
        if (!is_numeric($amount)) {
            $sender->sendMessage((string) new Message("amount-must-be-numeric"));
            return;
        }
        
        if ($amount <= 0) {
            $sender->sendMessage((string) new Message("amount-must-be-positive"));
            return;
        }
        
        $target_balance = $money->getBalance($args[0]);
        
        if ($target_balance < $amount) {
            $sender->sendMessage((string) new Message("target-broke"));
            return;
        }
        
        $money->remoneyMoney($args[0], $amount);
        
        $formatted_amount = $money->formatMoney($amount);
        
        $sender->sendMessage((string) new Message(
            "successfully-removed-money",
            ["{player}", "{amount}"],
            [$args[0], $formatted_amount]
        ));
    }
}