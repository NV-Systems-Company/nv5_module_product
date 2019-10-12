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

function categoryFixSort( $parent_id = 0, $order = 0, $lev = 0 )
{
	global $db_slave, $module_data;

	$sql = 'SELECT category_id, parent_id FROM ' . TABLE_PRODUCT_NAME . '_category WHERE parent_id=' . $parent_id . ' ORDER BY weight ASC';
	$result = $db_slave->query( $sql );
	$array_cat_order = array();
	while( $row = $result->fetch() )
	{
		$array_cat_order[] = $row['category_id'];
	}
	$result->closeCursor();
	$weight = 0;
	if( $parent_id > 0 )
	{
		++$lev;
	}
	else
	{
		$lev = 0;
	}
	foreach( $array_cat_order as $category_id_i )
	{
		++$order;
		++$weight;
		$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET weight=' . $weight . ', sort=' . $order . ', lev=' . $lev . ' WHERE category_id=' . intval( $category_id_i );
		$db_slave->query( $sql );
		$order = categoryFixSort( $category_id_i, $order, $lev );
	}
	$numsubcat = $weight;
	if( $parent_id > 0 )
	{
		$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET numsubcat=' . $numsubcat;
		if( $numsubcat == 0 )
		{
			$sql .= ",subcatid=''";
		}
		else
		{
			$sql .= ",subcatid='" . implode( ',', $array_cat_order ) . "'";
		}
		$sql .= ' WHERE category_id=' . intval( $parent_id );
		$db_slave->query( $sql );
	}
	return $order;
}
 
