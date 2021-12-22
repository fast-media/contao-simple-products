<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2021 Leo Feyer
 *
 * @package   simple_products
 * @author    Fast & Media | Christian Schmidt <info@fast-end-media.de>
 * @license   LGPL
 * @copyright Fast & Media 2013-2021 <https://www.fast-end-media.de>
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace SimpleProducts;

class ProductPrice extends \Frontend
{

  protected $tl = 'tl_product';
  protected $tl_settings = 'tl_product_settings';

	/**
	 * Return Formatted price
	 */
	public function priceFormat($strValue, $intSinglePrice=false)
	{

		//Verhindern, dass nicht-Zahlen zu PHP Fehlern führen
		if($strValue)
		{
			$strValue = doubleval($strValue);
		}

    $this->import('Database');
    $this->import('Product');

		// Load Settings for tax and language etc.
    $objSettings = $this->Product->getSettings();

		if(!$strValue && $objSettings->noprice && $intSinglePrice) {
			if($objSettings->noprice == 'none') { return ''; }
			else { return $GLOBALS['TL_LANG']['MSC']['product_noprice'][$objSettings->noprice]; }
		}

    // Standard Price
		if($objSettings->currency) { $strCurrency = $objSettings->currency; }
		elseif($objSettings->country) { $strCurrency = \Currencies::getCurrency($objSettings->country); }
    else { $strCurrency = 'EUR'; }

		//Währungszeichen
		if($objSettings->currency_sign && $GLOBALS['TL_LANG']['CUR'][$strCurrency]) {
      $strCurrency = $GLOBALS['TL_LANG']['CUR'][$strCurrency];
		}

		if($strValue !== '')
		{
	    $strPrice = number_format($strValue,2,',','.');
		}

    if($objSettings->currency_prefix) {
      $strPrice = $strCurrency.' '.$strPrice;
		}
		else {
			$strPrice = $strPrice.' '.$strCurrency;
		}

		return $strPrice;
	}


	/**
	 * Return Formatted price
	 */
	public function currentSales($objSettings, $strPrice, $categories='')
	{
		$tstamp = time();

    if($objSettings->sales_start <= $tstamp && ($objSettings->sales_stop == 0 || $objSettings->sales_stop > $tstamp)) { $period = true; }
		else { $period = false; }

		if($objSettings->sales && $period) {
			$arrSales = deserialize($objSettings->discount);
      if($arrSales['value'] > 0) { $strSale = $arrSales['value']; }
		}

		// Read categories
		$arrCategories = deserialize($categories);
    $objCategories = \ProductCategoryModel::findPublishedByIds($arrCategories);

		if($objCategories) {
			foreach($objCategories AS $category) {

		    if($category->sales_start <= $tstamp && ($category->sales_stop == 0 || $category->sales_stop > $tstamp)) { $period = true; }
				else { $period = false; }

				if($category->tax_reduced) { $tax_reduced = true; }
				if($category->sales && $period) {
					$arrSales = deserialize($category->discount);
					if($arrSales['value'] > 0) { $strSale = $arrSales['value']; }
				}
			}
		}

		if($strSale) {
			$strUnit = $arrSales['unit'];

			if($strUnit == 'percent') {
				$strPrice = $strPrice*(1-$strSale/100);
				$strSale = $strSale.'%';
			}
			elseif($strUnit) {
				$strPrice = $strPrice-$strSale;
				$strSale = $strSale.' '.$strCurrency;
			}

			$arr = array(
				'price' => $strPrice,
	      'sale' => $strSale,
				'tax_reduced' => $tax_reduced
			);

			return $arr;
		}
	}


