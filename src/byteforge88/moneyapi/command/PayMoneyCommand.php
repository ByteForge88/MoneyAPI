<?php

declare(strict_types=1);

namespace byteforge88\moneyapi\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

use pocketmine\player\Player;

use pocketmine\Server;

use byteforge88\moneyapi\MoneyAPI;

use byteforge88\moneyapi\utils\Message;

class PayMoneyCommand extends MoneyCommand {
    
    public function __construct(protected MoneyAPI $plugin) {
        parent::__construct("pay", $this->plugin);
        $this->setDescription("Pay someone money from your balance");
        $this->setAliases(["paymoney"]);
        $this->setUsage("/pay <player> <amount>");
        $this->setPermission("moneyapi.pay");
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
        
        if ($money->isNew($args[0])) {
            $sender->sendMessage((string) new Message("player-not-found"));
            return;
        }
        
        $amount = (int) $args[1];
        
        if (!is_numeric($amount)) {
            $sender->sendMessage((string) new Message("amount-must-be-numeric"));
            return;
        }
        
        if ($amount <= 0) {
            $sender->sendMessage((string) new Message("amount-must-be-positive"));
            return;
        }
        
        $user_balance = $money->getBalance($sender);
        
        if ($user_balance < $amount) {
            $sender->sendMessage((string) new Message("user-is-broke"));
            return;
        }
        
        $money->removeMoney($sender, $amount);
        $money->addMoney($args[0], $amount);
        
        $formatted_amount = $money->formatMoney($amount);
        
        $sender->sendMessage((string) new Message(
            "successfully-paid-player",
            ["{player}", "{amount}"],
            [$sender->getName(), $formatted_amount]
        ));
        
        $player = Server::getInstance()->getPlayerExact($args[0]);
        
        if ($player !== null) {
            $player->sendMessage((string) new Message(
                "successfully-recieved-money",
                ["{player}", "{amount}"],
                [$player->getName(), $formatted_amount]
            ));
        }
    }
}