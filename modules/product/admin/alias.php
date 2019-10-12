<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANG DINH TU (dlinhvan@gmail.com)
 * @Website http://dangdinhtu.com
 * @copyright 2009
 * @License GNU/GPL version 3 or any later version
 * @Createdate Sun, 28 Feb 2016 19:00:00 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$title = $nv_Request->get_title( 'title', 'post', '' );
$alias = strtolower( change_alias( $title ));

$id = $nv_Request->get_int( 'id', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );

if( $mod == 'cat' )
{
	$tab = TABLE_PRODUCT_NAME . '_category';
	$stmt = $db_slave->prepare( 'SELECT COUNT(*) FROM ' . $tab . ' WHERE category_id!=' . $id . ' AND alias= :alias' );
	$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
	$stmt->execute();
	$nb = $stmt->fetchColumn();
	if( ! empty( $nb ) )
	{
		$nb = $db_slave->query( 'SELECT MAX(category_id) FROM ' . $tab )->fetchColumn();

		$alias .= '-' . ( intval( $nb ) + 1 );
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $alias;
include NV_ROOTDIR . '/includes/footer.php';