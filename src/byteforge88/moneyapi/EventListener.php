<?php

declare(strict_types=1);

namespace byteforge88\moneyapi;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {
    
    public function onLogin(PlayerLoginEvent $event) : void{
        $player = $event->getPlayer();
        $money = MoneyAPI::getInstance();
        
        if ($money->isNew($player)) {
            $money->insertIntoDatabase($player, Utils::getStartingBalance());
        }
    }
    
    public function onJoin(PlayerJoinEvent $event) : void{
        //TODO: something...
    }
    
    public function onQuit(PlayerQuitEvent $event) : void{
        //TODO: something...
    }
}