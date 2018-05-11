<?php
/**
 * db class
 * @package core
 * @author terry
 * @access public
 * @version v1.0 2010-04-05
 */
 
/**
 * db class
 * @package core
 * @author terry
 * @access public
 * @version v1.0 2010-04-05
 */
class MyDB
{
	/**
	 * @access private
	 * @var resource
	 */
	private $_connectid;
	
	public function __construct($dbConfig = '')
	{
		if ($dbConfig){
			$this->_connect($dbConfig);
		}
	}
	
	/**
     * 连接数据库
     *
     * @param array $dbConfig
     */
	private function _connect($dbConfig)
	{
		if (!is_array($dbConfig)){
			return false;
		}
		@extract($dbConfig);
		
		if (!$ispconnect){
			$this->_connectid = @mysql_connect($host, $user, $pass);
		}else{
			$this->_connectid = @mysql_pconnect($host, $user, $pass);
		}
		if (mysql_errno() != 0){
			$this->_halt("Connect($pconnect) to MySQL ($dbhost, $dbuser) failed");
		}
		
		//charset
		if ($charset){
			mysql_query("SET NAMES '{$charset}'");
		}
		
		//if ($this->getVersion() > '5.0'){
		//	mysql_query("SET sql_mode=''");
		//}
		
		//select db
		if ($dbname){
			$this->selectDB($dbname);
		}
	}
	
	/**
     * 断开数据库连接
     *
     * @return bool
     */
	public function close()
	{
		return mysql_close();
	}

	/**
     * 选择数据库
     * 
     * @param string $dbname
     */	
	private function selectDB($dbname)
	{
		if (!@mysql_select_db($dbname, $this->_connectid)){
			$this->_halt('Cannot use database ' . $dbname);
		}
	}
	
	/**
     * 获取数据库版本
     * 
     * @return string
     */
	public function getVersion()
	{
		return mysql_get_server_info();
	}

	/**
     * 执行查询
     * 
     * @param string $query
     * @param string $method
     * @return resource
     */	
	public function query($query, $method = '')
	{
		if($method == 'UB' && function_exists('mysql_unbuffered_query')){
			$result = mysql_unbuffered_query($query);
		}else{
			$result = mysql_query($query);
		}
		if (!$result){
			$this->_halt('Query Error: ' . $query);
		}
		return $result;
	}

	/**
     * 执行查询返回一维数组
     * 
     * @param string $query
     * @return array
     */		
	public function getToArray($query)
	{
		$result = $this->query($query, 'UB');
		$row = mysql_fetch_assoc($result);

		return $row;
	}
	
	/**
	 * 执行查询返回一个单元
	 *
	 * @param string $query
	 * @return mixed
	 */
	public function getOne($query)
	{
		$result = $this->query($query,'UB');
		$row = mysql_fetch_row($result);
		return $row[0];
	}
	
	/**
     * 执行查询返回二维数组
     * 
     * 指定$key, $val后，返回一维数组array($key => $val);
     *
     * @param string $query
     * @param string $key
     * @param string $val
     * @return array
     */	
	public function loadToArray($query, $key = '', $val = '')
	{
		$result = $this->query($query, 'UB');
		
		if ($key && $val){
			while ($row = $this->fetchRow($result)){
				$rowList[$row[$key]] = $row[$val];
			}
		}elseif ($key){
			while ($row = $this->fetchRow($result)){
				$rowList[$row[$key]] = $row;
			}
		}elseif($val)
		{
			while($row = $this->fetchRow($result)){
				$rowList[] = $row[$val];
			}
		}else{
			while ($row = $this->fetchRow($result)){
				$rowList[] = $row;
			}
		}
		return $rowList;
	}

	/**
     * 执行查询（update/delete/truncate）
     *
     * @param string $query
     * @return resource 
     */	
	public function update($query)
	{
		$result = $this->query($query, 'UB');
	
		if (!$result){
			$this->_halt('Update Error: ' . $query);
		}
		return $result;
	}

	/**
     * 获取数据(key=both)
     *
     * @param resource $result
     * @param string resultType(MYSQL_ASSOC/MYSQL_NUM/MYSQL_BOTH)
     * @return array 
     */
	public function fetchArray($result, $resultType = 'MYSQL_BOTH')
	{
		return mysql_fetch_array($result, $resultType);
	}
	
