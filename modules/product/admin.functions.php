<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Nuke.vn. All rights reserved
 * @website https://nuke.vn
 * @License GNU/GPL version 3 or any later version
 * @Createdate Wed, 24 Aug 2016 02:00:00 GMT
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

define( 'NV_IS_FILE_ADMIN', true );

define( 'TABLE_PRODUCT_NAME', $db_config['prefix'] . '_' . $module_data );

define( 'ACTION_METHOD', $nv_Request->get_string( 'action', 'get,post', '' ) );

$array_viewcat_full = array(
	'view_home_category' => $lang_module['view_home_category'],
	'viewcat_page_list' => $lang_module['viewcat_page_list'],
	'viewcat_page_grid' => $lang_module['viewcat_page_grid'] );

$array_viewcat_nosub = array( 'viewcat_page_list' => $lang_module['viewcat_page_list'], 'viewcat_page_grid' => $lang_module['viewcat_page_grid'] );

$getOptionType = array(
	'Choose' => array(
		'select',
		'radio',
		'checkbox',
		//'image'
		),
	// 'Input' => array( 'text', 'textarea' ),
	// 'File' => array( 'file' ),
	// 'Date' => array(
		// 'date',
		// 'time',
		// 'datetime' )
);

global $productRegistry, $ProductGeneral, $ProductCurrency;

$productRegistry = array(
	'mod_data' => $module_data,
	'mod_name' => $module_name,
	'mod_file' => $module_file,
	'mod_lang' => $lang_module,
	'lang_data' => NV_LANG_DATA,
	);

$ProductGeneral = new NukeViet\Product\General( $productRegistry );
$ProductCurrency = new NukeViet\Product\Currency( $productRegistry );
$ProductTax = new NukeViet\Product\Tax( $productRegistry );
 
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global/global.functions.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/admin.menu_sub.php';
 
if( isset( $_SESSION[$module_data . '_shipping_address'] ) )
{
	$ProductTax->setShippingAddress( $_SESSION[$module_data . '_shipping_address']['country_id'], $_SESSION[$module_data . '_shipping_address']['zone_id'] );
}
else
{

	$ProductTax->setShippingAddress( $ProductGeneral->config['config_country_id'], $ProductGeneral->config['config_zone_id'] );
}

if( isset( $_SESSION[$module_data . '_payment_address'] ) )
{
	$ProductTax->setPaymentAddress( $_SESSION[$module_data . '_payment_address']['country_id'], $_SESSION[$module_data . '_payment_address']['zone_id'] );
}
elseif( $ProductGeneral->config['config_tax_default'] == 'payment' )
{
	$ProductTax->setPaymentAddress( $ProductGeneral->config['config_country_id'], $ProductGeneral->config['config_zone_id'] );
}

$ProductTax->setStoreAddress( $ProductGeneral->config['config_country_id'], $ProductGeneral->config['config_zone_id'] );
 
$getCurrencies = getCurrencies(); 
$code ='';
if( $nv_Request->get_string( $module_data . '_currency', 'session' ) )
{
	$code = $nv_Request->get_string( $module_data . '_currency', 'session' );
}

if( $nv_Request->get_string( $module_data . '_currency', 'cookie' ) && ! array_key_exists( $code, $getCurrencies ) )
{
	$code = $nv_Request->get_string( $module_data . '_currency', 'cookie' );
}

if( ! array_key_exists( $code, $getCurrencies ) )
{
	$code = $ProductGeneral->config['config_currency'];
}

if( ! $nv_Request->get_string( $module_data . '_currency', 'session' ) || $nv_Request->get_string( $module_data . '_currency', 'session' ) != $code )
{
	$nv_Request->set_Session( $module_data . '_currency', $code );
}

if( ! $nv_Request->get_string( $module_data . '_currency', 'cookie' ) || $nv_Request->get_string( $module_data . '_currency', 'cookie' ) != $code )
{
	$nv_Request->set_Cookie( $module_data . '_currency', $code, NV_LIVE_COOKIE_TIME );
}


