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

$page_title = $lang_module['tags'];
 
if( ACTION_METHOD == 'delete' )
{
	$info = array();
	$tags_id = $nv_Request->get_int( 'tags_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $tags_id ) )
	{
		$del_array = array( $tags_id );
	}

	if( ! empty( $del_array ) )
	{

		$sql = 'SELECT tags_id, ' . NV_LANG_DATA . '_name name  FROM ' . $db_config['prefix'] . '_' . $module_data . '_tags WHERE tags_id IN (' . implode( ',', $del_array ) . ')';
		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$artitle = array();
		$a = 0;
		while( list( $tags_id, $title ) = $result->fetch( 3 ) )
		{

			if( $db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_tags WHERE tags_id = ' . ( int )$tags_id ) )
			{
				//$db->query('DELETE FROM ' . $db_config['prefix'] . '_'. $module_data . '_product_reward WHERE tags_id = ' . (int)$tags_id);
				//$db->query('DELETE FROM ' . $db_config['prefix'] . '_'. $module_data . '_product_discount WHERE tags_id = ' . (int)$tags_id);

				 
				$info['id'][$a] = $tags_id;
				$del_array[] = $tags_id;
				$artitle[] = $title;
				++$a;
			}
			else
			{
				$no_del_array[] = $tags_id;
			}
		}

		$count = sizeof( $del_array );
		if( $count )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_tags', implode( ', ', $artitle ), $admin_info['userid'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$info['success'] = $lang_module['success_del_tags'];
			
			$ProductGeneral->deleteCache( 'tags' );
			
		}
		if( ! empty( $no_del_array ) )
		{

			$info['error'] = $lang_module['error_no_del_tags'];
		}

	}
	else
	{
		$info['error'] = $lang_module['error_no_del_tags'];
	}
	echo json_encode( $info );
	exit();
}

if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$data = array( 'tags_id' => 0, 'approval' => 0 );
	foreach( $getLangModId as $language_id => $value )
	{
		$data['tags_description'][$language_id] = array( 'alias' => '', 'keywords' => '', 'description' => '' );
	}
	$error = array();
	$error_key = array();

	$data['tags_id'] = $nv_Request->get_int( 'tags_id', 'get,post', 0 );
	if( $data['tags_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_tags  
		WHERE tags_id=' . $data['tags_id'] )->fetch();

		$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_tags_description WHERE tags_id=' . $data['tags_id'];

		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{

			$data['tags_description'][$rows['language_id']] = $rows;
		}
		$caption = $lang_module['tags_edit'];
	}
	else
	{
		$caption = $lang_module['tags_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['tags_id'] = $nv_Request->get_int( 'tags_id', 'post', 0 );
		
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
		
		$data['tags_description'] = $nv_Request->get_typed_array( 'tags_description', 'post', array() );
		
		foreach( $data['tags_description'] as $language_id => $value )
		{
			if( ( nv_strlen( $value['alias'] ) < 3 ) || ( nv_strlen( $value['alias'] ) > 255 ) )
			{
				$error['alias'][$language_id] = $lang_module['tags_error_alias'];
			}
			
			
			
		}
		if( ! empty( $error ) && ! isset( $error['warning'] ) )
		{
			$error['warning'] = $lang_module['tags_error_warning'];
		}

		if( empty( $error ) )
		{
			if( $data['tags_id'] == 0 )
			{
 			
				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_tags SET image =:image');
				$stmt->bindParam( ':image', $data['image'], PDO::PARAM_STR );
				$stmt->execute();
		 
 
				if( $data['tags_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A tags', 'tags_id: ' . $data['tags_id'], $admin_info['userid'] );

					foreach( $data['tags_description'] as $language_id => $value )
					{
 
						$alias = str_replace( '-', ' ', nv_unhtmlspecialchars( $value['alias'] ) );
						
						$keywords = explode( ',', $value['keywords'] );
						$keywords[] = $alias;
						$keywords = array_map( 'strip_punctuation', $keywords );
						$keywords = array_map( 'trim', $keywords );
						$keywords = array_diff( $keywords, array( '' ) );
						$keywords = array_unique( $keywords );
						$keywords = implode( ',', $keywords );

						$alias = str_replace( ' ', '-', strip_punctuation( $alias ) );
						
 						$value['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
						
						$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_tags_description SET 
							tags_id = ' . intval( $data['tags_id'] ) . ', 
							language_id = ' . intval( $language_id ) . ', 
							alias = :alias,
							keywords = :keywords,
							description = :description' );

						$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
						$stmt->bindParam( ':keywords', $keywords, PDO::PARAM_STR );
						$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
						$stmt->execute();
						$stmt->closeCursor();
					}

				}
				else
				{
					$error['warning'] = $lang_module['errorsave'];

				}

			}
			else
			{
				try
				{
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_tags SET image =:image WHERE tags_id = ' . ( int )$data['tags_id']);
					$stmt->bindParam( ':image', $data['image'], PDO::PARAM_STR );
 
					if( $stmt->execute() )
					{
						$stmt->closeCursor();
						
						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A tags', 'tags_id: ' . $data['tags_id'], $admin_info['userid'] );

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_tags_description WHERE tags_id = ' . ( int )$data['tags_id'] );

						foreach( $data['tags_description'] as $language_id => $value )
						{
							$alias = str_replace( '-', ' ', nv_unhtmlspecialchars( $value['alias'] ) );
						
							$keywords = explode( ',', $value['keywords'] );
							$keywords[] = $alias;
							$keywords = array_map( 'strip_punctuation', $keywords );
							$keywords = array_map( 'trim', $keywords );
							$keywords = array_diff( $keywords, array( '' ) );
							$keywords = array_unique( $keywords );
							$keywords = implode( ',', $keywords );

							$alias = str_replace( ' ', '-', strip_punctuation( $alias ) );
							
							$value['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
							
							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_tags_description SET 
								tags_id = ' . intval( $data['tags_id'] ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								alias = :alias,
								keywords = :keywords,
								description = :description' );

							$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
							$stmt->bindParam( ':keywords', $keywords, PDO::PARAM_STR );
							$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
							$stmt->execute();
							$stmt->closeCursor();
						}
						$ProductGeneral->deleteCache( 'tags' );
						Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
						die();
					}else
					{
						$error['warning'] = $lang_module['errorsave'];
					 

					}
					
				}catch( PDOException $e )
				{
					$error['warning'] = $lang_module['errorsave'];
					//var_dump($error['warning']);
				}	

			}

		}
		if( empty( $error ) )
		{
			$ProductGeneral->deleteCache( 'tags' );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=tags' );
			die();
		}

	}
	if( ! empty( $data['image'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data['image'] ) )
	{
		$data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data['image'];
	}
	$xtpl = new XTemplate( 'tags_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
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
	$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );
	$xtpl->assign( 'UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_name );
 

	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'WARNING', $error['warning'] );
		$xtpl->parse( 'main.tags_error_warning' );
	}

	foreach( $data['tags_description'] as $language_id => $value )
	{
		$value['description'] = nv_htmlspecialchars( nv_br2nl( $value['description'] ) );
		$xtpl->assign( 'VALUE', $value );
		$xtpl->assign( 'LANG_ID', $language_id );
		$xtpl->assign( 'LANG_IMAGE', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $getLangModId[$language_id]['image'] );
 		$xtpl->assign( 'LANG_TITLE', $getLangModId[$language_id]['name']  );
 		
		
		if( isset( $error['alias'] ) )
		{
			if( isset( $error['alias'][$language_id] ) )
			{
				$xtpl->assign( 'tags_error_alias', $error['alias'][$language_id] );
				$xtpl->parse( 'main.looplang.tags_error_alias' );
			}
		}

		$xtpl->parse( 'main.looplang' );
		$xtpl->parse( 'main.looplang1' );
		$xtpl->parse( 'main.looplang2' );
 
	}
 
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';

	exit();
}

/*show list tags*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_tags cs 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_tags_description cn 
ON cs.tags_id = cn.tags_id
WHERE cn.language_id = ' . $ProductGeneral->current_language_id;

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'cn.alias, cn.keywords'  );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY cn.alias";
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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tags&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( 'cs.*, cn.alias, cn.keywords, cn.numpro ' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$array = array();
while( $rows = $result->fetch() )
{
	$array[] = $rows;
}

$xtpl = new XTemplate( 'tags.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
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
$xtpl->assign( 'URL_ALIAS', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=cn.alias&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_KEYWORDS', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=cn.keywords&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
 
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=tags&action=add" );

if( ! empty( $array ) )
{
	$incomplete = false;
	
	$number = 1;
	foreach( $array as $item )
	{
		$item['number'] = $number;
		
		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['tags_id'] );
		
		$item['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['tag'] . '/' . $item['alias'];
		
		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=tags&action=edit&token=" . $item['token'] . "&tags_id=" . $item['tags_id'];

		$xtpl->assign( 'LOOP', $item );
		
		if( empty( $row['description'] ) and $incomplete === false )
		{
			$xtpl->parse( 'main.loop.incomplete' );
		}
 
		$xtpl->parse( 'main.loop' );
		++$number;
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
