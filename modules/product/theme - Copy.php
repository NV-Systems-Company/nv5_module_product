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

 
function view_home_group( $data_content, $html_pages = '', $sort = 0 )
{
	global $ProductCurrency, $productRegistry, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral, $array_wishlist_id;

	$xtpl = new XTemplate( 'main_procate.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$num_view = $ProductGeneral->config['config_per_row'];

	$xtpl->assign( 'CSS_MODEL', ! empty( $ProductGeneral->config['config_show_model'] ) ? ' show-product-code' : '' );

	if( ! empty( $data_content ) )
	{
		if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );

		foreach( $data_content as $data )
		{
			if( $data['num_pro'] > 0 )
			{
				$xtpl->assign( 'TITLE_CATALOG', $data['name'] );
				$xtpl->assign( 'LINK_CATALOG', $data['link'] );
				$xtpl->assign( 'NUM_PRO', $data['num_pro'] );
				$i = 1;
				$num_row = $ProductGeneral->config['config_per_row'] == 3 ? 4 : 3;
				$last = 1;
				foreach( $data['data'] as $data )
				{

					$xtpl->assign( 'LAST', ( $last % 3 == 0 ) ? 'last' : '' );
					$xtpl->assign( 'PRODUCT_ID', $data['product_id'] );
					$xtpl->assign( 'TOKEN', md5( $data['product_id'] . $global_config['sitekey'] . session_id() ) );
					$xtpl->assign( 'LINK', $data['link_pro'] );
					$xtpl->assign( 'TITLE', $data['name'] );
					$xtpl->assign( 'TITLE0', nv_clean60( $data['name'], 40 ) );
					$xtpl->assign( 'IMG_SRC', $data['thumb'] );
					$xtpl->assign( 'IMG_LARGE', creat_thumbs( $data['product_id'], $data['image'], $module_name, 250, 375 ) );
					$xtpl->assign( 'LINK_ORDER', $data['link_order'] );
 
					$xtpl->assign( 'model', $data['model'] );

					// hiện tình trạng hàng theo các cấu hình
					if( $data['quantity'] <= 0 )
					{
						$data['stock'] = $data['stock_status'];

					}
					elseif( $ProductGeneral->config['config_stock_display'] )
					{
						$data['stock'] = $data['quantity'];

					}
					else
					{
						$data['stock'] = $lang_module['product_instock'];
					}

					$xtpl->assign( 'NUM', $num_row );

					if( $ProductGeneral->config['config_active_order'] == '1' )
					{
						if( $data['showprice'] == '1' )
						{
							if( $data['quantity'] > 0 )
							{
								$xtpl->parse( 'main.category.items.order' );
							}
							else
							{
								$xtpl->parse( 'main.category.items.product_empty' );
							}
						}
					}
 
					if( ! empty( $ProductGeneral->config['config_show_model'] ) and ! empty( $data['model'] ) )
					{
						$xtpl->parse( 'main.category.items.model' );
					}

					if( $ProductGeneral->config['config_active_price'] == '1' )
					{
						if( $data['showprice'] == '1' )
						{

							// hiện phầm trăm giảm giá nếu có
							if( ( float )$data['special'] )
							{
								$percent = ( ( ( float )$data['price'] - ( float )$data['special'] ) / ( float )$data['price'] ) * 100;

								$xtpl->assign( 'PERCENT', floor( $percent ) );
								$xtpl->parse( 'main.category.items.percent' );
							}

							// định dạng lại giá gốc
							$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $data['price'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

							if( $data['price'] )
							{

								if( ( float )$data['special'] )
								{

									// định dạng lại giá sản phẩm giảm giá đặc biệt
									$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $data['special'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

									// giá giảm đặc biệt theo nhóm khách hàng
									$xtpl->assign( 'PRICE_NEW', $data['special'] );

									// giá gốc
									$xtpl->assign( 'PRICE', $data['price'] );

									$xtpl->parse( 'main.category.items.price.discounts' );

								}
								else
								{
									// giá gốc
									$xtpl->assign( 'PRICE', $data['price'] );

									$xtpl->parse( 'main.category.items.price.no_discounts' );
								}

								// hiện giá thuế phí nếu cấu hình hiện
								if( $ProductGeneral->config['config_tax'] )
								{
									// định dạng lại giá thuế phí
									$data['tax'] = $ProductCurrency->format( ( float )$data['special'] ? $data['special'] : $data['price'] );
								}
								else
								{
									$data['tax'] = false;
								}

							}

							$xtpl->parse( 'main.category.items.price' );
						}
						else
						{
							$xtpl->parse( 'main.category.items.contact' );
						}
					}

					if( defined( 'NV_IS_MODADMIN' ) )
					{
						$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data['product_id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data['product_id'] ) );
						$xtpl->parse( 'main.category.items.adminlink' );
					}

					// So sanh san pham
					if( $ProductGeneral->config['config_show_compare'] == 1 )
					{
						if( isset( $_SESSION[$module_name . '_array_id'] ) )
						{
							$array_id = $_SESSION[$module_name . '_array_id'];
							$array_id = unserialize( $array_id );
						}
						else
						{
							$array_id = array();
						}

						if( ! empty( $array_id ) )
						{
							$ch = ( in_array( $data['product_id'], $array_id ) ) ? ' checked="checked"' : '';
							$xtpl->assign( 'ch', $ch );
						}

						$xtpl->parse( 'main.category.items.compare' );
					}

					// San pham yeu thich
					if( $ProductGeneral->config['config_active_wishlist'] )
					{
						if( ! empty( $array_wishlist_id ) )
						{
							if( in_array( $data['product_id'], $array_wishlist_id ) )
							{
								$xtpl->parse( 'main.category.items.wishlist.disabled' );
							}
						}
						$xtpl->parse( 'main.category.items.wishlist' );
					}

					$xtpl->parse( 'main.category.items' );
					++$i;
					++$last;
				}
				if( $data['num_pro'] > $data['num_link'] ) $xtpl->parse( 'main.category.view_next' );
				$xtpl->parse( 'main.category' );
			}
		}
		// $ProductCurrency->clear();
		// $ProductTax->clear();
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function view_home_category( $data_content, $html_pages = '', $sort = 0 )
{ 
	global $ProductCurrency, $productRegistry,  $global_config, $module_info, $lang_module, $module_file, $module_name, $array_wishlist_id;

	$xtpl = new XTemplate( 'main_procate.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	
	$num_view = $ProductGeneral->config['config_per_row'];

	if( ! empty( $data_content ) )
	{
		if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );
		$a = 1;
		foreach( $data_content as $data )
		{ 
			if( $data['num_pro'] > 0 )
			{
				$xtpl->assign( 'CATEGORY_NAME', $data['name'] );
				$xtpl->assign( 'CATEGORY_LINK', $data['link'] );
				$xtpl->assign( 'CATEGORY_ID', $data['category_id'] );
				$xtpl->assign( 'NUM_PRO', $data['num_pro'] );
				
				$num_row = $ProductGeneral->config['config_per_row'] == 3 ? 4 : 3;
				
				$i = 1;
				$class = 1;
				foreach( $data['data'] as $data )
				{
				if( $class== 1 || $class % 5 == 0 )
					{
						$xtpl->assign( 'CLASS', 'first');
					}
					elseif( $class % 4 == 0 )
					{
						$xtpl->assign( 'CLASS', 'last');
					}
					else 
					{
						$xtpl->assign( 'CLASS', '');
					}
					$xtpl->assign( 'PRODUCT_ID', $data['product_id'] );
					$xtpl->assign( 'TOKEN', md5( $data['product_id'] . $global_config['sitekey'] . session_id() ) );
					
					$xtpl->assign( 'LINK', $data['link_pro'] );
					$xtpl->assign( 'TITLE', $data['name'] );
					$xtpl->assign( 'TITLE0', nv_clean60( $data['name'], 40 ) );
					$xtpl->assign( 'IMG_SRC', $data['thumb'] );
					$xtpl->assign( 'IMG_LARGE', creat_thumbs( $data['product_id'], $data['image'], $module_name, 250, 375 ) );
					$xtpl->assign( 'LINK_ORDER', $data['link_order'] );
					$xtpl->assign( 'MODEL', $data['model'] );
					
					// hiện tình trạng hàng theo các cấu hình
					if( $data['quantity'] <= 0 )
					{
						$data['stock'] = $data['stock_status'];

					}
					elseif( $ProductGeneral->config['config_stock_display'] )
					{
						$data['stock'] = $data['quantity'];

					}
					else
					{
						$data['stock'] = $lang_module['product_instock'];
					}

					$xtpl->assign( 'num', $num_row );

					if( $ProductGeneral->config['config_active_order'] == '1' )
					{
						if( $data['showprice'] == '1' )
						{
							if( $data['quantity'] > 0 )
							{
								$xtpl->parse( 'main.category.items.order' );
							}
							else
							{
								$xtpl->parse( 'main.category.items.product_empty' );
							}
						}
					}
 
					if( ! empty( $ProductGeneral->config['config_show_model'] ) and ! empty( $data['model'] ) )
					{
						$xtpl->parse( 'main.category.items.model' );
					}

					if( $ProductGeneral->config['config_active_price'] == '1' )
					{
						if( $data['showprice'] == '1' )
						{

							// hiện phầm trăm giảm giá nếu có
							if( ( float )$data['special'] )
							{
								$percent = ( ( ( float )$data['price'] - ( float )$data['special'] ) / ( float )$data['price'] ) * 100;

								$xtpl->assign( 'PERCENT', floor( $percent ) );
								$xtpl->parse( 'main.category.items.percent' );
							}

							// định dạng lại giá gốc
							$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $data['price'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

							if( $data['price'] )
							{

								if( ( float )$data['special'] )
								{

									// định dạng lại giá sản phẩm giảm giá đặc biệt
									$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $data['special'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

									// giá giảm đặc biệt theo nhóm khách hàng
									$xtpl->assign( 'PRICE_NEW', $data['special'] );

									// giá gốc
									$xtpl->assign( 'PRICE', $data['price'] );

									$xtpl->parse( 'main.category.items.price.discounts' );

								}
								else
								{
									// giá gốc
									$xtpl->assign( 'PRICE', $data['price'] );

									$xtpl->parse( 'main.category.items.price.no_discounts' );
								}

								// hiện giá thuế phí nếu cấu hình hiện
								if( $ProductGeneral->config['config_tax'] )
								{
									// định dạng lại giá thuế phí
									$data['tax'] = $ProductCurrency->format( ( float )$data['special'] ? $data['special'] : $data['price'] );
								}
								else
								{
									$data['tax'] = false;
								}

							}

							$xtpl->parse( 'main.category.items.price' );
						}
						else
						{
							$xtpl->parse( 'main.category.items.contact' );
						}
					}

					if( defined( 'NV_IS_MODADMIN' ) )
					{
						$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data['product_id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data['product_id'] ) );
						$xtpl->parse( 'main.category.items.adminlink' );
					}

					// So sanh san pham
					if( $ProductGeneral->config['config_show_compare'] == 1 )
					{
						if( isset( $_SESSION[$module_name . '_array_id'] ) )
						{
							$array_id = $_SESSION[$module_name . '_array_id'];
							$array_id = unserialize( $array_id );
						}
						else
						{
							$array_id = array();
						}

						if( ! empty( $array_id ) )
						{
							$ch = ( in_array( $data['product_id'], $array_id ) ) ? ' checked="checked"' : '';
							$xtpl->assign( 'ch', $ch );
						}

						$xtpl->parse( 'main.category.items.compare' );
					}

					// San pham yeu thich
					if( $ProductGeneral->config['config_active_wishlist'] )
					{
						if( ! empty( $array_wishlist_id ) )
						{
							if( in_array( $data['product_id'], $array_wishlist_id ) )
							{
								$xtpl->parse( 'main.category.items.wishlist.disabled' );
							}
						}
						$xtpl->parse( 'main.category.items.wishlist' );
					}

					$xtpl->parse( 'main.category.items' );
					++$i;
					++$class;
				}
				//if( $data['num_pro'] > $data['num_link'] ) $xtpl->parse( 'main.category.view_next' );
				$xtpl->parse( 'main.category' );
			}
		 
		}
		 
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function view_home_all( $data_content, $html_pages = '', $sort = 0 )
{
	global $ProductCurrency, $productRegistry, $module_info, $global_config, $lang_module, $module_file, $module_name, $ProductGeneral, $op, $array_displays, $array_wishlist_id;

	$xtpl = new XTemplate( 'main_product.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$xtpl->assign( 'CSS_MODEL', ! empty( $ProductGeneral->config['config_show_model'] ) ? ' show-product-code' : '' );
	
	if( ( ! isset( $op ) OR $op != 'detail' ) && $ProductGeneral->config['config_show_displays'] == 1 )
	{
		foreach( $array_displays as $k => $array_displays_i )
		{
			$se = '';
			$xtpl->assign( 'value', $array_displays_i );
			$xtpl->assign( 'key', $k );
			$se = ( $sort == $k ) ? 'selected="selected"' : '';
			$xtpl->assign( 'se', $se );
			$xtpl->parse( 'main.displays.sorts' );
		}
		$xtpl->parse( 'main.displays' );
	}

	if( ! empty( $data_content ) )
	{
		$i = 1;
		$num_row = $ProductGeneral->config['config_per_row'] == 3 ? 4 : 3;

		if( $op == 'main' )
		{
			$xtpl->parse( 'main.new_product_title' );
		}

		if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );

		foreach( $data_content as $data )
		{
			$xtpl->assign( 'PRODUCT_ID', $data['product_id'] );
			$xtpl->assign( 'TOKEN', md5( $data['product_id'] . $global_config['sitekey'] . session_id() ) );
			$xtpl->assign( 'LINK', $data['link_pro'] );
			$xtpl->assign( 'TITLE', $data['name'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $data['name'], 40 ) );
			$xtpl->assign( 'IMG_SRC', $data['thumb'] );
			$xtpl->assign( 'IMG_LARGE', creat_thumbs( $data['product_id'], $data['image'], $module_name, 250, 375 ) );
			$xtpl->assign( 'LINK_ORDER', $data['link_order'] );
			$xtpl->assign( 'MODEL', $data['model'] );

			$xtpl->assign( 'num', $num_row );

			// hiện tình trạng hàng theo các cấu hình
			if( $data['quantity'] <= 0 )
			{
				$data['stock'] = $data['stock_status'];

			}
			elseif( $ProductGeneral->config['config_stock_display'] )
			{
				$data['stock'] = $data['quantity'];

			}
			else
			{
				$data['stock'] = $lang_module['product_instock'];
			}

			if( $ProductGeneral->config['config_active_order'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
					if( $data['quantity'] > 0 )
					{
						$xtpl->parse( 'main.items.order' );
					}
					else
					{
						$xtpl->parse( 'main.items.product_empty' );
					}
				}
			}
 
			if( ! empty( $ProductGeneral->config['config_show_model'] ) and ! empty( $data['model'] ) )
			{
				$xtpl->parse( 'main.items.model' );
			}

			if( $ProductGeneral->config['config_active_price'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{

					// hiện phầm trăm giảm giá nếu có
					if( ( float )$data['special'] )
					{
						$percent = ( ( ( float )$data['price'] - ( float )$data['special'] ) / ( float )$data['price'] ) * 100;

						$xtpl->assign( 'PERCENT', floor( $percent ) );
						$xtpl->parse( 'main.items.percent' );
					}

					// định dạng lại giá gốc
					$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $data['price'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

					if( $data['price'] )
					{

						if( ( float )$data['special'] )
						{

							// định dạng lại giá sản phẩm giảm giá đặc biệt
							$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $data['special'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

							// giá giảm đặc biệt theo nhóm khách hàng
							$xtpl->assign( 'PRICE_NEW', $data['special'] );

							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.discounts' );

						}
						else
						{
							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.no_discounts' );
						}

						// hiện giá thuế phí nếu cấu hình hiện
						if( $ProductGeneral->config['config_tax'] )
						{
							// định dạng lại giá thuế phí
							$data['tax'] = $ProductCurrency->format( ( float )$data['special'] ? $data['special'] : $data['price'] );
						}
						else
						{
							$data['tax'] = false;
						}

					}

					$xtpl->parse( 'main.items.price' );
				}
				else
				{
					$xtpl->parse( 'main.items.contact' );
				}
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data['product_id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data['product_id'] ) );
				$xtpl->parse( 'main.items.adminlink' );
			}
			
			
			// So sanh san pham
			if( $ProductGeneral->config['config_show_compare'] == 1 )
			{
				if( isset( $_SESSION[$module_name . '_array_id'] ) )
				{
					$array_id = $_SESSION[$module_name . '_array_id'];
					$array_id = unserialize( $array_id );
				}
				else
				{
					$array_id = array();
				}

				if( ! empty( $array_id ) )
				{
					$ch = ( in_array( $data['product_id'], $array_id ) ) ? ' checked="checked"' : '';
					$xtpl->assign( 'ch', $ch );
				}

				$xtpl->parse( 'main.items.compare' );
			}

			// San pham yeu thich
			if( $ProductGeneral->config['config_active_wishlist'] )
			{
				if( ! empty( $array_wishlist_id ) )
				{
					if( in_array( $data['product_id'], $array_wishlist_id ) )
					{
						$xtpl->parse( 'main.items.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.items.wishlist' );
			}
 

			$xtpl->parse( 'main.items' );
			++$i;
		}
 
		if( ! empty( $html_pages ) )
		{
			$xtpl->assign( 'generate_page', $html_pages );
			$xtpl->parse( 'main.pages' );
		}
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
 
function view_other_in_catagory( $data_content, $html_pages = '', $sort = 0 )
{
	global $ProductCurrency, $productRegistry, $module_info, $global_config, $lang_module, $module_file, $module_name, $ProductGeneral->config, $op, $array_displays, $array_wishlist_id;

	$xtpl = new XTemplate( 'view_other_in_catagory.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$xtpl->assign( 'CSS_MODEL', ! empty( $ProductGeneral->config['config_show_model'] ) ? ' show-product-code' : '' );
	if( ( ! isset( $op ) or $op != 'detail' ) && $ProductGeneral->config['config_show_displays'] == 1 )
	{
		foreach( $array_displays as $k => $array_displays_i )
		{
			$se = '';
			$xtpl->assign( 'value', $array_displays_i );
			$xtpl->assign( 'key', $k );
			$se = ( $sort == $k ) ? 'selected="selected"' : '';
			$xtpl->assign( 'se', $se );
			$xtpl->parse( 'main.displays.sorts' );
		}
		$xtpl->parse( 'main.displays' );
	}

	if( ! empty( $data_content ) )
	{
		$i = 1;
		$num_row = $ProductGeneral->config['config_per_row'] == 3 ? 4 : 3;

		if( $op == 'main' )
		{
			$xtpl->parse( 'main.new_product_title' );
		}
		
		if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );
		$last = 1;
		foreach( $data_content as $data )
		{
			$xtpl->assign( 'LAST', ( $last % 3 == 0 ) ? 'last' : '' );
			$xtpl->assign( 'PRODUCT_ID', $data['product_id'] );
			$xtpl->assign( 'TOKEN', md5( $data['product_id'] . $global_config['sitekey'] . session_id() ) );
			$xtpl->assign( 'LINK', $data['link_pro'] );
			$xtpl->assign( 'TITLE', $data['name'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $data['name'], 40 ) );
			$xtpl->assign( 'IMG_SRC', $data['thumb'] );
			$xtpl->assign( 'IMG_LARGE', creat_thumbs( $data['product_id'], $data['image'], $module_name, 250, 375 ) );
			$xtpl->assign( 'LINK_ORDER', $data['link_order'] );
 
			$xtpl->assign( 'model', $data['model'] );

			$xtpl->assign( 'num', $num_row );

			if( $ProductGeneral->config['config_active_order'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
					if( $data['quantity'] > 0 )
					{
						$xtpl->parse( 'main.items.order' );
					}
					else
					{
						$xtpl->parse( 'main.items.product_empty' );
					}
				}
			}

			if( $ProductGeneral->config['config_active_price'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{

					// hiện phầm trăm giảm giá nếu có
					if( ( float )$data['special'] )
					{
						$percent = ( ( ( float )$data['price'] - ( float )$data['special'] ) / ( float )$data['price'] ) * 100;

						$xtpl->assign( 'PERCENT', floor( $percent ) );
						$xtpl->parse( 'main.items.percent' );
					}

					// định dạng lại giá gốc
					$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $data['price'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

					if( $data['price'] )
					{

						if( ( float )$data['special'] )
						{

							// định dạng lại giá sản phẩm giảm giá đặc biệt
							$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $data['special'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

							// giá giảm đặc biệt theo nhóm khách hàng
							$xtpl->assign( 'PRICE_NEW', $data['special'] );

							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.discounts' );

						}
						else
						{
							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.no_discounts' );
						}

						// hiện giá thuế phí nếu cấu hình hiện
						if( $ProductGeneral->config['config_tax'] )
						{
							// định dạng lại giá thuế phí
							$data['tax'] = $ProductCurrency->format( ( float )$data['special'] ? $data['special'] : $data['price'] );
						}
						else
						{
							$data['tax'] = false;
						}

					}

					$xtpl->parse( 'main.items.price' );
				}
				else
				{
					$xtpl->parse( 'main.items.contact' );
				}
			}

 
			if( ! empty( $ProductGeneral->config['config_show_model'] ) and ! empty( $data['model'] ) )
			{
				$xtpl->parse( 'main.items.model' );
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data['product_id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data['product_id'] ) );
				$xtpl->parse( 'main.items.adminlink' );
			}

			// So sanh san pham
			if( $ProductGeneral->config['config_show_compare'] == 1 )
			{
				if( isset( $_SESSION[$module_name . '_array_id'] ) )
				{
					$array_id = $_SESSION[$module_name . '_array_id'];
					$array_id = unserialize( $array_id );
				}
				else
				{
					$array_id = array();
				}

				if( ! empty( $array_id ) )
				{
					$ch = ( in_array( $data['product_id'], $array_id ) ) ? ' checked="checked"' : '';
					$xtpl->assign( 'ch', $ch );
				}

				$xtpl->parse( 'main.items.compare' );
			}

			// San pham yeu thich
			if( $ProductGeneral->config['config_active_wishlist'] )
			{
				if( ! empty( $array_wishlist_id ) )
				{
					if( in_array( $data['product_id'], $array_wishlist_id ) )
					{
						$xtpl->parse( 'main.items.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.items.wishlist' );
			}

 

			$xtpl->parse( 'main.items' );
			++$i;
			++$last;
		}
		// $ProductTax->clear();
		// $ProductCurrency->clear();
		
		if( ! empty( $html_pages ) )
		{
			$xtpl->assign( 'generate_page', $html_pages );
			$xtpl->parse( 'main.pages' );
		}
	}
 	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function view_search_all( $data_content, $html_pages = '' )
{
	global $module_info,  $global_config, $lang_module, $module_file, $ProductGeneral->config, $array_wishlist_id;

	$xtpl = new XTemplate( 'search_all.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$num_view = $ProductGeneral->config['config_per_row'];

	if( ! empty( $data_content ) )
	{
		$i = 1;
		$num_row = $ProductGeneral->config['config_per_row'] == 3 ? 4 : 3;

		foreach( $data_content as $data )
		{
			$xtpl->assign( 'ID', $data['id'] );
			$xtpl->assign( 'LINK', $data['link_pro'] );
			$xtpl->assign( 'TITLE', $data['title'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $data['title'], 40 ) );
			$xtpl->assign( 'IMG_SRC', $data['thumb'] );
			$xtpl->assign( 'IMG_LARGE', creat_thumbs( $data['id'], $data['image'], $module_name, 250, 375 ) );
			$xtpl->assign( 'LINK_ORDER', $data['link_order'] );
 
			$xtpl->assign( 'num', $num_row );

			if( $ProductGeneral->config['config_active_order'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
					if( $data['product_number'] > 0 )
					{
						$xtpl->parse( 'main.items.order' );
					}
					else
					{
						$xtpl->parse( 'main.items.product_empty' );
					}
				}
			}

			//$price = nv_currency_conversion( $data['product_price'], $data['money_unit'], $ProductGeneral->config['config_money_unit'], $data['discount_id'] );

			if( $ProductGeneral->config['config_active_price'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
					$xtpl->assign( 'PRICE', $price );
					if( $data['discount_id'] and $price['discount_percent'] > 0 )
					{
						$xtpl->parse( 'main.items.price.discounts' );
						$xtpl->parse( 'main.items.price.discounts.standard' );
					}
					else
					{
						$xtpl->parse( 'main.items.price.no_discounts' );
					}
					$xtpl->parse( 'main.items.price' );
				}
				else
				{
					$xtpl->parse( 'main.items.contact' );
				}

			}
 
			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data['id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data['id'] ) );
				$xtpl->parse( 'main.items.adminlink' );
			}

			// So sanh san pham
			if( $ProductGeneral->config['config_show_compare'] == 1 )
			{
				if( isset( $_SESSION[$module_name . '_array_id'] ) )
				{
					$array_id = $_SESSION[$module_name . '_array_id'];
					$array_id = unserialize( $array_id );
				}
				else
				{
					$array_id = array();
				}

				if( ! empty( $array_id ) )
				{
					$ch = ( in_array( $data['id'], $array_id ) ) ? ' checked="checked"' : '';
					$xtpl->assign( 'ch', $ch );
				}

				$xtpl->parse( 'main.items.compare' );
			}

			// San pham yeu thich
			if( $ProductGeneral->config['config_active_wishlist'] )
			{
				if( ! empty( $array_wishlist_id ) )
				{
					if( in_array( $data['id'], $array_wishlist_id ) )
					{
						$xtpl->parse( 'main.items.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.items.wishlist' );
			}

			if( $data['discount_id'] and $price['discount_percent'] > 0 )
			{
				$xtpl->parse( 'main.items.discounts' );
			}

			$xtpl->parse( 'main.items' );
			++$i;
		}
		if( ! empty( $html_pages ) )
		{
			$xtpl->assign( 'generate_page', $html_pages );
			$xtpl->parse( 'main.pages' );
		}
	}
 	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function brand_page_gird( $brand, $data_content, $html_pages = '', $sort = 0 )
{
	global $ProductCurrency, $productRegistry, $global_config, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $op, $array_displays, $array_wishlist_id;

	$xtpl = new XTemplate( 'view_brand_gird.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'CAT_NAME', $brand['name'] );
	$xtpl->assign( 'CSS_MODEL', ! empty( $ProductGeneral->config['config_show_model'] ) ? ' show-product-code' : '' );
	if( ( ! isset( $op ) or $op != 'detail' ) && $ProductGeneral->config['config_show_displays'] == 1 )
	{ 
		foreach( $array_displays as $k => $array_displays_i )
		{
			$se = '';
			$xtpl->assign( 'value', $array_displays_i );
			$xtpl->assign( 'key', $k );
			$se = ( $sort == $k ) ? 'selected="selected"' : '';
			$xtpl->assign( 'se', $se );
			$xtpl->parse( 'main.displays.sorts' );
		}
		$xtpl->parse( 'main.displays' );
	}

	if( ! empty( $data_content ) )
	{
		$i = 1;
		$num_row = $ProductGeneral->config['config_per_row'] == 3 ? 4 : 3;

		if( $op == 'main' )
		{
			$xtpl->parse( 'main.new_product_title' );
		}

		if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );

		foreach( $data_content as $data )
		{
			$xtpl->assign( 'PRODUCT_ID', $data['product_id'] );
			$xtpl->assign( 'TOKEN', md5( $data['product_id'] . $global_config['sitekey'] . session_id() ) );
			$xtpl->assign( 'LINK', $data['link_pro'] );
			$xtpl->assign( 'TITLE', $data['name'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $data['name'], 40 ) );
			$xtpl->assign( 'IMG_SRC', $data['thumb'] );
			$xtpl->assign( 'IMG_LARGE', creat_thumbs( $data['product_id'], $data['image'], $module_name, 250, 375 ) );
			$xtpl->assign( 'LINK_ORDER', $data['link_order'] );
 
			$xtpl->assign( 'MODEL', $data['model'] );

			$xtpl->assign( 'num', $num_row );

			// hiện tình trạng hàng theo các cấu hình
			if( $data['quantity'] <= 0 )
			{
				$data['stock'] = $data['stock_status'];

			}
			elseif( $ProductGeneral->config['config_stock_display'] )
			{
				$data['stock'] = $data['quantity'];

			}
			else
			{
				$data['stock'] = $lang_module['product_instock'];
			}

			if( $ProductGeneral->config['config_active_order'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
					if( $data['quantity'] > 0 )
					{
						$xtpl->parse( 'main.items.order' );
					}
					else
					{
						$xtpl->parse( 'main.items.product_empty' );
					}
				}
			}

 
			if( ! empty( $ProductGeneral->config['config_show_model'] ) and ! empty( $data['model'] ) )
			{
				$xtpl->parse( 'main.items.model' );
			}

			if( $ProductGeneral->config['config_active_price'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{

					// hiện phầm trăm giảm giá nếu có
					if( ( float )$data['special'] )
					{
						$percent = ( ( ( float )$data['price'] - ( float )$data['special'] ) / ( float )$data['price'] ) * 100;

						$xtpl->assign( 'PERCENT', floor( $percent ) );
						$xtpl->parse( 'main.items.percent' );
					}

					// định dạng lại giá gốc
					$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $data['price'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

					if( $data['price'] )
					{

						if( ( float )$data['special'] )
						{

							// định dạng lại giá sản phẩm giảm giá đặc biệt
							$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $data['special'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

							// giá giảm đặc biệt theo nhóm khách hàng
							$xtpl->assign( 'PRICE_NEW', $data['special'] );

							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.discounts' );

						}
						else
						{
							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.no_discounts' );
						}

						// hiện giá thuế phí nếu cấu hình hiện
						if( $ProductGeneral->config['config_tax'] )
						{
							// định dạng lại giá thuế phí
							$data['tax'] = $ProductCurrency->format( ( float )$data['special'] ? $data['special'] : $data['price'] );
						}
						else
						{
							$data['tax'] = false;
						}

					}

					$xtpl->parse( 'main.items.price' );
				}
				else
				{
					$xtpl->parse( 'main.items.contact' );
				}
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data['product_id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data['product_id'] ) );
				$xtpl->parse( 'main.items.adminlink' );
			}

			// So sanh san pham
			if( $ProductGeneral->config['config_show_compare'] == 1 )
			{
				if( isset( $_SESSION[$module_name . '_array_id'] ) )
				{
					$array_id = $_SESSION[$module_name . '_array_id'];
					$array_id = unserialize( $array_id );
				}
				else
				{
					$array_id = array();
				}

				if( ! empty( $array_id ) )
				{
					$ch = ( in_array( $data['product_id'], $array_id ) ) ? ' checked="checked"' : '';
					$xtpl->assign( 'ch', $ch );
				}

				$xtpl->parse( 'main.items.compare' );
			}

			// San pham yeu thich
			if( $ProductGeneral->config['config_active_wishlist'] )
			{
				if( ! empty( $array_wishlist_id ) )
				{
					if( in_array( $data['product_id'], $array_wishlist_id ) )
					{
						$xtpl->parse( 'main.items.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.items.wishlist' );
			}
 

			$xtpl->parse( 'main.items' );
			++$i;
		}
		// $ProductCurrency->clear();
		// $ProductTax->clear();

		if( ! empty( $html_pages ) )
		{
			$xtpl->assign( 'generate_page', $html_pages );
			$xtpl->parse( 'main.pages' );
		}
	}

 	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function theme_specials_view( $data_content, $html_pages = '', $sort = 0 )
{
	global $ProductCurrency, $productRegistry, $global_config, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $op, $array_displays, $array_wishlist_id;

	$xtpl = new XTemplate( 'theme_specials_view.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$xtpl->assign( 'CSS_MODEL', ! empty( $ProductGeneral->config['config_show_model'] ) ? ' show-product-code' : '' );
 

	if( ! empty( $data_content  ) )
	{
		$i = 1;
		$num_row = $ProductGeneral->config['config_per_row'] == 3 ? 4 : 3;

		if( $op == 'main' )
		{
			$xtpl->parse( 'main.new_product_title' );
		}

		if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );

		foreach( $data_content  as $data )
		{ 
			$xtpl->assign( 'PRODUCT_ID', $data['product_id'] );
			$xtpl->assign( 'TOKEN', md5( $data['product_id'] . $global_config['sitekey'] . session_id() ) );
			$xtpl->assign( 'LINK', $data['link_pro'] );
			$xtpl->assign( 'TITLE', $data['name'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $data['name'], 40 ) );
			$xtpl->assign( 'IMG_SRC', $data['thumb'] );
			$xtpl->assign( 'IMG_LARGE', creat_thumbs( $data['product_id'], $data['image'], $module_name, 250, 375 ) );
			$xtpl->assign( 'LINK_ORDER', $data['link_order'] );
 
			$xtpl->assign( 'model', $data['model'] );

			$xtpl->assign( 'num', $num_row );
 
			// hiện tình trạng hàng theo các cấu hình
			if( $data['quantity'] <= 0 )
			{
				$data['stock'] = $data['stock_status'];

			}
			elseif( $ProductGeneral->config['config_stock_display'] )
			{
				$data['stock'] = $data['quantity'];

			}
			else
			{
				$data['stock'] = $lang_module['product_instock'];
			}

			if( $ProductGeneral->config['config_active_order'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
					if( $data['quantity'] > 0 )
					{
						$xtpl->parse( 'main.items.order' );
					}
					else
					{
						$xtpl->parse( 'main.items.product_empty' );
					}
				}
			}

 
			if( ! empty( $ProductGeneral->config['config_show_model'] ) and ! empty( $data['model'] ) )
			{
				$xtpl->parse( 'main.items.model' );
			}

			if( $ProductGeneral->config['config_active_price'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{

					// hiện phầm trăm giảm giá nếu có
					if( ( float )$data['special'] )
					{
						$percent = ( ( ( float )$data['price'] - ( float )$data['special'] ) / ( float )$data['price'] ) * 100;

						$xtpl->assign( 'PERCENT', floor( $percent ) );
						$xtpl->parse( 'main.items.percent' );
					}

					// định dạng lại giá gốc
					$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $data['price'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

					if( $data['price'] )
					{

						if( ( float )$data['special'] )
						{

							// định dạng lại giá sản phẩm giảm giá đặc biệt
							$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $data['special'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

							// giá giảm đặc biệt theo nhóm khách hàng
							$xtpl->assign( 'PRICE_NEW', $data['special'] );

							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.discounts' );

						}
						else
						{
							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.no_discounts' );
						}

						// hiện giá thuế phí nếu cấu hình hiện
						if( $ProductGeneral->config['config_tax'] )
						{
							// định dạng lại giá thuế phí
							$data['tax'] = $ProductCurrency->format( ( float )$data['special'] ? $data['special'] : $data['price'] );
						}
						else
						{
							$data['tax'] = false;
						}

					}

					$xtpl->parse( 'main.items.price' );
				}
				else
				{
					$xtpl->parse( 'main.items.contact' );
				}
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data['product_id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data['product_id'] ) );
				$xtpl->parse( 'main.items.adminlink' );
			}

			// So sanh san pham
			if( $ProductGeneral->config['config_show_compare'] == 1 )
			{
				if( isset( $_SESSION[$module_name . '_array_id'] ) )
				{
					$array_id = $_SESSION[$module_name . '_array_id'];
					$array_id = unserialize( $array_id );
				}
				else
				{
					$array_id = array();
				}

				if( ! empty( $array_id ) )
				{
					$ch = ( in_array( $data['product_id'], $array_id ) ) ? ' checked="checked"' : '';
					$xtpl->assign( 'ch', $ch );
				}

				$xtpl->parse( 'main.items.compare' );
			}

			// San pham yeu thich
			if( $ProductGeneral->config['config_active_wishlist'] )
			{
				if( ! empty( $array_wishlist_id ) )
				{
					if( in_array( $data['product_id'], $array_wishlist_id ) )
					{
						$xtpl->parse( 'main.items.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.items.wishlist' );
			}
 

			$xtpl->parse( 'main.items' );
			++$i;
		}
		// $ProductCurrency->clear();
		// $ProductTax->clear();

		if( ! empty( $html_pages ) )
		{
			$xtpl->assign( 'generate_page', $html_pages );
			$xtpl->parse( 'main.pages' );
		}
	}

 	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
function viewcat_page_grid( $data_content, $html_pages = '', $sort = 0 )
{
	global $ProductCurrency, $productRegistry, $global_config, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $op, $array_displays, $array_wishlist_id;

	$xtpl = new XTemplate( 'view_gird.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'CAT_NAME', $data_content['name'] );
	$xtpl->assign( 'CSS_MODEL', ! empty( $ProductGeneral->config['config_show_model'] ) ? ' show-product-code' : '' );
	if( ( ! isset( $op ) or $op != 'detail' ) && $ProductGeneral->config['config_show_displays'] == 1 )
	{ 
		foreach( $array_displays as $k => $array_displays_i )
		{
			$se = '';
			$xtpl->assign( 'value', $array_displays_i );
			$xtpl->assign( 'key', $k );
			$se = ( $sort == $k ) ? 'selected="selected"' : '';
			$xtpl->assign( 'se', $se );
			$xtpl->parse( 'main.displays.sorts' );
		}
		$xtpl->parse( 'main.displays' );
	}

	if( ! empty( $data_content['data'] ) )
	{
		$i = 1;
		$num_row = $ProductGeneral->config['config_per_row'] == 3 ? 4 : 3;

		if( $op == 'main' )
		{
			$xtpl->parse( 'main.new_product_title' );
		}

		if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );

		foreach( $data_content['data'] as $data )
		{
			$xtpl->assign( 'PRODUCT_ID', $data['product_id'] );
			$xtpl->assign( 'TOKEN', md5( $data['product_id'] . $global_config['sitekey'] . session_id() ) );
			$xtpl->assign( 'LINK', $data['link_pro'] );
			$xtpl->assign( 'TITLE', $data['name'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $data['name'], 40 ) );
			$xtpl->assign( 'IMG_SRC', $data['thumb'] );
			$xtpl->assign( 'IMG_LARGE', creat_thumbs( $data['product_id'], $data['image'], $module_name, 250, 375 ) );
			$xtpl->assign( 'LINK_ORDER', $data['link_order'] );
 
			$xtpl->assign( 'model', $data['model'] );

			$xtpl->assign( 'num', $num_row );
			
			// hiện tình trạng hàng theo các cấu hình
			if( $data['quantity'] <= 0 )
			{
				$data['stock'] = $data['stock_status'];

			}
			elseif( $ProductGeneral->config['config_stock_display'] )
			{
				$data['stock'] = $data['quantity'];

			}
			else
			{
				$data['stock'] = $lang_module['product_instock'];
			}

			if( $ProductGeneral->config['config_active_order'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
					if( $data['quantity'] > 0 )
					{
						$xtpl->parse( 'main.items.order' );
					}
					else
					{
						$xtpl->parse( 'main.items.product_empty' );
					}
				}
			}

 
			if( ! empty( $ProductGeneral->config['config_show_model'] ) and ! empty( $data['model'] ) )
			{
				$xtpl->parse( 'main.items.model' );
			}

			if( $ProductGeneral->config['config_active_price'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{

					// hiện phầm trăm giảm giá nếu có
					if( ( float )$data['special'] )
					{
						$percent = ( ( ( float )$data['price'] - ( float )$data['special'] ) / ( float )$data['price'] ) * 100;

						$xtpl->assign( 'PERCENT', floor( $percent ) );
						$xtpl->parse( 'main.items.percent' );
					}

					// định dạng lại giá gốc
					$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $data['price'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

					if( $data['price'] )
					{

						if( ( float )$data['special'] )
						{

							// định dạng lại giá sản phẩm giảm giá đặc biệt
							$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $data['special'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

							// giá giảm đặc biệt theo nhóm khách hàng
							$xtpl->assign( 'PRICE_NEW', $data['special'] );

							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.discounts' );

						}
						else
						{
							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.no_discounts' );
						}

						// hiện giá thuế phí nếu cấu hình hiện
						if( $ProductGeneral->config['config_tax'] )
						{
							// định dạng lại giá thuế phí
							$data['tax'] = $ProductCurrency->format( ( float )$data['special'] ? $data['special'] : $data['price'] );
						}
						else
						{
							$data['tax'] = false;
						}

					}

					$xtpl->parse( 'main.items.price' );
				}
				else
				{
					$xtpl->parse( 'main.items.contact' );
				}
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data['product_id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data['product_id'] ) );
				$xtpl->parse( 'main.items.adminlink' );
			}

			// So sanh san pham
			if( $ProductGeneral->config['config_show_compare'] == 1 )
			{
				if( isset( $_SESSION[$module_name . '_array_id'] ) )
				{
					$array_id = $_SESSION[$module_name . '_array_id'];
					$array_id = unserialize( $array_id );
				}
				else
				{
					$array_id = array();
				}

				if( ! empty( $array_id ) )
				{
					$ch = ( in_array( $data['product_id'], $array_id ) ) ? ' checked="checked"' : '';
					$xtpl->assign( 'ch', $ch );
				}

				$xtpl->parse( 'main.items.compare' );
			}

			// San pham yeu thich
			if( $ProductGeneral->config['config_active_wishlist'] )
			{
				if( ! empty( $array_wishlist_id ) )
				{
					if( in_array( $data['product_id'], $array_wishlist_id ) )
					{
						$xtpl->parse( 'main.items.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.items.wishlist' );
			}
 

			$xtpl->parse( 'main.items' );
			++$i;
		}
		// $ProductCurrency->clear();
		// $ProductTax->clear();

		if( ! empty( $html_pages ) )
		{
			$xtpl->assign( 'generate_page', $html_pages );
			$xtpl->parse( 'main.pages' );
		}
	}

 	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function viewcat_page_list( $data_content, $html_pages = '', $sort = 0 )
{
	global $ProductCurrency, $productCategory, $productRegistry, $global_config, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $op, $array_displays, $array_wishlist_id;

	$xtpl = new XTemplate( 'view_list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'CAT_NAME', $data_content['name'] );
	$xtpl->assign( 'CSS_MODEL', ! empty( $ProductGeneral->config['config_show_model'] ) ? ' show-product-code' : '' );
	$xtpl->assign( 'SUM', count( $data_content['data'] ) );

	if( isset( $data_content['image'] ) )
	{
		$image = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data_content['image'];
		if( ! empty( $data_content['image'] ) and file_exists( $image ) )
		{
			$xtpl->assign( 'IMAGE', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data_content['image'] );
			$xtpl->parse( 'main.image' );
		}
	}

	if( ( ! isset( $op ) or $op != 'detail' ) && $ProductGeneral->config['config_show_displays'] == 1 )
	{
		foreach( $array_displays as $k => $array_displays_i )
		{
			$se = '';
			$xtpl->assign( 'value', $array_displays_i );
			$xtpl->assign( 'key', $k );
			$se = ( $sort == $k ) ? 'selected="selected"' : '';
			$xtpl->assign( 'se', $se );
			$xtpl->parse( 'main.displays.sorts' );
		}
		$xtpl->parse( 'main.displays' );
	}

	if( ! empty( $data_content['data'] ) )
	{
		$i = 1;
		$num_row = $ProductGeneral->config['config_per_row'] == 3 ? 4 : 3;

		if( $op == 'main' )
		{
			$xtpl->parse( 'main.new_product_title' );
		}

		if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );

		foreach( $data_content['data'] as $data )
		{
			$xtpl->assign( 'PRODUCT_ID', $data['product_id'] );
			$xtpl->assign( 'TOKEN', md5( $data['product_id'] . $global_config['sitekey'] . session_id() ) );
			$xtpl->assign( 'LINK', $data['link_pro'] );
			$xtpl->assign( 'TITLE', $data['name'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $data['name'], 40 ) );
			$xtpl->assign( 'IMG_SRC', $data['thumb'] );
			$xtpl->assign( 'IMG_LARGE', creat_thumbs( $data['product_id'], $data['image'], $module_name, 250, 375 ) );
			$xtpl->assign( 'LINK_ORDER', $data['link_order'] );
 
			$xtpl->assign( 'model', $data['model'] );

			$xtpl->assign( 'num', $num_row );


			// hiện tình trạng hàng theo các cấu hình
			if( $data['quantity'] <= 0 )
			{
				$data['stock'] = $data['stock_status'];

			}
			elseif( $ProductGeneral->config['config_stock_display'] )
			{
				$data['stock'] = $data['quantity'];

			}
			else
			{
				$data['stock'] = $lang_module['product_instock'];
			}

			if( $ProductGeneral->config['config_active_order'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
					if( $data['quantity'] > 0 )
					{
						$xtpl->parse( 'main.items.order' );
					}
					else
					{
						$xtpl->parse( 'main.items.product_empty' );
					}
				}
			}

 
			if( ! empty( $ProductGeneral->config['config_show_model'] ) and ! empty( $data['model'] ) )
			{
				$xtpl->parse( 'main.items.model' );
			}

			if( $ProductGeneral->config['config_active_price'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{

					// hiện phầm trăm giảm giá nếu có
					if( ( float )$data['special'] )
					{
						$percent = ( ( ( float )$data['price'] - ( float )$data['special'] ) / ( float )$data['price'] ) * 100;

						$xtpl->assign( 'PERCENT', floor( $percent ) );
						$xtpl->parse( 'main.items.percent' );
					}

					// định dạng lại giá gốc
					$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $data['price'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

					if( $data['price'] )
					{

						if( ( float )$data['special'] )
						{

							// định dạng lại giá sản phẩm giảm giá đặc biệt
							$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $data['special'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

							// giá giảm đặc biệt theo nhóm khách hàng
							$xtpl->assign( 'PRICE_NEW', $data['special'] );

							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.discounts' );

						}
						else
						{
							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.items.price.no_discounts' );
						}

						// hiện giá thuế phí nếu cấu hình hiện
						if( $ProductGeneral->config['config_tax'] )
						{
							// định dạng lại giá thuế phí
							$data['tax'] = $ProductCurrency->format( ( float )$data['special'] ? $data['special'] : $data['price'] );
						}
						else
						{
							$data['tax'] = false;
						}

					}

					$xtpl->parse( 'main.items.price' );
				}
				else
				{
					$xtpl->parse( 'main.items.contact' );
				}
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data['product_id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data['product_id'] ) );
				$xtpl->parse( 'main.items.adminlink' );
			}

			// So sanh san pham
			if( $ProductGeneral->config['config_show_compare'] == 1 )
			{
				if( isset( $_SESSION[$module_name . '_array_id'] ) )
				{
					$array_id = $_SESSION[$module_name . '_array_id'];
					$array_id = unserialize( $array_id );
				}
				else
				{
					$array_id = array();
				}

				if( ! empty( $array_id ) )
				{
					$ch = ( in_array( $data['product_id'], $array_id ) ) ? ' checked="checked"' : '';
					$xtpl->assign( 'ch', $ch );
				}

				$xtpl->parse( 'main.items.compare' );
			}

			// San pham yeu thich
			if( $ProductGeneral->config['config_active_wishlist'] )
			{
				if( ! empty( $array_wishlist_id ) )
				{
					if( in_array( $data['product_id'], $array_wishlist_id ) )
					{
						$xtpl->parse( 'main.items.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.items.wishlist' );
			}
 

			$xtpl->parse( 'main.items' );
			++$i;
		}
		// $ProductCurrency->clear();
		// $ProductTax->clear();

		if( ! empty( $html_pages ) )
		{
			$xtpl->assign( 'generate_page', $html_pages );
			$xtpl->parse( 'main.pages' );
		}
	}

 	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function detail_product( $product_info, $data_shop, $data_others, $array_other_view )
{
	global $ProductCurrency, $productCategory, $ProductTax, $global_config, $module_info, $lang_module, $module_file, $client_info, $module_name, $my_head, $ProductGeneral->config, $global_config, $array_wishlist_id;
	
 
	$data = $product_info;
	
	$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';
	$link2 = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=';
	
	// hiện tình trạng hàng theo các cấu hình
	if( $data['quantity'] <= 0 )
	{
		$data['stock'] = $product_info['stock_status'];

	}
	elseif( $ProductGeneral->config['config_stock_display'] )
	{
		$data['stock'] = $product_info['quantity'];
	}
	else
	{
		$data['stock'] = $lang_module['product_instock'];
	}
	
	if( ( $ProductGeneral->config['config_customer_price'] && defined( 'NV_IS_USER' ) ) || ! $ProductGeneral->config['config_customer_price'] )
	{
		$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $product_info['price'], $product_info['tax_class_id'], $ProductGeneral->config['config_tax'] ) );
	}
	else
	{
		$data['price'] = false;
	}
	if( ( float )$product_info['special'] )
	{
		$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $product_info['special'], $product_info['tax_class_id'], $ProductGeneral->config['config_tax'] ) );
	}
	else
	{
		$data['special'] = false;
	}
	
	if ( $ProductGeneral->config['config_tax'] ) 
	{
		$data['tax'] = $ProductCurrency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'] );
	} 
	else 
	{
		$data['tax'] = false;
	}
 
	// goi template
	$xtpl = new XTemplate( 'detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'MODULE', $module_name );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'SELFURL', $client_info['selfurl'] );
	$xtpl->assign( 'BASEURL', base64_encode( $client_info['selfurl'] ) );
	
	$faq_id = 0;
	$parent_id = 0;
	$xtpl->assign( 'TOKENKEY', md5( $data['product_id'] . $faq_id . $parent_id . $global_config['sitekey'] . session_id() ) );
	
	
	$review_id = 0;
	$parent_id = 0;
	$xtpl->assign( 'REVIEWTOKEN', md5( $data['product_id'] . $review_id . $parent_id . $global_config['sitekey'] . session_id() ) );
	
	
	$xtpl->assign( 'PRODUCT_ID', $data['product_id'] );
	$xtpl->assign( 'PRODUCT_ALIAS', $data['alias'] );
	$xtpl->assign( 'TOKEN', md5( $data['product_id'] . $global_config['sitekey'] . session_id() ) );
	$xtpl->assign( 'SRC_PRO', $data['thumb'] );
	$xtpl->assign( 'SRC_PRO_LAGE', $data['image'] );
	$xtpl->assign( 'TITLE', $data['name'] );
	$xtpl->assign( 'NUM_VIEW', $data['hitstotal'] );
	$xtpl->assign( 'DATE_UP', $lang_module['detail_dateup'] . ' ' . nv_date( 'd-m-Y h:i:s A', $data['addtime'] ) );
	$xtpl->assign( 'DETAIL', $data['description'] );
	$xtpl->assign( 'LINK_ORDER', $link2 . 'setcart&product_id=' . $data['product_id'] );
	
	$xtpl->assign( 'MODEL', $data['model'] );
	$xtpl->assign( 'QUANTITY', $data['quantity'] );
	// $xtpl->assign( 'RATINGDETAIL', $data['ratingdetail'] );
	// $xtpl->assign( 'PERCENT_RATE', $data['percent_rate'] );
  	// $xtpl->assign( 'UNITS', $data['units_name'] );
	$xtpl->assign( 'HOMEIMG', $data['image'] );
	$xtpl->assign( 'BASENAMEHOME', basename( $data['image'] ) );
 
	$xtpl->assign( 'CURRENCY', $ProductGeneral->config['config_currency'] );
	$xtpl->assign( 'STOCK', $data['stock'] );
 	 
	
	
	// hien thi nha cung cap
	if( !empty( $data['brand'] ) )
	{ 
		$brand = $data['brand'];
		$brand['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=brand/'. strtolower(change_alias( $brand['name'] ) );
		
		$width = 160;
		$height = 95;
		$image = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $brand['image'];
		$imginfo = nv_is_image( $image );

		if( $width >= $height ) $rate = $width / $height;
		else  $rate = $height / $width;
		$basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1-thumb\2',  basename( $brand['image'] ) );
 
		require_once NV_ROOTDIR . '/includes/class/image.class.php';
		$createImage = new image(  $image , NV_MAX_WIDTH, NV_MAX_HEIGHT );
		if( $imginfo['width'] <= $imginfo['height'] )
		{
			$createImage->resizeXY( $width, 0 );

		}
		elseif( ( $imginfo['width'] / $imginfo['height'] ) < $rate )
		{
			$createImage->resizeXY( $width, 0 );
		}
		elseif( ( $imginfo['width'] / $imginfo['height'] ) >= $rate )
		{
			$createImage->resizeXY( 0, $height );
		}
		$createImage->cropFromLeft( 0, 0, $width, $height );
		$createImage->save( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/brand', $basename );
		$createImage->close();
		
		$brand['logo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/brand/' . $basename;
		$brand['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $brand['image'];
		$brand['description'] =  nv_clean60( strip_tags( $brand['description'] ),  350 );

		$xtpl->assign( 'BRAND', $brand );
 
		$xtpl->parse( 'main.brand' );
		$xtpl->parse( 'main.brand_detail' );
		$xtpl->parse( 'main.brand_info' );
	}
	if( isset( $productCategory[$data['category_id']] ) )
	{
		$xtpl->assign( 'CATEGORY', $productCategory[$data['category_id']] );
		$xtpl->parse( 'main.category' );
	}
	// hien thi gia theo diem thuong
	if( !empty( $data['reward'] ) )
	{
		$xtpl->assign( 'REWARD', $data['reward'] );
		$xtpl->parse( 'main.reward' );
	}
	
	// hien thi diem thuong khi mua hang
	if( !empty( $data['points'] ) )
	{
		$xtpl->assign( 'POINTS', $data['points'] );
		$xtpl->parse( 'main.points' );
	}
	
	// thong tin theme
	if( !empty( $data['info'] ) )
	{
		$data['info'] = unserialize( $data['info'] );
		foreach( $data['info'] as $info )
		{
	 
			$xtpl->assign( 'INFO', $info );
			$xtpl->parse( 'main.info' );
		}
	}
	// hien thi ma san pham
	if( ! empty( $ProductGeneral->config['config_show_model'] ) and ! empty( $data['model'] ) )
	{
		$xtpl->parse( 'main.model' );
	}
	if( $ProductGeneral->config['config_active_price'] == '1' )
	{
		if( $data['showprice'] == '1' )
		{
 
			// hiện phầm trăm giảm giá nếu có
			 
			if( $data['special'] )
			{
				$percent = ( ( ( float )$product_info['price'] - ( float )$product_info['special'] ) / ( float )$product_info['price'] ) * 100;
 
				$xtpl->assign( 'SALE', $ProductCurrency->format( $product_info['special'] - $product_info['price'] ) );
				$xtpl->assign( 'PERCENT', floor( $percent ) );
				$xtpl->parse( 'main.price.discounts.percent' );
			}
 
			if( $data['price'] )
			{

				if( $data['special'] )
				{
 
					// giá giảm đặc biệt theo nhóm khách hàng
					$xtpl->assign( 'PRICE_NEW', $data['special'] );

					// giá gốc
					$xtpl->assign( 'PRICE', $data['price'] );
 
					if( !empty( $data['special_date_start'] ) && !empty( $data['special_date_end'] ) )
					{
						$xtpl->assign( 'SPECIAL_TIME', 'từ '. date( 'd/m', $data['special_date_start'] )  . ' - ' . date( 'd/m', $data['special_date_end'] ) );
					}

					
					
					$xtpl->parse( 'main.price.special' );

				}
				else
				{
					// giá gốc
					$xtpl->assign( 'PRICE', $data['price'] );

					$xtpl->parse( 'main.price.no_special' );
				}
				
				if( !empty( $data['discounts'] ) )
				{
					foreach( $data['discounts'] as $discount )
					{
						$xtpl->assign( 'DISCOUNT_QUANTITY', $discount['quantity'] );
						$xtpl->assign( 'DISCOUNT_PRICE', $discount['price'] );
						$xtpl->parse( 'main.price.discount' );
					}
				}
 
			}
			// hien thi cho phep dat hang
			if( $ProductGeneral->config['config_active_order'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
					if( $data['quantity'] > 0 )
					{
						$xtpl->parse( 'main.price.order' );
					}
					else
					{
						$xtpl->parse( 'main.price.product_empty' );
					}
				}
			}
			$xtpl->parse( 'main.price' );
		}
		else
		{
			$xtpl->parse( 'main.contact' );
		}
	}
	
	// hien thi cho phep dat hang
	if( $ProductGeneral->config['config_active_order'] == '1' )
	{
		if( $data['showprice'] == '1' )
		{
			if( $data['quantity'] > 0 )
			{
				$xtpl->parse( 'main.order' );
			}
			else
			{
				$xtpl->parse( 'main.product_empty' );
			}
		}
	}
 
	// San pham yeu thich
	if( $ProductGeneral->config['config_active_wishlist'] )
	{
		if( ! empty( $array_wishlist_id ) )
		{
			if( in_array( $data['product_id'], $array_wishlist_id ) )
			{
				$xtpl->parse( 'main.wishlist.disabled' );
			}
		}
		$xtpl->parse( 'main.wishlist' );
	}
 
		// hiển thị option sản phẩm
		if( !empty( $data['options'] ) )
		{
			foreach( $data['options']  as $option )
			{
				$option['required'] = ( !empty( $option['required'] ) ) ? ' required' : '';
				$xtpl->assign( 'OPTION', $option );
				
				foreach( $option['product_option_value'] as $option_value )
				{
				
					$option_value['alt'] = $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); 
					
					
					$xtpl->assign( 'OPTION_VALUE', $option_value );
					
					if( $option_value['price'] )
					{
						$xtpl->parse( 'main.option.'.$option['type'].'.option_value.option_value_price' );
					}
					
					$xtpl->parse( 'main.option.'.$option['type'].'.option_value' );
				}
				
				if ($option['type'] == 'select') 
				{
					$xtpl->parse( 'main.option.select' );
				}
				if ($option['type'] == 'radio') 
				{
					$xtpl->parse( 'main.option.radio' );
				}
				if ($option['type'] == 'checkbox') 
				{
					$xtpl->parse( 'main.option.checkbox' );
				}
				if ($option['type'] == 'image')
				{
					$xtpl->parse( 'main.option.image' );
				}
				if ($option['type'] == 'text') 
				{
					$xtpl->parse( 'main.option.text' );
				}
				if ($option['type'] == 'textarea') 
				{
					$xtpl->parse( 'main.option.textarea' );
				}
				if ($option['type'] == 'file')
				{
					$xtpl->parse( 'main.option.file' );
				}
				if ($option['type'] == 'date')
				{
					$xtpl->parse( 'main.option.date' );
				}
				if ($option['type'] == 'datetime')
				{
					$xtpl->parse( 'main.option.datetime' );
				}
				if ($option['type'] == 'time')
				{
					$xtpl->parse( 'main.option.time' );
				}
 
			}
			$xtpl->parse( 'main.option' );
		
		}
 
 
		// hiển thị các ảnh khác của sản phẩm
		if( ! empty( $data['images'] ) )
		{
			foreach( $data['images'] as $other_image )
			{
				$image = $other_image['image'];
				if( ! empty( $image ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $image ) )
				{
					$image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $image;
					$xtpl->assign( 'IMG_SRC_OTHER', $image );
					$xtpl->assign( 'BASENAME', basename( $image ) );
					$xtpl->parse( 'main.othersimg.loop' );
				}
			}
			$xtpl->parse( 'main.othersimg' );
		}
		
		// hien thi thuoc tinh san pham
		if( !empty( $data['attribute_groups'] ) )
		{
			foreach( $data['attribute_groups'] as $attribute )
			{ 
				$xtpl->assign( 'ATTRIBUTE', $attribute );
				$xtpl->parse( 'main.attribute' );
			}
			
		}
 
	if( !empty( $data['data_reviews'] ) )
	{
		foreach( $data['data_reviews'] as $reviews )
		{	
			
			$reviews['datetime'] = nv_date('Y-m-d', $reviews['date_added']);
			$reviews['date_string'] = convert_time( $reviews['date_added'] );
			$reviews['rating_percent'] = $reviews['rating'] * 20;
			$reviews['photo'] = !empty( $reviews['photo'] ) ? NV_BASE_SITEURL . $reviews['photo'] :  NV_BASE_SITEURL. 'themes/'.$module_info['template'].'/images/users/no_avatar.png';
			$xtpl->assign( 'REVIEWS', $reviews );
			$xtpl->parse( 'main.reviews' );
		}
		
		
		$xtpl->assign( 'REVIEWS_TOTAL', (int)$data['reviews_total'] );
		$xtpl->assign( 'REVIEWS_AVG', round ( $data['review_rating'] ) );
		$xtpl->assign( 'REVIEWS_AVG_PERCENT', $data['review_rating'] * 20 );
 
		for( $a = 5; $a > 0; --$a )
		{
			$rate = isset(  $data['data_reviews_rating'][$a] ) ? $data['data_reviews_rating'][$a] : array( $a => array( 'rating'=> $a, 'percent'=> 0, 'total'=> 0  )  );
			
			$rate['percent'] = $rate['total']/(int)$data['reviews_total'] * 100;
 
			$xtpl->assign( 'RATE', $rate );
			$xtpl->parse( 'main.rating' );
		}
 
		
	}
	
	
	
	// sản phẩm liên quan
	if( ! empty( $data_others ) )
	{
		$hmtl = view_other_in_catagory( $data_others );
		$xtpl->assign( 'OTHER', $hmtl );
		
		$xtpl->parse( 'main.other' );
	}
	
	// sản phẩm vừa xem
	if( ! empty( $array_other_view ) )
	{
		$hmtl = view_home_all( $array_other_view );
		$xtpl->assign( 'OTHER_VIEW', $hmtl );
		$xtpl->parse( 'main.other_view' );
	}

	$xtpl->assign( 'LINK_LOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=loadcart' );
	$xtpl->assign( 'THEME_URL', NV_BASE_SITEURL . 'themes/' . $module_info['template'] );
	$xtpl->assign( 'LINK_PRINT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=print_pro&product_id=' . $data['product_id'] );
 	
	//hiển thị đánh giá sản phẩm
	// if( ! empty( $data['allowed_rating'] ) )
	// { 
		// $ratingwidth = ( $data['total_rating'] > 0 ) ? ( $data['total_rating'] * 100 / ( $data['click_rating'] * 5 ) ) * 0.01 : 0;
 
		// $xtpl->assign( 'RATINGVALUE', ( $data['total_rating'] > 0 ) ? round( $data['total_rating']/$data['click_rating'], 1) : 0 );
		// $xtpl->assign( 'RATINGCOUNT', $data['total_rating'] );
		// $xtpl->assign( 'RATINGWIDTH', round( $ratingwidth, 2) );
		// $xtpl->assign( 'LINK_RATE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rate&product_id=' . $data['product_id'] );
	
		// $xtpl->parse( 'main.allowed_rating' );
		// $xtpl->parse( 'main.allowed_rating_js' );
	// }
	// if( ! empty( $data['allowed_send'] ) ) $xtpl->parse( 'main.allowed_send' );
	// if( ! empty( $data['allowed_print'] ) )
	// {
		// $xtpl->parse( 'main.allowed_print' );
		// $xtpl->parse( 'main.allowed_print_js' );
	// }
	// if( ! empty( $data['allowed_save'] ) ) $xtpl->parse( 'main.allowed_save' );

	if( defined( 'NV_IS_MODADMIN' ) )
	{
		$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data['product_id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data['product_id'] ) );
		$xtpl->parse( 'main.adminlink' );
	}
	if( ! defined( 'NV_IS_USER' ) )
	{
		$xtpl->parse( 'main.login_form' );
	}
	
	$xtpl->parse( 'main' );
	
	unset( $product_info, $data, $array_other_view, $data_others );
 
	return $xtpl->text( 'main' );
}

function theme_product_video( $data )
{
	global $module_info, $lang_module, $module_file, $global_config, $module_name, $ProductGeneral->config, $productCategory;
	$xtpl = new XTemplate( 'product_video.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	if( !empty( $data ) )
	{
		foreach( $data as $video )
		{
			preg_match( '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i', $video['url'], $match) ;
 
			$video['youtube_id'] = isset( $match[1] ) ? $match[1] : '';	
		
			$xtpl->assign( 'VIDEO', $video );
			$xtpl->parse( 'main.loop' );
		}
		
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
 
function theme_login_form( $referer )
{
	global $module_info, $lang_module, $client_info, $module_file, $global_config, $module_name, $ProductGeneral->config, $productCategory;
	$xtpl = new XTemplate( 'theme_login_form.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'REDIRECT', $referer );
	$xtpl->assign( 'USER_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login' );
	$xtpl->assign( 'USER_REGISTER', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=register' );
	$xtpl->assign( 'USER_LOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostpass' );
	$xtpl->assign( 'LANG', $lang_global );
	$xtpl->assign( 'LANG', $lang_global );
 
	if( in_array( $global_config['gfx_chk'], array(
		2,
		4,
		5,
		7 ) ) )
	{
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
		$xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha' );
		$xtpl->assign( 'GFX_MAXLENGTH', NV_GFX_NUM );
		$xtpl->parse( 'main.captcha' );
	}

	if( defined( 'NV_OPENID_ALLOWED' ) )
	{
		$xtpl->assign( 'OPENID_IMG_SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/users/openid_small.gif' );
		$xtpl->assign( 'OPENID_IMG_WIDTH', 24 );
		$xtpl->assign( 'OPENID_IMG_HEIGHT', 24 );
		$assigns = array();
		foreach( $global_config['openid_servers'] as $server )
		{
			$assigns['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=oauth&amp;server=" . $server . "&amp;nv_redirect=" . nv_base64_encode( $client_info['selfurl'] );
			$assigns['title'] = ucfirst( $server );
			$assigns['img_src'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/users/" . $server . ".gif";
			$assigns['img_width'] = $assigns['img_height'] = 24;

			$xtpl->assign( 'OPENID', $assigns );
			$xtpl->parse( 'main.openid.server' );
		}
		$xtpl->parse( 'main.openid' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
 
function theme_answer_list( $data, $data_answer )
{
	global $module_info, $lang_module, $module_file, $global_config, $module_name, $ProductGeneral->config, $productCategory;
	$xtpl = new XTemplate( 'theme_answer_list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'DATA', $data );
	if( !empty( $data_answer ) ) 
	{
		foreach( $data_answer as $answer )
		{ 
			$answer['token'] = md5( $product_id . $answer['faq_id'] . $global_config['sitekey'] . session_id() );
			$answer['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=faq/' . $alias . '-' . $faq['faq_id'] . $global_config['rewrite_exturl'], true );
			$xtpl->assign( 'ANSWER', $answer );
 
			$xtpl->parse( 'main.loop' );
		}
		
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
function theme_faq_list( $data, $product_id, $alias )
{
	global $module_info, $lang_module, $module_file, $global_config, $module_name, $ProductGeneral->config, $productCategory;
	$xtpl = new XTemplate( 'theme_faq_list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'PRODUCT_ID', $product_id );
	if( !empty( $data ) ) 
	{
		foreach( $data as $faq )
		{ 
			$faq['token'] = md5( $product_id . $faq['faq_id'] . $global_config['sitekey'] . session_id() );
			$faq['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=faq/' . $alias . '-' . $faq['faq_id'] . $global_config['rewrite_exturl'], true );
			$xtpl->assign( 'FAQ', $faq );
			
			if( !empty( $faq['subcontent'] ) )
			{
				foreach( $faq['subcontent'] as $subfaq )
				{
					$xtpl->assign( 'FAQSUB', $subfaq );
					$xtpl->parse( 'main.loop.subloop' );
				}
			}
			
			$xtpl->parse( 'main.loop' );
		}
		
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function print_product( $data_content, $data_unit, $page_title )
{
	global $module_info, $lang_module, $module_file, $global_config, $module_name, $ProductGeneral->config, $productCategory;

	$xtpl = new XTemplate( 'print_pro.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	
	
	
	if( ! empty( $data_content ) )
	{
		$xtpl->assign( 'proid', $data_content['id'] );
		$data_content['money_unit'] = ( $data_content['money_unit'] != '' ) ? $data_content['money_unit'] : 'N/A';
		$data_content[NV_LANG_DATA . '_address'] = ( $data_content[NV_LANG_DATA . '_address'] != '' ) ? $data_content[NV_LANG_DATA . '_address'] : 'N/A';
		$xtpl->assign( 'SRC_PRO', $data_content['thumb'] );
		$xtpl->assign( 'SRC_PRO_LAGE', $data_content['thumb'] );
		$xtpl->assign( 'TITLE', $data_content[NV_LANG_DATA . '_title'] );
		$xtpl->assign( 'NUM_VIEW', $data_content['hitstotal'] );
		$xtpl->assign( 'DATE_UP', $lang_module['detail_dateup'] . date( ' d-m-Y ', $data_content['addtime'] ) . $lang_module['detail_moment'] . date( " h:i'", $data_content['addtime'] ) );
		$xtpl->assign( 'DETAIL', $data_content[NV_LANG_DATA . '_bodytext'] );
		//$xtpl->assign( 'PRICE', nv_currency_conversion( $data_content['product_price'], $data_content['money_unit'], $ProductGeneral->config['config_money_unit'] ) );
		$xtpl->assign( 'money_unit', $ProductGeneral->config['config_money_unit'] );
		$xtpl->assign( 'pro_unit', $data_unit['title'] );
		$xtpl->assign( 'address', $data_content[NV_LANG_DATA . '_address'] );
		$xtpl->assign( 'product_number', $data_content['product_number'] );
 

		$xtpl->assign( 'site_name', $global_config['site_name'] );
		$xtpl->assign( 'url', $global_config['site_url'] );
		$xtpl->assign( 'contact', $global_config['site_email'] );
		$xtpl->assign( 'page_title', $page_title );
	}
	if( $ProductGeneral->config['config_active_price'] == '1' ) $xtpl->parse( 'main.price' );
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function cart_coupon( )
{
	global $getCountry, $ProductContent, $ProductCurrency, $module_data, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $productCategory, $global_config;
	
	$xtpl = new XTemplate( 'coupon.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file. '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $ProductGeneral->getLangSite( 'coupon', 'checkout' ) );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	if( isset( $_SESSION[$module_data . '_coupon'] ) )
	{
		$coupon = $_SESSION[$module_data . '_coupon'];
			
	}else
	{
		$coupon = '';
	}
	$xtpl->assign( 'COUPON', $coupon );

	$xtpl->parse( 'main' );
		
	return $xtpl->text( 'main' );
 
}

function cart_voucher( )
{
	global $getCountry, $ProductContent, $ProductCurrency, $module_data, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $productCategory, $global_config;
	
	$xtpl = new XTemplate( 'voucher.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file. '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $ProductGeneral->getLangSite( 'voucher', 'checkout' ) );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	if( isset( $_SESSION[$module_data . '_voucher'] ) )
	{
		$voucher = $_SESSION[$module_data . '_voucher'];
			
	}else
	{
		$voucher = '';
	}
	$xtpl->assign( 'VOUCHER', $voucher );

	$xtpl->parse( 'main' );
		
	return $xtpl->text( 'main' );
 
}

function cart_shipping( )
{
	global $getCountry, $ProductGeneral, $productRegistry, $ProductContent, $ProductCurrency, $module_data, $module_info, $lang_module, $module_file, $module_name, $global_product_group, $productCategory, $global_config;
	
	if( empty( $ProductContent ) ) $ProductContent = new shops_product( $productRegistry );
	$shipping_config = $ProductGeneral->getSetting( 'shipping', $ProductGeneral->store_id );
	if( !empty( $shipping_config ) )
	{
	
		if ( $shipping_config['shipping_status'] && $shipping_config['shipping_estimator'] && $ProductContent->hasShipping() ) 
		{
			$xtpl = new XTemplate( 'shipping.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file. '/checkout' );
			$xtpl->assign( 'LANG', $lang_module );
			$xtpl->assign( 'LANGE', $ProductGeneral->getLangSite( 'shipping', 'checkout' ) );
			$xtpl->assign( 'TEMPLATE', $module_info['template'] );
			$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
			$xtpl->assign( 'HOME_LINK', nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name , true ) );
			$xtpl->assign( 'CHECKOUT_LINK', nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout' , true ) );
			
			$shipping_address = isset( $_SESSION[$module_data . '_shipping_address'] ) ? $_SESSION[$module_data . '_shipping_address'] : array();
			$shipping_address['country_id'] = isset( $shipping_address['country_id'] ) ? $shipping_address['country_id'] : 0;
 
			foreach( $getCountry as $country_id => $value )
			{	
				$value['selected'] = ( $country_id == $shipping_address['country_id'] ) ? 'selected="selected"': '';
				$xtpl->assign( 'COUNTRY', $value );
				$xtpl->parse( 'main.country' );
			}
			
			$xtpl->assign( 'SHIP_ADD', $shipping_address );	
			
			$xtpl->parse( 'main' );
			
			return $xtpl->text( 'main' );
			
		}
	}
	else return ;
 
}

function cart_product( $data_content, $error )
{
	global $getCountry, $ProductCurrency, $nv_Request, $module_data, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $global_product_group, $productCategory, $global_config;

	$xtpl = new XTemplate( 'cart.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
 
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $ProductGeneral->getLangSite( 'cart', 'checkout' ) );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'HOME_LINK', nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name , true ) );
	$xtpl->assign( 'CHECKOUT_LINK', nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout' , true ) );
	
	// thông báo thành công
	if( isset( $_SESSION[$module_data . '_success'] ) )
	{
		$xtpl->assign( 'SUCCESS', $_SESSION[$module_data . '_success'] );

		$xtpl->parse( 'main.success' );

		unset( $_SESSION[$module_data . '_success'] );

	}	
	
	$price_total = 0;
	if( ! empty( $data_content['product'] ) )
	{
 
		foreach( $data_content['product'] as $product )
		{  
			$product['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$product['category_id']]['alias'] . '/' . $product['alias'] . $global_config['rewrite_exturl'], true );

			if( $product['thumb'] == 1 )
			{
				$product['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $product['image'];
			}
			elseif( $product['thumb'] == 2 )
			{
				$product['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $product['image'];
			}
			elseif( $product['thumb'] == 3 )
			{
				$product['thumb'] = $product['image'];
			}
			else
			{
				$product['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $mod_file . '/no-image.jpg';
			}
			$price_total += $product['total'];
			// định dạng giá sản phẩm
			$product['price'] = $ProductCurrency->format( $product['price']  );
			$product['total'] = $ProductCurrency->format( $product['total']  );
			
			$xtpl->assign( 'PRODUCT', $product );
					
			if( ! empty( $product['thumb'] ) )
			{
				$xtpl->parse( 'main.data.product.thumb' );
			}
			if( $product['option'] )
			{
				foreach( $product['option'] as $option )
				{
					$xtpl->assign( 'OPTION', $option );
					$xtpl->parse( 'main.data.product.option' );
				}
			}	
			$xtpl->parse( 'main.data.product' );
 
		}
		
		foreach( $data_content['vouchers'] as $voucher )
		{
			$xtpl->assign( 'VOUCHER', $voucher );
			$xtpl->parse( 'main.data.voucher' );
		} 
		
		foreach( $data_content['totals'] as $value )
		{
			$xtpl->assign( 'TOTAL', $value );
			$xtpl->parse( 'main.data.looptotal' );
				
		}
 
		
		
		// gọi function coupon nếu được kích hoạt và sản phẩm yêu cầu
		$xtpl->assign( 'COUPON', cart_coupon() );
		
		// gọi function voucher nếu được kích hoạt và sản phẩm yêu cầu	
		$xtpl->assign( 'VOUCHER', cart_voucher() );

		// gọi function shipping nếu được kích hoạt và sản phẩm yêu cầu
		$xtpl->assign( 'SHIPPING', cart_shipping() );
				
		$xtpl->parse( 'main.data' );
	}
	else
	{
		
		$xtpl->parse( 'main.empty' );
	} 
 
	
	$xtpl->parse( 'main' );
	
 
	return $xtpl->text( 'main' );
}

function checkout_guest( $data, $lang_ext )
{
	global $module_data, $module_name, $module_file, $lang_module, $lang_ext, $ProductGeneral->config, $user_info, $module_info, $global_config;
 
	$getInformation = getInformation();
	$getCountry = getCountry();
 
	$xtpl = new XTemplate( 'guest.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	
	$link_agree = NV_MY_DOMAIN . nv_url_rewrite( SHOPS_LINK . 'info/'. $getInformation[$ProductGeneral->config['config_account_id']]['alias'] . '-' . $ProductGeneral->config['config_account_id'], true );
	$xtpl->assign( 'AGREE', sprintf( $lang_ext['text_agree'], $link_agree, $getInformation[$ProductGeneral->config['config_account_id']]['title'] ) );
 
	
	foreach( $getCountry as $country_id => $value )
	{
		$xtpl->assign( 'COUNTRY', array( 'key'=> $country_id, 'name'=> $value['name'], 'selected' => ( $country_id == $data['country_id'] ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.country' );
 
	}
	
	if( $data['shipping_required'] )
	{
		
		$xtpl->assign( 'shipping_address_checked', ( $data['shipping_address'] == 1 ) ? 'checked="checked"' : '' );
		$xtpl->parse( 'main.shipping_address' );	
	}
	$xtpl->parse( 'main' );	
	return $xtpl->text( 'main' ); 
}

function checkout_guest_shipping( $data, $lang_ext )
{
	global $module_data, $module_name, $module_file, $lang_module, $lang_ext, $ProductGeneral->config, $user_info, $module_info, $global_config;
	
	$xtpl = new XTemplate( 'guest_shipping.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	
	$information = $data['information'];
	$link_agree = NV_MY_DOMAIN . nv_url_rewrite( SHOPS_LINK . 'info/'. $getInformation[$ProductGeneral->config['config_account_id']]['alias'] . '-' . $ProductGeneral->config['config_account_id'], true );
	$xtpl->assign( 'AGREE', sprintf( $lang_ext['text_agree'], $link_agree, $getInformation[$ProductGeneral->config['config_account_id']]['title'] ) );
 
	
	foreach( $data['countries'] as $country_id => $value )
	{
		$xtpl->assign( 'COUNTRY', array( 'key'=> $country_id, 'name'=> $value['name'], 'selected' => ( $country_id == $data['country_id'] ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.country' );
 
	}
	
	$xtpl->parse( 'main' );	
	return $xtpl->text( 'main' ); 
}

function checkout_register( $data, $lang_ext )
{
	global $module_data, $module_name, $module_file, $lang_module, $lang_ext, $ProductGeneral->config, $user_info, $module_info, $global_config;
 
	$getInformation = getInformation();
	$getCountry = getCountry();
 
	$xtpl = new XTemplate( 'register.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	
	$link_agree = NV_MY_DOMAIN . nv_url_rewrite( SHOPS_LINK . 'info/'. $getInformation[$ProductGeneral->config['config_account_id']]['alias'] . '-' . $ProductGeneral->config['config_account_id'], true );
	$xtpl->assign( 'AGREE', sprintf( $lang_ext['text_agree'], $link_agree, $getInformation[$ProductGeneral->config['config_account_id']]['title'] ) );
 
	
	foreach( $getCountry as $country_id => $value )
	{
		$xtpl->assign( 'COUNTRY', array( 'key'=> $country_id, 'name'=> $value['name'], 'selected' => ( $country_id == $data['country_id'] ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.country' );
 
	}
	
	$xtpl->parse( 'main' );	
	return $xtpl->text( 'main' ); 
}

function checkout_login( $data, $lang_ext  )
{
	global $module_data, $module_name, $module_file, $lang_module, $ProductGeneral->config, $user_info, $module_info, $global_config;
	
 
	$xtpl = new XTemplate( 'login.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
 
	$xtpl->parse( 'main' );	
	return $xtpl->text( 'main' ); 
}

function checkout_payment_address( $data, $lang_ext )
{
	global $module_data, $module_name, $module_file, $lang_module, $ProductGeneral->config, $module_info, $global_config;
	
 
	$xtpl = new XTemplate( 'payment_address.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'ZONE_LOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=ajax&zone' );
	$xtpl->assign( 'DATA', $data );
 
	foreach( $data['addresses'] as $address_id => $value )
	{
		$value['selected'] = ( $address_id == $data['address_id'] ) ? 'selected="selected"': '';
		$xtpl->assign( 'ADDRESS', $value );
		$xtpl->parse( 'main.addresses' );	
	}
	
	foreach( $data['countries'] as $country_id => $value )
	{
		$value['selected'] = ( $country_id == $data['country_id'] ) ? 'selected="selected"': '';
		$xtpl->assign( 'COUNTRY', $value );
		$xtpl->parse( 'main.country' );	
	}
 
	$xtpl->parse( 'main' );	
	return $xtpl->text( 'main' ); 
}

function checkout_payment_method( $data, $lang_ext  )
{
	global $module_data, $module_name, $module_file, $lang_module, $ProductGeneral->config, $module_info, $global_config;
	
 
	$xtpl = new XTemplate( 'payment_method.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	
	$data['agree'] = ( $data['agree'] ) ? 'checked="checked"' : '';
 
	$xtpl->assign( 'DATA', $data );
	
 
	if( !empty( $data['payment_methods'] ) )
	{
 
		foreach( $data['payment_methods'] as $payment_methods )
		{
			$payment_methods['checked'] = ( $payment_methods['code'] == $data['code'] || ! $data['code'] ) ? 'checked="checked"':'';
 
			$xtpl->assign( 'LOOP', $payment_methods );
 			$xtpl->parse( 'main.payment_method' );	
 	
		}
 
	}
	
	if( !empty( $data['text_agree'] ) )
	{	
 
		$xtpl->parse( 'main.text_agree' );	
	}else
	{
		$xtpl->parse( 'main.text_noagree' );	
	}
	$xtpl->parse( 'main' );		
	return $xtpl->text( 'main' ); 
}

function checkout_shipping_address( $data, $lang_ext  )
{
	global $module_data, $module_name, $module_file, $lang_module, $ProductGeneral->config, $module_info, $global_config;
	
 
	$xtpl = new XTemplate( 'shipping_address.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'ZONE_LOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=ajax&zone' );
	$xtpl->assign( 'DATA', $data );
 
	foreach( $data['addresses'] as $address_id => $value )
	{
		$value['selected'] = ( $address_id == $data['address_id'] ) ? 'selected="selected"': '';
		$xtpl->assign( 'ADDRESS', $value );
		$xtpl->parse( 'main.addresses' );	
	}
	
	foreach( $data['countries'] as $country_id => $value )
	{
		$value['selected'] = ( $country_id == $data['country_id'] ) ? 'selected="selected"': '';
		$xtpl->assign( 'COUNTRY', $value );
		$xtpl->parse( 'main.country' );	
	}
 
	$xtpl->parse( 'main' );	
	return $xtpl->text( 'main' ); 
}

function checkout_shipping_method( $data, $lang_ext  )
{
	global $module_data, $module_name, $module_file, $lang_module, $ProductGeneral->config, $module_info, $global_config;
	
 
	$xtpl = new XTemplate( 'shipping_method.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'DATA', $data );
	
	if( !empty( $data['shipping_methods'] ) )
	{

		foreach( $data['shipping_methods'] as $shipping_method )
		{
			$xtpl->assign( 'SHIPING', $shipping_method );
			if( empty( $shipping_method['error'] ) )
			{
				foreach ( $shipping_method['quote'] as $quote ) 
				{ 
					$quote['checked'] = ( ($quote['code'] == $data['code']) || (! $data['code']) ) ? 'checked="checked"':'';
					$xtpl->assign( 'LOOP', $quote );
					$xtpl->parse( 'main.shipping.shipping_method' );	
				}	
			}else
			{
			
				$xtpl->parse( 'main.shipping.shipping_error' );	
			}
			$xtpl->parse( 'main.shipping' );	
			
		}
		$xtpl->parse( 'main' );	
	}
		
	return $xtpl->text( 'main' ); 
}

function checkout_confirm_method( $data, $lang_ext  )
{
	global $ProductCurrency, $user_info, $module_data, $module_name, $module_file, $productCategory, $lang_module, $ProductGeneral->config, $module_info, $global_config;
	

	$xtpl = new XTemplate( 'confirm.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'DATA', $data );
	if ( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'warning', $error['warning'] );
		$xtpl->parse( 'main.warning' );
	}
	if ( !isset( $data['redirect'] ) )
	{
		$price_total = 0;
		foreach( $data['products'] as $product )
		{  
			$product['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$product['category_id']]['alias'] . '/' . $product['alias'] . $global_config['rewrite_exturl'], true );

			if( $product['thumb'] == 1 )
			{
				$product['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $product['image'];
			}
			elseif( $product['thumb'] == 2 )
			{
				$product['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $product['image'];
			}
			elseif( $product['thumb'] == 3 )
			{
				$product['thumb'] = $product['image'];
			}
			else
			{
				$product['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
			}
			$price_total += $product['total'];
			// định dạng giá sản phẩm
			// $product['price'] = $ProductCurrency->format( $product['price']  );
			// $product['total'] = $ProductCurrency->format( $product['total']  );
			 
			$xtpl->assign( 'PRODUCT', $product );
					
			if( ! empty( $product['thumb'] ) )
			{
				$xtpl->parse( 'main.data.product.thumb' );
			}
			if( $product['option'] )
			{
				foreach( $product['option'] as $option )
				{
					$xtpl->assign( 'OPTION', $option );
					$xtpl->parse( 'main.data.product.option' );
				}
			}	
			$xtpl->parse( 'main.data.product' );
 
		} 
		
		foreach( $data['vouchers'] as $voucher )
		{
			$xtpl->assign( 'VOUCHER', $voucher );
			$xtpl->parse( 'main.data.voucher' );
		} 
		
		foreach( $data['totals'] as $value )
		{
			$xtpl->assign( 'TOTAL', $value );
			$xtpl->parse( 'main.data.looptotal' );
				
		}
		$xtpl->parse( 'main.data' );	
	}else
	{
		$xtpl->parse( 'main.product' );	
	}
	$xtpl->parse( 'main' );	
	return $xtpl->text( 'main' ); 
}

function uers_checkout( $lang_ext )
{
	global $ProductCurrency, $module_data, $user_info, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $global_config, $array_card_type, $array_month, $productCategory, $global_product_group, $getCountry;

	$xtpl = new XTemplate( 'checkout.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'LOGIN_LOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&login' );
	$xtpl->assign( 'LOGIN_SAVE_LOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&login&save' );
	$xtpl->assign( 'PAYMENT_ADDRESS_LOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&payment_address' );
	$xtpl->assign( 'PAYMENT_ADDRESS_SAVE_LOAD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout&payment_address&save' );
 
	if( empty( $user_info ) )
	{
		//checkout_login();
		$xtpl->parse( 'main.script_guest' );
	}else
	{
		$xtpl->parse( 'main.script_user' );
	}
 
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
 
function payment( $order_info, $data_content, $url_checkout, $intro_pay )
{
	global $ProductCurrency, $module_info, $lang_module, $module_file, $global_config, $module_name, $ProductGeneral->config, $global_product_group, $productCategory;
	$xtpl = new XTemplate( 'payment.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'dateup', date( 'd-m-Y', $data_content['order_time'] ) );
	$xtpl->assign( 'moment', date( "h:i' ", $data_content['order_time'] ) );
	$xtpl->assign( 'DATA', $data_content );
	$xtpl->assign( 'order_id', $order_info['order_id'] );

 
	
	foreach( $data_content['products'] as $product )
	{
		$product['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$product['category_id']]['alias'] . '/' . $product['alias'] . $global_config['rewrite_exturl'], true );

		if( $product['thumb'] == 1 )
		{
			$product['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $product['image'];
		}
		elseif( $product['thumb'] == 2 )
		{
			$product['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $product['image'];
		}
		elseif( $product['thumb'] == 3 )
		{
			$product['thumb'] = $product['image'];
		}
		else
		{
			$product['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
		}

		// Định dạng giá sản phẩm
		// $product['price'] = $ProductCurrency->format( $product['price'] );
		// $product['total'] = $ProductCurrency->format( $product['total'] );

		$xtpl->assign( 'PRODUCT', $product );

		if( ! empty( $product['thumb'] ) )
		{
			$xtpl->parse( 'main.product.thumb' );
		}
		if( $product['option'] )
		{
			foreach( $product['option'] as $option )
			{
				$xtpl->assign( 'OPTION', $option );
				$xtpl->parse( 'main.product.option' );
			}
		}
		$xtpl->parse( 'main.product' );
	}
	
	
	foreach( $data_content['totals'] as $value )
	{ 
		$value['value'] = $ProductCurrency->format( $value['value'] );
		$xtpl->assign( 'TOTAL', $value );
		$xtpl->parse( 'main.looptotal' );

	}
	
	if( ! empty( $url_checkout ) )
	{
		$xtpl->assign( 'note_pay', '' );
		foreach( $url_checkout as $value )
		{
			$xtpl->assign( 'DATA_PAYMENT', $value );
			$xtpl->parse( 'main.actpay.payment.paymentloop' );
		}
		if( ! empty( $intro_pay ) )
		{ 
			$xtpl->assign( 'intro_pay', $intro_pay );
			$xtpl->parse( 'main.actpay.intro_pay' );
		}
		$xtpl->parse( 'main.actpay.payment' );
	}

	if( $ProductGeneral->config['config_active_payment'] == '1' and $ProductGeneral->config['config_active_order'] == '1' and $ProductGeneral->config['config_active_price'] == '1' and $ProductGeneral->config['config_active_order_number'] == '0' ) $xtpl->parse( 'main.actpay' );
	$xtpl->assign( 'url_finsh', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name );
	$xtpl->assign( 'url_print', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=print&order_id=' . $data_content['order_id'] . '&checkss=' . md5( $data_content['order_id'] . $global_config['sitekey'] . session_id() ) );

	
 
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function print_pay( $data_content, $data_pro )
{
	global $module_info, $lang_module, $module_file, $ProductGeneral->config, $global_config;

	$xtpl = new XTemplate( 'print.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'dateup', date( 'd-m-Y', $data_content['order_time'] ) );
	$xtpl->assign( 'moment', date( "h:i' ", $data_content['order_time'] ) );
	$xtpl->assign( 'DATA', $data_content );
	$xtpl->assign( 'order_id', $data_content['order_id'] );

	$i = 0;
	foreach( $data_pro as $pdata )
	{
		$xtpl->assign( 'product_name', $pdata['title'] );
		$xtpl->assign( 'product_number', $pdata['product_number'] );
		$xtpl->assign( 'product_price', nv_number_format( $pdata['product_price'], nv_get_decimals( $ProductGeneral->config['config_money_unit'] ) ) );
		$xtpl->assign( 'product_unit', $pdata['product_unit'] );
		$xtpl->assign( 'link_pro', $pdata['link_pro'] );
		$xtpl->assign( 'pro_no', $i + 1 );
		if( $ProductGeneral->config['config_active_price'] == '1' ) $xtpl->parse( 'main.loop.price2' );
		$xtpl->parse( 'main.loop' );
		++$i;
	}
	if( ! empty( $data_content['order_note'] ) )
	{
		$xtpl->parse( 'main.order_note' );
	}
	$xtpl->assign( 'order_total', nv_number_format( $data_content['order_total'], nv_get_decimals( $ProductGeneral->config['config_money_unit'] ) ) );
	$xtpl->assign( 'unit', $data_content['unit_total'] );

	$payment = '';
	if( $data_content['transaction_status'] == 4 )
	{
		$payment = $lang_module['history_payment_yes'];
	}
	elseif( $data_content['transaction_status'] == 3 )
	{
		$payment = $lang_module['history_payment_cancel'];
	}
	elseif( $data_content['transaction_status'] == 2 )
	{
		$payment = $lang_module['history_payment_check'];
	}
	elseif( $data_content['transaction_status'] == 1 )
	{
		$payment = $lang_module['history_payment_send'];
	}
	elseif( $data_content['transaction_status'] == 0 )
	{
		$payment = $lang_module['history_payment_no'];
	}
	elseif( $data_content['transaction_status'] == -1 )
	{
		$payment = $lang_module['history_payment_wait'];
	}
	else
	{
		$payment = 'ERROR';
	}
	$xtpl->assign( 'payment', $payment );
	if( $ProductGeneral->config['config_active_price'] == '1' )
	{
		$xtpl->parse( 'main.price1' );
		$xtpl->parse( 'main.price3' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function history_order( $data_content, $link_check_order )
{
	global $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $global_config;

	$xtpl = new XTemplate( 'history_order.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$i = 0;

	foreach( $data_content as $data )
	{
		$xtpl->assign( 'order_code', $data['order_code'] );
		$xtpl->assign( 'history_date', date( 'd-m-Y', $data['order_time'] ) );
		$xtpl->assign( 'history_moment', date( "h:i' ", $data['order_time'] ) );
		$xtpl->assign( 'history_total', nv_number_format( $data['order_total'], nv_get_decimals( $ProductGeneral->config['config_money_unit'] ) ) );
		$xtpl->assign( 'unit_total', $data['unit_total'] );
		$xtpl->assign( 'note', $data['order_note'] );
		$xtpl->assign( 'URL_DEL_BACK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=history' );
		if( intval( $data['transaction_status'] ) == -1 )
		{
			$xtpl->assign( 'link_remove', $data['link_remove'] );
			$xtpl->parse( 'main.rows.remove' );
		}
		else
		{
			$xtpl->parse( 'main.rows.no_remove' );
		}
		$xtpl->assign( 'link', $data['link'] );

		/* transaction_status: Trang thai giao dich:
		0 - Giao dich moi tao
		1 - Chua thanh toan;
		2 - Da thanh toan, dang bi tam giu;
		3 - Giao dich bi huy;
		4 - Giao dich da hoan thanh thanh cong (truong hop thanh toan ngay hoac thanh toan tam giu nhung nguoi mua da phe chuan)
		*/
		if( $data['transaction_status'] == 4 )
		{
			$history_payment = $lang_module['history_payment_yes'];
		}
		elseif( $data['transaction_status'] == 3 )
		{
			$history_payment = $lang_module['history_payment_cancel'];
		}
		elseif( $data['transaction_status'] == 2 )
		{
			$history_payment = $lang_module['history_payment_check'];
		}
		elseif( $data['transaction_status'] == 1 )
		{
			$history_payment = $lang_module['history_payment_send'];
		}
		elseif( $data['transaction_status'] == 0 )
		{
			$history_payment = $lang_module['history_payment_no'];
		}
		elseif( $data['transaction_status'] == -1 )
		{
			$history_payment = $lang_module['history_payment_wait'];
		}
		else
		{
			$history_payment = 'ERROR';
		}

		$xtpl->assign( 'LINK_CHECK_ORDER', $link_check_order );
		$xtpl->assign( 'history_payment', $history_payment );
		$bg = ( $i % 2 == 0 ) ? 'class="bg"' : '';
		$xtpl->assign( 'bg', $bg );
		$xtpl->assign( 'TT', $i + 1 );
		if( $ProductGeneral->config['config_active_price'] == '1' ) $xtpl->parse( 'main.rows.price2' );
		$xtpl->parse( 'main.rows' );
		++$i;
	}
	if( $ProductGeneral->config['config_active_price'] == '1' )
	{
		$xtpl->parse( 'main.price1' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function search_theme( $key, $check_num, $date_array, $array_cat_search )
{
	global $module_name, $module_info, $module_file, $lang_module, $module_name, $global_config;

	$xtpl = new XTemplate( "search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'BASE_URL_SITE', NV_BASE_SITEURL );
	$xtpl->assign( 'TO_DATE', $date_array['to_date'] );
	$xtpl->assign( 'FROM_DATE', $date_array['from_date'] );
	$xtpl->assign( 'KEY', $key );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'OP_NAME', 'search' );

	foreach( $array_cat_search as $search_cat )
	{
		$xtpl->assign( 'SEARCH_CAT', $search_cat );
		$xtpl->parse( 'main.search_cat' );
	}
	for( $i = 0; $i <= 3; $i++ )
	{
		if( $check_num == $i ) $xtpl->assign( 'CHECK' . $i, "selected=\"selected\"" );
		else  $xtpl->assign( 'CHECK' . $i, "" );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function search_result_theme( $key, $numRecord, $per_pages, $pages, $array_content, $url_link, $catid )
{
	global $module_file, $module_info, $lang_module, $productCategory, $ProductGeneral->config, $global_config;

	$xtpl = new XTemplate( "search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );

	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'KEY', $key );

	$xtpl->assign( 'TITLE_MOD', $lang_module['search_modul_title'] );

	if( ! empty( $array_content ) )
	{
		foreach( $array_content as $value )
		{
			$listcatid = explode( ",", $value['listcatid'] );
			$catid_i = ( $catid > 0 ) ? $catid : end( $listcatid );
			$url = $productCategory[$catid_i]['link'] . '/' . $value['alias'] . "-" . $value['id'];

			$value['hometext'] = nv_clean60( $value['hometext'], 170 );

			$xtpl->assign( 'LINK', $url );
			$xtpl->assign( 'TITLEROW', BoldKeywordInStr( $value['title'], $key ) );
			$xtpl->assign( 'CONTENT', BoldKeywordInStr( $value['hometext'], $key ) . "..." );
 

			$xtpl->assign( 'IMG_SRC', $value['thumb'] );
			$xtpl->parse( 'results.result.result_img' );

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $value['id'] ) . "&nbsp;-&nbsp;" . nv_link_delete_page( $value['id'] ) );
				$xtpl->parse( 'results.result.adminlink' );
			}

			$xtpl->parse( 'results.result' );
		}
	}
	if( $numRecord == 0 )
	{
		$xtpl->assign( 'KEY', $key );
		$xtpl->assign( 'INMOD', $lang_module['search_modul_title'] );
		$xtpl->parse( 'results.noneresult' );
	}
	if( $numRecord > $per_pages ) // show pages
	{
		$url_link = $_SERVER['REQUEST_URI'];
		$in = strpos( $url_link, '&page' );
		if( $in != 0 ) $url_link = substr( $url_link, 0, $in );
		$generate_page = nv_generate_page( $url_link, $numRecord, $per_pages, $pages );
		$xtpl->assign( 'VIEW_PAGES', $generate_page );
		$xtpl->parse( 'results.pages_result' );
	}
	$xtpl->assign( 'MY_DOMAIN', NV_MY_DOMAIN );
	$xtpl->assign( 'NUMRECORD', $numRecord );
	$xtpl->parse( 'results' );
	return $xtpl->text( 'results' );
}

function email_new_order_voucher( $data )
{
	global $ProductCurrency, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $global_config, $productCategory;

	$xtpl = new XTemplate( "email_new_order_voucher.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . '/mail' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'DATA', $data );
	if( $message )
	{
		$xtpl->parse( 'main.message' );
	}  
 
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function email_new_order( $data )
{
	global $ProductCurrency, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $global_config, $productCategory;

	$xtpl = new XTemplate( "order.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . '/mail' );
	$xtpl->assign( 'LANG', $lang_module );
 	$xtpl->assign( 'DATA', $data );
 
	if ( $data['userid'] ) 
	{
		$xtpl->parse( 'main.userid' );
	}
	if ( $data['download'] ) 
	{
		$xtpl->parse( 'main.download' );
	}
	if ( $data['shipping_method'] ) 
	{
		$xtpl->parse( 'main.shipping_method' );
	}
	if ( $data['comment'] ) 
	{ 
		$xtpl->parse( 'main.comment' );
	}
	if ( $data['shipping_address'] ) 
	{
		$xtpl->parse( 'main.shipping_address0' );
		$xtpl->parse( 'main.shipping_address1' );
	}

	foreach( $data['products'] as $product )
	{
		$product['link'] = $global_config['site_url'] . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=redirect&product_id=' . $product['product_id'];
 
		$xtpl->assign( 'PRODUCT', $product );
 
		if( $product['option'] )
		{
			foreach( $product['option'] as $option )
			{
				$xtpl->assign( 'OPTION', $option );
				$xtpl->parse( 'main.product.option' );
			}
		}
 
		$xtpl->parse( 'main.product' );

	}

	foreach( $data['vouchers'] as $voucher )
	{ 
		 
		$xtpl->assign( 'VOUCHER', $voucher );
		$xtpl->parse( 'main.voucher' );

	}
	
	foreach( $data['totals'] as $value )
	{ 
		$xtpl->assign( 'TOTAL', $value );
		$xtpl->parse( 'main.total' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function payment_not_found( $data )
{
	global $ProductCurrency, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $global_config, $productCategory;

	$xtpl = new XTemplate( "not_found.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . '/payment' );
	$xtpl->assign( 'LANG', $lang_module );
 	$xtpl->assign( 'DATA', $data );
   
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function checkout_success( $data, $lang_ext )
{
	global $ProductCurrency, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $global_config, $productCategory;

	$xtpl = new XTemplate( "success.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . '/checkout' );
	$xtpl->assign( 'LANG', $lang_module );
 	$xtpl->assign( 'LANGE', $lang_ext );
 	$xtpl->assign( 'DATA', $data );
 
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function compare( $data_pro )
{
	global $lang_module, $module_file, $module_info, $ProductGeneral->config, $global_config;

	$xtpl = new XTemplate( "compare.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'module_name', $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

	foreach( $data_pro as $data )
	{
		$xtpl->assign( 'title_pro', $data['title'] );
		$xtpl->assign( 'link_pro', $data['link_pro'] );
		$xtpl->parse( 'main.title' );
		$xtpl->assign( 'link_pro', $data['link_pro'] );
		$xtpl->assign( 'img_pro', $data['thumb'] );
		$xtpl->parse( 'main.thumb' );
		$xtpl->assign( 'intro', nv_clean60( $data['hometext'], 200 ) );
		$xtpl->parse( 'main.hometext' );
		$xtpl->assign( 'bodytext', nv_clean60( $data['bodytext'], 400 ) );
		$xtpl->parse( 'main.bodytext' );
		$xtpl->assign( 'id', $data['id'] );
		$xtpl->parse( 'main.delete' );

		if( ! empty( $ProductGeneral->config['config_show_model'] ) and ! empty( $data['model'] ) )
		{
			$xtpl->assign( 'MODELl', $data['model'] );
		}
		else
		{
			$xtpl->assign( 'model', 'N/A' );
		}
		$xtpl->parse( 'main.model' );

		//$price = nv_currency_conversion( $data['product_price'], $data['money_unit'], $ProductGeneral->config['config_money_unit'], $data['discount_id'] );
		if( $ProductGeneral->config['config_active_price'] == '1' )
		{
			if( $data['showprice'] == '1' )
			{
				$xtpl->assign( 'PRICE', $price );
				$xtpl->parse( 'main.product_price' );
			}
			else
			{
				$xtpl->parse( 'main.contact' );
			}
		}

		$xtpl->assign( 'PRICE', $price );
		$xtpl->parse( 'main.discount' );

		$xtpl->assign( 'promotional', $data['promotional'] );
		$xtpl->parse( 'main.promotional' );

		$xtpl->assign( 'warranty', $data['warranty'] );
		$xtpl->parse( 'main.warranty' );

		if( ! empty( $data['custom'] ) )
		{
			$array_custom = unserialize( $data['custom'] );
			foreach( $array_custom as $key => $custom )
			{
				if( ! empty( $custom ) )
				{
					$xtpl->assign( 'custom', array( 'lang' => $lang_module['custom_' . $key], 'title' => $custom ) );
					$xtpl->parse( 'main.custom_field.custom.loop' );
				}
			}
			$xtpl->parse( 'main.custom_field.custom' );
		}

		if( ! empty( $data[NV_LANG_DATA . '_custom'] ) )
		{
			$array_custom_lang = unserialize( $data[NV_LANG_DATA . '_custom'] );
			foreach( $array_custom_lang as $key => $custom_lang )
			{
				if( ! empty( $custom_lang ) )
				{
					$xtpl->assign( 'custom_lang', array( 'lang' => $lang_module['custom_' . $key], 'title' => $custom_lang ) );
					$xtpl->parse( 'main.custom_field.custom_lang.loop' );
				}
			}
			$xtpl->parse( 'main.custom_field.custom_lang' );
		}
		$xtpl->parse( 'main.custom_field' );

	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function wishlist( $data_content, $html_pages = '' )
{
	global $module_info, $lang_module, $module_file, $ProductGeneral->config, $global_config, $op, $array_displays, $array_wishlist_id, $module_name;

	$xtpl = new XTemplate( 'wishlist.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$xtpl->assign( 'LANG', $lang_module );

	$xtpl->assign( 'CSS_MODEL', ! empty( $ProductGeneral->config['config_show_model'] ) ? ' show-product-code' : '' );

	if( ! empty( $data_content ) )
	{
		foreach( $data_content as $data )
		{
			$xtpl->assign( 'ID', $data['id'] );
			$xtpl->assign( 'LINK', $data['link_pro'] );
			$xtpl->assign( 'TITLE', $data['title'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $data['title'], 40 ) );
			$xtpl->assign( 'IMG_SRC', $data['thumb'] );
			$xtpl->assign( 'LINK_ORDER', $data['link_order'] );
 
 			$xtpl->assign( 'MODELl', $data['model'] );


			if( $ProductGeneral->config['config_active_order'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
					if( $data['product_number'] > 0 )
					{
						$xtpl->parse( 'main.items.order' );
					}
					else
					{
						$xtpl->parse( 'main.items.product_empty' );
					}
				}
			}

			if( $ProductGeneral->config['config_active_price'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
					//$price = nv_currency_conversion( $data['product_price'], $data['money_unit'], $ProductGeneral->config['config_money_unit'], $data['discount_id'] );
					$xtpl->assign( 'PRICE', $price );
					if( $data['discount_id'] and $price['discount_percent'] > 0 )
					{
						$xtpl->parse( 'main.items.price.discounts' );
						$xtpl->parse( 'main.items.price.discounts.standard' );
					}
					else
					{
						$xtpl->parse( 'main.items.price.no_discounts' );
					}
					$xtpl->parse( 'main.items.price' );
				}
				else
				{
					$xtpl->parse( 'main.items.contact' );
				}
			}

 
			if( ! empty( $ProductGeneral->config['config_show_model'] ) and ! empty( $data['model'] ) )
			{
				$xtpl->parse( 'main.items.model' );
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $data['id'] ) . '&nbsp;-&nbsp;' . nv_link_delete_page( $data['id'] ) );
				$xtpl->parse( 'main.items.adminlink' );
			}

			// So sanh san pham
			if( $ProductGeneral->config['config_show_compare'] == 1 )
			{
				if( isset( $_SESSION[$module_name . '_array_id'] ) )
				{
					$array_id = $_SESSION[$module_name . '_array_id'];
					$array_id = unserialize( $array_id );
				}
				else
				{
					$array_id = array();
				}

				if( ! empty( $array_id ) )
				{
					$ch = ( in_array( $data['id'], $array_id ) ) ? ' checked="checked"' : '';
					$xtpl->assign( 'ch', $ch );
				}

				$xtpl->parse( 'main.items.compare' );
			}

			if( $data['discount_id'] and $price['discount_percent'] > 0 )
			{
				$xtpl->parse( 'main.items.discounts' );
			}

			$xtpl->parse( 'main.items' );
		}

		if( ! empty( $html_pages ) )
		{
			$xtpl->assign( 'generate_page', $html_pages );
			$xtpl->parse( 'main.pages' );
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function ajax_search_box( $data_content, $array_tags )
{
	global $ProductCurrency, $productRegistry, $ProductTax, $global_config, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $array_wishlist_id;

	$xtpl = new XTemplate( 'block.search_box.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$a = 1;
	if( ! empty( $array_tags ) )
	{
		foreach( $array_tags as $tags )
		{
			$xtpl->assign( 'TAGS', $tags );
			$xtpl->parse( 'search_content.tags' );
			++$a;
			if( $a == 5 ) break;
		}

	}
	if( ! empty( $data_content ) )
	{
		if( empty( $ProductTax ) ) $ProductTax = new shops_tax( $productRegistry );
		foreach( $data_content as $data )
		{
			if( $data['thumb'] == 1 )
			{
				$data['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $data['image'];
			}
			elseif( $data['thumb'] == 2 )
			{
				$data['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $data['image'];
			}
			elseif( $data['thumb'] == 3 )
			{
				$data['thumb'] = $data['image'];
			}
			else
			{
				$data['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
			}
			if( $ProductGeneral->config['config_active_price'] == '1' )
			{
				if( $data['showprice'] == '1' )
				{
 

					// định dạng lại giá gốc
					$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $data['price'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

					if( $data['price'] )
					{

						if( ( float )$data['special'] )
						{

							// định dạng lại giá sản phẩm giảm giá đặc biệt
							$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $data['special'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ) );

							// giá giảm đặc biệt theo nhóm khách hàng
							$xtpl->assign( 'PRICE_NEW', $data['special'] );

							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'search_content.data.loop.price.discounts' );

						}
						else
						{
							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'search_content.data.loop.price.no_discounts' );
						}
 

					}

					$xtpl->parse( 'search_content.data.loop.price' );
				}
				else
				{
					$xtpl->parse( 'search_content.data.loop.contact' );
				}
			}

			$xtpl->assign( 'DATA', $data );
			$xtpl->parse( 'search_content.data.loop' );
		}
		$xtpl->parse( 'search_content.data' );
	}

	$xtpl->parse( 'search_content' );
	return $xtpl->text( 'search_content' );
}

function infomation_theme( $data )
{
	global $ProductCurrency, $ProductTax, $global_config, $module_info, $lang_module, $module_file, $module_name, $ProductGeneral->config, $array_wishlist_id;

	$xtpl = new XTemplate( 'infomation.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	if( ! empty( $data ) )
	{ 
		$xtpl->assign( 'DATA', $data );
		$xtpl->parse( 'main.data' ); 
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}