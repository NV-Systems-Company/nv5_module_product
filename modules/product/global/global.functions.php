<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Nuke.vn. All rights reserved
 * @website https://nuke.vn
 * @License GNU/GPL version 3 or any later version
 * @Createdate Wed, 24 Aug 2016 02:00:00 GMT
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );
 
function getCategory()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;
	
	$sql='SELECT * FROM ' . $ProductGeneral->table . '_category c
	LEFT JOIN ' . $ProductGeneral->table . '_category_description cd ON (c.category_id = cd.category_id) 
	WHERE cd.language_id = ' . intval( $ProductGeneral->current_language_id ) . '
	ORDER BY sort ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'category', 'category_id' );

 
}

function getParentInCat( $category_id, $check_inhome = 0 )
{
	global $productCategory;

	$categoryListId[] = $category_id;
	$subcatid = array_map('intval', explode( ',', $productCategory[$category_id]['subcatid'] ));
	if( ! empty( $subcatid ) )
	{
		foreach( $subcatid as $_category_id )
		{
			if( $_category_id > 0 )
			{
				if( $productCategory[$_category_id]['numsubcat'] == 0 )
				{
					if( ! $check_inhome or ( $check_inhome and $productCategory[$_category_id]['inhome'] == 1 ) )
					{
						$categoryListId[] = $_category_id;
					}
				}
				else
				{
					$categoryListIdTemp = getParentInCat( $_category_id, $check_inhome );
					foreach( $categoryListIdTemp as $_category_id_i )
					{
						if( ! $check_inhome or ( $check_inhome and $productCategory[$_category_id_i]['inhome'] == 1 ) )
						{
							$categoryListId[] = $_category_id_i;
						}
					}
				}
			}
		}
	}
	return array_unique( $categoryListId );
	 

}

function getCatidInParent( $category_id, &$categoryListId )
{
	global $productCategory, $categoryListId;

	if( $productCategory[$category_id]['lev'] == 0 )
	{
		$categoryListId[] = $category_id;

		return $category_id;

	}
	else
	{

		$cat = $productCategory[$category_id];

		$categoryListId[] = $cat['category_id'];

		return getCatidInParent( $cat['parent_id'], $categoryListId );
	}

}

function getStores()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	return $nv_Cache->db( 'SELECT * FROM ' . $ProductGeneral->table . '_store ORDER BY url ASC', 'store_id', $ProductGeneral->mod_name );
}

function getCustomerGroup()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT g.customer_group_id, g.approval, gd.language_id, gd.name, gd.description FROM ' . $ProductGeneral->table . '_group g
		LEFT JOIN ' . $ProductGeneral->table . '_group_description gd
		ON (g.customer_group_id = gd.customer_group_id)
		WHERE gd.language_id = ' . $ProductGeneral->current_language_id . '
		ORDER BY g.weight ASC';

	return $ProductGeneral->getdbCache( $sql, 'customer_group', 'customer_group_id' );

}

function getBrand()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;
	return $ProductGeneral->getdbCache( 'SELECT b.*, bd.name, bd.alias, bd.description  FROM ' . $ProductGeneral->table . '_brand b  
	LEFT JOIN ' . $ProductGeneral->table . '_brand_description bd ON (b.brand_id = bd.brand_id ) ORDER BY b.weight ASC', 'brand', 'brand_id' );
}

function getOption( $option_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	return $db->query( 'SELECT * FROM ' . $ProductGeneral->table . '_option o LEFT JOIN ' . $ProductGeneral->table . '_option_description od ON (o.option_id = od.option_id) WHERE o.option_id = ' . ( int )$option_id . ' AND od.language_id = ' . ( int )$ProductGeneral->current_language_id )->fetch();

}

function getOptionValue( $option_value_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	return $db->query( 'SELECT * FROM ' . $ProductGeneral->table . '_option_value ov LEFT JOIN ' . $ProductGeneral->table . '_option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_value_id = ' . ( int )$option_value_id . ' AND ovd.language_id = ' . ( int )$ProductGeneral->current_language_id )->fetch();

}

function getOptionValues( $option_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT * FROM ' . $ProductGeneral->table . '_option_value ov LEFT JOIN ' . $ProductGeneral->table . '_option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_id = ' . ( int )$option_id . ' AND ovd.language_id = ' . ( int )$ProductGeneral->current_language_id . ' ORDER BY ov.sort_order, ovd.name';
	return $ProductGeneral->getdbCache( $sql, 'option_value', 'option_value_id' );

}

