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
 
function nv_fix_units()
{
	global $db, $db_config, $module_data;

	$sql = 'SELECT units_id FROM ' . TABLE_PRODUCT_NAME . '_units ORDER BY weight ASC';
	$weight = 0;
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		++$weight;
		$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_units SET weight=' . $weight . ' WHERE units_id=' . $row['units_id'];
		$db->query( $sql );
	}
	$result->closeCursor();
}

if( ACTION_METHOD == 'weight' )
{
	$json = array();
	$units_id = $nv_Request->get_int( 'units_id', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
 
	list( $units_id ) = $db->query( 'SELECT units_id FROM ' . TABLE_PRODUCT_NAME . '_units WHERE units_id=' . $units_id )->fetch( 3 );
	if( $units_id > 0 )
	{
		if(  $new_vid > 0 )
		{
			$sql = 'SELECT units_id FROM ' . TABLE_PRODUCT_NAME . '_units WHERE units_id !=' . $units_id . ' ORDER BY weight ASC';
			$result = $db->query( $sql );

			$weight = 0;
			while( $row = $result->fetch() )
			{
				++$weight;
				if( $weight == $new_vid ) ++$weight;
				$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_units SET weight=' . $weight . ' WHERE units_id=' . intval( $row['units_id'] );
				$db->query( $sql );
			}

			$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_units SET weight=' . $new_vid . ' WHERE units_id=' . $units_id;
			$db->query( $sql );

			nv_fix_units();
			
			$json['success'] = $lang_ext['text_status_success'];
			$nv_Cache->delMod( $module_name );
		}
		else{
			
			$json['error'] = $lang_ext['text_status_error'];
		}
		
		
	}else{
		
		$json['error'] = $lang_ext['text_status_error'];
	}
	nv_jsonOutput( $json );

}
elseif( ACTION_METHOD == 'delete' )
{
	$json = array();
	$units_id = $nv_Request->get_int( 'units_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $units_id ) )
	{
		$del_array = array( $units_id );
	}

	if( ! empty( $del_array ) )
	{

		$sql = 'SELECT a.units_id, b.name FROM ' . TABLE_PRODUCT_NAME . '_units a
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_units_description b ON( a.units_id = b.units_id)
		WHERE b.language_id = ' . $ProductGeneral->current_language_id . ' AND a.units_id IN (' . implode( ',', $del_array ) . ')';

		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$a = 0;
		while( list( $units_id, $name ) = $result->fetch( 3 ) )
		{

			$exist = $db->query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_product WHERE units_id=' . $units_id )->fetchColumn();
			if( $exist == 0 )
			{
				if( $db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_units WHERE units_id = ' . ( int )$units_id ) )
				{
					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_units_description WHERE units_id = ' . ( int )$units_id );
 
					$json['id'][$a] = $units_id;
					$del_array[] = $units_id;
					++$a;
				}
				else
				{
					$no_del_array[] = $units_id;
				}
			}
			else
			{
				$json['error'] = sprintf( $lang_ext['error_delete'], $exist );
				break;
			}
		}

		$count = sizeof( $del_array );
		if( $count )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_units', implode( ', ', $no_del_array ), $admin_info['userid'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$json['success'] = $lang_ext['success_delele'];
			
			$nv_Cache->delMod( $module_name );
			
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

	$data = array(
		'units_id' => 0,
		'weight' => '',
		);
	foreach( $getLangModId as $language_id => $value )
	{
		$data['units_description'][$language_id] = array(
			'name' => '',
			'description' => '',
		);
	}
	$error = array();
	$error_key = array();

	$data['units_id'] = $nv_Request->get_int( 'units_id', 'get,post', 0 );
	if( $data['units_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_units  
		WHERE units_id=' . $data['units_id'] )->fetch();

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_units_description WHERE units_id=' . $data['units_id'];

		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{
			$data['units_description'][$rows['language_id']] = $rows;
		}
		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['units_id'] = $nv_Request->get_int( 'units_id', 'post', 0 );
		$data['adddefault'] = $nv_Request->get_int( 'adddefault', 'post', 0 );

		$data['units_description'] = $nv_Request->get_typed_array( 'units_description', 'post', array() );

		foreach( $data['units_description'] as $language_id => $value )
		{
			if( ( nv_strlen( $value['name'] ) < 2 ) || ( nv_strlen( $value['name'] ) > 255 ) )
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
			if( $data['units_id'] == 0 )
			{

				$stmt = $db->prepare( 'SELECT max(weight) FROM ' . TABLE_PRODUCT_NAME . '_units' );
				$stmt->execute();
				$weight = $stmt->fetchColumn();

				$weight = intval( $weight ) + 1;

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_units SET 
				weight = ' . intval( $weight ) );
				$stmt->execute();

				if( $data['units_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();

					
					foreach( $data['units_description'] as $language_id => $value )
					{
						try
						{
							$value['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
							$value['name'] = isset( $value['name'] ) ? $value['name'] : '';

							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_units_description SET 
								units_id = ' . intval( $data['units_id'] ) . ', 
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
							//var_dump( $error['warning']);
						}
					}
					
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Units', 'units_id: ' . $data['units_id'], $admin_info['userid'] );

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
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_units SET 
					weight = ' . intval( $data['weight'] ) );

					if( $stmt->execute() )
					{
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_units_description WHERE units_id = ' . ( int )$data['units_id'] );

						foreach( $data['units_description'] as $language_id => $value )
						{
							$value['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
							$value['name'] = isset( $value['name'] ) ? $value['name'] : '';
							try
							{
								$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_units_description SET 
									units_id = ' . intval( $data['units_id'] ) . ', 
									language_id = ' . intval( $language_id ) . ', 
									name = :name,
									description = :description' );

								$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
								$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );

								$stmt->execute();
								$stmt->closeCursor();
							}catch ( PDOException $e )
							{
								$error['warning'] = $lang_ext['error_save'];
								 
							}
						}

						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Units', 'units_id: ' . $data['units_id'], $admin_info['userid'] );
						
						$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_update_success'] );
						 
					}
					else
					{
						$error['warning'] = $lang_ext['error_save'];

					}

					$stmt->closeCursor();

				}
				catch ( PDOException $e )
				{
					$error['warning'] = $lang_ext['error_save'];
					 
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
	$xtpl = new XTemplate( 'units_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
	$xtpl->assign( 'GLANG', $lang_global );
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
		$xtpl->parse( 'main.units_error_warning' );
	}

	foreach( $data['units_description'] as $language_id => $value )
	{
		$value['description'] = nv_htmlspecialchars( nv_br2nl( $value['description'] ) );
		$xtpl->assign( 'VALUE', $value );
		$xtpl->assign( 'LANG_ID', $language_id );

		if( isset( $error['name'] ) )
		{
			if( isset( $error['name'][$language_id] ) )
			{
				$xtpl->assign( 'units_error_name', $error['name'][$language_id] );
				$xtpl->parse( 'main.looplang.units_error_name' );
			}
		}

		$xtpl->parse( 'main.looplang' );
		$xtpl->parse( 'main.looplangscript' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
 
}

/*show list units*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_units cs 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_units_description cn 
ON cs.units_id = cn.units_id
WHERE cn.language_id = ' . $ProductGeneral->current_language_id;

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'cp.name', 'weight' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY weight";
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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=units&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( 'cs.*, cn.name, cn.description' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$xtpl = new XTemplate( 'units.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_WEIGHT', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=weight&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=units&action=add' );

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

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['units_id'] );

		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=units&action=edit&token=' . $item['token'] . '&units_id=' . $item['units_id'];

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
