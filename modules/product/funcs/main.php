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

$dataContent = array();

$orderby = ' p.price DESC, p.product_id DESC ';

$getHomeView = $ProductGeneral->config['config_home_view'];

if( $getHomeView == 'view_home_all' )
{
	$from = TABLE_PRODUCT_NAME . '_product p LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id)';

	// Fetch Limit
	$db->sqlreset()->select( 'COUNT(*)' )->from( $from )->where( 'p.status =1 AND pd.language_id = ' . $ProductGeneral->current_language_id );

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

		$dataContent[] = $rows;
	}
	$result->closeCursor(); unset( $result );
	

	if( empty( $dataContent ) and $page > 1 )
	{
		Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
		exit();
	}

	$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
	$html_pages = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
}
elseif( $getHomeView == 'view_home_category' )
{
	// $cache_file = NV_CACHE_PREFIX . '.product_category.' . NV_LANG_DATA . '.cache';
	// if( ( $cache = $nv_Cache->getItem( $module_name, $cache_file ) ) != false )
	// {
		// $contents = unserialize( $cache );
	// }
	// else
	// {
	
		foreach( $productCategory as $_category_id => $item )
		{
			if( $item['parent_id'] == 0 and $item['inhome'] != 0 )
			{
	 
				$categoryListId = getParentInCat( $_category_id, 1);
	 
				$from = TABLE_PRODUCT_NAME . '_product p LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id)';

				// Fetch Limit
				$db->sqlreset()->select( 'COUNT(*)' )->from( $from )->where( 'p.category_id IN (' . implode( ',', $categoryListId ) . ') AND p.status =1 AND pd.language_id=' . intval( $ProductGeneral->current_language_id ) );

				$product_total = $db->query( $db->sql() )->fetchColumn();

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

				$db->select( 'p.product_id, p.category_id, p.date_added, pd.name, pd.alias, p.image, p.thumb, p.model, p.quantity, p.price, p.tax_class_id, p.stock_status_id, p.units_id, p.showprice, ' . $select . ' ct.newday' )
					->join( 'INNER JOIN ' . TABLE_PRODUCT_NAME . '_category ct ON ct.category_id = p.category_id' )
					->order( 'p.product_id DESC' )
					->limit( $item['numlinks'] );
	 
				$result = $db->query( $db->sql() );

				$data = array();

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

					$data[] = $rows;
				}
				$result->closeCursor();
	 
				$dataContent[] = array(
					'category_id' => $_category_id,
					'name' => $item['name'],
					'link' => $item['link'],
					'content' => $data,
					'product_total' => $product_total,
					'category_link' => $item['numlinks'] );
				unset( $categoryListId );
			}
			 
		}
		
		$contents = ThemeProductViewByCategory( $dataContent );
	
		// $cache = serialize( $contents );
		
		// $nv_Cache->setItem( $module_name, $cache_file, $cache );
		
	// }
 
	if( $page > 1 )
	{
		Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
		exit();
	}
	
	
}
elseif( $getHomeView == 'view_home_group' )
{
	$num_links = $ProductGeneral->config['config_per_row'] * 3;

	foreach( $global_product_group as $groupid_i => $item )
	{
		if( $item['parent_id'] == 0 and $item['inhome'] != 0 )
		{
			$array_group = array();
			$array_group = GetGroupidInParent( $groupid_i, true );

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
			$select = $special . $stock_status;

			$sql_regexp = array();
			foreach( $array_group as $_gid )
			{
				$sql_regexp[] = "( gr.group_id='" . $_gid . "' )";
			}
			$sql_regexp = "(" . implode( " OR ", $sql_regexp ) . ")";

			// Fetch Limit
			$db->sqlreset()->select( 'DISTINCT p.product_id' )->from( $from )->join( 'INNER JOIN ' . TABLE_PRODUCT_NAME . '_items_group gr ON gr.product_id = p.product_id' )->where( $sql_regexp . ' AND p.status =1 AND pd.language_id = ' . $ProductGeneral->current_language_id );

			$num_pro = $db->query( $db->sql() )->rowCount();

			$db->select( 'DISTINCT p.product_id, p.category_id, p.date_added, pd.name, pd.alias,  p.image, p.thumb, p.model, p.quantity, p.price, p.tax_class_id, p.stock_status_id, p.units_id, p.showprice, ' . $select . ' ct.newday' )->from( $from )->join( 'INNER JOIN ' . TABLE_PRODUCT_NAME . '_category ct ON ct.category_id = p.category_id INNER JOIN ' . TABLE_PRODUCT_NAME . '_items_group gr ON gr.product_id = p.product_id' )->order( 'p.product_id DESC' )->limit( $num_links );

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
				'group_id' => $groupid_i,
				'name' => $item['name'],
				'link' => $item['link'],
				'data' => $data_pro,
				'num_pro' => $num_pro,
				'num_link' => $num_links );
		}
	}

	if( $page > 1 )
	{
		Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
		exit();
	}
}
else
{
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( '' );
	include NV_ROOTDIR . '/includes/footer.php';
}



if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	$description .= ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
