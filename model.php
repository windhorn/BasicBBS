<?php
/*
**モデルクラス
*/
include_once("post.php");
class Model {


const HOST = "localhost";    // MySQLのホスト
const USER = "intern";       // MySQLのユーザ名
const PASSWORD = "intern";   // MySQLのパスワード
const DB = "intern";         // MySQLのデータベース名

private $mysql;              // MySQLへの接続を表すオブジェクトを格納するメンバ変数
private $error = null;              // フォームが入力されていない時にエラー文を格納するための変数

// アクセサメソッド
private function setMysql($mysql){
  $this->mysql = $mysql;
}
private function getMysql(){
  return $this->mysql;
}
public function setError($error){
  $this->error = $error;
}
// エラー文を消去してから，エラー文を返す．
public function getError(){
  return $this->error;
}

// MySQLへの接続を行うメソッド
public function connect(){
  $mysql = new mysqli(self::HOST, self::USER, self::PASSWORD, self::DB);
  if ($mysql->connect_error) {
    die("MySQLへの接続に失敗しました");
  }
  $this->setMysql($mysql);
  return true;
}

// MySQLとの接続を切断するメソッド
public function close(){
  $mysql = $this->getMysql();
  $mysql->close();
  return $this->setMysql(null);
}

// 新規記事の追加を行うメソッド
public function addPost($post){
  $mysql = $this->getMysql();
  if($post->issetParams()){
    $paramsArray = [
      'name' => $post->getName(),
      'title' => $post->getTitle(),
      'body' => $post->getBody()
    ];
    $paramsArray = $this->escape($paramsArray);
    $post_date = date("Y/m/d H:i:s", time());
    $result = $mysql->query("INSERT INTO topics(name,title,body,post_date) VALUES('$paramsArray[name]','$paramsArray[title]','$paramsArray[body]','$post_date')");
    if (!$result) {
      die("追加出来ませんでした");
    }
    return true;
  } else {
    $this->setError("全てのフォームに入力してください");
    return false;
  }
}

// エスケープを行う処理
public function escape($paramsArray){
  $mysql = $this->getMysql();
  $returnParams = array();
  foreach($paramsArray as $key => $value){
    $value = htmlspecialchars($value);
    if ($key == 'body') {
      $value = nl2br($value);
    }
    $value = $mysql->real_escape_string($value);
    $returnParams += array($key => $value);
  }
  return $returnParams;
}

// 引数の記事IDの投稿を削除するメソッド
public function deletePost($id){
  $mysql = $this->getMysql();
  // 記事が存在すれば削除を行う．
  if ($this->is_exist($id)) {
    $result = $mysql->query("DELETE FROM topics WHERE id = $id");
    return True;
  }
  return False;
}

// 引数の記事IDがテーブルに存在するのかをチェックするメソッド
public function is_exist($id){
  $mysql = $this->getMysql();
  $result = $mysql->query("SELECT COUNT(*) FROM topics WHERE id = $id");
  if (!$result) {
    die("検索出来ませんでした");
  }
  foreach($result as $item){
    if ($item["COUNT(*)"] == 0) {
      return false;
    } else {
      return true;
    }
  }
}

// 記事一覧を取得して記事オブジェクトの配列として返すメソッド
public function getAllPosts(){
  $posts = array();
  $mysql = $this->getMysql();
  $allPosts = $mysql->query("SELECT * FROM topics ORDER BY post_date DESC");
  /*
  ** 投稿が1つも無いときはnullを返す.
  */
  if(!$allPosts) return null;
  foreach($allPosts as $item){
    $post = new Post();
    $post->setId($item['id']);
    $post->setTitle($item['title']);
    $post->setName($item['name']);
    $post->setBody($item['body']);
    $post->setPost_date($item['post_date']);
    $posts[] = $post;
  }
  return $posts;
}

// 指定された記事IDの記事を検索して，その記事オブジェクトを返すメソッド.
public function searchPost($id){
  $mysql = $this->getMysql();
  $result = $mysql->query("SELECT * FROM topics WHERE id = $id");
  foreach($result as $item){
    $post = new Post();
    $post->setId($item['id']);
    $post->setTitle($item['title']);
    $post->setName($item['name']);
    $post->setBody($item['body']);
    $post->setPost_date($item['post_date']);
    return $post;
  }
}

// 記事の更新を行うメソッド.
// 存在しない記事を更新しようとした場合はfalseを返す.
// 更新に成功した場合はtrueを返す.
public function updatePost($title, $body, $id){
  $mysql = $this->getMysql();
  if ($this->is_exist($id)) {
    $result = $mysql->query("UPDATE topics SET title='$title', body='$body' WHERE id = $id");
    if (!$result) {
      die("更新出来ませんでした.");
    }
    return true;
  }
  return false;
}

} //Class
