<?php

namespace angga7togk\market\listener;

use angga7togk\market\Market;
use pocketmine\event\Listener;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\player\Player;

class MenuListener implements Listener{
	private $prefix;
	
	public function __construct(){
	   $this->prefix = Market::$prefix;
	}
	
	public function getPrefix(): string{
	   return $this->prefix;
	}
	
	public function MarketMenu(Player $player, Item $sourceItem, Item $targetItem, SlotChangeAction $action): void{
	   $chestinv = $action->getInventory();
	   $chestItem = $sourceItem;
	   if($chestItem->getCustomName() == $this->lang->getTranslate("chestmenu.exit")){
		  $chestinv->onClose($player);
	   }
	   if($chestItem->getId() == 35
		  && $chestItem->getDamage() == 5
	   ){
		  if($chestItem->hasCustomBlockData()){
			 $page = $chestItem->getCustomBlockData()->getInt("page") + 1;
			 $chestinv->clearAll();
			 $chestinv->setContents(
				$this->plugin->getMarketChest()->sendMarketMenu($page)
			 );
		  }
	   }
	   if($chestItem->getCustomName() == $this->lang->getTranslate("chestmenu.search")){
		  $chestinv->onClose($player);
		  $this->plugin->getMarketForm()->SearchId($player);
	   }
	   if($chestItem->getCustomName() == $this->lang->getTranslate("chestmenu.back")){
		  $chestinv->setContents($this->plugin->getMarketChest()->sendMarketMenu());
	   }
	   if($chestItem->hasCustomBlockData()){
		  if($chestItem->getCustomBlockData()->hasTag("id", IntTag::class)){
			  $chestinv->onClose($player);
			  $this->plugin->getMarketForm()->BuyConfirm($player, $chestItem);
		  }
	   }
	}
	
 }