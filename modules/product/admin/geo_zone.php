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

$getCountries = getCountries();

if( ACTION_METHOD == 'zone' )
{
	$json = array();
	$country_id = $nv_Request->get_int( 'country_id', 'get', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );
	if( isset( $getCountries[$country_id] ) )
	{
		$json = $getCountries[$country_id];

		$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_zone WHERE country_id=' . intval( $country_id );
		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{
			$json['zone'][] = $rows;
		}
		$result->closeCursor();
	}

	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'delete' )
{
	$json = array();
	$geo_zone_id = $nv_Request->get_int( 'geo_zone_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $geo_zone_id ) )
	{
		$del_array = array( $geo_zone_id );
	}

	if( ! empty( $del_array ) )
	{

		$sql = 'SELECT geo_zone_id, name  FROM ' . TABLE_PRODUCT_NAME . '_geo_zone WHERE geo_zone_id IN (' . implode( ',', $del_array ) . ')';
		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$artitle = array();
		$a = 0;
		while( list( $geo_zone_id, $title ) = $result->fetch( 3 ) )
		{

			if( $db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_geo_zone WHERE geo_zone_id = ' . ( int )$geo_zone_id ) )
			{

				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_zone_to_geo_zone WHERE geo_zone_id = ' . ( int )$geo_zone_id );

				$json['id'][$a] = $geo_zone_id;
				$del_array[] = $geo_zone_id;
				$artitle[] = $title;
				++$a;
			}
			else
			{
				$no_del_array[] = $geo_zone_id;
			}
		}

		$count = sizeof( $del_array );
		if( $count )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_geo_zone', implode( ', ', $artitle ), $admin_info['userid'] );

			$nv_Request->unset_request( $module_data . '_success', 'session' );

			$json['success'] = $lang_ext['text_delete_success'];

			$nv_Cache->delMod( $module_name );

		}
		if( ! empty( $no_del_array ) )
		{

			$json['error'] = $lang_ext['error_delete'];
		}

	}
	else
	{
		$json['error'] = $lang_ext['error_delete'];
	}
	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$data = array(
		'geo_zone_id' => '',
		'date_modified' => '',
		'date_added' => '',
		'name' => '',
		'description' => '',
		'zone_to_geo_zone' => array(),
		'zone_id' => array() );
	$error = array();

	$data['geo_zone_id'] = $nv_Request->get_int( 'geo_zone_id', 'get,post', 0 );

	if( $data['geo_zone_id'] > 0 )
	{
		$data = $db->query( 'SELECT geo_zone_id, name , description 
		FROM ' . TABLE_PRODUCT_NAME . '_geo_zone  
		WHERE geo_zone_id=' . $data['geo_zone_id'] )->fetch();

		$result = $db->query( 'SELECT zone_to_geo_zone_id, country_id, zone_id, geo_zone_id
		FROM ' . TABLE_PRODUCT_NAME . '_zone_to_geo_zone
		WHERE geo_zone_id=' . intval( $data['geo_zone_id'] ) );
		$zon_to_geo_zone = array();
		while( $rows = $result->fetch() )
		{
			$zon_to_geo_zone[] = $rows;

		}

		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{
		$data['geo_zone_id'] = $nv_Request->get_int( 'geo_zone_id', 'post', 0 );
		$data['name'] = $nv_Request->get_title( 'name', 'post', '' );
		$data['description'] = $nv_Request->get_title( 'description', 'post', '' );

		$data['zone_to_geo_zone'] = $nv_Request->get_typed_array( 'zone_to_geo_zone', 'post' );

		if( nv_strlen( $data['name'] ) < 3 || nv_strlen( $data['name'] ) > 32 )
		{
			$error['name'] = $lang_ext['error_name'];

		}

		if( empty( $error ) )
		{
			if( $data['geo_zone_id'] == 0 )
			{

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_geo_zone SET  
				name = :name,
				description = :description,
				date_added = ' . NV_CURRENTTIME );

				$stmt->bindParam( ':name', $data['name'], PDO::PARAM_STR );
				$stmt->bindParam( ':description', $data['description'], PDO::PARAM_STR );

				$stmt->execute();

				if( $data['geo_zone_id'] = $db->lastInsertId() )
				{
					if( isset( $data['zone_to_geo_zone'] ) )
					{
						foreach( $data['zone_to_geo_zone'] as $value )
						{
							$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_zone_to_geo_zone SET 
							country_id = ' . ( int )$value['country_id'] . ', 
							zone_id = ' . ( int )$value['zone_id'] . ', 
							geo_zone_id = ' . ( int )$data['geo_zone_id'] . ', 
							date_added =' . NV_CURRENTTIME );
						}
					}
					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Geo Zone', 'geo_zone_id: ' . $data['geo_zone_id'], $admin_info['userid'] );

				}
				else
				{
					$error['warning'] = $lang_module['errorsave'];

				}

				$stmt->closeCursor();
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_geo_zone SET 
				name = :name,
				description = :description,
				date_modified = ' . NV_CURRENTTIME . '
				WHERE geo_zone_id=' . $data['geo_zone_id'] );

				$stmt->bindParam( ':name', $data['name'], PDO::PARAM_STR );
				$stmt->bindParam( ':description', $data['description'], PDO::PARAM_STR );

				if( $stmt->execute() )
				{

					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_zone_to_geo_zone WHERE geo_zone_id = ' . intval( $data['geo_zone_id'] ) );

					if( isset( $data['zone_to_geo_zone'] ) )
					{
						foreach( $data['zone_to_geo_zone'] as $value )
						{
							$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_zone_to_geo_zone SET 
							country_id = ' . intval( $value['country_id'] ) . ', 
							zone_id = ' . intval( $value['zone_id'] ) . ', 
							geo_zone_id = ' . intval( $data['geo_zone_id'] ) . ', 
							date_added = ' . NV_CURRENTTIME );
						}
					}

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Geo Zone', 'geo_zone_id: ' . $data['geo_zone_id'], $admin_info['userid'] );
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
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '' );
			die();
		}
	}

	$xtpl = new XTemplate( 'geo_zone_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'AddMenu', AddMenu() );
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
	$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . $nv_Request->session_id ) );
	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	
	foreach( $getCountries as $key => $item )
	{
		$xtpl->assign( 'COUNTRY', array( 'key' => $key, 'name' => nv_htmlspecialchars( $item['name'] ) ) );
		$xtpl->parse( 'main.country' );

	}
	$zone_to_geo_zone_row = 0;
	if( $data['geo_zone_id'] > 0 )
	{
		if( ! empty( $zon_to_geo_zone ) )
		{

			foreach( $zon_to_geo_zone as $key => $value )
			{

				$xtpl->assign( 'COUNTRY_ID', $value['country_id'] );
				$xtpl->assign( 'ZONE_ID', $value['zone_id'] );
				$xtpl->assign( 'KEY', $zone_to_geo_zone_row );
				$xtpl->parse( 'main.loopscript' );
				foreach( $getCountries as $country_id => $_c )
				{

					$xtpl->assign( 'COUNTRY_SELECTED', ( $country_id == $value['country_id'] ) ? 'selected="selected"' : '' );
					$xtpl->assign( 'COUNTRY_ID', $country_id );
					$xtpl->assign( 'COUNTRY_NAME', nv_htmlspecialchars( $_c['name'] ) );
					$xtpl->parse( 'main.loop2.loop_country' );

				}

				$xtpl->parse( 'main.loop2' );

				++$zone_to_geo_zone_row;
			}

		}

	}
	$xtpl->assign( 'zone_to_geo_zone_row', $zone_to_geo_zone_row );

	if( isset( $error['name'] ) )
	{
		$xtpl->assign( 'error_name', $error['name'] );
		$xtpl->parse( 'main.error_name' );
	}
	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'WARNING', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	unset( $getCountries, $xtpl, $lang_ext );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';

}

/*show list geo_zone*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_geo_zone';

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'name', 'description' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{
	if( $sort == 'name' )
	{
		$sort = 'name';
	}
	$sql .= ' ORDER BY ' . $sort;
}
else
{
	$sql .= ' ORDER BY  name';
}

if( isset( $order ) && ( $order == 'desc' ) )
{
	$sql .= ' DESC';
}
else
{
	$sql .= ' ASC';
}

$num_items = $db->query( 'SELECT COUNT(*) FROM ' . $sql )->fetchColumn();

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( '*' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}
$result->closeCursor();

$xtpl = new XTemplate( 'geo_zone.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'AddMenu', AddMenu() );
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
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_CODE2', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=iso_code_2&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_CODE3', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=iso_code_3&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=add' );

if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}

if( ! empty( $dataContent ) )
{
	foreach( $dataContent as $item )
	{
		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['geo_zone_id'] );

		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=edit&token=' . $item['token'] . '&geo_zone_id=' . $item['geo_zone_id'];
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

unset( $getCountries, $dataContent, $generate_page );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
