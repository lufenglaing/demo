<?php
//引入pdo
require_once("pdoClass.php");"wwwwwww";
header("content-type:text/html;charset=utf8");

$pdo=new Ppo("mysql","127.0.0.1","quan","root");


$data=$_POST;
$arr=["name"=>$data["name"],"state"=>0,"time"=>time()];
for ($i=0; $i < $data['num']; $i++) { 
	 $add=$pdo->table("quan")->add($arr);
}
if ($add) {
	echo "<script>alert('添加成功')</script>";
}else{
	echo "<script>alert('添加失败')</script>";
}