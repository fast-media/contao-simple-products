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



abstract class ModuleProduct extends \Module
{

	/**
	 * URL cache array
	 * @var array
	 */
	private static $arrUrlCache = array();

	protected function array_unique_by_subitem($array, $key, $sort_flags = SORT_STRING){
		$items = array();
		// Die Subeitems auslesen
		foreach($array as $index => $item) $items[$index] = $item[$key];
		//Die Subitems mit array_unique bearbeiten
		$uniqueItems = array_unique($items, $sort_flags);
		//Der eigentliche Array über den Key mit den selektierten Subitems abgleichen
		return array_intersect_key($array, $uniqueItems);
	}


	/**
	 * Sort out protected archives
	 * @param array
	 * @return array
	 */
	protected function sortOutProtected($arrArchives)
	{
		if (BE_USER_LOGGED_IN || !is_array($arrArchives) || empty($arrArchives))
		{
			return $arrArchives;
		}

		$this->import('FrontendUser', 'User');
		$objArchive = \ProductArchiveModel::findMultipleByIds($arrArchives);
		$arrArchives = array();

		if ($objArchive !== null)
		{
			while ($objArchive->next())
			{
				if ($objArchive->protected)
				{
					if (!FE_USER_LOGGED_IN)
					{
						continue;
					}

					$groups = deserialize($objArchive->groups);

					if (!is_array($groups) || empty($groups) || !count(array_intersect($groups, $this->User->groups)))
					{
						continue;
					}
				}

				$arrArchives[] = $objArchive->id;
			}
		}

		return $arrArchives;
	}

	private function highlight_words($strValue, $needle) {
		if(!stristr($strValue, '<span class="highlight">')) {
			// return $strValue if there is no highlight color or strings given, nothing to do.
			if (strlen($strValue) < 2 || strlen($needle) < 2) {
				 return $strValue;
			}

			$strValue = preg_replace("/($needle)/i", '<span class="highlight">$1</span>', $strValue);

			$strValue = preg_replace("/(<[^>]*)(<span class=\"highlight\">)($needle)(<\/span>)+/i", "$1$3", $strValue); //Temporär, wieder zurück ersetzen, wenn Suchbegriff innerhalb von Tags vorkommt
		}
		return $strValue;
	}


	public function labels()
	{
    $this->loadDataContainer('tl_product');
    $this->loadLanguageFile('tl_content');
    $this->loadLanguageFile('tl_product');
    $arrLabels = array();
    foreach($GLOBALS['TL_DCA']['tl_product']['fields'] AS $key => $arrField) {
			$arrLabels[$key] = $arrField['label'][0];
		}

		return (object)$arrLabels;
	}

