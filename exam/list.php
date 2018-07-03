<?php 
require_once("pdoClass.php"); 
header("content-type:text/html;charset=utf8");

$pdo=new Ppo("mysql","127.0.0.1","quan","root");

$data=$pdo->table("quan")->where("state=0")->select();
$num=count($data);

//模拟登录用户id

$regin_id=1;

?>

<table>
	<th>50元优惠券</th>
    <tr>
    	<td>金额</td>
    	<td>50</td>
    </tr>
    <tr>
    	<td>数量</td>
    	<td>还剩<?=$num?>张</td>
    </tr>
    <tr>
    	<td colspan="2"><a href="ling.php?regin_id=<?=$regin_id?>"><button>立即领取</button></a></td>
    </tr>
</table>