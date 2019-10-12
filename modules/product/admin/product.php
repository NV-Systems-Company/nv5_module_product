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

$getStockStatus = getStockStatus();
$getCustomerGroup = getCustomerGroup();
$getBrand = getBrand();

if( ACTION_METHOD == 'getAttribute' )
{
	$json = array();

	$getAttributeGroup = getAttributeGroup();

	$name = $nv_Request->get_string( 'filter_name', 'get', '' );

	$and = '';
	if( ! empty( $name ) )
	{
		$and .= ' AND ad.name LIKE :name';
	}

	$sql = 'SELECT a.attribute_id, a.attribute_group_id, ad.name FROM ' . TABLE_PRODUCT_NAME . '_attribute a
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_attribute_description ad
	ON ( a.attribute_id = ad.attribute_id )
	WHERE ad.language_id = ' . $ProductGeneral->current_language_id . $and . '
	ORDER BY ad.name DESC LIMIT 0, 10';

	$sth = $db->prepare( $sql );

	if( ! empty( $name ) )
	{
		$sth->bindValue( ':name', '%' . $name . '%' );
	}
	$sth->execute();
	while( list( $attribute_id, $attribute_group_id, $name ) = $sth->fetch( 3 ) )
	{
		$json[] = array(
			'attribute_id' => $attribute_id,
			'attribute_group' => $getAttributeGroup[$attribute_group_id]['name'],
			'name' => $name );
	}
	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'getBlock' )
{
	$name = $nv_Request->get_string( 'block_name', 'get', '' );
	$json = array();

	$and = '';

	if( ! empty( $name ) )
	{
		$and .= ' AND bcd.name LIKE :name';
	}

	$sql = 'SELECT bc.block_id, bcd.name FROM ' . TABLE_PRODUCT_NAME . '_block_cat bc
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_block_cat_description bcd
	ON ( bc.block_id = bcd.block_id )
	WHERE bcd.language_id = ' . $ProductGeneral->current_language_id . $and . '
	ORDER BY bcd.name DESC LIMIT 0, 20';

	$sth = $db->prepare( $sql );

	if( ! empty( $name ) )
	{
		$sth->bindValue( ':name', '%' . $name . '%' );
	}
	$sth->execute();

	while( $block = $sth->fetch() )
	{
		$json[] = array( 'block_id' => $block['block_id'], 'name' => strip_tags( html_entity_decode( $block['name'], ENT_QUOTES, 'UTF-8' ) ) );
	}
	$sth->closeCursor();

	$sort_order = array();

	foreach( $json as $key => $value )
	{
		$sort_order[$key] = $value['name'];
	}

	array_multisort( $sort_order, SORT_ASC, $json );
	$json = array_unique( $json, SORT_REGULAR );

	nv_jsonOutput( $json );

}
elseif( ACTION_METHOD == 'getFilter' )
{
	$name = $nv_Request->get_string( 'filter_name', 'get', '' );
	$json = array();

	$and = '';
	if( ! empty( $name ) )
	{
		$and .= ' AND fd.name LIKE :name';
	}

	$sql = 'SELECT *, (SELECT name FROM ' . TABLE_PRODUCT_NAME . '_filter_group_description fgd 
	WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' )  group_name 
	FROM ' . TABLE_PRODUCT_NAME . '_filter f 
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_filter_description fd ON (f.filter_id = fd.filter_id) 
	WHERE fd.language_id = ' . intval( $ProductGeneral->current_language_id ) . $and . ' ORDER BY f.sort_order ASC LIMIT 0, 20';

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
	$json = array_unique( $json, SORT_REGULAR );

	nv_jsonOutput( $json );

}
elseif( ACTION_METHOD == 'getTags' )
{
	$name = $nv_Request->get_string( 'term', 'get, post', '' );
	$json = array();

	$and = '';
	if( ! empty( $name ) )
	{
		$and .= ' AND (keywords LIKE :keywords OR alias LIKE :alias )';
	}

	$sql = 'SELECT keywords FROM ' . TABLE_PRODUCT_NAME . '_tags_description  
	WHERE language_id = ' . intval( $ProductGeneral->current_language_id ) . $and . ' ORDER BY alias ASC LIMIT 0, 20';

	$sth = $db->prepare( $sql );

	if( ! empty( $name ) )
	{
		$sth->bindValue( ':keywords', '%' . $name . '%' );
		$sth->bindValue( ':alias', '%' . $name . '%' );
	}
	$sth->execute();

	$json = array();
	while( list( $keywords ) = $sth->fetch( 3 ) )
	{
		$keywords = explode( ',', $keywords );
		foreach( $keywords as $_keyword )
		{
			$json[] = str_replace( '-', ' ', $_keyword );
		}
	}

	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'getCategory' )
{
	$json = array();

	$name = trim( $nv_Request->get_string( 'filter_name', 'get', '' ) );

	function convertLikeToRegex( $command )
	{
		return "/^" . str_replace( '%', '(.*?)', $command ) . "$/si";
	}

	$categoryArray = array();
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

	$json = array_unique( $json, SORT_REGULAR );

	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'getRelated' )
{
	$name = $nv_Request->get_string( 'filter_name', 'get', '' );
	$json = array();

	$and = '';
	if( ! empty( $name ) )
	{
		$and .= ' AND name LIKE :name';
	}

	$sql = 'SELECT product_id, name FROM ' . TABLE_PRODUCT_NAME . '_product_description  
	WHERE language_id = ' . $ProductGeneral->current_language_id . $and . '
	ORDER BY name DESC LIMIT 0, 5';

	$sth = $db->prepare( $sql );

	if( ! empty( $name ) )
	{
		$sth->bindValue( ':name', '%' . $name . '%' );
	}
	$sth->execute();
	while( list( $product_id, $name ) = $sth->fetch( 3 ) )
	{
		$json[] = array( 'product_id' => $product_id, 'name' => nv_htmlspecialchars( $name ) );
	}
	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'getOption' )
{
	$json = array();

	$from = TABLE_PRODUCT_NAME . '_option cs 
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option_description cn 
	ON cs.option_id = cn.option_id
	WHERE cn.language_id = ' . $ProductGeneral->current_language_id;

	$filter_name = $nv_Request->get_string( 'filter_name', 'get', '' );
	if( ! empty( $filter_name ) )
	{
		$from .= " AND cn.name LIKE '%" . $db->dblikeescape( $filter_name ) . "%' ";
	}

	$db->sqlreset()->select( 'cs.option_id, cs.type, cn.name' )->from( $from )->order( ' cn.name ASC' )->limit( 10 )->offset( 0 );
	$result = $db->query( $db->sql() );

	$a = 0;
	while( list( $option_id, $type, $option_name ) = $result->fetch( 3 ) )
	{
		$category = '';
		foreach( $getOptionType as $key => $value )
		{
			foreach( $value as $_type )
			{
				if( $type == $_type )
				{
					$category = $key;
					break;
				}
			}
		}

		$option_value = array();

		$sql2 = 'SELECT a.option_value_id, a.image, b.name FROM ' . TABLE_PRODUCT_NAME . '_option_value a 
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option_value_description b ON a.option_value_id = b.option_value_id
		WHERE b.language_id = ' . $ProductGeneral->current_language_id . ' AND a.option_id=' . $option_id;

		$result2 = $db->query( $sql2 );

		while( list( $option_value_id, $image, $option_value_name ) = $result2->fetch( 3 ) )
		{

			if( ! empty( $image ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $image ) )
			{
				$image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $image;
			}

			$option_value[] = array(
				'option_value_id' => ( int )$option_value_id,
				'name' => ( string )$option_value_name,
				'image' => ( string )$image );
		}
		$result2->closeCursor();
		$json[$a] = array(
			'option_id' => $option_id,
			'category' => $category,
			'type' => $type,
			'name' => $option_name,
			'option_value' => $option_value );
		++$a;
	}
	$result->closeCursor();

	nv_jsonOutput( $json );

}

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if( empty( $productCategory ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=category' );
	die();
}

$getUnits = getUnits();

if( empty( $getUnits ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=units' );
	die();
}

$month_dir_module = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name, date( 'Y_m' ), true );
$error = array();

$category_id = $nv_Request->get_int( 'category_id', 'get', 0 );
$parent_id = $nv_Request->get_int( 'parent_id', 'get', 0 );

$stmt = $db->prepare( 'SELECT numsubcat FROM ' . TABLE_PRODUCT_NAME . '_category WHERE category_id= :parent_id' );
$stmt->bindParam( ':parent_id', $parent_id, PDO::PARAM_STR );
$stmt->execute();
$subcategory_id = $stmt->fetchColumn();
if( $subcategory_id > 0 )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$data = array(
	'product_id' => 0,
	'category_id' => $category_id,
	'category' => isset( $productCategory[$category_id] ) ? $productCategory[$category_id]['name'] : '',
	'user_id' => $admin_info['userid'],
	'date_added' => NV_CURRENTTIME,
	'date_modified' => NV_CURRENTTIME,
	'status' => 1,
	'sort_order' => 0,
	'shipping' => 1,
	'points' => '',
	'model' => '',
	'barcode' => '',
	'quantity' => 100,
	'price' => 0,
	'units_id' => 2,
	'tax_class_id' => 0,
	'stock_status_id' => 0,
	'subtract' => 1,
	'weight' => '',
	'weight_class_id' => $ProductGeneral->config['config_weight_class_id'],
	'length' => '',
	'length_class_id' => $ProductGeneral->config['config_length_class_id'],
	'width' => '',
	'height' => '',
	'minimum' => 1,
	'brand_id' => 0,
	'image' => '',
	'thumb' => '',
	'viewed' => 0,
	'showprice' => 1,
	'layout' => '',
	'com_id' => 0,
	'tag' => array(),
	'tag_old' => array(),
	'product_discount' => array(),
	'product_special' => array(),
	'product_image' => array(),
	'product_reward' => array(),
	'product_attribute' => array(),
	'product_video' => array(),
	'product_extension' => array(),
	'product_option' => array(),
	'product_related' => array(),
	'product_filter' => array(),
	'product_block' => array(),
	'lang' => NV_LANG_DATA,
	'quantity_prefix' => '+',

	);

foreach( $getLangModId as $language_id => $value )
{
	$data['product_description'][$language_id] = array(
		'name' => '',
		'alias' => '',
		'description' => '',
		'meta_title' => '',
		'meta_description' => '' );
}

$data['product_id'] = $nv_Request->get_int( 'product_id', 'get,post', 0 );

if( $data['product_id'] > 0 )
{
	$data_old = $data = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product WHERE product_id=' . intval( $data['product_id'] ) )->fetch();

	$data['price'] = $ProductCurrency->format( $data['price'], $nv_Request->get_string( $module_data . '_currency', 'session' ), true, false );

	$data['length'] = floatFormat( $data['length'], 2 );
	$data['width'] = floatFormat( $data['width'], 2 );
	$data['height'] = floatFormat( $data['height'], 2 );
	$data['weight'] = floatFormat( $data['weight'], 2 );

	$data['category_old_id'] = $data['category_id'];
	$data['quantity2'] = $data['quantity'];
	$data['quantity'] = 0;
	$data['tag_old'] = array();
	$result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_description WHERE product_id=' . intval( $data['product_id'] ) );
	$extension_array = array();
	while( $rows = $result->fetch() )
	{
		if( ! empty( $rows['info'] ) )
		{
			$extension_array[$rows['language_id']] = unserialize( $rows['info'] );

		}

		$_result = $db->query( '
		SELECT td.tags_id, td.keywords FROM ' . TABLE_PRODUCT_NAME . '_tags_description td 
		LEFT JOIN  ' . TABLE_PRODUCT_NAME . '_tags_product tp ON ( td.tags_id = tp.tags_id ) 
		WHERE tp.product_id = ' . intval( $data['product_id'] ) . ' AND td.language_id = ' . intval( $language_id ) . '
		ORDER BY td.alias ASC' );
		while( $row = $_result->fetch() )
		{
			$data['tag_old'][$language_id][$row['tags_id']] = $row['keywords'];
			$rows['tag'][] = $row['keywords'];
		}
		$_result->closeCursor();

		$data['product_description'][$rows['language_id']] = $rows;
	}
	$result->closeCursor();
	unset( $result );

	if( ! empty( $extension_array ) )
	{
		foreach( $extension_array as $language_id => $info )
		{
			foreach( $info as $key => $value )
			{
				$data['product_extension'][$key][$language_id]['info'] = $value;
			}
		}
	}

	$result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_discount WHERE product_id=' . $data['product_id'] );
	while( $row = $result->fetch() )
	{
		$data['product_discount'][] = array(
			'product_id' => $row['product_id'],
			'customer_group_id' => $row['customer_group_id'],
			'quantity' => $row['quantity'],
			'priority' => $row['priority'],
			'price' => $ProductCurrency->format( $row['price'], $nv_Request->get_string( $module_data . '_currency', 'session' ), false, false ),
			'date_start' => ! empty( $row['date_start'] ) ? date( 'd/m/Y', $row['date_start'] ) : '',
			'date_end' => ! empty( $row['date_end'] ) ? date( 'd/m/Y', $row['date_end'] ) : '',
			);
	}
	$result->closeCursor();
	unset( $result );

	$result = $db->query( "SELECT * FROM " . TABLE_PRODUCT_NAME . "_product_special WHERE product_id=" . $data['product_id'] );
	while( $row = $result->fetch() )
	{
		$data['product_special'][] = array(
			'product_id' => $row['product_id'],
			'customer_group_id' => $row['customer_group_id'],
			'priority' => $row['priority'],
			'price' => $ProductCurrency->format( $row['price'], $nv_Request->get_string( $module_data . '_currency', 'session' ), false, false ),
			'date_start' => ! empty( $row['date_start'] ) ? date( 'd/m/Y', $row['date_start'] ) : '',
			'date_end' => ! empty( $row['date_start'] ) ? date( 'd/m/Y', $row['date_end'] ) : '',
			);
	}
	$result->closeCursor();
	unset( $result );

	$result = $db->query( "SELECT t1.related_id, t2.name FROM " . TABLE_PRODUCT_NAME . "_product_related t1 
	LEFT JOIN " . TABLE_PRODUCT_NAME . "_product_description t2 ON ( t1.related_id = t2.product_id ) 
	WHERE t1.product_id=" . $data['product_id'] );

	while( $row = $result->fetch() )
	{
		$data['product_related'][] = array(
			'related_id' => $row['related_id'],
			'name' => $row['name'],
			);
	}
	$result->closeCursor();
	unset( $result );

	$result = $db->query( "SELECT b.block_id, bcd.name FROM " . TABLE_PRODUCT_NAME . "_block b 
	LEFT JOIN " . TABLE_PRODUCT_NAME . "_block_cat_description bcd ON ( b.block_id = bcd.block_id ) 
	WHERE b.product_id=" . $data['product_id'] );

	while( $row = $result->fetch() )
	{
		$data['product_block'][] = array(
			'block_id' => $row['block_id'],
			'name' => $row['name'],
		);
	}
	$result->closeCursor();
	unset( $result );

	// filter
	$product_filter_data = array();
	$result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_filter WHERE product_id = ' . intval( $data['product_id'] ) );

	while( $row = $result->fetch() )
	{
		$product_filter_data[] = $row['filter_id'];
	}
	$data['product_filter'] = array();
	$result->closeCursor();
	unset( $result );

	foreach( $product_filter_data as $filter_id )
	{
		$filter_info = $db->query( 'SELECT *, (SELECT name FROM ' . TABLE_PRODUCT_NAME . '_filter_group_description fgd 
			WHERE f.filter_group_id = fgd.filter_group_id 
			AND fgd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ') group_name 
			FROM ' . TABLE_PRODUCT_NAME . '_filter f 
			LEFT JOIN ' . TABLE_PRODUCT_NAME . '_filter_description fd ON (f.filter_id = fd.filter_id) 
			WHERE f.filter_id = ' . intval( $filter_id ) . ' AND fd.language_id = ' . intval( $ProductGeneral->current_language_id ) )->fetch();

		if( $filter_info )
		{
			$data['product_filter'][] = $filter_info['filter_id'];
		}
	}
	// end filter

	// video
	$result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_video WHERE product_id = ' . intval( $data['product_id'] ) );

	while( $row = $result->fetch() )
	{
		$query = $db->query( 'SELECT v.*, vd.* FROM ' . TABLE_PRODUCT_NAME . '_product_video v
		INNER JOIN ' . TABLE_PRODUCT_NAME . '_product_video_description vd ON ( v.video_id = vd.video_id )
		WHERE v.video_id = ' . intval( $row['video_id'] ) );

		$array_video = array();
		while( $video_info = $query->fetch() )
		{
			$array_video[$video_info['language_id']] = array( 'name' => $video_info['name'], 'description' => $video_info['description'] );
		}
		$data['product_video'][] = array(
			'thumb' => $row['thumb'],
			'url' => $row['url'],
			'product_video_description' => $array_video );

	}
	$result->closeCursor();
	unset( $result );
	// end video

	$result = $db->query( "SELECT * FROM " . TABLE_PRODUCT_NAME . "_product_image where product_id=" . $data['product_id'] );
	while( $row = $result->fetch() )
	{
		if( ! empty( $row['image'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['image'] ) )
		{
			$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['image'];
		}
		$data['product_image'][] = array(
			'product_id' => $row['product_id'],
			'image' => $row['image'],
			'sort_order' => $row['sort_order'] );
	}
	$result->closeCursor();
	unset( $result );

	$product_attributes = getProductAttributes( $data['product_id'] );
	$getAttribute = getAttribute();
	$data['product_attribute'] = array();
	foreach( $product_attributes as $product_attribute )
	{

		if( isset( $getAttribute[$product_attribute['attribute_id']] ) )
		{
			$data['product_attribute'][] = array(
				'attribute_id' => $product_attribute['attribute_id'],
				'name' => $getAttribute[$product_attribute['attribute_id']]['name'],
				'product_attribute_description' => $product_attribute['product_attribute_description'] );
		}
	}

	$result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_option po 
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option o ON (po.option_id = o.option_id) 
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option_description od ON (o.option_id = od.option_id) 
	WHERE po.product_id = ' . intval( $data['product_id'] ) . ' AND od.language_id = ' . intval( $ProductGeneral->current_language_id ) );
	while( $product_option = $result->fetch() )
	{
		$product_option_value_data = array();

		$result2 = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_option_value WHERE product_option_id = ' . intval( $product_option['product_option_id'] ) );

		while( $product_option_value = $result2->fetch() )
		{
			$product_option_value_data[] = array(
				'product_option_value_id' => $product_option_value['product_option_value_id'],
				'option_value_id' => $product_option_value['option_value_id'],
				'quantity' => $product_option_value['quantity'],
				'subtract' => $product_option_value['subtract'],
				'price' => $ProductCurrency->format( $product_option_value['price'], $nv_Request->get_string( $module_data . '_currency', 'session' ), false, false ),
				'price_prefix' => $product_option_value['price_prefix'],
				'points' => $product_option_value['points'],
				'points_prefix' => $product_option_value['points_prefix'],
				'weight' => floatFormat( $product_option_value['weight'], 2 ),
				'weight_prefix' => $product_option_value['weight_prefix'] );
		}

		$data['product_option'][] = array(
			'product_option_id' => $product_option['product_option_id'],
			'product_option_value' => $product_option_value_data,
			'option_id' => $product_option['option_id'],
			'name' => $product_option['name'],
			'type' => $product_option['type'],
			'value' => $product_option['value'],
			'required' => $product_option['required'] );

		$result2->closeCursor();
	}
	$result->closeCursor();
	unset( $result );

	$result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_reward WHERE product_id=' . intval( $data['product_id'] ) );
	while( $row = $result->fetch() )
	{
		$data['product_reward'][$row['customer_group_id']] = array(
			'product_id' => $row['product_id'],
			'customer_group_id' => $row['customer_group_id'],
			'points' => $row['points'] );
	}
	$result->closeCursor();
	unset( $result );

	$data['points'] = ! empty( $data['points'] ) ? $data['points'] : '';
	$data['quantity_prefix'] = '+';

	$caption = $lang_ext['text_edit'];

}
else
{
	$caption = $lang_ext['text_add'];
}

if( $nv_Request->get_int( 'save', 'post' ) == 1 )
{

	$data['product_description'] = $nv_Request->get_typed_array( 'product_description', 'post', '', array() );
	$data['product_discount'] = $nv_Request->get_typed_array( 'product_discount', 'post', '', array() );
	$data['product_special'] = $nv_Request->get_typed_array( 'product_special', 'post', '', array() );
	$data['product_image'] = $nv_Request->get_typed_array( 'product_image', 'post', '', array() );
	$data['product_attribute'] = $nv_Request->get_typed_array( 'product_attribute', 'post', '', array() );
	$data['product_option'] = $nv_Request->get_typed_array( 'product_option', 'post', '', array() );
	$data['product_related'] = $nv_Request->get_typed_array( 'product_related', 'post', 'int', array() );
	$data['product_block'] = $nv_Request->get_typed_array( 'product_block', 'post', 'int', array() );
	$data['product_filter'] = $nv_Request->get_typed_array( 'product_filter', 'post', 'int', array() );
	$data['product_reward'] = $nv_Request->get_typed_array( 'product_reward', 'post', '', array() );
	$data['product_video'] = $nv_Request->get_typed_array( 'product_video', 'post', '', array() );
	$data['product_extension'] = $nv_Request->get_typed_array( 'product_extension', 'post', '', array() );

	$data['category_id'] = $nv_Request->get_int( 'category_id', 'post', 0 );

	$data['showprice'] = $nv_Request->get_int( 'showprice', 'post', 0 );
	$data['showorder'] = $nv_Request->get_int( 'showorder', 'post', 0 );

	$data['status'] = $nv_Request->get_int( 'status', 'post', 0 );
	$data['sort_order'] = $nv_Request->get_int( 'sort_order', 'post', 0 );

	$data['model'] = nv_substr( $nv_Request->get_title( 'model', 'post', '', 1 ), 0, 255 );
	$data['barcode'] = nv_substr( $nv_Request->get_title( 'barcode', 'post', '', 1 ), 0, 255 );
	$data['minimum'] = $nv_Request->get_int( 'minimum', 'post', 0 );
	$data['minimum'] = ! empty( $data['minimum'] ) ? $data['minimum'] : 1;
	$data['quantity'] = $nv_Request->get_int( 'quantity', 'post', 0 );

	$data['price'] = $nv_Request->get_string( 'price', 'post', '' );
	$data['price'] = floatval( preg_replace( '/[^0-9\.]/', '', $data['price'] ) );

	$data['units_id'] = $nv_Request->get_int( 'units_id', 'post', 0 );
	$data['tax_class_id'] = $nv_Request->get_int( 'tax_class_id', 'post', 0 );
	$data['stock_status_id'] = $nv_Request->get_int( 'stock_status_id', 'post', 0 );
	$data['subtract'] = $nv_Request->get_int( 'subtract', 'post', 0 );
	$data['weight'] = $nv_Request->get_float( 'weight', 'post', '' );
	$data['weight_class_id'] = $nv_Request->get_int( 'weight_class_id', 'post', 0 );
	$data['length'] = $nv_Request->get_float( 'length', 'post', '' );
	$data['width'] = $nv_Request->get_float( 'width', 'post', '' );
	$data['height'] = $nv_Request->get_float( 'height', 'post', '' );
	$data['length_class_id'] = $nv_Request->get_int( 'length_class_id', 'post', 0 );
	$data['brand_id'] = $nv_Request->get_int( 'brand_id', 'post', 0 );
	$data['image'] = $nv_Request->get_title( 'image', 'post', '' );
	$data['layout'] = $nv_Request->get_title( 'layout', 'post', '', 1 );

	$data['shipping'] = $nv_Request->get_bool( 'shipping', 'post' );

	$data['points'] = $nv_Request->get_int( 'points', 'post', 0 );

	$data['quantity_prefix'] = $nv_Request->get_title( 'quantity_prefix', 'post', '', 1 );

	// Kiem tra ma san pham trung
	$error_model = false;
	if( ! empty( $data['model'] ) )
	{
		$stmt = $db->prepare( 'SELECT product_id FROM ' . TABLE_PRODUCT_NAME . '_product WHERE model= :model AND product_id!=' . $data['product_id'] );
		$stmt->bindParam( ':model', $data['model'], PDO::PARAM_STR );
		$stmt->execute();
		$id_err = $stmt->rowCount();

		$stmt = $db->prepare( 'SELECT product_id FROM ' . TABLE_PRODUCT_NAME . '_product WHERE model= :model' );
		$stmt->bindParam( ':model', $data['model'], PDO::PARAM_STR );
		$stmt->execute();
		if( $data['product_id'] == 0 and $stmt->rowCount() )
		{
			$error_model = true;
		}
		elseif( $id_err )
		{
			$error_model = true;
		}
	}

	$error_barcode = false;
	if( ! empty( $data['barcode'] ) )
	{
		$stmt = $db->prepare( 'SELECT product_id FROM ' . TABLE_PRODUCT_NAME . '_product WHERE barcode= :barcode AND product_id!=' . $data['product_id'] );
		$stmt->bindParam( ':barcode', $data['barcode'], PDO::PARAM_STR );
		$stmt->execute();
		$barcode_err = $stmt->rowCount();

		$stmt = $db->prepare( 'SELECT product_id FROM ' . TABLE_PRODUCT_NAME . '_product WHERE barcode= :barcode' );
		$stmt->bindParam( ':barcode', $data['barcode'], PDO::PARAM_STR );
		$stmt->execute();
		if( $data['product_id'] == 0 and $stmt->rowCount() )
		{
			$error_barcode = true;
		}
		elseif( $barcode_err )
		{
			$error_barcode = true;
		}
	}

	foreach( $data['product_description'] as $language_id => $value )
	{
		if( empty( $value['name'] ) )
		{
			$error['name'][$language_id] = $lang_ext['error_name'];
		}
	}

	if( $error_model )
	{
		$error['model'] = $lang_ext['error_model'];
	}
	if( $error_barcode )
	{
		$error['barcode'] = $lang_ext['error_barcode'];
	}
	if( empty( $data['category_id'] ) )
	{
		$error['category_id'] = $lang_ext['error_category'];
	}
	if( $data['units_id'] == 0 )
	{
		$error['unit'] = $lang_ext['error_unit'];
	}
	if( $data['price'] <= 0 and $data['showprice'] )
	{
		$error['price'] = $lang_ext['error_price'];
	}
	if( ! empty( $error ) && ! isset( $error['warning'] ) )
	{
		$error['warning'] = $lang_ext['error_warning'];
	}
	if( empty( $error ) )
	{

		// Xu ly anh minh hoa
		$data['thumb'] = 0;
		if( ! nv_is_url( $data['image'] ) and is_file( NV_DOCUMENT_ROOT . $data['image'] ) )
		{
			$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
			$data['image'] = substr( $data['image'], $lu );
			if( file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $data['image'] ) )
			{
				$data['thumb'] = 1;
			}
			else
			{
				$data['thumb'] = 2;
			}
		}
		elseif( nv_is_url( $data['image'] ) )
		{
			$data['thumb'] = 3;
		}
		else
		{
			$data['image'] = '';
		}

		$array_extension = array();
		if( ! empty( $data['product_extension'] ) )
		{
			foreach( $data['product_extension'] as $key => $product_extension )
			{
				foreach( $getLangModId as $language_id => $lang )
				{
					$info = isset( $product_extension[$language_id]['info'] ) ? $product_extension[$language_id]['info'] : '';
					$info = defined( 'NV_EDITOR' ) ? nv_nl2br( $info, '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $info ) ), '<br />' );
					$array_extension[$language_id][] = $info;
				}
			}

		}

		if( $data['product_id'] == 0 )
		{

			$sql = "INSERT INTO " . TABLE_PRODUCT_NAME . "_product ( category_id, user_id, date_added, 
			date_modified, status, sort_order, shipping, points, model, barcode, minimum, quantity, price, units_id, 
			tax_class_id, stock_status_id, subtract, weight, weight_class_id, length, width, height, 
			length_class_id, brand_id, image, thumb, viewed, showprice, layout 
			)
				 VALUES ( 
				 :category_id,
				 " . intval( $data['user_id'] ) . ",
				 " . intval( $data['date_added'] ) . ",
				 " . intval( $data['date_modified'] ) . ",
				 " . intval( $data['status'] ) . ",
				 " . intval( $data['sort_order'] ) . ",
				 " . intval( $data['shipping'] ) . ",
				 " . intval( $data['points'] ) . ",
				 :model,
 				 :barcode,
 				 " . intval( $data['minimum'] ) . ",
				 " . intval( $data['quantity'] ) . ",
				 :price,
				 " . intval( $data['units_id'] ) . ",
				 " . intval( $data['tax_class_id'] ) . ",
				 " . intval( $data['stock_status_id'] ) . ",
				 " . intval( $data['subtract'] ) . ",
				 " . intval( $data['weight'] ) . ",
				 " . intval( $data['weight_class_id'] ) . ",
				 " . intval( $data['length'] ) . ",
				 " . intval( $data['width'] ) . ",
				 " . intval( $data['height'] ) . ",
				 " . intval( $data['length_class_id'] ) . ",
				 " . intval( $data['brand_id'] ) . ",
				 :image,
				 :thumb,
				 " . intval( $data['viewed'] ) . ",
				 " . intval( $data['showprice'] ) . ",
				 :layout 
			)";
			$data_insert = array();
			$data_insert['category_id'] = $data['category_id'];
			$data_insert['model'] = $data['model'];
			$data_insert['barcode'] = $data['barcode'];
			$data_insert['price'] = $data['price'];
			$data_insert['image'] = $data['image'];
			$data_insert['thumb'] = $data['thumb'];
			$data_insert['layout'] = $data['layout'];

			$data['product_id'] = $db->insert_id( $sql, 'product_id', $data_insert );

			if( $data['product_id'] > 0 )
			{

				foreach( $data['product_description'] as $language_id => $value )
				{
					$extension = isset( $array_extension[$language_id] ) ? serialize( $array_extension[$language_id] ) : '';

					$value['description'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $value['description'], '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );

					$value['meta_description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['meta_description'] ) ), '<br />' );

					$value['alias'] = ( $value['alias'] == '' ) ? change_alias( $value['name'] ) : change_alias( $value['alias'] );

					$value['alias'] = strtolower( $value['alias'] );

					$value['name'] = isset( $value['name'] ) ? $value['name'] : '';

					$value['meta_title'] = isset( $value['meta_title'] ) ? $value['meta_title'] : '';

					$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_description SET 
						product_id = ' . intval( $data['product_id'] ) . ', 
						language_id = ' . intval( $language_id ) . ', 
						name = :name,
						alias = :alias,
						description = :description,
						meta_title = :meta_title,
						meta_description = :meta_description,
						info=:info' );

					$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
					$stmt->bindParam( ':alias', $value['alias'], PDO::PARAM_STR );
					$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
					$stmt->bindParam( ':meta_title', $value['meta_title'], PDO::PARAM_STR );
					$stmt->bindParam( ':meta_description', $value['meta_description'], PDO::PARAM_STR );
					$stmt->bindParam( ':info', $extension, PDO::PARAM_STR );
					$stmt->execute();
					$stmt->closeCursor();

					if( isset( $value['tag'] ) )
					{
						insertTags( $value['tag'], $data['tag_old'], $data['product_id'], $language_id );
					}

				}

				// begin insert product_filter
				if( isset( $data['product_filter'] ) )
				{
					foreach( $data['product_filter'] as $filter_id )
					{
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_filter SET product_id = ' . intval( $data['product_id'] ) . ', filter_id = ' . intval( $filter_id ) );
					}
				}
				// end insert product_filter

				// begin insert product_related
				if( isset( $data['product_related'] ) )
				{
					foreach( $data['product_related'] as $related_id )
					{
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_related WHERE product_id = ' . intval( $data['product_id'] ) . ' AND related_id = ' . intval( $related_id ) );
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_related WHERE product_id = ' . intval( $related_id ) . ' AND related_id = ' . intval( $data['product_id'] ) );
						
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_related SET product_id = ' . intval( $data['product_id'] ) . ', related_id = ' . intval( $related_id ) );
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_related SET product_id = ' . intval( $related_id ) . ', related_id = ' . intval( $data['product_id'] ) );
					}
				}
				// end insert product_related
				
				// begin insert product_block
				if( !empty( $data['product_block'] ) ) 
				{
					foreach( $data['product_block'] as $block_id )
					{	
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_block SET product_id = ' . intval( $data['product_id'] ) . ', block_id = ' . intval( $block_id ) );
					}
				}
				// end insert product_block

				// begin insert product_reward
				foreach( $getCustomerGroup as $_group_id => $_g )
				{
					$points = isset( $data['product_reward'][$_group_id] ) ? $data['product_reward'][$_group_id]['points'] : 0;
					$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_reward SET product_id = ' . intval( $data['product_id'] ) . ', customer_group_id = ' . intval( $_group_id ) . ', points = ' . intval( $points ) );
					$sth->execute();
					$sth->closeCursor();
					unset( $sth );
				}
				// end insert product_reward

				// begin insert product_image
				if( ! empty( $data['product_image'] ) )
				{
					foreach( $data['product_image'] as $key => $value )
					{
						if( ! nv_is_url( $value['image'] ) and is_file( NV_DOCUMENT_ROOT . $value['image'] ) )
						{
							$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
							$value['image'] = substr( $value['image'], $lu );

							$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_image SET 
							product_id = ' . $data['product_id'] . ', 
							image = :image,
							sort_order = ' . intval( $value['sort_order'] ) );

							$sth->bindParam( ':image', $value['image'], PDO::PARAM_STR );
							$sth->execute();
							$sth->closeCursor();
							unset( $sth );
						}

					}
				}
				// end insert product_image

				// begin insert product_video
				if( ! empty( $data['product_video'] ) )
				{
					foreach( $data['product_video'] as $product_video )
					{
						$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_video SET 
							product_id = ' . intval( $data['product_id'] ) . ', 
							thumb = :thumb,
							url = :url' );

						$sth->bindParam( ':thumb', $product_video['thumb'], PDO::PARAM_STR );
						$sth->bindParam( ':url', $product_video['url'], PDO::PARAM_STR );
						$sth->execute();
						$video_id = $db->lastInsertId();
						$sth->closeCursor();
						unset( $sth );
						if( $video_id )
						{
							foreach( $product_video['product_video_description'] as $language_id => $product_video_description )
							{
								$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_video_description SET 
									video_id = ' . intval( $video_id ) . ', 
									language_id = ' . intval( $language_id ) . ', 
									name = :name,
									description = :description' );

								$sth->bindParam( ':name', $product_video_description['name'], PDO::PARAM_STR );
								$sth->bindParam( ':description', $product_video_description['description'], PDO::PARAM_STR );
								$sth->execute();
								$sth->closeCursor();
								unset( $sth );
							}

						}

					}
				}
				// end insert product_video
				// begin insert product_attribute
				if( ! empty( $data['product_attribute'] ) )
				{
					foreach( $data['product_attribute'] as $product_attribute )
					{
						if( $product_attribute['attribute_id'] )
						{

							$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_attribute WHERE product_id = ' . intval( $data['product_id'] ) . ' AND attribute_id = ' . intval( $product_attribute['attribute_id'] ) );

							foreach( $product_attribute['product_attribute_description'] as $language_id => $product_attribute_description )
							{
								$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_attribute SET 
									product_id = ' . intval( $data['product_id'] ) . ', 
									attribute_id = ' . intval( $product_attribute['attribute_id'] ) . ', 
									language_id = ' . intval( $language_id ) . ', 
									text = :text' );

								$sth->bindParam( ':text', $product_attribute_description['text'], PDO::PARAM_STR );
								$sth->execute();
								$sth->closeCursor();
								unset( $sth );
							}

						}

					}
				}
				// end insert product_attribute

				// begin insert product_option
				if( ! empty( $data['product_option'] ) )
				{
					foreach( $data['product_option'] as $product_option )
					{
						if( $product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' )
						{
							$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_option SET 
								product_id = ' . intval( $data['product_id'] ) . ', 
								option_id = ' . intval( $product_option['option_id'] ) . ', 
								required = ' . intval( $product_option['required'] ) );
							$sth->execute();

							$product_option_id = $db->lastInsertId();
							$sth->closeCursor();
							unset( $sth );
							if( ! empty( $product_option['product_option_value'] ) )
							{
								foreach( $product_option['product_option_value'] as $product_option_value )
								{
									$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_option_value SET 
										product_option_id = ' . intval( $product_option_id ) . ', 
										product_id = ' . intval( $data['product_id'] ) . ', 
										option_id = ' . intval( $product_option['option_id'] ) . ',
										option_value_id = ' . intval( $product_option_value['option_value_id'] ) . ',
										quantity = ' . intval( $product_option_value['quantity'] ) . ',
										subtract = ' . intval( $product_option_value['subtract'] ) . ',
										price = ' . floatval( $product_option_value['price'] ) . ',
										price_prefix = :price_prefix,
										points = ' . intval( $product_option_value['points'] ) . ',
										points_prefix = :points_prefix,
										weight = ' . floatval( $product_option_value['weight'] ) . ',
										weight_prefix = :weight_prefix ' );
									$sth->bindParam( ':price_prefix', $product_option_value['price_prefix'], PDO::PARAM_STR );
									$sth->bindParam( ':points_prefix', $product_option_value['points_prefix'], PDO::PARAM_STR );
									$sth->bindParam( ':weight_prefix', $product_option_value['weight_prefix'], PDO::PARAM_STR );
									$sth->execute();
									$sth->closeCursor();
									unset( $sth );
								}
							}
						}
					}
				}
				// end insert product_option

				// begin insert product_discount
				if( ! empty( $data['product_discount'] ) )
				{
					foreach( $data['product_discount'] as $key => $_value )
					{
						if( ! empty( $_value['date_start'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'] ) ) $_value['date_start'] = '';
						if( ! empty( $_value['date_end'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'] ) ) $_value['date_start'] = '';

						if( ! empty( $_value['date_start'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'], $m );
							$_value['date_start'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_start'] = 0;
						}
						unset( $m );
						if( ! empty( $_value['date_end'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'], $m );
							$_value['date_end'] = mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_end'] = 0;
						}

						$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_discount SET 
							product_id = ' . intval( $data['product_id'] ) . ', 
							customer_group_id = ' . intval( $_value['customer_group_id'] ) . ', 
							quantity = ' . intval( $_value['quantity'] ) . ',
							priority = ' . intval( $_value['priority'] ) . ',
							price = ' . floatval( $_value['price'] ) . ',
							date_start = ' . intval( $_value['date_start'] ) . ',
							date_end = ' . intval( $_value['date_end'] ) );
						$sth->execute();
						$sth->closeCursor();
						unset( $sth );
					}
				}
				// end insert product_discount

				// begin insert product_special
				if( ! empty( $data['product_special'] ) )
				{
					foreach( $data['product_special'] as $key => $_value )
					{
						if( ! empty( $_value['date_start'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'] ) ) $_value['date_start'] = '';
						if( ! empty( $_value['date_end'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'] ) ) $_value['date_start'] = '';

						if( ! empty( $_value['date_start'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'], $m );
							$_value['date_start'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_start'] = 0;
						}
						unset( $m );
						if( ! empty( $_value['date_end'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'], $m );
							$_value['date_end'] = mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_end'] = 0;
						}

						$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_special SET 
							product_id = ' . intval( $data['product_id'] ) . ', 
							customer_group_id = ' . intval( $_value['customer_group_id'] ) . ', 
							priority = ' . intval( $_value['priority'] ) . ',
							price = ' . floatval( $_value['price'] ) . ',
							date_start = ' . intval( $_value['date_start'] ) . ',
							date_end = ' . intval( $_value['date_end'] ) );
						$sth->execute();
						$sth->closeCursor();
						unset( $sth );
					}
				}
				// end insert product_special

				$auto_model = '';
				if( ! empty( $ProductGeneral->config['config_format_code_id'] ) and empty( $data['model'] ) )
				{
					$i = 1;
					$auto_model = vsprintf( $ProductGeneral->config['config_format_code_id'], $data['product_id'] );

					$stmt = $db->prepare( 'SELECT product_id FROM ' . TABLE_PRODUCT_NAME . '_product WHERE model= :model' );
					$stmt->bindParam( ':model', $auto_model, PDO::PARAM_STR );
					$stmt->execute();
					while( $stmt->rowCount() )
					{
						$auto_model = vsprintf( $ProductGeneral->config['config_format_code_id'], ( $data['product_id'] + $i++ ) );
					}

					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product SET model= :model WHERE product_id=' . $data['product_id'] );
					$stmt->bindParam( ':model', $auto_model, PDO::PARAM_STR );
					$stmt->execute();
				}

				$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_to_store SET product_id = ' . intval( $data['product_id'] ) . ', store_id=' . intval( $ProductGeneral->store_id ) );

				updateProductCategory( $data['category_id'] );

				$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_insert_success'] );

				nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Product', 'ID: ' . $data['product_id'], $admin_info['userid'] );
			}
			else
			{
				$error['errorsave'] = $lang_module['errorsave'];
			}
		}
		else
		{

			$data['user_id'] = $data_old['user_id'];

			$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product SET
				category_id= :category_id,
				user_id=' . intval( $data['user_id'] ) . ',
				status=' . intval( $data['status'] ) . ',
				sort_order=' . intval( $data['sort_order'] ) . ',
				date_modified= ' . NV_CURRENTTIME . ' ,
				shipping=' . intval( $data['shipping'] ) . ',
				points=' . intval( $data['points'] ) . ',
				model = :model,
				barcode = :barcode,
				minimum = ' . intval( $data['minimum'] ) . ',
				quantity = quantity ' . $data['quantity_prefix'] . ' ' . intval( $data['quantity'] ) . ',
				price = :price,
				units_id = ' . intval( $data['units_id'] ) . ',
				tax_class_id = ' . intval( $data['tax_class_id'] ) . ',
				stock_status_id = ' . intval( $data['stock_status_id'] ) . ',
				subtract = ' . intval( $data['subtract'] ) . ',
				weight = :weight,
				weight_class_id = ' . intval( $data['weight_class_id'] ) . ',
				length = :length,
				width = :width,
				height = :height,
				length_class_id =  ' . intval( $data['length_class_id'] ) . ',
				brand_id = ' . intval( $data['brand_id'] ) . ',
				image= :image,
				thumb= :thumb,
				layout= :layout,
				showprice = ' . intval( $data['showprice'] ) . '  
			WHERE product_id =' . $data['product_id'] );

			$stmt->bindParam( ':category_id', $data['category_id'], PDO::PARAM_STR );
			$stmt->bindParam( ':model', $data['model'], PDO::PARAM_STR );
			$stmt->bindParam( ':barcode', $data['barcode'], PDO::PARAM_STR );
			$stmt->bindParam( ':price', $data['price'], PDO::PARAM_STR );
			$stmt->bindParam( ':weight', $data['weight'], PDO::PARAM_STR );
			$stmt->bindParam( ':length', $data['length'], PDO::PARAM_STR );
			$stmt->bindParam( ':width', $data['width'], PDO::PARAM_STR );
			$stmt->bindParam( ':height', $data['height'], PDO::PARAM_STR );
			$stmt->bindParam( ':image', $data['image'], PDO::PARAM_STR );
			$stmt->bindParam( ':thumb', $data['thumb'], PDO::PARAM_STR );
			$stmt->bindParam( ':layout', $data['layout'], PDO::PARAM_STR );

			if( $stmt->execute() )
			{
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_description WHERE product_id = ' . intval( $data['product_id'] ) );

				foreach( $data['product_description'] as $language_id => $value )
				{
					$extension = isset( $array_extension[$language_id] ) ? serialize( $array_extension[$language_id] ) : '';

					$value['description'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $value['description'], '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );

					$value['meta_description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['meta_description'] ) ), '<br />' );

					$value['alias'] = ( $value['alias'] == '' ) ? change_alias( $value['name'] ) : change_alias( $value['alias'] );

					$value['alias'] = strtolower( $value['alias'] );

					$value['name'] = isset( $value['name'] ) ? $value['name'] : '';

					$value['meta_title'] = isset( $value['meta_title'] ) ? $value['meta_title'] : '';

					$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_description SET 
						product_id = ' . intval( $data['product_id'] ) . ', 
						language_id = ' . intval( $language_id ) . ', 
						name = :name,
						alias = :alias,
						description = :description,
						meta_title = :meta_title,
						meta_description = :meta_description,
						info=:info' );

					$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
					$stmt->bindParam( ':alias', $value['alias'], PDO::PARAM_STR );
					$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
					$stmt->bindParam( ':meta_title', $value['meta_title'], PDO::PARAM_STR );
					$stmt->bindParam( ':meta_description', $value['meta_description'], PDO::PARAM_STR );
					$stmt->bindParam( ':info', $extension, PDO::PARAM_STR );
					$stmt->execute();
					$stmt->closeCursor();

					if( isset( $value['tag'] ) )
					{
						insertTags( $value['tag'], $data['tag_old'], $data['product_id'], $language_id );
					}

				}

				// begin insert product_option
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_option WHERE product_id = ' . intval( $data['product_id'] ) );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_option_value WHERE product_id = ' . intval( $data['product_id'] ) );

				if( ! empty( $data['product_option'] ) )
				{

					foreach( $data['product_option'] as $product_option )
					{

						if( $product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' )
						{
							$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_option SET 
								product_id = ' . intval( $data['product_id'] ) . ', 
								option_id = ' . intval( $product_option['option_id'] ) . ', 
								required = ' . intval( $product_option['required'] ) );
							$sth->execute();

							$product_option_id = $db->lastInsertId();
							$sth->closeCursor();

							foreach( $product_option['product_option_value'] as $product_option_value )
							{
								$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_option_value SET 
									product_option_id = ' . intval( $product_option_id ) . ', 
									product_id = ' . intval( $data['product_id'] ) . ', 
									option_id = ' . intval( $product_option['option_id'] ) . ',
									option_value_id = ' . intval( $product_option_value['option_value_id'] ) . ',
									quantity = ' . intval( $product_option_value['quantity'] ) . ',
									subtract = ' . intval( $product_option_value['subtract'] ) . ',
									price = ' . floatval( $product_option_value['price'] ) . ',
									price_prefix = :price_prefix,
									points = ' . intval( $product_option_value['points'] ) . ',
									points_prefix = :points_prefix,
									weight = ' . floatval( $product_option_value['weight'] ) . ',
									weight_prefix = :weight_prefix ' );
								$sth->bindParam( ':price_prefix', $product_option_value['price_prefix'], PDO::PARAM_STR );
								$sth->bindParam( ':points_prefix', $product_option_value['points_prefix'], PDO::PARAM_STR );
								$sth->bindParam( ':weight_prefix', $product_option_value['weight_prefix'], PDO::PARAM_STR );
								$sth->execute();
								$sth->closeCursor();
							}

						}
					}
				}
				// end insert product_option
				// begin insert product_filter
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_filter WHERE product_id = ' . intval( $data['product_id'] ) );

				if( isset( $data['product_filter'] ) )
				{
					foreach( $data['product_filter'] as $filter_id )
					{
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_filter SET product_id = ' . intval( $data['product_id'] ) . ', filter_id = ' . intval( $filter_id ) );
					}
				}
				// end insert product_filter

				// begin insert product_video
				if( ! empty( $data['product_video'] ) )
				{

					$result = $db->query( 'SELECT video_id FROM ' . TABLE_PRODUCT_NAME . '_product_video WHERE product_id = ' . intval( $data['product_id'] ) );
					$video_array = array();
					while( list( $video_id ) = $result->fetch( 3 ) )
					{
						$video_array[] = $video_id;
					}
					$result->closeCursor();
					if( ! empty( $video_array ) )
					{
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_video WHERE video_id IN (' . implode( ',', $video_array ) . ') ' );
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_video_description WHERE video_id IN (' . implode( ',', $video_array ) . ') ' );
					}
					foreach( $data['product_video'] as $product_video )
					{

						$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_video SET 
							product_id = ' . intval( $data['product_id'] ) . ', 
							thumb = :thumb,
							url = :url' );

						$sth->bindParam( ':thumb', $product_video['thumb'], PDO::PARAM_STR );
						$sth->bindParam( ':url', $product_video['url'], PDO::PARAM_STR );
						$sth->execute();
						$video_id = $db->lastInsertId();
						$sth->closeCursor();

						if( $video_id )
						{
							foreach( $product_video['product_video_description'] as $language_id => $product_video_description )
							{
								$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_video_description SET 
									video_id = ' . $video_id . ', 
									language_id = ' . $language_id . ', 
									name = :name,
									description = :description' );

								$sth->bindParam( ':name', $product_video_description['name'], PDO::PARAM_STR );
								$sth->bindParam( ':description', $product_video_description['description'], PDO::PARAM_STR );
								$sth->execute();
								$sth->closeCursor();
							}

						}

					}
				}
				// end insert product_video

				// begin insert product_related
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_related WHERE product_id = ' . intval( $data['product_id'] ) );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_related WHERE related_id = ' . intval( $data['product_id'] ) );

				if( isset( $data['product_related'] ) )
				{
					foreach( $data['product_related'] as $related_id )
					{
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_related WHERE product_id = ' . intval( $data['product_id'] ) . ' AND related_id = ' . intval( $related_id ) );
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_related SET product_id = ' . intval( $data['product_id'] ) . ', related_id = ' . intval( $related_id ) );
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_related WHERE product_id = ' . intval( $data['product_id'] ) . ' AND related_id = ' . intval( $related_id ) );
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_related SET product_id = ' . intval( $data['product_id'] ) . ', related_id = ' . intval( $related_id ) );
					}
				}
				// end insert product_related
				
				
				// begin insert product_block
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_block WHERE product_id = ' . intval( $data['product_id'] ) );

				if( !empty( $data['product_block'] ) ) 
				{
					foreach( $data['product_block'] as $block_id )
					{	
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_block SET product_id = ' . intval( $data['product_id'] ) . ', block_id = ' . intval( $block_id ) );
					}
				}
				// end insert product_block
				
				
				// begin insert product_reward
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_reward WHERE product_id = ' . intval( $data['product_id'] ) );
				foreach( $getCustomerGroup as $_group_id => $_g )
				{
					$points = isset( $data['product_reward'][$_group_id] ) ? $data['product_reward'][$_group_id]['points'] : 0;
					$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_reward SET product_id = ' . intval( $data['product_id'] ) . ', customer_group_id = ' . intval( $_group_id ) . ', points = ' . intval( $points ) );
					$sth->execute();
					$sth->closeCursor();
				}
				// end insert product_reward

				// begin insert product_discount
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_discount WHERE product_id = ' . intval( $data['product_id'] ) );
				if( ! empty( $data['product_discount'] ) )
				{
					foreach( $data['product_discount'] as $key => $_value )
					{
						if( ! empty( $_value['date_start'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'] ) ) $_value['date_start'] = '';
						if( ! empty( $_value['date_end'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'] ) ) $_value['date_start'] = '';

						if( ! empty( $_value['date_start'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'], $m );
							$_value['date_start'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_start'] = 0;
						}
						unset( $m );
						if( ! empty( $_value['date_end'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'], $m );
							$_value['date_end'] = mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_end'] = 0;
						}

						$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_discount SET 
							product_id = ' . intval( $data['product_id'] ) . ', 
							customer_group_id = ' . intval( $_value['customer_group_id'] ) . ', 
							quantity = ' . intval( $_value['quantity'] ) . ',
							priority = ' . intval( $_value['priority'] ) . ',
							price = ' . floatval( $_value['price'] ) . ',
							date_start = ' . intval( $_value['date_start'] ) . ',
							date_end = ' . intval( $_value['date_end'] ) );
						$sth->execute();
						$sth->closeCursor();
					}
				}
				// end insert product_discount

				// begin insert product_special
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_special WHERE product_id = ' . intval( $data['product_id'] ) );

				if( ! empty( $data['product_special'] ) )
				{
					foreach( $data['product_special'] as $key => $_value )
					{
						if( ! empty( $_value['date_start'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'] ) ) $_value['date_start'] = '';
						if( ! empty( $_value['date_end'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'] ) ) $_value['date_start'] = '';

						if( ! empty( $_value['date_start'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'], $m );
							$_value['date_start'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_start'] = 0;
						}
						unset( $m );
						if( ! empty( $_value['date_end'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'], $m );
							$_value['date_end'] = mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_end'] = 0;
						}

						$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_special SET 
							product_id = ' . intval( $data['product_id'] ) . ', 
							customer_group_id = ' . intval( $_value['customer_group_id'] ) . ', 
							priority = ' . intval( $_value['priority'] ) . ',
							price = ' . floatval( $_value['price'] ) . ',
							date_start = ' . intval( $_value['date_start'] ) . ',
							date_end = ' . intval( $_value['date_end'] ) );
						$sth->execute();
						$sth->closeCursor();
					}
				}
				// end insert product_special

				// begin insert product_image
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_image WHERE product_id = ' . intval( $data['product_id'] ) );
				if( ! empty( $data['product_image'] ) )
				{
					foreach( $data['product_image'] as $key => $value )
					{
						if( ! nv_is_url( $value['image'] ) and is_file( NV_DOCUMENT_ROOT . $value['image'] ) )
						{
							$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
							$value['image'] = substr( $value['image'], $lu );

							$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_image SET 
							product_id = ' . $data['product_id'] . ', 
							image = :image,
							sort_order = ' . intval( $value['sort_order'] ) );

							$sth->bindParam( ':image', $value['image'], PDO::PARAM_STR );
							$sth->execute();
							$sth->closeCursor();
						}

					}
				}
				// end insert product_image

				// begin insert product_attribute
				if( ! empty( $data['product_attribute'] ) )
				{
					foreach( $data['product_attribute'] as $product_attribute )
					{
						if( $product_attribute['attribute_id'] )
						{

							$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_attribute WHERE product_id = ' . intval( $data['product_id'] ) . ' AND attribute_id = ' . intval( $product_attribute['attribute_id'] ) );

							foreach( $product_attribute['product_attribute_description'] as $language_id => $product_attribute_description )
							{
								$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_attribute SET 
									product_id = ' . intval( $data['product_id'] ) . ', 
									attribute_id = ' . intval( $product_attribute['attribute_id'] ) . ', 
									language_id = ' . intval( $language_id ) . ', 
									text = :text' );

								$sth->bindParam( ':text', $product_attribute_description['text'], PDO::PARAM_STR );
								$sth->execute();
								$sth->closeCursor();
							}

						}

					}
				}
				// end insert product_attribute

				if( $data['category_old_id'] != $data['category_id'] )
				{
					updateProductCategory( $data['category_id'] );
					updateProductCategory( $data['category_old_id'] );
				}

				$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_update_success'] );

				nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Product', 'ID: ' . $data['product_id'], $admin_info['userid'] );
			}
			else
			{
				$error[] = $lang_module['errorsave'];
				$error_key[] = 'errorsave';
			}
		}

		if( empty( $error ) )
		{

			$ProductGeneral->deleteCache( 'product' );
			$ProductGeneral->deleteCache( 'product_category' );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items' );
			die();
		}

	}
}

if( ! empty( $data['image'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data['image'] ) )
{
	$data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data['image'];
	$data['thumb'] = str_replace( NV_BASE_SITEURL . NV_ASSETS_DIR . '/', NV_BASE_SITEURL, $data['image'] );
}
else
{

	$data['thumb'] = NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/' . $module_file . '/no_image.png';

}

$data['category'] = isset( $productCategory[$data['category_id']] ) ? $productCategory[$data['category_id']]['name'] : '';

$xtpl = new XTemplate( 'product_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'AddMenu', AddMenu() );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'THEME', $global_config['module_theme'] );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'CURRENT_PATH', NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y_m') );	
$xtpl->assign( 'PATH', NV_UPLOADS_DIR . '/' . $module_upload );	
$xtpl->assign( 'CAPTION', $caption );
$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items' );

foreach( $data['product_description'] as $language_id => $value )
{

	$value['meta_description'] = nv_htmlspecialchars( nv_br2nl( $value['meta_description'] ) );

	$value['description'] = htmlspecialchars( nv_editor_br2nl( $value['description'] ) );

	if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
	{
		$value['description'] = nv_aleditor( 'product_description[' . $language_id . '][description]', '100%', '300px', $value['description'] );
	}
	else
	{
		$value['description'] = '<textarea style="width: 100%" name="product_description[' . $language_id . '][description]" id="input-description' . $language_id . '" cols="20" rows="15">' . $value['description'] . '</textarea>';
	}

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

	if( isset( $error['meta_title'] ) )
	{
		if( isset( $error['meta_title'][$language_id] ) )
		{
			$xtpl->assign( 'error_meta_title', $error['meta_title'][$language_id] );
			$xtpl->parse( 'main.looplang.error_meta_title' );
		}
	}

	if( isset( $value['tag'] ) )
	{
		foreach( $value['tag'] as $tag )
		{

			$xtpl->assign( 'TAGS', $tag );
			$xtpl->parse( 'main.looplang.tags' );

		}
	}

	$xtpl->parse( 'main.looplang' );
	$xtpl->parse( 'main.looplangscript' );
}

foreach( $getStockStatus as $key => $value )
{

	$xtpl->assign( 'STOCK_STATUS', array(
		'key' => $key,
		'name' => $value['name'],
		'selected' => ( $key == $data['stock_status_id'] ) ? 'selected="selected"' : '' ) );
	$xtpl->parse( 'main.stock_status' );
}

foreach( $productArrayYesNo as $key => $name )
{
	$checked = ( $key == $data['shipping'] ) ? 'checked="checked"' : '';
	$xtpl->assign( 'SHIPPING', array(
		'checked' => $checked,
		'key' => $key,
		'name' => $name ) );
	$xtpl->parse( 'main.shipping' );
}

foreach( $productArrayStatus as $key => $name )
{
	$selected = ( $key == $data['status'] ) ? 'selected="selected"' : '';
	$xtpl->assign( 'STATUS', array(
		'selected' => $selected,
		'key' => $key,
		'name' => $name ) );
	$xtpl->parse( 'main.status' );
}

// layout
$selectthemes = ( ! empty( $site_mods[$module_name]['theme'] ) ) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$layout_array = nv_scandir( NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout'] );

foreach( $layout_array as $val )
{
	$val = preg_replace( $global_config['check_op_layout'], '\\1', $val );

	$xtpl->assign( 'LAYOUT', array( 'key' => $val, 'selected' => ( $data['layout'] == $val ) ? ' selected="selected"' : '' ) );
	$xtpl->parse( 'main.layout' );
}

foreach( $getBrand as $key => $name )
{
	$xtpl->assign( 'BRAND', array(
		'key' => $key,
		'name' => $name['name'],
		'selected' => ( $key == $data['brand_id'] ) ? 'selected="selected"' : '' ) );
	$xtpl->parse( 'main.brand' );
}

foreach( $getCustomerGroup as $customer_group_id => $group )
{
	$xtpl->assign( 'GROUP', $group );
	$xtpl->parse( 'main.customer_group' );
	$xtpl->parse( 'main.customer_group2' );
}

foreach( $productArrayYesNo as $key => $name )
{
	$xtpl->assign( 'SUBTRACT', array(
		'key' => $key,
		'name' => $name,
		'selected' => ( $data['subtract'] == $key ) ? 'selected="selected"' : '' ) );
	$xtpl->parse( 'main.subtract' );
}

$getLengthClass = getLengthClass();
foreach( $getLengthClass as $key => $name )
{
	$xtpl->assign( 'LENGTH', array(
		'key' => $key,
		'name' => nv_htmlspecialchars( $name['title'] ),
		'selected' => ( $key == $data['length_class_id'] ) ? 'selected="selected"' : '',
		) );
	$xtpl->parse( 'main.length_class' );
}

$getWeightClass = getWeightClass();
foreach( $getWeightClass as $key => $name )
{
	$xtpl->assign( 'WEIGHT', array(
		'key' => $key,
		'name' => nv_htmlspecialchars( $name['title'] ),
		'selected' => ( $key == $data['weight_class_id'] ) ? 'selected="selected"' : '',
		) );
	$xtpl->parse( 'main.weight_class' );
}

$getTaxClass = getTaxClass();
foreach( $getTaxClass as $key => $name )
{
	$xtpl->assign( 'TAX_CLASS', array(
		'key' => $key,
		'name' => nv_htmlspecialchars( $name['title'] ),
		'selected' => ( $key == $data['tax_class_id'] ) ? 'selected="selected"' : '',
		) );
	$xtpl->parse( 'main.tax_class' );
}

foreach( $getUnits as $key => $name )
{
	$xtpl->assign( 'UNITS', array(
		'key' => $key,
		'name' => nv_htmlspecialchars( $name['name'] ),
		'selected' => ( $key == $data['units_id'] ) ? 'selected="selected"' : '',
		) );
	$xtpl->parse( 'main.units' );
}

$showprice_checked = ( $data['showprice'] ) ? " checked=\"checked\"" : "";
$xtpl->assign( 'ck_showprice', $showprice_checked );

if( ! empty( $data['product_related'] ) )
{
	foreach( $data['product_related'] as $key => $related )
	{
		$xtpl->assign( 'RELATED', $related );
		$xtpl->parse( 'main.related' );
	}
}
if( ! empty( $data['product_filter'] ) )
{
	$getFilter = getFilter( $data['product_filter'] );
	foreach( $getFilter as $key => $filter )
	{
		$xtpl->assign( 'FILTER', $filter );
		$xtpl->parse( 'main.filter' );
	}
}
if( ! empty( $data['product_block'] ) )
{
	$getBlock = getBlock( $data['product_block'] );
	foreach( $getBlock as $key => $block )
	{
		$xtpl->assign( 'BLOCK', $block );
		$xtpl->parse( 'main.block' );
	}
}

//Reward Points
foreach( $getCustomerGroup as $_group_id => $group )
{
	$group['points'] = isset( $data['product_reward'][$_group_id] ) ? $data['product_reward'][$_group_id]['points'] : 0;
	$xtpl->assign( 'GROUPS', $group );
	$xtpl->parse( 'main.customer_groups' );
}

$discount_row = 0;
if( ! empty( $data['product_discount'] ) )
{
	foreach( $data['product_discount'] as $key => $_value )
	{
		foreach( $getCustomerGroup as $customer_group_id => $group )
		{
			$group['selected'] = ( $group['customer_group_id'] == $_value['customer_group_id'] ) ? 'selected="selected"' : '';
			$xtpl->assign( 'GROUPS', $group );
			$xtpl->parse( 'main.discount.discount_groups' );
		}
		$xtpl->assign( 'DISCOUNT', array( 'key' => $key, 'value' => $_value ) );
		$xtpl->parse( 'main.discount' );
		++$discount_row;
	}
}

$special_row = 0;
if( ! empty( $data['product_special'] ) )
{
	foreach( $data['product_special'] as $key => $_value )
	{
		foreach( $getCustomerGroup as $customer_group_id => $group )
		{
			$group['selected'] = ( $group['customer_group_id'] == $_value['customer_group_id'] ) ? 'selected="selected"' : '';
			$xtpl->assign( 'GROUPS', $group );
			$xtpl->parse( 'main.special.special_groups' );
		}
		$xtpl->assign( 'SPECIAL', array( 'key' => $key, 'value' => $_value ) );
		$xtpl->parse( 'main.special' );
		++$special_row;
	}
}

$image_row = 0;
if( ! empty( $data['product_image'] ) )
{
	foreach( $data['product_image'] as $key => $item )
	{

		if( ! empty( $item['image'] ) )
		{
			$item['thumb'] = str_replace( NV_BASE_SITEURL . NV_ASSETS_DIR . '/', NV_BASE_SITEURL, $item['image'] );

			$xtpl->assign( 'IMG', array( 'key' => $key, 'value' => $item ) );
			$xtpl->parse( 'main.product_image' );
			++$image_row;
		}

	}
}

$attribute_row = 0;
if( ! empty( $data['product_attribute'] ) )
{
	foreach( $data['product_attribute'] as $key => $product_attribute )
	{
		$product_attribute['key'] = $key;
		$xtpl->assign( 'ATB', $product_attribute );
		foreach( $getLangModId as $language_id => $lang )
		{
			$xtpl->assign( 'TEXT', isset( $product_attribute['product_attribute_description'][$language_id] ) ? $product_attribute['product_attribute_description'][$language_id]['text'] : '' );
			$xtpl->assign( 'LANG_ID', $language_id );
			$xtpl->assign( 'LANG_IMAGE', NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang['image'] );
			$xtpl->assign( 'LANG_TITLE', $lang['name'] );
			$xtpl->parse( 'main.product_attribute.languages' );
		}
		$xtpl->parse( 'main.product_attribute' );
		++$attribute_row;
	}
}

$video_row = 0;

if( ! empty( $data['product_video'] ) )
{
	foreach( $data['product_video'] as $key => $product_video )
	{
		$product_video['key'] = $key;

		preg_match( '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i', $product_video['url'], $match );

		$product_video['youtube_id'] = isset( $match[1] ) ? $match[1] : '';

		$xtpl->assign( 'VIDEO', $product_video );
		foreach( $getLangModId as $language_id => $lang )
		{
			$xtpl->assign( 'NAME', isset( $product_video['product_video_description'][$language_id] ) ? $product_video['product_video_description'][$language_id]['name'] : '' );
			$xtpl->assign( 'DESCRIPTION', isset( $product_video['product_video_description'][$language_id] ) ? $product_video['product_video_description'][$language_id]['description'] : '' );

			$xtpl->assign( 'LANG_ID', $language_id );
			$xtpl->assign( 'LANG_IMAGE', NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang['image'] );
			$xtpl->assign( 'LANG_TITLE', $lang['name'] );
			$xtpl->parse( 'main.product_video.languages' );
		}
		$xtpl->parse( 'main.product_video' );
		++$video_row;
	}
}

$extension_row = 0;

if( ! empty( $data['product_extension'] ) )
{
	foreach( $data['product_extension'] as $key => $product_extension )
	{
		$product_extension['key'] = $key;

		$xtpl->assign( 'EXTENSION', $product_extension );
		foreach( $getLangModId as $language_id => $lang )
		{
			$info = isset( $product_extension[$language_id] ) ? $product_extension[$language_id]['info'] : '';

			$info = htmlspecialchars( nv_editor_br2nl( $info ) );

			if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
			{
				$info = nv_aleditor( "product_extension[" . $key . "][" . $language_id . "][info]", '100%', '50px', $info );
			}
			else
			{
				$info = "<textarea style=\"width: 100%\" name=\"product_extension[" . $key . "][" . $language_id . "][info]\" placeholder=\"" . $lang_ext['entry_extension_info'] . "\" id=\"extension-info" . $language_id . "\" cols=\"15\" rows=\"2\">" . $info . "</textarea>";
			}
			$xtpl->assign( 'INFO', $info );

			$xtpl->assign( 'LANG_ID', $language_id );
			$xtpl->assign( 'LANG_IMAGE', NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang['image'] );
			$xtpl->assign( 'LANG_TITLE', $lang['name'] );
			$xtpl->parse( 'main.product_extension.languages' );
		}
		$xtpl->parse( 'main.product_extension' );
		++$extension_row;
	}
}

foreach( $getLangModId as $language_id => $lang )
{
	$xtpl->assign( 'LANG_ID', $language_id );
	$xtpl->assign( 'LANG_IMAGE', NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_file . '/flags/' . $lang['image'] );
	$xtpl->assign( 'LANG_TITLE', $lang['name'] );
	$xtpl->parse( 'main.languages_attribute' );
	$xtpl->parse( 'main.languages_video' );
	$xtpl->parse( 'main.languages_extension' );
	$xtpl->parse( 'main.languages_tag' );
}

$data['option_values'] = array();
if( ! empty( $data['product_option'] ) )
{
	foreach( $data['product_option'] as $product_option )
	{
		if( $product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' )
		{
			if( ! isset( $data['option_values'][$product_option['option_id']] ) )
			{
				$data['option_values'][$product_option['option_id']] = getOptionValues( $product_option['option_id'] );
			}
		}
	}
}

$option_row = 0;
$option_value_row = 0;
if( ! empty( $data['product_option'] ) )
{
	foreach( $data['product_option'] as $product_option )
	{
		$product_option['num'] = $option_row;
		$product_option['active'] = ( $option_row == 0 ) ? 'active' : '';

		$xtpl->assign( 'OPTION', $product_option );

		if( $product_option['type'] == 'text' )
		{
			$xtpl->parse( 'main.product_option2.text' );
		}
		if( $product_option['type'] == 'textarea' )
		{
			$xtpl->parse( 'main.product_option2.textarea' );
		}
		if( $product_option['type'] == 'file' )
		{
			$xtpl->parse( 'main.product_option2.file' );
		}
		if( $product_option['type'] == 'date' )
		{
			$xtpl->parse( 'main.product_option2.date' );
		}
		if( $product_option['type'] == 'time' )
		{
			$xtpl->parse( 'main.product_option2.time' );
		}
		if( $product_option['type'] == 'datetime' )
		{
			$xtpl->parse( 'main.product_option2.datetime' );
		}
		if( $product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' )
		{
			if( isset( $product_option['product_option_value'] ) )
			{
				foreach( $product_option['product_option_value'] as $product_option_value )
				{
					if( isset( $data['option_values'][$product_option['option_id']] ) )
					{
						foreach( $data['option_values'][$product_option['option_id']] as $option_value )
						{
							$option_value['selected'] = ( $option_value['option_value_id'] == $product_option_value['option_value_id'] ) ? 'selected="selected"' : '';
							$xtpl->assign( 'OPV1', $option_value );
							$xtpl->parse( 'main.product_option2.select.loop.option_values1' );
						}
					}

					foreach( $productArrayPrefix as $price_prefix )
					{
						$xtpl->assign( 'PPX', array( 'price_prefix' => $price_prefix, 'selected' => ( $price_prefix == $product_option_value['price_prefix'] ) ? 'selected="selected"' : '' ) );
						$xtpl->parse( 'main.product_option2.select.loop.price_prefix' );
					}

					foreach( $productArrayPrefix as $points_prefix )
					{
						$xtpl->assign( 'PPOX', array( 'points_prefix' => $points_prefix, 'selected' => ( $points_prefix == $product_option_value['points_prefix'] ) ? 'selected="selected"' : '' ) );
						$xtpl->parse( 'main.product_option2.select.loop.points_prefix' );
					}
					foreach( $productArrayPrefix as $weight_prefix )
					{
						$xtpl->assign( 'WPX', array( 'weight_prefix' => $weight_prefix, 'selected' => ( $weight_prefix == $product_option_value['weight_prefix'] ) ? 'selected="selected"' : '' ) );
						$xtpl->parse( 'main.product_option2.select.loop.weight_prefix' );
					}
					foreach( $productArrayYesNo as $key => $subtract )
					{
						$xtpl->assign( 'SUT', array(
							'subtract' => $subtract,
							'key' => $key,
							'selected' => ( $key == $product_option_value['subtract'] ) ? 'selected="selected"' : '' ) );
						$xtpl->parse( 'main.product_option2.select.loop.subtract' );
					}
					$product_option_value['option_value_row'] = $option_value_row;
					$xtpl->assign( 'OPTIONVALUE', $product_option_value );
					$xtpl->parse( 'main.product_option2.select.loop' );
					++$option_value_row;
				}
			}
			if( isset( $data['option_values'][$product_option['option_id']] ) )
			{
				foreach( $data['option_values'][$product_option['option_id']] as $option_value )
				{
					$xtpl->assign( 'OPV', $option_value );
					$xtpl->parse( 'main.product_option2.select.option_values' );
				}
			}
			$xtpl->parse( 'main.product_option2.select' );
		}
		foreach( $productArrayYesNo as $key => $required )
		{
			$xtpl->assign( 'RQ', array(
				'required' => $required,
				'key' => $key,
				'selected' => ( $key == $product_option['required'] ) ? 'selected="selected"' : '' ) );
			$xtpl->parse( 'main.product_option2.required' );
		}
		$xtpl->parse( 'main.product_option1' );
		$xtpl->parse( 'main.product_option2' );

		++$option_row;

	}
}
$xtpl->assign( 'discount_row', $discount_row );
$xtpl->assign( 'option_row', $option_row );
$xtpl->assign( 'option_value_row', $option_value_row );
$xtpl->assign( 'special_row', $special_row );
$xtpl->assign( 'attribute_row', $attribute_row );
$xtpl->assign( 'video_row', $video_row );
$xtpl->assign( 'extension_row', $extension_row );
$xtpl->assign( 'image_row', $image_row );

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

if( isset( $error['model'] ) )
{
	$xtpl->assign( 'error_model', $error['model'] );
	$xtpl->parse( 'main.error_model' );
}

if( isset( $error['price'] ) )
{
	$xtpl->assign( 'error_price', $error['price'] );
	$xtpl->parse( 'main.error_price' );
}

if( isset( $error['product_unit'] ) )
{
	$xtpl->assign( 'error_unit', $error['product_unit'] );
	$xtpl->parse( 'main.error_unit' );
}

if( isset( $error['category_id'] ) )
{
	$xtpl->assign( 'error_category', $error['category_id'] );
	$xtpl->parse( 'main.error_category' );
}

if( $data['product_id'] > 0 )
{
	foreach( $productArrayPrefix as $quantity_prefix )
	{
		$selected = ( $quantity_prefix == $data['quantity_prefix'] ) ? 'selected="selected"' : '';
		$xtpl->assign( 'QX', array( 'quantity_prefix' => $quantity_prefix, 'selected' => $selected ) );
		$xtpl->parse( 'main.edit.quantity_prefix' );
	}

	$xtpl->parse( 'main.edit' );
	$xtpl->parse( 'main.edit1' );
}
else
{
	$xtpl->parse( 'main.add' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

unset( $option_row, $option_value_row, $layout_array, $error, $selectthemes, $data );
unset( $xtpl, $dataContent, $lang_ext, $productCategory, $productArrayYesNo, $productArrayStatus, $productArrayGender, $productArrayPrefix, $getLangModId, $getLangModCode );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
