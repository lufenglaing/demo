<?php
//引入pdo
require_once("pdoClass.php"); 
header("content-type:text/html;charset=utf8");

$pdo=new Ppo("mysql","127.0.0.1","quan","root");
$regin_id=$_GET["regin_id"];

$quan_id=$pdo->table("quan")->where("state=0")->limit(1)->find();
// print_r($quan_id);die;
$quan_num=time()+rand(1000,9999);
$name=$pdo->table("regin2")->where("regin_id=$regin_id")->find("name");

$arr=["user_id"=>$regin_id,"quan_id"=>$quan_id["id"],"name"=>$name['name'],"quan_num"=>$quan_num];
if($pdo->table('get')->add($arr)){
	echo "<script>alert('领取成功');location.href='lists.php'</script>";
}

