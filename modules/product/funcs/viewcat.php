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

if( empty( $category_id ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

$module_info['layout_funcs'][$op_file] = ! empty( $productCategory[$category_id]['layout'] ) ? $productCategory[$category_id]['layout'] : $module_info['layout_funcs'][$op_file];

$page_title = ! empty( $productCategory[$category_id]['meta_title'] ) ? $productCategory[$category_id]['meta_title'] : $productCategory[$category_id]['name'];

$key_words = $productCategory[$category_id]['meta_keyword'];

$description = ! empty( $productCategory[$category_id]['meta_description'] ) ? $productCategory[$category_id]['meta_description'] : $productCategory[$category_id]['description'];

if( $productCategory[$category_id]['viewcat'] == 'view_home_category' )
{
	$getCatView = 'ThemeProductViewByCategory';
}
elseif( $productCategory[$category_id]['viewcat'] == 'viewcat_page_grid' )
{
	$getCatView = 'ThemeProductViewGrid';
}
elseif( $productCategory[$category_id]['viewcat'] == 'viewcat_page_list' )
{
	$getCatView = 'ThemeProductViewList';
}
 
$dataContent = array();

$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$category_id]['alias'];

$orderby = 'product_id DESC ';

if( $getCatView == 'view_home_category' and $productCategory[$category_id]['numsubcat'] > 0 )
{

	$array_subcatid = explode( ',', $productCategory[$category_id]['subcatid'] );

	foreach( $array_subcatid as $category_id_i )
	{
		$array_info_i = $productCategory[$category_id_i];

		$array_cat = array();
		$array_cat = getParentInCat( $category_id_i );

		// Fetch Limit
		$db->sqlreset()->select( 'COUNT(*)' )->from( $from )->where( 'p.category_id IN (' . implode( ',', $array_cat ) . ') AND p.status =1 AND pd.language_id=' . $ProductGeneral->current_language_id );

		$num_pro = $db->query( $db->sql() )->fetchColumn();

		$db->select( 'p.product_id, p.category_id, p.date_added, pd.name, pd.alias, p.image, p.thumb, p.model, p.quantity, p.price, p.tax_class_id, p.stock_status_id, p.units_id, p.showprice, ' . $select . ' ct.newday' )->join( 'INNER JOIN ' . TABLE_PRODUCT_NAME . '_category ct ON ct.category_id = p.category_id' )->order( $orderby )->limit( $array_info_i['numlinks'] );
		$result = $db->query( $db->sql() );

		$data_pro = array();

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

			$data_pro[] = $rows;
		}

		$dataContent[] = array(
			'category_id' => $category_id_i,
			'name' => $array_info_i['name'],
			'link' => $array_info_i['link'],
			'data' => $data_pro,
			'num_pro' => $num_pro,
			'num_link' => $array_info_i['numlinks'],
			'image' => $array_info_i['image'] );
	}

	if( $page > 1 )
	{
		Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
		exit();
	}

	$contents = call_user_func( 'view_home_category', $dataContent );
}
else
{
	// Fetch Limit
	if( $productCategory[$category_id]['numsubcat'] == 0 )
	{
		$where = ' p.category_id=' . $category_id;
	}
	else
	{
		$categoryListId = getParentInCat( $category_id );

		$where = ' p.category_id IN (' . implode( ',', $categoryListId ) . ')';
	}

	$from = TABLE_PRODUCT_NAME . '_product p LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id)';

	// Fetch Limit
	$db->sqlreset()->select( 'COUNT(*)' )->from( $from )->where( $where . ' AND p.status =1 AND pd.language_id=' . $ProductGeneral->current_language_id );

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

	$db->select( 'p.product_id, p.category_id, p.date_added, pd.name, pd.alias, p.image, p.thumb, p.model, p.quantity, p.price, p.tax_class_id, p.stock_status_id, p.units_id, p.showprice, ' . $select . ' ct.newday' )->join( 'INNER JOIN ' . TABLE_PRODUCT_NAME . '_category ct ON ct.category_id = p.category_id' )->order( $orderby )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

	$result = $db->query( $db->sql() );
 
	while( $rows = $result->fetch() )
	{
		if( $rows['thumb'] == 1 )
		{
			$rows['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $rows['image'];
		}
		elseif( $rows['thumb'] == 2 )
		{
			$rows['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rows['image'];
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

		$dataContent[] = $rows;
	}
	$result->closeCursor();
 
	unset( $categoryListId );

	$generatePage = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );

	if( sizeof( $dataContent ) < 1 and $page > 1 )
	{
		Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
		exit();
	}

	$contents = call_user_func( $getCatView, $dataContent, $generatePage );
}
 
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
