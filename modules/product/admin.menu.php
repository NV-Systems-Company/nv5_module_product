<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Nuke.vn. All rights reserved
 * @website https://nuke.vn
 * @License GNU/GPL version 3 or any later version
 * @Createdate Wed, 24 Aug 2016 02:00:00 GMT
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main', 'alias', 'items', 'setting', 'product', 'custom_form', 'keywords', 
'category', 'del_category', 'block', 'block_cat', 'stock_status', 'order_status', 'brand',
'list_block', 'units', 'delunit', 'order', 'order_info', 'order_history', 'order_add','order_edit', 'currency', 'shipping', 
'language', 'payment', 'length_class', 'tax_rate', 'tax_class', 'view', 'tags', 'review', 'review', 'weight_class',
'coupon', 'voucher', 'voucher_theme', 'option', 'country', 'geo_zone', 'zone', 'total', 'information', 'help', 
'attribute', 'attribute_group', 'filter', 'customer', 'customer_group', 'faq', 'store' );

$submenu['order'] = 'Quản lý đơn hàng';


// $submenu['items'] = $lang_module['items'];
// $submenu['product'] = $lang_module['product_add'];

 
$menu_product['product'] = 'Thêm sản phẩm';
$submenu['items'] = array( 'title' => 'Quản lý sản phẩm', 'submenu' => $menu_product );
 
$menu_extension['shipping'] ='Vận chuyển';
$menu_extension['payment'] ='Thanh toán';
$menu_extension['total'] ='Quản lý tính giá';
$submenu['rml=removelink1'] = array( 'title' => 'Quản lý Tiện ích', 'submenu' => $menu_extension );

$menu_marketing['coupon'] = 'Mã giảm giá';
$menu_marketing['voucher'] = 'Phí mua hàng giảm giá Voucher';
$menu_marketing['voucher_theme'] = 'Mẫu phiếu mua hàng giảm giá';
$submenu['rml=removelink2'] = array( 'title' => 'Quản lý tiếp thị', 'submenu' => $menu_marketing );


$menu_category['category'] ='Danh mục sản phẩm';
$menu_category['block_cat'] ='Block sản phẩm';
$menu_category['units'] ='Đơn vị sản phẩm';
$menu_category['option'] ='Tùy chọn sản phẩm';
$menu_category['attribute'] ='Thuộc tính sản phẩm';
$menu_category['attribute_group'] ='Nhóm thuộc tính sản phẩm';
$menu_category['filter'] ='Bộ lọc';
$menu_category['information'] ='Bài viết';
$submenu['rml=removelink3'] = array( 'title' => 'Quản lý danh mục', 'submenu' => $menu_category );

$menu_system['currency'] ='Tiền tệ';
$menu_system['block_cat'] ='Block sản phẩm';
$menu_system['units'] ='Đơn vị sản phẩm';
$menu_system['option'] ='Tùy chọn sản phẩm';
$menu_system['attribute'] ='Thuộc tính sản phẩm';
$menu_system['attribute_group'] ='Nhóm thuộc tính sản phẩm';
$menu_system['filter'] ='Bộ lọc';
$menu_system['information'] ='Bài viết';
$submenu['rml=removelink4'] = array( 'title' => 'Quản lý hệ thống', 'submenu' => $menu_system );


$submenu['rml=removelink4'] = array( 'title' => 'Trợ giúp', 'submenu' => $menu_system );

 



// $submenu['category'] = $lang_module['menu_category'];
// $submenu['brand'] = $lang_module['menu_brand'];
// $submenu['customer'] = $lang_module['customer'];
// $submenu['customer_group'] = $lang_module['customer_group'];
// $submenu['review'] = $lang_module['menu_review'];
// $submenu['faq'] = $lang_module['menu_faq'];
// $submenu['setting'] = $lang_module['menu_setting'];