	/**
	 * Parse an item and return it as string
	 * @param object
	 * @param boolean
	 * @param string
	 * @param integer
	 * @return string
	 */
	protected function parseProduct($objProduct, $blnAddArchive=false, $strClass='', $intCount=0, $divs='')
	{
		global $objPage;

		$objTemplate = new \FrontendTemplate($this->product_template);
		$objTemplate->setData($objProduct->row());
		$objTemplate->class = (($objProduct->cssClass != '') ? ' ' . $objProduct->cssClass : '') . (($objProduct->addImage && $objProduct->singleSRC != '') ? ' product_image' : '') . (($objProduct->new) ? ' new' : '') . (($objProduct->featured) ? ' featured' : ''). $strClass;
		$objTemplate->productTitle = $objProduct->title;
		$objTemplate->subTitle = $objProduct->subtitle;
		$objTemplate->hasSubTitle = $objProduct->subtitle ? true : false;
		$objTemplate->linkHeadline = $this->generateLink($objProduct->title, $objProduct, $blnAddArchive);
		$objTemplate->link = $this->generateProductUrl($objProduct, $blnAddArchive);

		if($objTemplate->link) {
			$objTemplate->more = $this->generateLink($GLOBALS['TL_LANG']['MSC']['moreProduct'], $objProduct, $blnAddArchive, true);
		}
		//$objTemplate->archive = $objProduct->getRelated('pid');
		$objTemplate->divs = $divs;

    $objTemplate->label = $this->labels();

		//Produktart ersetzen
		if($objTemplate->type)
    {
			$objProductType = \ProductTypeModel::findPublishedByIdOrAlias($objTemplate->type);
      $objTemplate->type_title = $objProductType->title;
      $objTemplate->type_id = $objProductType->id;
		}

		// Check if extension 'simple_products_helper' is installed
		if (in_array('simple_products_helper', $this->Config->getActiveModules()))
		{
			//Produkt bestellen
			if($this->booking_cart || $this->booking_watchlist || $this->show_enquiry) {

				if (\Environment::get('isAjaxRequest') && \Input::post('FORM_SUBMIT') != '' && in_array('haste', $this->Config->getActiveModules())) {
					$ajax_post = true;
				}

				$this->import('SimpleProductsShop\SimpleProductsBooking', 'Booking');
				$booking = true;
			}

			if($booking)
			{
				$form_submit = \Input::post('FORM_SUBMIT');
				if(\Input::post('productCart')) {
					$submit_type = 'productCart';
				}
				elseif(\Input::post('productWatch'))
				{
					$submit_type = 'productWatch';
				}
				elseif(\Input::post('productEnquiry'))
				{
					$submit_type = 'productEnquiry';
				}

				// Get CSS Class
		    $strClass = 'product_booking';

				$arrOptions = array
				(
					'submit_type' => $submit_type,
					'product_variants' => $this->product_variants,
					'show_price' => $this->show_price,
          'booking_type' => $this->booking_type,
					'booking_max' => $this->booking_max,
					'booking_repeat' => $this->booking_repeat,
          'booking_cart' => $this->booking_cart,
					'cart_jump' => $this->cart_jump,
					'cart_jumpTo' => $this->cart_jumpTo,
					'booking_watchlist' => $this->booking_watchlist,
					'watchlist_jump' => $this->watchlist_jump,
					'watchlist_jumpTo' => $this->watchlist_jumpTo,
					'show_enquiry' => $this->show_enquiry,
					'enquiry_jump' => $this->enquiry_jump,
					'enquiry_jumpTo' => $this->enquiry_jumpTo,
					'show_agreement' => $this->show_agreement,
					'agreement_headline' => $this->agreement_headline,
					'agreement_text' => $this->agreement_text,
					'module_type' => $this->type,
					'product_link' => $objTemplate->link,
					'module_type' => $this->type,
					'module_id' => $this->id,
	        'action' => ampersand($this->Environment->request),
	        'class' => $strClass,
					'headline' => $this->headline,
					'hl' => $this->hl
				);

				if($objProduct->bookable == 1 || $this->booking_watchlist || $this->show_enquiry)
				{
					if($ajax_post)
					{
						$parseForm = $this->Booking->parseForm($objProduct, $arrOptions);
						$objResponse = new HtmlResponse($parseForm);
						$objResponse->send();
					}
					else
					{
						$objTemplate->form = $this->Booking->parseForm($objProduct, $arrOptions);
					}

					$objTemplate->bookable = 1;
				}
			}
		}

		//Varianten anzeigen
		if($this->product_variants)
		{
			$product_variants = deserialize($this->product_variants);

			if(is_array($product_variants) && $product_variants[0])
			{
				$this->loadDataContainer('tl_product');
				$this->loadLanguageFile('tl_product');

				//URL anpassen
				$base_url = $this->Environment->requestUri;
				if(stristr($base_url, '?'))
				{
					$var_url = strstr($base_url, '?');
					$base_url = strstr($base_url, '?', true);
				}
//echo $base_url;
				$objVariants = $this->Database->prepare("SELECT * FROM tl_product_variant WHERE pid = ?")->execute($objProduct->id);
				$arrVariants = array();
				while($objVariants->next()) {
					//if(in_array('mark', $product_variants)) { echo 'test'; }
					foreach($product_variants AS $variant) {
						if($objVariants->$variant != '' && $objVariants->$variant != '0.00') { $arrVariants[$variant][] = array('id' => $objVariants->id, 'title' => $objVariants->$variant);
							$arrVariants[$variant] = $this->array_unique_by_subitem($arrVariants[$variant], 'title'); //Doppelte Einträge entfernen
			}
					}
				}
				//print_r($arrVariants);

			//print_r($objTemplate->focus);
			$fieldsArray = array();
				foreach($arrVariants AS $key => $variant) {
					$suchmuster = array('/[&|?]'.$key.'=[a-zäöü0-9-_]*/i', '/[&|?]order_by=[a-z0-9-_]*/i', '/\?/i');
					$ersetzung = array('','','&');
					$url = preg_replace($suchmuster, $ersetzung, $var_url);

					if(stristr($url,'&')) { $url = '?'.substr($url,1); }
					$objTemplate->base_url = $base_url.$url;

					if(stristr($url,'?')) { $url = $url.'&'; }
					else { $url = '?'; }

					$url = $value = $base_url.$url.$key;

					$options = array();
					$reference = array();

					foreach($variant AS $var) {
						//echo $variant;
						$options[] = $var['title']; //$options[] = $var['id'];
						$reference[$var['title']] = $var['title']; //$reference[$var['id']] = $var['title'];
						$values[$var['title']] = $url.'='.$var['title']; //$values[$var['id']] = $url.'='.$var['id'];
					}
					//print_r($options);
					//$options = implode(',', $objVariants->);
					//$options = array('blau,rot');
					if($options) {
						$active = \Input::get($key);
						$fieldsArray[$key] = array(
							'label'		=> &$GLOBALS['TL_DCA']['tl_product']['fields'][$key]['label'][0],
							'name'		=> $key,
							'inputType'	=> 'select',
							'options' => $options,
							'reference' => $reference,
							'values'		 => $values,
							'active'		 => $active
						);
						$variants = true;
					}
				}

				if($variants) {
					$objTemplate->variants = $fieldsArray;
				}
				//print_r($fieldsArray);

				if($objVariants->amount) {
					$arrAmount = deserialize($objVariants->amount);
					if($arrAmount['value']) {
            $intAmount = $arrAmount['value'];
					}
				}

			}
			else {

			}
		}

		$arrCategories = deserialize($objProduct->category);
		$objCategories = \ProductCategoryModel::findPublishedByIds($arrCategories);

		if($objCategories) {
			$arrCategories = array();

			while ($objCategories->next())
			{
				//Fallback Image erkennen
				if($objCategories->fallback_image) { $fallback_image = $objCategories->fallback_image; }
				$alias = $objCategories->alias;
				$arrCategories[$objCategories->id] = $objCategories->row();
				$arrCategories[$objCategories->id]['class'] = 'category ' . ($objCategories->cssClass ? $objCategories->cssClass : 'category_' . $objCategories->id) . ((++$count == 1) ? ' first' : '') . (($count == $total) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even');
				$arrCategories[$objCategories->id]['title'] = specialchars($objCategories->title);

				if($this->category_jumpTo)
				{
					$objJumpTo = \PageModel::findByPk($this->category_jumpTo);
					$strUrl = $objJumpTo->row();
				}
				elseif($objPage)
				{
					$strUrl = $objPage->row();
				}

				if($strUrl)
				{
					$arrCategories[$objCategories->id]['href'] = ampersand($this->generateFrontendUrl($strUrl, ($GLOBALS['TL_CONFIG']['useAutoItem'] ?	'/' : '/category/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objCategories->alias != '') ? $objCategories->alias : $objCategories->id)));
				}

				$arrCategories[$objCategories->id]['link'] = $objCategories->title;
				$arrCategories[$objCategories->id]['isActive'] = ((\Input::get('category') != '') && (\Input::get('category') == ($GLOBALS['TL_CONFIG']['disableAlias'] ? $objCategories->id : $objCategories->alias))) ? true : false;
			}

			$objTemplate->categories = $arrCategories;
		}

		$objTemplate->count = $intCount;

		// Reset amount to prevent showing empty blob
		$objTemplate->amount = '';

    // Anzahl umwandeln
		$arrAmount = deserialize($objProduct->amount);
		if(is_array($arrAmount))
		{
			$intAmount = $arrAmount['value'];
			$objTemplate->unit = $arrAmount['unit'];
			if($objTemplate->unit)
			{
				$objTemplate->amountFormatted = $arrAmount['value'].' '.$objTemplate->unit;
			}
		}

		if($intAmount)
		{
      $objTemplate->amount = $intAmount;
		}

		//Gewicht umwandeln
		$arrWeight = deserialize($objProduct->weight);

		$objTemplate->weight = '';
		if(is_array($arrWeight))
		{
			$objTemplate->weight = $arrWeight['value'];
      $objTemplate->weightUnit = $arrWeight['unit'];
			//Kommastellen zählen
      $intDecimal = strlen(strstr($objTemplate->weight, '.'))-1;
			//if($objTemplate->weight == round($objTemplate->weight)) { $intDecimal = 0; }

			if($arrWeight['value'] > 0) { $objTemplate->weightFormatted = number_format($arrWeight['value'], $intDecimal, ',', '.'); }
			if($arrWeight['unit']) { $objTemplate->weightFormatted .= ' '.$arrWeight['unit']; }
		}

		// Abfrage muss noch ausgelagert werden, da sie in einer Schleife stattfindet
    $this->import('Product');
		$objSettings = $this->Product->getSettings();

		// Price
		if($objTemplate->price == 0)
		{
			if($objSettings->noprice != 'none' && $objSettings->noprice)
			{
				$objTemplate->priceFormatted = $GLOBALS['TL_LANG']['MSC']['product_noprice'][$objSettings->noprice];
			}
			else
			{
        $objTemplate->price = '';
			}
		}
		else
		{
      $this->import('ProductPrice', 'Price');

			$objTemplate->priceFormatted = $this->Price->priceFormat($objTemplate->price);

			if($objSettings->show_tax)
			{
				if($objSettings->tax > 0 || $objSettings->tax_reduced > 0) {
          $this->import('FrontendUser', 'User');

          if($this->User->account_type == 'Firma' && $this->User->uid && $objSettings->digital_products && $this->User->country && $this->User->country != $objSettings->country) {
						$objTemplate->priceInfo = $GLOBALS['TL_LANG']['product_info']['tax_reverse_charge'];
					}
					elseif($objSettings->gross) {
						$objTemplate->priceInfo = $GLOBALS['TL_LANG']['product_info']['tax_gross'];
					}
					else {
            $objTemplate->priceInfo = $GLOBALS['TL_LANG']['product_info']['tax'];
					}
				}
				else {
					$objTemplate->priceInfo = $GLOBALS['TL_LANG']['product_info']['tax_small_business'];
				}
			}
		}

		// Shipment price
		if($objSettings->shipment_price > 0)
		{
			$objTemplate->priceInfo .= ', <a href="system/modules/simple_products_shop/assets/ShipmentPrices.php" data-lightbox="shipment'.$objProduct->id.'">'.$GLOBALS['TL_LANG']['product_info']['shipment'].'</a>';
		}


		if($variants)
		{
      $this->import('ProductPrice', 'Price');

		  foreach($fieldsArray AS $key=>$field) {
        $value = \Input::get($key);

				//Varianten auslesen
				if($key == 'weight') {
	        $strWeightUnit = substr(strstr($value, ' '),1);
	        $strWeightValue = strstr($value, ' ', true);
					$value = serialize(array('unit' => $strWeightUnit, 'value' => $strWeightValue));
				}

     		if($value) {
	      	$arrPriceWhere[] = $key.' = \''.$value.'\'';
				}
			}

			//Preis anzeigen
			$objPrice = $this->Database->prepare("SELECT id, IF(price!='',price,'$objTemplate->price') AS price, amount FROM tl_product_variant WHERE " . ($arrPriceWhere ? implode(' AND ', $arrPriceWhere).' AND ' : '') . " pid = ? AND published = 1 GROUP BY price ORDER BY price ASC")->execute($objProduct->id);

			$p = 0;
      $strPriceMax = '';
      $strPriceMin = '';

			//Array setzen, um Preise sortieren zu können
			while($objPrice->next())
			{
				$p++;

				//Wenn Variante keinen Preis hat, wähle Preis vom eigentlichen Produkt
				if($objPrice->price == '') {
					$strPrice = $objTemplate->price;
				}
				else {
          $strPrice = $objPrice->price;
				}

				// Get min. price
				if(!$strPriceMin || $strPrice < $strPriceMin)
				{
					$strPriceMin = $objPrice->price;
				}

				// Get max. price
				if(!$strPriceMax || $strPrice > $strPriceMax)
				{
					$strPriceMax = $strPrice;
				}
			}

			if($p>1) {
				$multiple_values = true;

				if($strPriceMin && $strPriceMax && $strPriceMax != $strPriceMin) {
					$objTemplate->price = $strPrice;
          $objTemplate->priceMin = $strPriceMin;
          $objTemplate->priceMinFormatted = $this->Price->priceFormat($strPriceMin);
					$objTemplate->priceFormatted = $this->Price->priceFormat($strPriceMin).' - '.$this->Price->priceFormat($strPriceMax);
				}

			}
			else {
				$objTemplate->price = $strPrice;
				$objTemplate->priceFormatted = $this->Price->priceFormat($strPrice);
			}

		}

		// Product Aviable?
		if($objProduct->available) { $objTemplate->available_info = $GLOBALS['TL_DCA']['tl_product']['fields']['available']['reference'][$objProduct->available]; }

		//Contact person
		if (($objAuthor = $author) !== null) //$objProduct->getRelated('author')
		{
			if ($objAuthor->google != '')
			{
				$return['author'] = '<a href="https://plus.google.com/' . $objAuthor->google . '" rel="author" target="_blank">' . $objAuthor->name . '</a>';
			}
			else
			{
				$return['author'] = $objAuthor->name;
			}
			$objTemplate->author = $return['author'];
		}
		else { $objTemplate->author = ''; }

		// Clean the RTE output
		if ($objProduct->teaser != '')
		{
      $strTeaser = $objProduct->teaser;

			if($this->numberOfChars)
			{
        $strTeaser = \StringUtil::substr($strTeaser, $this->numberOfChars);
			}
			else
			{
				$strTeaser = \StringUtil::toHtml5($strTeaser);
				$strTeaser = \StringUtil::encodeEmail($strTeaser);
			}

      $objTemplate->teaser = $strTeaser;
		}

		$objTemplate->linkTitle = 'Produkt '.specialchars($objTemplate->title);
		//Suche
		if(\Input::get('for')) {
			$for = explode(' ',\Input::get('for'));
			foreach($for AS $val)
			{
				//$objTemplate->teaser = str_ireplace($val,'<span class="highlight">'.$val.'</span>',$objTemplate->teaser);
				//$objTemplate->teaser = strip_tags($objTemplate->teaser, '<p><strong><i><em><b>');
				$objTemplate->teaser = $this->highlight_words($objTemplate->teaser, $val);
				$objTemplate->title = $this->highlight_words($objTemplate->title, $val);
				$objTemplate->subtitle = $this->highlight_words($objTemplate->subtitle, $val);
			}
		}

		// Display the "read more" button for external/article links
		if ($objProduct->source != 'none')
		{
			$objTemplate->text = true;
		}
		// Compile the product text (tl_content)
		elseif (array_key_exists('switch_product', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['switch_product'] === true) {
			$objElement = \ContentModel::findPublishedByPidAndTable($objProduct->id, 'tl_product');
			$objTemplate->text = '';
			if ($objElement !== null)
			{
				$switch = true;
				while ($objElement->next())
				{
					$objTemplate->text .= $this->getContentElement($objElement->id);
				}
			}
		}

		// Compile the product text
		if ($objProduct->text != '' && !$switch)
		{
			$objProduct->text = \StringUtil::toHtml5($objProduct->text);
			$objTemplate->text = \StringUtil::encodeEmail($objProduct->text);
		}

    $this->fullsize = 1;
		// Add an image
    $imgSize = deserialize($this->imgSize);
    if($imgSize[0] || $imgSize[1] || $imgSize[2]) { $imgSize = $this->imgSize; }

		//Globale Einstellungen auslesen
    elseif($GLOBALS['TL_CONFIG']['sp_image_size']) { $imgSize = $GLOBALS['TL_CONFIG']['sp_image_size']; }

		//Großbild?
		if($GLOBALS['TL_CONFIG']['sp_image_fullsize']) {
      $objProduct->fullsize = 1;
		}

		//Fallback image der Kategorie wählen
		if(!$objProduct->addImage || !$objProduct->singleSRC) {
			if ($fallback_image)
			{
				$objProduct->addImage = 1;
				$objProduct->singleSRC = $fallback_image;
			}
			//Fallback image des Moduls wählen
			elseif ($this->fallback_image)
			{
				$objProduct->addImage = 1;
				$objProduct->singleSRC = $this->fallback_image;
			}
		}

		if ($objProduct->addImage && $objProduct->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($objProduct->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($objProduct->singleSRC))
				{
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303)
				$arrProduct = $objProduct->row();

				// Override the default image size
				if ($imgSize != '')
				{
					$size = deserialize($imgSize);

					if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
					{
						$arrProduct['size'] = $imgSize;
            $objTemplate->size = $imgSize;
					}
				}

				$arrProduct['singleSRC'] = $objModel->path;
				$this->addImageToTemplate($objTemplate, $arrProduct);
			}
		}

		// List all Fields / Optional
		if($this->product_fields) {
			$this->loadDataContainer('tl_product');
			$this->loadLanguageFile('tl_product');
			$arrFields = deserialize($this->product_fields);
      $arrFieldsNew = array();
			foreach($arrFields AS $field) {
        $strValue = '';
				if($objProduct->$field != '') {
					//Gewicht
					if($field == 'weight') {
						if($objTemplate->weightFormatted) {
							$strValue = $objTemplate->weightFormatted;
						}
						else {
							continue;
						}
					}
					//Felder mit Einheiten
					elseif(stristr($objProduct->$field, '"unit"')) {
						$arrValue = deserialize($objProduct->$field);
						$strValue = $arrValue['value'].' '.$arrValue['unit'];
					}
					// Selectfields and radioboxes
					elseif($GLOBALS['TL_DCA']['tl_product']['fields'][$field]['inputType'] == 'select' || $GLOBALS['TL_DCA']['tl_product']['fields'][$field]['inputType'] == 'radio') {
						$strValue = $GLOBALS['TL_DCA']['tl_product']['fields'][$field]['reference'][$objProduct->$field];
					}
					// Datetime fields
					elseif($GLOBALS['TL_DCA']['tl_product']['fields'][$field]['eval']['rgxp'] == 'datim') {
						$strValue = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objProduct->$field);
					}
					// Date fields
					elseif($GLOBALS['TL_DCA']['tl_product']['fields'][$field]['eval']['rgxp'] == 'date') {
						$strValue = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $objProduct->$field);
					}
					//Multiple Checkbox-Felder
					elseif($GLOBALS['TL_DCA']['tl_product']['fields'][$field]['inputType'] == 'checkboxWizard') {
						$arrOptions = deserialize($objProduct->$field);

						if($arrOptions) {
              $arrValue = array();
	            foreach($arrOptions AS $option) {
								$arrValue[] = $GLOBALS['TL_DCA']['tl_product']['fields'][$field]['reference'][$option];
							}
							$strValue = implode(', ', $arrValue);
						}
					}
					//Dateifelder
					elseif($GLOBALS['TL_DCA']['tl_product']['fields'][$field]['inputType'] == 'fileTree') {
            if(\Validator::isUuid($objProduct->$field)) {
							$objModel = \FilesModel::findByUuid($objProduct->$field);
	            $objFile = new \File($objModel->path, true);
							$strValue = '<img src="' . TL_ASSETS_URL . 'assets/contao/images/' . $objFile->icon . '" width="18" height="18" alt="' . $objFile->mime . '" class="mime_icon"><a href="' . $objModel->path . '">' . $objModel->name . '</a>';
						}
					}
					else {
            $strValue = $objProduct->$field;
					}

	        $arrFieldsNew[$field] = array('label' => $GLOBALS['TL_DCA']['tl_product']['fields'][$field]['label'][0], 'value' => $strValue);
				}
			}
			$objTemplate->arrFields = $arrFieldsNew;
		}

		if($objProduct->demo == 'none') { $objProduct->demo = ''; }

		$objTemplate->enclosure = array();

		// Add enclosures
		if ($objProduct->addEnclosure)
		{
			$this->addEnclosuresToTemplate($objTemplate, $objProduct->row());
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['parseProducts']) && is_array($GLOBALS['TL_HOOKS']['parseProducts']))
		{
			foreach ($GLOBALS['TL_HOOKS']['parseProducts'] as $callback)
			{
				$this->import($callback[0]);
        $this->{$callback[0]}->{$callback[1]}($objTemplate, $objProduct->row(), $this);
			}
		}

		return $objTemplate->parse();
	}


	/**
	 * Parse one or more items and return them as array
	 * @param object
	 * @param boolean
	 * @return array
	 */
	protected function parseProducts($objProducts, $blnAddArchive=false)
	{
		$limit = $objProducts->count();

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$n = 0;
		$arrProducts = array();

		while ($objProducts->next())
		{
			//Tabellendarstellung ermöglichen
			//Erstes Produkt ermitteln
			if($count == 0) { $objProducts->firstItem = true; }
			else { $objProducts->firstItem = false; }

			//Letztes Produkt ermitteln
			if($count+1 == $limit) { $objProducts->lastItem = true; }
			else { $objProducts->lastItem = false; }

			if($this->perRow > 1)
			{
				$perRow = $this->perRow;
				$class = ' row_'.(floor($count/$perRow)).' col_'.$n;
				if($count % $perRow == ($perRow-1) || $limit == $count+1) { $class .= ' col_last'; $n_new = true; }
				else { $n_new = false; }

				if($n == 0) { $class .= ' col_first'; $n++; }
				else { $n++; }
				if($n_new) { $n = 0; }
				if($count+1 == $limit && $count % $perRow != ($perRow-1)) {
				//echo $count % $perRow.'<br>';
					$divs = '';
					$max = $perRow - ($count % $perRow)-2;
					for($m=0;$m<=$max;$m++) {
						$divs .= '<div class="product_list product_item_empty'.$class.'"></div>';
					}
				}
			}
			else
			{
				$class = (($count+1 == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even');
			}

			$arrProducts[] = $this->parseProduct($objProducts, $blnAddArchive, ((++$count == 1) ? ' first' : '') . $class, $count, $divs);
			$class = '';
		}

		return $arrProducts;
	}

	/**
	 * Generate a URL and return it as string
	 * @param object
	 * @param boolean
	 * @return string
	 */
	protected function generateProductUrl($objItem, $blnAddArchive=false)
	{
		$strCacheKey = 'id_' . $objItem->id;

		// Load the URL from cache
		if (isset(self::$arrUrlCache[$strCacheKey]))
		{
			return self::$arrUrlCache[$strCacheKey];
		}

		// Initialize the cache
		self::$arrUrlCache[$strCacheKey] = null;

		switch ($objItem->source)
		{
			// Link to an external page
			case 'external':
				if (substr($objItem->url, 0, 7) == 'mailto:')
				{
					self::$arrUrlCache[$strCacheKey] = \StringUtil::encodeEmail($objItem->url);
				}
				else
				{
					self::$arrUrlCache[$strCacheKey] = ampersand($objItem->url);
				}
				break;

			// Link to an internal page
			case 'internal':
				$objArchive = \ProductArchiveModel::findPublishedByIdOrAlias($objItem->pid)->jumpTo;
				if (($objTarget = $objArchive->getRelated('jumpTo')) !== null)
				{
					self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objTarget->row()));
				}
				break;

			// Link to an article
			case 'article':
				if (($objProduct = \ArticleModel::findByPk($objItem->articleId, array('eager'=>true))) !== null && ($objPid = $objProduct->getRelated('pid')) !== null)
				{
					self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPid->row(), '/articles/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objProduct->alias != '') ? $objProduct->alias : $objProduct->id)));
				}
				break;
		}

		// Link to the default page
		if (self::$arrUrlCache[$strCacheKey] === null)
		{
			if($this->jumpTo) {
        $jumpTo = $this->jumpTo;
			}
			elseif($this->Database->tableExists('tl_product_archive_language')) {
        // Language Switch
				$jumpTo = $this->Database->prepare("SELECT jumpTo FROM tl_product_archive_language WHERE pid=? AND language=?")->limit(1)->execute($objItem->pid, $GLOBALS['TL_LANGUAGE'])->jumpTo;
			}

			if(!$jumpTo) {
				$jumpTo = \ProductArchiveModel::findPublishedById($objItem->pid)->jumpTo;
			}

			// Return if no jumpto link was defined
			if(!$jumpTo) {
				return '';
			}

			$objPage = \PageModel::findByPk($jumpTo);

			if ($objPage === null)
			{
				self::$arrUrlCache[$strCacheKey] = ampersand(\Environment::get('request'), true);
			}
			else
			{
				self::$arrUrlCache[$strCacheKey] = '/' . ampersand($this->generateFrontendUrl($objPage->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ? '/' : '/items/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objItem->alias != '') ? $objItem->alias : $objItem->id)));
			}

			// Add the current archive parameter (news archive)
			if ($blnAddArchive && \Input::get('month') != '')
			{
				self::$arrUrlCache[$strCacheKey] .= ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'month=' . \Input::get('month');
			}
		}

		return self::$arrUrlCache[$strCacheKey];
	}


	/**
	 * Generate a link and return it as string
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function generateLink($strLink, $objProduct, $blnAddArchive=false, $blnIsReadMore=false)
	{
		// Internal link
		if ($objProduct->source != 'external')
		{
			return sprintf('<a href="%s" title="%s">%s%s</a>',
							$this->generateProductUrl($objProduct, $blnAddArchive),
							specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMoreProduct'], $objProduct->title), true),
							$strLink,
							($blnIsReadMore ? ' <span class="invisible">'.$objProduct->title.'</span>' : ''));
		}

		// Encode e-mail addresses
		if (substr($objProduct->url, 0, 7) == 'mailto:')
		{
			$objProduct->url = \StringUtil::encodeEmail($objProduct->url);
		}

		// Ampersand URIs
		else
		{
			$objProduct->url = ampersand($objProduct->url);
		}

		global $objPage;

		// External link
		return sprintf('<a href="%s" title="%s"%s>%s</a>',
						$objProduct->url,
						specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['open'], $objProduct->url)),
						($objProduct->target ? (($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"') : ''),
						$strLink);
	}
}
