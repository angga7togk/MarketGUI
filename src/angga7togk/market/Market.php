<?php

namespace angga7togk\market;

use angga7togk\market\database\Database;
use angga7togk\market\database\YAML;
use angga7togk\market\listener\MenuListener;
use angga7togk\market\provider\EconomyAPI;
use angga7togk\market\provider\Provider;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Market extends PluginBase {
	public static Market $instance;
	public static Config $cfg;
	public static MenuListener $eventListner;
	public static String $prefix;

	public Database $database;
	public Provider $provider;

	public static String $databaseName;
	public static String $providerName;

	public function onLoad():void{
		self::$instance = $this;
	}

	public function onEnable():void {
		$this->saveResource("data.yml");
		$this->saveResource("config.yml");

		self::$cfg = new Config($this->getDataFolder() ."config.yml", Config::YAML, array());
		self::$eventListner = new MenuListener();
		self::$prefix = self::$cfg->getNested("prefix");
		self::$databaseName = self::$cfg->getNested("database");
		self::$providerName = self::$cfg->getNested("provider");

		$this->setDatabase();
		$this->setProvider();

		$this->getServer()->getPluginManager()->registerEvents(new MenuListener(), $this);
	}

	public static function getDatabase():Database{
		return self::$database;
	}

	public static function getProvider(): Provider{
		return self::$provider;
	}

	protected function setDatabase(){
		if(self::$databaseName == "YAML"){
			self::$database = new YAML(self::$databaseName);
		}
	}

	protected function setProvider(){
		if(self::$providerName == "EconomyAPI"){
			self::$provider = new EconomyAPI(self::$providerName);
		}
	}
}