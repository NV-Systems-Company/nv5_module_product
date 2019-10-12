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

 
$base_url_rewrite = nv_url_rewrite( $_SERVER['REQUEST_URI'], true );
if( $base_url_rewrite != $_SERVER['REQUEST_URI'] )
{
	header( "Location: " . $base_url_rewrite );
	die();
} 
 
$key_words = $module_info['keywords'];
$mod_title = $lang_module['main_title'];

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