<?php

declare(strict_types=1);

namespace byteforge88\moneyapi\command;

use pocketmine\command\Command;

use pocketmine\plugin\PluginOwned;

use byteforge88\moneyapi\MoneyAPI;

/**
 * @deprecated
 * TODO: use PM6 ugly and chopped command system
 * Perhaps we should use Commando for now?
 */
abstract class MoneyCommand extends Command implements PluginOwned {
    
    protected MoneyAPI $plugin;
    
    public function __construct(string $name, MoneyAPI $plugin) {
        parent::__construct($name);
        $this->plugin = $plugin;
        $this->usageMessage = "";
    }
    
    public function getOwningPlugin() : MoneyAPI{
        return $this->plugin;
    }
}