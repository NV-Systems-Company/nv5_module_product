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

if ( ! function_exists( 'getLangMod' ) )
{
	function getLangMod( $mod_file, $block_config )
	{
		if( ! file_exists( NV_ROOTDIR . '/modules/' . $mod_file . '/language/' . NV_LANG_DATA . '.php' ) )
		{
			trigger_error( 'Error! Language variables '. $block_config['block_name'] .' is empty!', 256 );
		}
		require ( NV_ROOTDIR . '/modules/' . $mod_file . '/language/' . NV_LANG_DATA . '.php' );
	 
		$lang_mod = $lang_module;
			
		unset( $lang_module );
			
		return $lang_mod;
	}
}

if( ! function_exists( 'GlobalBlockGroupBlockCat' ) )
{

	/**
	 * ConfigGlobalBlockGroupBlockCat()
	 *
	 * @param mixed $module
	 * @param mixed $data_block
	 * @param mixed $lang_block
	 * @return
	 */
	function ConfigGlobalBlockGroupBlockCat( $module, $data_block, $lang_block )
	{
		global $db, $db_config, $site_mods, $language_array, $db_config;
		
		$html = '';
		
		$productRegistry = array(
			'mod_data' => $site_mods[$module]['module_data'],
			'mod_name' => $module,
			'mod_file' => $site_mods[$module]['module_file'],
			'mod_lang' => array(),
			'lang_data' => NV_LANG_DATA,
		);

		$ProductGeneral = new NukeViet\Product\General( $productRegistry ); 

		$sql = 'SELECT * FROM ' . $ProductGeneral->table . '_block_cat bc
		LEFT JOIN ' . $ProductGeneral->table . '_block_cat_description bcd ON (bc.block_id = bcd.block_id) 
		WHERE bcd.language_id = ' . intval( $ProductGeneral->current_language_id ) . '
		ORDER BY weight ASC, bc.block_id ASC';
			
		$productBlockCat = $ProductGeneral->getdbCache( $sql, 'block_cat', 'block_id');
		 
		$html = '<tr>';
		$html .= '	<td>' . $lang_block['block_id'] . '</td>';
		$html .= '	<td>';
		$html .= '	<select name="config_block_id" class="form-control w200">';

		foreach( $productBlockCat as $block )
		{
			$sel = ( $data_block['block_id'] == $block['block_id'] ) ? ' selected' : '';
			$html .= '	<option value="' . $block['block_id'] . '" ' . $sel . '>' . $block['name'] . '</option>';
		}
		$html .= '		</select>';
		$html .= '	</td>';
		$html .= '</tr>';
		
		$html .= '<tr>';
		$html .= '	<td>' . $lang_block['numcut'] . '</td>';
		$html .= '	<td>';
		$html .= '		<input name="config_numcut" value="'.$data_block['numcut'].'" class="form-control w200">';
		$html .= '	</td>';
		$html .= '</tr>';
		
		$html .= '<tr>';
		$html .= '	<td>' . $lang_block['numrow'] . '</td>';
		$html .= '	<td>';
		$html .= '		<input name="config_numrow" value="'.$data_block['numrow'].'" class="form-control w200\">';
		$html .= '	</td>';
		$html .= '</tr>';
		
		return $html;
	}

	/**
	 * ConfigGlobalBlockGroupBlockCatSubmit()
	 *
	 * @param mixed $module
	 * @param mixed $lang_block
	 * @return
	 */
	function ConfigGlobalBlockGroupBlockCatSubmit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['block_id'] = $nv_Request->get_int( 'config_block_id', 'post', 0 );
		$return['config']['numcut'] = $nv_Request->get_int( 'config_numcut', 'post', 0 );
		$return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
		return $return;
	}

	/**
	 * GlobalBlockGroupBlockCat()
	 *
	 * @param mixed $block_config
	 * @return
	 */
	function GlobalBlockGroupBlockCat( $block_config )
	{
		global $ProductGeneral, $productCategory, $productRegistry, $ProductTax, $ProductCurrency, $ProductCart, $getWishlistProductId, $nv_Request, $global_config, $module_name, $site_mods, $module_info, $db, $db_config;

		$mod_name = $block_config['module'];
		$mod_file = $site_mods[$mod_name]['module_file'];
		$mod_data = $site_mods[$mod_name]['module_data'];
		$mod_upload = $site_mods[$mod_name]['module_upload'];

		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $mod_file . '/GlobalBlockGroupBlockCat.tpl' ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = 'default';
		}

		if( $mod_name != $module_name )
		{
 
			$productRegistry = array(
				'mod_data' => $mod_data,
				'mod_name' => $mod_name,
				'mod_file' => $mod_file,
				'mod_lang' => getLangMod( $mod_file, $block_config ),
				'lang_data' => NV_LANG_DATA,
			);

			$ProductGeneral = $ProductGeneral ? $ProductGeneral : new NukeViet\Product\General( $productRegistry ); 
			$ProductTax = $ProductTax ? $ProductTax : new NukeViet\Product\General( $productRegistry ); 
			$ProductCurrency = $ProductCurrency ? $ProductCurrency : new NukeViet\Product\General( $productRegistry ); 
			$ProductCart = $ProductCart ? $ProductCart : new NukeViet\Product\General( $productRegistry ); 
			
 
			$sql = 'SELECT * FROM ' . $ProductGeneral->table . '_category c
			LEFT JOIN ' . $ProductGeneral->table . '_category_description cd ON (c.category_id = cd.category_id) 
			WHERE cd.language_id = ' . intval( $ProductGeneral->current_language_id ) . '
			ORDER BY sort ASC';
			
			$productCategory = $ProductGeneral->getdbCache( $sql, 'category', 'category_id');
			
			
			
			
		}
		 
		$xtpl = new XTemplate( 'GlobalBlockGroupBlockCat.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'TEMPLATE', $block_theme );
		$xtpl->assign( 'ID', $block_config['bid'] );
		
 		 
		// truy vấn giảm giá đặc biệt
		$special = '(SELECT price
				FROM ' . TABLE_PRODUCT_NAME . '_product_special ps
				WHERE ps.product_id = p.product_id
					AND ps.customer_group_id = ' . intval( $ProductGeneral->config['config_customer_group_id'] ) . '
					AND ((ps.date_start = 0
						OR ps.date_start < ' . NV_CURRENTTIME . ')
						AND (ps.date_end = 0
						   OR ps.date_end > ' . NV_CURRENTTIME . '))
				ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) special,';
		// truy vấn lấy tình trạng hàng
		$stock_status = '(SELECT ss.name
				FROM ' . TABLE_PRODUCT_NAME . '_stock_status ss
				WHERE ss.stock_status_id = p.stock_status_id
					AND ss.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' ) stock_status,';

		// kết nối truy vấn sử dụng các biến trên nếu dùng
		$select = $special . $stock_status;
		
		$result = $db->query('SELECT p.*, '.$select.' pd.*  FROM ' . $ProductGeneral->table . '_product p 
			LEFT JOIN ' . $ProductGeneral->table . '_product_description pd ON (p.product_id = pd.product_id)
			LEFT JOIN ' . $ProductGeneral->table . '_block b ON (b.product_id = p.product_id) 
			WHERE b.block_id = '. intval( $block_config['block_id'] ) .'
			ORDER BY b.weight ASC, b.block_id ASC LIMIT 0, ' . intval( $block_config['numrow'] ) );
		while( $item = $result->fetch() )
		{
			if( $item['thumb'] == 1 )
			{
				$item['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $mod_upload . '/' . $item['image'];
			}
			elseif( $item['thumb'] == 2 )
			{
				$item['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $mod_upload . '/' . $item['image'];
			}
			elseif( $item['thumb'] == 3 )
			{
				$item['thumb'] = $item['image'];
			}
			else
			{
				$item['thumb'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $mod_file . '/no-image.jpg';
			}
			
			$item['token'] = md5( $nv_Request->session_id . $global_config['sitekey'] . $item['product_id'] );

			//Tinh trang don hang
			if( $item['quantity'] <= 0 )
			{
				$item['stock'] = $item['stock_status'];

			}
			elseif( $ProductGeneral->config['config_stock_display'] )
			{
				$item['stock'] = $item['quantity'];

			}
			else
			{
				$item['stock'] = $lang_module['product_instock'];
			}

			$item['name_short'] = nv_clean60( $item['name'], 60 );

			$xtpl->assign( 'PRODUCT', $item );

			if( $ProductGeneral->config['config_active_order'] == 1 )
			{
				if( $item['showprice'] == 1 )
				{
					if( $item['quantity'] > 0 )
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
				if( $item['showprice'] == 1 )
				{

					// Hien phan tram giam gia neu co
					if( ( float )$item['special'] )
					{
						$percent = ( ( ( float )$item['price'] - ( float )$item['special'] ) / ( float )$item['price'] ) * 100;

						$xtpl->assign( 'PERCENT', floor( $percent ) );
						$xtpl->parse( 'main.product.percent' );
					}

					//Dinh dang lai gia goc
					$item['price'] = $ProductCurrency->format( $ProductTax->calculate( $item['price'], $item['tax_class_id'], $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $mod_data . '_currency', 'session' ) );

					if( $item['price'] )
					{

						if( ( float )$item['special'] )
						{

							//Dinh dang lai gia san pham dac biet
							$item['special'] = $ProductCurrency->format( $ProductTax->calculate( $item['special'], $item['tax_class_id'], $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $mod_data . '_currency', 'session' ) );

							//Giam gia dac biet theo nhom khach hang
							$xtpl->assign( 'PRICE_NEW', $item['special'] );

							// Giá gốc
							$xtpl->assign( 'PRICE', $item['price'] );

							$xtpl->parse( 'main.product.price.discounts' );

						}
						else
						{
							// Giá gốc
							$xtpl->assign( 'PRICE', $item['price'] );

							$xtpl->parse( 'main.product.price.no_discounts' );
						}

						// Hiện giá thuế phí nếu cấu hình hiện
						if( $ProductGeneral->config['config_tax'] )
						{
							// Định dạng lại giá thuế phí
							$item['tax'] = $ProductCurrency->format( ( float )$item['special'] ? $item['special'] : $item['price'], $nv_Request->get_string( $mod_data . '_currency', 'session' ) );
						}
						else
						{
							$item['tax'] = false;
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
				if( isset( $_SESSION[$mod_name . '_array_id'] ) )
				{
					$array_id = $_SESSION[$mod_name . '_array_id'];
					$array_id = unserialize( $array_id );
				}
				else
				{
					$array_id = array();
				}

				if( ! empty( $array_id ) )
				{
					$ch = ( in_array( $item['product_id'], $array_id ) ) ? ' checked="checked"' : '';
					$xtpl->assign( 'ch', $ch );
				}

				$xtpl->parse( 'main.product.compare' );
			}

			// San pham yeu thich
			if( $ProductGeneral->config['config_active_wishlist'] )
			{
				if( ! empty( $getWishlistProductId ) )
				{
					if( in_array( $item['product_id'], $getWishlistProductId ) )
					{
						$xtpl->parse( 'main.product.wishlist.disabled' );
					}
				}
				$xtpl->parse( 'main.product.wishlist' );
			}
			
			$xtpl->assign( 'PRODUCT', $item );
			$xtpl->parse( 'main.product' );
		}
		$result->closeCursor();
		
		
		$xtpl->parse( 'main' );
		
		$content = $xtpl->text( 'main' );	
		
		return $content;
	}
 
 
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = GlobalBlockGroupBlockCat( $block_config );
}