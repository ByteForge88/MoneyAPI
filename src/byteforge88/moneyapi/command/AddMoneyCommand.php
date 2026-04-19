<?php

declare(strict_types=1);

namespace byteforge88\moneyapi\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

use pocketmine\player\Player;

use byteforge88\moneyapi\MoneyAPI;

use byteforge88\moneyapi\utils\Message;

class AddMoneyCommand extends MoneyCommand {
    
    public function __construct(protected MoneyAPI $plugin) {
        parent::__construct("addmoney", $this->plugin);
        $this->setDescription("Add money to a player's balance");
        $this->setUsage("/addmoney <player> <amount>");
        $this->setPermission("moneyapi.addmoney");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage((string) new Message("not-ingame"));
            return;
        }
        
        if (!isset($args[0]) || !isset($args[1])) {
            throw new InvalidCommandSyntaxException();
            return;
        }
        
        $money = MoneyAPI::getInstance();
        $amount = (int) $args[1];
        
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
        
        $money->addMoney($args[0], $amount);
        
        $formatted_amount = $money->formatMoney($amount);
        
        $sender->sendMessage((string) new Message(
            "successfully-added-money",
            ["{player}", "{amount}"],
            [$args[0], $formatted_amount]
        ));
    }
}