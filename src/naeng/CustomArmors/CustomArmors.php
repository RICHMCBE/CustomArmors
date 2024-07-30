<?php

namespace naeng\CustomArmors;


use customiesdevs\customies\item\CustomiesItemFactory;
use pocketmine\inventory\CreativeInventory;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;

class CustomArmors extends PluginBase{

    use SingletonTrait;

    protected array $info = [];

    public function onLoad() : void{
        self::setInstance($this);
    }

    public function getInfo(int $typeId) : array{
        return $this->info[$typeId];
    }

    public function onEnable() : void{
        $factory = CustomiesItemFactory::getInstance();
        $inv = CreativeInventory::getInstance();
        foreach((new Config(Path::join($this->getServer()->getDataPath(), "custom_armors.yml"), Config::YAML))->getAll() as $identifier => $info){
            $this->info[$info["type_id"]] = $info;
            $factory->registerItem(CustomArmor::class, $identifier, $info["custom_name"], $info["type_id"]);
            if(isset($info["lore"])){
                $i = $factory->get($identifier);
                $inv->remove($i);
                $i->setLore($info["lore"]);
                $inv->add($i);
            }
        }

    }

}