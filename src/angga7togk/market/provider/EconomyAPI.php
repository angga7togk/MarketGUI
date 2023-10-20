<?php

namespace angga7togk\market\provider;

use onebone\economyapi\EconomyAPI as EconomyapiEconomyAPI;

class EconomyAPI implements Provider{

	private String $name;
	private EconomyapiEconomyAPI $eco;

	public function __construct(String $name){
		$this->name = $name;
		$this->eco = EconomyapiEconomyAPI::getInstance();
	}

	public function getName():String{
		return $this->name;
	}

	public function myMoney(String $playerName):int{
		return $this->eco->myMoney($playerName);
	}

	public function setMoney(String $playerName, int $money):void{
		$this->eco->setMoney($playerName, $money);
	}

	public function addMoney(String $playerName, int $money):void{
		$this->eco->addMoney($playerName, $money);
	}
	
	public function reduceMoney(String $playerName, int $money):void{
		$this->eco->reduceMoney($playerName, $money);
	}
}