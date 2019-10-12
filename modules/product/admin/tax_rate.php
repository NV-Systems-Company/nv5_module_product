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

$array_type = array( 'F' => $lang_ext['text_amount'], 'P' => $lang_ext['text_percent'] );

if( ACTION_METHOD == 'delete' )
{
	$json = array();
	$tax_rate_id = $nv_Request->get_int( 'tax_rate_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $tax_rate_id ) )
	{
		$del_array = array( $tax_rate_id );
	}

	if( ! empty( $del_array ) )
	{
		$a = 0;
		$_del_array = array();
		$no_del_array = array();
		foreach( $del_array as $tax_rate_id )
		{
			$tax_rule_total = $db->query( 'SELECT COUNT(DISTINCT tax_class_id) total FROM ' . TABLE_PRODUCT_NAME . '_tax_rule WHERE tax_rate_id = ' . ( int )$tax_rate_id )->fetchColumn();

			if( $tax_rule_total )
			{
				$json['error'] = sprintf( $lang_ext['error_tax_rule'], $tax_rule_total );
				break;
			}

			if( ! empty( $tax_rate_id ) && ! isset( $json['error'] ) )
			{
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_tax_rate WHERE tax_rate_id = ' . ( int )$tax_rate_id );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_tax_rate_to_customer_group WHERE tax_rate_id = ' . ( int )$tax_rate_id );

				$json['id'][$a] = $tax_rate_id;

				$_del_array[] = $tax_rate_id;

				++$a;
			}
			else
			{
				$no_del_array[] = $tax_rate_id;
			}

		}

		$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );

		if( sizeof( $_del_array ) )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_tax_rate', implode( ', ', $_del_array ), $admin_info['userid'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$json['success'] = $lang_ext['text_success'];
			
			$ProductGeneral->deleteCache( 'text_class' );
		}
		if( ! empty( $no_del_array ) )
		{

			$json['error'] = $lang_ext['error_no_delete'];
		}

	}
	else
	{
		$json['error'] = $lang_ext['error_no_delete'];
	}
	nv_jsonOutput( $json );
}

if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{
	$getCustomerGroup = getCustomerGroup( );
	$getGeoZones =  getGeoZones();
	
	$data = array(
		'tax_rate_id' => 0,
		'geo_zone_id' => 0,
		'name' => '',
		'rate' => '',
		'type' => '',
		'date_added' => NV_CURRENTTIME,
		'date_modified' => '' );

	$data['tax_rate_customer_group'] = array();

	$error = array();

	$data['tax_rate_id'] = $nv_Request->get_int( 'tax_rate_id', 'get,post', 0 );

	if( $data['tax_rate_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_tax_rate  
		WHERE tax_rate_id=' . $data['tax_rate_id'] )->fetch();

		$query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_tax_rate_to_customer_group WHERE tax_rate_id = ' . ( int )$data['tax_rate_id'] )->fetchAll();

		foreach( $query as $result )
		{
			$data['tax_rate_customer_group'][] = $result['customer_group_id'];
		}

		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['tax_rate_id'] = $nv_Request->get_int( 'tax_rate_id', 'post', 0 );
		$data['geo_zone_id'] = $nv_Request->get_int( 'geo_zone_id', 'post', 0 );
		$data['tax_rate_customer_group'] = array_unique( $nv_Request->get_typed_array( 'tax_rate_customer_group', 'post', 'int', array() ) );

		$data['name'] = $nv_Request->get_title( 'name', 'post', '' );
		$data['rate'] = $nv_Request->get_float( 'rate', 'post', 0 );
		$data['type'] = $nv_Request->get_title( 'type', 'post', '' );

		if( ( nv_strlen( $data['name'] ) < 3 ) || ( nv_strlen( $data['name'] ) > 32 ) )
		{
			$error['name'] = $lang_ext['error_name'];
		}

		

		if( empty( $error ) )
		{
			if( $data['tax_rate_id'] == 0 )
			{

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_tax_rate SET 
				geo_zone_id=' . intval( $data['geo_zone_id'] ) . ',
				name =:name,
				rate =:rate,
				type =:type,
				date_added = ' . NV_CURRENTTIME );

				$stmt->bindParam( ':name', $data['name'], PDO::PARAM_STR );
				$stmt->bindParam( ':rate', $data['rate'], PDO::PARAM_STR );
				$stmt->bindParam( ':type', $data['type'], PDO::PARAM_STR );
				$stmt->execute();

				if( $data['tax_rate_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();
					if( ! empty( $data['tax_rate_customer_group'] ) )
					{
						foreach( $data['tax_rate_customer_group'] as $customer_group_id )
						{
							$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_tax_rate_to_customer_group SET 
							tax_rate_id = ' . ( int )$data['tax_rate_id'] . ', 
							customer_group_id = ' . ( int )$customer_group_id );
						}
					}

					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A tax rate', 'tax_rate_id: ' . $data['tax_rate_id'], $admin_info['userid'] );
				}
				else
				{
					$error['warning'] = $lang_module['errorsave'];

				}

			}
			else
			{
				try
				{
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_tax_rate SET 
					name = :name, 
					rate = :rate, 
					type = :type,
					geo_zone_id=' . intval( $data['geo_zone_id'] ) . ',
					date_modified = ' . NV_CURRENTTIME . '		
					WHERE tax_rate_id=' . $data['tax_rate_id'] );
					$stmt->bindParam( ':name', $data['name'], PDO::PARAM_STR );
					$stmt->bindParam( ':rate', $data['rate'], PDO::PARAM_STR );
					$stmt->bindParam( ':type', $data['type'], PDO::PARAM_STR );

					if( $stmt->execute() )
					{
						$stmt->closeCursor();

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_tax_rate_to_customer_group WHERE tax_rate_id = ' . ( int )$data['tax_rate_id'] );

						if( ! empty( $data['tax_rate_customer_group'] ) )
						{
							foreach( $data['tax_rate_customer_group'] as $customer_group_id )
							{
								$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_tax_rate_to_customer_group SET 
								tax_rate_id = ' . ( int )$data['tax_rate_id'] . ', 
								customer_group_id = ' . ( int )$customer_group_id );
							}
						}

						$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );

						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A tax rate', 'tax_rate_id: ' . $data['tax_rate_id'], $admin_info['userid'] );

						 
					}
					else
					{
						$error['warning'] = $lang_module['errorsave'];

					}

				}
				catch ( PDOException $e )
				{
					$error['warning'] = $lang_module['errorsave'];
					//var_dump($error['warning']);
				}

			}

		}
		if( empty( $error ) )
		{
			$ProductGeneral->deleteCache( 'text_class' );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
			die();
		}

	}

	$xtpl = new XTemplate( 'tax_rate_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
	$xtpl->assign( 'GLANG', $lang_global );
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
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'DATA', $data );
	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );

	foreach( $array_type as $key => $_type )
	{
		$selected = ( $key == $data['type'] ) ? 'selected="selected"' : '';
		$xtpl->assign( 'TYPE_SELECTED', $selected );
		$xtpl->assign( 'TYPE_KEY', $key );
		$xtpl->assign( 'TYPE_VALUE', $_type );
		$xtpl->parse( 'main.type' );
	}
	foreach( $getCustomerGroup as $customer_group_id => $value )
	{

		$xtpl->assign( 'GROUP', array(
			'customer_group_id' => $customer_group_id,
			'name' => $value['name'],
			'checked' => ( in_array( $customer_group_id, $data['tax_rate_customer_group'] ) ) ? 'checked="checked"' : '',
			) );

		$xtpl->parse( 'main.customer_group' );
	}
	
	foreach( $getGeoZones as $geo_zone_id => $value )
	{

		$xtpl->assign( 'GEOZONES', array(
			'geo_zone_id' => $geo_zone_id,
			'name' => $value['name'],
			'selected' => ( $geo_zone_id == $data['geo_zone_id'] ) ? 'selected="selected"' : '',
			) );

		$xtpl->parse( 'main.geo_zone' );
	}

	if( isset( $error['name'] ) )
	{
		$xtpl->assign( 'error_name', $error['name'] );
		$xtpl->parse( 'main.error_name' );
	}
	if( isset( $error['rate'] ) )
	{
		$xtpl->assign( 'error_rate', $error['rate'] );
		$xtpl->parse( 'main.error_rate' );
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

/*show list */

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_tax_rate tr LEFT JOIN ' . TABLE_PRODUCT_NAME . '_geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id)';

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array(
	'tr.name',
	'tr.rate',
	'tr.type',
	'gz.name',
	'tr.date_added',
	'tr.date_modified' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY tr.name";
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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '='.$op.'&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( 'tr.tax_rate_id, tr.name name, tr.rate, tr.type, gz.name geo_zone, tr.date_added, tr.date_modified' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}

$xtpl = new XTemplate( 'tax_rate.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
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
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=add' );

if(  $nv_Request->get_string( $module_data . '_success', 'session' ) ) 
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=cn.name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );


if( ! empty( $dataContent ) )
{
	foreach( $dataContent as $item )
	{

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['tax_rate_id'] );

		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=edit&token=" . $item['token'] . "&tax_rate_id=" . $item['tax_rate_id'];

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

unset( $xtpl, $dataContent, $generate_page, $lang_ext, $productCategory, $productArrayYesNo, $productArrayStatus, $productArrayGender, $productArrayPrefix, $getLangModId, $getLangModCode );


include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
