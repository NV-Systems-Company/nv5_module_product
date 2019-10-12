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

function ThemeProductViewByCategory( $dataContent )
{
	global $ProductGeneral, $ProductCurrency, $ProductTax, $productRegistry, $nv_Request, $module_info, $global_config, $module_file, $module_name, $module_data, $lang_module, $getWishlistProductId;

	$xtpl = new XTemplate( 'ThemeProductViewByCategory.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'CSS_MODEL', ! empty( $ProductGeneral->config['config_show_model'] ) ? ' show-product-code' : '' );

	if( ! empty( $dataContent ) )
	{
		foreach( $dataContent as $data )
		{

			if( $data['product_total'] > 0 )
			{

				$xtpl->assign( 'CATEGORY_NAME', $data['name'] );
				$xtpl->assign( 'CATEGORY_LINK', $data['link'] );
				$xtpl->assign( 'CATEGORY_ID', $data['category_id'] );
				$xtpl->assign( 'PRODUCT_TOTAL', $data['product_total'] );
				

				if( $data['content'] )
				{
					foreach( $data['content'] as $data )
					{
						$data['token'] = md5( $nv_Request->session_id . $global_config['sitekey'] . $data['product_id'] );

						//Tinh trang don hang
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

						$data['name_short'] = nv_clean60( $data['name'], 60 );

						$xtpl->assign( 'PRODUCT', $data );

						if( $ProductGeneral->config['config_active_order'] == 1 )
						{
							if( $data['showprice'] == 1 )
							{
								if( $data['quantity'] > 0 )
								{
									$xtpl->parse( 'main.category.product.order' );
								}
								else
								{
									$xtpl->parse( 'main.category.product.product_empty' );
								}
							}
						}
						if( $ProductGeneral->config['config_active_price'] == 1 )
						{
							if( $data['showprice'] == 1 )
							{

								// Hien phan tram giam gia neu co
								if( ( float )$data['special'] )
								{
									$percent = ( ( ( float )$data['price'] - ( float )$data['special'] ) / ( float )$data['price'] ) * 100;

									$xtpl->assign( 'PERCENT', floor( $percent ) );
									$xtpl->parse( 'main.category.product.percent' );
								}

								//Dinh dang lai gia goc
								$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $data['price'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $module_data . '_currency', 'session' ) );

								if( $data['price'] )
								{

									if( ( float )$data['special'] )
									{

										//Dinh dang lai gia san pham dac biet
										$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $data['special'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $module_data . '_currency', 'session' ) );

										//Giam gia dac biet theo nhom khach hang
										$xtpl->assign( 'PRICE_NEW', $data['special'] );

										// Giá gốc
										$xtpl->assign( 'PRICE', $data['price'] );

										$xtpl->parse( 'main.category.product.price.discounts' );

									}
									else
									{
										// Giá gốc
										$xtpl->assign( 'PRICE', $data['price'] );

										$xtpl->parse( 'main.category.product.price.no_discounts' );
									}

									// Hiện giá thuế phí nếu cấu hình hiện
									if( $ProductGeneral->config['config_tax'] )
									{
										// Định dạng lại giá thuế phí
										$data['tax'] = $ProductCurrency->format( ( float )$data['special'] ? $data['special'] : $data['price'], $nv_Request->get_string( $module_data . '_currency', 'session' ) );
									}
									else
									{
										$data['tax'] = false;
									}

								}

								$xtpl->parse( 'main.category.product.price' );
							}
							else
							{
								$xtpl->parse( 'main.category.product.contact' );
							}
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

							$xtpl->parse( 'main.category.product.compare' );
						}

						// San pham yeu thich
						if( $ProductGeneral->config['config_active_wishlist'] )
						{
							if( ! empty( $getWishlistProductId ) )
							{
								if( in_array( $data['product_id'], $getWishlistProductId ) )
								{
									$xtpl->parse( 'main.category.product.wishlist.disabled' );
								}
							}
							$xtpl->parse( 'main.category.product.wishlist' );
						}

						$xtpl->parse( 'main.category.product' );
					}
				}
				$xtpl->parse( 'main.category' );
			}
		}

	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function ThemeProductViewGrid( $dataContent, $generatePage )
{
	global $ProductGeneral, $ProductCurrency, $ProductTax, $productCategory, $productRegistry, $category_id, $nv_Request, $module_info, $global_config, $module_file, $module_name, $module_data, $lang_module, $getWishlistProductId;

	$xtpl = new XTemplate( 'ThemeProductViewGrid.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'CSS_MODEL', ! empty( $ProductGeneral->config['config_show_model'] ) ? ' show-product-code' : '' );

	$xtpl->assign( 'CATEGORY_NAME', $productCategory[$category_id]['name'] );
	$xtpl->assign( 'CATEGORY_LINK', $productCategory[$category_id]['link'] );
	$xtpl->assign( 'CATEGORY_ID', $productCategory[$category_id]['category_id'] );
	$xtpl->assign( 'PRODUCT_TOTAL', $productCategory[$category_id]['product_total'] );
	$xtpl->assign( 'COLUMNS', 24/$productCategory[$category_id]['columns_in_body'] );
	if( ! empty( $dataContent ) )
	{
		foreach( $dataContent as $data )
		{

			$data['token'] = md5( $nv_Request->session_id . $global_config['sitekey'] . $data['product_id'] );

			//Tinh trang don hang
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

			$data['name_short'] = nv_clean60( $data['name'], 60 );

			$xtpl->assign( 'PRODUCT', $data );

			if( $ProductGeneral->config['config_active_order'] == 1 )
			{
				if( $data['showprice'] == 1 )
				{
					if( $data['quantity'] > 0 )
					{
						$xtpl->parse( 'main.product.order' );
					}
					else
					{
						$xtpl->parse( 'main.product.product_empty' );
					}
				}
			}
			if( $ProductGeneral->config['config_active_price'] == 1 )
			{
				if( $data['showprice'] == 1 )
				{

					// Hien phan tram giam gia neu co
					if( ( float )$data['special'] )
					{
						$percent = ( ( ( float )$data['price'] - ( float )$data['special'] ) / ( float )$data['price'] ) * 100;

						$xtpl->assign( 'PERCENT', floor( $percent ) );
						$xtpl->parse( 'main.product.percent' );
					}

					//Dinh dang lai gia goc
					$data['price'] = $ProductCurrency->format( $ProductTax->calculate( $data['price'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $module_data . '_currency', 'session' ) );

					if( $data['price'] )
					{

						if( ( float )$data['special'] )
						{

							//Dinh dang lai gia san pham dac biet
							$data['special'] = $ProductCurrency->format( $ProductTax->calculate( $data['special'], $data['tax_class_id'], $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $module_data . '_currency', 'session' ) );

							//Giam gia dac biet theo nhom khach hang
							$xtpl->assign( 'PRICE_NEW', $data['special'] );

							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.product.price.discounts' );

						}
						else
						{
							// giá gốc
							$xtpl->assign( 'PRICE', $data['price'] );

							$xtpl->parse( 'main.product.price.no_discounts' );
						}

						// hiện giá thuế phí nếu cấu hình hiện
						if( $ProductGeneral->config['config_tax'] )
						{
							// định dạng lại giá thuế phí
							$data['tax'] = $ProductCurrency->format( ( float )$data['special'] ? $data['special'] : $data['price'], $nv_Request->get_string( $module_data . '_currency', 'session' ) );
						}
						else
						{
							$data['tax'] = false;
						}

					}

					$xtpl->parse( 'main.product.price' );
				}
				else
				{
					$xtpl->parse( 'main.product.contact' );
				}
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

				$xtpl->parse( 'main.product.compare' );
			}

			// San pham yeu thich
			if( $ProductGeneral->config['config_active_wishlist'] )
			{
				if( ! empty( $getWishlistProductId ) )
				{
					if( in_array( $data['product_id'], $getWishlistProductId ) )
					{
						$xtpl->parse( 'main.product.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.product.wishlist' );
			}

			$xtpl->parse( 'main.product' );

		}

	}
	if( $generatePage )
	{
		$xtpl->assign( 'GENERATEPAGE', $generatePage );
		$xtpl->parse( 'main.generatePage' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function ThemeProductViewDetail( $dataContent, $dataOthersProducts, $recentlyViewedProducts )
{
	global $ProductGeneral, $ProductCurrency, $ProductTax, $productCategory, $productRegistry, $category_id, $nv_Request, $module_info, $global_config, $module_file, $module_name, $module_data, $module_upload, $lang_module, $array_mod_title, $getWishlistProductId;

	$xtpl = new XTemplate( 'ThemeProductViewDetail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'CSS_MODEL', ! empty( $ProductGeneral->config['config_show_model'] ) ? ' show-product-code' : '' );

	if( $dataContent )
	{
		$dataContent['token'] = md5( $nv_Request->session_id . $global_config['sitekey'] . $dataContent['product_id'] );

		if( ! $dataContent['minimum'] )
		{
			$dataContent['minimum'] = 1;
		}

		$dataContent['name_short'] = nv_clean60( $dataContent['name'], 60 );

		$xtpl->assign( 'PRODUCT', $dataContent );

		$Price = false;
		if( $dataContent['showprice'] == 1 && $ProductGeneral->config['config_active_price'] == 1 && $dataContent['price'] )
		{
			// Hien phan tram giam gia neu co
			if( ( float )$dataContent['special'] )
			{
				$percent = ( ( ( float )$dataContent['price'] - ( float )$dataContent['special'] ) / ( float )$dataContent['price'] ) * 100;

				$xtpl->assign( 'PERCENT', floor( $percent ) );
				$xtpl->parse( 'main.percent' );
			}
			//Dinh dang lai gia goc
			$dataContent['price_sale'] = $ProductCurrency->format( $ProductTax->calculate( $dataContent['price'], $dataContent['tax_class_id'], $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $module_data . '_currency', 'session' ) );

			if( $dataContent['special'] )
			{

				//Dinh dang lai gia san pham dac biet
				$dataContent['special_sale'] = $ProductCurrency->format( $ProductTax->calculate( $dataContent['special'], $dataContent['tax_class_id'], $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $module_data . '_currency', 'session' ) );

				//Giam gia dac biet theo nhom khach hang
				$xtpl->assign( 'PRICE_NEW', $dataContent['special_sale'] );

				// giá gốc
				$xtpl->assign( 'PRICE', $dataContent['price_sale'] );

				$xtpl->parse( 'main.price.special' );

			}
			else
			{
				// giá gốc
				$xtpl->assign( 'PRICE', $dataContent['price_sale'] );

				$xtpl->parse( 'main.price.no_special' );
			}

			if( $dataContent['discounts'] )
			{

				foreach( $dataContent['discounts'] as $discount )
				{
					$xtpl->assign( 'DISCOUNT', $discount );

					$xtpl->parse( 'main.price.discount.loop' );

				}
				$xtpl->parse( 'main.price.discount' );
			}

			// hiện giá thuế phí nếu cấu hình hiện
			if( $ProductGeneral->config['config_tax'] )
			{
				// Định dạng lại giá thuế phí

				$xtpl->assign( 'TAX', $ProductCurrency->format( ( float )$dataContent['special'] ? $dataContent['special'] : $dataContent['price'], $nv_Request->get_string( $module_data . '_currency', 'session' ) ) );
				$xtpl->parse( 'main.price.tax' );
			}
			$Price = true;
			$xtpl->parse( 'main.price' );

		}
		else
		{

			$xtpl->parse( 'main.contact' );
		}

		if( $Price )
		{
			$xtpl->parse( 'main.show_order1' );
			$xtpl->parse( 'main.show_order2' );
			$xtpl->parse( 'main.show_order3' );
		}

		if( $dataContent['options'] )
		{
			foreach( $dataContent['options'] as $_key => $options )
			{
				$options['required'] = ( $options['required'] ) ? 'required' : '';
				$xtpl->assign( 'OPTION', $options );
				
				if( $options['type'] == 'select' )
				{
					foreach( $options['product_option_value'] as $key => $option )
					{ 
						$xtpl->assign( 'LOOP', $option );
					 
						if( $option['image'] )
						{
							$xtpl->parse( 'main.options.select.loop.image' );
						}
						$xtpl->parse( 'main.options.select.loop' );
					}
					$xtpl->parse( 'main.options.select' );

				}
				elseif( $options['type'] == 'radio' )
				{
					foreach( $options['product_option_value'] as $key => $option )
					{ 
						$xtpl->assign( 'LOOP', $option );
					 
						if( $option['image'] )
						{
							$xtpl->parse( 'main.options.radio.loop.image' );
						}
						$xtpl->parse( 'main.options.radio.loop' );
					}
					$xtpl->parse( 'main.options.radio' );

				}
				elseif( $options['type'] == 'checkbox' )
				{
					foreach( $options['product_option_value'] as $key => $option )
					{ 
					
						$xtpl->assign( 'LOOP', $option );
					 
						if( $option['image'] )
						{
							$xtpl->parse( 'main.options.checkbox.loop.image' );
						}
						$xtpl->parse( 'main.options.checkbox.loop' );
					}
					$xtpl->parse( 'main.options.checkbox' );

				}
				elseif( $options['type'] == 'image' )
				{
					foreach( $options['product_option_value'] as $key => $option )
					{
						$xtpl->assign( 'LOOP', $option );
						$xtpl->parse( 'main.option.loop' );
					}
					$xtpl->parse( 'main.options.option' );

				}
				elseif( $options['type'] == 'text' )
				{
					foreach( $options['product_option_value'] as $key => $option )
					{
						$xtpl->assign( 'LOOP', $option );
						$xtpl->parse( 'main.option.loop' );
					}
					$xtpl->parse( 'main.options.option' );

				}
				elseif( $options['type'] == 'textarea' )
				{
					foreach( $options['product_option_value'] as $key => $option )
					{
						$xtpl->assign( 'LOOP', $option );
						$xtpl->parse( 'main.option.loop' );
					}
					$xtpl->parse( 'main.options.option' );

				}
				elseif( $options['type'] == 'file' )
				{
					foreach( $options['product_option_value'] as $key => $option )
					{
						$xtpl->assign( 'LOOP', $option );
						$xtpl->parse( 'main.option.loop' );
					}
					$xtpl->parse( 'main.options.option' );

				}
				elseif( $options['type'] == 'date' )
				{
					foreach( $options['product_option_value'] as $key => $option )
					{
						$xtpl->assign( 'LOOP', $option );
						$xtpl->parse( 'main.option.loop' );
					}
					$xtpl->parse( 'main.options.option' );

				}
				elseif( $options['type'] == 'time' )
				{
					foreach( $options['product_option_value'] as $key => $option )
					{
						$xtpl->assign( 'LOOP', $option );
						$xtpl->parse( 'main.option.loop' );
					}
					$xtpl->parse( 'main.options.option' );

				}
				elseif( $options['type'] == 'datetime' )
				{
					foreach( $options['product_option_value'] as $key => $option )
					{
						$xtpl->assign( 'LOOP', $option );
						$xtpl->parse( 'main.option.loop' );
					}
					$xtpl->parse( 'main.options.option' );

				}

			}
			$xtpl->parse( 'main.options' );
		}

		if( $dataContent['images'] )
		{
			foreach( $dataContent['images'] as $image )
			{
				if( file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $image ) )
				{
					$xtpl->assign( 'IMAGE', array( 'thumb' => NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $image, 'image' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $image ) );
					$xtpl->parse( 'main.image' );
				}
			}
		}

		if( $ProductGeneral->config['config_active_order'] == 1 )
		{
			if( $dataContent['showprice'] == 1 )
			{
				if( $dataContent['quantity'] > 0 )
				{
					$xtpl->parse( 'main.order' );
				}
				else
				{
					$xtpl->parse( 'main.product_empty' );
				}
			}
		}

		// info
		if( ! empty( $dataContent['info'] ) )
		{
			$infoMore = @unserialize( $dataContent['info'] );
			foreach( $infoMore as $info )
			{
				$xtpl->assign( 'INFO', $info );
				$xtpl->parse( 'main.info.loop' );

			}
			$xtpl->parse( 'main.info' );
		}

		// danh muc
		if( $array_mod_title )
		{
			foreach( $array_mod_title as $key => $cat )
			{
				$xtpl->assign( 'CAT', $cat );
				$xtpl->parse( 'main.cat' );
			}
		}

		// attribute
		if( $dataContent['attribute_groups'] )
		{
			foreach( $dataContent['attribute_groups'] as $key => $attribute )
			{
				$xtpl->assign( 'ATTRIBUTE', $attribute );
				$xtpl->parse( 'main.attribute' );
			}
		}

		// question

		if( $dataContent['product_question'] )
		{
			foreach( $dataContent['product_question'] as $question )
			{
				$xtpl->assign( 'QUESTION', $question );
				$xtpl->parse( 'main.question' );

			}
		}

		// Hiện tab hỏi đáp sản phẩm
		if( $ProductGeneral->config['config_faq_status'] )
		{
			$xtpl->parse( 'main.faq_tab' );
			$xtpl->parse( 'main.faq_content' );
		}

		// Hiện tab đánh giá sản phẩm
		if( $ProductGeneral->config['config_review_status'] )
		{
			$xtpl->parse( 'main.review_tab' );
			$xtpl->parse( 'main.review_content' );
		}

		// Hiện nút so sánh sản phẩm
		if( $ProductGeneral->config['config_show_compare'] == 1 )
		{
			$xtpl->parse( 'main.compare' );
		}

		// Hiện nút sản phẩm yêu thích
		if( $ProductGeneral->config['config_active_wishlist'] )
		{
			if( ! empty( $getWishlistProductId ) )
			{
				if( in_array( $dataContent['product_id'], $getWishlistProductId ) )
				{
					$xtpl->parse( 'main.wishlist.disabled' );
				}
			}
			$xtpl->parse( 'main.wishlist' );
		}

	}

	if( $dataOthersProducts )
	{

	}

	if( $recentlyViewedProducts )
	{

	}
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	//unset( $dataContent, $dataOthersProducts, $recentlyViewedProducts, $lang_module );

	return $contents;
}

function ThemeProductViewCart( $dataContent )
{
	global $ProductGeneral, $ProductCurrency, $ProductTax, $productCategory, $productRegistry, $lang_ext, $category_id, $nv_Request, $module_info, $global_config, $module_file, $module_name, $module_data, $module_upload, $lang_module, $array_mod_title, $getWishlistProductId;

	$xtpl = new XTemplate( 'ThemeProductViewCart.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
 	$xtpl->assign( 'LANGE', $lang_ext );
 	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
 
	if( $dataContent )
	{
		$xtpl->assign( 'DATA', $dataContent );
		
		if( $dataContent['products'] )
		{
			foreach( $dataContent['products'] as $product )
			{
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
			
		}
		
		foreach( $dataContent['vouchers'] as $voucher )
		{
			$xtpl->assign( 'VOUCHER', $voucher );
			$xtpl->parse( 'main.voucher' );
		} 
		
	 
 
		if( $dataContent['xtotals'] )
		{
			foreach( $dataContent['xtotals'] as $_total )
			{ 
				$xtpl->assign( 'TOTAL', $_total );
				$xtpl->parse( 'main.total.loop_total' );
			}		
			$xtpl->parse( 'main.total' );		
		}
		
		//$this->config->get('total_voucher_status')
		if ( $dataContent['modules'] ) 
		{
			foreach ( $dataContent['modules'] as $result ) 
			{
				if( is_file( NV_ROOTDIR . '/modules/'. $module_file  .'/user_extension/total/'. $result .'.php' )  )
				{
					require_once ( NV_ROOTDIR . '/modules/'. $module_file  .'/user_extension/total/'. $result .'.php' );
 
					$checkconfig = $ProductGeneral->getSetting( $result, $ProductGeneral->store_id );
 
					if( nv_function_exists( 'total_' . $result ) && $checkconfig[$result .'_status'] == 1)
					{
						$xtpl->assign( 'EXTENSION', call_user_func( 'total_' . $result ) );
					
						$xtpl->parse( 'main.extension.extension_loop' );
					}
					
				}
	
			}
			$xtpl->parse( 'main.extension' );
		}		 
	}	   
	
 
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
  
}

function ThemeProductViewCheckout( $dataContent )
{
	global $ProductGeneral, $ProductCurrency, $ProductTax, $productCategory, $productRegistry, $globalUserid, $lang_ext, $category_id, $nv_Request, $module_info, $global_config, $module_file, $module_name, $module_data, $module_upload, $lang_module, $array_mod_title, $getWishlistProductId;

	$xtpl = new XTemplate( 'ThemeProductViewCheckout.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
 	$xtpl->assign( 'LANGE', $lang_ext );
 	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'DATA', $dataContent );
	
	if( $globalUserid > 0 )
	{
		$xtpl->parse( 'main.is_user' );
		$xtpl->parse( 'main.is_user_script' );
	}
	else
	{
		$xtpl->parse( 'main.is_guest' );
		$xtpl->parse( 'main.is_guest_script' );
	}
	if( $dataContent['shipping_required'] )
	{
		$xtpl->parse( 'main.shipping_required_1' );
		$xtpl->parse( 'main.shipping_required_2' );
		$xtpl->parse( 'main.shipping_required_3' );
		$xtpl->parse( 'main.shipping_required_4' );
	}
	else
	{
		$xtpl->parse( 'main.no_shipping_required_1' );
		$xtpl->parse( 'main.no_shipping_required_2' );
		$xtpl->parse( 'main.no_shipping_required_3' );
		$xtpl->parse( 'main.no_shipping_required_4' );
	} 
	
 
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
  
}


function ThemeProductErrorNotFound( $dataContent )
{
	global $ProductGeneral, $ProductCurrency, $ProductTax, $productCategory, $productRegistry, $lang_ext, $category_id, $nv_Request, $module_info, $global_config, $module_file, $module_name, $module_data, $module_upload, $lang_module, $array_mod_title, $getWishlistProductId;

	$xtpl = new XTemplate( 'ThemeProductErrorNotFound.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
 	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'DATA', $dataContent );

	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
 
}


/*[BEGIN_CART_TOTAL]*/
function ThemeProductTotalCoupon( $dataContent )
{
	global $ProductGeneral, $ProductCurrency, $ProductTax, $productCategory, $productRegistry, $lang_ext, $category_id, $nv_Request, $module_info, $global_config, $module_file, $module_name, $module_data, $module_upload, $lang_module, $array_mod_title, $getWishlistProductId;

	$xtpl = new XTemplate( 'ThemeProductTotalCoupon.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
 	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'DATA', $dataContent );

	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
 
}

function ThemeProductTotalReward( $dataContent )
{
	global $ProductGeneral, $ProductCurrency, $ProductTax, $productCategory, $productRegistry, $lang_ext, $category_id, $nv_Request, $module_info, $global_config, $module_file, $module_name, $module_data, $module_upload, $lang_module, $array_mod_title, $getWishlistProductId;

	$xtpl = new XTemplate( 'ThemeProductTotalReward.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
 	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'DATA', $dataContent );

	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
 
}

function ThemeProductTotalShipping( $dataContent )
{
	global $ProductGeneral, $ProductCurrency, $ProductTax, $productCategory, $productRegistry, $lang_ext, $category_id, $nv_Request, $module_info, $global_config, $module_file, $module_name, $module_data, $module_upload, $lang_module, $array_mod_title, $getWishlistProductId;

	$xtpl = new XTemplate( 'ThemeProductTotalShipping.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
 	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'DATA', $dataContent );

	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
 
}

function ThemeProductTotalVoucher( $dataContent )
{
	global $ProductGeneral, $ProductCurrency, $ProductTax, $productCategory, $productRegistry, $lang_ext, $category_id, $nv_Request, $module_info, $global_config, $module_file, $module_name, $module_data, $module_upload, $lang_module, $array_mod_title, $getWishlistProductId;

	$xtpl = new XTemplate( 'ThemeProductTotalVoucher.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
 	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'DATA', $dataContent );

	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
 
}

/*[END_CART_TOTAL]*/
