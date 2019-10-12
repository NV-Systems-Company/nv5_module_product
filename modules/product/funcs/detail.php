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

if( empty( $alias_url ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

// if( empty( $ProductTax ) ) $ProductTax = new NukeViet\Product\Tax( $productRegistry );
if( empty( $ProductContent ) ) $ProductContent = new NukeViet\Product\Product( $productRegistry );

// Thiet lap quyen xem chi tiet
$from = TABLE_PRODUCT_NAME . '_product p LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id)';

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

$special_date_start = '(SELECT date_start
			FROM ' . TABLE_PRODUCT_NAME . '_product_special ps
			WHERE ps.product_id = p.product_id
				AND ps.customer_group_id = ' . intval( $ProductGeneral->config['config_customer_group_id'] ) . '
				AND ((ps.date_start = 0
					OR ps.date_start < ' . NV_CURRENTTIME . ')
					AND (ps.date_end = 0
					   OR ps.date_end > ' . NV_CURRENTTIME . '))
			ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) special_date_start,';

$special_date_end = '(SELECT date_end
			FROM ' . TABLE_PRODUCT_NAME . '_product_special ps
			WHERE ps.product_id = p.product_id
				AND ps.customer_group_id = ' . intval( $ProductGeneral->config['config_customer_group_id'] ) . '
				AND ((ps.date_start = 0
					OR ps.date_start < ' . NV_CURRENTTIME . ')
					AND (ps.date_end = 0
					   OR ps.date_end > ' . NV_CURRENTTIME . '))
			ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) special_date_end,';

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

$weight_class_description = '(SELECT wcd.unit FROM ' . TABLE_PRODUCT_NAME . '_weight_class_description wcd 
					WHERE p.weight_class_id = wcd.weight_class_id 
						AND wcd.language_id = ' . ( int )$ProductGeneral->current_language_id . ') weight_class,';

$length_class_description = '(SELECT lcd.unit FROM ' . TABLE_PRODUCT_NAME . '_length_class_description lcd 
					WHERE p.length_class_id = lcd.length_class_id 
						AND lcd.language_id = ' . ( int )$ProductGeneral->current_language_id . ') length_class,';

$review_rating = '(SELECT AVG(rating) total FROM ' . TABLE_PRODUCT_NAME . '_product_review r1 
					WHERE r1.product_id = p.product_id 
						AND r1.status = 1 
					GROUP BY r1.product_id) review_rating,';

$reviews_total = '(SELECT COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_product_review r2 
					WHERE r2.product_id = p.product_id 
						AND r2.status = 1 
					GROUP BY r2.product_id) reviews_total,';

// kết nối truy vấn sử dụng các biến trên nếu dùng
$select = $discount . $special . $special_date_start . $special_date_end . $reward . $stock_status . $weight_class_description . $length_class_description . $review_rating . $reviews_total;

$query = $db->query( 'SELECT p.*, ' . $select . ' pd.* FROM ' . $from . ' WHERE pd.alias = ' . $db->quote( $alias_url ) . ' AND pd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' AND status=1' );

$dataContent = $query->fetch();

if( empty( $dataContent ) )
{
	$nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
	redict_link( $lang_module['detail_do_not_view'], $lang_module['redirect_to_back_shops'], $nv_redirect );
}

$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$category_id]['alias'] . '/' . $dataContent['alias'] . $global_config['rewrite_exturl'], true );

if( $_SERVER['REQUEST_URI'] != $base_url_rewrite )
{
	Header( 'Location: ' . $base_url_rewrite );
	die();
}
 
unset( $result, $discount, $special, $special_date_start, $special_date_end, $reward, $stock_status, $weight_class_description, $length_class_description, $review_rating, $reviews_total);
 
$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product SET viewed=viewed+1 WHERE product_id=' . $dataContent['product_id'] );

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global/product.php';
 
/*BEGIN STOCK*/
if( $dataContent['quantity'] <= 0 )
{
	$dataContent['stock'] = $dataContent['stock_status'];

}
elseif( $ProductGeneral->config['config_stock_display'] )
{
	$dataContent['stock'] = $dataContent['quantity'];

}
else
{
	$dataContent['stock'] = $lang_module['product_instock'];
}
/*END STOCK*/

