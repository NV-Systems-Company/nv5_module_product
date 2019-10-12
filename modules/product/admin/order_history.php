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



if( ACTION_METHOD =='add_history' )
{
	$json = array();
	$append = $nv_Request->get_int( 'append', 'post,get', 0 );
	$notify = $nv_Request->get_int( 'notify', 'post,get', 0 );
	$order_id = $nv_Request->get_int( 'order_id', 'post,get', 0 );
	$order_status_id = $nv_Request->get_int( 'order_status_id', 'post,get', 0 );
	$comment = $nv_Request->get_string( 'comment', 'post,get', '' );
	if( $db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_order_history SET order_id = ' . ( int )$order_id . ', order_status_id = ' . ( int )$order_status_id . ', notify = ' . ( int )$notify . ', comment = ' . $db->quote( $comment ) . ', date_added =' . NV_CURRENTTIME ))
	{
		$json['success'] = $lang_module['order_status_order_success'];
	}
	
	
	
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}
 

$order_id = $nv_Request->get_int( 'order_id', 'post,get', 0 );
$token = md5( $global_config['sitekey'] . session_id() . $order_id );

$page = $nv_Request->get_int( 'page', 'post,get', 1 );
$per_page = 15;
 
$num = $db->query( 'SELECT COUNT(*) FROM '. TABLE_PRODUCT_NAME . '_order_history oh LEFT JOIN ' . TABLE_PRODUCT_NAME . '_order_status os ON oh.order_status_id = os.order_status_id' )->fetchcolumn();

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&amp;" . NV_OP_VARIABLE . "=order_history&order_id=" .$order_id;

$num_items = ( $num_items > 1 ) ? $num_items : 1;

$lang_ext = getLangAdmin( 'order', 'sale' ); 
 
$xtpl = new XTemplate( 'order_history.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );

if ( $num_items > 0 )
{
	$db->sqlreset()
		->select( 'oh.date_added, os.name status, oh.comment, oh.notify' )
		->from( TABLE_PRODUCT_NAME.'_order_history oh LEFT JOIN '.TABLE_PRODUCT_NAME.'_order_status os ON oh.order_status_id = os.order_status_id')
		->where( 'oh.order_id = ' . (int)$order_id . ' AND os.language_id = ' . (int)$ProductGeneral->current_language_id )
		->order( 'oh.date_added ASC ' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );

	$result = $db->query( $db->sql() );
	if( $result->rowCount() )
	{
		while ( $history = $result->fetch() )
		{
			 
			$history['notify'] = $history['notify'] ? $lang_module['yes'] : $lang_module['no'];
			$history['status'] = $history['status'];
			$history['comment'] = nv_nl2br( $history['comment'] );
			$history['date_added'] = nv_date('d/m/Y', $history['date_added']);
			$xtpl->assign( 'HISTORY', $history );
			$xtpl->parse( 'main.data.history' );
		}
		$result->closeCursor();
		$xtpl->parse( 'main.data' );
	}else
	{
		$xtpl->parse( 'main.no_results' );
	}
	
 
	$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page, true, true, 'nv_urldecode_ajax', 'history' );
 
	if ( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}
  
}
 
 
$xtpl->parse( 'main' );
echo $xtpl->text( 'main' );
 
exit();
