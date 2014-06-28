<?php
include("EditTemplate.class.php");
include_once("post.php");
include_once("model.php");
/*
** Modelオブジェクトを生成して，MySQLに接続する．
*/
$db = new Model();
$db->connect();
$tpl = new EditTemplate();

/*
** レコードが存在するか判定するためのフラグ
*/
$tpl->existRecord = true;

/*
** Postオブジェクトを格納するための変数
*/
$tpl->post = null;

/*
** テーブルに存在しないレコードを指定されたらエラーを出力するためにフラグをfalseにする．
*/
if ($_GET) {
  if (!$db->is_exist($_GET['id'])) {
    $tpl->existRecord = false;
    $tpl->show("Edit.tpl.html");
    $db->close();
    exit();
  }
}

/*
** index.phpから編集ボタンを押された時の処理
*/
if (!$_POST) {
  /*
  ** 編集する記事のPostオブジェクトを取得する．
  */
  $post = $db->searchPost($_GET['id']);
  $post->setBody( str_replace("<br />", "", $post->getBody()) );
  $tpl->post = $post;
} else {  // edit.phpから更新ボタンを押された時の処理
  /*
  ** POSTで送られてきたデータを連想配列に格納する.
  */
  $paramsArray = [
    'title' => $_POST['title'],
    'body' => $_POST['body'],
    'id' => $_POST['id']
  ];

  /*
  ** エスケープする．
  */
  $paramsArray = $db->escape($paramsArray);

  /*
  ** 入力されたタイトルと本文を更新する．
  */
  $db->updatePost($paramsArray['title'], $paramsArray['body'], $paramsArray['id']);

  /*
  ** DB Close
  */
  $db->close();

  // index.phpにリダイレクトする．
  header("location: index.php");
  exit();
}

$tpl->show("Edit.tpl.html");
