えぇこのプラグインの最後のバグが完全に死角にあった簡単なミスでそれを指摘していただいた時に自分でもバカらしくなって体の中が大草原になっていたIRSです。

# Bank Plugin API

Q&A(仮)

Q.制作動機は？
A.代行してる鯖の主がこれと同じようなプラグイン見つけたけどBukkitだったから。とりあえずPMMP版+α作ろうかなーと

Q.予想DL数は？
A.期待せず

Q.このプラグインの機能は？
A.経験値とお金(Economy or PocketMoney)を預けられる銀行を追加する。
APIとしても使えるので、他プラグインと連携して口座引き落としとかもできたりする。
経験値は1レベル単位。

Q.OP用の管理コマンドは？
A.つけんの面倒だった。アドオン程度に作るなら簡単なので是非作ってみてね。

Q.コマンドは？
A./bankコマンドを追加。

/bank money a <金額> で<金額>を口座に放り込む。
/bank money r <金額>で口座から<金額>を引っ張り出す。
/bank money n で残高確認。

moneyをexpに置き換えると経験値版ATMできるよ。

Q.API詳しく教えるんだよあくしろよ
```php :APIコード
  ##APIコード##
//$this->bankAPIはこのプラグインとしてね

$this->bankAPI->addMoneyBank("プレイヤー名",値段);//プレイヤー名の口座に値段を追加

if($this->bankAPI->removeMoneyBank("プレイヤー名",値段)){//プレイヤー名の口座から値段を引き落とし
#引き落とし成功
}else{
#引き落とし失敗するとfalseが戻る
}

$this->bankAPI->setMoneyBank("プレイヤー名",値段);//プレイヤー名の口座の残高を値段に設定

$this->bankAPI->nowMoneyBank("プレイヤー名");//プレイヤー名の残高を確認(残高が数字単体で戻ってくる)

#MoneyをExpに書き換えると経験値版銀行になります。(建前)
#これ以上書くのダルイ。(本音)
```

こんな感じ。うまく使えば面白いもんできるかも(())
ｶﾞﾝ( ﾟдﾟ)ｶﾞﾚ


Q.API関数名なんか変じゃね？
A.適当だから気にしたら、な？(威圧)

Q.Expのコードも書けよ
A.(＾ω＾)ﾆｺﾆｺ(威圧)

 ## ライセンス
自分用以外の改造、改造配布、二次配布、製作者偽りは断じて禁止です。
このプラグインの著作権はgamesukimanIRSに帰属します
また、製作者はこのプラグインを使用して発生した問題の責任は負い兼ねます。
製作者が使用禁止を発表した場合、直ちにこのプラグインを抜き、破棄して下さい。
ライセンスを守れない方のこのプラグインの使用は禁止します。
僕にプラグインについての知識をご教授くださった皆様にここで感謝申し上げます。

## アップデート情報
2017/10/18 - 1.0.0 - 4種のバージョンを公開

## 4種のバージョンについて
このプラグインは各API(色んな意味)に対応しております。

・bankplugin-v1_0_0- v1-1 旧API(bool無)のEconomyバージョン  
[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/gamesukimanIRS/BankPluginAPI/bankpluginv1-1)](https://poggit.pmmp.io/ci/gamesukimanIRS/BankPluginAPI/bankpluginv1-1)  
・bankplugin-v1_0_0- v1-2 旧API(bool無)のPocketMoneyバージョン  
[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/gamesukimanIRS/BankPluginAPI/bankpluginv1-2)](https://poggit.pmmp.io/ci/gamesukimanIRS/BankPluginAPI/bankpluginv1-2)  
・bankplugin-v1_0_0- v2-1 新API(bool有)のEconomyバージョン  
[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/gamesukimanIRS/BankPluginAPI/bankplugin(NewApiv2-1)](https://poggit.pmmp.io/ci/gamesukimanIRS/BankPluginAPI/bankplugin(NewApi)v2-1)  
・bankplugin-v1_0_0- v2-2 新API(bool有)のPocketMoneyバージョン  
[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/gamesukimanIRS/BankPluginAPI/bankplugin(NewApiv2-2)](https://poggit.pmmp.io/ci/gamesukimanIRS/BankPluginAPI/bankplugin(NewApi)v2-2)  

の4種類がありますが、今後日本の開発者様の経済API対応バージョンも作成する予定です。  


[ダウンロード(共有フォルダ形式)](https://www.dropbox.com/sh/iac5zud4tupi321/AAASFHA4yiFWSw0-plZdPwfda?dl=0)
