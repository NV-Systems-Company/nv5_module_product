<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 10 Jun 2014 02:22:18 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$lang_ext = getLangAdmin( $op, 'localisation' );

$page_title = $lang_ext['heading_title']; 


if( ACTION_METHOD == 'delete' )
{
	$info = array();
	$country_id = $nv_Request->get_int( 'country_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );
	
	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] .  session_id() . $country_id ) )
	{
		$del_array = array( $country_id );
	}
 
	if( ! empty( $del_array ) )
	{
	
		$sql= 'SELECT country_id, name FROM ' . $db_config['prefix'] . '_' . $module_data . '_country WHERE country_id IN (' . implode( ',', $del_array ) .')';
		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$artitle = array();
		$a = 0;
		while( list( $country_id, $title ) = $result->fetch( 3 ) )
		{
 
			if( $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_country WHERE country_id = ' . (int)$country_id) )
			{

 
				$info['id'][$a] = $country_id;
				$del_array[] = $country_id;
				$artitle[] = $title;
				++$a;
			}
			else
			{
				$no_del_array[] = $country_id;
			}
		}

		$count = sizeof( $del_array );
		if( $count )
		{
 			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_country', implode( ', ', $artitle ), $admin_info['userid'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$nv_Cache->delMod( $module_name );
			
			$info['success'] = $lang_ext['text_success'] ;
		}
		if( ! empty( $no_del_array ) )
		{
 
			$info['error'] = $lang_ext['error_delete'] . ': ' . implode( ', ', $no_del_array );
		}
			
		
	}else
	{
		$info['error'] = $lang_ext['error_delete'];
	}
	echo json_encode( $info );
	exit();
}
 
$productArrayStatus = array( '0' => $lang_module['disabled'], '1' => $lang_module['enable'] );
$dataContent_postcode_required  = array( '1' => $lang_module['yes'], '0' => $lang_module['no'] );
 
