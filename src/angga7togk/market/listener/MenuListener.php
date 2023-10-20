<?php

namespace angga7togk\market\listener;

use angga7togk\market\Market;
use angga7togk\market\utils\MarketUtils;
use pocketmine\event\Listener;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\nbt\tag\IntTag;
use pocketmine\player\Player;

class MenuListener implements Listener
{
    private $prefix;
	private MarketUtils $utils;

    public function __construct()
    {
        $this->prefix = Market::$prefix;
		$this->utils = new MarketUtils();
    }

	public function getUtils(): MarketUtils{
		return $this->utils;
	}

    public function getPrefix(): String
    {
        return $this->prefix;
    }

    public function MarketMenu(Player $player, Item $chestItem, Item $targetItem, SlotChangeAction $action): void
    {
        $chestinv = $action->getInventory();
        if ($chestItem->getTypeId() == StringToItemParser::getInstance()->parse("red_wool")->getTypeId()) {
            $chestinv->onClose($player);
        }
        if ($chestItem->getTypeId() == StringToItemParser::getInstance()->parse("lime_wool")->getTypeId()) {
            if ($chestItem->hasCustomBlockData()) {
                $page = $chestItem->getCustomBlockData()->getInt('page') + 1;
                $chestinv->clearAll();
                $chestinv->setContents($this->getUtils()->getMarketChest()->sendMarketMenu($page));
            }
        }
        if ($chestItem->getTypeId() == StringToItemParser::getInstance()->parse("gray_wool")->getTypeId()) {
            if($chestinv->onClose($player)){
				$this->getUtils()->getMarketForm()->SearchId($player);
			}
        }

        if ($chestItem->getTypeId() == StringToItemParser::getInstance()->parse("yellow_wool")->getTypeId()) {
            $chestinv->setContents($this->getUtils()->getMarketChest()->sendMarketMenu());
        }

        if ($chestItem->hasCustomBlockData()) {
            if ($chestItem->getCustomBlockData()->getCompoundTag("id") !== null) {
                $chestinv->onClose($player);
                $this->getUtils()->getMarketForm()->BuyConfirm($player, $chestItem);
            }
        }
    }
}