if( in_array( ACTION_METHOD, array(
	'weight',
	'inhome',
	'numlinks',
	'viewcat',
	'newday' ) ) )
{
	$category_id = $nv_Request->get_int( 'category_id', 'post', 0 );
	$mod = $nv_Request->get_string( 'action', 'get,post', '' );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	$content = 'NO_' . $category_id;

	list( $category_id, $parent_id, $numsubcat ) = $db->query( 'SELECT category_id, parent_id, numsubcat FROM ' . TABLE_PRODUCT_NAME . '_category WHERE category_id=' . $category_id )->fetch( 3 );
	if( $category_id > 0 )
	{
		if( $mod == 'weight' and $new_vid > 0 )
		{
			$sql = 'SELECT category_id FROM ' . TABLE_PRODUCT_NAME . '_category WHERE category_id!=' . $category_id . ' AND parent_id=' . $parent_id . ' ORDER BY weight ASC';
			$result = $db->query( $sql );

			$weight = 0;
			while( $row = $result->fetch() )
			{
				++$weight;
				if( $weight == $new_vid ) ++$weight;
				$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET weight=' . $weight . ' WHERE category_id=' . intval( $row['category_id'] );
				$db->query( $sql );
			}

			$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET weight=' . $new_vid . ' WHERE category_id=' . $category_id;
			$db->query( $sql );

			categoryFixSort();
			$content = 'OK_' . $parent_id;

		}
		elseif( $mod == 'inhome' and ( $new_vid == 0 or $new_vid == 1 ) )
		{
			$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET inhome=' . $new_vid . ' WHERE category_id=' . $category_id;
			$db->query( $sql );

			$content = 'OK_' . $parent_id;
		}
		elseif( $mod == 'numlinks' and $new_vid >= 0 and $new_vid <= 10 )
		{
			$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET numlinks=' . $new_vid . ' WHERE category_id=' . $category_id;
			$db->query( $sql );
			$content = 'OK_' . $parent_id;
		}
		elseif( $mod == 'viewcat' and $nv_Request->isset_request( 'new_vid', 'post' ) )
		{
			$viewcat = $nv_Request->get_title( 'new_vid', 'post' );

			$array_viewcat = ( $numsubcat > 0 ) ? $array_viewcat_full : $array_viewcat_nosub;
			if( ! array_key_exists( $viewcat, $array_viewcat ) )
			{
				$viewcat = 'viewcat_page_new';
			}

			$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET viewcat= :viewcat WHERE category_id=' . $category_id );
			$stmt->bindParam( ':viewcat', $viewcat, PDO::PARAM_STR );
			$stmt->execute();

			$content = 'OK_' . $parent_id;
		}
		elseif( $mod == 'newday' and $new_vid >= 0 )
		{
			$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET newday=' . $new_vid . ' WHERE category_id=' . $category_id;
			$db->query( $sql );
			$content = 'OK_' . $parent_id;
		}
		$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_update_success'] );

		$ProductGeneral->deleteCache( array('category', 'product_category') );
	}

	include NV_ROOTDIR . '/includes/header.php';
	echo $content;
	include NV_ROOTDIR . '/includes/footer.php';

}
elseif( $nv_Request->get_int( 'category', 'get', 0 ) == 1 )
{
	$json = array();

	$name = trim( $nv_Request->get_string( 'filter_name', 'get', '' ) );

	function convertLikeToRegex( $command )
	{
		return "/^" . str_replace( '%', '(.*?)', $command ) . "$/si";
	}

	$categoryArray = array();
	$categoryArray[] = array(
					'category_id' => 0,
					'name' => 'Là chuyên mục chính',
					'alias' => 'chuyen-muc-chinh');
	foreach( $productCategory as $_category_id => $cat )
	{
		getCatidInParent( $_category_id, $categoryListId );
		$tname = '';
		$talias = '';
		krsort( $categoryListId );
		$count = count( $categoryListId );
		$i = 1;
		foreach( $categoryListId as $key => $catid )
		{
			$tname .= $productCategory[$catid]['name'];
			$talias .= $productCategory[$catid]['alias'];
			if( $i < $count )
			{
				$tname .= '&nbsp;&nbsp;&gt;&nbsp;&nbsp;';
			}
			++$i;
		}
		unset( $categoryListId );

		if( $cat['lev'] == 0 )
		{
			if( ! empty( $name ) )
			{
				$categoryArray[] = array(
					'category_id' => $cat['category_id'],
					'name' => $cat['name'],
					'alias' => str_replace( '-', ' ', $cat['alias'] ) );
			}
			else
			{
				$json[] = array( 'category_id' => $cat['category_id'], 'name' => strip_tags( html_entity_decode( $cat['name'], ENT_QUOTES, 'UTF-8' ) ) );

			}

		}
		else
		{

			if( ! empty( $name ) )
			{
				$categoryArray[] = array(
					'category_id' => $cat['category_id'],
					'name' => $tname,
					'alias' => str_replace( '-', ' ', $talias ) );
			}
			else
			{
				$json[] = array( 'category_id' => $_category_id, 'name' => strip_tags( html_entity_decode( $tname, ENT_QUOTES, 'UTF-8' ) ) );

			}

		}

	}

	if( ! empty( $name ) )
	{
		$likeClauses = array(
			'%' . $name . '',
			'' . $name . '%',
			'%' . $name . '%' );

		foreach( $categoryArray as $cat )
		{
			foreach( $likeClauses as $search )
			{
				if( preg_match( convertLikeToRegex( $search ), $cat['name'] ) || preg_match( convertLikeToRegex( $search ), $cat['alias'] ) )
				{
					$json[$cat['category_id']] = array( 'category_id' => $cat['category_id'], 'name' => strip_tags( html_entity_decode( $cat['name'], ENT_QUOTES, 'UTF-8' ) ) );
				}
			}
		}
	}

	// $sort_order = array();

	// foreach( $json as $key => $value )
	// {
	// $sort_order[$key] = $value['name'];
	// }

	// array_multisort( $sort_order, SORT_ASC, $json );

	$json = array_unique( $json, SORT_REGULAR );

	nv_jsonOutput( $json );
}
elseif( $nv_Request->get_int( 'filter', 'get', 0 ) == 1 )
{
	$name = $nv_Request->get_string( 'filter_name', 'get', '' );
	$json = array();

	$and = '';
	if( ! empty( $name ) )
	{
		$and .= ' AND fd.name LIKE :name';
	}

	$sql = 'SELECT *, (SELECT name FROM ' . TABLE_PRODUCT_NAME . '_filter_group_description fgd 
	WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = ' . ( int )$ProductGeneral->current_language_id . ' )  group_name 
	FROM ' . TABLE_PRODUCT_NAME . '_filter f 
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_filter_description fd ON (f.filter_id = fd.filter_id) 
	WHERE fd.language_id = ' . ( int )$ProductGeneral->current_language_id . $and . ' ORDER BY f.sort_order ASC LIMIT 0, 20';

	$sth = $db->prepare( $sql );

	if( ! empty( $name ) )
	{
		$sth->bindValue( ':name', '%' . $name . '%' );
	}
	$sth->execute();

	while( $filter = $sth->fetch() )
	{
		$json[] = array( 'filter_id' => $filter['filter_id'], 'name' => strip_tags( html_entity_decode( $filter['group_name'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8' ) ) );
	}

	$sort_order = array();

	foreach( $json as $key => $value )
	{
		$sort_order[$key] = $value['name'];
	}

	array_multisort( $sort_order, SORT_ASC, $json );

	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$selectthemes = ( ! empty( $site_mods[$module_name]['theme'] ) ) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];

	$productArrayLayout = nv_scandir( NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout'] );

	$data = array(
		'category_id' => 0,
		'parent_id' => 0,
		'perent_name' => '',
		'image' => '',
		'weight' => '',
		'sort' => '',
		'lev' => '',
		'layout' => '',
		'viewcat' => 'viewcat_page_grid',
		'numsubcat' => '',
		'subcatid' => '',
		'inhome' => 1,
		'status' => 1,
		'numlinks' => '',
		'newday' => 7,
		'columns_in_menu' => 0,
		'columns_in_body' => 4,
		'userid' => $admin_info['userid'],
		'date_added' => NV_CURRENTTIME,
		'date_modified' => NV_CURRENTTIME,
		'stores' => array(),
		'category_store' => array( 0 ),
		'category_filter' => array(),
		);
	$error = array();
	$filters = array();

	$getStores = getStores();
	$getStores[0] = array(
		'store_id' => 0,
		'name' => $lang_module['default'],
		'url' => NV_SERVER_NAME );
	asort( $getStores );

	foreach( $getLangModId as $language_id => $value )
	{
		$data['category_description'][$language_id] = array(
			'name' => '',
			'alias' => '',
			'description' => '',
			'meta_title' => '',
			'meta_description' => '',
			'meta_keyword' => '' );
	}

	$data['category_id'] = $nv_Request->get_int( 'category_id', 'get,post', 0 );
	$data['parent_id'] = $nv_Request->get_int( 'parent_id', 'get,post', 0 );
	if( $data['category_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_category  
		WHERE category_id=' . $data['category_id'] )->fetch();

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_category_description WHERE category_id=' . $data['category_id'];
		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{
			$data['category_description'][$rows['language_id']] = $rows;
		}
		$result->closeCursor();

		$result = $db->query( 'SELECT filter_id FROM ' . TABLE_PRODUCT_NAME . '_category_filter WHERE category_id = ' . ( int )$data['category_id'] );
		while( $rows = $result->fetch() )
		{
			$filters[] = $rows['filter_id'];
		}
		$result->closeCursor();

		$result = $db->query( 'SELECT store_id FROM ' . TABLE_PRODUCT_NAME . '_category_to_store WHERE category_id = ' . ( int )$data['category_id'] );
		while( $rows = $result->fetch() )
		{
			$data['category_store'][] = $rows['store_id'];
		}
		$result->closeCursor();

		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['category_id'] = $nv_Request->get_int( 'category_id', 'post', 0 );
		$data['parentid_old'] = $nv_Request->get_int( 'parentid_old', 'post', 0 );
		$data['parent_id'] = $nv_Request->get_int( 'parent_id', 'post', 0 );
		$data['inhome'] = $nv_Request->get_int( 'inhome', 'post', 0 );
		$data['status'] = $nv_Request->get_int( 'status', 'post', 0 );
		$data['columns_in_menu'] = $nv_Request->get_int( 'columns_in_menu', 'post', 0 );
		$data['columns_in_body'] = $nv_Request->get_int( 'columns_in_body', 'post', 0 );
		$data['perent_name'] = nv_substr( $nv_Request->get_title( 'perent_name', 'post', '', '' ), 0, 255 );
		$data['layout'] = nv_substr( $nv_Request->get_title( 'layout', 'post', '', '' ), 0, 255 );

		$data['category_description'] = $nv_Request->get_typed_array( 'category_description', 'post', array() );
		$data['category_store'] = $nv_Request->get_typed_array( 'category_store', 'post', array() );

		$filters = $nv_Request->get_typed_array( 'category_filter', 'post', array() );

		foreach( $data['category_description'] as $language_id => $value )
		{
			if( empty( $value['name'] ) )
			{
				$error['name'][$language_id] = $lang_ext['error_name'];
			}
		}

		if( empty( $data['category_store'] ) )
		{
			$error['store'] = $lang_ext['error_store'];
		}
		if( ! empty( $error ) && ! isset( $error['warning'] ) )
		{
			$error['warning'] = $lang_ext['error_warning'];
		}

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

		if( empty( $error ) )
		{
			if( $data['category_id'] == 0 )
			{

				$weight = $db->query( 'SELECT max(weight) FROM ' . TABLE_PRODUCT_NAME . '_category WHERE parent_id= ' . intval( $data['parent_id'] ) )->fetchColumn();

				$weight = intval( $weight ) + 1;

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_category SET 
				parent_id = ' . intval( $data['parent_id'] ) . ', 
				inhome=' . intval( $data['inhome'] ) . ', 
				weight = ' . intval( $weight ) . ', 
				status=' . intval( $data['status'] ) . ', 
				newday= ' . intval( $data['newday'] ) . ', 
				columns_in_menu= ' . intval( $data['columns_in_menu'] ) . ', 
				columns_in_body= ' . intval( $data['columns_in_body'] ) . ', 
				date_added=' . intval( $data['date_added'] ) . ',  
				userid=' . intval( $data['userid'] ) . ',  
				numlinks=4, 
				numsubcat=0, 
				sort = 0,
				lev = 0,
				image =:image,
				layout = :layout,
				subcatid=:subcatid, 
				viewcat = :viewcat' );

				$stmt->bindParam( ':image', $data['image'], PDO::PARAM_STR );
				$stmt->bindParam( ':layout', $data['layout'], PDO::PARAM_STR );
				$stmt->bindParam( ':viewcat', $data['viewcat'], PDO::PARAM_STR );
				$stmt->bindParam( ':subcatid', $data['subcatid'], PDO::PARAM_STR );
				$stmt->execute();

				if( $data['category_id'] = $db->lastInsertId() )
				{

					foreach( $data['category_description'] as $language_id => $value )
					{
						$value['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
						$value['meta_description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['meta_description'] ) ), '<br />' );

						$value['alias'] = ( $value['alias'] == '' ) ? change_alias( $value['name'] ) : change_alias( $value['alias'] );
						$value['alias'] = strtolower( $value['alias'] );
						$stmt = $db->prepare( 'SELECT count(*) FROM ' . TABLE_PRODUCT_NAME . '_category_description WHERE category_id !=' . $data['category_id'] . ' AND alias= :alias' );
						$stmt->bindParam( ':alias', $value['alias'], PDO::PARAM_STR );
						$stmt->execute();
						$check_alias = $stmt->fetchColumn();

						if( $check_alias and $data['parent_id'] > 0 )
						{
							$parentid_alias = $db->query( 'SELECT alias FROM ' . TABLE_PRODUCT_NAME . '_category_description WHERE category_id=' . $data['parent_id'] )->fetchColumn();
							$value['alias'] = $parentid_alias . '-' . $value['alias'];
						}

						$value['name'] = isset( $value['name'] ) ? $value['name'] : '';
						$value['meta_title'] = isset( $value['meta_title'] ) ? $value['meta_title'] : '';

						$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_category_description SET 
							category_id = ' . intval( $data['category_id'] ) . ', 
							language_id = ' . intval( $language_id ) . ', 
							name = :name,
							alias = :alias,
							description = :description,
							meta_title = :meta_title,
							meta_description = :meta_description,
							meta_keyword=:meta_keyword' );

						$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
						$stmt->bindParam( ':alias', $value['alias'], PDO::PARAM_STR );
						$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
						$stmt->bindParam( ':meta_title', $value['meta_title'], PDO::PARAM_STR );
						$stmt->bindParam( ':meta_description', $value['meta_description'], PDO::PARAM_STR );
						$stmt->bindParam( ':meta_keyword', $value['meta_keyword'], PDO::PARAM_STR );
						$stmt->execute();
						$stmt->closeCursor();
					}

					if( ! empty( $filters ) )
					{
						foreach( $filters as $filter_id )
						{
							$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_category_filter SET category_id = ' . intval( $data['category_id'] ) . ', filter_id = ' . ( int )$filter_id );
						}
					}
					if( ! empty( $data['category_store'] ) )
					{
						foreach( $data['category_store'] as $store_id )
						{
							$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_category_to_store SET category_id = ' . ( int )$data['category_id'] . ', store_id = ' . ( int )$store_id );
						}
					}

					categoryFixSort();

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Category', 'category_id: ' . $data['category_id'], $admin_info['userid'] );

					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );
				}
				else
				{
					$error['warning'] = $lang_ext['error_save'];

				}
				$stmt->closeCursor();

			}
			else
			{
				try
				{
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET 
					parent_id = ' . intval( $data['parent_id'] ) . ', 
					inhome=' . intval( $data['inhome'] ) . ', 
					columns_in_menu=' . intval( $data['columns_in_menu'] ) . ', 
					columns_in_body=' . intval( $data['columns_in_body'] ) . ', 
					status=' . intval( $data['status'] ) . ', 
					date_modified=' . intval( NV_CURRENTTIME ) . ', 
					image =:image,
					layout = :layout
					WHERE category_id=' . $data['category_id'] );

					$stmt->bindParam( ':image', $data['image'], PDO::PARAM_STR );
					$stmt->bindParam( ':layout', $data['layout'], PDO::PARAM_STR );
					if( $stmt->execute() )
					{

						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Category', 'category_id: ' . $data['category_id'], $admin_info['userid'] );

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_description WHERE category_id = ' . ( int )$data['category_id'] );

						foreach( $data['category_description'] as $language_id => $value )
						{
							$value['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );
							$value['meta_description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['meta_description'] ) ), '<br />' );

							$value['alias'] = ( $value['alias'] == '' ) ? change_alias( $value['name'] ) : change_alias( $value['alias'] );
							$value['alias'] = strtolower( $value['alias'] );
							$stmt = $db->prepare( 'SELECT count(*) FROM ' . TABLE_PRODUCT_NAME . '_category_description WHERE category_id !=' . $data['category_id'] . ' AND alias= :alias' );
							$stmt->bindParam( ':alias', $value['alias'], PDO::PARAM_STR );
							$stmt->execute();
							$check_alias = $stmt->fetchColumn();

							if( $check_alias and $data['parent_id'] > 0 )
							{
								$parentid_alias = $db->query( 'SELECT alias FROM ' . TABLE_PRODUCT_NAME . '_category_description WHERE category_id=' . $data['parent_id'] )->fetchColumn();
								$value['alias'] = $parentid_alias . '-' . $value['alias'];
							}

							$value['name'] = isset( $value['name'] ) ? $value['name'] : '';
							$value['meta_title'] = isset( $value['meta_title'] ) ? $value['meta_title'] : '';

							$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_category_description SET 
								category_id = ' . intval( $data['category_id'] ) . ', 
								language_id = ' . intval( $language_id ) . ', 
								name = :name,
								alias = :alias,
								description = :description,
								meta_title = :meta_title,
								meta_description = :meta_description,
								meta_keyword=:meta_keyword' );

							$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
							$stmt->bindParam( ':alias', $value['alias'], PDO::PARAM_STR );
							$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
							$stmt->bindParam( ':meta_title', $value['meta_title'], PDO::PARAM_STR );
							$stmt->bindParam( ':meta_description', $value['meta_description'], PDO::PARAM_STR );
							$stmt->bindParam( ':meta_keyword', $value['meta_keyword'], PDO::PARAM_STR );
							$stmt->execute();
							$stmt->closeCursor();
						}

						if( $data['parent_id'] != $data['parentid_old'] )
						{
							$stmt = $db->prepare( 'SELECT max(weight) FROM ' . TABLE_PRODUCT_NAME . '_category WHERE parent_id= :parent_id ' );
							$stmt->bindParam( ':parent_id', $data['parent_id'], PDO::PARAM_INT );
							$stmt->execute();

							$weight = $stmt->fetchColumn();

							$weight = intval( $weight ) + 1;
							$sql = 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET weight=' . $weight . ' WHERE category_id=' . intval( $data['category_id'] );
							$db->query( $sql );

							categoryFixSort();
						}

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_filter WHERE category_id = ' . ( int )$data['category_id'] );
						if( ! empty( $filters ) )
						{
							foreach( $filters as $filter_id )
							{
								$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_category_filter SET category_id = ' . ( int )$data['category_id'] . ', filter_id = ' . ( int )$filter_id );
							}
						}

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_to_store WHERE category_id = ' . ( int )$data['category_id'] );
						if( ! empty( $data['category_store'] ) )
						{
							foreach( $data['category_store'] as $store_id )
							{
								$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_category_to_store SET category_id = ' . ( int )$data['category_id'] . ', store_id = ' . ( int )$store_id );
							}
						}
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
					//var_dump($e);
				}

			}

		}
		if( empty( $error ) )
		{
			$ProductGeneral->deleteCache( array('category', 'product_category') );

			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=category&parent_id=' . $data['parent_id'] );
			die();
		}

	}

	if( ! empty( $data['image'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data['image'] ) )
	{
		$data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data['image'];
	}

	if( $data['parent_id'] > 0 )
	{
		unset( $categoryListId );
		getCatidInParent( $data['parent_id'], $categoryListId );
		$perent_name = '';
		krsort( $categoryListId );
		$count = count( $categoryListId );
		$i = 1;
		foreach( $categoryListId as $key => $catid )
		{
			$perent_name .= $productCategory[$catid]['name'];
			if( $i < $count )
			{
				$perent_name .= '&nbsp;&nbsp;&gt;&nbsp;&nbsp;';
			}
			++$i;
		}
		unset( $categoryListId );

		$data['perent_name'] = $perent_name;

	}
	else
	{
		$data['perent_name'] = '';
	}

	$xtpl = new XTemplate( 'category_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
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
	$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name . '/category' );
	$xtpl->assign( 'UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_name );
	$xtpl->assign( 'JSON_CATEGORY', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&category=1' );
	$xtpl->assign( 'JSON_FILTER', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&filter=1' );
	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	$xtpl->assign( 'DISPLAYLANG', count( $getLangModId ) == 1 ? 'style="display:none"' : '' );

	foreach( $getLangModId as $lang_id_tab => $lang_tab )
	{
		$lang_tab['image'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang_tab['image'];
		$xtpl->assign( 'LANG_TITLE', $lang_tab );
		$xtpl->assign( 'LANG_KEY', $lang_id_tab );
		$xtpl->parse( 'main.looplangtab' );
	}

	foreach( $data['category_description'] as $language_id => $value )
	{
		$value['description'] = nv_htmlspecialchars( nv_br2nl( $value['description'] ) );
		$value['meta_description'] = nv_htmlspecialchars( nv_br2nl( $value['meta_description'] ) );

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

	if( ! empty( $filters ) )
	{
		foreach( $filters as $filter_id )
		{
			//$filter_info = $shops_filter->getFilter( $filter_id );

			if( $filter_info )
			{
				$xtpl->assign( 'FILTER', array( 'filter_id' => $filter_info['filter_id'], 'name' => $filter_info['groupname'] . ' &gt; ' . $filter_info['name'] ) );
				$xtpl->parse( 'main.filter' );
			}
			$filter_info = null;
		}
	}

	if( ! empty( $getStores ) )
	{
		foreach( $getStores as $key => $store )
		{
			$xtpl->assign( 'STORE', array(
				'key' => $key,
				'name' => $store['name'],
				'checked' => in_array( $key, $data['category_store'] ) ? 'checked="checked"' : '' ) );
			$xtpl->parse( 'main.store' );
		}
	}

	foreach( $productArrayLayout as $value )
	{
		$value = preg_replace( $global_config['check_op_layout'], '\\1', $value );
		$xtpl->assign( 'LAYOUT', array( 'key' => $value, 'selected' => ( $data['layout'] == $value ) ? ' selected="selected"' : '' ) );
		$xtpl->parse( 'main.layout' );
	}

	foreach( $productArrayYesNo as $key => $name )
	{
		$xtpl->assign( 'INHOME', array(
			'key' => $key,
			'name' => $name,
			'selected' => ( $key == $data['inhome'] ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.inhome' );
	}

	foreach( $productArrayStatus as $key => $name )
	{
		$xtpl->assign( 'STATUS', array(
			'key' => $key,
			'name' => $name,
			'selected' => ( $key == $data['status'] ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.status' );
	}

	if( isset( $error['store'] ) )
	{
		$xtpl->assign( 'error_store', $error['store'] );
		$xtpl->parse( 'main.error_store' );
	}
	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'error_warning', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}


/*show list category*/
$per_page = 50;

$page = $nv_Request->get_int( 'page', 'get', 1 );

$parent_id = $nv_Request->get_int( 'parent_id', 'get', 0 );

$sql = TABLE_PRODUCT_NAME . '_category c LEFT JOIN ' . TABLE_PRODUCT_NAME . '_category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = ' . intval( $parent_id ) . ' AND cd.language_id = ' . intval( $ProductGeneral->current_language_id );

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'cd.name', 'c.weight' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= ' ORDER BY ' . $sort;
}
else
{
	$sql .= ' ORDER BY weight';
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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=category&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( 'c.*, cd.name' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$xtpl = new XTemplate( 'category.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
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

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';

$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=name&amp;order=' . $order2 . '&amp;parent_id=' . $parent_id . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_WEIGHT', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=weight&amp;order=' . $order2 . '&amp;parent_id=' . $parent_id . '&amp;per_page=' . $per_page );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=category&action=add&amp;parent_id=' . $parent_id );

if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}

if( $parent_id > 0 )
{
	$parentid_i = $parent_id;
	$array_cat_title = array();
	$a = 0;

	while( $parentid_i > 0 )
	{
		list( $category_id_i, $parentid_i, $title_i ) = $db->query( 'SELECT c.category_id, c.parent_id, cd.name FROM ' . TABLE_PRODUCT_NAME . '_category c 
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_category_description cd ON (c.category_id=cd.category_id) 
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_category_to_store ctd ON (c.category_id = ctd.category_id)
		WHERE cd.language_id = ' . $ProductGeneral->current_language_id . ' AND ctd.store_id = ' . intval( $ProductGeneral->store_id ) . '
		AND c.category_id=' . intval( $parentid_i ) )->fetch( 3 );

		$array_cat_title[] = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=category&amp;parent_id=' . $category_id_i . '"><strong>' . $title_i . '</strong></a>';

		++$a;
	}

	for( $i = $a - 1; $i >= 0; $i-- )
	{
		$xtpl->assign( 'CAT_NAV', $array_cat_title[$i] . ( $i > 0 ? ' &raquo; ' : '' ) );
		$xtpl->parse( 'main.catnav.loop' );
	}

	$xtpl->parse( 'main.catnav' );
}

if( ! empty( $dataContent ) )
{
	foreach( $dataContent as $item )
	{
		$array_viewcat = ( $item['numsubcat'] > 0 ) ? $array_viewcat_full : $array_viewcat_nosub;
		if( ! array_key_exists( $item['viewcat'], $array_viewcat ) )
		{
			$viewcat = 'viewcat_page_grid';
			$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET viewcat= :viewcat WHERE category_id=' . $item['category_id'] );
			$stmt->bindParam( ':viewcat', $viewcat, PDO::PARAM_STR );
			$stmt->execute();
		}

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['category_id'] );

		$item['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=category&parent_id=' . $item['category_id'];
		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=category&action=edit&token=' . $item['token'] . '&category_id=' . $item['category_id'] . '&parent_id=' . $item['parent_id'];

		$item['numsubcat'] = $item['numsubcat'] > 0 ? ' <span style="color:#FF0101;">(' . $item['numsubcat'] . ')</span>' : '';

		$xtpl->assign( 'LOOP', $item );

		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array( 'w' => $i, 'selected' => ( $i == $item['weight'] ) ? 'selected="selected"' : '' ) );

			$xtpl->parse( 'main.loop.weight' );
		}
		foreach( $productArrayStatus as $key => $val )
		{
			$xtpl->assign( 'INHOME', array(
				'key' => $key,
				'title' => $val,
				'selected' => $key == $item['inhome'] ? ' selected="selected"' : '' ) );
			$xtpl->parse( 'main.loop.inhome' );
		}

		foreach( $array_viewcat as $key => $val )
		{
			$xtpl->assign( 'VIEWCAT', array(
				'key' => $key,
				'title' => $val,
				'selected' => $key == $item['viewcat'] ? ' selected="selected"' : '' ) );
			$xtpl->parse( 'main.loop.viewcat' );
		}

		for( $i = 0; $i <= 10; $i++ )
		{
			$xtpl->assign( 'NUMLINKS', array(
				'key' => $i,
				'title' => $i,
				'selected' => $i == $item['numlinks'] ? ' selected="selected"' : '' ) );
			$xtpl->parse( 'main.loop.numlinks' );
		}

		for( $i = 0; $i <= 30; $i++ )
		{
			$xtpl->assign( 'NEWDAY', array(
				'key' => $i,
				'title' => $i,
				'selected' => $i == $item['newday'] ? ' selected="selected"' : '' ) );
			$xtpl->parse( 'main.loop.newday' );
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
