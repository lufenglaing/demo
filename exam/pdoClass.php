<?php
class Ppo
{
	static $obj;
	static $error;
	static $char;
	static $table;
	static $order;
	static $limit;
	static $where = " 1=1 ";
	static $dbtype;
	static $host;
	static $dbname;
	static $u;
	static $p;
	public function __construct($dbtype,$host,$dbname,$u,$p="root",$char="utf8")
	{
		self::$dbtype = $dbtype;
		self::$host = $host;
		self::$dbname = $dbname;
		self::$u = $u;
		self::$p = $p;
		self::$char = $char;
		if(!self::$obj){
			self::$obj = new \PDO(self::$dbtype.":host=".self::$host.";dbname=".self::$dbname,self::$u,self::$p);
		}
		
		self::$obj->query("set names ".self::$char);
	}
	// public function upself($name,$value)
	// {
	// 	self::$."$name"=$value;
	// }

	//添加
	public function add($arr)
	{
		//过滤冗余
		$data = $this->filt($arr);
		$key = "";
		$value = "";
		foreach ($data as $k => $v) {
			$key .= "`".$k."`,";
			$value .= ":".$k.",";
		}
		$key = trim($key,",");
		$value = trim($value,",");
		//拼接sql
		$sql = "INSERT INTO ".self::$table." (".$key.") VALUES (".$value.")";

		//预处理
		$source = self::$obj->prepare($sql);

		//绑定参数
		foreach ($data as $k => $v) {
			$res = $source->bindValue(":".$k,$v);
		}
		
		//执行
		$res = $source->execute();
		// var_dump($res);die;
		if($res){
			return self::$obj->lastInsertId();
		}else{
			self::$error = $source->errorInfo();
			return self::$error[2];
		}

	}

	//过滤冗余
	public function filt($arr)
	{
		$field = $this->field();
		foreach ($arr as $key => $val) {
			if(!in_array($key,$field)){
				unset($arr[$key]);
			}
		}
		return $arr;
	}
	// 一维数组过滤
	public function filtOne($arr)
	{
		$field = $this->field();
		foreach ($arr as $key => $val) {
			if(!in_array($val,$field)){
				unset($arr[$key]);
			}
		}
		return $arr;
	}
	//获取字段
	public function field()
	{
		
		$res = self::$obj->query("DESC ".self::$table);
		$ret = $res->fetchAll();
		foreach ($ret as $k => $v) {
			$field[] = $v["Field"];
		}
		return $field;
	}
	//删除
	public function delete()
	{
		$sql = "DELETE FROM `".self::$table."` ".self::$where;
		$res = self::$obj->exec($sql);
		self::$where = " 1=1 ";		
		self::$table = "";
		if($res){
			return $res;
		}else{
			self::$error = $source->errorInfo();
			return self::$error[2];
		}
	}

	//修改
	public function save($upVal)
	{
		//过滤冗余
		$data = $this->filt($upVal);
		
		if(empty(self::$where)){
			$upV = $this->nuWhere($data);
		}else{
			$upV = "";
			foreach ($data as $key => $val) {
				$upV .= "`".$key."` = :".$key.",";
			}
		}
		$upV = trim($upV,",");
		//组装sql
		$sql = "UPDATE `".self::$table."` SET ".$upV." WHERE ".self::$where;
		//预处理
		$source = self::$obj->prepare($sql);
		//绑定值
		foreach ($data as $key => $val) {
			$source->bindValue(":".$key,$val);
		}
		//执行
		$res = $source->execute();
		self::$where = " 1=1 ";
		self::$table = "";
		if($res){
			return $count = $source->rowCount();
			
		}else{
			self::$error = $source->errorInfo();
			return self::$error[2];
		}
		
	}
	//主键字段
	public function getPri()
	{
		$priField = "";
		$res = self::$obj->query("DESC ".$this->table);
		$field = $res->fetchAll(\PDO::FETCH_ASSOC);
		foreach ($field as $k => $v) {
		 	if($v["Key"]=="PRI"){
		 		$priField = $v["Field"];
		 	}
		} 
		return $priField;
	}

	//修改条件为空
	public function nuWhere($data)
	{
		$upV = "";
		$priField = $this->getPri($data);
		foreach ($data as $key => $val) {
			if($key==$priField){
				self::$where .= " AND `".$key."` = :".$key;
			}else{
				$upV .= "`".$key."` = :".$key." AND ";
			}
		}
		$upV = trim($upV," AND ");
		return $upV;
	}

	//查询
	public function select($field="*")
	{
		$str = "";
		if($field != "*"){
			if(is_array($field)){
				$data = $this->filtOne($field);
				foreach ($data as $k => $v) {
					$str .= "`".$v."`,";
				}
				$str = trim($str,",");
			}else{
				$str = "`".$field."`";
			}
		}else{
			$str = $field;
		}
		$sql = "SELECT ".$str." FROM `".self::$table."` WHERE ".self::$where." ".self::$order." ".self::$limit;
		$source = self::$obj->query($sql);
		self::$table = "";
		self::$order = "";
		self::$limit = "";
		self::$where = " 1=1 ";
		return $source->fetchAll(\PDO::FETCH_ASSOC);
		// return $source;
	}

	//find
	public function find($field="*")
	{
		$str = "";
		if($field != "*"){
			if(is_array($field)){
				$data = $this->filtOne($field);
				foreach ($data as $k => $v) {
					$str .= "`".$v."`,";
				}
				$str = trim($str,",");
			}else{
				$str = "`".$field."`";
			}
		}else{
			$str = $field;
		}
		$sql = "SELECT ".$str." FROM `".self::$table."` WHERE ".self::$where." ".self::$order." ".self::$limit;
		$source = self::$obj->query($sql);
		self::$table = "";
		self::$order = "";
		self::$limit = "";
		self::$where = " 1=1 ";
		return $source->fetch(\PDO::FETCH_ASSOC);
		// return $source;
	}
	//where
	public function where($where,$c="or")
	{
		if(is_array($where)){
			$data = $this->filt($where);
			if(strtolower($c)=="and"){
				foreach ($data as $k => $v) {
					self::$where .= " AND `".$k."` = '".$v."'";
				}
			}else{
				foreach ($data as $k => $v) {
					self::$where .= " OR `".$k."` = '".$v."'";
				}
			}
		}else{
			self::$where .= " AND ".$where;
		}
		return new self(self::$dbtype,self::$host,self::$dbname,self::$u,self::$p,self::$char);
	}

	//表名
	public function table($table)
	{
		self::$table = $table;
		return new self(self::$dbtype,self::$host,self::$dbname,self::$u,self::$p,self::$char);
	}

	//排序
	public function order($field,$con = "ASC")
	{
		if(strtoupper($con) == "DESC"){
			self::$order = "ORDER BY `".$field."` ".strtoupper($con);
		}else{
			self::$order = "ORDER BY `".$field."` ".strtoupper($con);
		}
		return new self(self::$dbtype,self::$host,self::$dbname,self::$u,self::$p,self::$char);
	}

	//限制条数
	public function limit()
	{
		$arr = func_get_args();
		if(count($arr)==1){
			self::$limit = "LIMIT ".$arr[0];
		}else{
			self::$limit = "LIMIT ".$arr[0].",".$arr[1];
		}
		return new self(self::$dbtype,self::$host,self::$dbname,self::$u,self::$p,self::$char);
	}
}


