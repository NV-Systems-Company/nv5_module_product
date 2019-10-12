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

if( ACTION_METHOD == 'sendmail' )
{
	$json = array();
	$voucher_list = $nv_Request->get_string( 'list', 'post', '' );
	$voucher_list = array_unique( array_filter( array_map( 'intval', explode( ',', $voucher_list ) ) ) );
	if( ! empty( $voucher_list ) )
	{
		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_voucher where voucher_id IN ( ' . implode( ',', $voucher_list ) . ' ) ';
		$result = $db->query( $sql );

		$dataContent = array();
		while( $item = $result->fetch() )
		{
			$dataContent[] = $item;
			$voucher_theme[] = $item['voucher_theme_id'];
		}
		$result->closeCursor();
		
		$voucher_theme = array_unique( array_filter( $voucher_theme ) );
		$theme_voucher = array();
		if( ! empty( $voucher_theme ) )
		{
			$sql = 'SELECT a.voucher_theme_id, a.image, b.name FROM ' . TABLE_PRODUCT_NAME . '_voucher_theme a 
			LEFT JOIN ' . TABLE_PRODUCT_NAME . '_voucher_theme_description b ON a.voucher_theme_id = b.voucher_theme_id 
			WHERE b.language_id =' . $ProductGeneral->current_language_id . ' AND a.voucher_theme_id IN ( ' . implode( ',', $voucher_theme ) . ' )
			ORDER BY a.voucher_theme_id DESC';
			$result = $db->query( $sql );

			while( $row = $result->fetch() )
			{
				$theme_voucher[$row['voucher_theme_id']] = $row;
			}
			$result->closeCursor();
		}
		$check = 0;
		if( ! empty( $dataContent ) )
		{

			foreach( $dataContent as $item )
			{

				$subject = $lang_ext['text_subject'] . ' ' . $item['from_name'];

				$xtpl = new XTemplate( 'voucher_email.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/maketing' );
				$xtpl->assign( 'LANG', $lang_module );
				$xtpl->assign( 'LANGE', $lang_ext );

				$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
				$xtpl->assign( 'NV_MY_DOMAIN', NV_MY_DOMAIN );
				$xtpl->assign( 'SITE_NAME', $global_config['site_name'] );

				$item['image'] = isset( $theme_voucher[$item['voucher_theme_id']] ) ? $theme_voucher[$item['voucher_theme_id']]['image'] : '';
				$item['theme'] = isset( $theme_voucher[$item['voucher_theme_id']] ) ? $theme_voucher[$item['voucher_theme_id']]['name'] : '';
				$item['text_redeem'] = sprintf( $lang_ext['text_redeem'], $item['code'] );

				$item['amount'] = ( float )$item['amount'];

				$item['text_received_gif'] = sprintf( $lang_ext['text_received_gif'], $ProductCurrency->format( $item['amount'] ) );

				$xtpl->assign( 'DATA', $item );

				if( ! empty( $item['image'] ) )
				{
					$item['image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['image'];
					$xtpl->assign( 'IMGAGE', $item['image'] );
					$xtpl->parse( 'main.image' );
				}

				$xtpl->parse( 'main' );
				$message = $xtpl->text( 'main' );
 
				if( nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $item['to_email'], $subject, $message ) )
				{
					++$check;
				}
			}
		}
		if( $check > 0 )
		{
			$nv_Request->unset_request( $module_data . '_success', 'session' );

			$json['success'] = $lang_ext['success_sendmail'];
		}
	}
	else
	{
		$json['error'] = $lang_ext['error_not_exist_voucher'];

	}
	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'delete' )
{
	$json = array();
	$voucher_id = $nv_Request->get_int( 'voucher_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $voucher_id ) )
	{
		$del_array = array( $voucher_id );
	}

	if( ! empty( $del_array ) )
	{
		$a = 0;
		$_del_array = array();
		$no_del_array = array();
		foreach( $del_array as $voucher_id )
		{
			$order_voucher_info = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_voucher WHERE voucher_id = ' . ( int )$voucher_id )->fetch();

			if( ! empty( $order_voucher_info ) )
			{
				$order = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order_info&order_id=' . $order_voucher_info['order_id'];
				$error['error'] = sprintf( $lang_ext['error_order'], $order );

				break;

			}
			else
			{
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_voucher WHERE voucher_id = ' . ( int )$voucher_id );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_voucher_history WHERE voucher_id=' . ( int )$voucher_id );
				$json['id'][$a] = $voucher_id;

				$_del_array[] = $voucher_id;

				++$a;
			}

		}

		if( sizeof( $_del_array ) )
		{

			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_voucher', implode( ', ', $_del_array ), $admin_info['userid'] );

			$json['success'] = $lang_ext['text_success'];
		}
		if( ! empty( $no_del_array ) )
		{

			$json['error'] = $lang_ext['error_no_delete'];
		}

	}
	else
	{
		$json['error'] = $lang_ext['error_no_delete'];
	}
	nv_jsonOutput( $json );

}