if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$data = array(
		'country_id' => '',
		'iso_code_2' => '',
		'iso_code_3' => '',
		'address_format' => '',
		'postcode_required' => 0,
		'status' => 1,
		'name' => '' 
	);
	$error = array();
 
	$data['country_id'] = $nv_Request->get_int( 'country_id', 'get,post', 0 );

	if( $data['country_id'] > 0 )
	{  
		$data = $db->query( 'SELECT country_id, iso_code_2, iso_code_3, address_format, postcode_required, status, name 
		FROM ' . $db_config['prefix'] . '_' . $module_data . '_country 
		WHERE country_id=' . $data['country_id'] )->fetch();
		
		$caption = $lang_ext['text_edit'];
	}else{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{
		$data['country_id'] = $nv_Request->get_int( 'country_id', 'post', 0 );
		$data['iso_code_2'] = $nv_Request->get_title( 'iso_code_2', 'post', '', 2 );
		$data['iso_code_3'] = $nv_Request->get_title( 'iso_code_3', 'post', '', 3 );
		$data['address_format'] = $nv_Request->get_textarea( 'address_format', 'post', '', 'br', 1 );
		$data['postcode_required'] = $nv_Request->get_int( 'postcode_required', 'post', 0 );
		$data['status'] = $nv_Request->get_int( 'status', 'post', 0 );
		$data['name'] = $nv_Request->get_title( 'name', 'post', '' );
		
		$data['iso_code_2'] = substr($data['iso_code_2'],0, 2);
		$data['iso_code_3'] = substr($data['iso_code_3'],0, 3);
		
		
		if( empty( $data['name'] ) )
		{
			$error['name'] = $lang_ext['error_name'];
 		}
 

		if( empty( $error ) )
		{
			if( $data['country_id'] == 0 )
			{

				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_country SET 
				iso_code_2 = :iso_code_2, 
				iso_code_3 = :iso_code_3, 
				address_format = :address_format, 
				postcode_required = '. intval($data['postcode_required'] ) .', 
				status = '. intval($data['status'] ) .', 
				name = :name');

				$stmt->bindParam( ':iso_code_2', $data['iso_code_2'], PDO::PARAM_STR );
				$stmt->bindParam( ':iso_code_3', $data['iso_code_3'], PDO::PARAM_STR );
				$stmt->bindParam( ':address_format', $data['address_format'], PDO::PARAM_STR );
				$stmt->bindParam( ':name', $data['name'], PDO::PARAM_STR );
 				
				$stmt->execute();

				if( $data['country_id'] = $db->lastInsertId() )
				{

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Country', 'country_id: ' . $data['country_id'], $admin_info['userid'] );

				}
				else
				{
					$error['warning'] = $lang_ext['error_save'];

				}

				$stmt->closeCursor();
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_country SET 
				iso_code_2 = :iso_code_2, 
				iso_code_3 = :iso_code_3, 
				address_format = :address_format, 
				postcode_required = '. intval($data['postcode_required'] ) .', 
				status = '. intval($data['status'] ) .', 
				name = :name
				WHERE country_id=' . $data['country_id'] );

				$stmt->bindParam( ':iso_code_2', $data['iso_code_2'], PDO::PARAM_STR );
				$stmt->bindParam( ':iso_code_3', $data['iso_code_3'], PDO::PARAM_STR );
				$stmt->bindParam( ':address_format', $data['address_format'], PDO::PARAM_STR );
				$stmt->bindParam( ':name', $data['name'], PDO::PARAM_STR );
 

				if( $stmt->execute() )
				{
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Country', 'country_id: ' . $data['country_id'], $admin_info['userid'] );

				}
				else
				{
					$error['warning'] = $lang_ext['error_save'];

				}

				$stmt->closeCursor();

			}

		}
		if( empty( $error ) )
		{
			$nv_Cache->delMod( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=country' );
			die();
		}

	}
	
	
	$data['address_format'] = nv_htmlspecialchars( nv_br2nl( $data['address_format'] ) );
	
	
	$xtpl = new XTemplate( 'country_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'AddMenu', AddMenu( ) );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'DATA', $data );
	$xtpl->assign( 'CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );

	 
	foreach( $dataContent_postcode_required as $postcode_required_key => $postcode_required_name )
	{
 
		$xtpl->assign( 'postcode_required_checked', ( $postcode_required_key == $data['postcode_required'] ) ? 'checked="checked"' : '' );
		$xtpl->assign( 'postcode_required_key', $postcode_required_key );
		$xtpl->assign( 'postcode_required_name', $postcode_required_name );
		$xtpl->parse( 'main.postcode_required' );
 
	}
	foreach( $productArrayStatus as $status_key => $status_name )
	{
 
		$xtpl->assign( 'status_checked', ( $status_key == $data['status'] ) ? 'selected="selected"' : '' );
		$xtpl->assign( 'status_key', $status_key );
		$xtpl->assign( 'status_name', $status_name );
		$xtpl->parse( 'main.status' );
 
	}
	 
	if( isset( $error['name'] ) )
	{
		$xtpl->assign( 'error_name', $error['name'] );
		$xtpl->parse( 'main.error_name' );
	}
	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'error_warning', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}
 

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';

	exit();
}

/*show list country*/

$base_url_sort = $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

$per_page = 50;

$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = $db_config['prefix'] . '_' . $module_data . '_country';

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'name', 'weight' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= ' ORDER BY name';
}

if( isset( $order ) && ( $order == 'desc' ) )
{
	$sql .= " DESC";
}
else
{
	$sql .= " ASC";
}

$num_items = $db->query( 'SELECT COUNT(*) FROM ' . $sql )->fetchColumn();
 
$db->sqlreset()->select( '*' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$base_url .='&sort=' . $sort . '&order=' . $order . '&per_page=' . $per_page;

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';

$xtpl = new XTemplate( 'country.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'AddMenu', AddMenu( ) );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=add' );
$xtpl->assign( 'CANCEL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
 
 
$xtpl->assign( 'URL_NAME', $base_url_sort . '&amp;sort=name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_ISO_CODE_2', $base_url_sort . '&amp;sort=iso_code_2&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_ISO_CODE_3', $base_url_sort . '&amp;sort=iso_code_3&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'NAME_ORDER', ( $sort == 'name' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'ISO_CODE_2_ORDER', ( $sort == 'iso_code_2' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'ISO_CODE_3_ORDER', ( $sort == 'iso_code_3' ) ? 'class="' . $order2 . '"' : '' );

if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}
if( !empty( $dataContent ) )
{
	foreach( $dataContent as $item )
	{
		
		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['country_id'] );
		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=country&action=edit&token=".$item['token']."&country_id=" . $item['country_id'];
		$xtpl->assign( 'LOOP', $item );
 
		$xtpl->parse( 'main.loop' );
	}

}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
if( ! empty( $generate_page ) )
{
 
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
