<?php
include("DeleteTemplate.class.php");
include_once("model.php");

$tpl = new DeleteTemplate();
$tpl->result = false;

/*
** Modelオブジェクトを生成して，MySQLに接続する．
*/
$db = new Model();
$db->connect();


/*
** delete.phpから削除ボタンが押された時の処理
*/
if($_POST){
  /*
  ** model.phpのdeletePostメソッドを叩く
  */
  $result = $db->deletePost($_POST['id']);

  /*
  ** MySQLとの接続を切断してから，
  ** index.phpにリダイレクトする
  */
  $db->close();
  header("location: index.php?delete=$result");
  exit();
}

$tpl->show("delete.tpl.html");
