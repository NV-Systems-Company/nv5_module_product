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
 
$page_title = $lang_module['content_main'];


// $table = TABLE_PRODUCT_NAME;
// $result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote($table . '_%'));
// while ($item = $result->fetch()) {
    // $name = substr($item['name'], strlen($table) + 1);
    // if (preg_match('/^' . TABLE_PRODUCT_NAME . '\_/', $item['name']) and (preg_match('/^([0-9]+)$/', $name) )) {
        // var_dump($item['name']);
    // }
// }

// die('ok');
// $result = $db->query('SELECT COUNT(product_id) total, tags_id FROM nv4_product_tags_product GROUP BY tags_id');
// while( $item = $result ->fetch() )
// {
	// $db->query('UPDATE ' . TABLE_PRODUCT_NAME . '_tags_description SET product_total=' . intval( $item['total'] ) . ' WHERE tags_id=' . intval( $item['tags_id'] ) );

// }
// die('ok');



$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );

$xtpl->assign( 'AddMenu', AddMenu() );
 
 
 
 
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' ); 

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';