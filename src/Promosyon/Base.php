<?php

/*
*
*
*
*         Eklenti EfePolat7749 Tarafından Kodlanmıştır
*               Editlenip Satılması Kesinlikle YASAKTIR!
*                      Discord: ! EfePolat#4776
*                   
*
*
*
*/

namespace Promosyon;

use pocketmine\plugin\PluginBase;
use pocketmine\{Player, Server};
use FormAPI\{SimpleForm, CustomForm};
use pocketmine\command\{Command, CommandSender};
use pocketmine\utils\Config;
use pocketmine\item\Item;
use onebone\economyapi\EconomyAPI;

class Base extends Command{


    public $mesaj = "";

         public function onEnable(){
		@mkdir($this->getDataFolder()."Promosyon/");
		@mkdir($this->getDataFolder()."PromosyonOyuncular/");
           $this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
       public function onCommand(CommandSender $o, Command $command, string $label, array $args): bool{
        if($command->getName() == "promosyon"){
		$this->promosyon($o);
         }
    return true;
}
    public function promoForm(Player $o){
        $f = new SimpleForm(function (Player $o, $args) {
            if ($args === null) {
                return true;
            }
         if($args[0] == 0){
           $this->promosyonKullan($o);
          }
        });
        $f->setTitle("Promosyon");
        $f->setContent("Merhaba, Promosyon Kodunmu Var? Seç!");
        $f->addButton("Promosyon Kullan");
        $f->sendToPlayer($o);
    }

public function promosyonKullan(Player $o){
        $f = new CustomForm(function (Player $o, $args) {
            if ($args === null) {
                return true;
            }
          if (file_exists($this->getDataFolder() . "Promosyon/" . $args[1] . ".yml")) {
                $promosyon = new Config($this->getDataFolder() . "Promosyon/" . $args[1] . ".yml", Config::YAML);
                $promosyonoyuncular = new Config($this->getDataFolder() . "PromosyonOyuncular/" . $o->getName() . ".yml", Config::YAML);
                if ($promosyon->get("Kullanım-Hakkı") !== 0) {
                    if (!($promosyonoyuncular->get("$args[1]"))) {
                        $promosyon->set("Kullanım-Hakkı", $promosyon->get("Kullanım-Hakkı") - 1);
                        $promosyon->save();
                        $para = $promosyon->get("Ödül");
                         EconomyAPI::getInstance()->addMoney($o, $para);
                        $o->sendMessage("§6$args[1] §aAdlı Promosyon Kodunuz Başarıyla Kullanıldı");
                        $promosyonoyuncular = new Config($this->getDataFolder() . "PromosyonOyuncular/" . $o->getName() . ".yml", Config::YAML);
                        $promosyonoyuncular->set("$args[1]");
                        $promosyonoyuncular->save();
                    } else {
                        $o->sendMessage("§cBu Promosyon Kodunu Zaten Kullanmışsın!");
                    }
                } else {
                    $o->sendMessage("§cBu Promosyon Kodunun Kullanım Hakkı Bitmiş!");
                }
            } else {
                $o->sendMessage("§c$args[1] şeklinde promosyon kodu Bulamadik!");
            }
        });
        $f->setTitle("Promosyon");
        $f->addLabel("Merhaba, Spawner Kodunmu Var? Para Kodunmu Var? Seç!");
        $f->addInput("Promosyon Kodu Gir");
        $f->sendToPlayer($o);
    }
    public function promoadminForm(Player $o){
        $f = new CustomForm(function (Player $o, $args) {
            if ($args === null) {
                return true;
            }
            var_dump($args);
            if ($args[1] == 0) {
                $this->proolustur($o);
            }
              if ($args[1] == 1) {
                $this->kodSorgula($o);
            }
        
        });
        $f->setTitle("Promosyon Admin Bölümü");
        $f->addLabel("");
        $f->addDropdown("", ["Promosyon Olustur", "Kod Sorgula"]);
        $f->sendToPlayer($o);
    }
    public function proolustur(Player $o){
        $f = new CustomForm(function (Player $o, $args) {
            if ($args === null) {
                return true;
            }
            $promosyon = new Config($this->getDataFolder() . "Promosyon/" . $args[1] . ".yml", Config::YAML);
            $promosyon->set("Ödül", $args[2]);
            $promosyon->set("Kullanım-Hakkı", $args[3]);
            $promosyon->save();
            $o->sendMessage("§aPromosyon Kodunuz Oluşturuldu!\n§6Kodunuz: §c$args[1]");
        });
        $f->setTitle("Promosyon Oluşturma Bölümü");
        $f->addLabel("");
        $f->addInput("Olusturmak İstediğiniz Promosyon Kodunu Giriniz", "");
        $f->addInput("Promosyon Odulu Giriniz ÖRN: 50000 TL", "");
        $f->addInput("Kullanım Hakkı Yazın.");
        $f->sendToPlayer($o);
    }
 public function kodSorgula(Player $o){
        $f = new CustomForm(function (Player $o, $args) {
            if ($args === null) {
                return true;
            }
            $promosyon = new Config($this ->getDataFolder() . "Promosyon/" . $args[1] . ".yml", Config::YAML);
           if(file_exists($this->getDataFolder() . "Promosyon/" . $args[1] . ".yml")) {
             $promosyon = new Config($this->getDataFolder() . "Promosyon/" . $args[1] . ".yml", Config::YAML);
             $hak = $promosyon->get("Kullanım-Hakkı");
             $odul = $promosyon->get("Ödül");
             $o->sendMessage("§aPromosyon Kodunun Kullanım Hakkı: §6 $hak\n§aPromosyon Kodunun Ödülü: §6$odulTL");
            }
        });
        $f->setTitle("Promosyon Sorgulama Bölümü");
        $f->addLabel("");
        $f->addInput("Sorgulamak İstediğiniz kod giriniz", "");
        $f->sendToPlayer($o);
    }
}
