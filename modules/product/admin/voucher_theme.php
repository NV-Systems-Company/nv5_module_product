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

if( ACTION_METHOD == 'delete' )
{
	$json = array();
	$voucher_theme_id = $nv_Request->get_int( 'voucher_theme_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $voucher_theme_id ) )
	{
		$del_array = array( $voucher_theme_id );
	}

	if( ! empty( $del_array ) )
	{
		$a = 0;
		$_del_array = array();
		$no_del_array = array();
		foreach( $del_array as $voucher_theme_id )
		{

			$voucher_total = $db->query( 'SELECT COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_voucher WHERE voucher_theme_id = ' . ( int )$voucher_theme_id )->fetchColumn();

			if( $voucher_total )
			{
				$json['error'] = sprintf( $lang_ext['error_voucher'], $voucher_total );
				$no_del_array[] = $voucher_theme_id;
			}
			else
			{
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_voucher_theme WHERE voucher_theme_id = ' . ( int )$voucher_theme_id );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_voucher_theme_description WHERE voucher_theme_id = ' . ( int )$voucher_theme_id );

				$json['id'][$a] = $voucher_theme_id;

				$_del_array[] = $voucher_theme_id;

				++$a;
			}

		}

		if( sizeof( $_del_array ) )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_voucher_theme', implode( ', ', $_del_array ), $admin_info['userid'] );
			
			$nv_Cache->delMod( $module_name );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$json['success'] = $lang_ext['text_delete_success'];
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
elseif( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$data = array( 'voucher_theme_id' => 0, 'image' => '' );

	foreach( $getLangModId as $language_id => $value )
	{
		$data['voucher_theme_description'][$language_id] = array( 'name' => '', 'description' => '' );
	}

	$error = array();

	$data['voucher_theme_id'] = $nv_Request->get_int( 'voucher_theme_id', 'get,post', 0 );
	if( $data['voucher_theme_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_voucher_theme  
		WHERE voucher_theme_id=' . $data['voucher_theme_id'] )->fetch();

		$result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_voucher_theme_description WHERE voucher_theme_id=' . $data['voucher_theme_id'] );

		while( $rows = $result->fetch() )
		{

			$data['voucher_theme_description'][$rows['language_id']] = $rows;
		}
		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['voucher_theme_id'] = $nv_Request->get_int( 'voucher_theme_id', 'post', 0 );

		$image = $nv_Request->get_string( 'image', 'post', '' );
		if( is_file( NV_DOCUMENT_ROOT . $image ) )
		{
			$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
			$data['image'] = substr( $image, $lu );
		}
		else
		{
			$data['image'] = '';
		}

		$data['voucher_theme_description'] = $nv_Request->get_typed_array( 'voucher_theme_description', 'post', array() );

		foreach( $data['voucher_theme_description'] as $language_id => $value )
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
			if( $data['voucher_theme_id'] == 0 )
			{

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_voucher_theme SET image = :image' );
				$stmt->bindParam( ':image', $data['image'], PDO::PARAM_STR );
				$stmt->execute();

				if( $data['voucher_theme_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();

					foreach( $data['voucher_theme_description'] as $language_id => $value )
					{
						$value['name'] = isset( $value['name'] ) ? $value['name'] : '';

						$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_voucher_theme_description SET 
							voucher_theme_id = ' . intval( $data['voucher_theme_id'] ) . ', 
							language_id = ' . intval( $language_id ) . ', 
							name = :name ' );

						$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
						$stmt->execute();
						$stmt->closeCursor();
					}
					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A voucher_theme', 'voucher_theme_id: ' . $data['voucher_theme_id'], $admin_info['userid'] );

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
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_voucher_theme SET image = :image WHERE voucher_theme_id=' . $data['voucher_theme_id'] );
					$stmt->bindParam( ':image', $data['image'], PDO::PARAM_STR );
					if( $stmt->execute() )
					{
						$stmt->closeCursor();

						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A voucher_theme', 'voucher_theme_id: ' . $data['voucher_theme_id'], $admin_info['userid'] );

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_voucher_theme_description WHERE voucher_theme_id = ' . ( int )$data['voucher_theme_id'] );

						foreach( $data['voucher_theme_description'] as $language_id => $value )
						{
							$value['name'] = isset( $value['name'] ) ? $value['name'] : '';
							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_voucher_theme_description SET 
								voucher_theme_id = ' . intval( $data['voucher_theme_id'] ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								name = :name ' );

							$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
							$stmt->execute();
							$stmt->closeCursor();
						}
						$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_update_success'] );

						 
					}
					else
					{
						$error['warning'] = $lang_ext['error_save'];

					}

				}
				catch ( PDOException $e )
				{
					$error['warning'] = $lang_ext['error_save'];
					//var_dump( $e );
				}

			}

		}
		if( empty( $error ) )
		{
			$nv_Cache->delMod( $module_name );
			
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}

	}
	if( ! empty( $data['image'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data['image'] ) )
	{
		$data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data['image'];
	}
	$xtpl = new XTemplate( 'voucher_theme_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/maketing' );
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
	$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name . '/voucher' );
	$xtpl->assign( 'UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_name );

	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );

	foreach( $getLangModId as $lang_id_tab => $lang_tab )
	{
		$lang_tab['image'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang_tab['image'];
		$xtpl->assign( 'LANG_TITLE', $lang_tab );
		$xtpl->assign( 'LANG_KEY', $lang_id_tab );
		$xtpl->parse( 'main.looplangtab' );
	}

	foreach( $data['voucher_theme_description'] as $language_id => $value )
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
		$xtpl->parse( 'main.looplang1' );

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

/*show list voucher theme*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_voucher_theme cs 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_voucher_theme_description cn 
ON cs.voucher_theme_id = cn.voucher_theme_id
WHERE cn.language_id = ' . $ProductGeneral->current_language_id;

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'cn.name' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY cn.name";
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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=voucher_theme&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( 'cs.*, cn.name ' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$array = array();
while( $rows = $result->fetch() )
{
	$array[] = $rows;
}

$xtpl = new XTemplate( 'voucher_theme.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/maketing' );
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

if(  $nv_Request->get_string( $module_data . '_success', 'session' ) ) 
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}
$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=cn.name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=voucher_theme&action=add" );

if( ! empty( $array ) )
{
	foreach( $array as $item )
	{

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['voucher_theme_id'] );

		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=voucher_theme&action=edit&token=" . $item['token'] . "&voucher_theme_id=" . $item['voucher_theme_id'];

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
