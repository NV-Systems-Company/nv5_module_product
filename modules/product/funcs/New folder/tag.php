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

$alias = $nv_Request->get_title( 'alias', 'get' );
$array_op = explode( '/', $alias );
$alias = $array_op[0];

if( isset( $array_op[1] ) )
{
	if( sizeof( $array_op ) == 2 and preg_match( '/^page\-([0-9]+)$/', $array_op[1], $m ) )
	{
		$page = intval( $m[1] );
	}
	else
	{
		$alias = '';
	}
}
$page_title = trim( str_replace( '-', ' ', $alias ) );

if( ! empty( $page_title ) and $page_title == strip_punctuation( $page_title ) )
{
	$stmt = $db->prepare( 'SELECT tid, ' . NV_LANG_DATA . '_image, ' . NV_LANG_DATA . '_description, ' . NV_LANG_DATA . '_keywords FROM ' . $db_config['prefix'] . '_' . $module_data . '_tags WHERE ' . NV_LANG_DATA . '_alias= :alias' );
	$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
	$stmt->execute();
	list( $tid, $image_tag, $description, $key_words ) = $stmt->fetch( 3 );

	if( $tid > 0 )
	{
		// Fetch Limit
		$db->sqlreset()
			->select( 'COUNT(*)' )
			->from( $db_config['prefix'] . '_' . $module_data . '_rows t1' )
			->where( 'status=1 AND id IN (SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_tags_id WHERE tid=' . $tid . ')' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 't1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.image, t1.thumb, t1.model, t1.product_number, t1.product_price, t1.money_unit, t1.discount_id, t1.showprice, t2.newday' )
			->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_category t2 ON t2.catid = t1.listcatid' )
			->limit( $per_page )
			->offset( ( $page - 1 ) * $per_page );

		$result = $db->query( $db->sql() );
		
		while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgalt, $image, $thumb, $model, $product_number, $product_price, $money_unit, $discount_id, $showprice, $newday ) = $result->fetch( 3 ) )
		{
			if( $thumb == 1 )//image thumb
			{
				$thumb = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $image;
			}
			elseif( $thumb == 2 )//image file
			{
				$thumb = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $image;
			}
			elseif( $thumb == 3 )//image url
			{
				$thumb = $image;
			}
			else//no image
			{
				$thumb = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
			}
		
			$data_content[] = array(
				'id' => $id,
				'publtime' => $publtime,
				'title' => $title,
				'alias' => $alias,
				'hometext' => $hometext,
				'homeimgalt' => $homeimgalt,
				'thumb' => $thumb,
				'product_price' => $product_price,
				'model' => $model,
				'product_number' => $product_number,
				'discount_id' => $discount_id,
				'money_unit' => $money_unit,
				'showprice' => $showprice,
				'newday' => $newday,
				'link_pro' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$listcatid]['alias'] . '/' . $alias .  $global_config['rewrite_exturl'],
				'link_order' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart&amp;id=' . $id
			);
		}

		if( empty( $data_content ) and $page > 1 )
		{
			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
			exit();
		}

		if( ! empty( $image_tag ) )
		{
			$image_tag = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/topics/' . $image_tag;
		}

		$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=wishlist';
		$html_pages = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
		
		$contents = call_user_func( 'view_home_all', $data_content, $html_pages );

		include NV_ROOTDIR . '/includes/header.php';
		echo nv_site_theme( $contents );
		$productCategory = null;
		$global_product_group = null;
		$global_stock_status = null;
		$global_brand = null;
		$global_customer_group = null;
		$ProductGeneral->config = null;
		$shops_cart= null;
		$shops_coupon= null;
		$ProductTax = null;
		$ProductContent= null;
		$data_content = null;
		$ProductCurrency = null;
		$ProductGeneral= null;
		include NV_ROOTDIR . '/includes/footer.php';
	}
}

$redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . '" />';
nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect );