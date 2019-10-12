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

function nv_fix_block_cat()
{
	global $db, $db_config, $module_data;

	$sql = 'SELECT block_id FROM ' . TABLE_PRODUCT_NAME . '_block_cat ORDER BY weight ASC';
	$weight = 0;
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		++$weight;
		$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_block_cat SET weight=' . $weight . ' WHERE block_id=' . $row['block_id'];
		$db->query( $sql );
	}
	$result->closeCursor();
}


if( in_array( ACTION_METHOD, array( 'weight', 'adddefault' ) ) )
{
	$json = array();
	$block_id = $nv_Request->get_int( 'block_id', 'post', 0 );
	$mod = $nv_Request->get_string( 'action', 'get,post', '' );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	 
	list( $block_id, $adddefault ) = $db->query( 'SELECT block_id, adddefault FROM ' . TABLE_PRODUCT_NAME . '_block_cat WHERE block_id=' . $block_id )->fetch( 3 );
	if( $block_id > 0 )
	{
		if( $mod == 'weight' and $new_vid > 0 )
		{
			$sql = 'SELECT block_id FROM ' . TABLE_PRODUCT_NAME . '_block_cat WHERE block_id!=' . $block_id . ' ORDER BY weight ASC';
			$result = $db->query( $sql );

			$weight = 0;
			while( $row = $result->fetch() )
			{
				++$weight;
				if( $weight == $new_vid ) ++$weight;
				$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_block_cat SET weight=' . $weight . ' WHERE block_id=' . intval( $row['block_id'] );
				$db->query( $sql );
			}

			$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_block_cat SET weight=' . $new_vid . ' WHERE block_id=' . $block_id;
			$db->query( $sql );

			nv_fix_block_cat();
			
			
			$json['success'] = $lang_ext['success_change_weight'];
		}
		elseif( $mod == 'adddefault' and ( $new_vid == 0 or $new_vid == 1 ) )
		{
			$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_block_cat SET adddefault=' . $new_vid . ' WHERE block_id=' . $block_id;
			$db->query( $sql );

			$json['success'] = $lang_ext['success_adddefault'];
		}

		$nv_Cache->delMod( $module_name );
	}
	nv_jsonOutput( $json );

}
elseif( ACTION_METHOD == 'delete' )
{
	$json = array();
	$block_id = $nv_Request->get_int( 'block_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $block_id ) )
	{
		$del_array = array( $block_id );
	}

	if( ! empty( $del_array ) )
	{

		$sql = 'SELECT block_id, name FROM ' . TABLE_PRODUCT_NAME . '_block_cat_description WHERE block_id IN (' . implode( ',', $del_array ) . ')';
		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$a = 0;
		while( list( $block_id, $title ) = $result->fetch( 3 ) )
		{

			if( $db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_block_cat WHERE block_id = ' . ( int )$block_id ) )
			{
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_block_cat_description WHERE block_id = ' . ( int )$block_id );
				$json['id'][$a] = $block_id;
				$del_array[] = $block_id;
				++$a;
			}
			else
			{
				$no_del_array[] = $block_id;
			}
		}

		$count = sizeof( $del_array );
		if( $count )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_block_cat', implode( ', ', $del_array ), $admin_info['userid'] );

			$nv_Cache->delMod( $module_name );

			$json['success'] = $lang_ext['success_delele'];
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
		'block_id' => 0,
		'weight' => '',
		'adddefault' => ''  );
	
	foreach( $getLangModId as $language_id => $value )
	{
		$data['block_cat_description'][$language_id] = array(
			'name' => '',
			'description' => '',
		);
	}
	
	$error = array();

	$data['block_id'] = $nv_Request->get_int( 'block_id', 'get,post', 0 );
	if( $data['block_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_block_cat  
		WHERE block_id=' . $data['block_id'] )->fetch();

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_block_cat_description WHERE block_id=' . $data['block_id'];

		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{

			$data['block_cat_description'][$rows['language_id']] = $rows;
		}
		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['block_id'] = $nv_Request->get_int( 'block_id', 'post', 0 );
		$data['adddefault'] = $nv_Request->get_int( 'adddefault', 'post', 0 );

		$data['block_cat_description'] = $nv_Request->get_typed_array( 'block_cat_description', 'post', array() );

		foreach( $data['block_cat_description'] as $language_id => $value )
		{
			if( empty( $value['name'] ) )
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
			if( $data['block_id'] == 0 )
			{

				$stmt = $db->prepare( 'SELECT max(weight) FROM ' . TABLE_PRODUCT_NAME . '_block_cat' );
				$stmt->execute();
				$weight = $stmt->fetchColumn();

				$weight = intval( $weight ) + 1;

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_block_cat SET 
				adddefault = ' . intval( $data['adddefault'] ) . ', 
				weight = ' . intval( $weight ) );
				$stmt->execute();

				if( $data['block_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();

					
					foreach( $data['block_cat_description'] as $language_id => $value )
					{
						$value['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
						$value['alias'] = ( $value['alias'] == '' ) ? change_alias( $value['name'] ) : change_alias( $value['alias'] );
						$value['name'] = isset( $value['name'] ) ? $value['name'] : '';
						try
						{
							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_block_cat_description SET 
								block_id = ' . intval( $data['block_id'] ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								name = :name,
								alias = :alias,
								description = :description' );

							$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
							$stmt->bindParam( ':alias', $value['alias'], PDO::PARAM_STR );
							$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
							$stmt->execute();
							$stmt->closeCursor();
						}
						catch ( PDOException $e )
						{
							$error['warning'] = $lang_ext['error_save'];
						}
					}
 	
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Block', 'block_id: ' . $data['block_id'], $admin_info['userid'] );
					
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
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_block_cat SET 
					adddefault = ' . intval( $data['adddefault'] ) );

					if( $stmt->execute() )
					{
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_block_cat_description WHERE block_id = ' . ( int )$data['block_id'] );

						foreach( $data['block_cat_description'] as $language_id => $value )
						{
							$value['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
							$value['alias'] = ( $value['alias'] == '' ) ? change_alias( $value['name'] ) : change_alias( $value['alias'] );
							$value['name'] = isset( $value['name'] ) ? $value['name'] : '';
							try
							{
								$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_block_cat_description SET 
									block_id = ' . intval( $data['block_id'] ) . ', 
									language_id = ' . intval( $language_id ) . ', 
									name = :name,
									alias = :alias,
									description = :description' );

								$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
								$stmt->bindParam( ':alias', $value['alias'], PDO::PARAM_STR );
								$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );

								$stmt->execute();
								$stmt->closeCursor();
							}
							catch ( PDOException $e )
							{
								$error['warning'] = $lang_ext['error_save'];
							}
						}

						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Block', 'block_id: ' . $data['block_id'], $admin_info['userid'] );
						
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
					//var_dump($error['warning']);
				}

			}

		}
		if( empty( $error ) )
		{
			$nv_Cache->delMod( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=block_cat' );
			die();
		}

	}
	$xtpl = new XTemplate( 'block_cat_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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
	
	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'error_warning', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}

	foreach( $getLangModId as $lang_id_tab => $lang_tab )
	{
		$lang_tab['image'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang_tab['image'];
		$xtpl->assign( 'LANG_TITLE', $lang_tab );
		$xtpl->assign( 'LANG_KEY', $lang_id_tab );
		$xtpl->parse( 'main.looplangtab' );
	}

	
	foreach( $data['block_cat_description'] as $language_id => $value )
	{
		$value['description'] = nv_htmlspecialchars( nv_br2nl( $value['description'] ) );
		$xtpl->assign( 'VALUE', $value );
		$xtpl->assign( 'LANG_ID', $language_id );

		if( empty( $value['alias'] ) )
		{
			$xtpl->parse( 'main.looplang.getalias' );
		}

		if( isset( $error['name'] ) )
		{
			if( isset( $error['name'][$language_id] ) )
			{
				$xtpl->assign( 'error_name', $error['name'][$language_id] );
				$xtpl->parse( 'main.looplang.error_name' );
			}
		}

		$xtpl->parse( 'main.looplang' );
		$xtpl->parse( 'main.looplangscript' );
	}

	foreach( $productArrayYesNo as $key => $name )
	{
		$xtpl->assign( 'DEFAULT', array('key'=> $key, 'name'=> $name, 'selected'=> ( $key == $data['adddefault'] ) ? 'selected="selected"' : '') );
		$xtpl->parse( 'main.adddefault' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}

/*show list block_cat*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_block_cat cs 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_block_cat_description cn 
ON cs.block_id = cn.block_id
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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=block_cat&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( 'cs.*, cn.name' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}
$result->closeCursor();


$xtpl = new XTemplate( 'block_cat.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_WEIGHT', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=weight&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=block_cat&action=add' );

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

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['block_id'] );

		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=block_cat&action=edit&token=" . $item['token'] . "&block_id=" . $item['block_id'];

		$xtpl->assign( 'LOOP', $item );

		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array( 'key' => $i, 'name' => $i, 'selected' => ( $i == $item['weight'] ) ? ' selected="selected"' : '' ) );

			$xtpl->parse( 'main.loop.weight' );
		}
		foreach( $productArrayStatus as $key => $val )
		{
			$xtpl->assign( 'ADDDEFAULT', array(
				'key' => $key,
				'title' => $val,
				'selected' => $key == $item['adddefault'] ? ' selected="selected"' : '' ) );
			$xtpl->parse( 'main.loop.adddefault' );
		}

		$xtpl->parse( 'main.loop' );
	}
	$array_data = null;
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
