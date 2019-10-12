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

if( ! defined( 'NV_IS_MOD_PRODUCT' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$db->sqlreset()->select( 'COUNT(*)' )->from( TABLE_PRODUCT_NAME . '_product_special ps' )->join( 'LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product p ON (ps.product_id = p.product_id) 
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id) 
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_to_store p2s ON (p.product_id = p2s.product_id)' )->where( 'p.status = 1 AND p.date_added <= ' . NV_CURRENTTIME . ' 
			AND p2s.store_id = ' . ( int )$ProductGeneral->store_id . ' 
			AND ps.customer_group_id = ' . ( int )$ProductGeneral->config['config_customer_group_id'] . ' 
			AND ((ps.date_start = 0 OR ps.date_start < ' . NV_CURRENTTIME . ') 
			AND (ps.date_end = 0 OR ps.date_end > ' . NV_CURRENTTIME . '))' )->group( 'ps.product_id' );

$num_items = $db->query( $db->sql() )->fetchColumn();

$data['sort'] = $nv_Request->get_int( 'sort', 'post', 0 );
$data['order'] = $nv_Request->get_int( 'order', 'post', 0 );
$data['sort'] = 'pd.name';
$sort_data = array(
	'pd.name',
	'p.model',
	'ps.price',
	'rating',
	'p.sort_order' );

$orderby = '';

if( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) )
{

	if( $data['sort'] == 'pd.name' || $data['sort'] == 'p.model' )
	{
		$orderby .= ' LCASE(' . $data['sort'] . ')';
	}
	else
	{
		$orderby .= '  ' . $data['sort'];
	}
}
else
{
	$orderby .= '  p.sort_order';
}

if( isset( $data['order'] ) && ( $data['order'] == 'DESC' ) )
{
	$orderby .= ' DESC, LCASE(pd.name) DESC';
}
else
{
	$orderby .= ' ASC, LCASE(pd.name) ASC';
}

$db->select( 'DISTINCT ps.product_id, (SELECT AVG(rating) FROM ' . TABLE_PRODUCT_NAME . '_product_review r1 WHERE r1.product_id = ps.product_id AND r1.status = 1 GROUP BY r1.product_id) rating' )->order( $orderby )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
 
$result = $db->query( $db->sql() );
$product_id_array = array();
while( $rows = $result->fetch() )
{
	$product_id_array[] = $rows['product_id'];
}
$result->closeCursor();

$discount = '(SELECT price FROM ' . TABLE_PRODUCT_NAME . '_product_discount pd2 
WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = ' . ( int )$ProductGeneral->config['config_customer_group_id'] . ' AND pd2.quantity = 1 AND ((pd2.date_start = 0 OR pd2.date_start < ' . NV_CURRENTTIME . ') AND (pd2.date_end = 0 OR pd2.date_end > ' . NV_CURRENTTIME . ') ) 
ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) discount,';

$special = '(SELECT price FROM ' . TABLE_PRODUCT_NAME . '_product_special ps 
WHERE ps.product_id = p.product_id AND ps.customer_group_id = ' . ( int )$ProductGeneral->config['config_customer_group_id'] . ' AND ((ps.date_start = 0 OR ps.date_start < ' . NV_CURRENTTIME . ') AND (ps.date_end = 0 OR ps.date_end > ' . NV_CURRENTTIME . ')) 
ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) special,';

$reward = '(SELECT points FROM ' . TABLE_PRODUCT_NAME . '_product_reward pr 
WHERE pr.product_id = p.product_id AND customer_group_id = ' . ( int )$ProductGeneral->config['config_customer_group_id'] . ') reward,';

$stock_status = '(SELECT ss.name FROM ' . TABLE_PRODUCT_NAME . '_stock_status ss 
WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = ' . ( int )$ProductGeneral->current_language_id . ') stock_status,';

$brand_description = '(SELECT md.name FROM ' . TABLE_PRODUCT_NAME . '_brand_description md 
WHERE p.brand_id = md.brand_id AND md.language_id = ' . ( int )$ProductGeneral->current_language_id . ') brand,';

$weight_class_description = '(SELECT wcd.unit FROM ' . TABLE_PRODUCT_NAME . '_weight_class_description wcd 
WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = ' . ( int )$ProductGeneral->current_language_id . ') weight_class,';

$length_class_description = '(SELECT lcd.unit FROM ' . TABLE_PRODUCT_NAME . '_length_class_description lcd 
WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = ' . ( int )$ProductGeneral->current_language_id . ') length_class,';

// $review_avg = '(SELECT AVG(rating) AS total FROM ' . TABLE_PRODUCT_NAME . '_review r1
// WHERE r1.product_id = p.product_id AND r1.status = 1 GROUP BY r1.product_id) AS rating,';

// $review_count  = '(SELECT COUNT(*) AS total FROM ' . TABLE_PRODUCT_NAME . '_review r2
// WHERE r2.product_id = p.product_id AND r2.status = 1 GROUP BY r2.product_id) AS reviews,';

// $review_count  = '(SELECT AVG(rating) AS total FROM ' . TABLE_PRODUCT_NAME . '_review r1
// WHERE r1.product_id = p.product_id AND r1.status = 1 GROUP BY r1.product_id) AS rating,';

$select = $discount . $special . $reward . $stock_status . $brand_description . $weight_class_description . $length_class_description;

$sql = 'SELECT DISTINCT *, pd.name name, pd.alias alias, p.image, p.thumb, p.shipping, ' . $select . ' p.status FROM ' . TABLE_PRODUCT_NAME . '_product p 
		LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id) 
		WHERE p.product_id IN (' . implode( ',', $product_id_array ) . ') AND pd.language_id = ' . ( int )$ProductGeneral->current_language_id . '  AND p.status = 1 AND p.date_added <= ' . NV_CURRENTTIME;

$result = $db->query( $sql );
		
$product_data = array(); 
 
while( $rows = $result->fetch() )
{ 
	if( $rows['thumb'] == 1 )
	{
		$rows['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $rows['image'];
	}
	elseif( $rows['thumb'] == 2 )
	{
		$rows['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $rows['image'];
	}
	elseif( $rows['thumb'] == 3 )
	{
		$rows['thumb'] = $rows['image'];
	}
	else
	{
		$rows['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
	}

	$rows['link_pro'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$rows['category_id']]['alias'] . '/' . $rows['alias'] . $global_config['rewrite_exturl'];
	$rows['link_order'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart&amp;product_id=' . $rows['product_id'];

	$product_data[] = $rows; 
}
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=hotdeal';

$pages = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );

$contents = call_user_func( 'theme_specials_view', $product_data, $pages, $data );

include NV_ROOTDIR . '/includes/header.php';

echo nv_site_theme( $contents );

unset( $array_cat_shops, $product_info, $contents, $data_content, $ProductGeneral->config, $global_customer_group, $global_brand, $global_stock_status, $global_product_group, $productCategory, $module_array_cat, $data_shop, $sql, $data_others, $array_other_view, $html_pages, $user, $discount, $special, $reward, $stock_status, $select, $data_pro, $rows, $array_cat_shops, $user_info, $module_config, $openid_servers, $admin_info, $module_array_cat );

$shops_cart = null;
$shops_coupon = null;
$ProductTax = null;
$ProductContent = null;
$ProductCurrency = null;
$ProductGeneral = null;
include NV_ROOTDIR . '/includes/footer.php';
