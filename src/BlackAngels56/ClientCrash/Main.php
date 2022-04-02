<?php

declare(strict_types=1);

namespace BlackAngels56\ClientCrash;

use BlackAngels56\ClientCrash\Command\ClientCrash;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase{

    use SingletonTrait;

    /**
     * @var array
     */
    private static array $commands;

    public function onEnable(): void
    {
        self::setInstance($this);
        $map = $this->getServer()->getCommandMap();
        self::$commands = [new ClientCrash()];
        $map->registerAll("ClientCrash", self::$commands);
    }


}
