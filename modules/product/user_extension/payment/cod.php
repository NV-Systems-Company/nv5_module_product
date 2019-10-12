<?php
 
function getMethod_cod( $address, $total )
{
	global $db, $ProductGeneral, $cod_config ;
	
	$cod_config = $ProductGeneral->getSetting( 'payment_cod', $ProductGeneral->store_id );
	
	$lang_ext = $ProductGeneral->getLangSite( 'checkout', 'checkout' );
	$lang_plug = $ProductGeneral->getLangSite( 'cod', 'payment' );
	
	if( $cod_config['payment_cod_status'] )
	{
		
		if( $cod_config['payment_cod_geo_zone_id'] == 1)
		{
			$status = true;
		}
		elseif( $db->query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_zone_to_geo_zone WHERE geo_zone_id = ' . ( int )$cod_config['payment_cod_geo_zone_id'] . ' AND country_id = ' . ( int )$address['country_id'] . ' AND (zone_id = ' . ( int )$address['zone_id'] . ' OR zone_id = 0)' )->fetchColumn() )
		{
			$status = true;
		}
		else
		{
			$status = false;
		}
	}
	else
	{
		$status = false;
	}
	
	$method_data = array();

	if( $status )
	{
		$method_data = array(
			'code' => 'cod',
			'title' => $lang_plug['text_title'],
			'terms' => '',
			'sort_order' => $cod_config['payment_cod_sort_order'] );
	}
	return $method_data;
}
 
function confirm_cod() 
{
	global $module_data, $module_name, $ProductGeneral;
	$json = array();

	if ( $_SESSION[$module_data . '_payment_method']['code'] == 'cod' ) 
	{
 
		$cod_config= $ProductGeneral->getSetting( 'payment_cod', $ProductGeneral->store_id );
 
		addOrderHistory($_SESSION[$module_data . '_order_id'], $cod_config['payment_cod_order_status_id'] );

		$json['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkout/success', true );
	}
	return $json;
}

function index_cod( ) 
{
	global $module_data, $lang_module, $lang_ext, $module_info, $module_file, $module_name, $ProductGeneral;
	
	$xtpl = new XTemplate( 'cod.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/extension/payment' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	 
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