function getLangAdmin( $name, $dir )
{
	global $module_file, $productRegistry;
	
	if( ! file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/' . $dir . '/' . $name . '_admin_' . NV_LANG_DATA . '.php' ) )
	{
		trigger_error( "Error! Language variables " . $name . " is empty!", 256 );
	}
	
	require ( NV_ROOTDIR . '/modules/' . $module_file . '/language/' . $dir . '/' . $name . '_admin_' . NV_LANG_DATA . '.php' );

	return $lang_module;
}

function editSetting( $group, $data )
{
	global $db;

	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_setting WHERE groups = ' . $db->quote( $group ) );

	foreach( $data as $key => $value )
	{
		if( substr( $key, 0, strlen( $group ) ) == $group )
		{
			if( ! is_array( $value ) )
			{
				$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_setting SET groups = ' . $db->quote( $group ) . ', code = ' . $db->quote( $key ) . ', value = ' . $db->quote( $value ) );
			}
			else
			{
				$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_setting SET groups = ' . $db->quote( $group ) . ', code = ' . $db->quote( $key ) . ', value = ' . $db->quote( serialize( $value ) ) . ', serialized = 1' );
			}
		}
	}
}


/* BEGIN PRODUCT */
function delete_product( $product_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $db_config, $productRegistry;

	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_attribute WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_description WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_discount WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_filter WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_image WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_option WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_option_value WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_related WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_reward WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_special WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_review WHERE product_id = ' . ( int )$product_id );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_to_store WHERE product_id = ' . ( int )$product_id );

	//$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_faq_answer WHERE faq_id IN ( SELECT faq_id FROM ' . TABLE_PRODUCT_NAME  . '_product_faq WHERE product_id = ' . ( int )$product_id . ')' );
	// $db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_faq WHERE product_id = ' . ( int )$product_id );

	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_video_description WHERE video_id IN ( SELECT video_id FROM ' . TABLE_PRODUCT_NAME  . '_product_video WHERE product_id = ' . ( int )$product_id . ')' );
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_product_video WHERE product_id = ' . ( int )$product_id );

	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME  . '_block WHERE product_id = ' . $product_id );

}

function getProduct( $product_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $db_config, $productRegistry;

	return $db->query( 'SELECT DISTINCT * FROM ' . TABLE_PRODUCT_NAME  . '_product p LEFT JOIN ' . TABLE_PRODUCT_NAME  . '_product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = ' . ( int )$product_id . ' AND pd.language_id = ' . ( int )$ProductGeneral->current_language_id )->fetch();

}

function getProducts( $data = array() )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $db_config, $productRegistry;

	$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME  . '_product p LEFT JOIN ' . TABLE_PRODUCT_NAME  . '_product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = ' . ( int )$ProductGeneral->current_language_id;

	if( ! empty( $data['filter_name'] ) )
	{
		$sql .= ' AND pd.name LIKE :name';
	}

	if( ! empty( $data['filter_model'] ) )
	{
		$sql .= ' AND p.model LIKE :model';
	}

	if( isset( $data['filter_price'] ) && ! is_null( $data['filter_price'] ) )
	{
		$sql .= ' AND p.price LIKE :price';
	}

	if( isset( $data['filter_quantity'] ) && ! is_null( $data['filter_quantity'] ) )
	{
		$sql .= ' AND p.quantity = ' . ( int )$data['filter_quantity'];
	}

	if( isset( $data['filter_status'] ) && ! is_null( $data['filter_status'] ) )
	{
		$sql .= ' AND p.status = ' . ( int )$data['filter_status'];
	}

	$sql .= ' GROUP BY p.product_id';

	$sort_data = array(
		'pd.name',
		'p.model',
		'p.price',
		'p.quantity',
		'p.status',
		'p.sort_order' );

	if( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) )
	{
		$sql .= ' ORDER BY ' . $data['sort'];
	}
	else
	{
		$sql .= ' ORDER BY pd.name';
	}

	if( isset( $data['order'] ) && ( $data['order'] == 'DESC' ) )
	{
		$sql .= ' DESC';
	}
	else
	{
		$sql .= ' ASC';
	}

	if( isset( $data['start'] ) || isset( $data['limit'] ) )
	{
		if( $data['start'] < 0 )
		{
			$data['start'] = 0;
		}

		if( $data['limit'] < 1 )
		{
			$data['limit'] = 20;
		}

		$sql .= ' LIMIT ' . ( int )$data['start'] . ',' . ( int )$data['limit'];
	}
	$sth = $db->prepare( $sql );

	if( ! empty( $data['filter_name'] ) )
	{
		$sth->bindValue( ':name', '%' . $data['filter_name'] . '%' );
	}
	if( ! empty( $data['filter_model'] ) )
	{
		$sth->bindValue( ':model', '%' . $data['filter_name'] . '%' );
	}
	if( ! empty( $data['filter_price'] ) )
	{
		$sth->bindValue( ':price', '%' . $data['filter_price'] . '%' );
	}

	$sth->execute();

	$getProducts = array();

	while( $product = $sth->fetch() )
	{
		$getProducts[] = $product;
	}

	$sth->closeCursor();

	return $getProducts;
}

