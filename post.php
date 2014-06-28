<?php
/*
** 記事モデル．
*/
class Post {

private $id;        // 記事の記事ID
private $title;     // 記事のタイトル
private $name;      // 記事投稿者の名前
private $body;      // 記事の本文
private $post_date; // 貴女の投稿日時

// コンストラクタ
function __construct(){
  $this->setId(null);
  $this->setTitle(null);
  $this->setName(null);
  $this->setBody(null);
  $this->setPost_date(null);
}

/*
** 各メンバ変数のアクセサメソッド達です.
*/
public function getId(){
  return $this->id;
}
public function setId($id){
  $this->id = $id;
}
public function getTitle(){
  return $this->title;
}
public function setTitle($title){
  $this->title = $title;
}
public function getName(){
  return $this->name;
}
public function setName($name){
  $this->name = $name;
}
public function getBody(){
  return $this->body;
}
public function setBody($body){
  $this->body = $body;
}
public function getPost_date(){
  return $this->post_date;
}
public function setPost_date($post_date){
  $this->post_date = $post_date;
}

/*
** 自身のname,title,bodyが入力されているかをチェックするメソッド
*/
public function issetParams(){
  $name = $this->getName();
  $title = $this->getTitle();
  $body = $this->getBody();
  if( (mb_strlen($name)*mb_strlen($title)*mb_strlen($body) ) == 0){
    return false;
  } else {
    return true;
  }
}


} //Class
