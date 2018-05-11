<?php
	/**
	 *
	 * 用户模型
	 *
	 * @category Model
	 * @package Model
	 * @author harry
	 * @version v1.0 2014-06-23
	 */
	class ModelEvent extends Base
	{
		/**
		 * 构造函数
		 *
		 * @access public
		 */
		public function __construct()
		{
			parent::Base();
			parent::initDB();
			self::initConfig();
		}
		
		/**
		 * 构造函数
		 *
		 * @access public
		 */
		public function modelEvent()
		{
			self::__construct();
		}
		
		/**
		 * 初始化配置
		 *
		 * @access public
		 */
		public function initConfig()
		{
			//include_once($this->config->confDir.'event.conf');
		}
		
		
		public function nublist()
		{
			$sql="select * from phone_nub where state=1  order by rand() limit 1";
			return $this->db->getToArray($sql);
		}
		public function findwinnub($id)
		{
			
			$sql="select * from user_win ";
			if($id){
				$sql.="where id ='$id' ";
			}
			$sql.= " limit 0,20";
			return $this->db->loadToArray($sql);
		}
		public function findquestionlist($id='')
		{
			
			$sql="select * from to_sign_up where user_time !=0 ";
			if($id){
				$sql.=" and  id ='$id' ";
			}
			$sql.=" order by user_time limit 0,10";
			return $this->db->loadToArray($sql);
		}
		public function findfloohlist($id='')
		{
			
			$sql="select *,from_unixtime(basketball_input.AddTime) time from basketball_input,user_contact where basketball_input.OpenID = user_contact.OpenID ";
			if($id){
				$sql.=" and  id ='$id' ";
			}
			//$sql.=" order by user_time limit 0,10";
			return $this->db->loadToArray($sql);
		}
		public function findzhubolist($id='')
		{
			
			$sql="select *,from_unixtime(user_input.AddTime) time from user_input,user_contact where user_input.OpenID = user_contact.OpenID ";
			if($id){
				$sql.=" and  id ='$id' ";
			}
			//$sql.=" order by user_time limit 0,10";
			return $this->db->loadToArray($sql);
		}
		public function nublist1($openid)
		{
			$sql="select * from phone_nub where look_openid='$openid' and state=2 ";
			return $this->db->getToArray($sql);
		}
		public function nublistwin()
		{
			$sql="select * from phone_nub where state=1 and winstate=1 order by rand() limit 1 ";
			return $this->db->getToArray($sql);
		}
		public function countlist($openid)
		{
			$sql="select count(*) from user_statistics where openid='$openid'";

			return $this->db->getToArray($sql);
		}
		public function countlist1()
		{
			$sql="select count(*) from user_win";

			return $this->db->getToArray($sql);
		}
		public function winnerlist($code)
		{
			$sql="select * from user_win where phone_nub = '$code' ";
			//echo $sql;
			return $this->db->getToArray($sql);
		}
		public function winnerlistall($data)
		{
			$sql="select * from user_win where FROM_UNIXTIME( `add_time`, '%Y-%m-%d') ='$data'";
			//echo $sql;
			return $this->db->getToArray($sql);
		}
		public function userlist($openid)
		{
			$sql="select * from user_statistics where openid = '$openid' ";
			//echo $sql;
			return $this->db->getToArray($sql);
		}
		public function allwinner()
		{
			$sql="select count(*) from winner_user ";
		//echo $sql;
			return $this->db->getToArray($sql);
		}
		public function nubweb()
		{
			$sql="select * from winner_user where state=1 order by rank desc";
			return $this->db->getToArray($sql);
		}
		public function rankone()
		{
			$sql="select * from user_nub where state=1  order by rand() limit 1";
			return $this->db->getToArray($sql);
		}
		public function findallnum($from)
		{
			$sql="select count(*) from `{$from}` ";
			return $this->db->getOne($sql);
		}		
		public function upnub($openid,$text,$nub)
		{
			
			$sql="update cnc_user_sel set content='$text',win_state='$nub'  where openid='$openid'";
			//var_dump($sql);
			return $this->db->update($sql);
		
		}
		public function upuserinput($openid,$text)
		{
			
			$sql="update cnc_user_sel set user_input='$text'  where openid='$openid'";
			//var_dump($sql);
			return $this->db->update($sql);
		
		}
		public function upuserx1($openid,$x1)
		{			
			$sql="update cnc_user_sel set x1='$x1'  where openid='$openid'";
			return $this->db->update($sql);		
		}
		public function upuserx2($openid,$x2)
		{			
			$sql="update cnc_user_sel set x2='$x2'  where openid='$openid'";
			return $this->db->update($sql);		
		}
		public function upuserx3($openid,$x3)
		{			
			$sql="update cnc_user_sel set x3='$x3'  where openid='$openid'";
			return $this->db->update($sql);		
		}
		public function upuserx4($openid,$x4)
		{			
			$sql="update cnc_user_sel set x4='$x4'  where openid='$openid'";
			return $this->db->update($sql);		
		}
		public function upwinuser($id)
		{
			
			$sql="update user_win set state =0 where id='$id'";
			return $this->db->update($sql);
		
		}
		public function upstateuser($id)
		{
			
			$sql="update to_sign_up set sent_state =1 where id='$id'";
			return $this->db->update($sql);
		
		}
		public function uplooknub($phone_nub,$time,$openid)
		{
			
			$sql="update phone_nub set state =2,look_Time='$time',look_openid='$openid' where phone_nub='$phone_nub' and state!=0";
			return $this->db->update($sql);
		
		}
		public function upweb($id)
		{
			
			$sql="update winner_user set state =0 where id='$id'";
			return $this->db->update($sql);
		
		}
		
		public function winner($array)
		{
			
			return $this->db->saveFromArray("user_win",$array);
		
		}
		
			public function banner($array)
		{
			
			return $this->db->saveFromArray("wechat_banner",$array);
		
		}
		public function seve_winner($array)
		{
			
			return $this->db->saveFromArray("winner_user",$array);
		
		}
		public function saveuserinput($array)
		{
			
			return $this->db->saveFromArray("askinput",$array);
		
		}
		
		public function userdate($array)
		{
			
			return $this->db->saveFromArray("user_statistics",$array);
		
		}
		public function shanwin($id)
		{
			
			$sql="delete from winner_user where id='$id'";
			return $this->db->update($sql);
		}
		

		/**
		 * 验证Email
		 *
		 * @access public
		 * @param string $email Email
		 * @return bool
		 */
		public function checkEmail($email)
		{
			$status = validate::email($email);
			return $status;
		}
		
		/**
		 * 验证用户姓名
		 *
		 * @access public
		 * @param string $realname 
		 * @return bool
		 */
		public function checkRealName($realname)
		{
			$status = validate::fixtext($realname,array(3,20));
			return $status;
		}
		
		
		
		
		
			
		/**
		 * 验证checkPhone
		 *验证电话和手机
		 * @access public
		 * @param string $Phone Phone
		 * @return bool
		 */
		public function checkPhone($phone)
		{
			if(strlen($phone)==12 || strlen($phone)==8){
				$status = validate::phone($phone);//验证电话
			}else{
				$status = validate::mobile($phone);//验证手机
			}
			return $status;
		}
	
	/**
	 * 执行sql查询
	 * @param $data 		需要查询的字段值[例`name`,`gender`,`birthday`]
	 * @param $table 		数据表
	 * @param $where 		查询条件[例`name`='$name']
	 * @param $limit 		返回结果范围[例：10或10,10 默认为空]
	 * @param $order 		排序方式	[默认按数据库默认方式排序]
	 * @param $group 		分组方式	[默认为空]
	 * @param $key 			返回数组按键名排序
	 * @return array		查询结果集数组
	 */
	public function select($data, $table, $where = '', $limit = '', $order = '', $group = '', $key = '') {
        $where = $where == '' ? '' : ' WHERE ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = 'SELECT ' . $data . ' FROM `' . $table . '`' . $where . $group . $order . $limit;
        $result = $this->db->loadToArray($sql);
		//var_dump($sql);die();
        return $result;
    }

    /* 数据信息总数 */
    public function total($table, $where = "") {
        $sql = "select count(*) as counts from `" . $table . "`";
        if ($where) {
            $sql.="where " . $where;
        }
        return $this->db->getOne($sql);
    }

    /* 分页数据 */
    public function getList($data = "*", $table, $where = '', $pagesize = '', $start = '', $order = '') {
        if (is_array($table)) {
            $table = $table["table"] . " tc  LEFT JOIN " . $table["table1"] . " c ON tc." . $table["id"] . "=c." . $table["id"] . "";
            $tna = "tc.";
        }
        $sql = "SELECT " . $data . " from " . $table . "";

        if ($where) {
            $sql.=" where " . $where;
        }
        /*if (is_array($order)) {
            foreach ($order as $key => $value) {
                $order1 .= $tna . $value . " desc,";
            }
            $order = substr($order1, 0, -1);
        } else {
            $order = $tna . $order . " desc";
        }
        $sql .= " order by " . $order;*/

        if(!empty($order)) {
            $sql .= " order by " . $order;
        }
        if(!empty($pagesize) && !empty($start)) {
            $sql .= " limit " . $start . ',' . $pagesize;
        }
        return $this->db->loadToArray($sql);
    }

    /* 删除数据 */
    public function del($table, $where = '') {
        if (is_array($table)) {
            $table = $table["table"] . " tc  LEFT JOIN " . $table["table1"] . " c ON tc." . $table["id"] . "=c." . $table["id"] . "";
            $tna = "tc,c";
        }
        if ($where) {
            $where = " where " . $where;
        }
        $sql = "delete " . $tna . " from " . $table . $where;
        $query = $this->db->query($sql);
        return $query;
    }

    /**
     * 修改表中记录信息
     * parm data mixed
     */
    public function update($data, $table = 'user', $condition) {
        $sql_fields = $where = array();
        $sql = "UPDATE `" . $table . "` SET ";
        foreach ($condition AS $key => $value) {
            $sql_fields[] = "`" . $key . "`='" . $value . "'";
        }
        $sql .= implode(",", $sql_fields);

        foreach ($data AS $key => $value) {
            $where[] = "`" . $key . "`='" . $value . "'";
        }
        $sql .= " WHERE " . implode(" AND ", $where);
        return $this->db->query($sql);
    }

    /**
     * 添加表中记录信息
     * parm data mixed
     */
    public function insert($data, $table) {
        $sql = "INSERT INTO " . $table;
        $sql_fields = array();
        $sql_val = array();
        foreach ($data AS $key => $value) {
            $sql_fields[] = $key;
            $sql_val[] = "'" . $value . "'";
        }
        $sql.= "(" . (implode(",", $sql_fields)) . ") VALUES(" . (implode(",", $sql_val)) . ")";
        return $this->db->query($sql);
    }

}
?>