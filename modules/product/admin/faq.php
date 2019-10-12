<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Webdep24.com. All rights reserved
 * @Blog http://dangdinhtu.com
 * @Developers http://developers.dangdinhtu.com/
 * @License GNU/GPL version 2 or any later version
 * @Createdate  Mon, 20 Oct 2014 14:00:59 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$lang_ext = getLangAdmin( $op, 'product' );

if( ACTION_METHOD == 'delete' )
{
	$info = array();
	$faq_id = $nv_Request->get_int( 'faq_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );
	
	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] .  session_id() . $faq_id ) )
	{
		$del_array = array( $faq_id );
	}
 
	if( ! empty( $del_array ) )
	{
 
		$_del_array = array();
		
		$a = 0;
		
		foreach( $del_array as $faq_id )
		{
			$db->query('DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_faq WHERE faq_id = ' . (int)$faq_id );
 			
			
			$info['id'][$a] = $faq_id;
			
			$_del_array[] = $faq_id;
 
			++$a;
		}
 
		
		$count = sizeof( $del_array );
		if( $count )
		{
 			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_faq', implode( ', ', $_del_array ), $admin_info['userid'] );
			
			$ProductGeneral->deleteCache( 'faq' );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$info['success'] = $lang_ext['text_success'];
		}else
		{
 
			$info['error'] = $lang_ext['error_delete'] ;
		}
			
		
	}else
	{
		$info['error'] = $lang_ext['error_delete'];
	}
	echo json_encode( $info );
	exit();
}

if( ACTION_METHOD == 'delete_answer' )
{
	$info = array();
	$faq_id = $nv_Request->get_int( 'faq_id', 'post', 0 );
	$answer_id = $nv_Request->get_int( 'answer_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );
	
	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] .  session_id() . $answer_id ) )
	{
		$del_array = array( $answer_id );
	}
 
	if( ! empty( $del_array ) )
	{
 
		$_del_array = array();
		
		$a = 0;
		
		foreach( $del_array as $answer_id )
		{
			$db->query('DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_faq_answer WHERE faq_id = ' . (int)$faq_id . ' AND answer_id = ' . (int)$answer_id );
 			
			$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product_faq SET num_answer = num_answer - 1 WHERE faq_id=' . $faq_id );
					
			$info['id'][$a] = $answer_id;
			
			$_del_array[] = $answer_id;
 
			++$a;
		}
 
		
		$count = sizeof( $del_array );
		if( $count )
		{
 			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_answer', implode( ', ', $_del_array ), $admin_info['userid'] );
			
			$nv_Cache->delMod( $module_name );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$info['success'] = $lang_ext['text_success'];
		}else
		{
 
			$info['error'] = $lang_ext['error_delete'] ;
		}
			
		
	}else
	{
		$info['error'] = $lang_ext['error_delete'];
	}
	echo json_encode( $info );
	exit();
}
 
