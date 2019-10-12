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

$alias_url = isset( $array_op[0] ) ? $array_op[0] : '';

if( sizeof( $array_op ) == 2 and ! preg_match( '/^page\-([0-9]+)$/', $array_op[1], $m ) )
{
	$alias_url = preg_replace( '/^(.*?)\-([0-9]+)$/', '${1}', $array_op[1] );
}

$data = array();
$getInformation = getInformation();
foreach( $getInformation as $information_id => $value )
{
	if( $value['alias'] == $alias_url )
	{
		$data = $value;
	}
}
if( $data )
{
	$page_title = ! empty( $data['meta_title'] ) ? $data['meta_title'] : $data['name'];
	$description = $data['meta_description'];
	$key_words = $data['meta_keyword'];

}
else
{
	$nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
	redict_link( $lang_module['detail_no_permission'], $lang_module['redirect_to_back_shops'], $nv_redirect );
}

$contents = infomation_theme( $data );

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
