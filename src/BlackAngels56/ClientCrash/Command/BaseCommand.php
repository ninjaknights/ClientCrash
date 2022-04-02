<?php


namespace BlackAngels56\ClientCrash\Command;


use BlackAngels56\ClientCrash\Main;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;

abstract class BaseCommand extends Command implements PluginOwned
{
    use PluginOwnedTrait;

    public function __construct(string $name, string $description = "", string $usageMessage = null, $aliases = [])
    {
        $this->owningPlugin = Main::getInstance();
        $this->setPermission($this->getPerms($name));
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    private function getPerms(string $name): string
    {
        return "njk." .  strtolower($name);
    }

}