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

$id = $nv_Request->get_int( 'id', 'get,post', 0 );

$result = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE id = " . $id );
$data_content = $result->fetch();

if( empty( $data_content ) )
{
	include NV_ROOTDIR . '/includes/header.php';
	echo "Error Access!!!";
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

$catid = $data_content['listcatid'];

$result = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_units WHERE id = " . $data_content['product_unit'] );
$data_unit = $result->fetch();
$data_unit['title'] = $data_unit[NV_LANG_DATA . '_title'];

$image = $data_content['image'];
if( $data_content['thumb'] == 1 )//image thumb
{
	$data_content['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $image;
	$data_content['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $image;
}
elseif( $data_content['thumb'] == 2 )//image file
{
	$data_content['thumb'] = $data_content['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $image;
}
elseif( $data_content['thumb'] == 3 )//image url
{
	$data_content['thumb'] = $data_content['image'] = $image;
}
else//no image
{
	$data_content['thumb'] = $data_content['image'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_name . "/no-image.jpg";
}

$page_title = $data_content[NV_LANG_DATA . '_title'];

$contents = print_product( $data_content, $data_unit, $page_title );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents, false );
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