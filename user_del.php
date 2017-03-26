<?php
$userlog_email = $_COOKIE["email"];
$userlog_password = base64_decode($_COOKIE["password"]);

if (!empty($userlog_email) && !empty($userlog_password)) {
  include("include/PDO.php");

  $reslut = $pdo -> query("SELECT * FROM opa_member where email= '".$userlog_email."'");
	$rows = $reslut->fetchAll();
	$user = $rows[0];
  if ( $user['authority'] != 1 ) {
  	$pdo = NULL;
    header("location:index.php");
  }
}else{
	$pdo = NULL;
  header("location:index.php");
}


include "include/PDO.php" ;

$id = $_GET['id'];
$reslut = $pdo -> prepare("DELETE FROM opa_member WHERE id = :id");
$reslut->execute(array(':id' => $id));
$pdo = NULL;
header("location:admin.php");
?>