function getProductOptions( $product_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $db_config, $productRegistry;

	$product_option_data = array();

	$product_option_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME  . '_product_option po LEFT JOIN ' . TABLE_PRODUCT_NAME  . '_option o ON (po.option_id = o.option_id) LEFT JOIN ' . TABLE_PRODUCT_NAME  . '_option_description od ON (o.option_id = od.option_id) WHERE po.product_id = ' . ( int )$product_id . ' AND od.language_id = ' . ( int )$ProductGeneral->current_language_id );

	while( $product_option = $product_option_query->fetch() )
	{
		$product_option_value_data = array();

		$result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME  . '_product_option_value WHERE product_option_id = ' . ( int )$product_option['product_option_id'] );

		while( $product_option_value = $result->fetch() )
		{
			$product_option_value_data[] = array(
				'product_option_value_id' => $product_option_value['product_option_value_id'],
				'option_value_id' => $product_option_value['option_value_id'],
				'quantity' => $product_option_value['quantity'],
				'subtract' => $product_option_value['subtract'],
				'price' => $product_option_value['price'],
				'price_prefix' => $product_option_value['price_prefix'],
				'points' => $product_option_value['points'],
				'points_prefix' => $product_option_value['points_prefix'],
				'weight' => $product_option_value['weight'],
				'weight_prefix' => $product_option_value['weight_prefix'] );
		}

		$result->closeCursor();

		$product_option_data[] = array(
			'product_option_id' => $product_option['product_option_id'],
			'product_option_value' => $product_option_value_data,
			'option_id' => $product_option['option_id'],
			'name' => $product_option['name'],
			'type' => $product_option['type'],
			'value' => $product_option['value'],
			'required' => $product_option['required'] );
	}

	$product_option_query->closeCursor();

	return $product_option_data;
}

function getProductAttributes( $product_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $db_config, $productRegistry;

	$data = array();

	$result = $db->query( 'SELECT attribute_id FROM ' . TABLE_PRODUCT_NAME  . '_product_attribute WHERE product_id = ' . ( int )$product_id . ' GROUP BY attribute_id' );

	while( $product_attribute = $result->fetch() )
	{
		$item = array();

		$_result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME  . '_product_attribute WHERE product_id = ' . ( int )$product_id . ' AND attribute_id = ' . ( int )$product_attribute['attribute_id'] );

		while( $row = $_result->fetch() )
		{
			$item[$row['language_id']] = array( 'text' => $row['text'] );
		}

		$_result->closeCursor();

		$data[] = array( 'attribute_id' => $product_attribute['attribute_id'], 'product_attribute_description' => $item );
	}

	$result->closeCursor();

	return $data;
}

