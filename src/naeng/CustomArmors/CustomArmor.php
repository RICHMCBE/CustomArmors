<?php

namespace naeng\CustomArmors;

use customiesdevs\customies\item\component\ArmorComponent;
use customiesdevs\customies\item\component\DurabilityComponent;
use customiesdevs\customies\item\component\WearableComponent;
use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\ItemIdentifier;

class CustomArmor extends Armor implements ItemComponents{

    use ItemComponentsTrait;

    private int $defensePoint;
    private int $maxDurability;

    public function __construct(ItemIdentifier $identifier, string $name = "Unknown"){
        $info = CustomArmors::getInstance()->getInfo($identifier->getTypeId());
        $this->defensePoint = $info["defense_point"] ?? 5;
        $this->maxDurability = $info["max_durability"] ?? 300;
        $option = match($info["type"]){
            "boots"      => [ArmorInventory::SLOT_FEET, CreativeInventoryInfo::GROUP_BOOTS, WearableComponent::SLOT_ARMOR_FEET],
            "chestplate" => [ArmorInventory::SLOT_CHEST, CreativeInventoryInfo::GROUP_CHESTPLATE, WearableComponent::SLOT_ARMOR_CHEST],
            "helmet"     => [ArmorInventory::SLOT_HEAD, CreativeInventoryInfo::GROUP_HELMET, WearableComponent::SLOT_ARMOR_HEAD],
            "leggings"   => [ArmorInventory::SLOT_LEGS, CreativeInventoryInfo::GROUP_LEGGINGS, WearableComponent::SLOT_ARMOR_LEGS],
        };
        $armorInfo = new ArmorTypeInfo($this->getDefensePoints(), $this->getMaxDurability(), $option[0]);
        $inventory = new CreativeInventoryInfo(CreativeInventoryInfo::CATEGORY_EQUIPMENT, $option[1]);
        parent::__construct($identifier, $name, $armorInfo);
        $this->initComponent($info["texture"], $inventory);
        if(isset($info["render_offsets"])) {
            $this->setupRenderOffsets(($info["render_offsets"]["width"] ?? 16), ($info["render_offsets"]["height"] ?? 16), ($info["render_offsets"]["hand_equipped"] ?? false));
        }
        $this->addComponent(new ArmorComponent($this->getDefensePoints(), $info["armor_texture_type"] ?? "diamond"));
        $this->addComponent(new DurabilityComponent($this->getMaxDurability()));
        $this->addComponent(new WearableComponent($option[2]));
    }

    public function getDefensePoints() : int{
        return $this->defensePoint;
    }

    public function getMaxDurability() : int{
        return $this->maxDurability;
    }

}