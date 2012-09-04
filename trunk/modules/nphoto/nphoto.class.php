<?php

/**
 * @Project NUKEVIET 3.0
 * @Author Nguyen Ngoc Phuong ( nguyenngocphuongnb@gmail.com )
 * @Copyright (C) 2012 NNP
 * @Createdate 25-07-2012 21:34
 */
 
if( !defined( 'NV_MAINFILE' ) or !defined( 'NV_ADMIN' ) ) die ( 'stop!!!' );

class nphoto
{
	public $message = array();
	public $error = array();
	public $warning = array();
	public $success = array();
	public $information = array();
	public $sql = '';
	public $int_params = array('catid', 'pid', 'albid', 'userid' );
	
	protected function query( $sql, $type )
	{
		global $db, $module_data;
		
		( $type == "insert" ) ? ( $rsid =( int )$db->sql_query_insert_id( $sql ) ) : $rsid = $db->sql_query( $sql );
		
		if( $rsid > 0 or $rsid == true )
		{
			$db->sql_freeresult();
		}
		else
		{
			$this->error[] = "Đã có lỗi sảy ra";
		}
		return $rsid;
	}
	
	protected function checkError( $type, $data = array(), $when )
	{
		if( $when != 'update' )
		{
			$check_exist = $this->getItems( $type, 'alias', 'alias', $data['alias'] );
			if( !empty( $check_exist ) ) $this->error[] = "- Alias đã tồn tại hãy thay alias khác";
		}
	}
	
