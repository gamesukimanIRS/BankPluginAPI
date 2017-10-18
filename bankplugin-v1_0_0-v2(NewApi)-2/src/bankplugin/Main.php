<?php

namespace bankplugin;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\entity\Attribute;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

	public function onEnable(){
		$PluginName = "bankplugin";
		$version = "1.0.0";
		$this->getlogger()->info($PluginName." v".$version."を読み込みました。作者:gamesukimanIRS");
    	$this->getlogger()->warning("製作者偽りと二次配布、改造、改造配布はおやめ下さい。");
    	$this->getlogger()->info("このプラグインを使用する際はどこかにプラグイン名「".$PluginName."」と作者名「gamesukimanIRS」を記載する事を推奨します。");

    	#システム
    	$this->getServer()->getPluginManager()->registerEvents($this,$this);
    	if($this->getServer()->getPluginManager()->getPlugin("PocketMoney") != null){
			$this->PocketMoney = $this->getServer()->getPluginManager()->getPlugin("PocketMoney");
			$this->getLogger()->notice("PocketMoneyを検出しました。");
		}else{
			$this->getLogger()->error("PocketMoneyが見つかりませんでした");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
		if(!file_exists($this->getDataFolder())){ 
          mkdir($this->getDataFolder(), 0756, true); 
       }
       $this->money = new Config($this->getDataFolder() . "bankmoney.yml", Config::YAML);
       $this->exp = new Config($this->getDataFolder() . "bankexp.yml", Config::YAML);
	}

	public function PJE(PlayerJoinEvent $event){
		$name = $event->getPlayer()->getName();
		if($this->momey->exists($name)){
			$this->money->set($name, 0);
			$this->money->save();
		}
		if($this->exp->exists($name)){
			$this->exp->set($name, 0);
			$this->exp->save();
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
		if($command->getName() == "bank"){
			if($sender instanceof Player){
				if(isset($args[0])){
					switch ($args[0]) {
						case 'money':
							$name = $sender->getName();
							$money = $this->PocketMoney->getMoney($name);
							if(isset($args[1])){
								switch ($args[1]) {
									case 'a':
										if(isset($args[2])){
											$price = intval($args[2]);
											if($money >= $price){
												$this->addMoneyBank($name, $price);
												$newmoney = $this->nowMoneyBank($name);
												$price = $money - $price;
												$this->PocketMoney->setMoney($name, $price);
												$sender->sendMessage("¥".$price."預けました。預金額が¥".$newmoney."に増えました。");
												return true;
											}else{
												$sender->sendMessage("[bank]§c所持金が足りません。");
												return true;
											}
										}else{
											$sender->sendMessage("[bank]§c預ける金額を入力して下さい。");
											return true;
										}
										break;

									case 'r':
										if(isset($args[2])){
											$price = intval($args[2]);
											if($this->removeMoneyBank($name, $price)){
												$newmoney = $this->nowMoneyBank($name);
												$this->PocketMoney->grantMoney($name, +$price);
												$sender->sendMessage("¥".$price."引き出しました。預金額が¥".$newmoney."に減りました。");
												return true;
											}else{
												$sender->sendMessage("[bank]§c預金額が足りません。");
												return true;
											}
										}else{
											$sender->sendMessage("[bank]§c引き出す金額を入力して下さい。");
											return true;
										}
										break;

									case 'n':
										$nowmoney = $this->nowMoneyBank($name);
										$sender->sendMessage("ただいまの預金額は¥".$nowmoney."です。");
										return true;
										break;
									
									default:
										$sender->sendMessage("§bbankplugin §a使用方法");
										$sender->sendMessage("§b/bank money a <金額> §a銀行に<金額>円預けます");
										$sender->sendMessage("§b/bank money r <金額> §a銀行から<金額>円引き出します");
										$sender->sendMessage("§b/bank money n §a銀行に預けてる金額を確認します");
										return true;
										break;
								}
							}else{
								$sender->sendMessage("§bbankplugin §a使用方法");
								$sender->sendMessage("§b/bank money a <金額> §a銀行に<金額>円預けます");
								$sender->sendMessage("§b/bank money r <金額> §a銀行から<金額>円引き出します");
								$sender->sendMessage("§b/bank money n §a銀行に預けてる金額を確認します");
								return true;
							}
							break;

						case 'exp':
							$name = $sender->getName();
							$meexp = intval($sender->getAttributeMap()->getAttribute(Attribute::EXPERIENCE_LEVEL)->getValue());
							if(isset($args[1])){
								switch ($args[1]) {
									case 'a':
										if(isset($args[2])){
											$exp = intval($args[2]);
											if($meexp >= $exp){
												$this->addExpBank($name, $price);
												$newexp = $this->nowExpBank($name);
												$newmeexp = $meexp - $exp;
												$sender->getAttributeMap()->getAttribute(Attribute::EXPERIENCE_LEVEL)->setValue($newmeexp);
												$sender->sendTip(" ");
												$sender->sendMessage($exp."EXP預けました。預EXPが".$newexp."EXPに増えました。");
												return true;
											}else{
												$sender->sendMessage("[bank]§c所持EXPが足りません。");
												return true;
											}
										}else{
											$sender->sendMessage("[bank]§c預けるEXPを入力して下さい。");
											return true;
										}
										break;

									case 'r':
										if(isset($args[2])){
											$exp = intval($args[2]);
											if($this->removeExpBank($name, $exp)){
												$newexp = $this->nowExpBank($name);
												$newmeexp = $meexp + $exp;
												$sender->getAttributeMap()->getAttribute(Attribute::EXPERIENCE_LEVEL)->setValue($newmeexp);
												$sender->sendTip(" ");
												$sender->sendMessage($exp."EXP引き出しました。預EXPが".$newexp."EXPに減りました。");
												return true;
											}else{
												$sender->sendMessage("[bank]§c預EXPが足りません。");
												return true;
											}
										}else{
											$sender->sendMessage("[bank]§c引き出すEXPを入力して下さい。");
											return true;
										}
										break;

									case 'n':
										$nowexp = $this->nowExpBank($name);
										$sender->sendMessage("ただいまの預EXPは".$nowexp."EXPです。");
										return true;
										break;
									
									default:
										$sender->sendMessage("§bbankplugin §a使用方法");
										$sender->sendMessage("§b/bank exp a <EXP> §a銀行に<EXP>EXP預けます");
										$sender->sendMessage("§b/bank exp r <EXP> §a銀行から<EXP>EXP引き出します");
										$sender->sendMessage("§b/bank exp n §a銀行に預けてるEXPを確認します");
										return true;
										break;
								}
							}else{
								$sender->sendMessage("§bbankplugin §a使用方法");
								$sender->sendMessage("§b/bank exp a <金額> §a銀行に<金額>円預けます");
								$sender->sendMessage("§b/bank exp r <金額> §a銀行から<金額>円引き出します");
								$sender->sendMessage("§b/bank exp n §a銀行に預けてる金額を確認します");
								return true;
							}
							break;
						
						default:
							$sender->sendMessage("§bbankplugin §a使用方法");
							$sender->sendMessage("§b/bank money §a銀行とお金のやり取りを行います。");
							$sender->sendMessage("§b/bank exp §a銀行と経験値のやり取りを行います");
							return true;
							break;
					}
				}else{
					$sender->sendMessage("§bbankplugin §a使用方法");
					$sender->sendMessage("§b/bank money §a銀行とお金のやり取りを行います。");
					$sender->sendMessage("§b/bank exp §a銀行と経験値のやり取りを行います");
					return true;
				}
			}else{
				$sender->sendMessage("§b[bank]サーバー内でお願い致します。");
				return true;
			}
		}
	}

	public function addMoneyBank($name, $price){
		$price = intval($price);
		$nowmoney = intval($this->money->get($name));
		$newmoney = $nowmoney + $price;
		$this->money->set($name, $newmoney);
		$this->money->save();
		return true;
	}

	public function removeMoneyBank($name, $price){
		$price = intval($price);
		$nowmoney = intval($this->money->get($name));
		if($nowmoney >= $price){
			$newmoney = $nowmoney - $price;
			$this->money->set($name, $newmoney);
			$this->money->save();
			return true;
		}else{
			return false;
		}
	}

	public function nowMoneyBank($name){
		$nowmoney = intval($this->money->get($name));;
		return $nowmoney;
	}

	public function setMoneyBank($name, $price){
		$price = intval($price);
		$this->money->set($name, $price);
		$this->money->save();
		return true;
	}

	public function addExpBank($name, $exp){
		$exp = intval($exp);
		$nowexp = intval($this->exp->get($name));
		$newexp = $nowexp + $exp;
		$this->money->set($name, $newmoney);
		$this->money->save();
		return true;
	}

	public function removeExpBank($name, $exp){
		$price = intval($exp);
		$nowexp = intval($this->exp->get($name));
		if($nowexp >= $exp){
			$newexp = $nowexp - $exp;
			$this->exp->set($name, $newexp);
			$this->exp->save();
			return true;
		}else{
			return false;
		}
	}

	public function nowExpBank($name){
		$nowexp = intval($this->exp->get($name));;
		return $nowexp;
	}

	public function setExpBank($name, $exp){
		$exp = intval($exp);
		$this->exp->set($name, $exp);
		$this->exp->save();
		return true;
	}
}