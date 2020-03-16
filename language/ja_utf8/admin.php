<?php
// $Id: admin.php,v 1.1 2011/12/29 14:41:32 ohwada Exp $

// 2006-09-01 K.OHWADA
// use RSSC lang pack

// 2006-07-02 K.OHWADA

//=========================================================
// RSSC HeadLine
// 2006-07-02 K.OHWADA
// Japanese UTF-8
//=========================================================
// based on xoopsHeadline
// Id: admin.php,v 1.4 2005/08/03 12:40:01 onokazu Exp
//=========================================================

//%%%%%%        Admin Module Name  Headlines         %%%%%
define('_AM_RSSHEADLINE_DBUPDATED', 'データベースを更新しました!');
define('_AM_RSSHEADLINE_HEADLINES', 'ヘッドライン設定');
define('_AM_RSSHEADLINE_HLMAIN', 'ヘッドラインメイン');
define('_AM_RSSHEADLINE_SITENAME', 'サイト名');
define('_AM_RSSHEADLINE_URL', 'サイトURL');
define('_AM_RSSHEADLINE_ORDER', '表示順');
define('_AM_RSSHEADLINE_ENCODING', 'RSSエンコード');
define('_AM_RSSHEADLINE_CACHETIME', 'キャッシュタイム');
define('_AM_RSSHEADLINE_MAINSETT', 'メインページの設定');
define('_AM_RSSHEADLINE_BLOCKSETT', 'ブロックの設定');
define('_AM_RSSHEADLINE_DISPLAY', 'メインページに表示');
define('_AM_RSSHEADLINE_DISPIMG', '画像を表示');
define('_AM_RSSHEADLINE_DISPFULL', 'すべてを表示');
define('_AM_RSSHEADLINE_DISPMAX', '最大表示件数');
define('_AM_RSSHEADLINE_ASBLOCK', 'ブロックに表示');
define('_AM_RSSHEADLINE_ADDHEADL', 'ヘッドラインの新規追加');
define('_AM_RSSHEADLINE_URLEDFXML', 'RDF/RSSファイルのURL');
define('_AM_RSSHEADLINE_EDITHEADL', 'ヘッドラインの編集');
define('_AM_RSSHEADLINE_WANTDEL', '本当にこのヘッドラインを削除してもよろしいですか？<br> サイト名： %s');
define('_AM_RSSHEADLINE_INVALIDID', 'IDが正しくありません');
define('_AM_RSSHEADLINE_OBJECTNG', 'オブジェクトが存在しません');
define('_AM_RSSHEADLINE_FAILUPDATE', 'ヘッドラインの保存ができませんでした<br> %s');
define('_AM_RSSHEADLINE_FAILDELETE', 'ヘッドラインの削除ができませんでした<br> %s');

// 2006-07-02 K.OHWADA
define('_AM_RSSHEADLINE_INDEX_DESC', '登録するWEBサイトが RSS Auto Discovery (自動検出) に対応している場合は、<br><b>RDF/RSSファイルのURL</b> と <b>RSSエンコード</b> を記入しなくとも、自動的に設定されます');
//define('_AM_RSSHEADLINE_RSSC_LINK_RSS_EXIST','このRSSのURLは登録済みです');
//define('_AM_RSSHEADLINE_RSSC_LINK_RSS_EXIST_MORE','同じRSSのURLを持つ複数のリンクが見つかりました');
//define('_AM_RSSHEADLINE_RSSC_LINK_LID_EXIST_NOT','このリンクIDのレコードは存在していません');
//define('_AM_RSSHEADLINE_RSSC_AUTO_FAILD','RSSのURLの自動検出ができませんでした');
//define('_AM_RSSHEADLINE_RSSC_LID','RSSセンタのリンクID');
//define('_AM_RSSHEADLINE_RSSC_LID_UPDATE','リンクIDを変更する');
//define('_AM_RSSHEADLINE_RSSC_GOTO_LINK','RSSセンタの管理画面へ');
