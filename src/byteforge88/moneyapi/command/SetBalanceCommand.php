<?php

declare(strict_types=1);

namespace byteforge88\moneyapi\command;

use pocketmine\command\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

use pocketmine\player\Player;

use byteforge88\moneyapi\MoneyAPI;

use byteforge88\moneyapi\utils\Message;

class SetBalanceCommand extends MoneyCommand {
    
    public function __construct(protected MoneyAPI $plugin) {
        parent::__construct("setbalance", $this->plugin);
        $this->setDescription("Set the balance of a player");
        $this->setAliases(["setbal"]);
        $this->setUsage("/setbalance <player> <amount>");
        $this->setPermission("moneyapi.setbalance");
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
        
        $money = Money::getInstance();
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
        
        $money->setBalance($args[0], $amount);
        
        $formatted_amount = $money->formatMoney($amount);
        
        $sender->sendMessage((string) new Message(
            "successfully-set-balance",
            ["{player}", "{amount}"],
            [$args[0], $formatted_amount]
        ));
    }
}