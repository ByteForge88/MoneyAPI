<?php

declare(strict_types=1);

namespace byteforge88\moneyapi\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

use pocketmine\player\Player;

use byteforge88\moneyapi\MoneyAPI;

use byteforge88\moneyapi\utils\Message;

class SeeBalanceCommand extends MoneyCommand {
    
    public function __construct(protected MoneyAPI $plugin) {
        parent::__construct("seebalance", $this->plugin);
        $this->setDescription("Checkout someone's balance");
        $this->setAliases(["seebal"]);
        $this->setUsage("/seebalance <player>");
        $this->setPermission("moneyapi.seebalance");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if (!$sender instanceof Player) {
            //TODO: no idea if this is necessary
            $sender->sendMessage((string) new Message("not-ingame"));
            return;
        }
        
        if (!isset($args[0])) {
            throw new InvalidCommandSyntaxException();
            return;
        }
        
        $money = MoneyAPI::getInstance();
        
        if ($money->isNew($args[0])) {
            $sender->sendMessage((string) new Message("player-not-found"));
            return;
        }
        
        $balance = $money->getBalance($args[0]);
        $formatted_balance = $money->formatMoney($balance);
        
        $sender->sendMessage((string) new Message(
            "other-balance",
            ["{player}", "{balance}"],
            [$args[0], $formatted_balance])
        );
    }
}