	/**
     * 获取数据(key=field)
     *
     * @param resource $result
     * @return array 
     */	
	public function fetchRow($result)
	{
		return mysql_fetch_assoc($result);
	}

	/**
     * 查询所影响的记录行数
     *
     * 取得最近一次与$this->_connectid关联的INSERT/UPDATE/DELETE查询所影响的记录行数
     *
     * @return int 
     */	
	public function affectedRows()
	{
		return mysql_affected_rows();
	}
	
	/**
     * 取得结果集中行的数目
     *
     * 返回结果集中行的数目。此命令仅对SELECT语句有效
     *
     * @param resource $result
     * @return int 
     */	
	public function numRows($result)
	{
		return mysql_num_rows($result);
	}

	/**
     * 释放结果内存
     *
     * 将释放所有与结果标识符$result所关联的内存
     *
     * @param resource $result
     * @return bool 
     */
	public function freeResult($result)
	{
		return mysql_free_result($result);
	}

	/**
     * 最后插入主键ID
     *
     * @return int 
     */
	public function insertID()
	{
		//return mysql_insert_id();
		//纠正字段类型兼容错误
		$query = "select last_insert_id() as LastInsertID ";
		$rt = $this->getToArray($query);
		
		return $rt['LastInsertID'];
	}
	
	/**
     * 取表字段列表
     * 
     * @param string $table
     * @return array
     */
	private function _getFieldList($table)
	{
		$result = $this->query("show columns from `{$table}`");		
		while ($row = $this->fetchRow($result)){
			 $fieldList[] = $row['Field'];
		}
		return $fieldList;
	}

	/**
     * 通过数组保存,需要添加可以传入where条件的
     * 
     * @param string $table
     * @param array $array
     * @param string $primaryKey
     * @return array
     */
	public function saveFromArray($table, $array, $primaryKey = '')
	{
		if (!$table || !is_array($array)) return false;	
		if (!$primaryKey){
			$tmpTable = explode('_',$table);
			if(is_array($tmpTable))
			{
				foreach($tmpTable as $val)
				{
					$tableNameFormat .= ucfirst($val);
				}
			}
			$primaryKey = $tableNameFormat . 'ID';
		}
		$fields = $this->_getFieldList($table);
		
		//update
		if (is_numeric($array[$primaryKey]) && (int)$array[$primaryKey]){
			$query = " update " . $table . " set ";
			foreach ($fields as $key => $field){
				if ($field == 'SystemTime') continue;
				if ($field == $primaryKey) continue;
				if (!array_key_exists($field, $array)) continue;
				$query .= " {$field} = '{$array[$field]}', ";
			}
			$query = substr($query, 0, -2);
			$query .= " where {$primaryKey} = {$array[$primaryKey]} ";
			$this->update($query);
			
			return (int)$array[$primaryKey];
		}else{
			$query = "insert into " . $table ." ( ";
			foreach ($fields as $key => $field){
				if (!array_key_exists($field, $array)) continue;
				$query .= " {$field}, ";
			}
			$query = substr($query, 0, -2);
			$query .= " ) Values( ";
			foreach ($fields as $key => $field){
				if ($field == 'SystemTime') continue;
				if (!array_key_exists($field, $array)) continue;
				$query .= " '{$array[$field]}', ";
			}
			$query = substr($query, 0, -2);
			$query .= " ) ";
			$this->query($query);
			
			return $this->insertID();
		}
	}
	/**
     * where条件处理
     *
     * 删除 and or 无效连接符
     * 
     * @param string $condition
     * @return array
     */
	public function whereAdd($condition)
	{
		if ($condition == ''){
			return $condition;
		}
		$condition = trim($condition);
		$temp = explode(' ', $condition);
		if (strtolower($temp[0]) == 'and' || strtolower($temp[0]) == 'or'){
			$condition = substr($condition, strlen($temp[0]));
		}
		$condition = ' where ' . $condition;
		return $condition;
	}
	
	/**
     * 错误提示
     * 
     * @param string $msg
     */
	private function _halt($msg = '')
	{
		$mysqlError = mysql_error();
		$mysqlErrno = mysql_errno();
		//exit;
		echo $msg;
		echo "<br /><br /><b>The URL Is</b>: http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		echo "<br /><br /><b>MySQL Server Error</b>: {$mysqlError} ( {$mysqlErrno} )";
		exit();
	}
}
?>
