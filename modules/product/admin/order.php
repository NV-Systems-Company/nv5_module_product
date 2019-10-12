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

$page_title = $lang_module['order'];

 
/*show list order*/

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

 
$data['filter_order_status'] = $nv_Request->get_string( 'filter_order_status', 'get', '' );
$data['filter_order_id'] = $nv_Request->get_string( 'filter_order_id', 'get', '' );
$data['filter_customer'] = $nv_Request->get_string( 'filter_customer', 'get', '' );
$data['filter_date_added'] = $nv_Request->get_string( 'filter_date_added', 'get', '' );
$data['filter_date_modified'] = $nv_Request->get_string( 'filter_date_modified', 'get', '' );
$data['filter_total'] = $nv_Request->get_string( 'filter_total', 'get', '' );
 
$data['sort'] = $nv_Request->get_string( 'sort', 'get', '' );
$data['order'] = $nv_Request->get_string( 'order', 'get' ) == 'asc' ? 'asc' : 'desc';

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=order&amp;sort=' . $data['sort'] . '&amp;order=' . $data['order'] . '&amp;per_page=' . $per_page;


$sql = TABLE_PRODUCT_NAME . '_order o';


if( is_numeric( $data['filter_order_status'] ) )
{
	$implode = array();

	$order_statuses = explode( ',', $data['filter_order_status'] );

	foreach( $order_statuses as $order_status_id )
	{
		$implode[] = 'o.order_status_id = ' . ( int )$order_status_id;
	}

	if( $implode )
	{
		$sql .= ' WHERE (' . implode( ' OR ', $implode ) . ')';
	}
	
}
else
{
	$sql .= ' WHERE o.order_status_id > 0';
}

if( is_numeric( $data['filter_order_id'] ) )
{
	$sql .= ' AND o.order_id = ' . ( int )$data['filter_order_id'];
}

if( ! empty( $data['filter_customer'] ) )
{
	$sql .= " AND CONCAT(o.first_name, ' ', o.last_name) LIKE '%" . $db->dblikeescape( $data['filter_customer'] ) . "%'";
}
 
if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $data['filter_date_added'], $m ))
{
	$date_added_start = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	$date_added_end = $date_added_start + 86399; 
	
	$sql .= " AND o.date_added BETWEEN ". $date_added_start ." AND ". $date_added_end ."";
}

unset($m);

if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $data['filter_date_modified'], $m ))
{
	$date_modified_start = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	$date_modified_end = $date_added_start + 86399; 
	
	$sql .= " AND o.date_modified BETWEEN ". $date_modified_start ." AND ". $date_modified_end ."";
 
}

if( ! empty( $data['filter_total'] ) )
{
	$sql .= " AND o.total = '" . ( float )$data['filter_total'] . "'";
}
 
$num_items = $db->query( 'SELECT COUNT(*) FROM ' . $sql )->fetchColumn();

$sort_data = array(
	'o.order_id',
	'customer',
	'status',
	'o.date_added',
	'o.date_modified',
	'o.total' );

 
if( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) )
{
	$sql .= " ORDER BY " . $data['sort'];
}
else
{
	$sql .= " ORDER BY o.order_id";
}

if( isset( $data['order'] ) && ( $data['order'] == 'desc' ) )
{
	$sql .= " DESC";  
}
else
{
	$sql .= " ASC";
}
 
$db->sqlreset()
	->select( 'o.order_id, CONCAT(o.first_name, \' \', o.last_name) customer, (SELECT os.name FROM ' . TABLE_PRODUCT_NAME . '_order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = ' . ( int )$ProductGeneral->current_language_id . ' ) status, o.invoice_prefix, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified' )
	->from( $sql )
	->limit( $per_page )
	->offset( ( $page - 1 ) * $per_page );
 
$result = $db->query( $db->sql() );

$array = array();
while( $rows = $result->fetch() )
{
	$array[] = $rows;
}
 
$xtpl = new XTemplate( 'order.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'AddMenu', AddMenu( ) );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'DATA', $data );

$order2 = ( $data['order'] == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_ID', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=o.order_id&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=customer&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_STATUS', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=status&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_DATE_ADDED', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=o.date_added&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_DATE_MODIFIED', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=o.date_modified&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_TOTAL', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=o.total&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=order_add" );
 
$order_status = getOrderStatus();
$order_status[0] = array( 'order_status_id' => 0, 'name'=> $lang_module['order_missing'] ); 
ksort( $order_status );
foreach( $order_status as $valuekey => $status  )
{
	$xtpl->assign( 'key_selected', ( $valuekey == $data['filter_order_status'] && is_numeric( $data['filter_order_status'] ) ) ? 'selected="selected"' : '' );
	$xtpl->assign( 'valuekey', $valuekey );
	$xtpl->assign( 'status_title', $status['name'] );
	$xtpl->parse( 'main.order_status' );

}
 
if( ! empty( $array ) )
{
	foreach( $array as $item )
	{
		$item['total'] = $ProductCurrency->format($item['total'], $item['currency_code'], $item['currency_value']);
 		$item['date_added'] = date( 'd/m/Y', $item['date_added'] );
		$item['date_modified'] = date( 'd/m/Y', $item['date_modified'] );
		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['order_id'] );

		$item['order_info'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=order_info&token=" . $item['token'] . "&order_id=" . $item['order_id'];
		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=order_edit&token=" . $item['token'] . "&order_id=" . $item['order_id'];
		$xtpl->assign( 'LOOP', $item );

		$xtpl->parse( 'main.loop' );
	}

}
else
{
	$xtpl->parse( 'main.no_results' );
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
