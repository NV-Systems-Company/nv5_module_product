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


$json = array();

$country_id = $nv_Request->get_int( 'country_id', 'get', 0 );

$sql = 'SELECT zone_id, code, status, name FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone WHERE country_id=' . $country_id;

$result = $db->query( $sql );

while( list( $_zone_id, $code, $status, $name ) = $result->fetch( 3 ) )
{
	$json['zone'][] = array(
		'code' => $code,
		'country_id' => $country_id,
		'status' => $status,
		'name' => nv_htmlspecialchars( $name ),
		'zone_id' => $_zone_id );

}
$result->closeCursor();

nv_jsonOutput( $json );
