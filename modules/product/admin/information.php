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
	$information_id = $nv_Request->get_int( 'information_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $information_id ) )
	{
		$del_array = array( $information_id );
	}

	if( ! empty( $del_array ) )
	{
		if( $ProductGeneral->config['config_checkout_id'] == $information_id )
		{
			$json['error'] = $lang_ext['error_checkout'];

		}
		if( empty( $json['error'] ) )
		{
			$a = 0;
			$_del_array = array();
			$no_del_array = array();
			foreach( $del_array as $information_id )
			{

				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_information WHERE information_id = ' . ( int )$information_id );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_information_description WHERE information_id = ' . ( int )$information_id );
				$json['id'][$a] = $information_id;

				$_del_array[] = $information_id;

				++$a;
			}

		}

		if( sizeof( $_del_array ) )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_information', implode( ', ', $_del_array ), $admin_info['userid'] );
			
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

if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{
	if( defined( 'NV_EDITOR' ) )
	{
		require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
	}
	
	$selectthemes = ( ! empty( $site_mods[$module_name]['theme'] ) ) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
	$layout_array = nv_scandir( NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout'] );

	$data = array(
		'information_id' => 0,
		'layout' => '',
		'sort_order' => 0,
		'status' => 1,
		'date_added' => NV_CURRENTTIME,
		'date_modified' => 0,
		);
	foreach( $getLangModId as $language_id => $value )
	{
		$data['information_description'][$language_id] = array(
			'title' => '',
			'alias' => '',
			'description' => '',
			'meta_title' => '',
			'meta_description' => '',
			'meta_keyword' => '' );
	}
	$error = array();

	$data['information_id'] = $nv_Request->get_int( 'information_id', 'get,post', 0 );

	if( $data['information_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_information 
		WHERE information_id=' . $data['information_id'] )->fetch();

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_information_description WHERE information_id=' . $data['information_id'];

		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{

			$data['information_description'][$rows['language_id']] = $rows;
		}
		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['information_id'] = $nv_Request->get_int( 'information_id', 'post', 0 );
		$data['status'] = $nv_Request->get_int( 'status', 'post', 0 );
		$data['sort_order'] = $nv_Request->get_int( 'sort_order', 'post', 0 );
		$data['layout'] = nv_substr( $nv_Request->get_title( 'layout', 'post', '', '' ), 0, 255 );

		$data['information_description'] = $nv_Request->get_typed_array( 'information_description', 'post', array() );

		foreach( $data['information_description'] as $language_id => $value )
		{
			if( empty( $value['title'] ) )
			{
				$error['title'][$language_id] = $lang_ext['error_title'];
			}

			if( empty( $value['meta_title'] ) )
			{
				$error['meta_title'][$language_id] = $lang_ext['error_meta_title'];
			}
			if( ( nv_strlen( $value['description'] ) < 2 ) )
			{
				$error['error_description'][$language_id] = $lang_ext['error_description'];
			}
		}
		if ( !empty( $error ) && !isset( $error['warning'] ) ) 
		{
			$error['warning'] = $lang_ext['error_warning'];
		}
		if( empty( $error ) )
		{
			if( $data['information_id'] == 0 )
			{

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_information SET 
					layout = :layout,
					sort_order = ' . intval( $data['sort_order'] ) . ', 
					status = ' . intval( $data['status'] ) . ', 
					date_added = ' . intval( $data['date_added'] ) );

				$stmt->bindParam( ':layout', $data['layout'], PDO::PARAM_STR );
				$stmt->execute();

				if( $data['information_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();

					$checkInsert = array();	
					foreach( $data['information_description'] as $language_id => $value )
					{
						$value['title'] = isset( $value['title'] ) ? $value['title'] : '';
						$value['alias'] = ( empty( $value['alias'] ) ) ? change_alias( $value['title'] ) : change_alias( $value['alias'] );
						$value['description'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $value['description'], '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
						$value['meta_title'] = isset( $value['meta_title'] ) ? $value['meta_title'] : '';
						$value['meta_description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['meta_description'] ) ), '<br />' );
						$value['meta_keyword'] = isset( $value['meta_keyword'] ) ? $value['meta_keyword'] : '';

						try
						{
							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_information_description SET 
								information_id = ' . intval( $data['information_id'] ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								title = :title,
								alias = :alias,
								description = :description,
								meta_title = :meta_title,
								meta_description = :meta_description,
								meta_keyword=:meta_keyword' );

							$stmt->bindParam( ':title', $value['title'], PDO::PARAM_STR );
							$stmt->bindParam( ':alias', $value['alias'], PDO::PARAM_STR );
							$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
							$stmt->bindParam( ':meta_title', $value['meta_title'], PDO::PARAM_STR );
							$stmt->bindParam( ':meta_description', $value['meta_description'], PDO::PARAM_STR );
							$stmt->bindParam( ':meta_keyword', $value['meta_keyword'], PDO::PARAM_STR );
							$stmt->execute();
							$stmt->closeCursor();
							$checkInsert[] = $stmt->rowCount();
						}
						catch ( PDOException $e )
						{
							$error['warning'] = $lang_ext['error_save'];
						}
					}
					if( $checkInsert )
					{
						nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A information', 'information_id: ' . $data['information_id'], $admin_info['userid'] );
						$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );

					}
					
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
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_information SET 
						layout = :layout,
						sort_order = ' . intval( $data['sort_order'] ) . ', 
						status = ' . intval( $data['status'] ) . ',  
						date_modified=' . intval( NV_CURRENTTIME ) . '
						WHERE information_id=' . $data['information_id'] );

					$stmt->bindParam( ':layout', $data['layout'], PDO::PARAM_STR );
					if( $stmt->execute() )
					{
						$checkInsert = array();
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_information_description WHERE information_id = ' . ( int )$data['information_id'] );
				
						foreach( $data['information_description'] as $language_id => $value )
						{
							$value['title'] = isset( $value['title'] ) ? $value['title'] : '';
							$value['alias'] = ( empty( $value['alias'] ) ) ? change_alias( $value['title'] ) : change_alias( $value['alias'] );
							$value['description'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $value['description'], '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
							$value['meta_title'] = isset( $value['meta_title'] ) ? $value['meta_title'] : '';
							$value['meta_description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['meta_description'] ) ), '<br />' );
							$value['meta_keyword'] = isset( $value['meta_keyword'] ) ? $value['meta_keyword'] : '';
							try
							{
								$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_information_description SET 
									information_id = ' . intval( $data['information_id'] ) . ', 
									language_id = ' . intval( $language_id ) . ', 
									title = :title,
									alias = :alias,
									description = :description,
									meta_title = :meta_title,
									meta_description = :meta_description,
									meta_keyword=:meta_keyword' );

								$stmt->bindParam( ':title', $value['title'], PDO::PARAM_STR );
								$stmt->bindParam( ':alias', $value['alias'], PDO::PARAM_STR );
								$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
								$stmt->bindParam( ':meta_title', $value['meta_title'], PDO::PARAM_STR );
								$stmt->bindParam( ':meta_description', $value['meta_description'], PDO::PARAM_STR );
								$stmt->bindParam( ':meta_keyword', $value['meta_keyword'], PDO::PARAM_STR );
								$stmt->execute();
								$stmt->closeCursor();
								$checkInsert[] =  $stmt->rowCount();

							}
							catch ( PDOException $e )
							{
								$error['warning'] = $lang_ext['error_save'];
							}
						}
						
						if( $checkInsert )
						{
							nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A information', 'information_id: ' . $data['information_id'], $admin_info['userid'] );
	 
							$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_update_success'] );
						}
						
						
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
					//$error['warning'] = $e;
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

	$xtpl = new XTemplate( 'information_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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
		$xtpl->assign( 'error_warning', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}
	
	foreach( $data['information_description'] as $language_id => $value )
	{
		$value['description'] = htmlspecialchars( nv_editor_br2nl( $value['description'] ) );
		$value['meta_description'] = nv_htmlspecialchars( nv_br2nl( $value['meta_description'] ) );
 
		if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
		{
			$value['descript'] = nv_aleditor( "information_description[" . $language_id . "][description]", '100%', '300px', $value['description'] );
		}
		else
		{
			$value['descript'] = "<textarea style=\"width: 100%\" name=\"information_description[" . $language_id . "][description]\" id=\"input-description" . $language_id . "\" cols=\"20\" rows=\"15\">" . $value['description'] . "</textarea>";
		}
		
		$xtpl->assign( 'VALUE', $value );
		$xtpl->assign( 'LANG_ID', $language_id );

		if( empty( $value['alias'] ) )
		{
			$xtpl->parse( 'main.looplang.getalias' );
		}
		if( isset( $error['title'] ) )
		{
			if( isset( $error['title'][$language_id] ) )
			{
				$xtpl->assign( 'error_title', $error['title'][$language_id] );
				$xtpl->parse( 'main.looplang.error_title' );
			}
		}
		if( isset( $error['description'] ) )
		{
			if( isset( $error['description'][$language_id] ) )
			{
				$xtpl->assign( 'error_description', $error['description'][$language_id] );
				$xtpl->parse( 'main.looplang.error_description' );
			}
		}
		if( isset( $error['meta_title'] ) )
		{
			if( isset( $error['meta_title'][$language_id] ) )
			{
				$xtpl->assign( 'error_meta_title', $error['meta_title'][$language_id] );
				$xtpl->parse( 'main.looplang.error_meta_title' );
			}
		}
		
		$xtpl->parse( 'main.looplang' );
		$xtpl->parse( 'main.looplangscript' );
	}

	foreach( $layout_array as $value )
	{
		$value = preg_replace( $global_config['check_op_layout'], '\\1', $value );
		$xtpl->assign( 'LAYOUT', array( 'key' => $value, 'selected' => ( $data['layout'] == $value ) ? ' selected="selected"' : '' ) );
		$xtpl->parse( 'main.layout' );
	}

	foreach( $productArrayStatus as $key => $value )
	{ 
		$xtpl->assign( 'STATUS', array(
			'key' => $key,
			'name' => $value,
			'selected' => ( $key == $data['status'] ) ? 'selected="selected"' : '',
			) );

		$xtpl->parse( 'main.status' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';

	exit();
}

/*show list information*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_information a 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_information_description b 
ON a.information_id = b.information_id
WHERE b.language_id = ' . $ProductGeneral->current_language_id;

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'b.title', 'a.sort_order' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY a.sort_order";
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

$db->sqlreset()->select( 'a.*, b.*' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$xtpl = new XTemplate( 'information.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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
$xtpl->assign( 'URL_TITLE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=b.title&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_SORT_ORDER', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=a.sort_order&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_STATUS', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=a.status&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
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
		
		$item['status'] = $productArrayStatus[$item['status']];
		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['information_id'] );
		$item['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info/' . $item['alias'] . '-' . $item['information_id'] . $global_config['rewrite_exturl'], true );
		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=edit&token=" . $item['token'] . "&information_id=" . $item['information_id'];
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
