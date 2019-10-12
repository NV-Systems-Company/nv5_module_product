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

$url = array();
$cacheFile = NV_LANG_DATA . '_sitemap.cache';
$pa = NV_CURRENTTIME - 7200;

if( ( $cache = nv_get_cache( $module_name, $cacheFile ) ) != false and filemtime( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module_name . '/' . $cacheFile ) >= $pa )
{
	$url = unserialize( $cache );
}
else
{
	$db->sqlreset()->select( 'id, listcatid, edittime, ' . NV_LANG_DATA . '_alias' )->from( $db_config['prefix'] . '_' . $module_data . '_rows' )->where( 'status =1' )->order( 'publtime DESC' )->limit( 1000 );

	$result = $db->query( $db->sql() );
	$url = array();

	while( list( $id, $catid_i, $edittime, $alias ) = $result->fetch( 3 ) )
	{
		$url[] = array( 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$catid_i]['alias'] . '/' . $alias . $global_config['rewrite_exturl'], 'publtime' => $edittime );
	}

	$cache = serialize( $url );
	nv_set_cache( $module_name, $cacheFile, $cache );
}

nv_xmlSitemap_generate( $url );
die();