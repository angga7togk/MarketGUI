<?php

namespace angga7togk\market\menu;

use angga7togk\market\Market;
use angga7togk\market\utils\MarketUtils;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\Server;

class MarketForm
{
    private $prefix;
    private MarketUtils $utils;

    public function __construct()
    {
        $this->utils = new MarketUtils();
        $this->prefix = Market::$prefix;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    protected function getUtils(): MarketUtils
    {
        return $this->utils;
    }

    public function SearchId(Player $player, string $content = ''): void
    {
        $form = new CustomForm(function (Player $player, $data) {
            if ($data == null) {
                return;
            }
            $id = explode(' ', $data[1]);
            if ($id[0] == null) {
                $text = '§cThe form cannot be empty!';
                $this->SearchId($player, $text);
                return;
            }
            if (!is_numeric($id[0])) {
                $text = '§cPlease enter a number!';
                $this->SearchId($player, $text);
                return;
            }
            $id = (int) $id[0];
            if (!$this->getUtils()->isShop($id)) {
                $text = "§c($id) Shop ID is not found!";
                $this->SearchId($player, $text);
                return;
            }
            $this->getUtils()
                ->getMarketChest()
                ->sendChest($player, 'search', $id);
        });
        $form->setTitle($this->getPrefix() . ' SearchId');
        $form->addLabel($content);
        $form->addInput('ShopId', '1161');
        $player->sendForm($form);
    }

    public function BuyConfirm(Player $player, Item $item): void
    {
        $shopId = $item->getCustomBlockData()->getInt('id');
        $owner = $item->getCustomBlockData()->getString('owner');
        $description = $item->getCustomBlockData()->getString('description');
        $price = $item->getCustomBlockData()->getInt('price');
        $item->setLore([]);

        $form = new SimpleForm(function (Player $player, $data) use ($shopId, $owner, $price, $item) {
            if (!isset($data) || $data == 1) {
                return;
            }
            if ($data == 0) {
				$playerName = (String) $player->getName(); 
                if ($this->getUtils()->getProvider()->myMoney($playerName) >= $price) {
                    $this->getUtils()
                        ->getProvider()
                        ->reduceMoney($playerName, $price);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage($this->getPrefix() . "§aYou have bought {$item->getName()} amount {$item->getCount()} in price $price successfully");
                    if (strtolower($item->getCustomBlockData()->getString('owner')) == strtolower($playerName)) {
                        $this->getUtils()
                            ->getProvider()
                            ->addMoney($owner, 0);
                        $price = 0;
                    } else {
                        $this->getUtils()
                            ->getProvider()
                            ->addMoney($owner, $price);
                    }
                    
                    if (Server::getInstance()->getPlayerExact($owner) !== null) {
						$ownerPlayer = Server::getInstance()->getPlayerExact($owner);
                        $ownerPlayer->sendMessage($this->getPrefix() . "§aPlayer $playerName have purchased items in id shop #$shopId of you with price $price");
                    }
                    $this->getUtils()->removeShop($shopId);
                } else {
                    $player->sendMessage($this->getPrefix() . "§cYour money is not enough");
                }
            }
        });
        $form->setTitle($this->getPrefix() . ' Buy Confirm');
        $form->setContent("§fShopId #$shopId\n§fShop owner $owner\n§fYou want to buy §b{$item->getName()}\n§famount §e{$item->getCount()} \n§fDescription $description\n§fin price §6$price §fOr not?");
        $form->addButton("§aBuy Now\n§rTap To Buy");
        $form->addButton("§cCancel\n§rTap To Exit");
        $player->sendForm($form);
    }
}
