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

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );


$ProductGeneral->current_language_id = $db->query( 'SELECT value FROM ' . $db_config['prefix'] . '_' . $mod_data . '_setting WHERE code="config_language_id"' )->fetchColumn();
 

$sql = 'SELECT c.category_id, c.parent_id, c.groups_view, cd.name, cd.alias FROM ' . $db_config['prefix'] . '_' . $mod_data . '_category c LEFT JOIN 
' . $db_config['prefix'] . '_' . $mod_data . '_category_description cd ON (c.category_id = cd.category_id)
WHERE cd.language_id = ' . (int)$ProductGeneral->current_language_id . '
ORDER BY c.sort ASC';
$result = $db->query( $sql );
while( $row = $result->fetch() )
{
	$array_item[$row['category_id']] = array(
		'parentid' => $row['parent_id'],
		'groups_view' => $row['groups_view'],
		'key' => $row['category_id'],
		'title' => $row['name'],
		'alias' => $row['alias'],
	);
}

$max = max( array_keys( $array_item ) );

$sql='SELECT a.information_id, b.title, b.alias FROM ' . $db_config['prefix'] . '_' . $mod_data . '_information a 
	LEFT JOIN ' . $db_config['prefix'] . '_' . $mod_data . '_information_description b ON (a.information_id = b.information_id) 
	WHERE b.language_id = ' . (int)$ProductGeneral->current_language_id . ' ORDER BY a.sort_order';
$result = $db->query( $sql );	
while( $row2 = $result->fetch() )
{
	$array_item[$max] = array(
		'parentid' => 0,
		'key' => $max,
		'title' => $row2['title'],
		'alias' => 'info/'.$row2['alias'] . $global_config['rewrite_exturl'],
	);
	++$max;
}