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

$lang_ext = getLangAdmin( $op, 'sale' );

$page_title = $lang_ext['heading_title'];

function nv_fix_customer_group()
{
	global $db, $db_config, $module_data;

	$sql = 'SELECT customer_group_id FROM ' . TABLE_PRODUCT_NAME . '_group ORDER BY weight ASC';
	$weight = 0;
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		++$weight;
		$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_group SET weight=' . $weight . ' WHERE customer_group_id=' . $row['customer_group_id'];
		$db->query( $sql );
	}
	$result->closeCursor();
}

if( ACTION_METHOD == 'delete' )
{
	$json = array();
	$customer_group_id = $nv_Request->get_int( 'customer_group_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	$del_array = array();
	if( $listid != '' and md5( $nv_Request->session_id . $global_config['sitekey'] ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $nv_Request->session_id . $global_config['sitekey'] . $customer_group_id ) )
	{
		$del_array = array( $customer_group_id );
	}

	if( ! empty( $del_array ) )
	{

		$sql = 'SELECT g.customer_group_id, gd.name FROM ' . TABLE_PRODUCT_NAME . '_group g 
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_group_description gd ON (g.customer_group_id = gd.customer_group_id)
		WHERE gd.language_id=' . $ProductGeneral->current_language_id . ' AND g.customer_group_id IN (' . implode( ',', $del_array ) . ')';
		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$a = 0;
		while( list( $customer_group_id, $title ) = $result->fetch( 3 ) )
		{
			if( $ProductGeneral->config['config_customer_group_id'] == $customer_group_id )
			{
				$json['error'] = $lang_ext['error_default'];
				break;
			}
			if( empty( $json['error'] ) )
			{
				$customer_total = $db->query( 'SELECT COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_group_users WHERE customer_group_id = ' . ( int )$customer_group_id )->fetchColumn();
				if( $customer_total == 0 )
				{
					if( $db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_group WHERE customer_group_id = ' . ( int )$customer_group_id ) )
					{
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_group_description WHERE customer_group_id = ' . ( int )$customer_group_id );

 
						$json['id'][$a] = $customer_group_id;
						$del_array[] = $customer_group_id;
						++$a;
					}
					else
					{
						$no_del_array[] = $customer_group_id;
					}
				}
				else
				{
					$json['error'] = sprintf( $lang_ext['error_customer'], $customer_total );
				}
			}
		}

		$count = sizeof( $del_array );
		if( $count )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_customer_group', implode( ', ', $del_array ), $admin_info['userid'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$json['success'] = $lang_ext['text_delete_success'];
			
			$ProductGeneral->deleteCache( 'customer_group' );
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
elseif( ACTION_METHOD == 'weight' )
{
	$json = array();

	$customer_group_id = $nv_Request->get_int( 'customer_group_id', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '', 1 );

	if( $token == md5( $nv_Request->session_id . $global_config['sitekey'] . $customer_group_id ) )
	{
		$sql = 'SELECT customer_group_id FROM ' . TABLE_PRODUCT_NAME . '_group WHERE customer_group_id!=' . $customer_group_id . ' ORDER BY weight ASC';
		$result = $db->query( $sql );

		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_group SET weight=' . $weight . ' WHERE customer_group_id=' . intval( $row['customer_group_id'] );
			$db->query( $sql );
		}
		$result->closeCursor();
		
		$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_group SET weight=' . $new_vid . ' WHERE customer_group_id=' . $customer_group_id;
		if( $db->exec( $sql ) )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_change_weight_customer_group', 'customer_group_id:' . $customer_group_id, $admin_info['userid'] );
			
			$ProductGeneral->deleteCache( 'customer_group' );
			//$nv_Cache->delMod($module_name);

			$json['success'] = $lang_ext['text_change_weight_success'];
			$json['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

		}
		else
		{
			$json['error'] = $lang_ext['text_change_weight_error'];

		}
	}
	else
	{
		$json['error'] = $lang_ext['text_security_error'];
	}

	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$data = array( 'customer_group_id' => 0, 'approval' => 0 );
	foreach( $getLangModId as $language_id => $value )
	{
		$data['customer_group_description'][$language_id] = array( 'name' => '', 'description' => '' );
	}
	$error = array();

	$data['customer_group_id'] = $nv_Request->get_int( 'customer_group_id', 'get,post', 0 );
	if( $data['customer_group_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_group  
		WHERE customer_group_id=' . $data['customer_group_id'] )->fetch();

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_group_description WHERE customer_group_id=' . $data['customer_group_id'];

		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{

			$data['customer_group_description'][$rows['language_id']] = $rows;
		}
		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['customer_group_id'] = $nv_Request->get_int( 'customer_group_id', 'post', 0 );
		$data['approval'] = $nv_Request->get_int( 'approval', 'post', 0 );

		$data['customer_group_description'] = $nv_Request->get_typed_array( 'customer_group_description', 'post', array() );

		foreach( $data['customer_group_description'] as $language_id => $value )
		{
			if( ( nv_strlen( $value['name'] ) < 3 ) || ( nv_strlen( $value['name'] ) > 240 ) )
			{
				$error['name'][$language_id] = $lang_ext['error_name'];
			}
		}
 
		if( empty( $error ) )
		{
			if( $data['customer_group_id'] == 0 )
			{

				$stmt = $db->prepare( 'SELECT max(weight) FROM ' . TABLE_PRODUCT_NAME . '_group' );
				$stmt->execute();
				$weight = $stmt->fetchColumn();

				$weight = intval( $weight ) + 1;

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_group SET 
				approval = ' . intval( $data['approval'] ) . ', 
				weight = ' . intval( $weight ) );
				$stmt->execute();

				if( $data['customer_group_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();unset($stmt);
 
					foreach( $data['customer_group_description'] as $language_id => $value )
					{
						$value['name'] = isset( $value['name'] ) ? $value['name'] : '';
						$value['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
						try
						{
							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_group_description SET 
								customer_group_id = ' . intval( $data['customer_group_id'] ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								name = :name,
								description = :description' );

							$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
							$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
							$stmt->execute();
							$stmt->closeCursor();unset($stmt);
						}
						catch ( PDOException $e )
						{
							$error['warning'] = $lang_ext['error_save'];
						}
					}
					
					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );
	
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A customer_group', 'customer_group_id: ' . $data['customer_group_id'], $admin_info['userid'] );


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
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_group SET 
					approval = ' . intval( $data['approval'] ) . ' 
					WHERE customer_group_id = ' . ( int )$data['customer_group_id'] );

					if( $stmt->execute() )
					{
						$stmt->closeCursor();

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_group_description WHERE customer_group_id = ' . ( int )$data['customer_group_id'] );

						foreach( $data['customer_group_description'] as $language_id => $value )
						{
							$value['name'] = isset( $value['name'] ) ? $value['name'] : '';
							$value['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
							try
							{
								$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_group_description SET 
									customer_group_id = ' . intval( $data['customer_group_id'] ) . ', 
									language_id = ' . intval( $language_id ) . ', 
									name = :name,
									description = :description' );

								$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
								$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
								$stmt->execute();
								$stmt->closeCursor();
							}
							catch ( PDOException $e )
							{
								$error['warning'] = $lang_ext['error_save'];
							}
						}
						
						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A customer_group', 'customer_group_id: ' . $data['customer_group_id'], $admin_info['userid'] );
					
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
			$ProductGeneral->deleteCache( 'customer_group' );
			//$nv_Cache->delMod($module_name);
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}

	}

	$xtpl = new XTemplate( 'customer_group_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/sale' );
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
	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );

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

	foreach( $data['customer_group_description'] as $language_id => $value )
	{
		$value['description'] = nv_htmlspecialchars( nv_br2nl( $value['description'] ) );
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
	foreach( $productArrayYesNo as $key => $name )
	{
		$xtpl->assign( 'APPROVAL', array('key'=> $key, 'name'=> $name, 'checked'=> ( $key == $data['approval'] ) ? 'checked="checked"' : '' ) );
		$xtpl->parse( 'main.approval' );
	}
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
 
}

/*show list customer_group*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_group g 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_group_description gd 
ON g.customer_group_id = gd.customer_group_id
WHERE gd.language_id = ' . $ProductGeneral->current_language_id;

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'gd.name' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY g.weight";
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

$db->sqlreset()->select( 'g.*, gd.name ' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$array = array();
while( $rows = $result->fetch() )
{
	$array[] = $rows;
}

$xtpl = new XTemplate( 'customer_group.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/sale' );
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
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'TOKEN', md5( $nv_Request->session_id . $global_config['sitekey'] ) );
$xtpl->assign( 'MAXITEM', $num_items );

if(  $nv_Request->get_string( $module_data . '_success', 'session' ) )  
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );
	$xtpl->parse( 'main.success' );
	$nv_Request->unset_request( $module_data . '_success', 'session' );
}

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_WEIGHT', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=g.weight&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=gd.name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . '=' . $op . '&action=add' );

if( ! empty( $array ) )
{
	foreach( $array as $item )
	{

		$item['token'] = md5( $nv_Request->session_id . $global_config['sitekey'] . $item['customer_group_id'] );
		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=edit&token=' . $item['token'] . '&customer_group_id=' . $item['customer_group_id'];
		$xtpl->assign( 'LOOP', $item );
		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array( 'key' => $i, 'name' => $i, 'selected' => ( $i == $item['weight'] ) ? ' selected="selected"' : '' ) );
			$xtpl->parse( 'main.loop.weight' );
		}
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
