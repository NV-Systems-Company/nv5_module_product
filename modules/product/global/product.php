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

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );
 
function updateViewed( $product_id )
{
	global $db;
	
	$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product SET hitstotal = ( hitstotal + 1 ) WHERE product_id = ' . ( int )$product_id );
}

function getProduct( $product_id )
{
	global $db, $ProductGeneral, $module_data, $user_info;
	
	$result = $db->query( '
	SELECT DISTINCT *, pd.name AS name, p.image, m.name AS brand, 
		(SELECT price FROM ' . TABLE_PRODUCT_NAME . '_product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = ' . intval( $ProductGeneral->config['config_customer_group_id'] ) . ' AND pd2.quantity = 1 AND ((pd2.date_start = 0 OR pd2.date_start < '. NV_CURRENTTIME .') AND (pd2.date_end = 0 OR pd2.date_end > '. NV_CURRENTTIME .')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, 
		(SELECT price FROM ' . TABLE_PRODUCT_NAME . '_product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = ' . intval( $ProductGeneral->config['config_customer_group_id'] ) . ' AND ((ps.date_start = 0 OR ps.date_start < '. NV_CURRENTTIME .') AND (ps.date_end = 0 OR ps.date_end > '. NV_CURRENTTIME .')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, 
		(SELECT points FROM ' . TABLE_PRODUCT_NAME . '_product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = ' . intval( $ProductGeneral->config['config_customer_group_id'] ) . ' AS reward, 
		(SELECT ss.name FROM ' . TABLE_PRODUCT_NAME . '_stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = ' . intval( $ProductGeneral->current_language_id ) . ') AS stock_status, 
		(SELECT wcd.unit FROM ' . TABLE_PRODUCT_NAME . '_weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ') AS weight_class, 
		(SELECT lcd.unit FROM ' . TABLE_PRODUCT_NAME . '_length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ') AS length_class, 
		(SELECT AVG(rating) AS total FROM ' . TABLE_PRODUCT_NAME . '_review r1 WHERE r1.product_id = p.product_id AND r1.status = 1 GROUP BY r1.product_id) AS rating, 
		(SELECT COUNT(*) AS total FROM ' . TABLE_PRODUCT_NAME . '_review r2 WHERE r2.product_id = p.product_id AND r2.status = 1 GROUP BY r2.product_id) AS reviews, p.sort_order 
	FROM ' . TABLE_PRODUCT_NAME . '_product p 
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id) 
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_store p2s ON (p.product_id = p2s.product_id) 
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_brand m ON (p.brand_id = m.brand_id) 
	WHERE p.product_id = ' . intval ( $product_id ) . ' 
	AND pd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' 
	AND p.status = 1 
	AND p.date_available <= '. NV_CURRENTTIME .' 
	AND p2s.store_id = ' . intval( $ProductGeneral->config['config_store_id']) );

	if( $result->rowCount() )
	{
		$data = $result->fetch();
		return array(
			'product_id' => $data['product_id'],
			'name' => $data['name'],
			'description' => $data['description'],
			'meta_title' => $data['meta_title'],
			'meta_description' => $data['meta_description'],
			'tag' => $data['tag'],
			'model' => $data['model'],
			'sku' => $data['sku'],
			'upc' => $data['upc'],
			'ean' => $data['ean'],
			'jan' => $data['jan'],
			'isbn' => $data['isbn'],
			'mpn' => $data['mpn'],
			'location' => $data['location'],
			'quantity' => $data['quantity'],
			'stock_status' => $data['stock_status'],
			'image' => $data['image'],
			'brand_id' => $data['brand_id'],
			'brand' => $data['brand'],
			'price' => ( $data['discount'] ? $data['discount'] : $data['price'] ),
			'special' => $data['special'],
			'reward' => $data['reward'],
			'points' => $data['points'],
			'tax_class_id' => $data['tax_class_id'],
			'date_available' => $data['date_available'],
			'weight' => $data['weight'],
			'weight_class_id' => $data['weight_class_id'],
			'length' => $data['length'],
			'width' => $data['width'],
			'height' => $data['height'],
			'length_class_id' => $data['length_class_id'],
			'subtract' => $data['subtract'],
			'rating' => round( $data['rating'] ),
			'reviews' => $data['reviews'] ? $data['reviews'] : 0,
			'minimum' => $data['minimum'],
			'sort_order' => $data['sort_order'],
			'status' => $data['status'],
			'date_added' => $data['date_added'],
			'date_modified' => $data['date_modified'],
			'viewed' => $data['viewed'] );
	}
	else
	{
		return false;
	}
}

function getProducts( $data = array() )
{
	global $db, $module_data, $user_info;
	
	$sql = 'SELECT p.product_id, (SELECT AVG(rating) AS total FROM ' . TABLE_PRODUCT_NAME . '_review r1 WHERE r1.product_id = p.product_id AND r1.status = 1 GROUP BY r1.product_id) AS rating, (SELECT price FROM ' . TABLE_PRODUCT_NAME . '_product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = ' . ( int )$config->get( 'config_customer_group_id' ) . ' AND pd2.quantity = 1 AND ((pd2.date_start = 0 OR pd2.date_start < '. NV_CURRENTTIME .') AND (pd2.date_end = 0 OR pd2.date_end > '. NV_CURRENTTIME .')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM ' . TABLE_PRODUCT_NAME . '_product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = ' . ( int )$config->get( 'config_customer_group_id' ) . ' AND ((ps.date_start = 0 OR ps.date_start < '. NV_CURRENTTIME .') AND (ps.date_end = 0 OR ps.date_end > '. NV_CURRENTTIME .')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special';

	if( ! empty( $data['filter_category_id'] ) )
	{
		if( ! empty( $data['filter_sub_category'] ) )
		{
			$sql .= ' FROM ' . TABLE_PRODUCT_NAME . '_category_path cp LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_category p2c ON (cp.category_id = p2c.category_id)';
		}
		else
		{
			$sql .= ' FROM ' . TABLE_PRODUCT_NAME . '_product_to_category p2c';
		}

		if( ! empty( $data['filter_filter'] ) )
		{
			$sql .= ' LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product p ON (pf.product_id = p.product_id)';
		}
		else
		{
			$sql .= ' LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product p ON (p2c.product_id = p.product_id)';
		}
	}
	else
	{
		$sql .= ' FROM ' . TABLE_PRODUCT_NAME . '_product p';
	}

	$sql .= ' LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' AND p.status = 1 AND p.date_available <= '. NV_CURRENTTIME .' AND p2s.store_id = ' . ( int )$config->get( 'config_store_id' ) . '';

	if( ! empty( $data['filter_category_id'] ) )
	{
		if( ! empty( $data['filter_sub_category'] ) )
		{
			$sql .= ' AND cp.path_id = ' . ( int )$data['filter_category_id'] . '';
		}
		else
		{
			$sql .= ' AND p2c.category_id = ' . ( int )$data['filter_category_id'] . '';
		}

		if( ! empty( $data['filter_filter'] ) )
		{
			$implode = array();

			$filters = explode( ',', $data['filter_filter'] );

			foreach( $filters as $filter_id )
			{
				$implode[] = ( int )$filter_id;
			}

			$sql .= ' AND pf.filter_id IN (' . implode( ',', $implode ) . ')';
		}
	}

	if( ! empty( $data['filter_name'] ) || ! empty( $data['filter_tag'] ) )
	{
		$sql .= ' AND (';

		if( ! empty( $data['filter_name'] ) )
		{
			$implode = array();

			$words = explode( ' ', trim( preg_replace( '/\s+/', ' ', $data['filter_name'] ) ) );

			foreach( $words as $word )
			{
				$implode[] = 'pd.name LIKE \'%' . $db->quote( $word ) . '%\'';
			}

			if( $implode )
			{
				$sql .= implode( ' AND ', $implode );
			}

			if( ! empty( $data['filter_description'] ) )
			{
				$sql .= ' OR pd.description LIKE \'%' . $db->dblikeescape( $data['filter_name'] ) . '%\'';
			}
		}

		if( ! empty( $data['filter_name'] ) && ! empty( $data['filter_tag'] ) )
		{
			$sql .= ' OR ';
		}

		if( ! empty( $data['filter_tag'] ) )
		{
			$sql .= 'pd.tag LIKE \'%' . $db->dblikeescape( $data['filter_tag'] ) . '%\'';
		}

		// if( ! empty( $data['filter_name'] ) )
		// {
			// $sql .= ' OR LCASE(p.model) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			// $sql .= ' OR LCASE(p.sku) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			// $sql .= ' OR LCASE(p.upc) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			// $sql .= ' OR LCASE(p.ean) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			// $sql .= ' OR LCASE(p.jan) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			// $sql .= ' OR LCASE(p.isbn) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			// $sql .= ' OR LCASE(p.mpn) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
		// }

		$sql .= ')';
	}

	if( ! empty( $data['filter_brand_id'] ) )
	{
		$sql .= ' AND p.brand_id = ' . ( int )$data['filter_brand_id'] . '';
	}

	$sql .= ' GROUP BY p.product_id';

	$sort_data = array(
		'pd.name',
		'p.model',
		'p.quantity',
		'p.price',
		'rating',
		'p.sort_order',
		'p.date_added' );

	if( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) )
	{
		if( $data['sort'] == 'pd.name' || $data['sort'] == 'p.model' )
		{
			$sql .= ' ORDER BY LCASE(' . $data['sort'] . ')';
		}
		elseif( $data['sort'] == 'p.price' )
		{
			$sql .= ' ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)';
		}
		else
		{
			$sql .= ' ORDER BY ' . $data['sort'];
		}
	}
	else
	{
		$sql .= ' ORDER BY p.sort_order';
	}

	if( isset( $data['order'] ) && ( $data['order'] == 'DESC' ) )
	{
		$sql .= ' DESC, LCASE(pd.name) DESC';
	}
	else
	{
		$sql .= ' ASC, LCASE(pd.name) ASC';
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

	$product_data = array();

	$query = $db->query( $sql );

	foreach( $datas as $result )
	{
		$product_data[$result['product_id']] = $getProduct( $result['product_id'] );
	}

	return $product_data;
}

function getProductSpecials( $data = array() )
{
	global $db, $module_data, $user_info;
	
	$sql = 'SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM ' . TABLE_PRODUCT_NAME . '_review r1 WHERE r1.product_id = ps.product_id AND r1.status = 1 GROUP BY r1.product_id) AS rating FROM ' . TABLE_PRODUCT_NAME . '_product_special ps LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product p ON (ps.product_id = p.product_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = 1 AND p.date_available <= '. NV_CURRENTTIME .' AND p2s.store_id = ' . ( int )$config->get( 'config_store_id' ) . ' AND ps.customer_group_id = ' . ( int )$config->get( 'config_customer_group_id' ) . ' AND ((ps.date_start = 0 OR ps.date_start < '. NV_CURRENTTIME .') AND (ps.date_end = 0 OR ps.date_end > '. NV_CURRENTTIME .')) GROUP BY ps.product_id';

	$sort_data = array(
		'pd.name',
		'p.model',
		'ps.price',
		'rating',
		'p.sort_order' );

	if( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) )
	{
		if( $data['sort'] == 'pd.name' || $data['sort'] == 'p.model' )
		{
			$sql .= ' ORDER BY LCASE(' . $data['sort'] . ')';
		}
		else
		{
			$sql .= ' ORDER BY ' . $data['sort'];
		}
	}
	else
	{
		$sql .= ' ORDER BY p.sort_order';
	}

	if( isset( $data['order'] ) && ( $data['order'] == 'DESC' ) )
	{
		$sql .= ' DESC, LCASE(pd.name) DESC';
	}
	else
	{
		$sql .= ' ASC, LCASE(pd.name) ASC';
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

	$product_data = array();

	$query = $db->query( $sql );

	foreach( $datas as $result )
	{
		$product_data[$result['product_id']] = $getProduct( $result['product_id'] );
	}

	return $product_data;
}

function getLatestProducts( $limit )
{
	global $db, $module_data, $user_info;
	
	$product_data = $cache->get( 'product.latest.' . intval( $ProductGeneral->current_language_id ) . '.' . ( int )$config->get( 'config_store_id' ) . '.' . $config->get( 'config_customer_group_id' ) . '.' . ( int )$limit );

	if( ! $product_data )
	{
		$query = $db->query( 'SELECT p.product_id FROM ' . TABLE_PRODUCT_NAME . '_product p LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = 1 AND p.date_available <= '. NV_CURRENTTIME .' AND p2s.store_id = ' . ( int )$config->get( 'config_store_id' ) . ' ORDER BY p.date_added DESC LIMIT ' . ( int )$limit );

		foreach( $datas as $result )
		{
			$product_data[$result['product_id']] = $getProduct( $result['product_id'] );
		}

		$cache->set( 'product.latest.' . intval( $ProductGeneral->current_language_id ) . '.' . ( int )$config->get( 'config_store_id' ) . '.' . $config->get( 'config_customer_group_id' ) . '.' . ( int )$limit, $product_data );
	}

	return $product_data;
}

function getPopularProducts( $limit )
{
	global $db, $module_data, $user_info;
	
	$product_data = array();

	$query = $db->query( 'SELECT p.product_id FROM ' . TABLE_PRODUCT_NAME . '_product p LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = 1 AND p.date_available <= '. NV_CURRENTTIME .' AND p2s.store_id = ' . ( int )$config->get( 'config_store_id' ) . ' ORDER BY p.viewed DESC, p.date_added DESC LIMIT ' . ( int )$limit );

	foreach( $datas as $result )
	{
		$product_data[$result['product_id']] = $getProduct( $result['product_id'] );
	}

	return $product_data;
}

function getBestSellerProducts( $limit )
{
	global $db, $module_data, $user_info, $ProductGeneral;
	
	//$product_data = $cache->get( 'product.bestseller.' . intval( $ProductGeneral->current_language_id ) . '.' . ( int )$config->get( 'config_store_id' ) . '.' . $config->get( 'config_customer_group_id' ) . '.' . ( int )$limit );

	if( ! $product_data )
	{
		$product_data = array();

		$query = $db->query( 'SELECT op.product_id, SUM(op.quantity) AS total FROM ' . TABLE_PRODUCT_NAME . '_order_product op LEFT JOIN ' . TABLE_PRODUCT_NAME . '_order o ON (op.order_id = o.order_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product p ON (op.product_id = p.product_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > 0 AND p.status = 1 AND p.date_available <= '. NV_CURRENTTIME .' AND p2s.store_id = ' . $ProductGeneral->config['config_store_id'] . ' GROUP BY op.product_id ORDER BY total DESC LIMIT ' . intval( $limit ) );

		foreach( $datas as $result )
		{
			$product_data[$result['product_id']] = $getProduct( $result['product_id'] );
		}

		//$cache->set( 'product.bestseller.' . intval( $ProductGeneral->current_language_id ) . '.' . ( int )$config->get( 'config_store_id' ) . '.' . $config->get( 'config_customer_group_id' ) . '.' . ( int )$limit, $product_data );
	}

	return $product_data;
}

function getProductAttributes( $product_id )
{
	global $db, $ProductGeneral;
	$product_attribute_data = array();
	
	$product_attribute_query = $db->query( 'SELECT a.attribute_id, ad.name, pa.text FROM ' . TABLE_PRODUCT_NAME . '_product_attribute pa LEFT JOIN ' . TABLE_PRODUCT_NAME . '_attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = ' . ( int )$product_id . ' AND ad.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' AND pa.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' ORDER BY a.sort_order, ad.name' )->fetchAll();
	
	foreach( $product_attribute_query as $product_attribute )
	{
		$product_attribute_data[] = array(
			'attribute_id' => $product_attribute['attribute_id'],
			'name' => $product_attribute['name'],
			'text' => $product_attribute['text'] );
	}
	return $product_attribute_data;
}

function getProductAttributesByGroup( $product_id )
{
	global $db, $ProductGeneral;
	
	$product_attribute_group_data = array();

	$product_attribute_group_query = $db->query( 'SELECT ag.attribute_group_id, agd.name FROM ' . TABLE_PRODUCT_NAME . '_product_attribute pa LEFT JOIN ' . TABLE_PRODUCT_NAME . '_attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = ' . ( int )$product_id . ' AND agd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name' );

	foreach( $product_attribute_group_query as $product_attribute_group )
	{
		$product_attribute_data = array();

		$product_attribute_query = $db->query( 'SELECT a.attribute_id, ad.name, pa.text FROM ' . TABLE_PRODUCT_NAME . '_product_attribute pa LEFT JOIN ' . TABLE_PRODUCT_NAME . '_attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = ' . ( int )$product_id . ' AND a.attribute_group_id = ' . ( int )$product_attribute_group['attribute_group_id'] . ' AND ad.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' AND pa.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' ORDER BY a.sort_order, ad.name' );

		foreach( $product_attribute_query as $product_attribute )
		{
			$product_attribute_data[] = array(
				'attribute_id' => $product_attribute['attribute_id'],
				'name' => $product_attribute['name'],
				'text' => $product_attribute['text'] );
		}

		$product_attribute_group_data[] = array(
			'attribute_group_id' => $product_attribute_group['attribute_group_id'],
			'name' => $product_attribute_group['name'],
			'attribute' => $product_attribute_data );
	}

	return $product_attribute_group_data;
}

function getProductOptions( $product_id )
{
	global $db, $module_data, $user_info;
	
	$product_option_data = array();

	$product_option_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_option po LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option o ON (po.option_id = o.option_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option_description od ON (o.option_id = od.option_id) WHERE po.product_id = ' . ( int )$product_id . ' AND od.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' ORDER BY o.sort_order' );

	foreach( $product_option_query->rows as $product_option )
	{
		$product_option_value_data = array();

		$product_option_value_query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_option_value pov LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = ' . ( int )$product_id . ' AND pov.product_option_id = ' . ( int )$product_option['product_option_id'] . ' AND ovd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' ORDER BY ov.sort_order' );

		foreach( $product_option_value_query->rows as $product_option_value )
		{
			$product_option_value_data[] = array(
				'product_option_value_id' => $product_option_value['product_option_value_id'],
				'option_value_id' => $product_option_value['option_value_id'],
				'name' => $product_option_value['name'],
				'image' => $product_option_value['image'],
				'quantity' => $product_option_value['quantity'],
				'subtract' => $product_option_value['subtract'],
				'price' => $product_option_value['price'],
				'price_prefix' => $product_option_value['price_prefix'],
				'weight' => $product_option_value['weight'],
				'weight_prefix' => $product_option_value['weight_prefix'] );
		}

		$product_option_data[] = array(
			'product_option_id' => $product_option['product_option_id'],
			'product_option_value' => $product_option_value_data,
			'option_id' => $product_option['option_id'],
			'name' => $product_option['name'],
			'type' => $product_option['type'],
			'value' => $product_option['value'],
			'required' => $product_option['required'] );
	}

	return $product_option_data;
}

function getProductDiscounts( $product_id )
{
	global $db, $module_data, $user_info;
	
	$query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_discount WHERE product_id = ' . ( int )$product_id . ' AND customer_group_id = ' . ( int )$config->get( 'config_customer_group_id' ) . ' AND quantity > 1 AND ((date_start = 0 OR date_start < '. NV_CURRENTTIME .') AND (date_end = 0 OR date_end > '. NV_CURRENTTIME .')) ORDER BY quantity ASC, priority ASC, price ASC' );

	return $datas;
}

function getProductImages( $product_id )
{
	global $db;
	
	$result = $db->query( 'SELECT image FROM ' . TABLE_PRODUCT_NAME . '_product_image WHERE product_id = ' . intval( $product_id ) . ' ORDER BY sort_order ASC' );
	$images = array();
	while( list( $image ) = $result->fetch(3) )
	{
		$images[] = $image;
	}
	$result->closeCursor();
	return $images;
}

function getProductRelated( $product_id )
{
	global $db, $module_data, $user_info;
	
	$product_data = array();

	$query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_related pr LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product p ON (pr.related_id = p.product_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = ' . ( int )$product_id . ' AND p.status = 1 AND p.date_available <= '. NV_CURRENTTIME .' AND p2s.store_id = ' . ( int )$config->get( 'config_store_id' ) . '' );

	foreach( $datas as $result )
	{
		$product_data[$result['related_id']] = $getProduct( $result['related_id'] );
	}

	return $product_data;
}

function getCategories( $product_id )
{
	global $db, $module_data, $user_info;
	
	$query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_to_category WHERE product_id = ' . ( int )$product_id . '' );

	return $datas;
}

function getTotalProducts( $data = array() )
{
	global $db, $module_data, $user_info;
	
	$sql = 'SELECT COUNT(DISTINCT p.product_id) AS total';

	if( ! empty( $data['filter_category_id'] ) )
	{
		if( ! empty( $data['filter_sub_category'] ) )
		{
			$sql .= ' FROM ' . TABLE_PRODUCT_NAME . '_category_path cp LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_category p2c ON (cp.category_id = p2c.category_id)';
		}
		else
		{
			$sql .= ' FROM ' . TABLE_PRODUCT_NAME . '_product_to_category p2c';
		}

		if( ! empty( $data['filter_filter'] ) )
		{
			$sql .= ' LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product p ON (pf.product_id = p.product_id)';
		}
		else
		{
			$sql .= ' LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product p ON (p2c.product_id = p.product_id)';
		}
	}
	else
	{
		$sql .= ' FROM ' . TABLE_PRODUCT_NAME . '_product p';
	}

	$sql .= ' LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' AND p.status = 1 AND p.date_available <= '. NV_CURRENTTIME .' AND p2s.store_id = ' . ( int )$config->get( 'config_store_id' ) . '';

	if( ! empty( $data['filter_category_id'] ) )
	{
		if( ! empty( $data['filter_sub_category'] ) )
		{
			$sql .= ' AND cp.path_id = ' . ( int )$data['filter_category_id'] . '';
		}
		else
		{
			$sql .= ' AND p2c.category_id = ' . ( int )$data['filter_category_id'] . '';
		}

		if( ! empty( $data['filter_filter'] ) )
		{
			$implode = array();

			$filters = explode( ',', $data['filter_filter'] );

			foreach( $filters as $filter_id )
			{
				$implode[] = ( int )$filter_id;
			}

			$sql .= ' AND pf.filter_id IN (' . implode( ',', $implode ) . ')';
		}
	}

	if( ! empty( $data['filter_name'] ) || ! empty( $data['filter_tag'] ) )
	{
		$sql .= ' AND (';

		if( ! empty( $data['filter_name'] ) )
		{
			$implode = array();

			$words = explode( ' ', trim( preg_replace( '/\s+/', ' ', $data['filter_name'] ) ) );

			foreach( $words as $word )
			{
				$implode[] = 'pd.name LIKE \'%' . $db->dblikeescape( $word ) . '%\'';
			}

			if( $implode )
			{
				$sql .= implode( ' AND ', $implode );
			}

			if( ! empty( $data['filter_description'] ) )
			{
				$sql .= ' OR pd.description LIKE \'%' . $db->dblikeescape( $data['filter_name'] ) . '%\'';
			}
		}

		if( ! empty( $data['filter_name'] ) && ! empty( $data['filter_tag'] ) )
		{
			$sql .= ' OR ';
		}

		if( ! empty( $data['filter_tag'] ) )
		{
			$sql .= 'pd.tag LIKE \'%' . $db->dblikeescape( utf8_strtolower( $data['filter_tag'] ) ) . '%\'';
		}

		if( ! empty( $data['filter_name'] ) )
		{
			$sql .= ' OR LCASE(p.model) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			$sql .= ' OR LCASE(p.sku) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			$sql .= ' OR LCASE(p.upc) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			$sql .= ' OR LCASE(p.ean) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			$sql .= ' OR LCASE(p.jan) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			$sql .= ' OR LCASE(p.isbn) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
			$sql .= ' OR LCASE(p.mpn) = ' . $db->dblikeescape( utf8_strtolower( $data['filter_name'] ) ) . '';
		}

		$sql .= ')';
	}

	if( ! empty( $data['filter_brand_id'] ) )
	{
		$sql .= ' AND p.brand_id = ' . ( int )$data['filter_brand_id'] . '';
	}

	$query = $db->query( $sql );

	return $data['total'];
}

function getProfiles( $product_id )
{
	global $db, $module_data, $user_info;
	
	return $db->query( 'SELECT pd.* FROM ' . TABLE_PRODUCT_NAME . '_product_recurring pp JOIN ' . TABLE_PRODUCT_NAME . '_recurring_description pd ON pd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' AND pd.recurring_id = pp.recurring_id JOIN ' . TABLE_PRODUCT_NAME . '_recurring p ON p.recurring_id = pd.recurring_id WHERE product_id = ' . ( int )$product_id . ' AND status = 1 AND customer_group_id = ' . ( int )$config->get( 'config_customer_group_id' ) . ' ORDER BY sort_order ASC' )->rows;
}

function getProfile( $product_id, $recurring_id )
{
	global $db, $module_data, $user_info;
	
	return $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_recurring p JOIN ' . TABLE_PRODUCT_NAME . '_product_recurring pp ON pp.recurring_id = p.recurring_id AND pp.product_id = ' . ( int )$product_id . ' WHERE pp.recurring_id = ' . ( int )$recurring_id . ' AND status = 1 AND pp.customer_group_id = ' . ( int )$config->get( 'config_customer_group_id' ) )->fetch();
}

function getTotalProductSpecials()
{
	global $db, $module_data, $user_info;
	
	$query = $db->query( 'SELECT COUNT(DISTINCT ps.product_id) AS total FROM ' . TABLE_PRODUCT_NAME . '_product_special ps LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product p ON (ps.product_id = p.product_id) LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = 1 AND p.date_available <= '. NV_CURRENTTIME .' AND p2s.store_id = ' . ( int )$config->get( 'config_store_id' ) . ' AND ps.customer_group_id = ' . ( int )$config->get( 'config_customer_group_id' ) . ' AND ((ps.date_start = 0 OR ps.date_start < '. NV_CURRENTTIME .') AND (ps.date_end = 0 OR ps.date_end > '. NV_CURRENTTIME .'))' );

	if( isset( $data['total'] ) )
	{
		return $data['total'];
	}
	else
	{
		return 0;
	}
}

function getReviewsByProductId( $product_id, $start = 0, $limit = 20 )
{
	global $db, $current_language_id;

	if( $start < 0 )
	{
		$start = 0;
	}

	if( $limit < 1 )
	{
		$limit = 20;
	}

	$query = $db->query( 'SELECT r.*, u.photo FROM ' . TABLE_PRODUCT_NAME . '_product_review r 
		LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' u ON ( r.userid = u.userid )
		WHERE r.product_id = ' . ( int )$product_id . '  
		ORDER BY r.date_added DESC LIMIT ' . ( int )$start . ',' . ( int )$limit )->fetchAll();

	return $query;
}

function getReviewsRatingByProductId( $product_id )
{
	global $db;

	$result = $db->query( 'SELECT rating, COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_product_review WHERE product_id = ' . ( int )$product_id . ' GROUP BY rating ASC' );
	$array_rating = array();
 
	while( list( $rating, $total ) = $result->fetch( 3 ) )
	{
		
		$array_rating[$rating] = array(
			'rating' => $rating,
			'total' => $total );
 
	}
 
	return $array_rating;
}

function getQuestionByProductId( $product_id , $start = 0, $limit = 20)
{
	global $db;
	if( $start < 0 )
	{
		$start = 0;
	}

	if( $limit < 1 )
	{
		$limit = 20;
	}
	$query = $db->query('SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_faq 
			WHERE product_id = ' . (int)$product_id .' 
			ORDER BY date_added DESC 
			LIMIT ' . ( int )$start . ',' . ( int )$limit )->fetchAll();
 
	return $query;
}

