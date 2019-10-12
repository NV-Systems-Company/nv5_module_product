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

if( ! defined( 'NV_IS_MOD_PRODUCT' ) )
{
	die( 'Stop!!!' );
}

$channel = array();
$items = array();

$channel['title'] = $module_info['custom_title'];
$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$channel['description'] = ! empty( $module_info['description'] ) ? $module_info['description'] : $global_config['site_description'];

$catid = 0;
if( isset( $array_op[1] ) )
{
	$alias_cat_url = $array_op[1];
	$cattitle = '';
	foreach( $productCategory as $catid_i => $array_cat_i )
	{
		if( $alias_cat_url == $array_cat_i['alias'] )
		{
			$catid = $catid_i;
			break;
		}
	}
}
if( ! empty( $catid ) )
{
	$channel['title'] = $module_info['custom_title'] . ' - ' . $productCategory[$catid]['title'];
	$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $alias_cat_url;
	$channel['description'] = $productCategory[$catid]['description'];

	$db->sqlreset()->select( 'id, listcatid, publtime, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_hometext, image' )->from( $db_config['prefix'] . '_' . $module_data . '_rows' )->where( 'listcatid= ' . $catid . ' AND status =1' )->order( 'publtime DESC' )->limit( 30 );
	$sql = $db->sql();
}
else
{
	$db->sqlreset()->select( 'id, listcatid, publtime, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_hometext, image, thumb' )->from( $db_config['prefix'] . '_' . $module_data . '_rows' )->where( 'status =1' )->order( 'publtime DESC' )->limit( 30 );
	$sql = $db->sql();
}

if( $module_info['rss'] )
{
	$result = $db->query( $sql );
	while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $image, $thumb ) = $result->fetch( 3 ) )
	{
		$catalias = $productCategory[$listcatid]['alias'];

		if( $thumb == 1 )//image thumb
		{
			$rimages = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $image;
		}
		elseif( $thumb == 2 )//image file
		{
			$rimages = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $image;
		}
		elseif( $thumb == 3 )//image url
		{
			$rimages = $image;
		}
		else//no image
		{
			$rimages = '';
		}

		$rimages = ( ! empty( $rimages ) ) ? '<img src="' . $rimages . '" width="100" align="left" border="0">' : '';

		$items[] = array(
			'title' => $title,
			'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $catalias . '/' . $alias . $global_config['rewrite_exturl'],
			'guid' => $module_name . '_' . $id,
			'description' => $rimages . $hometext,
			'pubdate' => $publtime
		);
	}
}

nv_rss_generate( $channel, $items );
die();