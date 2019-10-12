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

$order_id = $nv_Request->get_string( 'order_id', 'get', '' );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );

if( $order_id > 0 and $checkss == md5( $order_id . $global_config['sitekey'] . session_id() ) )
{
	TABLE_PRODUCT_NAME = $db_config['prefix'] . "_" . $module_data . "_orders";
	$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=";

	$result = $db->query( "SELECT * FROM " . TABLE_PRODUCT_NAME . " WHERE order_id=" . $order_id );
	$data = $result->fetch();

	if( empty( $data ) )
	{
		Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
		exit();
	}

	$listid = explode( "|", $data['listid'] );
	$listnum = explode( "|", $data['listnum'] );
	$listprice = explode( "|", $data['listprice'] );
	$data_pro = array();
	$temppro = array();
	$i = 0;

	foreach( $listid as $proid )
	{
		if( empty( $listprice[$i] ) ) $listprice[$i] = 0;
		if( empty( $listnum[$i] ) ) $listnum[$i] = 0;

		$temppro[$proid] = array( "price" => $listprice[$i], "num" => $listnum[$i] );

		$arrayid[] = $proid;
		++$i;
	}

	if( ! empty( $arrayid ) )
	{
		$templistid = implode( ",", $arrayid );

		$sql = "SELECT t1.id, t1.listcatid, t1.publtime, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t1." . NV_LANG_DATA . "_hometext, t2." . NV_LANG_DATA . "_title, t1.money_unit FROM " . $db_config['prefix'] . "_" . $module_data . "_rows as t1 LEFT JOIN " . $db_config['prefix'] . "_" . $module_data . "_units as t2 ON t1.product_unit = t2.id WHERE t1.id IN (" . $templistid . ") AND t1.status =1";
		$result = $db->query( $sql );

		while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $unit, $money_unit ) = $result->fetch( 3 ) )
		{
			$data_pro[] = array(
				"id" => $id,
				"publtime" => $publtime,
				"title" => $title,
				"alias" => $alias,
				"hometext" => $hometext,
				"product_price" => $temppro[$id]['price'],
				"product_unit" => $unit,
				"money_unit" => $money_unit,
				"link_pro" => $link . $productCategory[$listcatid]['alias'] . "/" . $alias . $global_config['rewrite_exturl'],
				"product_number" => $temppro[$id]['num']
			);
		}
	}

    $page_title = $data['order_code'];
	$contents = call_user_func( "print_pay", $data, $data_pro );

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
}
else
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}