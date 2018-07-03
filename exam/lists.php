<?php
//引入pdo
require_once("pdoClass.php"); 
header("content-type:text/html;charset=utf8");

$pdo=new Ppo("mysql","127.0.0.1","quan","root");

$data=$pdo->table("get")->select();
?>

<table>
	<th>领取人</th>
	<th>时间</th>
	<th>金额</th>
	<?php

     foreach ($data as $key => $value) { ?>
     	<tr>
     		<td><?=$value["name"]?></td>
     		<td><?=$value["start_time"]?></td>
     		<td>50</td>
     	</tr>
    <?php  }

	?>
</table>