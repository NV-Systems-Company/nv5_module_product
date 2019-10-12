<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Nuke.vn. All rights reserved
 * @website https://nuke.vn
 * @License GNU/GPL version 3 or any later version
 * @Createdate Wed, 24 Aug 2016 02:00:00 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$lang_ext = getLangAdmin( $op, 'product' );

$page_title = $lang_ext['heading_title'];

function nv_fix_brand()
{
	global $db, $db_config, $module_data;

	$sql = 'SELECT brand_id FROM ' . TABLE_PRODUCT_NAME . '_brand ORDER BY weight ASC';
	$weight = 0;
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		++$weight;
		$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_brand SET weight=' . $weight . ' WHERE brand_id=' . $row['brand_id'];
		$db->query( $sql );
	}
	$result->closeCursor();
}

if( ACTION_METHOD == 'weight' )
{
	$brand_id = $nv_Request->get_int( 'brand_id', 'post', 0 );
	$mod = $nv_Request->get_string( 'action', 'get,post', '' );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$content = 'NO_' . $brand_id;

	list( $brand_id ) = $db->query( 'SELECT brand_id FROM ' . TABLE_PRODUCT_NAME . '_brand WHERE brand_id=' . $brand_id )->fetch( 3 );
	if( $brand_id > 0 )
	{
		if( $mod == 'weight' and $new_vid > 0 )
		{
			$sql = 'SELECT brand_id FROM ' . TABLE_PRODUCT_NAME . '_brand WHERE brand_id!=' . $brand_id . ' ORDER BY weight ASC';
			$result = $db->query( $sql );

			$weight = 0;
			while( $row = $result->fetch() )
			{
				++$weight;
				if( $weight == $new_vid ) ++$weight;
				$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_brand SET weight=' . $weight . ' WHERE brand_id=' . intval( $row['brand_id'] );
				$db->query( $sql );
			}

			$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_brand SET weight=' . $new_vid . ' WHERE brand_id=' . $brand_id;
			$db->query( $sql );

			nv_fix_brand();
			$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_update_success'] );
			$nv_Cache->delMod( $module_name );
			$content = 'OK_' . $brand_id;
		}

	}
	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';

}
elseif( ACTION_METHOD == 'delete' )
{
	$json = array();
	$brand_id = $nv_Request->get_int( 'brand_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $brand_id ) )
	{
		$del_array = array( $brand_id );
	}

	if( ! empty( $del_array ) )
	{

		$sql = 'SELECT a.brand_id, b.name, b.alias  FROM ' . TABLE_PRODUCT_NAME . '_brand a
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_brand_description b ON (a.brand_id = b.brand_id)
		WHERE language_id=' . $ProductGeneral->current_language_id . ' AND a.brand_id IN (' . implode( ',', $del_array ) . ')';
		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$artitle = array();
		$a = 0;
		while( list( $brand_id, $title ) = $result->fetch( 3 ) )
		{
			$product_total = $db->query( 'SELECT COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_product WHERE brand_id = ' . ( int )$brand_id )->fetchColumn();
			if( $product_total == 0 )
			{
				if( $db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_brand WHERE brand_id = ' . ( int )$brand_id ) )
				{
					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_brand_description WHERE brand_id = ' . ( int )$brand_id );
					$nv_Cache->delMod( $module_name );
					$json['id'][$a] = $brand_id;
					$del_array[] = $brand_id;
					$artitle[] = $title;
					++$a;
				}
				else
				{
					$no_del_array[] = $brand_id;
				}
			}
			else
			{
				$json['error'] = sprintf( $lang_module['brand_error_product'], $product_total );
			}
		}

		$count = sizeof( $del_array );
		if( $count )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_brand', implode( ', ', $artitle ), $admin_info['userid'] );

			$nv_Request->unset_request( $module_data . '_success', 'session' );

			$json['success'] = $lang_ext['text_delete_success'];
		}
		if( ! empty( $no_del_array ) )
		{

			$json['error'] = $lang_ext['text_delete_error'];
		}

	}
	else
	{
		$json['error'] = $lang_ext['text_delete_error'];
	}
	get_output_json( $json );
}
elseif( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{
	if( defined( 'NV_EDITOR' ) )
	{
		require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
	}

	$data = array(
		'brand_id' => 0,
		'image' => '',
		'weight' => '',
		);
	foreach( $getLangModId as $language_id => $value )
	{
		$data['brand_description'][$language_id] = array( 'name' => '', 'description' => '' );
	}
	$error = array();

	$data['brand_id'] = $nv_Request->get_int( 'brand_id', 'get,post', 0 );
	if( $data['brand_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_brand  
		WHERE brand_id=' . $data['brand_id'] )->fetch();

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_brand_description WHERE brand_id=' . $data['brand_id'];

		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{

			$data['brand_description'][$rows['language_id']] = $rows;
		}
		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['brand_id'] = $nv_Request->get_int( 'brand_id', 'post', 0 );

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

		$data['brand_description'] = $nv_Request->get_typed_array( 'brand_description', 'post', array() );

		foreach( $data['brand_description'] as $language_id => $value )
		{
			if( ( nv_strlen( $value['name'] ) < 2 ) || ( nv_strlen( $value['name'] ) > 255 ) )
			{
				$error['name'][$language_id] = $lang_module['brand_error_name'];
			}
		}
		if( ! empty( $error ) && ! isset( $error['warning'] ) )
		{
			$error['warning'] = $lang_module['brand_error_warning'];
		}

		if( empty( $error ) )
		{
			if( $data['brand_id'] == 0 )
			{

				$stmt = $db->prepare( 'SELECT max(weight) FROM ' . TABLE_PRODUCT_NAME . '_brand' );
				$stmt->execute();
				$weight = $stmt->fetchColumn();

				$weight = intval( $weight ) + 1;

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_brand SET 
				image = :image, 
				weight = ' . intval( $weight ) );
				$stmt->bindParam( ':image', $data['image'], PDO::PARAM_STR );
				$stmt->execute();

				if( $data['brand_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();

					foreach( $data['brand_description'] as $language_id => $value )
					{
						$value['description'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $value['description'], '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );

						$value['name'] = isset( $value['name'] ) ? $value['name'] : '';

						$alias = change_alias( $value['name'] );
						$alias = strtolower( $alias );

						$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_brand_description SET 
							brand_id = ' . intval( $data['brand_id'] ) . ', 
							language_id = ' . intval( $language_id ) . ', 
							name = :name,
 							alias = :alias,
 							description = :description' );

						$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
						$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
						$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
						$stmt->execute();
						$stmt->closeCursor();
					}

					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A brand', 'brand_id: ' . $data['brand_id'], $admin_info['userid'] );

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
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_brand SET 
					image =:image 
					WHERE brand_id=' . $data['brand_id'] );

					$stmt->bindParam( ':image', $data['image'], PDO::PARAM_STR );
					if( $stmt->execute() )
					{
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_brand_description WHERE brand_id = ' . ( int )$data['brand_id'] );

						foreach( $data['brand_description'] as $language_id => $value )
						{
							$value['description'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $value['description'], '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
							$value['name'] = isset( $value['name'] ) ? $value['name'] : '';
							$alias = change_alias( $value['name'] );
							$alias = strtolower( $alias );
							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_brand_description SET 
								brand_id = ' . intval( $data['brand_id'] ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								name = :name,
								alias = :alias,
								description = :description' );

							$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
							$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
							$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );

							$stmt->execute();
							$stmt->closeCursor();
						}

						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A brand', 'brand_id: ' . $data['brand_id'], $admin_info['userid'] );

						$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_update_success'] );

						$nv_Cache->delMod( $module_name );
						Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
						die();
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
					//var_dump($e);
				}

			}

		}
		if( empty( $error ) )
		{
			$nv_Cache->delMod( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=brand' );
			die();
		}

	}

	if( ! empty( $data['image'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data['image'] ) )
	{
		$data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data['image'];
	}

	$xtpl = new XTemplate( 'brand_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
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
	$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name . '/brand' );
	$xtpl->assign( 'UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_name );
	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=brand' );
	$xtpl->assign( 'DISPLAYLANG', count( $getLangModId ) == 1 ? 'style="display:none"' : '' );

	foreach( $getLangModId as $lang_id_tab => $lang_tab )
	{
		$lang_tab['image'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang_tab['image'];
		$xtpl->assign( 'LANG_TITLE', $lang_tab );
		$xtpl->assign( 'LANG_KEY', $lang_id_tab );
		$xtpl->parse( 'main.looplangtab' );
	}

	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'error_warning', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}

	foreach( $data['brand_description'] as $language_id => $value )
	{
		$value['description'] = htmlspecialchars( nv_editor_br2nl( $value['description'] ) );

		if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
		{
			$value['description'] = nv_aleditor( "brand_description[" . $language_id . "][description]", '100%', '300px', $value['description'] );
		}
		else
		{
			$value['description'] = "<textarea style=\"width: 100%\" name=\"brand_description[" . $language_id . "][description]\" id=\"input-description" . $language_id . "\" cols=\"20\" rows=\"15\">" . $value['description'] . "</textarea>";
		}

		$xtpl->assign( 'VALUE', $value );
		$xtpl->assign( 'LANG_ID', $language_id );

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

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}

/*show list brand*/

$base_url_sort = $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

$per_page = 50;

$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_brand cs 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_brand_description cn 
ON cs.brand_id = cn.brand_id
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
 
$db->sqlreset()->select( 'cs.*, cn.name, cn.description' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$base_url .='&sort=' . $sort . '&order=' . $order . '&per_page=' . $per_page;

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';


$xtpl = new XTemplate( 'brand.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=brand&action=add" );

$xtpl->assign( 'URL_NAME', $base_url_sort . '&amp;sort=name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_WEIGHT', $base_url_sort . '&amp;sort=weight&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'NAME_ORDER', ( $sort == 'name' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'WEIGHT_ORDER', ( $sort == 'weight' ) ? 'class="' . $order2 . '"' : '' );
 

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

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['brand_id'] );

		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=brand&action=edit&token=" . $item['token'] . "&brand_id=" . $item['brand_id'];

		$xtpl->assign( 'LOOP', $item );

		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array(
				'key' => $i,
				'name' => $i,
				'selected' => ( $i == $item['weight'] ) ? ' selected="selected"' : '' ) );

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