if( ACTION_METHOD == 'view_answer' )
{
	$error = array();
	$faq_id = $nv_Request->get_int( 'faq_id', 'get,post', 0 );
	
	$result = $db->query('SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_faq WHERE faq_id = ' . (int)$faq_id );
		 
	$data = $result->fetch();
	
	$dataContent_answer = array();
	
	if( $data['faq_id'] > 0 )
	{ 
		$result = $db->query('SELECT *
			FROM ' . TABLE_PRODUCT_NAME . '_product_faq_answer
 			WHERE  faq_id = ' . (int)$data['faq_id'] );
		 
		while( $rows = $result->fetch())
		{
			$dataContent_answer[] = $rows;
			
		}
 	
	}else
	{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		
	}
 
	$caption = $lang_ext['text_answer_list'];
 
	$xtpl = new XTemplate( 'faq_answer_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'AddMenu', AddMenu() );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'DATA', $data );
	if( !empty( $dataContent_answer ) )
	{
		foreach( $dataContent_answer as $answer )
		{
			$answer['token'] = md5( $global_config['sitekey'] . session_id() . $answer['answer_id'] );
 			$answer['edit_answer'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=edit_answer&token=" . $answer['token'] . "&answer_id=" . $answer['answer_id'] . "&faq_id=" . $answer['faq_id'];
 		
			$xtpl->assign( 'ANSWER', $answer );
			$xtpl->parse( 'main.answer' );
		}
	}
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}
 

if( ACTION_METHOD == 'answer' || ACTION_METHOD == 'edit_answer' )
{
	$error = array();
	$answer = '';
	
	$answer_id = $nv_Request->get_int( 'answer_id', 'get,post', 0 );
	$data['faq_id'] = $nv_Request->get_int( 'faq_id', 'get,post', 0 );
 
	if( $data['faq_id'] > 0 )
	{ 
		$result = $db->query('SELECT DISTINCT *, pd.name FROM  ' . TABLE_PRODUCT_NAME . '_product_faq f 
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (f.product_id = pd.product_id) 
		WHERE pd.language_id = ' . ( int )$ProductGeneral->current_language_id . ' AND faq_id = ' . (int)$data['faq_id'] );
		 
		$data = $result->fetch();
		
		$result = $db->query('SELECT answer_id, answer FROM  ' . TABLE_PRODUCT_NAME . '_product_faq_answer WHERE answer_id = '. $answer_id .' AND faq_id = ' . (int)$data['faq_id'] );
		 
		list( $answer_id, $answer ) = $result->fetch( 3 );
		
	}
	
	if( empty(  $data['faq_id'] ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
	
	if(  ACTION_METHOD == 'edit_answer' )
	{
		$caption = $lang_ext['text_edit_answer'];
	}
	elseif( ACTION_METHOD == 'answer' )
	{
		$caption = $lang_ext['text_answer'];
	}
	
	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{
 
		$data['faq_id'] = $nv_Request->get_int( 'faq_id', 'post', 0 );
		
		$status = $nv_Request->get_int( 'status', 'post', 0 );
		$answer_id = $nv_Request->get_int( 'answer_id', 'post', 0 );
		$answer = $nv_Request->get_textarea( 'answer', 'post', '', 'br', 1 );
		
		if( empty( $answer ) )
		{
			$error['answer'] = $lang_ext['error_answer'];
		}
		
		if( empty( $error ) )
		{
			if( $answer_id == 0 )
			{

				$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_faq_answer SET 
					faq_id = ' . ( int )$data['faq_id'] . ', 
					date_added = ' . ( int )NV_CURRENTTIME . ', 
					answer = :answer,
					status = ' . (int)$status );
 				$sth->bindParam( ':answer', $answer, PDO::PARAM_STR );
				$sth->execute();
				
		
				if( $answer_id = $db->lastInsertId() )
				{
					$sth->closeCursor();
 
					$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product_faq SET status=1, num_answer = num_answer + 1 WHERE faq_id=' . $data['faq_id'] );
					
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A faq answer', 'answer_id: ' . $answer_id, $admin_info['userid'] );
 					
 				}
				else
				{
					$error['warning'] = $lang_ext['error_save'];

				}

			}
			else
			{
				try
				{
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product_faq_answer SET 
						answer = :answer,
						status = ' . (int)$status.'
						WHERE answer_id='. $answer_id );

					$stmt->bindParam( ':answer', $answer, PDO::PARAM_STR );
 					
					if( $stmt->execute() )
					{
						$stmt->closeCursor();
 
						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A answer', 'answer_id: ' . $answer_id, $admin_info['userid'] );
						
						$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );
 
					}
 
					$stmt->closeCursor();

				}catch( PDOException $e )
				{
					$error['warning'] = $lang_ext['error_save'];
					//var_dump($e);
				}
 
			}
			if( empty( $error ) )
			{
				$ProductGeneral->deleteCache( 'faq' );
				
				$token = md5( $global_config['sitekey'] . session_id() . $old_faq_id );
				
				Header( 'Location: ' . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=view_answer&token=" . $token['token'] . "&faq_id=" . $data['faq_id'] );
				die();
			}
		}
	}
	
	$xtpl = new XTemplate( 'faq_answer.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'GLANG', $lang_global );
	//$xtpl->assign( 'AddMenu', AddMenu( ) );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'DATA', $data );
	$xtpl->assign( 'ANSWER', $answer );
	$xtpl->assign( 'ANSWER_ID', $answer_id );
	$xtpl->assign( 'JSON_PRODUCT', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=add" );
	
	foreach( $productArrayStatus as $key => $name )
	{
		$xtpl->assign( 'STATUS', array(
			'key' => $key,
			'name' => $name,
			'selected' => ( $key == $data['status'] && is_numeric( $data['status'] ) ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.status' );
	}
 
	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'error_warning', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}
 	if( isset( $error['answer']	 ) )
	{
		$xtpl->assign( 'error_answer', $error['answer'] );
		$xtpl->parse( 'main.error_answer' );
	}
 
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

if( ACTION_METHOD == 'edit_question' )
{
	$error = array();
	$data['faq_id'] = $nv_Request->get_int( 'faq_id', 'get,post', 0 );
	
	if( $data['faq_id'] > 0 )
	{ 
		$result = $db->query('SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_faq WHERE faq_id = ' . (int)$data['faq_id'] );
		 
		$data = $result->fetch();
 	
	}
	
	if( empty(  $data['faq_id'] ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
	
	if(  ACTION_METHOD == 'edit_question' )
	{
		$caption = $lang_ext['text_edit_question'];
	}
 
	
	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{
 
		$data['faq_id'] = $nv_Request->get_int( 'faq_id', 'post', 0 );
 		$data['status'] = $nv_Request->get_int( 'status', 'post', 0 );
		$data['question'] = $nv_Request->get_textarea( 'question', 'post', '', 'br', 1 );
		if( empty( $data['question'] ) )
		{
			$error['question'] = $lang_ext['error_question'];
		}
		
		if( empty( $error ) )
		{
			$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product_faq SET 
				status = '. (int)$data['status'] .',
				question = :question			
				WHERE faq_id='. $data['faq_id'] );
			
			$stmt->bindParam( ':question', $data['question'], PDO::PARAM_STR );
			$stmt->execute();
			$stmt->closeCursor();
			
			nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A question', 'faq_id: ' . $data['faq_id'], $admin_info['userid'] );
						
			$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );

			$ProductGeneral->deleteCache( 'faq' );
			
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();	
			 
		}
	}
	
	$xtpl = new XTemplate( 'faq_question.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'AddMenu', AddMenu() );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'DATA', $data );
	
	foreach( $productArrayStatus as $key => $name )
	{
		$xtpl->assign( 'STATUS', array(
			'key' => $key,
			'name' => $name,
			'selected' => ( $key == $data['status'] && is_numeric( $data['status'] ) ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.status' );
	}
 
	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'error_warning', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}
 	if( isset( $error['question']	 ) )
	{
		$xtpl->assign( 'error_question', $error['question'] );
		$xtpl->parse( 'main.error_question' );
	}
 
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}
 
 
 
$page_title = $lang_ext['heading_title'];

$base_url_sort = $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

$per_page = 50;

$page = $nv_Request->get_int( 'page', 'get', 1 );

$sort = $nv_Request->get_string( 'sort', 'get', '' );

$order = $nv_Request->get_string( 'order', 'get' ) == 'asc' ? 'asc' : 'desc';

$data['filter_product'] = $nv_Request->get_string( 'filter_product', 'get', '' );

$data['filter_customer_name'] = $nv_Request->get_string( 'filter_customer_name', 'get', '' );

$data['filter_status'] = $nv_Request->get_string( 'filter_status', 'get', '' );

$data['filter_date_added'] = $nv_Request->get_string( 'filter_date_added', 'get', '' );

 
 
$sql = TABLE_PRODUCT_NAME . '_product_faq f 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (f.product_id = pd.product_id) 
WHERE pd.language_id = ' . ( int )$ProductGeneral->current_language_id;

if( ! empty( $data['filter_product'] ) )
{
	$sql .= " AND pd.name LIKE '" . $db->dblikeescape( $data['filter_product'] ) . "%'";
}

if( ! empty( $data['filter_customer_name'] ) )
{
	$sql .= " AND f.customer_name LIKE '" . $db->dblikeescape( $data['customer_name'] ) . "%'";
}

if( isset( $data['filter_status'] ) && is_numeric( $data['filter_status'] ) )
{
	$sql .= " AND f.status = " . ( int )$data['filter_status'];
}

if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $data['filter_date_added'], $m ) )
{
	$date_added_start = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	$date_added_end = $date_added_start + 86399;

	$sql .= " AND f.date_added BETWEEN " . $date_added_start . " AND " . $date_added_end . "";
} 

$num_items = $db->query( 'SELECT COUNT(*) FROM ' . $sql )->fetchColumn();

$sort_data = array(
	'pd.name',
	'f.question',
	'f.customer_name',
	'f.num_answer',
	'f.status',
	'f.date_added' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{
	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY f.date_added";
}

if( isset( $data['order'] ) && ( $data['order'] == 'desc' ) )
{
	$sql .= " DESC";
}
else
{
	$sql .= " ASC";
}

$db->sqlreset()->select( 'f.faq_id, f.question, pd.name, f.customer_name, f.num_answer, f.status, f.date_added' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
 
$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$base_url .='&sort=' . $sort . '&order=' . $order . '&per_page=' . $per_page;

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';

$xtpl = new XTemplate( 'faq.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'AddMenu', AddMenu() );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=add" );
 
$xtpl->assign( 'URL_PRODUCT', $base_url_sort . '&amp;sort=pd.name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_CUSTOMER_NAME', $base_url_sort . '&amp;sort=f.customer_name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_QUESTION', $base_url_sort . '&amp;sort=f.question&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_NUM_ANSWER', $base_url_sort . '&amp;sort=f.num_answer&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_STATUS', $base_url_sort . '&amp;sort=f.status&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_DATE_ADDED', $base_url_sort . '&amp;sort=f.date_added&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'SORT_PRODUCT', ( $sort == 'pd.name' ) ? strtolower( $order2 ) : '' );
$xtpl->assign( 'SORT_CUSTOMER_NAME', ( $sort == 'f.customer_name' ) ? strtolower( $order2 ) : '' );
$xtpl->assign( 'SORT_QUESTION', ( $sort == 'f.question' ) ? strtolower( $order2 ) : '' );
$xtpl->assign( 'SORT_NUM_ANSWER', ( $sort == 'f.num_answer' ) ? strtolower( $order2 ) : '' );
$xtpl->assign( 'SORT_STATUS', ( $sort == 'f.status' ) ? strtolower( $order2 ) : '' );
$xtpl->assign( 'SORT_DATE_ADDED', ( $sort == 'f.date_added' ) ? strtolower( $order2 ) : '' );


if( $nv_Request->get_string( $module_data . '_success', 'session' ) ) 
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}

foreach( $productArrayStatus as $key => $name )
{
	$xtpl->assign( 'STATUS', array(
		'key' => $key,
		'name' => $name,
		'selected' => ( $key == $data['filter_status'] && is_numeric( $data['filter_status'] ) ) ? 'selected="selected"' : '' ) );
	$xtpl->parse( 'main.filter_status' );
}

if( ! empty( $dataContent ) )
{
	foreach( $dataContent as $item )
	{
		$item['status'] = $productArrayStatus[$item['status']];
		$item['date_added'] = date( 'd/m/Y', $item['date_added'] );
		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['faq_id'] );
		$item['answer'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=answer&token=' . $item['token'] . '&faq_id=' . $item['faq_id'];
		$item['edit_answer'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=edit_answer&token=' . $item['token'] . '&faq_id=' . $item['faq_id'];
		$item['view_answer'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=view_answer&token=' . $item['token'] . '&faq_id=' . $item['faq_id'];
		$item['edit_question'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=edit_question&token=' . $item['token'] . '&faq_id=' . $item['faq_id'];
		$xtpl->assign( 'LOOP', $item );

		$xtpl->parse( 'main.loop' );
	}

}
else
{
	$xtpl->parse( 'main.no_results' );
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
if( ! empty( $generate_page ) )
{

	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
