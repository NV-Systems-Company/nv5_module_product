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

$page = 1;
$product_alias = '';
$faq_id = 0;
$sizeof = sizeof( $array_op ) ;
if( $sizeof == 2 || $sizeof == 3 )
{
	$product_alias = preg_replace( '/^(.*?)\-([0-9]+)$/', '${1}', $array_op[1] );
	$faq_id = preg_replace( '/^(.*?)\-([0-9]+)$/', '${2}', $array_op[1] );	
	
	if( sizeof( $array_op ) == 3 )
	{
		if( preg_match( '/^page\-([0-9]+)$/', ( isset( $array_op[2] ) ? $array_op[2] : '' ), $m ) )
		{
			$page = ( int )$m[1];
		}
	}
 
}
 
 // kiem tra lien ket hop le
$stmt = $db->prepare( 'SELECT COUNT(*) FROM '. TABLE_PRODUCT_NAME .'_product_faq pq LEFT JOIN '. TABLE_PRODUCT_NAME .'_product_description pd ON ( pq.product_id = pd.product_id ) WHERE pd.alias=:alias AND pq.parent_id = 0 AND pq.faq_id = ' . intval( $faq_id ) );
$stmt->bindParam( ':alias', $product_alias, PDO::PARAM_STR ); 
$stmt->execute(); 
$check_exist = $stmt->fetchColumn();
$stmt->closeCursor();
if( empty( $check_exist ) )
{
	Header( 'HTTP/1.1 301 Moved Permanently' ); 
	Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$data = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_faq WHERE parent_id = 0 AND faq_id=' . intval( $faq_id ) )->fetch();

// Fetch Limit
$db->sqlreset()
	->select( 'COUNT(*)' )
	->from( TABLE_PRODUCT_NAME .'_product_faq' )
	->where( 'parent_id=' . intval( $data['faq_id'] ) );

$num_pro = $db->query( $db->sql() )->fetchColumn();

$db->select( '*' )
	->order( 'date_added DESC' )
	->limit( $per_page )
	->offset( ( $page - 1 ) * $per_page );
$result = $db->query( $db->sql() );
 
$data_answer = array();

while( $rows = $result->fetch() )
{
	$data_answer[] = $rows;
}
  
if( $data )
{
	$page_title = ! empty( $data['question'] ) ? $data['question'] : $data['question'];
	$description = '';
	$key_words = '';

}
else
{
	$nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
	redict_link( $lang_module['detail_no_permission'], $lang_module['redirect_to_back_shops'], $nv_redirect );
}

$contents = theme_answer_list( $data, $data_answer ); 

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
