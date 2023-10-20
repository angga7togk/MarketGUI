<?php

namespace angga7togk\market\command;

use angga7togk\market\utils\MarketUtils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class MarketCommand extends Command {
	private MarketUtils $utils;
	public function __construct() {
		parent::__construct("market", "opem market gui", "", ["marketui", "marketgui"]);
		$this->setPermission("market.use");
		$this->utils = new MarketUtils();
	}

	public function execute(CommandSender $sender, string $label, array $args) : bool{
		if($sender instanceof Player){
			$this->utils->getMarketChest()->sendChest($sender);
		}
		return true;
	}
}