	public function status()
	{
		global $global_config, $module_file, $my_head;
		$msg = array( 'msgcontent' => '', 'title' => '', 'class' => '' );
		$status = true;
		
		if( !empty( $this->error ) )
		{
			$this->error = array_unique( $this->error );
			$msg['msgcontent'] = implode( '<br />', $this->error );
			$msg['title'] = "Error!";
			$msg['class'] = "nperror";
			$this->message[] = $msg;
			$status = false;
		}
		if( !empty( $this->information ) )
		{
			$this->information = array_unique( $this->information );
			$msg['msgcontent'] = implode( '<br />', $this->information );
			$msg['title'] = "Information!";
			$msg['class'] = "npinfo";
			$this->message[] = $msg;
		}
		if( !empty( $this->success ) )
		{
			$this->success = array_unique( $this->success );
			$msg['msgcontent'] = implode( '<br />', $this->success );
			$msg['title'] = "Success!";
			$msg['class'] = "success";
			$this->message[] = $msg;
		}
		if( !empty( $this->warning ) )
		{
			$this->warning = array_unique( $this->warning );
			$msg['msgcontent'] = implode( '<br />', $this->warning );
			$msg['title'] = "Warning!";
			$msg['class'] = "warning";
			$this->message[] = $msg;
			$status = false;
		}
		if( !empty( $this->message ) )
		{
			$xtpl = new XTemplate( "data.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
			
			foreach( $this->message as $data )
			{
				$xtpl->assign( 'DATA', $data );
				$xtpl->parse( 'message' );
				$my_head .= $xtpl->text( 'message' );
			}
		}
		return $status;
	}
	
	protected function fetchArrayData( $type,  $data = array() )
	{
		global $db;
		
		$keylist = $valuelist = $update_params = array();
		foreach( $data as $key => $value )
		{
			if( $type == 'insert' )
			{
				$keylist[] = "`" . $key . "`";
				( is_numeric( $value ) or $value == 'NULL' )? ( $valuelist[] = $value ) : ( $valuelist[] = $db->dbescape( $value ) );
			}
			elseif( $type == 'update' )
			{
				is_numeric( $value ) ? ( $update_params[] = "`" . $key . "`=" . $value ) : ( $update_params[] = "`" . $key . "`=" . $db->dbescape( $value ) );
			}				
		}
		return ( $type == 'insert' ) ? array( implode(',', $keylist ), implode(',', $valuelist ) ) : implode(',', $update_params );
	}		
	
	public function getItems( $type, $key, $by = '', $value = 0, $limit = 0, $startget = NULL, $orderby = '', $order = "ASC")
	{
		global $db, $module_data;
		$results = array();
		
		if( empty( $by ) )
		{
			$select_condition = '';
		}
		elseif( in_array( $by, $this->int_params ) )
		{
			$select_condition = "WHERE `" . $by . "`=" . intval( $value );
		}
		else
		{
			$select_condition = "WHERE `" . $by . "`=" . $db->dbescape( $value );
		}
		
		if( $limit == 0 and $orderby != '' )
		{
			$show_condition = " ORDER BY `" . $orderby . "` " . $order;
		}
		elseif( $limit == 0 and $orderby == '' )
		{
			$show_condition = "";
		}
		elseif( $limit != 0 and $orderby == '' )
		{
			$show_condition = " LIMIT " . $limit;
		}
		elseif( $limit != 0 and $orderby != '' and $startget == NULL )
		{
			$show_condition = " ORDER BY `" . $orderby . "` " . $order . " LIMIT " . $limit;
		}
		else
		{
			$show_condition = " ORDER BY `" . $orderby . "` " . $order . " LIMIT " . $startget . "," . $limit;
		}
		
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $type . "` " . $select_condition . $show_condition;
		$query = $db->sql_query( $sql );
		while( $row = $db->sql_fetchrow( $query ) )
		{
			$results[$row[$key]] = $row;
		}
		return $results;	
	}
	
	public function seachItems( $type, $key, $by = '', $value = '', $limit = 0, $startget = NULL, $orderby = '', $order = "ASC", $other_condition = '' )
	{
		global $db, $module_data;
		$results = array();
		
		$search_condition = $order_condition = $limit_condition = '';
		
		if( !empty( $other_condition ) ) $other_condition = " AND " . $other_condition;
		
		( $type == '0' ) ? ( $stt = "`status`=1" ) : ( $stt = "`status`=0" );
		
		if( !empty( $by ) )
		{
			$search_condition = " " . $other_condition . " AND `" . $by . "` LIKE '%" . $db->dblikeescape( $value ) . "%'";
		}
		else
		{
			$search_condition = " " . $other_condition;
		}
		if( !empty( $orderby ) )
		{
			$order_condition = " ORDER BY `" . $orderby . "` " . $order;
		}
		if( $limit > 0 )
		{
			( $startget > 0 ) ? ( $limit_condition = " LIMIT " . $startget . "," . $limit ) : ( $limit_condition = " LIMIT " . $limit );
		}
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $type . "` WHERE `status` IN (0,1) " . $search_condition . $order_condition . $limit_condition ;
		$query = $db->sql_query( $sql );
		while( $row = $db->sql_fetchrow( $query ) )
		{
			$results[$row[$key]] = $row;
		}
		return $results;	
	}
	
	public function addItem( $type, $data )
	{
		global $db, $module_data;
		
		$this->checkError( $type, $data, 'insert');
		
		if( $this->status() )
		{
			$fetchdata = $this->fetchArrayData( 'insert', $data );
			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_" . $type . "` (" . $fetchdata[0] . ") VALUES (" . $fetchdata[1] . ")";	
			$this->sql = $sql;
			return $this->query($sql, 'insert');
		}
	}
	
	public function updateItem( $type, $data, $condition )
	{
		global $db, $module_data;

		$this->checkError( $type, $data, 'update');
		
		if( $this->status() )
		{
			$fetchdata = $this->fetchArrayData( 'update', $data );
			is_numeric( $data[$condition] ) ? $cdt_value = $data[$condition] : $cdt_value = $db->dbescape( $data[$condition] );
			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $type . "` SET " . $fetchdata . " WHERE `" . $condition . "`=" . $cdt_value . "";
			$this->sql = $sql;
			return $this->query($sql, 'update');
		}
	}
	
	public function deleteItem( $type, $by, $value)
	{
		global $db, $module_data;
		
		if( empty( $this->error ) and empty( $this->warning ) )
		{	
			in_array( $by, $this->int_params ) ? ( $del_condition = "`" . $by . "`=" . intval( $value ) ) : ( $del_condition = "`" . $by . "`=" . $db->dbescape( $value ) );
			
			$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $type . "` WHERE " . $del_condition;		
			if( $this->query($sql, 'delete') ) return true;
			else return false;
		}
	}
	
	public function transferData( $type, $pid, $current_catid, $new_catid, $update = 0, $get_from = 'photos' )
	{
		$image = $this->getItems( $get_from, 'pid', 'pid', $pid );
		foreach( $image[$pid] as $key => $img )
		{
			if( is_numeric( $key ) ) unset( $image[$pid][$key] );
		}
		$copyData = $image[$pid];
		if( $current_catid != $new_catid )
		{
			if( $get_from == '0' ) $this->addItem( 'photos', $copyData );
			if( $this->addItem( $new_catid, $copyData ) )
			{
				if( $this->deleteItem( $current_catid, 'pid', $copyData['pid']) == false )
				{
					$this->error[] = "Không thể chuyển file " . $image[$pid]['title'];
				}
				else $this->success[] = "Chuyển thành công file " . $image[$pid]['title'];
			}
		}
		elseif( $update == 1 ) $this->updateItem( $current_catid, $copyData, 'pid' );
	}
	
	public function CheckAdminAccess($type, $id)
	{
		global $admin_id, $adminModuleData;
		
		if( in_array( $type, array( 'listcatid', 'listalbid' ) ) )
		{
			if( !in_array( $id, $adminModuleData[$admin_id][$type] ) )
			$this->warning[] = "You cannot access this action";
			return false;
		}
		elseif( in_array( $type, array( 'add_cat', 'del_cat', 'add_album', 'del_album', 'addphoto' ) ) )
		{
			if( $adminModuleData[$admin_id][$type] == 0 ) 
			{
				$this->warning[] = "You cannot " . $type . " item";
				return false;
			}
			else return true;
		}
	}
	
	/*
	* olddata = array
	* newdata = array
	* type = admins, category, album
	* key = userid, catid, albid
	* value = compare to key
	*/
	public function setAdminRoll( $newdata, $olddata, $where, $type, $key, $value )
	{
		if( !empty( $olddata ) ) 
		{
			$olddata = explode( ',', $olddata );
			$remove_data = array_diff( $olddata, $newdata );
			if( !empty( $remove_data ) )
			{
				foreach( $remove_data as $id )
				{
					$updatedata = $this->getItems( $where, $key, $key, $id );
					if( !empty( $updatedata[$id][$type] ) )
					{
						$updatedata[$id][$type] = explode( ',', $updatedata[$id][$type] );
						$updatedata[$id][$type] = array_diff( $updatedata[$id][$type], array( $value ) );
						$data = array( $key => $id, $type => implode( ',', $updatedata[$id][$type] ) );
						$this->updateItem( $where, $data, $key );
					}
				}
			}
		}
		
		if( !empty( $newdata ) )
		{
			foreach( $newdata as $id )
			{
				$updatedata = $this->getItems( $where, $key, $key, $id );
				if( !empty( $updatedata[$id][$type] ) )
				{
					$updatedata[$id][$type] = explode( ',', $updatedata[$id][$type] );
					$updatedata[$id][$type][] = $value;
					$updatedata[$id][$type] = array_unique( $updatedata[$id][$type] );
					$data = array( $key => $id, $type => implode( ',', $updatedata[$id][$type] ) );
					$this->updateItem( $where, $data, $key );
				}
				else
				{
					$data = array( $key => $id, $type => $value );
					$this->updateItem( $where, $data, $key );
				}
			}
		}
	}
}
		

?>