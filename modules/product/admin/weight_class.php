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

if( ACTION_METHOD == 'delete' )
{
	$json = array();
	$weight_class_id = $nv_Request->get_int( 'weight_class_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $weight_class_id ) )
	{
		$del_array = array( $weight_class_id );
	}

	if( ! empty( $del_array ) )
	{
		$a = 0;
		$_del_array = array();
		$no_del_array = array();
		foreach( $del_array as $weight_class_id )
		{
			if( $ProductGeneral->config['config_weight_class_id'] == $weight_class_id )
			{
				$error['warning'] = $lang_ext['error_default'];
			}

			$product_total = $db->query( 'SELECT COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_product WHERE weight_class_id = ' . ( int )$weight_class_id )->fetchColumn();

			if( $product_total )
			{
				$error['warning'] = sprintf( $lang_ext['error_product'], $product_total );
			}

			if( isset( $error['warning'] ) )
			{
				$no_del_array[] = $weight_class_id;
			}
			else
			{
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_weight_class WHERE weight_class_id = ' . ( int )$weight_class_id );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_weight_class_description WHERE weight_class_id = ' . ( int )$weight_class_id );

				$json['id'][$a] = $weight_class_id;

				$_del_array[] = $weight_class_id;

				++$a;
			}
		}

		if( sizeof( $_del_array ) )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_weight_class', implode( ', ', $_del_array ), $admin_info['userid'] );

			$nv_Request->unset_request( $module_data . '_success', 'session' );

			$json['success'] = $lang_ext['text_success'];
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

if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$error = array();

	$data = array( 'weight_class_id' => 0, 'value' => 0 );

	foreach( $getLangModId as $language_id => $value )
	{
		$data['weight_class_description'][$language_id] = array( 'title' => '', 'unit' => '' );
	}

	$data['weight_class_id'] = $nv_Request->get_int( 'weight_class_id', 'get,post', 0 );

	if( $data['weight_class_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_weight_class  
		WHERE weight_class_id=' . $data['weight_class_id'] )->fetch();

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_weight_class_description WHERE weight_class_id=' . $data['weight_class_id'];

		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{

			$data['weight_class_description'][$rows['language_id']] = $rows;
		}
		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['weight_class_id'] = $nv_Request->get_int( 'weight_class_id', 'post', 0 );
		$data['value'] = $nv_Request->get_float( 'value', 'post', 0 );

		$data['weight_class_description'] = $nv_Request->get_typed_array( 'weight_class_description', 'post', array() );

		foreach( $data['weight_class_description'] as $language_id => $value )
		{
			if( ( nv_strlen( $value['title'] ) < 1 ) || ( nv_strlen( $value['title'] ) > 255 ) )
			{
				$error['title'][$language_id] = $lang_ext['error_title'];
			}
			if( ( nv_strlen( $value['unit'] ) < 1 ) || ( nv_strlen( $value['unit'] ) > 255 ) )
			{
				$error['unit'][$language_id] = $lang_ext['error_unit'];
			}
		}

		if( empty( $error ) )
		{
			if( $data['weight_class_id'] == 0 )
			{

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_weight_class SET value = ' . ( float )$data['value'] );
				$stmt->execute();

				if( $data['weight_class_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();
					unset( $stmt );

					foreach( $data['weight_class_description'] as $language_id => $value )
					{
						$value['title'] = isset( $value['title'] ) ? ( string )$value['title'] : '';
						$value['unit'] = isset( $value['unit'] ) ? ( string )$value['unit'] : '';
						try
						{
							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_weight_class_description SET 
								weight_class_id = ' . intval( $data['weight_class_id'] ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								title = :title,
								unit = :unit' );

							$stmt->bindParam( ':title', $value['title'], PDO::PARAM_STR );
							$stmt->bindParam( ':unit', $value['unit'], PDO::PARAM_STR );
							$stmt->execute();
							$stmt->closeCursor();
							unset( $stmt );
						}
						catch ( PDOException $e )
						{
							$error['warning'] = $lang_ext['error_save'];
						}
					}
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A weight_class', 'weight_class_id: ' . $data['weight_class_id'], $admin_info['userid'] );

					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );
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
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_weight_class SET 
					value = ' . ( float )$data['value'] . ' 
					WHERE weight_class_id = ' . ( int )$data['weight_class_id'] );

					if( $stmt->execute() )
					{
						$stmt->closeCursor();
						unset( $stmt );

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_weight_class_description WHERE weight_class_id = ' . intval( $data['weight_class_id'] ) );

						foreach( $data['weight_class_description'] as $language_id => $value )
						{
							$value['title'] = isset( $value['title'] ) ? ( string )$value['title'] : '';
							$value['unit'] = isset( $value['unit'] ) ? ( string )$value['unit'] : '';
							try
							{
								$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_weight_class_description SET 
									weight_class_id = ' . intval( $data['weight_class_id'] ) . ', 
									language_id = ' . intval( $language_id ) . ', 
									title = :title,
									unit = :unit' );

								$stmt->bindParam( ':title', $value['title'], PDO::PARAM_STR );
								$stmt->bindParam( ':unit', $value['unit'], PDO::PARAM_STR );
								$stmt->execute();
								$stmt->closeCursor();
							}
							catch ( PDOException $e )
							{
								$error['warning'] = $lang_ext['error_save'];
							}
						}

						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A weight_class', 'weight_class_id: ' . $data['weight_class_id'], $admin_info['userid'] );

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
					//var_dump($e);
				}

			}

		}
		if( empty( $error ) )
		{
			$nv_Cache->delMod( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=weight_class' );
			die();
		}

	}

	$xtpl = new XTemplate( 'weight_class_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
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
	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );

	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'DATA', $data );

	foreach( $getLangModId as $lang_id_tab => $lang_tab )
	{
		$lang_tab['image'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang_tab['image'];
		$xtpl->assign( 'LANG_TITLE', $lang_tab );
		$xtpl->assign( 'LANG_KEY', $lang_id_tab );
		$xtpl->parse( 'main.looplangtab' );
	}

	foreach( $data['weight_class_description'] as $language_id => $value )
	{
		$xtpl->assign( 'VALUE', $value );
		$xtpl->assign( 'LANG_ID', $language_id );
		$xtpl->assign( 'LANG_IMAGE', NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $getLangModId[$language_id]['image'] );
		$xtpl->assign( 'LANG_TITLE', $getLangModId[$language_id]['name'] );

		if( isset( $error['title'] ) )
		{
			if( isset( $error['title'][$language_id] ) )
			{
				$xtpl->assign( 'error_title', $error['title'][$language_id] );
				$xtpl->parse( 'main.looplang.error_title' );
			}
		}
		if( isset( $error['unit'] ) )
		{
			if( isset( $error['unit'][$language_id] ) )
			{
				$xtpl->assign( 'error_unit', $error['unit'][$language_id] );
				$xtpl->parse( 'main.looplang1.error_unit' );
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

/*show list weight*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_weight_class w 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_weight_class_description wd 
ON w.weight_class_id = wd.weight_class_id
WHERE wd.language_id = ' . $ProductGeneral->current_language_id;

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'wd.name', 'wd.unit' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= ' ORDER BY ' . $sort;
}
else
{
	$sql .= ' ORDER BY wd.weight_class_id';
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

$db->sqlreset()->select( 'w.*, wd.title , wd.unit ' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$xtpl = new XTemplate( 'weight_class.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
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
$xtpl->assign( 'URL_TITLE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=wd.title&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

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

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['weight_class_id'] );

		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=edit&token=' . $item['token'] . '&weight_class_id=' . $item['weight_class_id'];

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
