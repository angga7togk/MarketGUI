<?php

namespace angga7togk\market\utils;

use angga7togk\market\database\Database;
use angga7togk\market\Market;
use angga7togk\market\menu\MarketChest;
use angga7togk\market\menu\MarketForm;
use angga7togk\market\provider\Provider;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;

class MarketUtils {
	private Database $database;
	private MarketChest $marketChest;
	private MarketForm $marketForm;
	private Provider $provider;
	public function __construct() {
		$this->database = Market::getDatabase();
		$this->provider = Market::getProvider();
		$this->marketChest = new MarketChest();
		$this->marketForm = new MarketForm();
	}

	public function getDatabase(): Database{
		return $this->database;
	}

	public function getProvider(): Provider{
		return $this->provider;
	}

	public function getMarketChest(): MarketChest{
		return $this->marketChest;
	}

	public function getMarketForm(): MarketForm{
		return $this->marketForm;
	}

	public function getShopId(): array{
		$array = $this->getDatabase()->getShopId();
		arsort($array);
		return $array;
	 }
	 
	 public function getCountShop(): int{
		return $this->getDatabase()->getCountShop();
	 }
	 
	 public function isShop(int $id): bool{
		return $this->getDatabase()->isShop($id);
	 }
	 
	 public function createShop(string $owner, string $description, int $price, Item $item): void{
		$id = $this->getCountShop() + 1;
		for($i = 0; $i < $this->getCountShop(); $i++){
		   if($this->isShop($id)){
			  $id++;
		   }
		}
		$tag = new CompoundTag();
		$tag->setInt("id", $id);
		$tag->setString("owner", $owner);
		$tag->setString("description", $description);
		$tag->setInt("price", $price);
		$item->setCustomBlockData($tag);
		$this->getDatabase()->createShop($id, $item);
	 }
	 
	 public function removeShop(int $id): void{
		$this->getDatabase()->removeShop($id);
	 }
	 
	 public function getShopOwner(int $id): string{
		return $this->getDatabase()->getOwner($id);
	 }
	 
	 public function setShopOwner(int $id, string $ownerNew): void{
		$this->getDatabase()->setOwner($id, $ownerNew);
	 }
	 
	 public function getShopDescription(int $id): string{
		return $this->getDatabase()->getDescription($id);
	 }
	 
	 public function setShopDescription(int $id, string $descriptionNew): void{
		$this->getDatabase()->setDescription($id, $descriptionNew);
	 }
	 
	 public function getShopPrice(int $id): int{
		return $this->getDatabase()->getPrice($id);
	 }
	 
	 public function setShopPrice(int $id, string $priceNew): void{
		$this->getDatabase()->setPrice($id, $priceNew);
	 }
	 
	 public function getShopItem(int $id): Item{
		return $this->getDatabase()->getItem($id);
	 }
}