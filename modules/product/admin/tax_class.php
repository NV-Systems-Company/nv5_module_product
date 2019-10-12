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
	$tax_class_id = $nv_Request->get_int( 'tax_class_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $tax_class_id ) )
	{
		$del_array = array( $tax_class_id );
	}

	if( ! empty( $del_array ) )
	{
		$a = 0;
		$_del_array = array();
		$no_del_array = array();
		foreach( $del_array as $tax_class_id )
		{
 
			$product_total =  $db->query( 'SELECT COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_product WHERE tax_class_id = ' . intval( $tax_class_id ) )->fetchColumn();
 
			if( $product_total )
			{
				$json['error'] = sprintf( $lang_ext['error_product'], $product_total );
				nv_jsonOutput( $json );
			}
			else
			{
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_tax_class WHERE tax_class_id = ' . intval( $tax_class_id ) );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_tax_rule WHERE tax_class_id = ' . intval( $tax_class_id ) );

				$json['id'][$a] = $tax_class_id;

				$_del_array[] = $tax_class_id;

				++$a;
			}
 
		}
 
		if( sizeof( $_del_array ) )
		{
			$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_tax_class', implode( ', ', $_del_array ), $admin_info['userid'] );

			$json['success'] = $lang_ext['text_success'];
			
			$ProductGeneral->deleteCache( 'text_class' );
			
		}
		else
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
	$array_based = array(
		'shipping' => $lang_ext['text_shipping'],
		'payment' => $lang_ext['text_payment'],
		'store' => $lang_ext['text_store'] );

	$data = array(
		'tax_class_id' => 0,
		'title' => '',
		'description' => '',
		'date_added' => NV_CURRENTTIME,
		'date_modified' => '' );

	$data['tax_rule'] = array();

	$error = array();

	$data['tax_class_id'] = $nv_Request->get_int( 'tax_class_id', 'get,post', 0 );

	if( $data['tax_class_id'] > 0 )
	{
		$data = $db->query( 'SELECT *
		FROM ' . TABLE_PRODUCT_NAME . '_tax_class  
		WHERE tax_class_id=' . $data['tax_class_id'] )->fetch();

		$result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_tax_rule WHERE tax_class_id = ' . intval($data['tax_class_id']) );
		while( $row  = $result->fetch() )
		{
			$data['tax_rule'][] = array(
				'tax_rate_id' => $row['tax_rate_id'],
				'based' => $row['based'],
				'priority' => $row['priority'],
			);
		}
		$result->closeCursor();
		unset( $result,$row );

		$caption = $lang_ext['text_edit'];
	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['tax_class_id'] = $nv_Request->get_int( 'tax_class_id', 'post', 0 );
		$data['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
		$data['description'] = $nv_Request->get_title( 'description', 'post', '', 1 );
		$data['tax_rule'] = $nv_Request->get_typed_array( 'tax_rule', 'post', array() );

		if( ( nv_strlen( $data['title'] ) < 3 ) || ( nv_strlen( $data['title'] ) > 32 ) )
		{
			$error['title'] = $lang_ext['error_title'];
		}

		if( ( nv_strlen( $data['description'] ) < 3 ) || ( nv_strlen( $data['description'] ) > 255 ) )
		{
			$error['description'] = $lang_ext['error_description'];
		}

		if( empty( $error ) )
		{
			if( $data['tax_class_id'] == 0 )
			{

				$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_tax_class SET 
				title = :title,
				description = :description,
				date_added = ' . NV_CURRENTTIME );

				$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
				$stmt->bindParam( ':description', $data['description'], PDO::PARAM_STR );
				$stmt->execute();

				if( $data['tax_class_id'] = $db->lastInsertId() )
				{
					$stmt->closeCursor();
					if( ! empty( $data['tax_rule'] ) )
					{
						foreach( $data['tax_rule'] as $key => $tax_rule )
						{ 
							$tax_rule['tax_rate_id'] = isset( $tax_rule['tax_rate_id'] ) ? ( int )$tax_rule['tax_rate_id'] : 0;
							$tax_rule['based'] = isset( $tax_rule['based'] ) ? ( string )$tax_rule['based'] : '';
							$tax_rule['priority'] = isset( $tax_rule['priority'] ) ? ( int )$tax_rule['priority'] : 0;
							try
							{
								$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_tax_rule SET 
									tax_class_id = ' . ( int )$data['tax_class_id'] . ', 
									tax_rate_id = ' . ( int )$tax_rule['tax_rate_id'] . ', 
									based = :based,
									priority = ' . ( int )$tax_rule['priority'] );

								$stmt->bindParam( ':based', $tax_rule['based'], PDO::PARAM_STR );
								$stmt->execute();
								$stmt->closeCursor();
							}
							catch ( PDOException $e )
							{
								$error['warning'] = $lang_ext['error_tax_rule_save'];
								//var_dump($e);
							}
						}
					}

					$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A tax class', 'tax_class_id: ' . $data['tax_class_id'], $admin_info['userid'] );
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
					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_tax_class SET 
					title = :title, 
					description = :description, 
					date_modified = ' . NV_CURRENTTIME . '		
					WHERE tax_class_id=' . $data['tax_class_id'] );
					$stmt->bindParam( ':title', $data['title'], PDO::PARAM_STR );
					$stmt->bindParam( ':description', $data['description'], PDO::PARAM_STR );

					if( $stmt->execute() )
					{
						$stmt->closeCursor();

						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_tax_rule WHERE tax_class_id = ' . ( int )$data['tax_class_id'] );

						if( ! empty( $data['tax_rule'] ) )
						{
							foreach( $data['tax_rule'] as $key => $tax_rule )
							{
								$tax_rule['tax_rate_id'] = isset( $tax_rule['tax_rate_id'] ) ? ( int )$tax_rule['tax_rate_id'] : 0;
								$tax_rule['based'] = isset( $tax_rule['based'] ) ? ( string )$tax_rule['based'] : ' ';
								$tax_rule['priority'] = isset( $tax_rule['priority'] ) ? ( int )$tax_rule['priority'] : 0;
								try
								{
									$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_tax_rule SET 
										tax_class_id = ' . ( int )$data['tax_class_id'] . ', 
										tax_rate_id = ' . ( int )$tax_rule['tax_rate_id'] . ', 
										based = :based,
										priority = ' . ( int )$tax_rule['priority'] );

									$stmt->bindParam( ':based', $tax_rule['based'], PDO::PARAM_STR );
									$stmt->execute();
									$stmt->closeCursor();
								}
								catch ( PDOException $e )
								{
									$error['warning'] = $lang_ext['error_tax_rule_save'];
								}
							}
						}

						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A tax class', 'tax_class_id: ' . $data['tax_class_id'], $admin_info['userid'] );
						
						$nv_Request->set_Session( $module_data . '_success', $lang_ext['text_success'] );

						
					}
					else
					{
						$error['warning'] = $lang_ext['error_tax_class_save'];

					}

				}
				catch ( PDOException $e )
				{
					$error['warning'] = $lang_ext['error_tax_class_save'];

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

	$xtpl = new XTemplate( 'tax_class_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'GLANG', $lang_global );
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
	
	$getTaxRate = getTaxRate();
	
	$tax_rule_row = 0;
	if( ! empty( $data['tax_rule'] ) )
	{
		foreach( $data['tax_rule'] as $key => $tax_rule )
		{
			foreach( $getTaxRate as $tax_rate_id => $value )
			{

				$xtpl->assign( 'TAXRATE', array(
					'tax_rate_id' => $tax_rate_id,
					'name' => $value['name'],
					'selected' => ( $tax_rate_id == $tax_rule['tax_rate_id'] ) ? 'selected="selected"' : '',
					) );

				$xtpl->parse( 'main.tax_rule.tax_rate' );
			}
			foreach( $array_based as $based_id => $value )
			{

				$xtpl->assign( 'BASED', array(
					'based_id' => $based_id,
					'name' => $value,
					'selected' => ( $based_id == $tax_rule['based'] ) ? 'selected="selected"' : '',
					) );

				$xtpl->parse( 'main.tax_rule.based' );
			}

			$xtpl->assign( 'TAXRULE', array( 'key' => $key, 'priority' => $tax_rule['priority'] ) );
			$xtpl->parse( 'main.tax_rule' );
			++$tax_rule_row;
		}
	}
	$xtpl->assign( 'tax_rule_row', $tax_rule_row );

	foreach( $getTaxRate as $tax_rate_id => $value )
	{

		$xtpl->assign( 'TAXRATE', array( 'tax_rate_id' => $tax_rate_id, 'name' => $value['name'] ) );

		$xtpl->parse( 'main.tax_rate' );
	}
	foreach( $array_based as $based_id => $value )
	{

		$xtpl->assign( 'BASED', array( 'based_id' => $based_id, 'name' => $value ) );

		$xtpl->parse( 'main.based' );
	}

	if( isset( $error['title'] ) )
	{
		$xtpl->assign( 'error_title', $error['title'] );
		$xtpl->parse( 'main.error_title' );
	}

	if( isset( $error['description'] ) )
	{
		$xtpl->assign( 'error_description', $error['description'] );
		$xtpl->parse( 'main.error_description' );
	}
	
	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'WARNING', $error['warning'] );
		$xtpl->parse( 'main.warning' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	
	unset( $xtpl, $lang_ext, $productCategory, $productArrayYesNo, $productArrayStatus, $productArrayGender, $productArrayPrefix, $getLangModId, $getLangModCode, $array_based, $getTaxRate );
 
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}

/*show list */

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_tax_class';

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'title' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY title";
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

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;

$db->sqlreset()->select( '*' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();
while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;
}
$result->closeCursor();
unset($sql, $result);

$xtpl = new XTemplate( 'tax_class.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/localisation' );
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

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_TITLE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=title&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

if(  $nv_Request->get_string( $module_data . '_success', 'session' ) ) 
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}
 
if( ! empty( $dataContent ) )
{
	foreach( $dataContent as $item )
	{

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['tax_class_id'] );

		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=edit&token=' . $item['token'] . '&tax_class_id=' . $item['tax_class_id'];

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
