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


$lang_ext = getLangAdmin( $op, 'localisation' );

$page_title = $lang_ext['heading_title']; 


 if( ACTION_METHOD == 'delete' )
{
	$json = array();
	$zone_id = $nv_Request->get_int( 'zone_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );
	
	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] .  session_id() . $zone_id ) )
	{
		$del_array = array( $zone_id );
	}
 
	if( ! empty( $del_array ) )
	{
	
		$sql= 'SELECT zone_id, name  FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone WHERE zone_id IN (' . implode( ',', $del_array ) .')';
		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$artitle = array();
		$a = 0;
		while( list( $zone_id, $title ) = $result->fetch( 3 ) )
		{
 
			if( $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone WHERE zone_id = ' . (int)$zone_id) )
			{

				 
				$json['id'][$a] = $zone_id;
				$del_array[] = $zone_id;
				$artitle[] = $title;
				++$a;
			}
			else
			{
				$no_del_array[] = $zone_id;
			}
		}

		$count = sizeof( $del_array );
		if( $count )
		{
 			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_zone', implode( ', ', $artitle ), $admin_info['userid'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$json['success'] = $lang_module['zone_text_success'] ;
			
			$nv_Cache->delMod( $module_name );
		}
		if( ! empty( $no_del_array ) )
		{
 
			$json['error'] = $lang_module['zone_text_error'] . ': ' . implode( ', ', $no_del_array );
		}

	}else
	{
		$json['error'] = $lang_module['error_no_del_zone'];
	}
	echo nv_jsonOutput( $json );
 
}
else if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{
	$data = array(
		'zone_id' => '',
		'country_id' => '',
		'code' => '',
		'status' => 1,
		'name' => '' 
	);
	$error = array();
 
	$data['zone_id'] = $nv_Request->get_int( 'zone_id', 'get,post', 0 );

	if( $data['zone_id'] > 0 )
	{  
		$data = $db->query( 'SELECT zone_id, country_id, code, status, name 
		FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone  
		WHERE zone_id=' . $data['zone_id'] )->fetch();
		
		$caption = $lang_ext['text_edit'];
	}else{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{
		$data['zone_id'] = $nv_Request->get_int( 'zone_id', 'post', 0 );
		$data['country_id'] = $nv_Request->get_int( 'country_id', 'post', 0 );
		$data['code'] = $nv_Request->get_title( 'code', 'post', '' );
 		$data['status'] = $nv_Request->get_int( 'status', 'post', 0 );
		$data['name'] = $nv_Request->get_title( 'name', 'post', '' );
		
		$data['code'] = substr($data['code'],0, 32);
 
		if( nv_strlen( $data['name'] ) < 3 || nv_strlen( $data['name'] ) > 128 )
		{
			$error['name'] = $lang_ext['error_name'];
 		}
 

		if( empty( $error ) )
		{
			if( $data['zone_id'] == 0 )
			{
 	
				$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_zone SET 
				country_id = '. intval($data['country_id'] ) .', 
				code = :code, 
				status = '. intval($data['status'] ) .', 
				name = :name');

				$stmt->bindParam( ':code', $data['code'], PDO::PARAM_STR );
				$stmt->bindParam( ':name', $data['name'], PDO::PARAM_STR );
 				
				$stmt->execute();

				if( $data['zone_id'] = $db->lastInsertId() )
				{

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Zone', 'zone_id: ' . $data['zone_id'], $admin_info['userid'] );

				}
				else
				{
					$error['warning'] = $lang_module['errorsave'];

				}

				$stmt->closeCursor();
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_zone SET 
				country_id = '. intval($data['country_id'] ) .', 
				code = :code, 
				status = '. intval($data['status'] ) .', 
				name = :name
				WHERE zone_id=' . $data['zone_id'] );

				$stmt->bindParam( ':code', $data['code'], PDO::PARAM_STR );
				$stmt->bindParam( ':name', $data['name'], PDO::PARAM_STR );
 

				if( $stmt->execute() )
				{
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Zone', 'zone_id: ' . $data['zone_id'], $admin_info['userid'] );

				}
				else
				{
					$error['warning'] = $lang_module['errorsave'];

				}

				$stmt->closeCursor();

			}

		}
		if( empty( $error ) )
		{
			$nv_Cache->delMod( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=zone' );
			die();
		}

	}
 
	$xtpl = new XTemplate( 'zone_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
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

	$getCountries = getCountries();
	foreach( $getCountries as $country_id => $_c )
	{
 
		$xtpl->assign( 'country_checked', ( $country_id == $data['country_id'] ) ? 'checked="checked"' : '' );
		$xtpl->assign( 'country_id', $country_id );
		$xtpl->assign( 'country_name', $_c['name'] );
		$xtpl->parse( 'main.country' );
 
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
 
}

/*show list zone*/
 
$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$table = $db_config['prefix'] . '_' . $module_data ;

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'asc' ? 'asc' : 'desc';

$sql = $table . '_zone z LEFT JOIN ' . $table . '_country c ON (z.country_id = c.country_id)';
 
$num_items = $db->query( 'SELECT COUNT(*) FROM ' . $sql )->fetchColumn();
		
$sort_data = array(
	'c.name',
	'z.name',
	'z.code',
	'z.status'
);
	
if( isset( $sort ) && in_array( $sort, $sort_data ) )
{
	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY c.name";
}

if( isset( $data['order'] ) && ( $data['order'] == 'desc' ) )
{
	$sql .= " desc";
}
else
{
	$sql .= " asc";
}
 
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=zone&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( ' c.name country, z.zone_id, z.name, z.status, z.code' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
 
$result = $db->query( $db->sql() );

$array = array();
while( $rows = $result->fetch() )
{
	$array[] = $rows;
}

$xtpl = new XTemplate( 'zone.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
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
 
$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_COUNTRY', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=c.name&amp;order=' . $order2 . '&amp;per_page=' . $per_page);
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=z.name&amp;order=' . $order2 . '&amp;per_page=' . $per_page);
$xtpl->assign( 'URL_CODE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=z.code&amp;order=' . $order2 . '&amp;per_page=' . $per_page);
$xtpl->assign( 'URL_STATUS', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=z.status&amp;order=' . $order2 . '&amp;per_page=' . $per_page);
 
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=zone&action=add" );

if( !empty( $array ) )
{
	foreach( $array as $item )
	{
		
		$item['status'] =  $productArrayStatus[$item['status']];
		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['zone_id'] );
		
		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=zone&action=edit&token=".$item['token']."&zone_id=" . $item['zone_id'];
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
