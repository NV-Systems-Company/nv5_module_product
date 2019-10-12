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
	
	nv_jsonOutput( array('error'=> 'chức năng đang bị khóa') );
	
	$filter_group_id = $nv_Request->get_int( 'filter_group_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $filter_group_id ) )
	{
		$del_array = array( $filter_group_id );
	}
	
	if( ! empty( $del_array ) )
	{
		$a = 0;
		$_del_array = array();
		$no_del_array = array();
		foreach( $del_array as $filter_group_id )
		{
 
			$product_total = $db->query( 'SELECT COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_product WHERE filter_group_id = ' . ( int )$filter_group_id )->fetchColumn();

			if( $product_total )
			{
				$error['warning'] = sprintf( $lang_ext['error_product'], $product_total );
			}

			if( isset( $error['warning'] ) )
			{
				$no_del_array[] = $filter_group_id;
			}
			else
			{
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_filter_group WHERE filter_group_id = ' . ( int )$filter_group_id );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_filter_group_description WHERE filter_group_id = ' . ( int )$filter_group_id );

				$json['id'][$a] = $filter_group_id;

				$_del_array[] = $filter_group_id;

				++$a;
			}
		}
 
		if( sizeof( $_del_array ) )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_filter_group', implode( ', ', $_del_array ), $admin_info['userid'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$json['success'] = $lang_ext['text_success'] . ' ' . implode( ', ', $_del_array );
			
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

	$data = array( 'filter_group_id' => 0, 'sort_order' => 0, 'filter' => array() );

	foreach( $getLangModId as $language_id => $value )
	{
		$data['filter_group_description'][$language_id] = array( 'name' => '' );
	}

	$data['filter_group_id'] = $nv_Request->get_int( 'filter_group_id', 'get,post', 0 );

	if( $data['filter_group_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_filter_group  
		WHERE filter_group_id=' . $data['filter_group_id'] )->fetch();

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_filter_group_description WHERE filter_group_id=' . $data['filter_group_id'];

		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{

			$data['filter_group_description'][$rows['language_id']] = $rows;
		}
		
		$data['filter'] = getFilterDescriptions( $data['filter_group_id'] ); 
 
		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['filter_group_id'] = $nv_Request->get_int( 'filter_group_id', 'post', 0 );
		$data['sort_order'] = $nv_Request->get_float( 'sort_order', 'post', 0 );

		$data['filter'] = $nv_Request->get_typed_array( 'filter', 'post', array() );
		$data['filter_group_description'] = $nv_Request->get_typed_array( 'filter_group_description', 'post', array() );

		foreach( $data['filter_group_description'] as $language_id => $value )
		{ 
			if( ( nv_strlen( $value['name'] ) < 1 ) || ( nv_strlen( $value['name'] ) > 64 ) )
			{
				$error['name'][$language_id] = $lang_ext['error_name'];
			}

		}

		if( empty( $error ) )
		{
			if( $data['filter_group_id'] == 0 )
			{

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_filter_group SET 
				sort_order = ' . ( int )$data['sort_order'] );
				$stmt->execute();

				if( $data['filter_group_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();

					
					foreach( $data['filter_group_description'] as $language_id => $value )
					{
						$value['name'] = isset( $value['name'] ) ? ( string )$value['name'] : '';
						try
						{
							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_filter_group_description SET 
								filter_group_id = ' . intval( $data['filter_group_id'] ) . ', 
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
					
					if( !empty( $data['filter'] ) )
					{
						foreach ( $data['filter'] as $filter ) 
						{
							$db->query('INSERT INTO ' . TABLE_PRODUCT_NAME . '_filter SET filter_group_id = ' . (int)$data['filter_group_id'] . ', sort_order = ' . (int)$filter['sort_order'] );

							$filter_id = $db->lastInsertId();

							foreach ( $filter['filter_description'] as $language_id => $filter_description ) 
							{
								$db->query('INSERT INTO ' . TABLE_PRODUCT_NAME . '_filter_description SET filter_id = ' . (int)$filter_id . ', language_id = ' . (int)$language_id . ', filter_group_id = ' . (int)$data['filter_group_id'] . ', name = ' . $db->quote( $filter_description['name'] ) );
							}
						}
					}
					
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add Attribute group description', 'filter_group_id: ' . $data['filter_group_id'], $admin_info['userid'] );

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
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_filter_group SET 
					sort_order = ' . ( int )$data['sort_order'] . ' 
					WHERE filter_group_id = ' . ( int )$data['filter_group_id'] );

					if( $stmt->execute() )
					{
						$stmt->closeCursor();

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_filter_group_description WHERE filter_group_id = ' . ( int )$data['filter_group_id'] );

						foreach( $data['filter_group_description'] as $language_id => $value )
						{
							$value['name'] = isset( $value['name'] ) ? ( string )$value['name'] : '';
							try
							{
								$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_filter_group_description SET 
									filter_group_id = ' . intval( $data['filter_group_id'] ) . ', 
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
						
						$db->query('DELETE FROM ' . TABLE_PRODUCT_NAME . '_filter WHERE filter_group_id = ' . (int)$data['filter_group_id'] );
						$db->query('DELETE FROM ' . TABLE_PRODUCT_NAME . '_filter_description WHERE filter_group_id = ' . (int)$data['filter_group_id']);

						if( !empty( $data['filter'] ) )
						{
							foreach ( $data['filter'] as $filter ) 
							{
								$db->query('INSERT INTO ' . TABLE_PRODUCT_NAME . '_filter SET filter_group_id = ' . (int)$data['filter_group_id'] . ', sort_order = ' . (int)$filter['sort_order'] );

								$filter_id = $db->lastInsertId();

								foreach ( $filter['filter_description'] as $language_id => $filter_description ) 
								{
									$db->query('INSERT INTO ' . TABLE_PRODUCT_NAME . '_filter_description SET filter_id = ' . (int)$filter_id . ', language_id = ' . (int)$language_id . ', filter_group_id = ' . (int)$data['filter_group_id'] . ', name = ' . $db->quote( $filter_description['name'] ) );
								}
							}
						}
						
						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit Attribute group description', 'filter_group_id: ' . $data['filter_group_id'], $admin_info['userid'] );
						
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

	$xtpl = new XTemplate( 'filter_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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
	$filter_row = 0;
	foreach( $data['filter_group_description'] as $language_id => $value )
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
		++$filter_row;
	}
	$xtpl->assign( 'filter_row', $filter_row );
	
	if( !empty( $data['filter'] ) )
	{
		foreach( $data['filter'] as $key => $filter )
		{
			$filter['key'] = $key;
			$xtpl->assign( 'FILTER', $filter );
			foreach( $getLangModId as $language_id => $lang )
			{
				
				$xtpl->assign( 'LANG_ID', $language_id );
				$xtpl->assign( 'LANG_IMAGE', NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang['image'] );
				$xtpl->assign( 'LANG_TITLE', $lang['name'] );
				$xtpl->assign( 'NAME', isset( $filter['filter_description'][$language_id] ) ? $filter['filter_description'][$language_id]['name'] : '' ); 
				$xtpl->assign( 'SORT_ORDER', isset( $filter['filter_description'][$language_id] ) ? $filter['filter_description'][$language_id]['sort_order'] : '' ); 
			
				$xtpl->parse( 'main.filter.languages' );
			}
			$xtpl->parse( 'main.filter' );
		}
	}
	
	
	foreach( $getLangModId as $language_id => $lang )
	{
		$xtpl->assign( 'LANG_ID', $language_id );
		$xtpl->assign( 'LANG_IMAGE', NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang['image'] );
		$xtpl->assign( 'LANG_TITLE', $lang['name'] );
		$xtpl->parse( 'main.languages' );
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

/*show list filter group*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_filter_group fg 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_filter_group_description fgd 
ON fg.filter_group_id = fgd.filter_group_id
WHERE fgd.language_id = ' . $ProductGeneral->current_language_id;

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'fgd.name' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY fgd.name";
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

$db->sqlreset()->select( 'fg.*, fgd.name' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$xtpl = new XTemplate( 'filter.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=fgd.name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_SORT_ORDER', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=fg.sort_order&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

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

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['filter_group_id'] );

		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=edit&token=' . $item['token'] . '&filter_group_id=' . $item['filter_group_id'];

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
