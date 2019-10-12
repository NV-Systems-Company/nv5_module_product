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

if( !defined( 'NV_MAINFILE' ) )	die( 'Stop!!!' );

global $array_menu;

$array_menu = array();

$link = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=';

$array_menu[] = array(
	'title' => $lang_module['menu_extensions'],
	'link' => 'javascript:void(0);',
	'active' => '',
	'sub' => array(  
		array( 
			'title' => $lang_module['menu_shipping'],
			'link' => $link . 'shipping',
			'active' => ( $op =='shipping' ) ? 'active': '',
		),
		array( 
			'title' => $lang_module['menu_payment'],
			'link' => $link . 'payment',
			'active' => ( $op =='payment' ) ? 'active': '',
		), 
		array( 
			'title' => $lang_module['menu_order_totals'],
			'link' => $link . 'total',
			'active' => ( $op =='total' ) ? 'active': '',
		)
	)	
);
$array_menu[] = array(
	'title' => $lang_module['menu_maketing'],
	'link' => 'javascript:void(0);',
	'active' => '',
	'sub' => array(  
		array( 
			'title' => 'Gift Vouchers',
			'link' => $link . 'voucher',
			'active' => ( $op =='voucher' ) ? 'active': '',
		), 
		array( 
			'title' => 'Voucher Theme',
			'link' => $link . 'voucher_theme',
			'active' => ( $op =='voucher_theme' ) ? 'active': '',
		),
		array( 
			'title' => $lang_module['menu_coupons'],
			'link' => $link . 'coupon',
			'active' => ( $op =='coupon' ) ? 'active': '',
		), 
  
	)	
);
 $array_menu[] = array(
	'title' => $lang_module['menu_product'],
	'link' => 'javascript:void(0);',
	'active' => '',
	'sub' => array(  
		array( 
			'title' =>  $lang_module['menu_product_blocks'],
			'link' => $link . 'block_cat',
			'active' => ( $op =='block_cat' ) ? 'active': '',
		),	 
		array( 
			'title' => $lang_module['menu_product_unit'],
			'link' => $link . 'units',
			'active' => ( $op =='units' ) ? 'active': '',
		), 
 		array( 
			'title' => $lang_module['menu_product_option'],
			'link' => $link . 'option',
			'active' => ( $op =='option' ) ? 'active': '',
		), 
 		array( 
			'title' => $lang_module['menu_attribute'],
			'link' => $link . 'attribute',
			'active' => ( $op =='attribute' ) ? 'active': '',
		), 
 		array( 
			'title' => $lang_module['menu_attribute_group'],
			'link' => $link . 'attribute_group',
			'active' => ( $op =='attribute_group' ) ? 'active': '',
		), 
 		array( 
			'title' => $lang_module['menu_filter'],
			'link' => $link . 'filter',
			'active' => ( $op =='filter' ) ? 'active': '',
		), 
 		array( 
			'title' => $lang_module['menu_product_information'],
			'link' => $link . 'information',
			'active' => ( $op =='information' ) ? 'active': '',
		), 
  
	)	
);
 
$array_menu[] = array(
	'title' => $lang_module['menu_language'],
	'link' => $link . 'language',
	'active' => ( $op =='language' ) ? 'active': '',
	'sub' => array(  ),	
);

$array_menu[] = array(
	'title' => $lang_module['menu_currency'],
	'link' => $link . 'currency',
	'active' => ( $op =='currency' ) ? 'active': '',
	'sub' => array(  ),	
);
   
$array_menu[] = array(
	'title' => $lang_module['menu_taxes'],
	'link' => 'javascript:void(0);',
	'active' => '',
	'sub' => array(  
		array( 
			'title' => $lang_module['menu_tax_class'],
			'link' => $link . 'tax_class',
			'active' => ( $op =='tax_class' ) ? 'active': '',
		),
		array( 
			'title' => $lang_module['menu_tax_rates'],
			'link' => $link . 'tax_rate',
			'active' => ( $op =='tax_rate' ) ? 'active': '',
		), 
	)	
);
 
$array_menu[] = array(
	'title' => $lang_module['menu_length_class'],
	'link' => $link . 'length_class',
	'active' => ( $op =='length_class' ) ? 'active': '',
	'sub' => array(  ),	
);

$array_menu[] = array(
	'title' => $lang_module['menu_weight_class'],
	'link' => $link . 'weight_class',
	'active' => ( $op =='weight_class' ) ? 'active': '',
	'sub' => array(  ),	
); 
 
  
$array_menu[] = array(
	'title' => $lang_module['menu_stock_status'],
	'link' => $link . 'stock_status',
	'active' => ( $op =='stock_status' ) ? 'active': '',
	'sub' => array(  ),	
);

$array_menu[] = array(
	'title' => $lang_module['menu_order_status'],
	'link' => $link . 'order_status',
	'active' => ( $op =='order_status' ) ? 'active': '',
	'sub' => array(  ),	
);

$array_menu[] = array(
	'title' => $lang_module['menu_location'],
	'link' => 'javascript:void(0);',
	'active' => '',
	'sub' => array(  
		array( 
			'title' => $lang_module['menu_country'],
			'link' => $link . 'country',
			'active' => ( $op =='country' ) ? 'active': '',
		), 
		array( 
			'title' => $lang_module['menu_zone'],
			'link' => $link . 'zone',
			'active' => ( $op =='zone' ) ? 'active': '',
		),
		array( 
			'title' => $lang_module['menu_geo_zone'],
			'link' => $link . 'geo_zone',
			'active' => ( $op =='geo_zone' ) ? 'active': '',
		), 
  
	)	
);
 
$array_menu[] = array(
	'title' => $lang_module['menu_help'],
	'link' => $link . 'help',
	'active' => ( $op =='help' ) ? 'active': '',
	'sub' => array(  ),	
);

function AddMenu( )
{
	global $global_config, $module_file, $array_menu;
	$xtpl = new XTemplate( 'menu.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	
	foreach( $array_menu as $menu )
	{
		$xtpl->assign( 'CAT', $menu );
		if( !empty( $menu['sub'] ) )
		{
			$xtpl->parse( 'main.cat.checksub' );
			
			foreach( $menu['sub'] as $sub )
			{
				$xtpl->assign( 'SUBCAT', $sub );
				$xtpl->parse( 'main.cat.subcat.loop' );
			}
			$xtpl->parse( 'main.cat.subcat' );
		}
		$xtpl->parse( 'main.cat' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
	 
}
