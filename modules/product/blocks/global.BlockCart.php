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

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! function_exists( 'getLangMod' ) )
{
	function getLangMod( $mod_file, $block_config )
	{
		if( ! file_exists( NV_ROOTDIR . '/modules/' . $mod_file . '/language/' . NV_LANG_DATA . '.php' ) )
		{
			trigger_error( "Error! Language variables " . $block_config['block_name'] . " is empty!", 256 );
		}
		require ( NV_ROOTDIR . '/modules/' . $mod_file . '/language/' . NV_LANG_DATA . '.php' );

		$lang_mod = $lang_module;

		unset( $lang_module );

		return $lang_mod;
	}
}

if( ! function_exists( 'BlockGetCartInfo' ) )
{
	/**
	 * BlockGetCartInfo()
	 * 
	 * @param mixed $block_config
	 * @return
	 */
	function BlockGetCartInfo( $block_config )
	{
		global $ProductGeneral, $ProductCurrency, $ProductTax, $ProductCart, $nv_Cache, $nv_Request, $productRegistry, $globalUserid, $user_info, $module_name, $module_file, $module_data, $site_mods, $db, $lang_module, $module_info, $global_config, $productCategory;
		
		$globalUserid = ( isset( $user_info['userid'] ) ) ? $user_info['userid'] : 0;
		$mod_name = $block_config['module'];
		$mod_file = $site_mods[$mod_name]['module_file'];
		$mod_data = $site_mods[$mod_name]['module_data'];
		$mod_upload = $site_mods[$mod_name]['module_upload'];
		$data = array();

		if( $module_name == $mod_name and ! empty( $ProductGeneral ) )
		{
			$ProductTax = $ProductTax ? $ProductTax : new NukeViet\Product\Tax( $productRegistry ); 
			$ProductCart = $ProductCart ? $ProductCart : new NukeViet\Product\Cart( $productRegistry );
		}
		else
		{
			 
			$productRegistry = array(
				'mod_data' => $mod_data,
				'mod_name' => $mod_name,
				'mod_file' => $mod_file,
				'mod_lang' => getLangMod( $mod_file, $block_config ),
				'lang_data' => NV_LANG_DATA,
			);

			$ProductGeneral = $ProductGeneral ? $ProductGeneral : new NukeViet\Product\General( $productRegistry ); 
			$ProductTax = $ProductTax ? $ProductTax : new NukeViet\Product\Tax( $productRegistry ); 
			$ProductCurrency = $ProductCurrency ? $ProductCurrency : new NukeViet\Product\Currency( $productRegistry ); 
			$ProductCart = $ProductCart ? $ProductCart : new NukeViet\Product\Cart( $productRegistry );

		}
		
		$getProducts = $ProductCart->getProducts();
		
		if( $getProducts )
		{
 
			$xtotals = array();
			$taxes = $ProductCart->getTaxes();
			$total = 0;
	 
			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'xtotals' => &$xtotals,
				'taxes' => &$taxes,
				'total' => &$total );

			// Display prices
			if( defined( 'NV_IS_USER' ) || ! $ProductGeneral->config['config_customer_price'] )
			{
				$sort_order = array();

				$results = $db->query( 'SELECT * FROM ' . $ProductGeneral->table . '_extension WHERE type = ' . $db->quote( 'total' ) )->fetchAll();

				foreach( $results as $key => $value )
				{
					$total_config = $ProductGeneral->getSetting( $value['code'], $ProductGeneral->store_id );

					$sort_order[$key] = isset( $total_config[$value['code'] . '_sort_order'] ) ? $total_config[$value['code'] . '_sort_order'] : 0;

				}

				array_multisort( $sort_order, SORT_ASC, $results );

				$array_class = array();
				foreach( $results as $result )
				{
					$total_config = $ProductGeneral->getSetting( $result['code'], $ProductGeneral->store_id );
					if( isset( $total_config[$result['code'] . '_status'] ) && $total_config[$result['code'] . '_status'] )
					{					 
						$array_class[] = $result['code'];
					}
				}

				foreach( $array_class as $key => &$class )
				{
	 
					$classMap = 'NukeViet\Product\Total\\' . $class;
					${$class} = new $classMap( $productRegistry );
					${$class}->getTotal( $total_data );

				}

				$sort_order = array();

				foreach( $xtotals as $key => $value )
				{
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort( $sort_order, SORT_ASC, $xtotals );
			}
	 
			$data['text_items'] = sprintf( $ProductGeneral->mod_lang['checkout_text_items'], $ProductCart->countProducts() + ( isset( $_SESSION[$mod_data . '_vouchers'] ) ? count( $_SESSION[$mod_data . '_vouchers'] ) : 0 ), $ProductCurrency->format( $total, $nv_Request->get_string( $mod_data . '_currency', 'session' ) ) );
			 
			$data['products'] = array();
	 
			foreach( $getProducts as $product )
			{
			 
				if( $product['thumb'] == 1 )
				{
						$product['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $mod_upload . '/' . $product['image'];
				}
				elseif( $product['thumb'] == 2 )
				{
					$product['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $mod_upload . '/' . $product['image'];
				}
				elseif( $product['thumb'] == 3 )
				{
					$product['thumb'] = $product['image'];
				}
				else
				{
					$product['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
				}
				$option_data = array();

				foreach( $product['option'] as $option )
				{
					if( $option['type'] != 'file' )
					{
						$value = $option['value'];
					}
					else
					{
						$upload_info = array();

						if( $upload_info )
						{
							$value = $upload_info['name'];
						}
						else
						{
							$value = '';
						}
					}

					$option_data[] = array(
						'name' => $option['name'],
						'value' => ( nv_strlen( $value ) > 20 ? nv_substr( $value, 0, 20 ) . '..' : $value ),
						'type' => $option['type'] );
				}

				// Display prices
				if( defined( 'NV_IS_USER' ) || ! $ProductGeneral->config['config_customer_price'] )
				{
					$unit_price = $ProductTax->calculate( $product['price'], $product['tax_class_id'], $ProductGeneral->config['config_tax'] );

					$price = $ProductCurrency->format( $unit_price, $nv_Request->get_string( $mod_data . '_currency', 'session' ) );
					$total = $ProductCurrency->format( $unit_price * $product['quantity'], $nv_Request->get_string( $mod_data . '_currency', 'session' ) );
				}
				else
				{
					$price = false;
					$total = false;
				}

				$data['products'][] = array(
					'cart_id' => $product['cart_id'],
					'thumb' => $product['thumb'],
					'name' => $product['name'],
					'model' => $product['model'],
					'option' => $option_data,
					'recurring' => ( $product['recurring'] ? $product['recurring']['name'] : '' ),
					'quantity' => $product['quantity'],
					'price' => $price,
					'total' => $total,
					'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$product['category_id']]['alias'] . '/' . $product['alias'] . $global_config['rewrite_exturl'] );
			}

			// Gift Voucher
			$data['vouchers'] = array();

			if( ! empty( $_SESSION[$mod_data . '_vouchers'] ) )
			{
				foreach( $_SESSION[$mod_data . '_vouchers'] as $key => $voucher )
				{
					$data['vouchers'][] = array(
						'key' => $key,
						'description' => $voucher['description'],
						'amount' => $ProductCurrency->format( $voucher['amount'], $nv_Request->get_string( $mod_data . '_currency', 'session' ) ) );
				}
			}

			$data['xtotals'] = array();

			foreach( $xtotals as $total )
			{
				$data['xtotals'][] = array(
					'title' => $total['title'],
					'text' => $ProductCurrency->format( $total['value'], $nv_Request->get_string( $mod_data . '_currency', 'session' ) ),
				);
			}
 	
		}else{
			
			$data['text_items'] = sprintf( $ProductGeneral->mod_lang['checkout_text_items'], 0, $ProductCurrency->format( 0, $nv_Request->get_string( $mod_data . '_currency', 'session' ) ) );
			
		}
		  
		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $mod_file . '/BlockCart.tpl' ) )
		{
			$block_theme = $global_config['module_theme'];
		}
		else
		{
			$block_theme = 'default';
		}

		$xtpl = new XTemplate( 'BlockCart.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
		$xtpl->assign( 'LANG', $ProductGeneral->mod_lang );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'MOD_FILE', $mod_file );
		$xtpl->assign( 'MOD_NAME', $mod_name );
 		$xtpl->assign( 'LINK_CART', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cart' );
		$xtpl->assign( 'LINK_CHECKOUT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout' );
		$xtpl->assign( 'TEXT_ITEMS', $data['text_items']);
 		
		if( isset( $data['products'] ) || isset( $data['vouchers'] ) )
		{
			
			
			if( ! empty( $data['products'] ) )
			{
				foreach( $data['products'] as $product )
				{
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
				foreach( $data['vouchers'] as $vouchers )
				{

					$xtpl->assign( 'VOUCHERS', $vouchers );
					$xtpl->parse( 'main.data.vouchers' );
				}

			}
			
			if( $data['xtotals'] )
			{
				foreach( $data['xtotals'] as $_total )
				{

					$xtpl->assign( 'TOTAL', $_total );
					$xtpl->parse( 'main.data.total' );
				}
				
			}
			
			$xtpl->parse( 'main.data' );

		}
		else
		{
			$xtpl->parse( 'main.empty' );
		}

		$xtpl->parse( 'main' );

		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = BlockGetCartInfo( $block_config );
}
