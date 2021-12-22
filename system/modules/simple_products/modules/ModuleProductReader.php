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

use Contao\CoreBundle\Exception\PageNotFoundException;
use Patchwork\Utf8;

class ModuleProductReader extends \ModuleProduct
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_product_reader';


	/**
	 * Add description to Meta-Tag <description>
	 * @param string
	 * @return void
	 */
	protected function addDescription($strDescription)
	{
		global $objPage;
		$objPage->description = $strDescription;
		return;
	}


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD'][$this->type][0]) . ' ###';
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
    if(\Input::get('order_by') && \Input::get('order_by') != 'asc' && \Input::get('order_by') != 'desc' || (!stristr($var_url, 'file=') && stristr($var_url, '/'))) {
			$this->redirect($base_url);
		}

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
		}

		// Do not index or cache the page if no product item has been specified
		if (!\Input::get('items'))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

    $this->product_archive = $this->sortOutProtected(deserialize($this->product_archive));

		// Do not index or cache the page if there are no archives
		if (!is_array($this->product_archive) || empty($this->product_archive))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		//prepare to show all categories if no categories are selected
		$this->product_category = deserialize($this->product_category);
		if (!is_array($this->product_category) || !count($this->product_category))
		{
			$this->product_category = '';
		}

		// Get the product item
		$objProduct = \ProductModel::findPublishedByParentAndIdOrAlias(\Input::get('items'), $this->product_archive);
