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

$nv_Request->get_int( 'sorts', 'session', 0 );
$sorts = $nv_Request->get_int( 'sort', 'post', 0 );
$sorts_old = $nv_Request->get_int( 'sorts', 'session', 0 );
$sorts = $nv_Request->get_int( 'sorts', 'post', $sorts_old );

$contents = '';
$cache_file = '';

if( $nv_Request->isset_request( 'changesprice', 'post' ) )
{
	$sorts = $nv_Request->get_int( 'sort', 'post', 0 );
	$nv_Request->set_Session( 'sorts', $sorts, NV_LIVE_SESSION_TIME );
	$ProductGeneral->deleteCache( 'brand' );
	die( 'OK' );
}

$alias_brand_url = isset( $array_op[1] ) ? $array_op[1] : '';

$brand_id = 0;
$getBrand = getBrand();
foreach( $getBrand as $_brand_id => $brand )
{
	if( $alias_brand_url == $brand['alias'] )
	{
		$brand_id = $brand['brand_id'];
	}
}
if( empty( $brand_id ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

$data_content = array();
$html_pages = '';
$orderby = '';
if( $sorts == 0 )
{
	$orderby = ' p.product_id DESC ';

}
elseif( $sorts == 1 )
{
	$orderby = ' p.price ASC, p.product_id DESC ';
}
else
{
	$orderby = ' p.price DESC, p.product_id DESC ';
}

$from = TABLE_PRODUCT_NAME . '_product p LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id)';

// Fetch Limit
$db->sqlreset()->select( 'COUNT(*)' )->from( $from )->where( 'p.status =1 AND p.brand_id =' . $brand_id . ' AND pd.language_id = ' . $ProductGeneral->current_language_id );

$num_items = $db->query( $db->sql() )->fetchColumn();

// truy vấn giảm giá
$discount = '(SELECT price
			FROM ' . TABLE_PRODUCT_NAME . '_product_discount pd2
			WHERE pd2.product_id = p.product_id
				AND pd2.customer_group_id = ' . intval( $ProductGeneral->config['config_customer_group_id'] ) . '
				AND pd2.quantity > 0
				AND ((pd2.date_start = 0
					OR pd2.date_start < ' . NV_CURRENTTIME . ')
					AND (pd2.date_end = 0
					   OR pd2.date_end > ' . NV_CURRENTTIME . '))
			ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) discount,';

// truy vấn giảm giá đặc biệt
$special = '(SELECT price
			FROM ' . TABLE_PRODUCT_NAME . '_product_special ps
			WHERE ps.product_id = p.product_id
				AND ps.customer_group_id = ' . intval( $ProductGeneral->config['config_customer_group_id'] ) . '
				AND ((ps.date_start = 0
					OR ps.date_start < ' . NV_CURRENTTIME . ')
					AND (ps.date_end = 0
					   OR ps.date_end > ' . NV_CURRENTTIME . '))
			ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) special,';

// truy vấn lấy số điểm theo nhóm
$reward = '(SELECT points
			FROM ' . TABLE_PRODUCT_NAME . '_product_reward pr
			WHERE pr.product_id = p.product_id
				AND customer_group_id = ' . intval( $ProductGeneral->config['config_customer_group_id'] ) . ') reward,';

// truy vấn lấy tình trạng hàng
$stock_status = '(SELECT ss.name
			FROM ' . TABLE_PRODUCT_NAME . '_stock_status ss
			WHERE ss.stock_status_id = p.stock_status_id
				AND ss.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' ) stock_status,';

// kết nối truy vấn sử dụng các biến trên nếu dùng
$select = $special . $stock_status;

$db->select( 'p.product_id, p.category_id, p.date_added, pd.name, pd.alias, p.image, p.thumb, p.model, p.quantity, p.price, p.units_id, p.tax_class_id, p.stock_status_id, p.units_id, p.showprice, ' . $select . ' ct.newday' )->join( 'INNER JOIN ' . TABLE_PRODUCT_NAME . '_category ct ON ct.category_id = p.category_id' )->order( $orderby )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

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

	$data_content[] = $rows;
}

if( empty( $data_content ) and $page > 1 )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$html_pages = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );

$contents = call_user_func( 'brand_page_gird', $getBrand[$brand_id], $data_content, $html_pages, $sorts );

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	$description .= ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
$productCategory = null;
$global_product_group = null;
$global_stock_status = null;
$global_brand = null;
$global_customer_group = null;
$ProductGeneral->config = null;
$shops_cart = null;
$shops_coupon = null;
$ProductTax = null;
$ProductContent = null;
$data_content = null;
$ProductCurrency = null;
$ProductGeneral = null;
include NV_ROOTDIR . '/includes/footer.php';
