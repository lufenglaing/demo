<?php
//引入pdo
require_once("pdoClass.php"); 
header("content-type:text/html;charset=utf8");

$pdo=new Ppo("mysql","127.0.0.1","quan","root");

$data=$_POST;
$arr=["name"=>$data["name"],"pass"=>$data["pass"]];
$arr1=["email"=>$data["name"],"pass"=>$data["pass"]];
if ($pdo->table("regin2")->where($arr)->find('regin_id')) {
	echo "<script>alert('登录成功');location.href='list.php'</script>";
}else{
	if ($pdo->table("regin2")->where($arr1)->find('regin_id')) {
		echo "<script>alert('登录成功');location.href='list.php'</script>";
	}else{
		echo "<script>alert('登录失败');location.href='login.html'</script>";
	}
}