function getInformation()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT a.*, b.* FROM ' . $ProductGeneral->table . '_information a 
	LEFT JOIN ' . $ProductGeneral->table . '_information_description b ON (a.information_id = b.information_id) 
	WHERE b.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' ORDER BY a.sort_order';
	return $ProductGeneral->getdbCache( $sql, 'information', 'information_id' );

}

function getAttributeGroup()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT ag.*, agd.name FROM ' . $ProductGeneral->table . '_attribute_group ag LEFT JOIN ' . $ProductGeneral->table . '_attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE agd.language_id = ' . ( int )$ProductGeneral->current_language_id . ' ORDER BY ag.sort_order ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'attribute_group', 'attribute_group_id' );
}

function getAttribute()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT a.*, ad.name FROM ' . $ProductGeneral->table . '_attribute a LEFT JOIN ' . $ProductGeneral->table . '_attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.language_id = ' . ( int )$ProductGeneral->current_language_id . ' ORDER BY a.sort_order ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'attribute', 'attribute_id' );
	
}

function getOrderStatus()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT * FROM ' . $ProductGeneral->table . '_order_status WHERE language_id = ' . intval( $ProductGeneral->current_language_id ) . ' ORDER BY name ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'order_status', 'order_status_id' );

}

function getVoucherTheme()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT vc.voucher_theme_id, vct.name FROM ' . $ProductGeneral->table . '_voucher_theme vc
	LEFT JOIN ' . $ProductGeneral->table . '_voucher_theme_description vct ON (vc.voucher_theme_id = vct.voucher_theme_id)
	WHERE language_id=' . intval( $ProductGeneral->current_language_id );

	return $ProductGeneral->getdbCache( $sql, 'voucher_theme', 'voucher_theme_id' );
}

function getCountries()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT * FROM ' . $ProductGeneral->table . '_country WHERE status = 1 ORDER BY name ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'users', 'country_id' );
	
}
function getCountry( $country_id = 0 )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;
	if($country_id > 0)
		return $db->query( 'SELECT * FROM ' . $ProductGeneral->table . '_country WHERE country_id= ' . intval( $country_id ))->fetch();
	else
		return $db->query( 'SELECT * FROM ' . $ProductGeneral->table . '_country ')->fetch();
}
 
function getZoneName( $zone_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT * FROM ' . $ProductGeneral->table . '_zone WHERE zone_id = ' . intval( $zone_id ) . ' LIMIT 0, 1';

	$result = $db->query( $sql );

	$array = $result->fetch();

	$result->closeCursor();

	return $array;
}

function getGeoZones()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT * FROM ' . $ProductGeneral->table . '_geo_zone ORDER BY name ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'geo_zone', 'geo_zone_id' );

}

function getTaxClass()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT * FROM ' . $ProductGeneral->table . '_tax_class ORDER BY title ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'tax_class', 'tax_class_id' );
}

function getTaxRate()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT * FROM ' . $ProductGeneral->table . '_tax_rate ORDER BY name ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'tax_rate', 'tax_rate_id' );
}

function getWeightClass()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT wc.*, wcd.title, wcd.unit FROM ' . $ProductGeneral->table . '_weight_class wc 
	LEFT JOIN ' . $ProductGeneral->table . '_weight_class_description wcd ON (wc.weight_class_id =  wcd.weight_class_id )
	WHERE wcd.language_id = ' . $ProductGeneral->current_language_id . ' ORDER BY wcd.title ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'weight_class', 'weight_class_id' );
}

function getLengthClass()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT lc.*, lcd.title, lcd.unit FROM ' . $ProductGeneral->table . '_length_class lc 
	LEFT JOIN ' . $ProductGeneral->table . '_length_class_description lcd ON (lc.length_class_id =  lcd.length_class_id )
	WHERE lcd.language_id = ' . $ProductGeneral->current_language_id . ' ORDER BY lcd.title ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'length_class', 'length_class_id' );
	
}

function getStockStatus()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT stock_status_id, name FROM ' . $ProductGeneral->table . '_stock_status
	WHERE language_id = ' . $ProductGeneral->current_language_id . ' 
	ORDER BY name ASC';
	return $ProductGeneral->getdbCache( $sql, 'stock_status', 'stock_status_id' );
}

function getUnits()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT u.units_id, ud.name FROM ' . $ProductGeneral->table . '_units u
	LEFT JOIN ' . $ProductGeneral->table . '_units_description ud ON (u.units_id =  ud.units_id )
	WHERE ud.language_id = ' . $ProductGeneral->current_language_id . ' ORDER BY u.weight ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'units', 'units_id' );
}

function getBlock()
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT bc.block_id, bcd.name FROM ' . $ProductGeneral->table . '_block_cat bc
	LEFT JOIN ' . $ProductGeneral->table . '_block_cat_description bcd ON (bc.block_id =  bcd.block_id )
	WHERE bcd.language_id = ' . $ProductGeneral->current_language_id . ' ORDER BY bc.weight ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'block', 'block_id' );
}

