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

function getInstalled( $type )
{
	global $db;
	
	$extension_data = array();

	$query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( $type ) )->fetchAll();

	foreach( $query as $result )
	{
		$extension_data[] = $result['code'];
	}

	return $extension_data;
}

function install( $type, $code )
{
	global $db;
	$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_extension SET type = ' . $db->quote( $type ) . ', code = ' . $db->quote( $code ) );
}

function uninstall( $type, $code )
{
	global $db;
	$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_extension WHERE type = ' . $db->quote( $type ) . ' AND code =' . $db->quote( $code ) );
}