if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$data = array(
		'voucher_id' => '',
		'code' => '',
		'from_name' => '',
		'to_name' => '',
		'from_email' => '',
		'to_email' => '',
		'voucher_theme_id' => 0,
		'message' => '',
		'amount' => '',
		'status' => 1,
		);
	$error = array();

	$data['voucher_id'] = $nv_Request->get_int( 'voucher_id', 'get,post', 0 );

	if( $data['voucher_id'] > 0 )
	{
		$data = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_voucher where voucher_id=' . $data['voucher_id'] )->fetch();
		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['code'] = $nv_Request->get_title( 'code', 'post', '' );
		$data['from_name'] = $nv_Request->get_title( 'from_name', 'post', '' );
		$data['to_name'] = $nv_Request->get_title( 'to_name', 'post', '' );
		$data['from_email'] = $nv_Request->get_title( 'from_email', 'post', '' );
		$data['to_email'] = $nv_Request->get_title( 'to_email', 'post', '' );
		$data['voucher_theme_id'] = $nv_Request->get_int( 'voucher_theme_id', 'post', 0 );
		$data['message'] = $nv_Request->get_textarea( 'message', 'post', '', 'br', 1 );
		$data['amount'] = $nv_Request->get_float( 'amount', 'post', 0 );
		$data['status'] = $nv_Request->get_float( 'status', 'post', 0 );

		if( nv_strlen( $data['code'] ) < 3 || nv_strlen( $data['code'] ) > 10 )
		{
			$error['code'] = $lang_ext['error_code'];
		}
		if( nv_strlen( $data['from_name'] ) < 2 || nv_strlen( $data['from_name'] ) > 64 )
		{
			$error['from_name'] = $lang_ext['error_from_name'];
		}
		if( nv_strlen( $data['to_name'] ) < 2 || nv_strlen( $data['to_name'] ) > 64 )
		{
			$error['to_name'] = $lang_ext['error_to_name'];
		}
		if( ( $error_xemail = nv_check_valid_email( $data['from_email'] ) ) != '' )
		{
			$error['from_email'] = $error_xemail;
		}
		if( ( $error_xemail = nv_check_valid_email( $data['to_email'] ) ) != '' )
		{
			$error['to_email'] = $error_xemail;
		}
		if( nv_strlen( $data['message'] ) < 3 || nv_strlen( $data['message'] ) > 1000 )
		{
			$error['message'] = $lang_ext['error_message'];
		}
		if( ! empty( $error ) && ! isset( $error['warning'] ) )
		{
			$error['warning'] = $lang_ext['error_warning'];
		}
		if( empty( $error ) )
		{
			if( $data['voucher_id'] == 0 )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_voucher SET 
					order_id = 0, 
					code = :code, 
					from_name = :from_name, 
					from_email = :from_email, 
					to_name = :to_name, 
					to_email = :to_email, 
					voucher_theme_id = ' . intval( $data['voucher_theme_id'] ) . ', 
					message = :message, 
					amount = ' . ( float )$data['amount'] . ', 
					status = ' . intval( $data['status'] ) . ', 
					date_added =' . intval( NV_CURRENTTIME ) );

				$stmt->bindParam( ':code', $data['code'], PDO::PARAM_STR );
				$stmt->bindParam( ':from_name', $data['from_name'], PDO::PARAM_STR );
				$stmt->bindParam( ':from_email', $data['from_email'], PDO::PARAM_STR );
				$stmt->bindParam( ':to_name', $data['to_name'], PDO::PARAM_STR );
				$stmt->bindParam( ':to_email', $data['to_email'], PDO::PARAM_STR );
				$stmt->bindParam( ':message', $data['message'], PDO::PARAM_STR, strlen( $data['message'] ) );

				$stmt->execute();

				if( $data['voucher_id'] = $db->lastInsertId() )
				{
					if( isset( $data['coupon_product'] ) )
					{
						foreach( $data['coupon_product'] as $product_id )
						{
							$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_voucher_product SET voucher_id = ' . ( int )$data['voucher_id'] . ', product_id = ' . ( int )$product_id );
						}
					}

					if( isset( $data['coupon_category'] ) )
					{
						foreach( $data['coupon_category'] as $category_id )
						{
							$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_voucher_category SET voucher_id = ' . ( int )$data['voucher_id'] . ', category_id = ' . ( int )$category_id );
						}
					}

					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Voucher', 'voucher_id: ' . $data['voucher_id'], $admin_info['userid'] );

				}
				else
				{
					$error['warning'] = $lang_ext['error_save'];

				}

				$stmt->closeCursor();
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_voucher SET 
					code = :code, 
					from_name = :from_name, 
					from_email = :from_email, 
					to_name = :to_name, 
					to_email = :to_email, 
					voucher_theme_id = ' . intval( $data['voucher_theme_id'] ) . ', 
					message = :message, 
					amount = ' . ( float )$data['amount'] . ', 
					status = ' . intval( $data['status'] ) . '
					WHERE voucher_id=' . $data['voucher_id'] );

				$stmt->bindParam( ':code', $data['code'], PDO::PARAM_STR );
				$stmt->bindParam( ':from_name', $data['from_name'], PDO::PARAM_STR );
				$stmt->bindParam( ':from_email', $data['from_email'], PDO::PARAM_STR );
				$stmt->bindParam( ':to_name', $data['to_name'], PDO::PARAM_STR );
				$stmt->bindParam( ':to_email', $data['to_email'], PDO::PARAM_STR );
				$stmt->bindParam( ':message', $data['message'], PDO::PARAM_STR, strlen( $data['message'] ) );
				if( $stmt->execute() )
				{

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Voucher', 'voucher_id: ' . $data['voucher_id'], $admin_info['userid'] );
					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_update_success'] );
				}
				else
				{
					$error['warning'] = $lang_ext['error_save'];

				}

				$stmt->closeCursor();

			}

		}
		if( empty( $error ) )
		{
			$nv_Cache->delMod( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=voucher' );
			die();
		}

	}

	$data['message'] = nv_htmlspecialchars( nv_br2nl( $data['message'] ) );
	$data['amount'] = ! empty( $data['amount'] ) ? $data['amount'] : '';

	$xtpl = new XTemplate( 'voucher_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/maketing' );
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
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	
	$xtpl->assign( 'DATA', $data );

	$sql = 'SELECT a.voucher_theme_id, b.name FROM ' . TABLE_PRODUCT_NAME . '_voucher_theme a 
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_voucher_theme_description b ON a.voucher_theme_id = b.voucher_theme_id 
	WHERE b.language_id =' . $ProductGeneral->current_language_id . '
	ORDER BY a.voucher_theme_id DESC';
	$result = $db->query( $sql );

	while( list( $key, $name ) = $result->fetch( 3 ) )
	{
		$xtpl->assign( 'VOUCHER_THEME', array( 'key'=> $key, 'name'=>  $name, 'selected'=> ( $key == $data['voucher_theme_id'] ) ? 'selected="selected"' : '') );		
		$xtpl->parse( 'main.voucher_theme' );
	}

	foreach( $productArrayStatus as $key => $name )
	{
		$xtpl->assign( 'STATUS', array( 'key'=> $key, 'name'=>  $name, 'selected'=> ( $key == $data['status'] ) ? 'selected="selected"' : '') );
		$xtpl->parse( 'main.status' );
	}

	if( isset( $error['code'] ) )
	{
		$xtpl->assign( 'error_code', $error['code'] );
		$xtpl->parse( 'main.error_code' );
	}

	if( isset( $error['from_name'] ) )
	{
		$xtpl->assign( 'error_from_name', $error['from_name'] );
		$xtpl->parse( 'main.error_from_name' );
	}
	if( isset( $error['to_name'] ) )
	{
		$xtpl->assign( 'error_to_name', $error['to_name'] );
		$xtpl->parse( 'main.error_to_name' );
	}
	if( isset( $error['from_email'] ) )
	{
		$xtpl->assign( 'error_from_email', $error['from_email'] );
		$xtpl->parse( 'main.error_from_email' );
	}
	if( isset( $error['to_email'] ) )
	{
		$xtpl->assign( 'error_to_email', $error['to_email'] );
		$xtpl->parse( 'main.error_to_email' );
	}

	if( isset( $error['message'] ) )
	{
		$xtpl->assign( 'error_message', $error['message'] );
		$xtpl->parse( 'main.error_message' );
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

/*show list voucher*/

$getVoucherTheme = getVoucherTheme();
 
$per_page = 100;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_voucher';

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'asc' ? 'asc' : 'desc';

$sort_data = array(
	'code',
	'from_name',
	'to_name',
	'amount',
	'theme',
	'status',
	'date_added' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{
	$sql .= ' ORDER BY ' . $sort;
}
else
{
	$sql .= ' ORDER BY date_added';
}

if( isset( $order ) && ( $order == 'desc' ) )
{
	$sql .= ' DESC';
}
else
{
	$sql .= ' ASC';
}

$num_items = $db->query( 'SELECT COUNT(*) FROM ' . $sql )->fetchColumn();

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( '*' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$array = array();
while( $rows = $result->fetch() )
{
	$array[] = $rows;
}

$xtpl = new XTemplate( 'voucher.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/maketing' );
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
if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}
$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_CODE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=code&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_FROMNAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=from_name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_TONAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=to_name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_AMOUNT', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=amount&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_THEME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=theme&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'STATUS', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=status&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'DATE_ADD', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=date_added&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=add' );

if( ! empty( $array ) )
{
	foreach( $array as $item )
	{
		$item['status_checked'] = ( $item['status'] ) ? 'checked="checked"' : '';

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['voucher_id'] );
		$item['date_added'] = date( 'd/m/y', $item['date_added'] );
		$item['theme'] = $getVoucherTheme[$item['voucher_theme_id']]['name'];
		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=edit&voucher_id=' . $item['voucher_id'] . '&token=' . $item['token'];
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
