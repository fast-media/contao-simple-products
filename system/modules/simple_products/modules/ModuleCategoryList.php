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


/**
 * Class ModuleProductList
 *
 * Front end module "product list".
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    product
 */
class ModuleCategoryList extends \ModuleCategory
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_product_category_list';


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

		//prepare to show all categories if no categories are selected
		$this->product_category = deserialize($this->product_category);
		if (!is_array($this->product_category) || !count($this->product_category))
		{
			$this->product_category = '';
		}

		return parent::generate();
	}


	protected function compile()
	{
    global $objPage;
		$offset = intval($this->skipFirst);
		$limit = null;
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

		if($this->arrOptions)
		{
			$arrOptions = $this->arrOptions;
		}
		else
		{
			$arrOptions = array();
		}


		//Aktuelle Kategorie herausfinden
		$pid = 0;

		// Set the item from the auto_item parameter
		if (!isset($_GET['category']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
      $category = \Input::get('auto_item');
		}
		else {
    	$category = \Input::get('category');
		}

		// If no category was found
		if(!$category)
		{
			// Find out current requst url without queries
	    $strUrl = preg_replace('/\?.*$/', '', urldecode(\Environment::get('request')));

			// Search for current page alias
			$strUrl = strstr($strUrl, $objPage->alias);

      // Cut alias away from string
      $strUrl = str_replace(
				array($GLOBALS['TL_CONFIG']['urlSuffix'], $objPage->alias . '/'),
				'',
				$strUrl
			);

			// Redirect pages if slash is in category alias
			if(stristr($strUrl, '/')) {
				$this->redirect('');
        exit();
			}
		}

		//Simple Products Extended
		if(\Config::get('sp_languages') && in_array('simple_products_extended', $this->Config->getActiveModules())) {
      // Language switch for categories
      $this->import('Product');
			$objSettings = $this->Product->getSettings();

			// Special request fallback language
			if($objSettings->fallback_language)
			{//echo 'test'.$objSettings->fallback_language;
				$objLanguage = $this->Database->prepare("SELECT * FROM tl_product_category_language WHERE published=1 AND alias=? AND (language=? OR language=?) ORDER BY (language = ?)")->limit(1)->execute($category, $GLOBALS['TL_LANGUAGE'], $objSettings->fallback_language, $objSettings->fallback_language);//print_r($objLanguage);
			}
			// Standard request
			else
			{
	      $objLanguage = $this->Database->prepare("SELECT * FROM tl_product_category_language WHERE published=1 AND alias=? AND language=?")->execute($category, $GLOBALS['TL_LANGUAGE']);
			}

			if($objLanguage->pid) { $category = $objLanguage->pid; }
		}

    $objCategory = \ProductCategoryModel::findPublishedByIdOrAlias($category);

		// Redirect to start page, if category alias exists but no category was found
    if($category && !$objCategory->id) {
			$this->redirect('');
      exit();
		}
    elseif($objCategory->id) {
			$pid = $objCategory->id;
      // Add CSS class to the body
			if($objCategory->cssClass) {
        $strClass = $objCategory->cssClass;
			}
			else {
        $strClass = 'sp_cat'.$pid;
			}
			$objPage->cssClass = $strClass;
		}

		// Get the total number of items
		if($this->intTotal)
		{
			$intTotal = $this->intTotal;
		}
		else
		{
			$intTotal = \ProductCategoryModel::countPublishedByPids($pid, $this->product_category, $blnFeatured, $arrOptions);
		}

		if($objLanguage->id) {
			if ($objLanguage->title != '')
			{
	      $objCategory->title = $objLanguage->title;
			}

			if ($objLanguage->subtitle != '')
			{
	      $objCategory->subtitle = $objLanguage->subtitle;
			}

			//Links
			if ($objLanguage->alias != '')
			{
	    	$objCategory->alias = $objLanguage->alias;
			}

			// Clean the RTE output
			if ($objLanguage->teaser != '')
			{
				$objCategory->teaser = $objLanguage->teaser;
			}

			// Compile the product text
			if ($objLanguage->text != '' && !$switch) {
				$objCategory->text = $objLanguage->text;
			}
		}

    $this->Template->title = $objCategory->title;
    $this->Template->teaser = $objCategory->teaser;
    $this->Template->text = $objCategory->text;
    $this->Template->categoryClass = $objCategory->cssClass;

		// Translated meta page title
		if($objCategory->metatags && $objLanguage->meta_title)
		{
			$metaTitle = $objLanguage->meta_title;
		}
		// Translated category title
		elseif($objCategory->metatags && !$objLanguage->meta_title)
		{
			$metaTitle = $objLanguage->title;
		}
		// Manuelle Meta Tags aus Produkt laden
		elseif($objCategory->metatags && $objCategory->meta_title)
		{
			$metaTitle = $objCategory->meta_title;
		}
		// Automatic title from category title
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
		// Translated meta page title
		if($objCategory->metatags && $objLanguage->meta_description)
		{
			$metaDescription = $objLanguage->meta_description;
		}
		// Translated category title
		elseif($objCategory->metatags && !$objLanguage->meta_description)
		{
			$metaDescription = $metaTitle.' - '.$objLanguage->teaser;
		}
		// Manuelle Meta Tags aus Produkt laden
		elseif($objCategory->metatags && $objCategory->meta_description)
		{
			$metaDescription = $objCategory->meta_description;
		}
		//Automatische Meta Tags aus Produktdaten generieren
		elseif($objCategory->teaser != '')
		{
      $metaDescription = $metaTitle.' - '.$objCategory->teaser;
		}

		if ($metaDescription)
		{
			$objPage->description = $this->prepareMetaDescription($metaDescription);
		}

    // Overwrite the keywords
		if($objCategory->metatags && $objLanguage->meta_keywords)
		{
			$strKeywords = $objLanguage->meta_keywords;
		}
		// Manuelle Meta Tags aus Produkt laden
		elseif($objCategory->metatags && $objCategory->meta_keywords)
		{
			$strKeywords = $objCategory->meta_keywords;
		}

		if($strKeywords)
		{
			$arrKeywords = explode(',', specialchars($strKeywords));
      $strKeywords = implode(',', $arrKeywords);
			if($strKeywords)
			{
      	$GLOBALS['TL_KEYWORDS'] .= $strKeywords . (strlen($GLOBALS['TL_KEYWORDS']) ? ', ' : '');
			}
		}

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

		// Get the items
		if($this->hideElement)
		{

		}
		else
		{
			if (isset($limit))
			{
				$objCategories = \ProductCategoryModel::findPublishedByPids($pid, $this->product_category, $blnFeatured, $limit, $offset, $arrOptions);
			}
			else
			{
				$objCategories = \ProductCategoryModel::findPublishedByPids($pid, $this->product_category, $blnFeatured, 0, $offset, $arrOptions);
			}

			// No items found
			if ($objCategories === null)
			{
				$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyCategoryList'];
			}
			else
			{
				$this->Template->articles = $this->parseCategories($objCategories);
			}
		}
	}
}