function getFilter( $array_filter_id )
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;

	$sql = 'SELECT *, (SELECT name FROM ' . $ProductGeneral->table . '_filter_group_description fgd 
	WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' )  group_name 
	FROM ' . $ProductGeneral->table . '_filter f 
	LEFT JOIN ' . $ProductGeneral->table . '_filter_description fd ON (f.filter_id = fd.filter_id) 
	WHERE fd.language_id = ' . intval( $ProductGeneral->current_language_id ) . ' AND f.filter_id IN (' . implode( ',', $array_filter_id ) . ') ORDER BY f.sort_order ASC';

	$result = $db_slave->query( $sql );
	$data = array();
	while( $filter = $result->fetch() )
	{
		$data[] = array( 'filter_id' => $filter['filter_id'], 'name' => strip_tags( html_entity_decode( $filter['group_name'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8' ) ) );
	}
	$result->closeCursor();
	return $data;
}

function getCurrencies( ) 
{
	global $db, $db_slave, $ProductGeneral, $nv_Cache, $productRegistry;
	
	$sql ='SELECT * FROM ' . $ProductGeneral->table . '_currency ORDER BY title ASC';
	
	return $ProductGeneral->getdbCache( $sql, 'currency', 'code' );

}

function getRandomString( $length = 15 )
{
	return substr( md5( uniqid( '', true ) ), 0, $length );
}

function floatFormat( $number, $round = 8 )
{
	return floatval( number_format( $number, $round, '.', '' ) );
}
 
$productCategory = getCategory();
$productArrayYesNo = array( '0' => $lang_module['no'], '1' => $lang_module['yes'] );
$productArrayStatus = array( '0' => $lang_module['disabled'], '1' => $lang_module['enable'] );
$productArrayGender = array( 'M' => $lang_module['male'], 'F' => $lang_module['female'] );
$productArrayPrefix = array( '-', '+' );

/** LANG_MOD **/
global $getLangModId, $getLangModCode;
$getLangModId = $ProductGeneral->getLangMod( 'language_id' );
$getLangModCode = $ProductGeneral->getLangMod( 'code' );
/** LANG_MOD **/
 
 
 function getAddresses()
	{
		global $db, $ProductContent, $globalUserid,$ProductGeneral;
		$address_data = array();
		
		$query = $db->query( 'SELECT * FROM ' . $ProductGeneral->table . '_address WHERE customer_temp_id = ' . ( int )$globalUserid . '' )->fetch();
print_r($querys);
		foreach( $querys as $result )
		{
			
			$country_query = $db->query( 'SELECT * FROM ' . $ProductGeneral->table . '_country WHERE country_id = ' . ( int )$result['country_id'] . '' )->fetch();

			if( $country_query )
			{
				$country = $country_query['name'];
				$iso_code_2 = $country_query['iso_code_2'];
				$iso_code_3 = $country_query['iso_code_3'];
				$address_format = $country_query['address_format'];
			}
			else
			{
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $db->query( 'SELECT * FROM ' . $ProductGeneral->table . '_zone WHERE zone_id = ' . ( int )$result['zone_id'] . '' )->fetch();

			if( $zone_query )
			{
				$zone = $zone_query['name'];
				$zone_code = $zone_query['code'];
			}
			else
			{
				$zone = '';
				$zone_code = '';
			}

			$address_data[$result['address_id']] = array(
				'address_id' => $result['address_id'],
				'firstname' => $result['firstname'],
				'lastname' => $result['lastname'],
				'company' => $result['company'],
				'address_1' => $result['address_1'],
				'address_2' => $result['address_2'],
				'postcode' => $result['postcode'],
				'city' => $result['city'],
				'zone_id' => $result['zone_id'],
				'zone' => $zone,
				'zone_code' => $zone_code,
				'country_id' => $result['country_id'],
				'country' => $country,
				'iso_code_2' => $iso_code_2,
				'iso_code_3' => $iso_code_3,
				'address_format' => $address_format,
				'custom_field' => json_decode( $result['custom_field'], true ) );
		}

		return $address_data;
	}

	function addAddress( $userid, $data )
	{
		global $db, $ProductContent, $globalUserid, $ProductGeneral;

		$db->query( 'INSERT INTO ' . $ProductGeneral->table . '_address SET customer_temp_id = ' . ( int )$globalUserid . ', first_name = ' . $db->quote( $data['firstname'] ) . ', last_name = ' . $db->quote( $data['lastname'] ) . ', company = ' . $db->quote( $data['company'] ) . ', address_1 = ' . $db->quote( $data['address_1'] ) . ', address_2 = ' . $db->quote( $data['address_2'] ) . ', postcode = ' . $db->quote( $data['postcode'] ) . ', city = ' . $db->quote( $data['city'] ) . ', zone_id = ' . ( int )$data['zone_id'] . ', country_id = ' . ( int )$data['country_id'] . ', custom_field = ' . $db->quote( isset( $data['custom_field'] ) ? json_encode( $data['custom_field'] ) : '' ) );

		$address_id = $db->lastInsertId();

		if( ! empty( $data['default'] ) )
		{
			$db->query( 'UPDATE ' . $ProductGeneral->table . '_address SET address_id = ' . ( int )$address_id . ' WHERE customer_temp_id = ' . ( int )$globalUserid . '' );
		}

		return $address_id;
	}
	function getAddress( $userid,$address_id )
	{
		global $db, $ProductContent, $globalUserid, $ProductGeneral;
		$address_query = $db->query( 'SELECT DISTINCT * FROM ' . $ProductGeneral->table . '_address WHERE address_id = ' . ( int )$address_id . ' AND customer_temp_id = ' . ( int )$globalUserid . '' )->fetch();
		
		if( $address_query )
		{
			$country_query = $db->query( 'SELECT * FROM ' . $ProductGeneral->table . '_country WHERE country_id = ' . ( int )$address_query['country_id'] . '' )->fetch();

			if( $country_query )
			{
				$country = $country_query['name'];
				$iso_code_2 = $country_query['iso_code_2'];
				$iso_code_3 = $country_query['iso_code_3'];
				$address_format = $country_query['address_format'];
			}
			else
			{
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $db->query( 'SELECT * FROM ' . $ProductGeneral->table . '_zone WHERE zone_id = ' . ( int )$address_query['zone_id'] . '' )->fetch();

			if( $zone_query )
			{
				$zone = $zone_query['name'];
				$zone_code = $zone_query['code'];
			}
			else
			{
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'address_id' => $address_query['address_id'],
				'firstname' => $address_query['firstname'],
				'lastname' => $address_query['lastname'],
				'company' => $address_query['company'],
				'address_1' => $address_query['address_1'],
				'address_2' => $address_query['address_2'],
				'postcode' => $address_query['postcode'],
				'city' => $address_query['city'],
				'zone_id' => $address_query['zone_id'],
				'zone' => $zone,
				'zone_code' => $zone_code,
				'country_id' => $address_query['country_id'],
				'country' => $country,
				'iso_code_2' => $iso_code_2,
				'iso_code_3' => $iso_code_3,
				'address_format' => $address_format,
				'sql' => 'SELECT DISTINCT * FROM ' . $ProductGeneral->table . '_address WHERE address_id = ' . ( int )$address_id . ' AND customer_temp_id = ' . ( int )$globalUserid . '',
				'custom_field' => json_decode( $address_query['custom_field'], true ) );

			return $address_data;
		}
		else
		{
			return false;
		}
	}
	function editAddress( $address_id, $data )
	{
		global $db, $ProductContent, $globalUserid, $ProductGeneral;

		$db->query( 'UPDATE ' . $$ProductGeneral->table . '_address SET first_name = ' . $db->quote( $data['firstname'] ) . ', last_name = ' . $db->quote( $data['lastname'] ) . ', company = ' . $db->quote( $data['company'] ) . ', address_1 = ' . $db->quote( $data['address_1'] ) . ', address_2 = ' . $db->quote( $data['address_2'] ) . ', postcode = ' . $db->quote( $data['postcode'] ) . ', city = ' . $db->quote( $data['city'] ) . ', zone_id = ' . ( int )$data['zone_id'] . ', country_id = ' . ( int )$data['country_id'] . ', custom_field = ' . $db->quote( isset( $data['custom_field'] ) ? json_encode( $data['custom_field'] ) : '' ) . ' WHERE address_id  = ' . ( int )$address_id . ' AND customer_temp_id = ' . ( int )$globalUserid . '' );

		if( ! empty( $data['default'] ) )
		{
			$db->query( 'UPDATE ' . $ProductGeneral->table . '_address SET address_id = ' . ( int )$address_id . ' WHERE customer_temp_id = ' . ( int )$globalUserid . '' );
		}
	}
	function editAddressId($customer_id, $address_id) {
		global $db, $ProductContent, $globalUserid, $ProductGeneral;
		$db->query("UPDATE " . $ProductGeneral->table . "_address SET address_id = '" . (int)$address_id . "' WHERE customer_temp_id = '" . (int)$customer_id . "'");
	}