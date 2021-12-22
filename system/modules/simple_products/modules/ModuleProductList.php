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


class ModuleProductList extends \ModuleProduct
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_product_list';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD'][$this->type][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		//Urls umwandeln
    $base_url = $this->Environment->requestUri;
		if(stristr($base_url,'?')) {
			$var_url = strstr($base_url, '?');
			$base_url = strstr($base_url, '?', true);
		}

		//Falsche URLs umschreiben
    if(\Input::get('order_by') && \Input::get('order_by') != 'asc' && \Input::get('order_by') != 'desc' || stristr($var_url, '/') && !\Input::get('file')) {
			$this->redirect($base_url);
		}

    $this->product_archive = $this->sortOutProtected(deserialize($this->product_archive));

		// Return if there are no archives
		if (!is_array($this->product_archive) || empty($this->product_archive))
		{
			return '';
		}

		//prepare to show all categories if no categories are selected
		$this->product_category = deserialize($this->product_category);
		if (!is_array($this->product_category) || !count($this->product_category))
		{
			$this->product_category = '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->import('ProductPrice', 'Price');
    $this->import('Product');

		// Load Settings for tax and language etc.
    $objSettings = $this->Product->getSettings();
    //$objSettings = \ProductSettingsModel::findOne(); ALT löschen

		$offset = intval($this->skipFirst);
		$limit = null;

		$args = array();

		$this->Template->articles = array();

		// Maximum number of items
		if ($this->numberOfItems > 0)
		{
			$limit = $this->numberOfItems;
		}

		// Handle featured product
		if ($this->product_featured == 'featured')
		{
			$blnFeatured = true;
		}
		elseif ($this->product_featured == 'unfeatured')
		{
			$blnFeatured = false;
		}
		else
		{
			$blnFeatured = null;
		}

		$arrPids = $this->product_archive;
		$arrCats = $this->product_category;

		if (!is_array($arrPids) || empty($arrPids))
		{
			return 0;
		}

		$t = 'tl_product';

		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if ($blnFeatured === true)
		{
			$arrColumns[] = "$t.featured=1";
		}
		elseif ($blnFeatured === false)
		{
			$arrColumns[] = "$t.featured=''";
		}

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if(!$this->filter_ignore)
		{

			// Set the item from the auto_item parameter
			if (!isset($_GET['category']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
			{
				\Input::setGet('items', \Input::get('auto_item', false, true));
	      $category = \Input::get('auto_item', false, true);
			}
			else {
	    	$category = \Input::get('category');
			}

	    $isTranslationFallback = false;
			// Check if extension 'simple_products_extended' is installed
			if (\Config::get('sp_languages') && in_array('simple_products_extended', $this->Config->getActiveModules()))
			{
			//echo $objSettings->id.' >> product_fallback >> '.$objSettings->fallback_language;
				$sp_extended = true;
				$strLanguage = $GLOBALS['TL_LANGUAGE'];

        if($objSettings->language != $GLOBALS['TL_LANGUAGE'] && $objSettings->fallback || \Config::get('sp_language') == $GLOBALS['TL_LANGUAGE'] || !$objSettings->language)
				{
	      	$isTranslationFallback = true;
				}
				//Übersetzte Alias wieder zurückverfolgen
				if($this->Database->tableExists('tl_product_category_language')) {
					//Sprachen Switch
		      $intProductId = $this->Database->prepare("SELECT pid FROM tl_product_category_language WHERE published=1 AND alias=? AND (language=? OR language=?) ORDER BY (language = ?)")->limit(1)->execute($category, $GLOBALS['TL_LANGUAGE'], $objSettings->fallback_language, $objSettings->fallback_language)->pid;
					if($intProductId) {	$category = $intProductId; }
				}
			}

			// Try to find by category
			if(is_array($category))
			{
        $arrCats = $category;
			}
			// Set meta data for category pages
			elseif ($category && !is_array($category))
			{

				$objCategory = \ProductCategoryModel::findPublishedByIdOrAlias($category);

				if ($objCategory === null)
				{
					return null;
				}

	      global $objPage;

				// Overwrite the page title
				// Manuelle Meta Tags aus Produkt laden
				if($objCategory->metatags && $objCategory->meta_title)
				{
					$metaTitle = $objCategory->meta_title;
				}
				//Automatische Meta Tags aus Produktdaten generieren
				else {
					if ($objCategory->title != '')
					{
						$objPage->pageTitle = strip_tags(strip_insert_tags($objCategory->title));
						$metaTitle = $objCategory->title;
					}
				}

				if ($metaTitle)
				{
					$objPage->pageTitle = strip_tags(strip_insert_tags($metaTitle));
				}

				// Overwrite the page description
				// Manuelle Meta Tags aus Produkt laden
				if($objCategory->metatags && $objCategory->meta_description)
				{
					$metaDescription = $objCategory->meta_description;
				}
				//Automatische Meta Tags aus Produktdaten generieren
				elseif($objCategory->teaser != '')
				{
		      $metaDescription = $objCategory->title.' - '.$objCategory->teaser;
				}

				if ($metaDescription)
				{
					$objPage->description = $this->prepareMetaDescription($metaDescription);
				}

		    // Overwrite the keywords
				// Manuelle Meta Tags aus Produkt laden
				if($objCategory->metatags && $objCategory->meta_keywords)
				{
					if($objCategory->meta_keywords)
					{
						$arrKeywords = explode(',', specialchars($objCategory->meta_keywords));
			      $strKeywords = implode(',', $arrKeywords);
						if($strKeywords)
						{
			      	$GLOBALS['TL_KEYWORDS'] .= $strKeywords . (strlen($GLOBALS['TL_KEYWORDS']) ? ', ' : '');
						}
					}
				}

	      // Add CSS class to the body
				if($objCategory->cssClass) {
	        $strClass = $objCategory->cssClass;
				}
				else {
	        $strClass = 'sp_cat'.$pid;
				}

				$objPage->cssClass = $strClass;

				//Sortierung in dieser Kategorie ändern
				if($objCategory->sort_fields) {
					$this->sort_fields = $objCategory->sort_fields;
				}

		    //Try to find sub category
				$objSubCategories = \ProductCategoryModel::findPublishedByParent($objCategory->id);

				if ($objSubCategories && $this->deep_categories)
				{
					foreach ($objSubCategories AS $cat)
					{
	    			$arrColumnsTemp .= " OR $t.category LIKE '%\"$cat->id\"%'";
						//Try to find sub sub category
		        $objSubCategories2 = \ProductCategoryModel::findPublishedByParent($cat->id);
	          if ($objSubCategories2)
						{
							foreach($objSubCategories2 AS $cat2)
							{
			    			$arrColumnsTemp .= " OR $t.category LIKE '%\"$cat2->id\"%'";
								//Try to find sub sub category
						 	}
						}
				 	}
	        $arrColumns[] = "($t.category LIKE '%\"$objCategory->id\"%'".$arrColumnsTemp.")";
	        $arrColumnsTemp = '';
				}
				else
				{
					$arrColumns[] = "$t.category LIKE '%\"$objCategory->id\"%'";
				}
			}

			if (is_array($arrCats) && !empty($arrCats))
			{
				foreach($arrCats AS $cat)
				{
					$arrColumnsTemp .= "$t.category LIKE '%\"$cat\"%' OR ";
				}
	      $arrColumns[] = "(".substr($arrColumnsTemp,0,-4).")";
			}

			// Try to find by Tags
			if ((strlen(\Input::get('tag')) && (!$this->tag_ignore)) || (strlen($this->tag_filter)))
			{
				$arrProductIds = array();

				$relatedlist = (strlen(\Input::get('related'))) ? preg_split("/,/", \Input::get('related')) : array();
				$alltags = array_merge(array(\Input::get('tag')), $relatedlist);
				$first = true;
				if (strlen($this->tag_filter))
				{
					$headlinetags = preg_split("/,/", $this->tag_filter);
					$arrProductIds = $this->getFilterTags();
					$first = false;
				}
				else
				{
					$headlinetags = array();
				}

				foreach ($alltags as $tag)
				{
					if (strlen(trim($tag)))
					{
						if (count($arrProductIds))
						{
							$arrProductIds = $this->Database->prepare("SELECT tid FROM tl_tag WHERE from_table = ? AND tag = ? AND tid IN (" . join($arrProductIds, ",") . ")")
								->execute('tl_product', $tag)
								->fetchEach('tid');
						}
						else if ($first)
						{
							$arrProductIds = $this->Database->prepare("SELECT tid FROM tl_tag WHERE from_table = ? AND tag = ?")
								->execute('tl_product', $tag)
								->fetchEach('tid');
							$first = false;
						}
					}
				}

				//Meta Tags für Tag-Seiten
	      global $objPage;
				$tagsTitle = sprintf($GLOBALS['TL_LANG']['product_seo']['tags_meta_title'], $tag);
	      $objPage->pageTitle = $tagsTitle.' '.$objPage->pageTitle;

				$objMetaProducts = \ProductModel::findPublishedByIds($arrProductIds);
				$m = 1;
				foreach($objMetaProducts AS $product) {
					$m++;
					$arrMetaProducts[] = $product->title;
					if($m > 3) { break; }
				}

				if(count($arrMetaProducts) > 1) {
					$strMetaProducts = implode(', ', $arrMetaProducts);
	        $tagsDescription = sprintf($GLOBALS['TL_LANG']['product_seo']['tags_meta_description'], $strMetaProducts, $objPage->description);
					// Overwrite the page description
					$objPage->description = $this->prepareMetaDescription($tagsDescription);
				}

				if (count($arrProductIds))
				{
					$arrColumns = array("$t.id IN(" . implode(',', array_map('intval', $arrProductIds)) . ")");
				}
			}

			//Try to find by search

			//multiple search
			if(\Input::get('search_type') == 'mf')
			{
				$search = \Input::get('search');
				$arrSearch = explode(',',$search);

				foreach($_GET as $key => $value) {
					if(\Input::get($key)) { $arrSearch[] = $key; }
				}
			}
			elseif (\Input::get('search'))
			{
				$search_type = 'multiple';
				$search = \Input::get('search');
				$arrSearch = explode(',',$search);
			}
			//Single Search
			else {
				$search_type = 'single';
				$arrSearch = array();

				foreach($_GET as $key => $value) {
					if(\Input::get($key)) { $arrSearch[] = $key; }
				}
			}

			//Load DCA/Languages
			if($this->sort_fields || $this->show_sort || $arrSearch) {
	      $this->loadDataContainer('tl_product');
	      \System::loadLanguageFile('tl_product');
			}

			//multiple + single search
	    $replace = '';

			if ($arrSearch)
			{
        $arrOr = array();

				foreach($arrSearch AS $field)
				{
					$arrField = $GLOBALS['TL_DCA']['tl_product']['fields'][$field];
					if($arrField && $field != 'category') {
						if($search_type == 'multiple' || !\Input::get($field)) { $for = \Input::get('for'); }
						else { $for = \Input::get($field); }

						if ($for)
						{
							if(!is_array($for))
							{
								// Compare values
								if(stristr($for, '&#62;e'))	{ $compare = '>='; }
			          elseif(stristr($for, '&#62;'))	{ $compare = '>'; }
			          elseif(stristr($for, '&lt;e'))	{ $compare = '<='; }
			          elseif(stristr($for, '&lt;'))	{ $compare = '<'; }

			          $for = str_replace(
									array('&#62;', '>e', '&lt;', '&lt;e'),
									array('', '', '', ''),
									$for
								);
							}

							// ignore spaces
							if($this->search_nospace)
							{
								$arrNospaceOr[] = "REPLACE($t.$field, ' ', '') LIKE ?";
								array_push($args, '%'.$for.'%');
							}

							if(is_array($for) && count($for))
							{
								if(\Input::get($field.'_all') == 1) { $findAll = true; }
								else { $findAll = false; }

								foreach($for AS $val) {
									if($findAll) {
	                  $arrColumns[] = "$t.$field LIKE '%\"$val\"%'";
									}
									elseif($arrField['inputType'] == 'select' && !$arrField['eval']['multiple']) {
										$arrOr[] = "$t.$field = '$val'";
									}
									else {
										$arrOr[] = "$t.$field LIKE '%\"$val\"%'";//echo "$t.$field LIKE '%\"$val\"%'<br>";
									}
								}
							}
							// fulltext search
							elseif($this->search_fulltext && ($arrField['inputType'] == 'text' || $arrField['inputType'] == 'textarea'))
							{
		            $arrMatch[] = "$t.$field";
	              //$strMatchFor = '+'.$for.' '; //Irgendein Wort finden
	              $arrMatchFor = '+'.$for.' '; //Alle Wörter finden
	              $arrMatchFor = explode(' ', $for);
								$strMatchFor = '+'.implode(' +', $arrMatchFor);
								array_push($args, $strMatchFor);

							}
							//größer/kleiner Suche
							elseif($compare)
							{
								$arrColumns[] = "($t.$field $compare '$for' OR $t.$field = '')";
							}
							// Search in multiple fields (blob)
							elseif($arrField['inputType'] == 'checkboxWizard')
							{
								$arrColumns[] = "$t.$field LIKE '%\"$for\"%'";//'%\"$intContactId\"%')
							}
              // standard search with integers
							elseif($arrField['inputType'] == 'checkbox' || $arrField['inputType'] == 'select')
							{
								if($for == 'empty') {
									$arrColumns[] = "$t.$field = ''";
								}
								else {
									$arrColumns[] = "$t.$field = '$for'";
								}
							}
							// standard search
							else
							{
								$arrOr[] = "$t.$field LIKE '% ".$for."%' OR $t.$field LIKE '%>".$for."%' OR $t.$field LIKE '".$for."%'";
							}
						}

		        if($value = \Input::get($field) && !$arrOr && ! $arrMatch)
						{
		        	//$arrColumns[] = "($t.$field LIKE '% ".$value."%' OR $t.$field LIKE '".$value."%')";
						}
					}
				}

				if($arrOr)
				{
					$arrOr = implode(' OR ',$arrOr);
					$arrColumns[] = '(' . $arrOr . ')';
				}
			}
		}

		//Where-Bedingungen zusammensetzen
		if($arrColumns) {
			$strColumns = implode(' AND ',$arrColumns);
		}

		if($arrNospaceOr) {
			$strNospaceOr = implode(' OR ',$arrNospaceOr);
			$strNospaceOr = ' OR '.$strNospaceOr;
		}

		// Get the total number of items
		if($arrMatch)
		{

			//Suche nach Kategorien ermöglichen
			if(stristr(\Input::get('search'), 'category')) {
	    	$category = \Input::get('for');
				$isSearchLikeCategory = true;
				if($category) {
					$objCategory = \ProductCategoryModel::findPublishedByTitle($category, true);

          if($objCategory->id) {
						$strColumnsSpecial = "$t.category LIKE '%\"$objCategory->id\"%'";
					}
				}
			}

			$strMatch = implode(',',$arrMatch);
			$strMatch = ",MATCH ($strMatch) AGAINST (? IN BOOLEAN MODE) AS score";

			if($strColumnsSpecial) {
				$intTotal = $this->Database->prepare("
	      	SELECT COUNT(id) AS count FROM (
					(
	          SELECT tl_product.id FROM
							(
								SELECT tl_product.id".$strMatch."
								FROM tl_product
								WHERE ".$strColumns. "
							) AS tl_product
							WHERE tl_product.score > 0".$strNospaceOr."
						)
						UNION
						(
							SELECT tl_product.id
							FROM tl_product
							WHERE ".$strColumnsSpecial."
						)
					) AS t"
				)
					->execute($args)->count;
			}
			else {
	      $intTotal = $this->Database->prepare("SELECT COUNT(id) AS count FROM (SELECT tl_product.*".$strMatch." FROM tl_product WHERE ".$strColumns. ") AS tl_product WHERE score > 0".$strNospaceOr)->execute($args)->count;
			}
		}
		//Produktfilter
		elseif ($this->product_switch == 'member')
		{

	    $this->member_id = 0;
	    $this->import('FrontendUser', 'User');
			if(FE_USER_LOGGED_IN) { $this->member_id = $this->User->id; }

			$intTotal = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_product LEFT JOIN tl_product_cart_item t2 ON tl_product.id = t2.product_id LEFT JOIN tl_product_cart t3 ON t2.pid = t3.id WHERE ".$strColumns . " AND t3.pid = '$this->member_id' AND t3.status != 'cart' AND t3.status != 'watchlist' AND t3.status != 'roundup'")->execute()->count;
		}
		elseif($sp_extended && $isTranslationFallback == false && $objSettings->fallback_language) {
    	$intTotal = $this->Database->execute("SELECT COUNT(*) AS count FROM tl_product LEFT JOIN tl_product_language t2 ON tl_product.id = t2.pid WHERE t2.published = 1 AND (t2.language = '$strLanguage' OR t2.language = '$objSettings->fallback_language') AND t2.id != 0 AND " . $strColumns . " ORDER BY (language = '$objSettings->fallback_language')")->count;
		}
		elseif($sp_extended && $isTranslationFallback == false) {
    	$intTotal = $this->Database->execute("SELECT COUNT(*) AS count FROM tl_product LEFT JOIN tl_product_language t2 ON tl_product.id = t2.pid WHERE t2.published = 1 AND t2.language = '$strLanguage' AND t2.id != 0 AND " . $strColumns)->count;
		}
		else
		{//echo "SELECT COUNT(*) AS count FROM tl_product WHERE ".$strColumns;
      $intTotal = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_product WHERE ".$strColumns)->execute()->count;
		}

		if($objCategory)
		{
      if($objCategory->headline)
      {
        $this->Template->category = $objCategory->headline;
      }
      else
      {
        $this->Template->category = $objCategory->title;
      }

			$this->Template->teaser = $objCategory->teaser;
      $this->Template->text = $objCategory->text;

			// Compile the product text (tl_content)
			if (array_key_exists('sp_switch_category', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['sp_switch_category'] === true) {
				$objElement = \ContentModel::findPublishedByPidAndTable($objCategory->id, 'tl_product_category');
				$objTemplate->text = '';
				if ($objElement !== null)
				{
					$switch = true;
					while ($objElement->next())
					{
						$this->Template->text .= $this->getContentElement($objElement->id);
					}
				}
			}

			if ($objCategory->addImage && $objCategory->singleSRC != '')
			{
				$objModel = \FilesModel::findByUuid($objCategory->singleSRC);

				if (is_file(TL_ROOT . '/' . $objModel->path))
				{
					$arrCategory = $objCategory->row();
          $this->Template->addImage = true;

					// Override the default image size
					if ($objCategory->size != '')
					{
						$size = deserialize($objCategory->size);

						if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
						{
							$arrCategory['size'] = $objCategory->size;
						}
					}

					$arrCategory['singleSRC'] = $objModel->path;
					$this->addImageToTemplate($this->Template, $arrCategory);
				}
			}

			if($this->Database->tableExists('tl_product_category_language')) {
				//Sprachen Switch
	      $objCategoryLanguage = $this->Database->prepare("SELECT * FROM tl_product_category_language WHERE pid=? AND language=?")->execute($objCategory->id, $GLOBALS['TL_LANGUAGE']);
				if($objCategoryLanguage->title) {	$this->Template->category = $objCategoryLanguage->title; }
        if($objCategoryLanguage->alias) {	$this->Template->teaser = $objCategoryLanguage->teaser; }
			}
		}

		//Produkte nicht anzeigen, wenn Unterkategorien, aber keine Produkte existieren
		if ($intTotal < 1 && $objSubCategories && !$this->deep_categories)
		{
      //$this->Template->classes = ' invisible'; //Temporär
			//return null;
		}

		if ($intTotal < 1)
		{
			$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyProductList'];
			return;
		}

    $this->Template->show_count = $this->show_count;
		$this->Template->count = $intTotal;
		$total = $intTotal - $offset;

		// Split the results
		if ($this->perPage > 0 && (!isset($limit) || $this->numberOfItems > $this->perPage))
		{
			// Adjust the overall limit
			if (isset($limit))
			{
				$total = min($limit, $total);
			}

			// Get the current page
			$id = 'page_n' . $this->id;
			$page = \Input::get($id) ?: 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
			{
				global $objPage;
				$objPage->noSearch = 1;
				$objPage->cache = 0;

				// Send a 404 header
				header('HTTP/1.1 404 Not Found');
				return;
			}

			// Set limit and offset
			$limit = $this->perPage;
			$offset += (max($page, 1) - 1) * $this->perPage;

			// Overall limit
			if ($offset + $limit > $total)
			{
				$limit = $total - $offset;
			}

			// Add the pagination menu
			$objPagination = new \Pagination($total, $this->perPage, $GLOBALS['TL_CONFIG']['maxPaginationLinks'], $id);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		//Urls umwandeln
    $base_url = $this->Environment->requestUri;
		if(stristr($base_url,'?')) {
			$var_url = strstr($base_url, '?');
			$base_url = strstr($base_url, '?', true);
		}

		//URL anpassen - Ansicht
		$suchmuster = array('/[&|?]view=[a-z0-9-_]*/i', '/\?/i');
		$ersetzung = array('', '&');
		$url = preg_replace($suchmuster, $ersetzung, $var_url);

		if(stristr($url,'?')) { $url = $url.'&'; } else { $url = '?'.substr($url,1).'&'; }

		//Ansicht
		if($this->perRow > 1) {
	    $view = \Input::get('view');
			if(!$view) { $view = 'gal';}
			$this->Template->view = $view;
			if($view) { $this->Template->classes .= ' view_'.$view; }
		}
		if($this->show_view && $this->perRow > 1) {

      $arrLabel = $GLOBALS['TL_LANG']['product_list']['list_view']['gallery'];
			if($view == 'gal') { $gal_active = ' active'; }
	    else { $gal_title = ' title="'.$arrLabel[1].'"'; }
      $view_url = $base_url.$url.'view=gal';
	    $list_view = '<a class="view_gal'.$gal_active.'" href="'.$view_url.'"'.$gal_title.'>'.$arrLabel[0].'</a> ';

      $arrLabel = $GLOBALS['TL_LANG']['product_list']['list_view']['list'];
	    if($view == 'list') { $list_active = ' active'; }
			else { $list_title = ' title="'.$arrLabel[1].'"'; }
			$view_url = $base_url.$url.'view=list';

	    $list_view .= '<a class="view_list'.$list_active.'" href="'.$view_url.'"'.$list_title.'>'.$arrLabel[0].'</a> ';

			$this->Template->view_label = $GLOBALS['TL_LANG']['product_list']['list_view']['view'];
	    $this->Template->list_view = $list_view;
		}

		//Sortieren

		//Standard Sortierung
		$order_by = ' ASC';
		if(\Input::get('order_by') == 'desc') { $order_by = ' DESC'; }
		$strOrder = 'tl_product.sorting'.$order_by;

    $arrSortFields = deserialize($this->sort_fields);

		//get current sort value
		if($sort = \Input::get('sort'))
		{
			$order_by = ' ASC';
			if(\Input::get('order_by') == 'desc') { $order_by = ' DESC'; }
			$strOrder = 'tl_product.'.$sort.$order_by;

			if($GLOBALS['TL_DCA']['tl_product']['fields'][$sort]['eval']['rgxp'] == 'digit')
			{
  	    $strOrder = '(tl_product.'.$sort.' IS NULL), tl_product.'.$sort.$order_by;
			}
			elseif($sort == 'location')
			{
				$strOrder = '(SELECT title FROM tl_location WHERE tl_product.location = tl_location.id)' . $order_by;
			}
			else
			{
        $strOrder = 'tl_product.'.$sort.$order_by;
			}

		}
		//get default search from module
		elseif ($arrSortFields)
		{
			foreach($arrSortFields AS $var)
			{
        $field = strstr($var, ' ', true);
				if($var == 'rand')
				{
					$sort_new .= 'rand(),';
				}
				elseif(isset($GLOBALS['TL_DCA']['tl_product']['fields'][$field]['label']))
				{
					if($GLOBALS['TL_DCA']['tl_product']['fields'][$field]['eval']['rgxp'] == 'digit') {
      	    $sort_new .= '(tl_product.'.$field.' IS NULL), tl_product.'.$var.',';
					}
					else {
            $sort_new .= 'tl_product.'.$var.',';
					}
				}
			}

      $sort_new = substr($sort_new,0,-1);
			$strOrder = $sort_new;
			$strDefaultSorting = $var;
		}

    //Sortierungsfelder im Frontend zeigen
    $arrShowSort = deserialize($this->show_sort);
		if (!empty($arrShowSort) && is_array($arrShowSort))
		{
			$this->Template->sort_label = $GLOBALS['TL_LANG']['product_list']['list_sort']['sort'];

			$strShowSort = implode(',',$arrShowSort);

			//Felder umbenennen
      $GLOBALS['TL_DCA']['tl_product']['fields']['sorting']['label'][0] = $GLOBALS['TL_LANG']['product_list']['list_sort']['sorting'];

			//URL anpassen
			$suchmuster = array('/[&|?]sort=[a-z0-9-_]*/i', '/[&|?]order_by=[a-z0-9-_]*/i', '/\?/i');
			$ersetzung = array('','','&');
			$url = preg_replace($suchmuster, $ersetzung, $var_url);
      if(stristr($url,'?')) { $url = $url.'&'; } else { $url = '?'.substr($url,1).'&'; }

			foreach ($arrShowSort as $var)
			{
        $isSelected = false;
        $field = strstr($var, ' ', true);
        $sort = substr(strstr($var, ' '),1);
				$value = $base_url.$url.'sort='.$field.'&order_by='.$sort;

				//"aufsteigend" und "absteigend" nicht anzeigen, wenn Feld nur 1 mal vorkommt
        if(substr_count(','.$strShowSort, ','.$field) > 1) { $show_order = true; }
				else  { $show_order = false; }

				if(strlen($label = $GLOBALS['TL_DCA']['tl_product']['fields'][$field]['label'][0])) {

				if($field == \Input::get('sort') && \Input::get('order_by') == $sort || !$strDefaultSorting && $field == 'sorting' && !\Input::get('sort') || $strDefaultSorting == $var && !\Input::get('sort')) {
					$isSelected = true;
				}

					$strOptions .= '  <option value="' . $value . '"' . ($isSelected ? ' selected="selected" class="selected"' : '') . '>' . $label.($show_order ? ': '.$GLOBALS['TL_LANG']['product']['search'][$sort] : '') . '</option>' . "\n";
				}
			}
		}

		$this->Template->sort_class = 'select sort sort_'.\Input::get('sort');
		$this->Template->show_sort = $strOptions;

		if($this->perRow > 1) { $this->Template->classes .= ' pl'.$this->perRow; }

		// per Page
		if(stristr($this->show_limit, ',')) {
			$arrShowLimit = explode(',', $this->show_limit);

			if(isset($arrShowLimit)) {
				foreach($arrShowLimit AS $value) {
	        $perPage_fields .= '<option value="' . $value . '"' . ($value == \Input::get('per_page') ? ' selected="selected" class="selected"' : '') . '>' . $value . '</option>' . "\n";
				}

	      $this->Template->sort_class = 'select per_page per_page_'.\Input::get('per_page');
	      $this->Template->show_limit = $perPage_fields;
	      $this->Template->per_page_label = specialchars($GLOBALS['TL_LANG']['MSC']['list_perPage']);
			}
		}

		// Get the items
		if($this->hideElement)
		{

		}
		else
		{
			if($strMatch) { $strOrder = "score DESC, ".$strOrder; }

			if($arrMatch)
			{
				$args2 = array_merge(array($strMatchFor),$args);

				if($strColumnsSpecial) {

					$objProducts = $this->Database->prepare("
		      	SELECT tl_product.* FROM (
						(
		          SELECT tl_product.* FROM
								(
									SELECT tl_product.*".$strMatch."
									FROM tl_product
									WHERE ".$strColumns. "
								) AS tl_product
								WHERE tl_product.score > 0".$strNospaceOr ."
							)
							UNION
							(
								SELECT tl_product.*, 1 AS score
								FROM tl_product
								WHERE ".$strColumnsSpecial."
							)
						) AS tl_product"
					)
						->execute($args2);
						//print_r($objProducts->query);
				}
				else {
		    	$objProducts = $this->Database->prepare("SELECT tl_product.*".$strMatch." FROM (SELECT tl_product.*".$strMatch." FROM tl_product WHERE ".$strColumns. ") AS tl_product WHERE score > 0".$strNospaceOr . ($strOrder ? ' ORDER BY '.$strOrder : '') . ($limit ? ' LIMIT '.$offset.','.$limit : ''))->execute($args2);
				}
			}
			//Produktfilter
			elseif ($this->product_switch == 'member')
			{

		    $this->member_id = 0;
		    $this->import('FrontendUser', 'User');
				if(FE_USER_LOGGED_IN) { $this->member_id = $this->User->id; }

				$objProducts = $this->Database->execute("SELECT tl_product.*, t3.date AS cart_date, t2.amount AS cart_amount, t2.price AS cart_price FROM tl_product LEFT JOIN tl_product_cart_item t2 ON tl_product.id = t2.product_id LEFT JOIN tl_product_cart t3 ON t2.pid = t3.id WHERE ".$strColumns . " AND t3.pid = '$this->member_id'" . ($strOrder ? ' ORDER BY '.$strOrder : '') . ($limit ? ' LIMIT '.$offset.','.$limit : ''));
			}
			elseif($sp_extended && $objSettings->fallback_language && $isTranslationFallback == false) {
				$objProducts = $this->Database->execute("SELECT tl_product.* FROM tl_product LEFT JOIN tl_product_language t2 ON tl_product.id = t2.pid WHERE t2.published = 1 AND (t2.language = '$strLanguage' OR t2.language = '$objSettings->fallback_language') AND t2.id != 0 AND " . $strColumns . " GROUP BY tl_product.id ORDER BY (language = '$objSettings->fallback_language')" . ($limit ? ' LIMIT '.$offset.','.$limit : ''));
			}
			elseif($sp_extended && $isTranslationFallback == false) {
	    	$objProducts = $this->Database->execute("SELECT tl_product.* FROM tl_product LEFT JOIN tl_product_language t2 ON tl_product.id = t2.pid WHERE t2.published = 1 AND t2.language = '$strLanguage' AND t2.id != 0 AND ".$strColumns . ($strOrder ? ' ORDER BY '.$strOrder : '') . ($limit ? ' LIMIT '.$offset.','.$limit : ''));
			}
			else {
	    	$objProducts = $this->Database->execute("SELECT * FROM tl_product WHERE ".$strColumns . ($strOrder ? ' ORDER BY '.$strOrder : '') . ($limit ? ' LIMIT '.$offset.','.$limit : ''));
			}

			// No items found
			if ($objProducts === null)
			{
				$this->Template = new \FrontendTemplate('mod_product_empty');
				$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyProductList'];
			}
			else
			{
				// Inject Javascript if multiple watchlists activated
				if($GLOBALS['TL_CONFIG']['sp_muliple_watchlist'] && $this->booking_watchlist)
				{
					$GLOBALS['TL_HEAD'][] = "
					<script>
						function showWatchlistBox(id) {
							document.getElementById('watchlistBox' + id).style.display = 'block';
              document.getElementById('watchlistBox' + id).className = 'watchlistBox active';
						}
					</script>";
				}

				if($objSettings->show_tax)
				{
					$tax = $objSettings->tax;

					// Reverse charge info - for business customers from foreign european countries (only digital products)
					if($this->User->account_type == 'business' && $this->User->uid && $objSettings->digital_products && $this->User->country && $this->User->country != $objSettings->country)
					{
						$this->Template->priceInfo = $GLOBALS['TL_LANG']['product_info']['tax_list_reverse_charge'];
					}
					// Gross price info
					elseif($objSettings->gross && $tax > 0)
					{
						$this->Template->priceInfo = $GLOBALS['TL_LANG']['product_info']['tax_list_gross'];
					}
          // Default price info
					elseif($tax > 0)
					{
						$this->Template->priceInfo = $GLOBALS['TL_LANG']['product_info']['tax_list'];
					}
          // Small business info
					else
					{
						$this->Template->priceInfo = $GLOBALS['TL_LANG']['product_info']['tax_list_small_business'];
					}
				}

				$this->Template->articles = $this->parseProducts($objProducts);
			}
		}

		$this->Template->archives = $this->product_archive;
    $this->Template->search_for = \Input::get('for');

	}
}
