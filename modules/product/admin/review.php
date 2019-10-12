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
	$review_id = $nv_Request->get_int( 'review_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );
	
	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] .  session_id() . $review_id ) )
	{
		$del_array = array( $review_id );
	}
 
	if( ! empty( $del_array ) )
	{
 
		$_del_array = array();
		
		$a = 0;
		
		foreach( $del_array as $review_id )
		{
			$db->query('DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_review WHERE review_id = ' . (int)$review_id );
 			
			
			$info['id'][$a] = $review_id;
			
			$_del_array[] = $review_id;
 
			++$a;
		}
 
		
		$count = sizeof( $del_array );
		if( $count )
		{
 			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_review', implode( ', ', $_del_array ), $admin_info['userid'] );
			
			$ProductGeneral->deleteCache( 'review' );
			
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
 
if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{
	$data['review_id'] = 0;
	$data['status'] = 0;
	$data['rating'] = 0;
	$data['title'] = '';
	$data['detail'] = '';
	
	$data['review_id'] = $nv_Request->get_int( 'review_id', 'get,post', 0 );
	if( $data['review_id'] > 0 )
	{ 
		$result = $db->query('SELECT DISTINCT *, (SELECT pd.name FROM ' . TABLE_PRODUCT_NAME . '_product_description pd 
			WHERE pd.product_id = r.product_id AND pd.language_id = ' . (int)$ProductGeneral->current_language_id . ') product 
			FROM ' . TABLE_PRODUCT_NAME . '_product_review r 
			WHERE r.review_id = ' . (int)$data['review_id'] );
		
		$data = $result->fetch();
	 
		$caption = $lang_ext['text_edit'];
	}else
	{
		$caption = $lang_ext['text_add'];
	}
	
	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{
 
		$data['review_id'] = $nv_Request->get_int( 'review_id', 'post', 0 );
		$data['status'] = $nv_Request->get_int( 'status', 'post', 0 );
		$data['rating'] = $nv_Request->get_int( 'rating', 'post', 0 );
		$data['title'] = $nv_Request->get_title( 'title', 'post', '' );
		$data['detail'] = $nv_Request->get_textarea( 'detail', 'post', '', 'br', 1 );
		
		if( empty( $error ) )
		{
			if( $data['review_id'] == 0 )
			{
 
				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_review SET 
					title = :title, 
					detail = :detail' );
				$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
				$stmt->bindParam( ':detail', $data['detail'], PDO::PARAM_STR );
				$stmt->execute();
				
				if( $data['review_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();
					
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Review', 'review_id: ' . $data['review_id'], $admin_info['userid'] );
					
					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );

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
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product_review SET 
						status = '. (int)$data['status'] .',
						title =:title,
						detail =:detail,
						WHERE review_id='. $data['review_id'] );

					$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
					$stmt->bindParam( ':detail', $data['detail'], PDO::PARAM_STR );
					
					if( $stmt->execute() )
					{
						$stmt->closeCursor();
						 
						
						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A review', 'review_id: ' . $data['review_id'], $admin_info['userid'] );
						
						$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );

						$ProductGeneral->deleteCache( 'review' );
						Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
						die();
					}
 
					$stmt->closeCursor();

				}catch( PDOException $e )
				{
					$error['warning'] = $lang_module['error_save'];
					//var_dump($e);
				}
 
			}
			if( empty( $error ) )
			{
				$ProductGeneral->deleteCache( 'review' );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
				die();
			}
		}
	}
	
	$xtpl = new XTemplate( 'review_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'AddMenu', AddMenu( ) );
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
	$xtpl->assign( 'JSON_PRODUCT', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=add" );
	
	foreach( $productArrayStatus as $key => $name )
	{
		$xtpl->assign( 'STATUS', array(
			'key' => $key,
			'name' => $name,
			'selected' => ( $key == $data['status'] && is_numeric( $data['status'] ) ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.status' );
	}
	for( $i= 1; $i<= 5; ++$i )
	{
		$xtpl->assign( 'RATING', array(
			'key' => $i,
			'name' => $i,
			'checked' => ( $i == $data['rating'] ) ? 'checked="checked"' : '' ) );
		$xtpl->parse( 'main.rating' );
	}
	 
	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'error_warning', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}
 
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}



 
$page_title = $lang_ext['heading_title'];

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$data['filter_product'] = $nv_Request->get_string( 'filter_product', 'get', '' );
$data['filter_customer_name'] = $nv_Request->get_string( 'filter_customer_name', 'get', '' );
$data['filter_status'] = $nv_Request->get_string( 'filter_status', 'get', '' );
$data['filter_date_added'] = $nv_Request->get_string( 'filter_date_added', 'get', '' );

$data['sort'] = $nv_Request->get_string( 'sort', 'get', '' );
$data['order'] = $nv_Request->get_string( 'order', 'get' ) == 'asc' ? 'asc' : 'desc';

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=' . $data['sort'] . '&amp;order=' . $data['order'] . '&amp;per_page=' . $per_page;

$sql = TABLE_PRODUCT_NAME . '_product_review r 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (r.product_id = pd.product_id) 
WHERE pd.language_id = ' . ( int )$ProductGeneral->current_language_id;

if( ! empty( $data['filter_product'] ) )
{
	$sql .= " AND pd.name LIKE '" . $db->dblikeescape( $data['filter_product'] ) . "%'";
}

if( ! empty( $data['filter_customer_name'] ) )
{
	$sql .= " AND r.customer_name LIKE '" . $db->dblikeescape( $data['customer_name'] ) . "%'";
}

if( isset( $data['filter_status'] ) && is_numeric( $data['filter_status'] ) )
{
	$sql .= " AND r.status = " . ( int )$data['filter_status'];
}

if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $data['filter_date_added'], $m ) )
{
	$date_added_start = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	$date_added_end = $date_added_start + 86399;

	$sql .= " AND r.date_added BETWEEN " . $date_added_start . " AND " . $date_added_end . "";
}

$num_items = $db->query( 'SELECT COUNT(*) FROM ' . $sql )->fetchColumn();

$sort_data = array(
	'pd.name',
	'r.customer_name',
	'r.rating',
	'r.status',
	'r.date_added' );

if( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) )
{
	$sql .= " ORDER BY " . $data['sort'];
}
else
{
	$sql .= " ORDER BY r.date_added";
}

if( isset( $data['order'] ) && ( $data['order'] == 'desc' ) )
{
	$sql .= " DESC";
}
else
{
	$sql .= " ASC";
}

$db->sqlreset()->select( 'r.review_id, pd.name, r.customer_name, r.rating, r.status, r.date_added' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$array = array();
while( $rows = $result->fetch() )
{
	$array[] = $rows;
}

$xtpl = new XTemplate( 'review.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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

$order2 = ( $data['order'] == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_PRODUCT', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=pd.name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_CUSTOMER_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=r.customer_name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_STATUS', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=r.status&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_RATING', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=r.rating&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_DATE_ADDED', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=r.date_added&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'SORT_PRODUCT', ( $data['sort'] == 'pd.name' ) ? strtolower( $order2 ) : '' );
$xtpl->assign( 'SORT_CUSTOMER_NAME', ( $data['sort'] == 'r.customer_name' ) ? strtolower( $order2 ) : '' );
$xtpl->assign( 'SORT_RATING', ( $data['sort'] == 'r.rating' ) ? strtolower( $order2 ) : '' );
$xtpl->assign( 'SORT_STATUS', ( $data['sort'] == 'r.status' ) ? strtolower( $order2 ) : '' );
$xtpl->assign( 'SORT_DATE_ADDED', ( $data['sort'] == 'r.date_added' ) ? strtolower( $order2 ) : '' );

$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=add" );

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

if( ! empty( $array ) )
{
	foreach( $array as $item )
	{
		$item['status'] = $productArrayStatus[$item['status']];
		$item['date_added'] = date( 'd/m/Y', $item['date_added'] );
		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['review_id'] );
		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=edit&token=" . $item['token'] . "&review_id=" . $item['review_id'];
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
