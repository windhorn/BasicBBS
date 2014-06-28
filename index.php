<?php
/*
** 各クラスをincludeする
*/
include("IndexTemplate.class.php");
include_once("model.php");
include_once("post.php");
$db = new Model();
$tpl = new IndexTemplate();
$tpl->posts = array();
$tpl->error = null;

/*
** DBに接続する
*/
$db->connect();

/*
** POSTで送られてきたデータをDBに保存する．
*/
if ($_POST) {

/*
** PostオブジェクトにPOSTで送られてきたデータをセットする．
*/
  $post = new Post();
  $post->setName($_POST['name']);
  $post->setTitle($_POST['title']);
  $post->setBody($_POST['body']);

/*
** ModelクラスにPostオブジェクトに入っているデータを追加するようにメッセージを送る.
** エラーメッセージをIndexTemplateオブジェクトのerrorに格納する.
** エラーメッセージ格納後，IndexTemplateオブジェクトのerrorにnullをセットする.
*/
  $db->addPost($post);
  $tpl->error = $db->getError();  // エラーメッセージがなければnullが格納される.
  $db->setError(null);
}


/*
** 投稿の一覧を取得して、$tplのpostsに格納する．
*/
$tpl->posts = $db->getAllPosts();

/*
** DBとの接続を解除する.
*/
$db->close();
$tpl->show('index.tpl.html');
 ?>
