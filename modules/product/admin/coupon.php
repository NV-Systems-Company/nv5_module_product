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

$lang_ext = getLangAdmin( $op, 'maketing' );

$page_title = $lang_ext['heading_title'];

if( $nv_Request->get_int( 'get_product', 'get', 0 ) == 1 )
{
	$json = array();

	$from = TABLE_PRODUCT_NAME . '_product a 
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description b ON (a.product_id = b.product_id)
	WHERE b.language_id=' . $ProductGeneral->current_language_id;

	$filter_name = $nv_Request->get_string( 'filter_name', 'get', '' );
	if( ! empty( $filter_name ) )
	{
		$from .= " AND b.name LIKE '%" . $db->dblikeescape( $filter_name ) . "%' ";
	}
	$db->sqlreset()->select( 'a.product_id, b.name' )->from( $from )->order( 'a.publtime DESC, b.name ASC' )->limit( 10 )->offset( 0 );
	$result = $db->query( $db->sql() );
	$a = 0;
	while( list( $product_id, $name ) = $result->fetch( 3 ) )
	{
		$json[$a] = array( 'product_id' => $product_id, 'name' => $name );
		++$a;
	}
	nv_jsonOutput( $json );

}
elseif( $nv_Request->get_int( 'get_category', 'get', 0 ) == 1 )
{
	$json = array();

	$from = TABLE_PRODUCT_NAME . '_category a 
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_category_description b ON (a.category_id = b.category_id)
	WHERE b.language_id=' . $ProductGeneral->current_language_id;

	$filter_name = $nv_Request->get_string( 'filter_name', 'get', '' );
	if( ! empty( $filter_name ) )
	{
		$from .= " AND b.name LIKE '%" . $db->dblikeescape( $filter_name ) . "%' ";
	}
	$db->sqlreset()->select( 'a.category_id, a.parent_id, a.lev, b.name' )->from( $from )->order( 'a.sort ASC' )->limit( 10 )->offset( 0 );
	$result = $db->query( $db->sql() );

	$array_cat = array();
	while( list( $category_id, $parent_id, $lev, $name ) = $result->fetch( 3 ) )
	{
		$array_cat[$category_id] = array(
			'category_id' => $category_id,
			'parent_id' => $parent_id,
			'lev' => $lev,
			'name' => $name );
	}
	$a = 0;
	foreach( $array_cat as $_categoryid => $cat )
	{

		$name = $productCategory[$_categoryid]['name'];
		$parent_id = $productCategory[$_categoryid]['parent_id'];
		if( isset( $productCategory[$parent_id] ) )
		{
			$name = $productCategory[$parent_id]['name'] . ' --> ' . $name;
			$parent_id = $productCategory[$parent_id]['parent_id'];
			if( isset( $productCategory[$parent_id] ) )
			{
				$name = $productCategory[$parent_id]['name'] . ' --> ' . $name;
			}
		}

		$json[$a] = array( 'category_id' => $cat['category_id'], 'name' => $name );
		++$a;
	}

	nv_jsonOutput( $json );


}
elseif( ACTION_METHOD == 'delete' )
{
	$json = array();
	$coupon_id = $nv_Request->get_int( 'coupon_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $coupon_id ) )
	{
		$del_array = array( $coupon_id );
	}

	if( ! empty( $del_array ) )
	{
		$a = 0;
		$_del_array = array();
		$no_del_array = array();
		foreach( $del_array as $coupon_id )
		{

			$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_coupon WHERE coupon_id=' . ( int )$coupon_id );
			$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_coupon_product WHERE coupon_id=' . ( int )$coupon_id );
			$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_coupon_category WHERE coupon_id=' . ( int )$coupon_id );
			$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_coupon_history WHERE coupon_id=' . ( int )$coupon_id );

			$json['id'][$a] = $coupon_id;

			$_del_array[] = $coupon_id;

			++$a;
		}

		if( sizeof( $_del_array ) )
		{
			$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );

			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_coupon', $coupon_id, $admin_info['userid'] );
 
			$nv_Cache->delMod( $module_name );

			$json['success'] = $lang_ext['text_delete_success'];
		}
		if( ! empty( $no_del_array ) )
		{

			$info['error'] = $lang_ext['error_no_delete'];
		}

	}
	else
	{
		$json['error'] = $lang_ext['error_no_delete'];
	}
	nv_jsonOutput( $json );
}

$getTypeForm = array( 'F' => $lang_ext['text_amount'], 'P' => $lang_ext['text_percent'] );

