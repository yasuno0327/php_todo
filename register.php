<?php
include 'ChromePhp.php';
header('Content-type: text/plain; charset= UTF-8');
$sa = $_POST["id"];
$dsn = 'mysql:dbname=j5076;host=localhost';
$user = 'root';
$password='';
try {
  $PDO = new PDO($dsn,$user,$password);
}catch(PDOException $e){
  die("接続失敗".$e->getMessage());
}

if(!empty($_POST["id"])) {
  $id = intval($_POST["id"]);
  ChromePhp::log("$id");
  $stmt =$PDO->prepare("delete from todo where id = ?");
  $stmt -> bindValue(1,$id, PDO::PARAM_INT);
  $stmt -> execute();
}
?>
