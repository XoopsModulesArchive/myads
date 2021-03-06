// ------------------------------------------------------------------------- //
//               E-Xoops: Content Management for the Masses                  //
//                       < http://www.e-xoops.com >                          //
// ------------------------------------------------------------------------- //
// Original Author: Pascal Le Boustouller
// Modifier	  : glassJAw and jojup from http://www.myxoops.de
// Author Website : pascal.e-xoops@perso-search.com
// Licence Type   : GPL
// ------------------------------------------------------------------------- //
/*

■myadsは、訪問者が投稿利用できる、掲載期限付きお知らせモジュールです。

記事は、カテゴリー分けすることが出来ます。
記事投稿は、サイトメンバー限定、管理者認証、画像投稿、価格表示等の有無が
設定変更できます。
掲載期限は基本設定で変更でき、期限が来ると記事と画像は自動削除されます。
投稿記事に対する読者の問い合わせメールが、フォームから投稿者に届きます。
管理者認証有りにした場合の認証許可のお知らせ、掲載期限切れによる記事削除の
お知らせメールが、投稿者へ自動送信されます。
記事は、管理者によって編集・削除が出来ます。


■日本語化に際して

日本語の表記上、メールに関する部分等で僅かながら本体スクリプトを
編集してあります。多言語で使用する場合は注意してください。


日本語ファイルはフリーマーケット用に設定してあるので、その他の場合は
myads/language/japanese/内にある下記ファイルの「フリーマーケット」を
書き換えてください。

blocks.php
modinfo.php
main.php（５行目）

モジュール名はmyadsになっています。
メインメニューに別の名前を入れる場合は、モジュール管理より行ってください。


■インストール

"myads"全てのファイルを、"modules"ディレクトリに入れてください。

サーバーに送ったら、下記のようにパーミッション変更します。

/images_ann/ディレクトリ：CHMOD 777　（投稿されたイメージファイルを収容）
cache/config.phpファイル：CHMOD 666　（設定ファイル）


■設定

モジュールのインストールを行ったら、管理メニューより設定を行ってください。

●一般設定
myadsモジュールの基本設定

●構成設定
記事タイプでは、例えば「売りたし」「買いたし」「募集」などを設定。
価格タイプは、価格の後に付ける注釈を設定。例えば「応談」「以上」「以下」
「一日当り」「一人当り」など。

●カテゴリー管理
MyLinks等と同様、カテゴリーを設定できます。
一般設定でカテゴリー表示順を設定値順にすると、カテゴリーの並び順を
自由に設定できます。

####################################################################
  Version 2.04 jp By Tom 2003.12.25

#### 修正・変更箇所 
--中国語のファイルを追加しました。（謝謝　liyaさん kikuchiさん）
--細かなバグをいくつか修正

####################################################################
  Version 2.03_a jp By Tom 2003.12.18

#### 修正・変更箇所 
-- MyBlocksAdminに不具合がありましたので、最新のMyBlocksAdmin0.3に差し替えました。

####################################################################
  Version 2.03 jp Beta  By Tom 	2003.12.12-17

#### 修正・変更箇所 
--resiter_globals=offに一応対応してみました。
--日本語ファイル、若干修正
--「友達にこの記事を送る」のメールの文字化けを修正
--新規に言語用の文字列を１件追加。(contact.phpに直接記述されていた分)
--GIJOEさんのmyblocksadminを導入しました。
　（GIJOEさん、ありがとうございます。）
--各管理画面のメニューを変更しました。

####################################################################
  Version 2.02 jp  By Tom 	2003.10.30

今回もまた、toshimitsuさん、ryujiさんにお世話になりました。ありがとうございます。

####アップデートについて 

すいません。今回も、テンプレート関係、若干、変更してます。注意してください。
#### 修正・変更箇所 

**** バグなど ****

・addannonces.php announces-p-f.php supprann.php で、header.phpの読み込み個所の修正。
・記事投稿（addannonces.php）にて、CSSの影響(?)でJAVAスクリプトが作動しなかった為、
　記入漏れのアラートが出なかったのを修正

**** 変更箇所 ****

・テンプレート(myads_items.html)にて、コピーライト表示が、
　コードには記述されてるが、テンプレートに反映されてなかったので、追加修正した。

**** 追加機能 ****

・管理画面（admin/index.php）に、「myads設定（一般設定）」へのリンクを追加。
・xoopsCODEの入力（投稿）が出来るようにした


######################################################################
  Version 2.0.1 jp  By Tom 	2003.10.15

marjoさん作の日本語ファイルと、
XOOPS日本サイトフォーラムにて、marjoさんとtoshimitsuさんが修正されたファイルを元に、
以下の修正・変更を加えました。

参考URL :
　http://jp.xoops.org/modules/xhnewbb/viewtopic.php?viewmode=flat&topic_id=1934&forum=11
　http://www.xoops.org/modules/mydownloads/singlefile.php?lid=458

marjoさんとtoshimitsuさんに、多大な感謝をいたします。

####アップデートについて 

・通常のアップデートで、特に問題はないと思いますが、テンプレート関係を拡張しています。
一応、旧バージョンとは互換してますが、念の為バックアップを取るなどして、注意してください。

#### 修正・変更箇所 

**** バグなど ****

・既出のバグの修正
・管理画面「カテゴリ追加」で日本語が文字化けするのを修正
・テンプレートの誤記の訂正（HTMLの表記ミス）
・「新着記事一覧」で、「画像」の表題が出てこないのを修正
・日本語ファイル内の訂正・変更
・addannonces.php で"$title" "$titre" の誤記修正 （"$title"に統一）

・すでに投稿されてる画像と同一の名前の画像を投稿すると、上書きされてしまう問題。
　（画像名に$dateをprefixで付けて逃げてます。もしかしたら不具合あるかも）

**** 変更箇所 ****

・若干、日本仕様にレイアウトの変更（テンプレート等）
・テンプレート変数の追加拡張
　（一応、旧バージョンと互換はとってますが、アップデートする時は注意してください。）

**** 追加機能 ****

・新着一覧画面(index.php)にて、価格タイプの表示
・登録画面において、登録ユーザーの場合、本名が登録してない場合、ユーザーネームが表示されるようにした。
・投稿フォームなどに、CSSのクラスを追記
・投稿内容表示画面にて、ナビバーが表示されるようにしてみた。


##################################################################
