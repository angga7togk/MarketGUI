<?php 

namespace angga7togk\market\menu;

use angga7togk\market\Market;
use angga7togk\market\utils\MarketUtils;
use angga7togk\menu\MenuListener;
use muqsit\invmenu\InvMenu;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class MarketChest{
	private MarketUtils $utils;
	private String $prefix;
	
	public function __construct(){
	   $this->utils = new MarketUtils();
	   $this->prefix = Market::$prefix;
	}
	
	protected function getUtils(): MarketUtils{
	   return $this->utils;
	}
	
	protected function getPrefix(): string{
	   return $this->prefix;
	}
	
	public function sendChest(Player $player, string $content = "menu", int $id = null): void{
	   $menu = InvMenu::create(InvMenu::TYPE_CHEST);
	   $menu->readonly();
	   $menu->setListener([Market::$eventListner, "MarketMenu"]);
	   $menu->setName($this->getPrefix());
	   $inv = $menu->getInventory();
	   switch($content){
		  case "menu":
			 $inv->setContents($this->sendMarketMenu());
			 break;
		  case "search":
			 $inv->setContents($this->sendMarketSearch($id));
			 break;
	   }
	   $menu->send($player);
	}
 
	public function makeMarketMenuPage(): array{
	   $items = [];
	   foreach($this->getUtils()->getShopId() as $id){
		  $items[] = $this->getUtils()->getShopItem($id);
	   }
	   return array_chunk($items, 18);
	}
	
	public function sendMarketMenu(int $page = 0): array{
	   if(!isset($this->makeMarketMenuPage()[$page])){
		  $arrayNull = [
			 25 => StringToItemParser::getInstance()->parse("yellow_wool")->setCustomName("§bBack (".($page + 1)."/".count($this->makeMarketMenuPage()).")")->setCount(1)
			 ,
			 26 => StringToItemParser::getInstance()->parse("red_wool")->setCustomName("§cExit")->setCount(1)
		  ];
		  for($i = 18; $i < 25; $i++){
			 $arrayNull[$i] = StringToItemParser::getInstance()->parse("glass")->setCustomName("---")->setCount(1);
		  }
		  return $arrayNull;
	   }
	   $itemNext = StringToItemParser::getInstance()->parse("lime_wool")->setCustomName("§bBack (".($page + 1)."/".count($this->makeMarketMenuPage()).")")->setCount(1);
	   $tag1 = new CompoundTag();
	   $tag1->setInt("page", $page);
	   $itemNext->setCustomBlockData($tag1);
	   $array = [
		  23 => StringToItemParser::getInstance()->parse("gray_wool")->setCustomName("§eSearch")->setCount(1),
		  24 => StringToItemParser::getInstance()->parse("yellow_wool")->setCustomName("§bBack (".($page + 1)."/".count($this->makeMarketMenuPage()).")")->setCount(1)
		  ,
		  25 => StringToItemParser::getInstance()->parse("lime_wool")->setCustomName("§bNext (".($page + 1)."/".count($this->makeMarketMenuPage()).")")->setCount(1)
		  ,
		  26 => StringToItemParser::getInstance()->parse("red_wool")->setCustomName("§cExit")->setCount(1)
	   ];
	   for($i = 18; $i < 23; $i++){
			$arrayNull[$i] = StringToItemParser::getInstance()->parse("glass")->setCustomName("---")->setCount(1);
	   }
	   $i = 0;
	   if($this->getUtils()->getCountShop() !== 0){
		  foreach($this->makeMarketMenuPage()[$page] as $item){
			 $id = $item->getCustomBlockData()->getInt("id");
			 $owner = $item->getCustomBlockData()->getString("owner");
			 $description = $item->getCustomBlockData()->getString("description");
			 $price = $item->getCustomBlockData()->getInt("price");
			 $lore = [
				"text1" => "ShopId #$id\nOwner $owner\nDescription %$description\nPrice %$price"
			 ];
			 $array[$i] = $item->setLore($lore);
			 $i++;
		  }
	   }
	   return $array;
	}
 
	public function sendMarketSearch(int $id): array{
		$array = [
			25 => StringToItemParser::getInstance()->parse("yellow_wool")->setCustomName("§bBack")->setCount(1)
			,
			26 => StringToItemParser::getInstance()->parse("red_wool")->setCustomName("§cExit")->setCount(1)
		 ];
	   for($i = 18; $i < 25; $i++){
		  $array[$i] = StringToItemParser::getInstance()->parse("glass")->setCustomName("---")->setCount(1);
	   }
	   $item = $this->getUtils()->getShopItem($id);
	   $id = $item->getCustomBlockData()->getInt("id");
	   $owner = $item->getCustomBlockData()->getString("owner");
	   $description = $item->getCustomBlockData()->getString("description");
	   $price = $item->getCustomBlockData()->getInt("price");
	   $lore = [
		  "text1" => "ShopId #$id\nOwner $owner\nDescription %$description\nPrice %$price"
	   ];
	   $array[0] = $item->setLore($lore);
	   return $array;
	}
	
 }