if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$data = array(
		'coupon_id' => '',
		'name' => '',
		'code' => '',
		'type' => 'P',
		'discount' => '',
		'total' => '',
		'logged' => 0,
		'shipping' => 0,
		'product' => '',
		'category' => '',
		'date_start' => 0,
		'date_end' => 0,
		'uses_total' => 1,
		'uses_customer' => 1,
		'status' => 1,
		'coupon_product' => array(),
		'coupon_category' => array(),
		);
	$phour = $pmin = $ehour = $emin = 0;
	$date_start = $date_end = '';
	$error = array();

	$data['coupon_id'] = $nv_Request->get_int( 'coupon_id', 'get,post', 0 );

	if( $data['coupon_id'] > 0 )
	{
		$data = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_coupon WHERE coupon_id=' . $data['coupon_id'] )->fetch();
		$data['discount'] = $ProductCurrency->format( $data['discount'], false, true, false );
		$data['total'] = $ProductCurrency->format( $data['total'], false, true, false );
		if( ! empty( $data['coupon_id'] ) )
		{
			$sql = 'SELECT product_id FROM ' . TABLE_PRODUCT_NAME . '_coupon_product WHERE coupon_id=' . $data['coupon_id'];
			$result = $db->query( $sql );

			while( list( $product_id_i ) = $result->fetch( 3 ) )
			{
				$data['coupon_product'][] = $product_id_i;
			}

			$sql = 'SELECT category_id FROM ' . TABLE_PRODUCT_NAME . '_coupon_category WHERE coupon_id=' . $data['coupon_id'];
			$result = $db->query( $sql );

			while( list( $category_id_i ) = $result->fetch( 3 ) )
			{
				$data['coupon_category'][] = $category_id_i;
			}

		}

	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{
		$data['coupon_product'] = array_unique( $nv_Request->get_typed_array( 'coupon_product', 'post', 'int', array() ) );
		$data['coupon_category'] = array_unique( $nv_Request->get_typed_array( 'coupon_category', 'post', 'int', array() ) );

		$data['coupon_id'] = $nv_Request->get_int( 'coupon_id', 'post', 0 );
		$data['name'] = $nv_Request->get_title( 'name', 'post', '' );
		$data['code'] = $nv_Request->get_title( 'code', 'post', '' );
		$data['type'] = $nv_Request->get_title( 'type', 'post', '' );
		$data['discount'] = $nv_Request->get_float( 'discount', 'post', 0 );
		$data['total'] = $nv_Request->get_float( 'total', 'post', 0 );
		$data['logged'] = $nv_Request->get_int( 'logged', 'post', 0 );
		$data['shipping'] = $nv_Request->get_int( 'shipping', 'post', 0 );

		$data['uses_total'] = $nv_Request->get_int( 'uses_total', 'post', 1 );
		$data['uses_customer'] = $nv_Request->get_int( 'uses_customer', 'post', 1 );
		$data['status'] = $nv_Request->get_float( 'status', 'post', 0 );

		if( empty( $data['name'] ) )
		{
			$error['name'] = $lang_ext['error_name'];
		}

		if( empty( $data['code'] ) )
		{
			$error['code'] = $lang_ext['error_code'];
		}

		if( ! in_array( $data['type'], array( 'P', 'F' ) ) )
		{
			$error['type'] = $lang_ext['error_type'];
		}

		if( $data['coupon_id'] > 0 )
		{
			if( $db->query( 'SELECT DISTINCT * FROM ' . TABLE_PRODUCT_NAME . '_coupon WHERE code = ' . $db->quote( $data['code'] ) . ' AND coupon_id != ' . $data['coupon_id'] )->fetchColumn() )
			{
				$error['exists'] = $lang_module['error_exists'];
			}
		}

		$date_start = $nv_Request->get_title( 'date_start', 'post', '' );
		$date_end = $nv_Request->get_title( 'date_end', 'post', '' );

		if( ! empty( $date_start ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $date_start ) ) $date_start = '';
		if( ! empty( $date_end ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $date_end ) ) $date_end = '';

		if( empty( $date_start ) )
		{
			$data['date_start'] = 0;
			$error['date_start'] = $lang_ext['error_date_start'];
		}
		else
		{
			$phour = $nv_Request->get_int( 'phour', 'post', 0 );
			$pmin = $nv_Request->get_int( 'pmin', 'post', 0 );
			unset( $m );
			preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $date_start, $m );
			$data['date_start'] = mktime( $phour, $pmin, 0, $m[2], $m[1], $m[3] );
		}

		if( empty( $date_end ) )
		{
			$data['date_end'] = 0;
			$error['date_end'] = $lang_ext['error_date_end'];

		}
		else
		{
			$ehour = $nv_Request->get_int( 'ehour', 'post', 0 );
			$emin = $nv_Request->get_int( 'emin', 'post', 0 );
			unset( $m );
			preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $date_end, $m );
			$data['date_end'] = mktime( $ehour, $emin, 0, $m[2], $m[1], $m[3] );
		}

		if( empty( $error ) )
		{
			if( $data['coupon_id'] == 0 )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_coupon SET 
					name = :name, 
					code = :code, 
					discount = ' . ( float )$data['discount'] . ', 
					type = :type, 
					total = ' . ( float )$data['total'] . ', 
					logged = ' . intval( $data['logged'] ) . ', 
					shipping = ' . intval( $data['shipping'] ) . ', 
					date_start = ' . intval( $data['date_start'] ) . ', 
					date_end = ' . intval( $data['date_end'] ) . ', 
					uses_total = ' . intval( $data['uses_total'] ) . ', 
					uses_customer = ' . intval( $data['uses_customer'] ) . ', 
					status = ' . intval( $data['status'] ) . ', 
					date_added = ' . NV_CURRENTTIME );

				$stmt->bindParam( ':name', $data['name'], PDO::PARAM_STR );
				$stmt->bindParam( ':code', $data['code'], PDO::PARAM_STR );
				$stmt->bindParam( ':type', $data['type'], PDO::PARAM_STR );

				$stmt->execute();

				if( $data['coupon_id'] = $db->lastInsertId() )
				{
					if( isset( $data['coupon_product'] ) )
					{
						foreach( $data['coupon_product'] as $product_id )
						{
							$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_coupon_product SET coupon_id = ' . ( int )$data['coupon_id'] . ', product_id = ' . ( int )$product_id );
						}
					}

					if( isset( $data['coupon_category'] ) )
					{
						foreach( $data['coupon_category'] as $category_id )
						{
							$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_coupon_category SET coupon_id = ' . ( int )$data['coupon_id'] . ', category_id = ' . ( int )$category_id );
						}
					}

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Coupon', 'coupon_id: ' . $data['coupon_id'], $admin_info['userid'] );
					
					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );

					
				}
				else
				{
					$error['warning'] = $lang_module['errorsave'];

				}

				$stmt->closeCursor();
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_coupon SET 
					name = :name, 
					code = :code, 
					discount = ' . ( float )$data['discount'] . ', 
					type = :type, 
					total = ' . ( float )$data['total'] . ', 
					logged = ' . intval( $data['logged'] ) . ', 
					shipping = ' . intval( $data['shipping'] ) . ', 
					date_start = ' . intval( $data['date_start'] ) . ', 
					date_end = ' . intval( $data['date_end'] ) . ', 
					uses_total = ' . intval( $data['uses_total'] ) . ', 
					uses_customer = ' . intval( $data['uses_customer'] ) . ', 
					status = ' . intval( $data['status'] ) . '
					WHERE coupon_id=' . $data['coupon_id'] );

				$stmt->bindParam( ':name', $data['name'], PDO::PARAM_STR );
				$stmt->bindParam( ':code', $data['code'], PDO::PARAM_STR );
				$stmt->bindParam( ':type', $data['type'], PDO::PARAM_STR );

				if( $stmt->execute() )
				{
					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_coupon_product WHERE coupon_id = ' . ( int )$data['coupon_id'] );
					if( isset( $data['coupon_product'] ) )
					{
						foreach( $data['coupon_product'] as $product_id )
						{
							$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_coupon_product SET coupon_id = ' . ( int )$data['coupon_id'] . ', product_id = ' . ( int )$product_id );
						}
					}

					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_coupon_category WHERE coupon_id = ' . ( int )$data['coupon_id'] );
					if( isset( $data['coupon_category'] ) )
					{
						foreach( $data['coupon_category'] as $category_id )
						{
							$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_coupon_category SET coupon_id = ' . ( int )$data['coupon_id'] . ', category_id = ' . ( int )$category_id );
						}
					}

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Coupon', 'coupon_id: ' . $data['coupon_id'], $admin_info['userid'] );
					
					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_update_success'] );
				}
				else
				{
					$error['warning'] = $lang_module['errorsave'];

				}

				$stmt->closeCursor();

			}

		}
		if( empty( $error ) )
		{
			$nv_Cache->delMod( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=coupon' );
			die();
		}

	}
	if( $data['date_start'] == 0 )
	{
		$emin = $ehour = 0;
		$date_start = '';
	}
	else
	{
		$date_start = date( 'd/m/Y', $data['date_start'] );
		$tdate = date( 'H|i', $data['date_start'] );
		list( $phour, $pmin ) = explode( '|', $tdate );
	}

	if( $data['date_end'] == 0 )
	{
		$emin = $ehour = 0;
		$exp_date = '';
	}
	else
	{
		$date_end = date( 'd/m/Y', $data['date_end'] );
		$tdate = date( 'H|i', $data['date_end'] );
		list( $ehour, $emin ) = explode( '|', $tdate );
	}

	$xtpl = new XTemplate( 'coupon_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/maketing' );
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
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'DATA', $data );

	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	$xtpl->assign( 'JSON_PRODUCT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&get_product=1' );
	$xtpl->assign( 'JSON_CATEGORY', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&get_category=1' );

	if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
	{
		$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

		$xtpl->parse( 'main.success' );

		$nv_Request->unset_request( $module_data . '_success', 'session' );

	}

	foreach( $getTypeForm as $key => $name )
	{
		$xtpl->assign( 'TYPE', array( 'key'=> $key, 'name'=>  $name, 'selected'=> ( $key == $data['type'] ) ? 'selected="selected"' : '') );
		$xtpl->parse( 'main.type' );
	}

	foreach( $productArrayStatus as $key => $name )
	{
		$xtpl->assign( 'STATUS', array( 'key'=> $key, 'name'=>  $name, 'selected'=> ( $key == $data['status'] ) ? 'selected="selected"' : '') );
		$xtpl->parse( 'main.status' );
	}

	foreach( $productArrayYesNo as $key => $name )
	{
 
		$xtpl->assign( 'LOGIN', array( 'key'=> $key, 'name'=>  $name, 'checked'=> ( $key == $data['logged'] ) ? 'selected="selected"' : '') );
		$xtpl->parse( 'main.login' );
	}

	foreach( $productArrayYesNo as $key => $name )
	{
		$xtpl->assign( 'SHIPPING', array( 'key'=> $key, 'name'=>  $name, 'checked'=> ( $key == $data['shipping'] ) ? 'selected="selected"' : '') );		
		$xtpl->parse( 'main.shipping' );
	}

	if( ! empty( $data['coupon_product'] ) )
	{

		$sql = 'SELECT a.product_id, b.name FROM ' . TABLE_PRODUCT_NAME . '_product a 
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description b ON (a.product_id = b.product_id)
		WHERE b.language_id=' . $ProductGeneral->current_language_id . ' AND a.product_id IN ( ' . implode( ',', $data['coupon_product'] ) . ' )';

		$result = $db->query( $sql );

		while( list( $product_id, $name ) = $result->fetch( 3 ) )
		{
			$xtpl->assign( 'PRODUCT_ID', $product_id );
			$xtpl->assign( 'PRODUCT_NAME', $name );
			$xtpl->parse( 'main.product' );
		}
	}
	if( ! empty( $data['coupon_category'] ) )
	{

		foreach( $data['coupon_category'] as $category_id )
		{
			$name = $productCategory[$category_id]['name'];
			$parent_id = $productCategory[$category_id]['parent_id'];
			if( isset( $productCategory[$parent_id] ) )
			{
				$name = $productCategory[$parent_id]['name'] . ' --> ' . $name;
				$parent_id = $productCategory[$parent_id]['parent_id'];
				if( isset( $productCategory[$parent_id] ) )
				{
					$name = $productCategory[$parent_id]['name'] . ' --> ' . $name;
				}
			}

			$xtpl->assign( 'CATEGORY_ID', $category_id );
			$xtpl->assign( 'CATEGORY_NAME', $name );
			$xtpl->parse( 'main.category' );
		}

	}

	// Time update
	$xtpl->assign( 'date_start', $date_start );
	$select = '';
	for( $i = 0; $i <= 23; $i++ )
	{
		$select .= '<option value="' . $i . '"' . ( ( $i == $phour ) ? ' selected="selected"' : '' ) . '>' . str_pad( $i, 2, '0', STR_PAD_LEFT ) . '</option>\n';
	}
	$xtpl->assign( 'phour', $select );

	$select = '';
	for( $i = 0; $i < 60; $i++ )
	{
		$select .= '<option value="' . $i . '"' . ( ( $i == $pmin ) ? ' selected="selected"' : '' ) . '>' . str_pad( $i, 2, '0', STR_PAD_LEFT ) . '</option>\n';
	}
	$xtpl->assign( 'pmin', $select );

	// Time exp
	$xtpl->assign( 'date_end', $date_end );
	$select = '';
	for( $i = 0; $i <= 23; $i++ )
	{
		$select .= '<option value="' . $i . '"' . ( ( $i == $ehour ) ? ' selected="selected"' : '' ) . '>' . str_pad( $i, 2, '0', STR_PAD_LEFT ) . '</option>\n';
	}
	$xtpl->assign( 'ehour', $select );

	$select = '';
	for( $i = 0; $i < 60; $i++ )
	{
		$select .= '<option value="' . $i . '"' . ( ( $i == $emin ) ? ' selected="selected"' : '' ) . '>' . str_pad( $i, 2, '0', STR_PAD_LEFT ) . '</option>\n';
	}
	$xtpl->assign( 'emin', $select );

	if( isset( $error['name'] ) )
	{
		$xtpl->assign( 'error_name', $error['name'] );
		$xtpl->parse( 'main.error_name' );
	}

	if( isset( $error['code'] ) )
	{
		$xtpl->assign( 'error_code', $error['code'] );
		$xtpl->parse( 'main.error_code' );
	}

	if( isset( $error['exists'] ) )
	{
		$xtpl->assign( 'error_exists', $error['exists'] );
		$xtpl->parse( 'main.error_exists' );
	}
	if( isset( $error['type'] ) )
	{
		$xtpl->assign( 'error_type', $error['type'] );
		$xtpl->parse( 'main.error_type' );
	}
	if( isset( $error['date_start'] ) )
	{
		$xtpl->assign( 'error_date_start', $error['date_start'] );
		$xtpl->parse( 'main.error_date_start' );
	}
	if( isset( $error['date_end'] ) )
	{
		$xtpl->assign( 'error_date_end', $error['date_end'] );
		$xtpl->parse( 'main.error_date_end' );
	}

	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'WARNING', $error['warning'] );
		$xtpl->parse( 'main.warning' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}

