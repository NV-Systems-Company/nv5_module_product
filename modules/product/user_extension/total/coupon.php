<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );
 
function total_coupon() 
{
	global $lang_global, $lang_module, $op, $module_info, $global_config, $module_data, $module_file, $module_name;
	
	$xtpl = new XTemplate( 'coupon.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/extension/total' );
	$xtpl->assign( 'LANG', $lang_module );

	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	
	if( isset( $_SESSION[$module_data . '_coupon'] ) )
	{
		$coupon = $_SESSION[$module_data . '_coupon'];
	}
	else
	{
		$coupon = '';
	}

	$xtpl->assign( 'COUPON', $coupon );
  
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