/* BEGIN OTHERIMAGE */
$dataContent['images'] = getProductImages( $dataContent['product_id'] );
$dataContent['images'] = array_unique ( array_merge( array( $dataContent['image'] ) , $dataContent['images'] ) );
/* END OTHERIMAGE */

/* BEGIN IMAGE */
if( $dataContent['thumb'] == 1 ) 
{
	$dataContent['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $dataContent['image'];
	$dataContent['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $dataContent['image'];
}
elseif( $dataContent['thumb'] == 2 )
{
	$dataContent['thumb'] = $dataContent['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $dataContent['image'];
}
elseif( $dataContent['thumb'] == 3 ) 
{
	$dataContent['thumb'] = $dataContent['image'] = $dataContent['image'];
}
else  
{
	$dataContent['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
}

$meta_property['og:image'] = NV_MY_DOMAIN . $dataContent['image'];
/* END IMAGE */
 
// lay gia khuyen mai discount
$result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_discount 
							WHERE product_id = ' . intval( $dataContent['product_id'] ) . ' 
								AND customer_group_id = ' . intval( $ProductGeneral->config['config_customer_group_id'] ) . ' 
								AND quantity > 1 
								AND ((date_start = 0 OR date_start < ' . NV_CURRENTTIME . ') 
								AND (date_end = 0 OR date_end > ' . NV_CURRENTTIME . ')) 
							ORDER BY quantity ASC, priority ASC, price ASC' );

$dataContent['discounts'] = array();

while( $discount = $result->fetch() )
{
	$dataContent['discounts'][] = array( 'quantity' => $discount['quantity'], 'price' => $ProductCurrency->format( $ProductTax->calculate( $discount['price'], $dataContent['tax_class_id'], $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $module_data . '_currency', 'session' ) ) );
}
$result->closeCursor();
unset( $result, $discount );
 
$dataContent['product_question'] = getQuestionByProductId( $dataContent['product_id'], $start = 0, $limit = 20 );

$dataContent['attribute_groups'] = getProductAttributes( $dataContent['product_id'] );
 
// lay option
$dataContent['options'] = array();

foreach( $ProductContent->getProductOptions( $dataContent['product_id'] ) as $option )
{
	$product_option_value_data = array();

	foreach( $option['product_option_value'] as $option_value )
	{
		if( ! $option_value['subtract'] || ( $option_value['quantity'] > 0 ) )
		{
			if( ! $ProductGeneral->config['config_customer_price'] && ( float )$option_value['price'] )
			{
				$price = $ProductCurrency->format( $ProductTax->calculate( $option_value['price'], $dataContent['tax_class_id'], $ProductGeneral->config['config_tax'] ? 'P' : false ), $nv_Request->get_string( $module_data . '_currency', 'session' ), true, false );
				$price_sale = $ProductCurrency->format( $ProductTax->calculate( $option_value['price'], $dataContent['tax_class_id'], $ProductGeneral->config['config_tax'] ? 'P' : false ), $nv_Request->get_string( $module_data . '_currency', 'session' )  );
				
			}
			else
			{
				$price = false;
				$price_sale = false;
			}
			if( ! empty( $option_value['image'] ) )
			{
				$option_value['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $option_value['image'];
			}
			$product_option_value_data[] = array(
				'product_option_value_id' => $option_value['product_option_value_id'],
				'option_value_id' => $option_value['option_value_id'],
				'name' => $option_value['name'],
				'image' => $option_value['image'],
				'quantity' => $option_value['quantity'],
				'price' => $price,
				'price_sale' => $price_sale,
				'price_prefix' => $option_value['price_prefix'] );
		}
	}

	$dataContent['options'][] = array(
		'product_option_id' => $option['product_option_id'],
		'product_option_value' => $product_option_value_data,
		'option_id' => $option['option_id'],
		'name' => $option['name'],
		'type' => $option['type'],
		'value' => $option['value'],
		'required' => $option['required'] );
}

// lấy thông tin nhà cung cấp
$brand_info = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_brand m
	LEFT JOIN ' . TABLE_PRODUCT_NAME . '_brand_description md ON (m.brand_id = md.brand_id)
	WHERE m.brand_id = ' . intval( $dataContent['brand_id'] ) . ' AND md.language_id=' . $ProductGeneral->current_language_id )->fetch();

if( $brand_info )
{
	$brand_info['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=brand/' . $brand_info['alias'] . $global_config['rewrite_exturl'];	
}else{
	$brand_info['link'] = 'javascript:void(0);';	
	$brand_info['name'] = 'N/A';	

}
$dataContent['brand'] = $brand_info;

// lay thong tin danh gia san pham

$dataContent['data_reviews'] = getReviewsByProductId( $dataContent['product_id'], 0, 5 );
$dataContent['data_reviews_rating'] = getReviewsRatingByProductId( $dataContent['product_id'] );
 
$from = TABLE_PRODUCT_NAME . '_product p LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id)';
 
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
$select = $discount . $special . $reward . $stock_status;

$db->select( 'p.product_id, p.category_id, p.date_added, pd.name, pd.alias, p.image, p.thumb, p.model, p.quantity, p.price, p.units_id, p.tax_class_id, p.stock_status_id, p.units_id, p.showprice, ' . $select . ' ct.newday' )->from( $from )->join( 'INNER JOIN ' . TABLE_PRODUCT_NAME . '_category ct ON ct.category_id = p.category_id' )->where( 'p.product_id !=' . $dataContent['product_id'] . ' AND p.category_id = ' . $dataContent['category_id'] . ' AND pd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' AND p.status=1' )->order( 'p.product_id DESC' )->limit( $ProductGeneral->config['config_per_row'] * 2 );
$result = $db->query( $db->sql() );
// san pham khac
$dataOthersProducts = array();
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

	$rows['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$rows['category_id']]['alias'] . '/' . $rows['alias'] . $global_config['rewrite_exturl'];
	$rows['link_order'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart&amp;product_id=' . $rows['product_id'];

	$dataOthersProducts[] = $rows;
}
$result->closeCursor();
unset( $result, $select, $discount, $special, $reward, $stock_status );

// san pham vua xem
$recentlyViewedProducts = array();
if( ! empty( $_SESSION[$module_data . '_proview'] ) )
{
	$arrid = array();
	foreach( $_SESSION[$module_data . '_proview'] as $id_i => $data_i )
	{
		if( $id_i != $dataContent['product_id'] )
		{
			$arrid[] = $id_i;
		}
	}
	$arrtempid = implode( ',', $arrid );
	if( ! empty( $arrtempid ) )
	{

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

		// Fetch Limit
		$db->select( 'p.product_id, p.category_id, p.date_added, pd.name, pd.alias, p.image, p.thumb, p.model, p.quantity, p.price, p.units_id, p.tax_class_id, p.stock_status_id, p.units_id, p.showprice, ' . $select . ' ct.newday' )->from( $from )->join( 'INNER JOIN ' . TABLE_PRODUCT_NAME . '_category ct ON ct.category_id = p.category_id' )->where( 'p.product_id IN ( ' . $arrtempid . ') AND pd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' AND p.status=1' )->order( 'p.product_id DESC' )->limit( $ProductGeneral->config['config_per_row'] * 2 );
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

			$rows['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$rows['category_id']]['alias'] . '/' . $rows['alias'] . $global_config['rewrite_exturl'];
			$rows['link_order'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart&amp;product_id=' . $rows['product_id'];

			$recentlyViewedProducts[] = $rows;
		}

	}
}

// SetSessionProView( $dataContent['product_id'], $dataContent['name'], $dataContent['alias'], $dataContent['addtime'], NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $productCategory[$category_id]['alias'] . '/' . $dataContent['alias'] . '-' . $dataContent['product_id'], $dataContent['thumb'] );

$page_title = ! empty( $dataContent['meta_title'] ) ? $dataContent['meta_title'] : $dataContent['name'];
$description = ! empty( $dataContent['meta_description'] ) ? $dataContent['meta_description'] : $dataContent['name'];
// $key_words = $dataContent['meta_keyword'];

$contents = ThemeProductViewDetail( $dataContent, $dataOthersProducts, $recentlyViewedProducts );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