/*show list coupon*/

$base_url_sort = $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
 

$per_page = 100;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_coupon';

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'asc' ? 'asc' : 'desc';

$sort_data = array(
	'name',
	'code',
	'discount',
	'date_start',
	'date_end',
	'status' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{
	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY name";
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
 
$db->sqlreset()->select( '*' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$base_url .='&sort=' . $sort . '&order=' . $order . '&per_page=' . $per_page;

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';

$xtpl = new XTemplate( 'coupon.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/maketing' );
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
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );


$xtpl->assign( 'URL_NAME', $base_url_sort . '&amp;sort=name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_CODE', $base_url_sort . '&amp;sort=code&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_DISCOUNT', $base_url_sort . '&amp;sort=discount&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_DATE_START', $base_url_sort . '&amp;sort=date_start&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_DATE_END', $base_url_sort . '&amp;sort=date_end&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_STATUS', $base_url_sort . '&amp;sort=status&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
 
$xtpl->assign( 'NAME_ORDER', ( $sort == 'name' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'CODE_ORDER', ( $sort == 'code' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'DISCOUNT_ORDER', ( $sort == 'discount' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'DATE_START_ORDER', ( $sort == 'date_start' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'DATE_END_ORDER', ( $sort == 'date_end' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'WEIGHT_ORDER', ( $sort == 'weight' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'STATUS_ORDER', ( $sort == 'status' ) ? 'class="' . $order2 . '"' : '' );



$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=add" );

if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}

if( ! empty( $dataContent ) )
{ 
	foreach( $dataContent as $item )
	{ 

		$item['discount'] = $ProductCurrency->format( $item['discount'], $ProductGeneral->config['config_currency'], true, false );
		$item['total'] = $ProductCurrency->format( $item['total'], $ProductGeneral->config['config_currency'], true, false );
		$item['status'] = $productArrayStatus[$item['status']];
		$item['date_start'] = date( 'd/m/Y', $item['date_start'] );
		$item['date_end'] = date( 'd/m/Y', $item['date_end'] );
		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['coupon_id'] );

		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=edit&token=" . $item['token'] . "&coupon_id=" . $item['coupon_id'];
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
