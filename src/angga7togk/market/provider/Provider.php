<?php 

namespace angga7togk\market\provider;

interface Provider {

	public function __construct(String $name);

	public function getName(): String;

	public function myMoney(String $playerName):int;

	public function setMoney(String $playerName, int $money):void;

	public function addMoney(String $playerName, int $money):void;

	public function reduceMoney(String $playerName, int $money):void;
	
}