	/**
	 * Return tax
	 */
	public function currentPrice($intProductId, $intVariantId='', $intAmount=0)
	{
    $tax = 0;

		// Steuereinstellungen
		$objSettings = \ProductSettingsModel::findOne();
		if($objSettings->tax > 0) {
			$tax = $objSettings->tax;
		}
		if($objSettings->tax_reduced > 0) {
			$tax_reduced = $objSettings->tax_reduced;
		}

		//Check if extension 'simple_products_extended' is installed
		if (in_array('simple_products_shop', $this->Config->getActiveModules()))
		{
			$strSelect = ', shipment_price, scale_prices';
		}
		else
		{
			$strSelect = '';
		}

		//Produkt auslesen
		$objProduct = $this->Database->prepare("SELECT price, category, tax_reduced".$strSelect." FROM $this->tl WHERE id=?")->execute($intProductId);

		//Aktueller Preis
		$strPrice = $objProduct->price;

		//Staffelpreise beim eigentlichen Produkt
    $arrScalePrices = deserialize($objProduct->scale_prices);
		if(!empty($arrScalePrices[0]['price']) && $intAmount) {

			//print_r($arrScalePrices);

			foreach($arrScalePrices AS $arrPrice) {
				//echo $arrPrice['min'].' - '.$intAmount.'<br>';
				if((!$arrPrice['min'] || $arrPrice['min'] <= $intAmount) && ($arrPrice['max'] >= $intAmount || !$arrPrice['max'])) {
					$strPrice = $arrPrice['price'];
				}
				//print_r($arrPrice);
			}
		}

		//Aktueller Variantenpreis
		if($intVariantId)
		{
      $objVariant = $this->Database->prepare("SELECT price, scale_prices FROM tl_product_variant WHERE id=?")->execute($intVariantId);
			if($objVariant->price != '') {
	      $strPrice = $objVariant->price;
			}

			//Staffelpreise der Variante
			if($objVariant->scale_prices && $intAmount)
			{
				$arrScalePrices = deserialize($objVariant->scale_prices);
				//print_r($arrScalePrices);

				// Prevent problems with empty scale prices
				if($arrPrice['price']) {

					foreach($arrScalePrices AS $arrPrice) {
						//echo $arrPrice['min'].' - '.$intAmount.'<br>';
						if((!$arrPrice['min'] || $arrPrice['min'] <= $intAmount) && ($arrPrice['max'] >= $intAmount || !$arrPrice['max'])) {
							$strPrice = $arrPrice['price'];
						}
					}
				}
			}

		}

    $tax_reduced_category = false;
		$hasVariants = false;

		//Check if extension 'simple_products_extended' is installed
		if (in_array('simple_products_extended', $this->Config->getActiveModules()))
		{

		//Hat das Produkt Varianten?
      $objVariant = $this->Database->prepare("SELECT price FROM tl_product_variant WHERE pid=? AND published=1")->execute($intProductId);
			if($objVariant->price != '') {
	      $hasVariants = true;
			}
      //Reduzierter Preis
			$arrCategorySales = $this->currentSales($objSettings, $strPrice, $objProduct->category);
      if($arrCategorySales['price']) { $strPrice = $arrCategorySales['price']; }
      if($arrCategorySales['tax_reduced']) { $tax_reduced_category = $arrCategorySales['tax_reduced']; }
      if($arrCategorySales['sale']) { $sale = $arrCategorySales['sale']; }
		}

		//Steuersatz
		if($objProduct->tax_reduced && $tax_reduced || $tax_reduced_category) { $tax = $tax_reduced; }

		//Enthaltene Steuern ausrechnen
		if($objSettings->gross && $tax > 0)
		{
			$taxTotal = round($strPrice*($tax/100),2);
		}
		elseif($tax) {
			$taxTotal = round($strPrice/($tax/100+1)*$tax/100,2);
		}
		else {
			$taxTotal = 0;
		}

		$arr = array(
			'price' => $strPrice,
      'variants' => $hasVariants,
			'tax' => $tax,
			'taxTotal' => $taxTotal,
			'sale' => $sale
		);

		if($objProduct->shipment_price) {
			$arr['shipment_price'] = $objProduct->shipment_price;
		}

		return $arr;
	}
}