//print_r($objProduct);
		$listitems = deserialize($objProduct->listitems);
		if($listitems) {
			foreach($listitems AS $item) {
				if ($item) { $items .= $item; }
			}
		}
		if ($this->product_template == 'product_features' && !isset($items) && $this->demo != 'none') { return ''; }

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		$this->Template->articles = '';
		
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		$productId = \Input::get('items');
		//Simple Products Extended
		if($this->Database->tableExists('tl_product_language'))
		{
			// Language switch for products

      $this->import('Product');
			$objSettings = $this->Product->getSettings();

			// Special request fallback language
			if($objSettings->fallback_language)
			{
				$objProductLanguage = $this->Database->prepare("SELECT pid, title, subtitle, teaser FROM tl_product_language WHERE published=1 AND alias=? AND (language=? OR language=?) ORDER BY (language = ?)")->limit(1)->execute(\Input::get('items'), $GLOBALS['TL_LANGUAGE'], $objSettings->fallback_language, $objSettings->fallback_language);
			}
			// Standard request
			else
			{
				$objProductLanguage = $this->Database->prepare("SELECT pid, title, subtitle, teaser FROM tl_product_language WHERE published=1 AND alias=? AND language=?")->execute(\Input::get('items'), $GLOBALS['TL_LANGUAGE']);
			}

			if($objProductLanguage->pid) { $productId = $objProductLanguage->pid; }
		}

		// Get the product item
		$objProduct = \ProductModel::findPublishedByParentAndIdOrAlias($productId, $this->product_archive);

		if (null === $objProduct)
		{
      //header('HTTP/1.1 404 Not Found');
      throw new PageNotFoundException('Page not found: ' . \Environment::get('uri'));

			//$this->Template->notFound = true;
      //$this->Template->headline = 'Produkt nicht gefunden';
      //$this->Template->articles = 'Produkt nicht gefunden';
      return '';
		}

    if ($objProduct->demo == 'none') { $objProduct->demo = ''; }
    elseif ($objProduct->demo == 'internal') {
      if(($jumpTo = \PageModel::findByPk($objProduct->demo_jumpTo)) !== null) {
				$strUrl = \Controller::generateFrontendUrl($jumpTo->row());
			}
			else {
				$strUrl = '';
			}
		  $objProduct->demo_url = $strUrl;
		}

		$arrProduct = $this->parseProduct($objProduct);
		$this->Template->articles = $arrProduct;

		//Simple Products Extended
		if($this->Database->tableExists('tl_product_language') && $objProductLanguage->pid) {
			//Sprachen Switch
			if($objProductLanguage->title) { $objProduct->title = $objProductLanguage->title; }
			if($objProductLanguage->subtitle) { $objProduct->subtitle = $objProductLanguage->subtitle; }
      if($objProductLanguage->teaser) { $objProduct->teaser = $objProductLanguage->teaser; }
		}

		// Add CSS class to the body + get last category for page title + get link for category
		$categories = deserialize($objProduct->category);
    $strTitle = '';

		if($categories)
		{
			foreach($categories AS $category)
			{
        $objCategory = \ProductCategoryModel::findPublishedByIdOrAlias($category);

        $strMetaTitle = $objCategory->title;

				if($objCategory->cssClass) {
					$strClass[] = $objCategory->cssClass;
				}
				else {
	        $strClass[] = 'sp_cat'.$objCategory->id;
				}
			}

			//print_r($category_alias);
			$strClass = implode(' ',$strClass);
			$objPage->cssClass = $strClass;
		}

		if(stristr($this->getReferer(), 'search') || !$this->category_jumpTo) {
			$strUrl = 'javascript:history.go(-1)';
		}
		elseif($this->category_jumpTo)
		{
			$objJumpTo = \PageModel::findByPk($this->category_jumpTo);
			$strUrl = $objJumpTo->row();

      $strUrl = ampersand($this->generateFrontendUrl($strUrl, ($GLOBALS['TL_CONFIG']['useAutoItem'] ?	'/' : '/category/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objCategory->alias != '') ? $objCategory->alias : $objCategory->id)));
		}

		$this->Template->referer = $strUrl;

		// Overwrite the page title
		// Manuelle Meta Tags aus Produkt laden
		if($objProduct->metatags && $objProduct->meta_title)
		{
			$strMetaTitle = $objProduct->meta_title;
		}
		//Automatische Meta Tags aus Produktdaten generieren
		else
		{
			if ($objProduct->title != '')
			{
				if($strMetaTitle)
				{
          $strMetaTitle = $objProduct->title . ' - ' . $strMetaTitle;
				}
				else
				{
					$strMetaTitle = $objProduct->title;
				}
			}

			if ($objProduct->subtitle != '')
			{
				$strMetaTitle .= ' - '.$objProduct->subtitle;
			}
		}

		if ($strMetaTitle)
		{
			$objPage->pageTitle = strip_tags(strip_insert_tags($strMetaTitle));
		}

		// Overwrite the page description
		// Manuelle Meta Tags aus Produkt laden
		if($objProduct->metatags && $objProduct->meta_description)
		{
			$metaDescription = $objProduct->meta_description;
		}
		//Automatische Meta Tags aus Produktdaten generieren
		elseif($objProduct->teaser != '')
		{
      $metaDescription = $strMetaTitle.' - '.$objProduct->teaser;
		}

		if ($metaDescription)
		{
			$objPage->description = $this->prepareMetaDescription($metaDescription);
		}

    // Overwrite the keywords
		// Manuelle Meta Tags aus Produkt laden
		if($objProduct->metatags && $objProduct->meta_keywords)
		{
			if($objProduct->meta_keywords)
			{
				$arrKeywords = explode(',', specialchars($objProduct->meta_keywords));
	      $strKeywords = implode(',', $arrKeywords);
				if($strKeywords)
				{
	      	$GLOBALS['TL_KEYWORDS'] .= $strKeywords . (strlen($GLOBALS['TL_KEYWORDS']) ? ', ' : '');
				}
			}
		}

		// Inject Javascript if multiple watchlists activated
		if($GLOBALS['TL_CONFIG']['sp_muliple_watchlist'] && $this->booking_watchlist)
		{
			$GLOBALS['TL_HEAD'][] = "
			<script>
				function showWatchlistBox(id) {
					document.getElementById('watchlistBox' + id).style.display = 'block';
				}
			</script>";
		}

		// HOOK: comments extension required
		if ($objProduct->noComments || !in_array('comments', $this->Config->getActiveModules()))
		{
			$this->Template->allowComments = false;
			return;
		}

		$objArchive = $objProduct->getRelated('pid');
		$this->Template->allowComments = $objArchive->allowComments;

		// Comments are not allowed
		if (!$objArchive->allowComments)
		{
			return;
		}

		// Adjust the comments headline level
		$intHl = min(intval(str_replace('h', '', $this->hl)), 5);
		$this->Template->hlc = 'h' . ($intHl + 1);

		$this->import('Comments');
		$arrNotifies = array();

		// Notify the system administrator
		if ($objArchive->notify != 'notify_author')
		{
			$arrNotifies[] = $GLOBALS['TL_ADMIN_EMAIL'];
		}

		// Notify the author
		if ($objArchive->notify != 'notify_admin')
		{
			if (($objAuthor = $objProduct->getRelated('author')) !== null && $objAuthor->email != '')
			{
				$arrNotifies[] = $objAuthor->email;
			}
		}

		$objConfig = new \stdClass();

		$objConfig->perPage = $objArchive->perPage;
		$objConfig->order = $objArchive->sortOrder;
		$objConfig->template = $this->com_template;
		$objConfig->requireLogin = $objArchive->requireLogin;
		$objConfig->disableCaptcha = $objArchive->disableCaptcha;
		$objConfig->bbcode = $objArchive->bbcode;
		$objConfig->moderate = $objArchive->moderate;

		$this->Comments->addCommentsToTemplate($this->Template, $objConfig, 'tl_product', $objProduct->id, $arrNotifies);
	}
}
