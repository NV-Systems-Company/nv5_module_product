<?php


namespace NukeViet\Product;

use NukeViet\Product\General;
use NukeViet\Product\Tax;
use NukeViet\Product\Currency;
use NukeViet\Product\Product;

class flat extends General
{
	private $flat_config = null;
	private $currency = null;
	private $product = null;
	
	public function __construct( $productRegistry )
	{
		global $ProductGeneral, $ProductCurrency, $ProductTax;
		
		parent::__construct( $productRegistry ); 
		
		$this->flat_config = $this->getSetting( 'flat', $this->store_id );
 
		$this->tax = new Tax( $productRegistry );

		$this->currency = new Currency( $productRegistry );

		$this->product = new Product( $productRegistry );

	}

	public function getQuote( $address )
	{
		global $db;
		$query = $db->query( 'SELECT * FROM ' . $this->table . '_zone_to_geo_zone WHERE geo_zone_id = ' . ( int )$this->flat_config['flat_geo_zone_id'] . ' AND country_id = ' . ( int )$address['country_id'] . ' AND (zone_id = ' . ( int )$address['zone_id'] . ' OR zone_id = 0)' );

		if( ! $this->flat_config['flat_geo_zone_id'] )
		{
			$status = true;
		}
		elseif( $query->rowCount() )
		{
			$status = true;
		}
		else
		{
			$status = false;
		}

		$method_data = array();

		if( $status )
		{
			$language = $this->getLangSite( 'flat', 'shipping' );
			
			$quote_data = array();

			$quote_data['flat'] = array(
				'code' => 'flat.flat',
				'title' => $language['text_description'],
				'cost' => $this->flat_config['flat_cost'],
				'tax_class_id' => $this->flat_config['flat_tax_class_id'],
				'text' => $this->currency->format( $this->tax->calculate( $this->flat_config['flat_cost'], $this->flat_config['flat_tax_class_id'], $this->config['config_tax'] ) ) );

			$method_data = array(
				'code' => 'flat',
				'title' => $language['text_title'],
				'quote' => $quote_data,
				'sort_order' => $this->flat_config['flat_sort_order'],
				'error' => false );
		}

		return $method_data;
	}
}