function getFilterDescriptions( $filter_group_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $db_config, $productRegistry;

	$filter_data = array();

	$filter_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME  . '_filter WHERE filter_group_id = ' . ( int )$filter_group_id );

	while( $filter = $filter_query->fetch() )
	{
		$filter_description_data = array();

		$result = $db->query( 'SELECT f.sort_order, fd.name, fd.language_id FROM ' . TABLE_PRODUCT_NAME  . '_filter f LEFT JOIN ' . TABLE_PRODUCT_NAME  . '_filter_description fd ON f.filter_id = fd.filter_id WHERE f.filter_id = ' . ( int )$filter['filter_id'] . ' ORDER BY f.sort_order ASC' );

		while( $filter_description = $result->fetch() )
		{
			$filter_description_data[$filter_description['language_id']] = array( 'name' => $filter_description['name'], 'sort_order' => $filter_description['sort_order'] );
		}

		$result->closeCursor();

		$filter_data[] = array(
			'filter_id' => $filter['filter_id'],
			'filter_description' => $filter_description_data,
			'sort_order' => $filter['sort_order'] );
	}
	$filter_query->closeCursor();

	$sort_order = array();

	foreach( $filter_data as $key => $value )
	{
		$sort_order[$key] = $value['sort_order'];
	}

	array_multisort( $sort_order, SORT_ASC, $filter_data );

	return $filter_data;
}
/* END PRODUCT */

 
function insertTags( $new_keywords, $old_keywords, $product_id, $language_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $db_config, $productRegistry;
 
	$insertKeywords = array();
	$removeKeywords = array();
	if( ! empty( $old_keywords[$language_id] ) )
	{
		if( $new_keywords )
		{
			foreach( $old_keywords[$language_id] as $tags_id => $tag )
			{
				if( in_array( $tag, $new_keywords ) )
				{
					$insertKeywords[] = $tag;
				}else{
					
					$removeKeywords[$tags_id] = $tag;
				}
				
			}
			
			if( !empty( $removeKeywords ) )
			{
				foreach( $removeKeywords as $tags_id => $tag )
				{
					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_tags_product WHERE tags_id=' . intval( $tags_id ) . ' AND product_id=' . intval( $product_id ) );
					$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_tags_description SET product_total = IF( product_total > 0, product_total - 1, 0 ) WHERE tags_id=' . intval( $tags_id ) );
				}
				
			}
			
		}			
	}else{
		
		$insertKeywords = $new_keywords;
	}
	
	
	$keywords = array_map( 'strip_punctuation', $insertKeywords );
	$keywords = array_map( 'trim', $keywords );
	$keywords = array_diff( $keywords, array( '' ) );
	$keywords = array_unique( $keywords );
	foreach( $keywords as $keyword )
	{

		$alias_i = ( $ProductGeneral->config['config_tags_alias'] ) ? change_alias( $keyword ) : str_replace( ' ', '-', $keyword );
		$alias_i = nv_strtolower( $alias_i );

		$sth = $db->prepare( 'SELECT tags_id, alias, description, keywords FROM ' . TABLE_PRODUCT_NAME . '_tags_description WHERE language_id = ' . intval( $language_id ) . ' AND alias= :alias OR FIND_IN_SET(:keywords, keywords)>0' );
		$sth->bindParam( ':alias', $alias_i, PDO::PARAM_STR );
		$sth->bindParam( ':keywords', $keyword, PDO::PARAM_STR );
		$sth->execute();
		list( $tags_id, $alias, $keywords_i ) = $sth->fetch( 3 );

		if( empty( $tags_id ) )
		{
			$description = '';

			$tags_id = $db->insert_id('INSERT INTO ' . TABLE_PRODUCT_NAME . '_tags ( tags_id, image ) VALUES ( NULL, \'\' )' );
			$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_tags_description SET 
				tags_id = ' . intval( $tags_id ) . ', 
				language_id = ' . intval( $language_id ) . ', 
				product_total = 1,
				alias = :alias,
				keywords = :keywords,
				description = :description' );

			$stmt->bindParam( ':alias', $alias_i, PDO::PARAM_STR );
			$stmt->bindParam( ':keywords', $keyword, PDO::PARAM_STR );
			$stmt->bindParam( ':description', $description, PDO::PARAM_STR );
			$stmt->execute();
			$stmt->closeCursor();
			
			$db->query('INSERT INTO ' . TABLE_PRODUCT_NAME . '_tags_product VALUES ( '. intval( $tags_id ) .', '. intval( $product_id ) .' )' );
 
		}
		else
		{
			if( $alias != $alias_i )
			{
				if( ! empty( $keywords_i ) )
				{
					$keyword_arr = explode( ',', $keywords_i );
					$keyword_arr[] = $keyword;
					$keywords_i2 = implode( ',', array_unique( $keyword_arr ) );
				}
				else
				{
					$keywords_i2 = $keyword;
				}
				if( $keywords_i != $keywords_i2 )
				{
					$sth = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_tags_description SET keywords= :keywords WHERE tags_id =' . intval( $tags_id ) . ' AND language_id = ' . intval( $language_id ) );
					$sth->bindParam( ':keywords', $keywords_i2, PDO::PARAM_STR );
					$sth->execute();
				}

			}
			
			$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_tags_description SET product_total = product_total+1 WHERE tags_id = ' . intval( $tags_id ) . ' AND language_id = ' . intval( $language_id ) );
		}
	}
 
	return;
}

function updateProductCategory( $category_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $db_config, $productRegistry;

	$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_category SET product_total =(SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_product WHERE category_id = ' . intval( $category_id ) . ') WHERE category_id = ' . intval( $category_id ) );

}