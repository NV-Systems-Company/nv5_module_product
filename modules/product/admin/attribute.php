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

$getAttributeGroup = getAttributeGroup();

if( ACTION_METHOD == 'delete' )
{
	$json = array();
	$attribute_id = $nv_Request->get_int( 'attribute_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $attribute_id ) )
	{
		$del_array = array( $attribute_id );
	}

	if( ! empty( $del_array ) )
	{
		$a = 0;
		$_del_array = array();
		$no_del_array = array();
		foreach( $del_array as $attribute_id )
		{
			if( $ProductGeneral->config['config_attribute_id'] == $attribute_id )
			{
				$error['warning'] = $lang_ext['error_default'];
			}

			$product_total = $db->query( 'SELECT COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_product_attribute WHERE attribute_id = ' . ( int )$attribute_id )->fetchColumn();

			if( $product_total )
			{
				$error['warning'] = sprintf( $lang_ext['error_product'], $product_total );
			}

			if( isset( $error['warning'] ) )
			{
				$no_del_array[] = $attribute_id;
			}
			else
			{
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_attribute WHERE attribute_id = ' . ( int )$attribute_id );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_attribute_description WHERE attribute_id = ' . ( int )$attribute_id );

				$json['id'][$a] = $attribute_id;

				$_del_array[] = $attribute_id;

				++$a;
			}
		}

		$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );

		if( sizeof( $_del_array ) )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_attribute', implode( ', ', $_del_array ), $admin_info['userid'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$nv_Cache->delMod( $module_name );
			
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
elseif( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$error = array();

	$data = array( 'attribute_id' => 0, 'attribute_group_id' => 0, 'sort_order' => 0 );

	foreach( $getLangModId as $language_id => $value )
	{
		$data['attribute_description'][$language_id] = array( 'name' => '' );
	}

	$data['attribute_id'] = $nv_Request->get_int( 'attribute_id', 'get,post', 0 );
	

	if( $data['attribute_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_attribute  
		WHERE attribute_id=' . $data['attribute_id'] )->fetch();

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_attribute_description WHERE attribute_id=' . $data['attribute_id'];

		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{

			$data['attribute_description'][$rows['language_id']] = $rows;
		}
		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['attribute_id'] = $nv_Request->get_int( 'attribute_id', 'post', 0 );
		$data['attribute_group_id'] = $nv_Request->get_int( 'attribute_group_id', 'post', 0 );
		$data['sort_order'] = $nv_Request->get_float( 'sort_order', 'post', 0 );

		$data['attribute_description'] = $nv_Request->get_typed_array( 'attribute_description', 'post', array() );

		foreach( $data['attribute_description'] as $language_id => $value )
		{ 
			if( empty( $value['name'] ) )
			{ 
				$error['name'][$language_id] = $lang_ext['error_name'];
			}

		}

		if( empty( $error ) )
		{
			if( $data['attribute_id'] == 0 )
			{

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_attribute SET 
				attribute_group_id = ' . ( int )$data['attribute_group_id'] .',
				sort_order = ' . ( int )$data['sort_order'] );
				$stmt->execute();

				if( $data['attribute_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();

					
					foreach( $data['attribute_description'] as $language_id => $value )
					{
						$value['name'] = isset( $value['name'] ) ? ( string )$value['name'] : '';
						try
						{
							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_attribute_description SET 
								attribute_id = ' . intval( $data['attribute_id'] ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								name = :name' );

							$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
							$stmt->execute();
							$stmt->closeCursor();
						}
						catch ( PDOException $e )
						{
							$error['warning'] = $lang_ext['error_save'];
						}
					}
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add Attribute group description', 'attribute_id: ' . $data['attribute_id'], $admin_info['userid'] );
					
					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );


				}
				else
				{
					$error['warning'] = $lang_module['error_save'];

				}

			}
			else
			{
				try
				{
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_attribute SET 
					attribute_group_id = ' . ( int )$data['attribute_group_id'] . ', 
					sort_order = ' . (int)$data['sort_order'] . ' 
					WHERE attribute_id = ' . ( int )$data['attribute_id'] );

					if( $stmt->execute() )
					{
						$stmt->closeCursor();

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_attribute_description WHERE attribute_id = ' . ( int )$data['attribute_id'] );

						foreach( $data['attribute_description'] as $language_id => $value )
						{
							$value['name'] = isset( $value['name'] ) ? ( string )$value['name'] : '';
							try
							{
								$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_attribute_description SET 
									attribute_id = ' . intval( $data['attribute_id'] ) . ', 
									language_id = ' . intval( $language_id ) . ', 
									name = :name' );

								$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
								$stmt->execute();
								$stmt->closeCursor();
							}
							catch ( PDOException $e )
							{
								$error['warning'] = $lang_ext['error_save'];
							}
						}
						
						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit Attribute group description', 'attribute_id: ' . $data['attribute_id'], $admin_info['userid'] );

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
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}

	}

	$xtpl = new XTemplate( 'attribute_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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

	foreach( $data['attribute_description'] as $language_id => $value )
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
	foreach( $getAttributeGroup as $attribute_group_id => $value )
	{
		$xtpl->assign( 'ATBG', array( 
			'name'=> $value['name'], 
			'key'=> $attribute_group_id, 
			'selected'=> ( $attribute_group_id == $data['attribute_group_id'] ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.attribute_group' );	
	}
	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'WARNING', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}

/*show list attribute*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_attribute ag 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_attribute_description agd 
ON ag.attribute_id = agd.attribute_id
WHERE agd.language_id = ' . $ProductGeneral->current_language_id;

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'ag.sort_order', 'agd.name' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY agd.name";
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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( 'ag.*, agd.name' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$xtpl = new XTemplate( 'attribute.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=agd.name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_SORT_ORDER', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=ag.sort_order&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=add' );

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

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['attribute_id'] );

		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=edit&token=' . $item['token'] . '&attribute_id=' . $item['attribute_id'];
		$item['group_name'] =  $getAttributeGroup[$item ['attribute_group_id']]['name']; 
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
