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


class ModuleCategoryNavigation extends \Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_navigation';


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

		$strBuffer = parent::generate();
		return ($this->Template->items != '') ? $strBuffer : '';
	}


	protected function compile()
	{
		// Jump to current page
		global $objPage, $strCategory, $strLanguage;
		$arrPage = array('id'=>$objPage->id, 'alias'=>$objPage->alias);

		// Overwrite target with jumpTo page
		if ($this->jumpTo)
		{
			$objJumpTo = $this->Database->prepare("SELECT id,alias FROM tl_page WHERE id=?")->limit(1)->execute($this->jumpTo);

			if ($objJumpTo->numRows)
			{
				$arrPage = $objJumpTo->row();
			}
		}

		// Check if extension 'simple_products_extended' is installed
		if (in_array('easy_translations', $this->Config->getActiveModules()))
		{
			$strLanguage = \Input::get('language');;
		}
		else
		{
      $strLanguage = $GLOBALS['TL_LANGUAGE'];
		}

		$c = 0;

		$objTemplate = new \FrontendTemplate($this->navigationTpl);

		$objTemplate->type = get_class($this);
		$objTemplate->level = 'level_1';

		// Set the category from the auto_item parameter
		if (!isset($_GET['category']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			//\Input::setGet('items', \Input::get('auto_item', false, true));
      $strCategory = \Input::get('auto_item', false, true);
		}
		elseif(\Input::get('category'))
		{
    	$strCategory = \Input::get('category');
		}

		// Find out category
		if($strCategory)
		{
	    $objCategory = \ProductCategoryModel::findPublishedByIdOrAlias($strCategory);

      if($this->Database->tableExists('tl_product_category_language'))
			{
				//Sprachen Switch
				if(!$objCategory->id) {
	    	  $objCategory = $this->Database->prepare("SELECT t2.* FROM tl_product_category_language t1 LEFT JOIN tl_product_category t2 ON t1.pid = t2.id WHERE t1.published=1 AND (t1.alias=? OR t1.id=?) AND t1.language=?")->execute($strCategory, $strCategory, $strLanguage);//echo $objCategory->id;exit();
				}
			}

			// Redirect category
			if($objCategory->jumpTo)
			{
				$objJumpTo = \ProductCategoryModel::findPublishedByIdOrAlias($objCategory->jumpTo);
	      $strUrl = $this->generateFrontendUrl($arrPage, ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/category/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objJumpTo->alias != '') ? $objJumpTo->alias : $objJumpTo->id));
				$this->redirect($strUrl);
				exit();
			}
		}

		// Read settings, if translation has a fallback to the original
    global $isTranslationFallback;
    $isTranslationFallback = false;
    $sp_extended = false;

		// Check if extension 'simple_products_extended' is installed
		if (in_array('simple_products_extended', $this->Config->getActiveModules()))
		{
			$sp_extended = true;
      // Language switch for categories
      $this->import('Product');
			$objSettings = $this->Product->getSettings();
			global $strLanguageFallback;
			$strLanguageFallback = $objSettings->fallback_language;

			if($objSettings->language != $strLanguage && $objSettings->fallback || \Config::get('sp_language') == $strLanguage || !$objSettings->language)
			{
      	$isTranslationFallback = true;
			}
		}

		// Backlink
		if($objCategory->id) {

		}
    // Find out product
		else {
			if(\Input::get('auto_item', false, true)) { $item_ID = \Input::get('auto_item', false, true); }
			else { $item_ID = \Input::get('items'); }

			$objProduct = $this->Database->prepare("SELECT category FROM tl_product WHERE id=? OR alias=?")->execute($item_ID, $item_ID);
			$arrCategories = deserialize($objProduct->category);
      $strCategory = $arrCategories[0];
			$objCategory = \ProductCategoryModel::findPublishedByIdOrAlias($strCategory);
		}

		if($this->changeLevel) { $pid = $objCategory->id; }
    else { $pid = 0; }
		$this->currentId = $objCategory->id;
    $this->currentPid = $objCategory->pid;

		if($objCategory->id) { $arrActiveCategories = $this->activeCategories($objCategory->id); }

		$arrCategories = array();
		$arrCategories = $this->renderNavigationTree($arrPage, $this->levelOffset+1, $pid, $arrActiveCategories);

    $this->Template->category = $objCategory->title;
    $this->Template->teaser = $objCategory->teaser;

		$objTemplate->items = $arrCategories;

		$request = ampersand($this->Environment->request, true);

		if ($request == 'index.php')
		{
			$request = '';
		}

		$this->Template->request = $request;
		$this->Template->skipId = 'skipNavigation' . $this->id;
		$this->Template->skipNavigation = specialchars($GLOBALS['TL_LANG']['MSC']['skipNavigation']);
		$this->Template->items = count($arrCategories) ? $objTemplate->parse() : '';

	}

	protected function activeCategories($strCategory)
	{
		if(!$strCategory) { return ''; }
    $arrCategory = array($strCategory);
		for($i=1;$i<25;$i++) {
    	$strCategory = $this->Database->execute("SELECT pid FROM tl_product_category WHERE id = '$strCategory' LIMIT 1")->pid;
			if($strCategory != 0) {
	      $arrCategory[] = $strCategory;
			}
			else {
				break;
			}
		}

		return $arrCategory;
	}


	protected function renderNavigationTree($arrPage, $hierarchy, $pid=0, $arrActiveCategories)
	{
    global $strCategory,$isTranslationFallback,$strLanguage;

		if($strCategory && $pid==0) {
      // Backlink
			//echo $this->backlink_jumpTo.'test';
			if($this->backlink_jumpTo)
			{
				$objJumpTo = \PageModel::findByPk($this->backlink_jumpTo);
	      $strUrl = \Controller::generateFrontendUrl($objJumpTo->row());
        $strTitle = $objJumpTo->title;
        $this->Template->backLink = '<a href="'.$strUrl.'">'.$strTitle.'</a>';

				$arrCategory = array
				(
					'class'				=> 'backLink first',
					'title'				=> specialchars($strTitle, true),
					'link'				=> $strTitle,
					'href'				=> $this->generateFrontendUrl($objJumpTo->row(), null),
					'alias'				=> $objCategories->id,
					'nofollow'		=> (strncmp($objSubpages->robots, 'noindex', 7) === 0)
				);

        $arrCategories[] = $arrCategory;
			}
		}

		$subitems = array();

		if (!BE_USER_LOGGED_IN)
		{
			$published = " AND published=1";
		}

    $objCategories = $this->Database->execute("SELECT * FROM tl_product_category WHERE pid = '$pid'".$published. ($this->product_category ? " AND id IN (" . implode(',', $this->product_category) . ")" : "")." ORDER BY sorting ASC");
		while($objCategories->next())
		{
			if(!$this->showLevel || $this->showLevel >= $hierarchy) {
				$go_on = true;
			}
			elseif($this->currentId == $pid || $this->currentPid == $pid || $pid == 0) {
				$go_on = true;
			}
			elseif($arrActiveCategories) {
				if(in_array($objCategories->id, $arrActiveCategories)) { $go_on = true; }
			}

			if($go_on) {

				$strTitle = $objCategories->title;
        $strAlias = $objCategories->alias;

        $isTranslated = false;
				// Check if extension 'simple_products_extended' is installed
				if (\Config::get('sp_languages') && in_array('simple_products_extended', $this->Config->getActiveModules()))
				{
          $sp_extended = true;
					global $strLanguageFallback;

					//Sprachen Switch
		      $objCategoryLanguage = $this->Database->prepare("SELECT * FROM tl_product_category_language WHERE published=1 AND pid=? AND (language=? OR language=?) ORDER BY (language = ?)")->limit(1)->execute($objCategories->id, $strLanguage, $strLanguageFallback, $strLanguageFallback);

					if($objCategoryLanguage->id) { $isTranslated = true; }
					if($objCategoryLanguage->title) {	$strTitle = $objCategoryLanguage->title; }
          if($objCategoryLanguage->alias) {	$strAlias = $objCategoryLanguage->alias; }
				}

        if($isTranslationFallback || $isTranslated || !$sp_extended) {//echo $strTitle.'<br>';

					if ($objCategories->id)
					{
		        ${'arrCategories'.$hierarchy} = array();
						${'arrCategories'.$hierarchy} = $this->renderNavigationTree($arrPage, $hierarchy+1, $objCategories->id, $arrActiveCategories);
						$objTemplate = new \FrontendTemplate($this->navigationTpl);
						$objTemplate->items = ${'arrCategories'.$hierarchy};
				    $objTemplate->level = 'level_'.($hierarchy+1);
						$subitems = (is_array(${'arrCategories'.$hierarchy}) && count(${'arrCategories'.$hierarchy})) ? $objTemplate->parse() : '';
					}
					else { $subitems = '';}

					//Trail Klasse ermitteln
	        $isTrail = false;
					if($arrActiveCategories) {
						//$trail = $this->Database->execute("SELECT id FROM tl_product_category WHERE pid = '$objCategories->id'".$published. ($this->product_category ? " AND id IN (" . implode(',', $this->product_category) . ")" : "")." ORDER BY sorting ASC LIMIT 1")->id;
						//print_r($arrActiveCategories);echo ' - '.$strTitle.' - '.$trail.' '.$objCategories->id.'<br><br>';
						if(in_array($objCategories->id, $arrActiveCategories)) {
		          $isTrail = true;
						}

						if($isTranslated && in_array($objCategoryLanguage->pid, $arrActiveCategories)) {
		          $isTrail = true;
						}
					}

					if($objCategories->link_title) {
	        	$strLinkTitle = $objCategories->link_title ;
					}
					else {
	          $strLinkTitle = $objCategories->title ;
					}

					$arrCategory = array
					(
						'isActive'		=> (($strCategory == $objCategories->id || $strCategory == $strAlias) ? true : false),
						'subitems'		=> $subitems,
						'class'				=> ('category' . $objCategories->id . ($objCategories->cssClass ? ' '.$objCategories->cssClass : '') . ($this->currentId == $objCategories->id ? ' active' : ($isTrail ? ' trail' : '')) . ($c==0 ? ' first' : '') . ($c+1==$objCategories->numRows ? ' last' : '')),
						'id'					=> 'category_'.$objCategories->id,
						'title'				=> specialchars($strLinkTitle, true),
						'link'				=> $strTitle,
						'href'				=> $this->generateFrontendUrl($arrPage, ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/category/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $strAlias != '') ? $strAlias : $objCategories->id)),
						'alias'				=> $objCategories->id,
            'locations'		=> $objCategories->locations,
            'hide'				=> $objCategories->hide,
						'nofollow'		=> (strncmp($objSubpages->robots, 'noindex', 7) === 0)
					);

					// add image to template
					if($objCategories->addImage && $objCategories->singleSRC != '') {
		        $objPic = \FilesModel::findByUuid($objCategories->singleSRC);
						if($objPic) {
							// Handle img size
							if($objCategories->size)
							{
								$imgSize = $objCategories->size;
							}
							else
							{
								$imgSize = $this->imgSize;
							}

							$arrImageSize = deserialize($imgSize);

              $strImage = $this->generateImage($this->getImage($objPic->path, $arrImageSize[0], $arrImageSize[1], $arrImageSize[2]), $objCategories->alt);
							$arrCategory['image'] = $strImage;
              $arrCategory['alt'] = $objCategories->alt;
              $arrCategory['caption'] = $objCategories->caption;
              $arrCategory['imageUrl'] = $objCategories->imageUrl;
						}
					}


					//Check if extension 'simple_products_extended' is installed
					if (in_array('simple_products_extended', $this->Config->getActiveModules()))
					{
						//Page Title
						if($objCategories->meta_title)
						{
	          	$arrCategory['pageTitle'] = specialchars($objCategories->meta_title, true);
						}

						//Meta Description
	          $arrCategory['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objCategories->meta_description);
					}

	        $arrCategories[] = $arrCategory;

					$c++;
    }
			}
		}

		return $arrCategories;
	}
}
