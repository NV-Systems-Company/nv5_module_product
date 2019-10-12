<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Nuke.vn. All rights reserved
 * @website https://nuke.vn
 * @License GNU/GPL version 3 or any later version
 * @Createdate Wed, 24 Aug 2016 02:00:00 GMT
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	'name' => 'Product',
	'modfuncs' => 'main, viewcat,detail,search,cart,checkout,payment,complete,history,search_result,compare, wishlist,tag,info,brand,faq,hotdeal',  
	'is_sysmod' => 0, 
	'virtual' => 1, 
	'version' => '4.0.29', 
	'date' => 'Wed, 24 Aug 2016 02:00:00 GMT', 
	'author' => 'DANGDINHTU (dlinhvan@gmail.com)', 
	'note' => '',
	'uploads_dir' => array( 
		$module_name, $module_name . '/temp_pic', 
		$module_name . '/brand', 
		$module_name . '/color', 
		$module_name . '/category', 
		$module_name . '/voucher', 
		$module_name . '/' . date( 'Y_m' ) )
);