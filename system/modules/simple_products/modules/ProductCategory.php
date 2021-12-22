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


class ProductCategory extends \Module
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

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['productcategories'][0]) . ' ###';
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
		global $objPage;
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

		$c = 0;



		$objTemplate = new FrontendTemplate($this->navigationTpl);

		$objTemplate->type = get_class($this);
		$objTemplate->level = 'level_0';

		//Kategorie herausfinden
    $category = \ProductCategoryModel::findPublishedByIdOrAlias(\Input::get('category'));
    $pid = $category->id;

		$arrCategories = array();
		$arrCategories = $this->renderNavigationTree($arrPage,$this->levelOffset,$pid);

    $this->Template->category = $category->title;
    $this->Template->teaser = $category->teaser;

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

	protected function renderNavigationTree($arrPage,$hierarchy,$pid=0) {
$subitems = array();

		if (!BE_USER_LOGGED_IN)
		{
			$published = " AND published=1";
		}

    $objCategories = $this->Database->execute("SELECT * FROM tl_product_category WHERE pid = '$pid'".$published. ($this->product_category ? " AND id IN (" . implode(',', $this->product_category) . ")" : "")." ORDER BY sorting ASC");

		while( $objCategories->next() )
		{
			if ($objCategories->id && $this->showLevel > $hierarchy) {
        ${'arrCategories'.$hierarchy} = array();
				${'arrCategories'.$hierarchy} = $this->renderNavigationTree($arrPage,$hierarchy+1,$objCategories->id);

				$objTemplate = new FrontendTemplate($this->navigationTpl);
				$objTemplate->items = ${'arrCategories'.$hierarchy};
		    $objTemplate->level = 'level_'.$hierarchy;
				$subitems = count(${'arrCategories'.$hierarchy}) ? $objTemplate->parse() : '';
			}
			else { $subitems = '';}
			//print_r($subitems);
			$arrCategories[] = array
			(
				'isActive'		=> ((\Input::get('category') == $objCategories->id || \Input::get('category') == $objCategories->alias || $blnNews) ? true : false),
				'subitems'		=> $subitems,
				'class'			=> ('category category_'.$objCategories->alias. ($c==0 ? ' first' : '') . ($c+1==$objCategories->numRows ? ' last' : '')),
				'id'          => 'category_'.$objCategories->id,
				'pageTitle'		=> specialchars($objCategories->title),
				'title'			=> specialchars($objCategories->title),
				'link'			=> $objCategories->title,
				'href'			=> $this->generateFrontendUrl($arrPage, '/category/'.(strlen($objCategories->alias) ? $objCategories->alias : $objCategories->id)),
				'alias'			=> $objCategories->id,
				'nofollow'		=> (strncmp($objSubpages->robots, 'noindex', 7) === 0),
				'target'		=> '',
				'description'	=> '',
				'accesskey'		=> '',
				'tabindex'		=> '',
			);

			$c++;
		}

		return $arrCategories;
		//print_r($this->Template->items);

	}

}
