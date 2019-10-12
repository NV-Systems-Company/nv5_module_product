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

$lang_ext = getLangAdmin( $op, 'localisation' );

$page_title = $lang_ext['heading_title']; 
 
$currencies_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/currencies.ini', true );
 
if( ACTION_METHOD == 'delete' )
{
	$json = array();
	$currency_id = $nv_Request->get_int( 'currency_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );
	
	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] .  session_id() . $currency_id ) )
	{
		$del_array = array( $currency_id );
	}
	
	 
	if( ! empty( $del_array ) )
	{
		$json['error'] = '';
		$sql= 'SELECT currency_id, title, code FROM ' . TABLE_PRODUCT_NAME . '_currency WHERE currency_id IN (' . implode( ',', $del_array ) .')';
		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$artitle = array();
		$a = 0;
		while( list( $currency_id, $title, $code ) = $result->fetch( 3 ) )
		{
			if( $ProductGeneral->config['config_currency'] == $code )
			{
				$json['error'] = $lang_ext['error_default']; 
			}
			
			$order_total = $db->query('SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_order WHERE currency_code = '. $db->quote( $code ) )->fetchColumn( );
			
			if( (int)$order_total > 0 )
			{
				$json['error'] = sprintf( $lang_ext('error_order'), $order_total ); 
			}
			if( empty( $json['error'] ) )
			{
				if( $db->query('DELETE FROM ' . TABLE_PRODUCT_NAME . '_currency WHERE currency_id = ' . (int)$currency_id) )
				{
 
					$json['currency_id'][$a] = $currency_id;
					$del_array[] = $currency_id;
					$artitle[] = $title;
					++$a;
				}
				else
				{
					$no_del_array[] = $currency_id;
				}
			}
		}

		$count = sizeof( $del_array );
		if( $count )
		{
 			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_currency', implode( ', ', $artitle ), $admin_info['userid'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$nv_Cache->delMod( $module_name );
			
			$json['success'] = $lang_ext['text_delete_success'] ;
		}
		if( ! empty( $no_del_array ) )
		{
 
			$json['error'] = $lang_ext['error_delete'];
		}
			
		
	}else
	{
		$json['error'] = $lang_ext['error_delete'];
	}
	nv_jsonOutput( $json );
 
}
elseif( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$data = array(
		'currency_id' => '',
		'title' => '',
		'code' => '',
		'symbol_left' => '',
		'symbol_right' => '',
		'decimal_place' => '',
		'value' => 0,
		'status' => 1,
		'date_modified' => NV_CURRENTTIME,
		
	);
	$error = array();
	$error_key = array();
	
	$data['currency_id'] = $nv_Request->get_int( 'currency_id', 'get,post', 0 );
	
	if( $data['currency_id'] > 0 )
	{  
 
		
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_currency  
		WHERE currency_id=' . $data['currency_id'] )->fetch();
		
		$data['value'] = floatFormat( $data['value'] );
		
		$caption = $lang_ext['text_edit'];
	}else{
		$caption = $lang_ext['text_add'];
	}
 
	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{
		$data['currency_id'] = $nv_Request->get_int( 'currency_id', 'post', 0 );
		$data['title'] = $nv_Request->get_title( 'title', 'post', '' );
		$data['code'] = $nv_Request->get_title( 'code', 'post', '' );
		$data['symbol_left'] = $nv_Request->get_title( 'symbol_left', 'post', '' );
		$data['symbol_right'] = $nv_Request->get_title( 'symbol_right', 'post', '' );
		$data['decimal_place'] = $nv_Request->get_int( 'decimal_place', 'post', 0 );
		$data['value'] = $nv_Request->get_float( 'value', 'post', 0 );
		$data['status'] = $nv_Request->get_int( 'status', 'post', 0 );
  
		if( nv_strlen( $data['title'] ) < 2 || nv_strlen( $data['title'] ) > 32 )
		{
			$error['currency_error_title'] = $lang_ext['error_title'];
			$error_key[] = 'currency_error_title';
		}
 
		if( nv_strlen( $data['code'] ) < 2 || nv_strlen( $data['code'] ) > 32 )
		{
			$error['currency_error_code'] = $lang_ext['error_code'];
			$error_key[] = 'currency_error_code';
		}
 

		if( empty( $error_key ) )
		{
			if( $data['currency_id'] == 0 )
			{

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_currency SET 
				title = :title, 
				code = :code, 
				symbol_left = :symbol_left, 
				symbol_right = :symbol_right, 
				decimal_place = '. intval ( $data['decimal_place'] ) .', 
				value = '. (float) $data['value'] . ',
				status = '. intval( $data['status'] ) . ',
				date_modified=' . intval( $data['date_modified'] ));

				$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
				$stmt->bindParam( ':code', $data['code'], PDO::PARAM_STR );
				$stmt->bindParam( ':symbol_left', $data['symbol_left'], PDO::PARAM_STR );
				$stmt->bindParam( ':symbol_right', $data['symbol_right'], PDO::PARAM_STR );
 				
				$stmt->execute();

				if( $data['currency_id'] = $db->lastInsertId() )
				{

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A currency', 'currency_id: ' . $data['currency_id'], $admin_info['userid'] );
					
					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );
					
					$nv_Cache->delMod( $module_name );
				}
				else
				{
					$error[] = $lang_ext['error_save'];

				}

				$stmt->closeCursor();
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_currency SET 
				title = :title, 
				code = :code, 
				symbol_left = :symbol_left, 
				symbol_right = :symbol_right, 
				decimal_place = '. intval ( $data['decimal_place'] ) .', 
				value = '. (float) $data['value'] . ',
				status = '. intval( $data['status'] ) . ',
				date_modified= ' . NV_CURRENTTIME . '
				WHERE currency_id=' . $data['currency_id'] );

				$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
				$stmt->bindParam( ':code', $data['code'], PDO::PARAM_STR );
				$stmt->bindParam( ':symbol_left', $data['symbol_left'], PDO::PARAM_STR );
				$stmt->bindParam( ':symbol_right', $data['symbol_right'], PDO::PARAM_STR );

				if( $stmt->execute() )
				{
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A currency', 'currency_id: ' . $data['currency_id'], $admin_info['userid'] );
					
					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_update_success'] );
					
					$nv_Cache->delMod( $module_name );
					
				}
				else
				{
					$error[] = $lang_ext['error_save'];

				}

				$stmt->closeCursor();

			}

		}
		if( empty( $error ) )
		{
			$nv_Cache->delMod( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=currency' );
			die();
		}

	}
	
	$lang_ext['help_code'] = nv_htmlspecialchars ( $lang_ext['help_code'] );
	$lang_ext['help_value'] = nv_htmlspecialchars ( $lang_ext['help_value'] );
 
	$xtpl = new XTemplate( 'currency_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file. '/localisation');
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'AddMenu', AddMenu( ) );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_FILE', $module_file);
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'DATA', $data );
	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
 
	ksort( $currencies_array );

	foreach( $currencies_array as $code => $value )
	{
 
			$array_temp = array( );
			$array_temp['value'] = $code;
			$array_temp['title'] = $code . ' - ' . $value['currency'];
			$array_temp['selected'] = ( $code == $data['code'] ) ? ' selected="selected"' : '';

			$xtpl->assign( 'DATAMONEY', $array_temp );

			$xtpl->parse( 'main.currency' );
 
	}
	foreach( $productArrayStatus as $status_key => $status_name )
	{
 
		$xtpl->assign( 'status_checked', ( $status_key == $data['status'] ) ? 'selected="selected"' : '' );
		$xtpl->assign( 'status_key', $status_key );
		$xtpl->assign( 'status_name', $status_name );
		$xtpl->parse( 'main.status' );
 
	}
	 
	foreach( $error_key as $key )
	{
		if( ! empty( $error[$key] ) )
		{
			$xtpl->assign( $key, $error[$key] );
			$xtpl->parse( 'main.' . $key );
		}
	}
 
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
 
}

