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

function nv_fix_option()
{
	global $db, $db_config, $module_data;

	$sql = 'SELECT option_id FROM ' . TABLE_PRODUCT_NAME . '_option ORDER BY sort_order ASC';
	$sort_order = 0;
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		++$sort_order;
		$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_option SET sort_order=' . $sort_order . ' WHERE option_id=' . $row['option_id'];
		$db->query( $sql );
	}
	$result->closeCursor();
}

if( ACTION_METHOD =='sort_order' )
{
	$json = array();
	$option_id = $nv_Request->get_int( 'option_id', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
 
	list( $option_id ) = $db->query( 'SELECT option_id FROM ' . TABLE_PRODUCT_NAME . '_option WHERE option_id=' . $option_id )->fetch( 3 );
	if( $option_id > 0 )
	{
		 
		$sql = 'SELECT option_id FROM ' . TABLE_PRODUCT_NAME . '_option WHERE option_id!=' . $option_id . ' ORDER BY sort_order ASC';
		$result = $db->query( $sql );

		$sort_order = 0;
		while( $row = $result->fetch() )
		{
			++$sort_order;
			if( $sort_order == $new_vid ) ++$sort_order;
			$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_option SET sort_order=' . $sort_order . ' WHERE option_id=' . intval( $row['option_id'] );
			$db->query( $sql );
		}
		$result->closeCursor();
		
		
		$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_option SET sort_order=' . $new_vid . ' WHERE option_id=' . $option_id;
		$db->query( $sql );

		nv_fix_option();
			
		$json['success'] = $lang_ext['text_status_success'];
		 

		$nv_Cache->delMod( $module_name );
		
	}else{
		
		$json['error'] = $lang_ext['text_status_error'];
	}
	nv_jsonOutput( $json );

}
elseif( ACTION_METHOD == 'delete' )
{
	$json = array();
	$error = array();

	$option_id = $nv_Request->get_int( 'option_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $option_id ) )
	{
		$del_array = array( $option_id );
	}

	if( ! empty( $del_array ) )
	{

		$sql = 'SELECT cn.option_id, cn.name FROM ' . TABLE_PRODUCT_NAME . '_option cs 
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option_description cn ON cs.option_id = cn.option_id
		WHERE cn.language_id = ' . $ProductGeneral->current_language_id . ' AND cs.option_id IN (' . implode( ',', $del_array ) . ')';

		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$artitle = array();
		$a = 0;
		while( list( $option_id, $title ) = $result->fetch( 3 ) )
		{

			$product_total = $db->query( 'SELECT COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_product_option WHERE option_id = ' . ( int )$option_id )->fetchColumn();
			if( $product_total == 0 )
			{

				if( $db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_option WHERE option_id = ' . ( int )$option_id ) )
				{

					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_option_description WHERE option_id = ' . ( int )$option_id );
					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_option_value WHERE option_id = ' . ( int )$option_id );
					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_option_value_description WHERE option_id = ' . ( int )$option_id );
 
					$json['option_id'][$a] = $option_id;

					$del_array[] = $option_id;
					++$a;
				}
				else
				{
					$no_del_array[] = $option_id;
				}
			}
			else
			{
				$json['error'] = sprintf( $lang_ext['error_product'], $product_total );
			}

		}

		$count = sizeof( $del_array );
		if( $count )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_option', implode( ', ', $no_del_array ), $admin_info['userid'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$json['success'] = $lang_ext['success_delele'] ;
			
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
		'option_id' => 0,
		'type' => '',
		'sort_order' => '',
		'option_value' => array() );

	foreach( $getLangModId as $language_id => $value )
	{
		$data['option_description'][$language_id] = array( 'name' => '' );
	}

	$error = array();
	$error_key = array();

	$data['option_id'] = $nv_Request->get_int( 'option_id', 'get,post', 0 );
	if( $data['option_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_option  
		WHERE option_id=' . $data['option_id'] )->fetch();

		$data['sort_order_old'] = $data['sort_order'];

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_option_description WHERE option_id=' . $data['option_id'];

		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{

			$data['option_description'][$rows['language_id']] = $rows;
		}
		unset( $sql, $rows );
		$result->closeCursor();unset( $result );

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_option_value WHERE option_id=' . $data['option_id'] . ' ORDER BY sort_order';
		$result = $db->query( $sql );
		while( $option_value = $result->fetch() )
		{
			$option_value_description_data = array();

			$sql2 = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_option_value_description WHERE option_value_id=' . $option_value['option_value_id'];
			$result2 = $db->query( $sql2 );
			while( $option_value_description = $result2->fetch() )
			{
				$option_value_description_data[$option_value_description['language_id']] = array( 'name' => $option_value_description['name'] );
			}
			$result2->closeCursor();unset( $result2 );

			if( ! empty( $option_value['image'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $option_value['image'] ) )
			{
				$option_value['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $option_value['image'];
			}
			$data['option_value'][] = array(
				'option_value_id' => $option_value['option_value_id'],
				'option_value_description' => $option_value_description_data,
				'image' => $option_value['image'],
				'sort_order' => $option_value['sort_order'] );

		}

		$result->closeCursor();unset( $result );

		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['option_id'] = $nv_Request->get_int( 'option_id', 'post', 0 );
		$data['type'] = $nv_Request->get_title( 'type', 'post', '' );
		$data['sort_order'] = $nv_Request->get_int( 'sort_order', 'post', 0 );

		$data['option_value'] = $nv_Request->get_typed_array( 'option_value', 'post', array() );
		$data['option_description'] = $nv_Request->get_typed_array( 'option_description', 'post', array() );

		foreach( $data['option_description'] as $language_id => $value )
		{
			if( empty( $value['name'] ) )
			{
				$error['name'][$language_id] = $lang_ext['error_name'];
			}
		}

		if( ( $data['type'] == 'select' || $data['type'] == 'radio' || $data['type'] == 'checkbox' ) && empty( $data['option_value'] ) )
		{
			$error['warning'] = $lang_ext['error_type'];
		}

		if( ! empty( $data['option_value'] ) )
		{
			foreach( $data['option_value'] as $option_value_id => $option_value )
			{
				foreach( $option_value['option_value_description'] as $language_id => $option_value_description )
				{
					if( empty( $option_value_description['name'] ) )
					{
						$error['option_value'][$option_value_id][$language_id] = $lang_ext['error_option_value'];
					}
				}
			}
		}

		if( ! empty( $error ) && ! isset( $error['warning'] ) )
		{
			$error['warning'] = $lang_ext['error_warning'];
		}

		if( empty( $error ) )
		{
			if( $data['option_id'] == 0 )
			{

				$stmt = $db->prepare( 'SELECT max(sort_order) FROM ' . TABLE_PRODUCT_NAME . '_option' );
				$stmt->execute();
				$sort_order = $stmt->fetchColumn();

				$sort_order = intval( $sort_order ) + 1;

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_option SET 
				type = :type, 
				sort_order = :sort_order' );

				if( empty( $data['sort_order'] ) )
				{
					$data['sort_order'] = $sort_order;
				}

				$stmt->bindParam( ':type', $data['type'], PDO::PARAM_STR );
				$stmt->bindParam( ':sort_order', $data['sort_order'], PDO::PARAM_STR );
				$stmt->execute();

				if( $data['option_id'] = $db->lastInsertId() )
				{
					nv_fix_option();

					$stmt->closeCursor();

					
					foreach( $data['option_description'] as $language_id => $value )
					{
						$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_option_description SET 
							option_id = ' . intval( $data['option_id'] ) . ', 
							language_id = ' . intval( $language_id ) . ', 
							name = :name ' );

						$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
						$stmt->execute();
						$stmt->closeCursor();
					}

					if( ! empty( $data['option_value'] ) )
					{
						foreach( $data['option_value'] as $option_value )
						{

							$option_value['image'] = $nv_Request->security_post( $option_value['image'] );
							if( is_file( NV_DOCUMENT_ROOT . $option_value['image'] ) )
							{
								$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
								$option_value['image'] = substr( $option_value['image'], $lu );
							}
							else
							{
								$option_value['image'] = '';
							}

							$option_value['sort_order'] = ( int )$option_value['sort_order'];

							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_option_value SET 
							option_id = ' . intval( $data['option_id'] ) . ', 
							image = :image, 
							sort_order = :sort_order' );

							$stmt->bindParam( ':image', $option_value['image'], PDO::PARAM_STR );
							$stmt->bindParam( ':sort_order', $option_value['sort_order'], PDO::PARAM_INT );
							$stmt->execute();

							$option_value_id = $db->lastInsertId();

							$stmt->closeCursor();

							foreach( $option_value['option_value_description'] as $language_id => $option_value_description )
							{
								$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_option_value_description SET 
								option_value_id = ' . intval( $option_value_id ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								option_id = ' . intval( $data['option_id'] ) . ', 
								name = :name ' );

								$stmt->bindParam( ':name', $option_value_description['name'], PDO::PARAM_STR );
								$stmt->execute();

							}
						}
					}
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A option', 'option_id: ' . $data['option_id'], $admin_info['userid'] );

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

					$stmt = $db->prepare( 'SELECT max(sort_order) FROM ' . TABLE_PRODUCT_NAME . '_option' );
					$stmt->execute();
					$sort_order = $stmt->fetchColumn();

					$sort_order = intval( $sort_order ) + 1;

					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_option SET 
					type = :type,
					sort_order = :sort_order
					WHERE option_id=' . $data['option_id'] );

					if( empty( $data['sort_order'] ) )
					{
						$data['sort_order'] = $sort_order;
					}

					$stmt->bindParam( ':type', $data['type'], PDO::PARAM_STR );
					$stmt->bindParam( ':sort_order', $data['sort_order'], PDO::PARAM_INT );

					if( $stmt->execute() )
					{
						$stmt->closeCursor();

						nv_fix_option();

						
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_option_description WHERE option_id = ' . ( int )$data['option_id'] );

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_option_value WHERE option_id = ' . ( int )$data['option_id'] );

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_option_value_description WHERE option_id = ' . ( int )$data['option_id'] );

						foreach( $data['option_description'] as $language_id => $value )
						{
							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_option_description SET 
								option_id = ' . intval( $data['option_id'] ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								name = :name ' );

							$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
							$stmt->execute();
							$stmt->closeCursor();
						}

						if( ! empty( $data['option_value'] ) )
						{
							foreach( $data['option_value'] as $option_value )
							{

								$option_value['image'] = $nv_Request->security_post( $option_value['image'] );
								if( is_file( NV_DOCUMENT_ROOT . $option_value['image'] ) )
								{
									$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
									$option_value['image'] = substr( $option_value['image'], $lu );
								}
								else
								{
									$option_value['image'] = '';
								}
								$option_value['sort_order'] = ( int )$option_value['sort_order'];

								$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_option_value SET 
								option_id = ' . intval( $data['option_id'] ) . ', 
								image = :image, 
								sort_order = :sort_order' );

								$stmt->bindParam( ':image', $option_value['image'], PDO::PARAM_STR );
								$stmt->bindParam( ':sort_order', $option_value['sort_order'], PDO::PARAM_INT );
								$stmt->execute();

								$option_value_id = $db->lastInsertId();

								$stmt->closeCursor();

								foreach( $option_value['option_value_description'] as $language_id => $option_value_description )
								{
									$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_option_value_description SET 
									option_value_id = ' . intval( $option_value_id ) . ', 
									language_id = ' . intval( $language_id ) . ', 
									option_id = ' . intval( $data['option_id'] ) . ', 
									name = :name ' );

									$stmt->bindParam( ':name', $option_value_description['name'], PDO::PARAM_STR );
									$stmt->execute();

								}
							}
						}
						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A option', 'option_id: ' . $data['option_id'], $admin_info['userid'] );

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
					//var_dump($error['warning']);
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

	$xtpl = new XTemplate( 'option_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'AddMenu', AddMenu() );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'THEME', $global_config['module_theme'] );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'DATA', $data );
	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=option' );
	$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );
	$xtpl->assign( 'UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_name );

	foreach( $data['option_description'] as $language_id => $value )
	{ 
		$xtpl->assign( 'VALUE', $value );
		$xtpl->assign( 'LANG_ID', $language_id );
		$xtpl->assign( 'LANG_IMAGE', NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $getLangModId[$language_id]['image'] );
		$xtpl->assign( 'LANG_TITLE', $getLangModId[$language_id]['name'] );

		if( isset( $error['name'] ) )
		{
			if( isset( $error['name'][$language_id] ) )
			{
				$xtpl->assign( 'option_error_name', $error['name'][$language_id] );
				$xtpl->parse( 'main.looplang.option_error_name' );
			}
		}

		$xtpl->parse( 'main.looplang' );
		$xtpl->parse( 'main.looplang1' );

	}
	unset( $value );
	foreach( $getOptionType as $key => $value )
	{
		$xtpl->assign( 'label', $key );
		foreach( $value as $type )
		{
			$xtpl->assign( 'selected', ( $type == $data['type'] ) ? 'selected="selected"' : '' );
			$xtpl->assign( 'key', $type );
			$xtpl->assign( 'type', ucfirst( $type ) );
			$xtpl->parse( 'main.optgroup.option' );
		}
		$xtpl->parse( 'main.optgroup' );
	}

	$option_value_row = 0;
	unset( $key );
	if( ! empty( $data['option_value'] ) )
	{
		foreach( $data['option_value'] as $key => $value )
		{

			$xtpl->assign( 'valuekey', $key );
			$xtpl->assign( 'LOOP', $value );

			foreach( $getLangModId as $lang_id_tab => $lang_tab )
			{
				$lang_tab['image'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang_tab['image'];
				$xtpl->assign( 'LANG_TITLE', $lang_tab );
				$xtpl->assign( 'LANG_KEY', $lang_id_tab );
				$xtpl->assign( 'NAME', $value['option_value_description'][$lang_id_tab]['name'] );

				if( isset( $error['option_value'][$key][$lang_id_tab] ) )
				{
					$xtpl->assign( 'option_value_error_name', $error['option_value'][$key][$lang_id_tab] );
					$xtpl->parse( 'main.option_value.looplang5.option_value_error_name' );
				}

				$xtpl->parse( 'main.option_value.looplang5' );

			}
			++$option_value_row;
			$xtpl->parse( 'main.option_value' );
		}

	}

	$xtpl->assign( 'option_value_row', $option_value_row );

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

/*show list option*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_option cs 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option_description cn 
ON cs.option_id = cn.option_id
WHERE cn.language_id = ' . $ProductGeneral->current_language_id;

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'sort_order', 'cn.name' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY sort_order";
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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=option&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( 'cs.*, cn.name ' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$xtpl = new XTemplate( 'option.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=cn.name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_SORT', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=sort_order&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=option&action=add' );

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

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['option_id'] );

		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=option&action=edit&token=" . $item['token'] . "&option_id=" . $item['option_id'];

		$xtpl->assign( 'LOOP', $item );
		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'SORT', array( 'key' => $i, 'name' => $i, 'selected' => ( $i == $item['sort_order'] ) ? ' selected="selected"' : '' ) );

			$xtpl->parse( 'main.loop.sort_order' );
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
