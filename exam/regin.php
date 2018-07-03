<?php
//引入pdo
require_once("pdoClass.php"); 
header("content-type:text/html;charset=utf8");

$pdo=new Ppo("mysql","127.0.0.1","quan","root");

$data=$_POST;
// print_r($data);
if ($data["name"]=="") {
	$msg=["num"=>0,"content"=>"用户名不能为空"];
}else{
	if ($data["pass"]=="") {
		$msg=["num"=>0,"content"=>"密码不能为空"];
	}else{
		if ($data["email"]=="") {
			$msg=["num"=>0,"content"=>"邮箱不能为空"];
		}else{
			$ze="/^([0-9a-zA-z]+)@([0-9a-zA-z]+)\.(com|cn|net)$/";
			if (!preg_match($ze, $data["email"])) {
				$msg=["num"=>0,"content"=>"邮箱输入不合法"];
			}else{
				$aa=$pdo->table("regin2")->add($data);
				if ($aa) {
					$msg=["num"=>1,"content"=>"注册成功"];
				}else{
					$msg=["num"=>0,"content"=>"注册失败"];
				}
				
			}
		}
	}
}
echo json_encode($msg);