/*show list currency*/
 
$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_currency';

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'title', 'code', 'value', 'date_modified' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{
 
	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= ' ORDER BY title';
}

if( isset( $order ) && ( $order == 'desc' ) )
{
	$sql .= " DESC";
}
else
{
	$sql .= " ASC";
}

$num_items = $db->query( 'SELECT COUNT(*) FROM ' . $sql )->fetchColumn();


$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '='. $op .'&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;


$db->sqlreset()->select( '*' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$array = array();
while( $rows = $result->fetch() )
{  
	$array[] = $rows;
}

$xtpl = new XTemplate( 'currency.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'AddMenu', AddMenu( ) );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_FILE', $module_file);
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
 
$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_TITLE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=title&amp;order=' . $order2 . '&amp;per_page=' . $per_page);
$xtpl->assign( 'URL_CODE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=code&amp;order=' . $order2 . '&amp;per_page=' . $per_page);
$xtpl->assign( 'URL_VALUE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=value&amp;order=' . $order2 . '&amp;per_page=' . $per_page);
$xtpl->assign( 'URL_DATE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=date_modified&amp;order=' . $order2 . '&amp;per_page=' . $per_page);
 
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '='. $op .'&action=add' );

if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}
 
if( !empty( $array ) )
{
	foreach( $array as $item )
	{
		
 		$item['date_modified'] = date( 'd/m/Y', $item['date_modified'] );
		
		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['currency_id'] );
	
		$item['value'] = floatFormat( $item['value'] );	
		
		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '='. $op .'&action=edit&token='.$item['token'].'&currency_id=' . $item['currency_id'];
		
		if( $item['code'] == $ProductGeneral->config['config_currency'] )
		{
			$xtpl->parse( 'main.loop.default' );
		}
		$xtpl->assign( 'LOOP', $item );
 
		$xtpl->parse( 'main.loop' );
	}

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
