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

$page_title = $lang_ext['heading_title'];

if( ACTION_METHOD == 'delete' )
{
	$json = array();
	$order_status_id = $nv_Request->get_int( 'order_status_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $order_status_id ) )
	{
		$del_array = array( $order_status_id );
	}

	if( ! empty( $del_array ) )
	{

		$sql = 'SELECT order_status_id, name FROM ' . TABLE_PRODUCT_NAME . '_order_status  
		WHERE language_id=' . $ProductGeneral->current_language_id . ' AND order_status_id IN (' . implode( ',', $del_array ) . ')';
		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$a = 0;
		while( list( $order_status_id, $title ) = $result->fetch( 3 ) )
		{
			if( $ProductGeneral->config['config_order_status_id'] == $order_status_id )
			{
				$json['error'] = $lang_ext['error_default'];
				break;
			}
			if( empty( $json['error'] ) )
			{
				$order_history_total = $db->query( 'SELECT COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_order_history WHERE order_status_id = ' . intval( $order_status_id ) )->fetchColumn();
				if( $order_history_total == 0 )
				{
					if( $db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_status WHERE order_status_id = ' . intval( $order_status_id ) ) )
					{

						$json['id'][$a] = $order_status_id;
						$del_array[] = $order_status_id;
						++$a;
					}
					else
					{
						$no_del_array[] = $order_status_id;
					}
				}
				else
				{
					$json['error'] = sprintf( $lang_ext['error_order'], $order_history_total );
					break;
				}
			}
		}

		$count = sizeof( $del_array );
		if( $count )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_order_status', implode( ', ', $del_array ), $admin_info['userid'] );

			$nv_Request->unset_request( $module_data . '_success', 'session' );

			$json['success'] = $lang_ext['text_delete_success'];

			$nv_Cache->delMod( $module_name );
		}
		if( ! empty( $no_del_array ) )
		{

			$json['error'] = $lang_ext['error_no_delete'] . ': ' . implode( ', ', $no_del_array );
		}

	}
	else
	{
		$json['error'] = $lang_ext['error_no_delete'];
	}
	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$data = array();
	foreach( $getLangModId as $language_id => $value )
	{
		$data['order_status'][$language_id] = array( 'name' => '' );
	}
	$error = array();
	$error_key = array();

	$order_status_id = $nv_Request->get_int( 'order_status_id', 'get,post', 0 );
	if( $order_status_id > 0 )
	{
		$data = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_order_status WHERE order_status_id=' . $order_status_id )->fetchAll();

		foreach( $data as $value )
		{
			$data['order_status'][$value['language_id']] = array( 'name' => $value['name'] );
		}
		$data['order_status_id'] = $order_status_id;

		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['order_status_id'] = $nv_Request->get_int( 'order_status_id', 'post', 0 );

		$data['order_status'] = $nv_Request->get_typed_array( 'order_status', 'post', array() );

		foreach( $data['order_status'] as $language_id => $value )
		{
			if( ( nv_strlen( $value['name'] ) < 1 ) || ( nv_strlen( $value['name'] ) > 255 ) )
			{
				$error['name'][$language_id] = $lang_ext['error_name'];
			}
		}
		if( ! empty( $error ) && ! isset( $error['warning'] ) )
		{
			$error['warning'] = $lang_ext['error_warning'];
		}

		if( empty( $error ) )
		{
			if( $data['order_status_id'] == 0 )
			{
				$order_status_id = 0;
				foreach( $data['order_status'] as $language_id => $value )
				{
					$value['name'] = isset( $value['name'] ) ? $value['name'] : '';
					if( $order_status_id > 0 )
					{
						$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_status SET 
							order_status_id = ' . intval( $order_status_id ) . ', 
							language_id = ' . intval( $language_id ) . ', 
							name = :name ' );

						$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
						$stmt->execute();
						$stmt->closeCursor();

					}
					else
					{
						$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_status SET 
							language_id = ' . intval( $language_id ) . ', 
							name = :name ' );

						$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
						$stmt->execute();
						$order_status_id = $db->lastInsertId();
						$stmt->closeCursor();

					}

				}
				nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A order_status', 'order_status_id: ' . $order_status_id, $admin_info['userid'] );
				
				$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );

				
			}
			else
			{
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_order_status WHERE order_status_id = ' . ( int )$data['order_status_id'] );

				foreach( $data['order_status'] as $language_id => $value )
				{
					$value['name'] = isset( $value['name'] ) ? $value['name'] : '';

					$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_status SET 
								order_status_id = ' . intval( $data['order_status_id'] ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								name = :name' );
					$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
					$stmt->execute();
					$stmt->closeCursor();
					unset( $stmt );
				}

				nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A order_status', 'order_status_id: ' . $data['order_status_id'], $admin_info['userid'] );
				
				$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_update_success'] );
				
			}

		}
		if( empty( $error ) )
		{
			$nv_Cache->delMod( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}

	}

	$xtpl = new XTemplate( 'order_status_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
	$xtpl->assign( 'GLANG', $lang_global );
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
	$xtpl->assign( 'DATA', $data );
	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	foreach( $getLangModId as $lang_id_tab => $lang_tab )
	{
		$lang_tab['image'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang_tab['image'];
		$xtpl->assign( 'LANG_TITLE', $lang_tab );
		$xtpl->assign( 'LANG_KEY', $lang_id_tab );
		$xtpl->parse( 'main.looplangtab' );
	}

	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'WARNING', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}

	foreach( $data['order_status'] as $language_id => $value )
	{
		$xtpl->assign( 'VALUE', $value );
		$xtpl->assign( 'LANG_ID', $language_id );
		$xtpl->assign( 'LANG_IMAGE', NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $getLangModId[$language_id]['image'] );
		$xtpl->assign( 'LANG_TITLE', $getLangModId[$language_id]['name'] );

		if( isset( $error['name'] ) )
		{
			if( isset( $error['name'][$language_id] ) )
			{
				$xtpl->assign( 'error_name', $error['name'][$language_id] );
				$xtpl->parse( 'main.looplang.error_name' );
			}
		}

		$xtpl->parse( 'main.looplang' );

	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';

	exit();
}

/*show list order_status*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_order_status WHERE language_id = ' . intval( $ProductGeneral->current_language_id );

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'name' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= ' ORDER BY ' . $sort;
}
else
{
	$sql .= ' ORDER BY order_status_id';
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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=order_status&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( '*' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$xtpl = new XTemplate( 'order_status.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=add' );
 
$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'NAME_ORDER', ( $sort == 'name' ) ? 'class="' . $order2 . '"' : '' );

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

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['order_status_id'] );

		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order_status&action=edit&token=' . $item['token'] . '&order_status_id=' . $item['order_status_id'];

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
