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

$category_id = $nv_Request->get_int( 'category_id', 'post', 0 );
$contents = "NO_" . $category_id;

list( $category_id, $parent_id, $title ) = $db->query( 'SELECT a.category_id, a.parent_id, b.name FROM ' . TABLE_PRODUCT_NAME . '_category a
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_category_description b ON (a.category_id = b.category_id)
WHERE language_id = ' . $ProductGeneral->current_language_id . ' AND a.category_id=' . $category_id )->fetch( 3 );

if( $category_id > 0 )
{
	$delallcheckss = $nv_Request->get_string( 'delallcheckss', 'post', '' );
	$check_parent_id = $db->query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_category WHERE parent_id = ' . (int)$category_id )->fetchColumn();

	if( intval( $check_parent_id ) > 0 ) 
	{
		$contents = "ERR_CAT_" . sprintf( $lang_module['delcat_msg_cat'], $check_parent_id );
	}
	else// San pham trong chu de
	{
		$check_rows = $db->query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_product WHERE category_id=' . (int) $category_id )->fetchColumn();

		if( intval( $check_rows ) > 0 )
		{
			if( $delallcheckss == md5( $category_id . session_id() . $global_config['sitekey'] ) )
			{
				$delcatandrows = $nv_Request->get_string( 'delcatandrows', 'post', "" );
				$movecat = $nv_Request->get_string( 'movecat', 'post', "" );
				$category_idnews = $nv_Request->get_int( 'category_idnews', 'post', 0 );

				if( empty( $delcatandrows ) and empty( $movecat ) )// Hien form
				{ 
					$sql = 'SELECT a.category_id, b.name, a.lev FROM ' . TABLE_PRODUCT_NAME . '_category a 
					LEFT JOIN ' . TABLE_PRODUCT_NAME . '_category_description b ON (a.category_id = b.category_id)
					WHERE language_id = ' . $ProductGeneral->current_language_id . ' AND
					a.category_id !=' . $category_id . ' ORDER BY a.sort ASC';
					$result = $db->query( $sql );

					$array_cat_list = array();
					$array_cat_list[0] = "&nbsp;";

					while( list( $category_id_i, $title_i, $lev_i ) = $result->fetch( 3 ) )
					{
						$xtitle_i = "";
						if( $lev_i > 0 )
						{
							$xtitle_i .= "&nbsp;&nbsp;&nbsp;|";
							for( $i = 1; $i <= $lev_i; $i++ )
							{
								$xtitle_i .= "---";
							}
							$xtitle_i .= ">&nbsp;";
						}
						$xtitle_i .= $title_i;
						$array_cat_list[$category_id_i] = $xtitle_i;
					}

					$xtpl = new XTemplate( "cat_delete.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
					$xtpl->assign( 'LANG', $lang_module );
					$xtpl->assign( 'GLANG', $lang_global );
					$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
					$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
					$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
					$xtpl->assign( 'MODULE_NAME', $module_name );
					$xtpl->assign( 'OP', $op );

					$xtpl->assign( 'category_id', $category_id );
					$xtpl->assign( 'DELALLCHECKSS', $delallcheckss );
					$xtpl->assign( 'INFO', sprintf( $lang_module['delcat_msg_rows_select'], $title, $check_rows ) );

					while( list( $category_id_i, $title_i ) = each( $array_cat_list ) )
					{
						$xtpl->assign( 'CAT_ID', $category_id_i );
						$xtpl->assign( 'CAT_TITLE', $title_i );
						$xtpl->parse( 'main.catloop' );
					}

					$xtpl->parse( 'main' );
					$contents = $xtpl->text( 'main' );
				}
				elseif( ! empty( $delcatandrows ) )// Xoa loai san pham va san pham
				{
					$sql = $db->query( "SELECT product_id, category_id FROM " . TABLE_PRODUCT_NAME . "_product WHERE category_id=" . $category_id );
					while( $row = $sql->fetch() )
					{
						delete_product( $row['product_id'] );
 					}

					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category WHERE category_id=' . $category_id );
					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_description WHERE category_id = ' . $category_id );
					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_to_store WHERE category_id = ' . $category_id );
					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_filter WHERE category_id = ' . $category_id );
				
					nv_fix_cat_order();
					$ProductGeneral->deleteCache( 'category' );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=category&parent_id=" . $parent_id );
					die();
				}
				elseif( ! empty( $movecat ) and $category_idnews > 0 and $category_idnews != $category_id )// Di chuyen san pham sang chu de moi
				{
					$category_idnews = $db->query( 'SELECT category_id FROM ' . TABLE_PRODUCT_NAME . '_category WHERE category_id =' . $category_idnews )->fetchColumn();

					if( $category_idnews > 0 )
					{
						$sql = $db->query( 'SELECT product_id, category_id FROM ' . TABLE_PRODUCT_NAME . '_product WHERE category_id=' . $category_id );
						while( $row = $sql->fetch() )
						{
							$db->query( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product SET category_id=' . $category_idnews . ' WHERE product_id =' . $row['product_id'] );
						}
						$db->query( "DELETE FROM " . TABLE_PRODUCT_NAME . "_category WHERE category_id=" . $category_id );
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_description WHERE category_id = ' . $category_id );
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_to_store WHERE category_id = ' . $category_id );
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_filter WHERE category_id = ' . $category_id );
						nv_fix_cat_order();
						
						$ProductGeneral->deleteCache( 'category' );
						$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_delete_success'] );
						Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=category&parent_id=" . $parent_id );
						die();
					}
				}
			}
			else
			{
				$contents = "ERR_ROWS_" . $category_id . "_" . md5( $category_id . session_id() . $global_config['sitekey'] ) . "_" . sprintf( $lang_module['delcat_msg_rows'], $check_rows );
			}
		}
	}

	if( $contents == "NO_" . $category_id )
	{
		$sql = "DELETE FROM " . TABLE_PRODUCT_NAME . "_category WHERE category_id=" . $category_id;
		if( $db->query( $sql ) )
		{
			$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_description WHERE category_id = ' . $category_id );
			$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_to_store WHERE category_id = ' . $category_id );
			$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_category_filter WHERE category_id = ' . $category_id );
				
			nv_fix_cat_order();
			$contents = "OK_" . $parent_id;
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_catalog', "category_id " . $category_id, $admin_info['userid'] );
		}
		$ProductGeneral->deleteCache( 'category' );
	}
}

if( defined( 'NV_IS_AJAX' ) )
{
	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
}
else
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=category" );
	die();
}