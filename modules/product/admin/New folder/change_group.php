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

$groupid = $nv_Request->get_int( 'groupid', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
$content = 'NO_' . $groupid;

list( $groupid, $parentid, $numsubgroup ) = $db->query( 'SELECT groupid, parentid, numsubgroup FROM ' . $db_config['prefix'] . '_' . $module_data . '_group WHERE groupid=' . $groupid )->fetch( 3 );

if( $groupid > 0 )
{
	if( $mod == 'weight' and $new_vid > 0 )
	{
		$sql = 'SELECT groupid FROM ' . $db_config['prefix'] . '_' . $module_data . '_group WHERE groupid!=' . $groupid . ' AND parentid=' . $parentid . ' ORDER BY weight ASC';
		$result = $db->query( $sql );

		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_group SET weight=' . $weight . ' WHERE groupid=' . $row['groupid'];
			$db->query( $sql );
		}

		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_group SET weight=' . $new_vid . ' WHERE groupid=' . $groupid;
		$db->query( $sql );

		nv_fix_group_order();
		$content = 'OK_' . $parentid;
	}
	elseif( $mod == 'inhome' and ( $new_vid == 0 or $new_vid == 1 ) )
	{
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_group SET inhome=' . $new_vid . ' WHERE groupid=' . $groupid;
		$db->query( $sql );
		$content = 'OK_' . $parentid;
	}
	elseif( $mod == 'numlinks' and $new_vid >= 0 and $new_vid <= 10 )
	{
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_group SET numlinks=' . $new_vid . ' WHERE groupid=' . $groupid;
		$db->query( $sql );
		$content = 'OK_' . $parentid;
	}
	elseif( $mod == 'viewgroup' and $nv_Request->isset_request( 'new_vid', 'post' ) )
	{
		$viewgroup = $nv_Request->get_title( 'new_vid', 'post' );
		$array_viewgroup = ( $numsubgroup > 0 ) ? $array_viewcat_full : $array_viewcat_nosub;
		if( ! array_key_exists( $viewgroup, $array_viewgroup ) )
		{
			$viewgroup = 'viewcat_page_list';
		}
		$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_group SET viewgroup= :viewgroup WHERE groupid=' . $groupid );
		$stmt->bindParam( ':viewgroup', $viewgroup, PDO::PARAM_STR );
		$stmt->execute();
		$content = 'OK_' . $parentid;
	}
	elseif( $mod == 'in_order' and ( $new_vid == 0 or $new_vid == 1 ) )
	{
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_group SET in_order=' . $new_vid . ' WHERE groupid=' . $groupid;
		$db->query( $sql );
		$content = 'OK_' . $parentid;
	}
	$ProductGeneral->deleteCache( 'group' );
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';