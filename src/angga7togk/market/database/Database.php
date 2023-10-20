<?php

namespace angga7togk\market\database;

use angga7togk\market\Market;
use pocketmine\item\Item;
use pocketmine\utils\Config;

interface Database{

	public function __construct(String $name);

	public function getName(): String;

	public function getData(): Config;
	 
	 public function getShopId(): array;
	 
	 public function getCountShop(): int;
	 
	 public function isShop(int $id): bool;
	 
	 public function createShop(int $id, Item $item): void;
	
	 public function removeShop(int $id): void;
	
	 public function setItem(int $id, Item $item): void;
	 
	 public function getItem(int $id):Item;
	 
	 public function getOwner(int $id): string;
	 
	 public function setOwner(int $id, string $ownerNew): void;
	 
	 public function getDescription(int $id): string;
	 
	 public function setDescription(int $id, string $descriptionNew): void;
	 
	 public function getPrice(int $id): int;
	 
	 public function setPrice(int $id, string $priceNew): void;
 }