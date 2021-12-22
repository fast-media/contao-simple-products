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


abstract class ModuleCategory extends \Module
{

	/**
	 * URL cache array
	 * @var array
	 */
	private static $arrUrlCache = array();

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

	/**
	 * Parse an item and return it as string
	 * @param object
	 * @param boolean
	 * @param string
	 * @param integer
	 * @return string
	 */
	protected function parseCategory($objCategory, $blnAddArchive=false, $strClass='', $intCount=0, $divs='')
	{
		global $objPage;

		$objTemplate = new \FrontendTemplate($this->category_template);
		$objTemplate->setData($objCategory->row());

		$objTemplate->class = (($objCategory->cssClass != '') ? ' ' . $objCategory->cssClass : '') . $strClass;
    $objTemplate->cssID = ' id="category' . $objCategory->id.'"';
		$objTemplate->more = $this->generateLink($GLOBALS['TL_LANG']['MSC']['moreCategory'], $objCategory, $blnAddArchive, true);
		$objTemplate->link = $this->generateCategoryUrl($objCategory, $blnAddArchive);

		$objTemplate->divs = $divs;

		$objTemplate->count = $intCount;

		// Clean the RTE output
		if ($objCategory->teaser != '')
		{
			$objCategory->teaser = \StringUtil::toHtml5($objCategory->teaser);
			$objTemplate->teaser = \StringUtil::encodeEmail($objCategory->teaser);
		}

		// Add an image
		if ($objCategory->addImage && $objCategory->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($objCategory->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($objCategory->singleSRC))
				{
					$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303)
				$arrCategory = $objCategory->row();

				// Override the default image size
				if ($this->imgSize != '')
				{
					$size = deserialize($this->imgSize);

					if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
					{
						$arrCategory['size'] = $this->imgSize;
					}
				}

				$arrCategory['singleSRC'] = $objModel->path;
				$this->addImageToTemplate($objTemplate, $arrCategory);
			}
		}

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['parseCategories']) && is_array($GLOBALS['TL_HOOKS']['parseCategories']))
		{
			foreach ($GLOBALS['TL_HOOKS']['parseCategories'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objTemplate, $objCategory->row(), $this);
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
	protected function parseCategories($objCategories, $blnAddArchive=false)
	{
		$limit = $objCategories->count();

		if ($limit < 1)
		{
			return array();
		}

    $isTranslationFallback = false;
    $sp_extended = false;
		if(\Config::get('sp_languages') && in_array('simple_products_extended', $this->Config->getActiveModules())) {
			$sp_extended = true;

			$this->import('Product');
			// Load Settings for tax and language etc.
	    $objSettings = $this->Product->getSettings();

			if($objSettings->language != $GLOBALS['TL_LANGUAGE'] && $objSettings->fallback || \Config::get('sp_language') == $GLOBALS['TL_LANGUAGE'] || !$objSettings->language)
			{
      	$isTranslationFallback = true;
			}
		}

		$count = 0;
    $n = 0;
		$arrCategories = array();

		while ($objCategories->next())
		{
			//Tabellendarstellung ermöglichen
			//Erstes Produkt ermitteln
			if($count == 0) { $objCategories->firstItem = true; }
			else { $objCategories->firstItem = false; }

			//Letztes Produkt ermitteln
			if($count+1 == $limit) { $objCategories->lastItem = true; }
			else { $objCategories->lastItem = false; }

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

			//Simple Products Extended
			if($sp_extended)
			{
        $isTranslated = false;

	      // Language switch for categories
	      $this->import('Product');
				$objSettings = $this->Product->getSettings();

				// Special request fallback language
				if($objSettings->fallback_language)
				{
					$objLanguage = $this->Database->prepare("SELECT * FROM tl_product_category_language WHERE published=1 AND pid=? AND (language=? OR language=?) ORDER BY (language = ?)")->execute($objCategories->id, $GLOBALS['TL_LANGUAGE'], $objSettings->fallback_language, $objSettings->fallback_language);
				}
				// Standard request
				else
				{
        	$objLanguage = $this->Database->prepare("SELECT * FROM tl_product_category_language WHERE published=1 AND pid=? AND language=?")->limit(1)->execute($objCategories->id, $GLOBALS['TL_LANGUAGE']);
				}

		    if($objLanguage->id) {
          $isTranslated = true;

					if ($objLanguage->title != '')
					{
		        $objCategories->title = $objLanguage->title;
					}

					if ($objLanguage->subtitle != '')
					{
		        $objCategories->subtitle = $objLanguage->subtitle;
					}

					//Links
					if ($objLanguage->alias != '')
					{
		      	$objCategories->alias = $objLanguage->alias;
					}

					// Clean the RTE output
					if ($objLanguage->teaser != '')
					{
						$objCategories->teaser = $objLanguage->teaser;
					}

					if ($objLanguage->alt != '')
					{
		        $objCategories->alt = $objLanguage->alt;
					}

					if ($objLanguage->caption != '')
					{
		        $objTemplate->caption = $objLanguage->caption;
					}

					// Compile the product text
					if ($objLanguage->text != '' && !$switch) {
						$objCategories->text = $objLanguage->text;
					}

					// Change category image
					if ($objLanguage->singleSRC != '')
					{
						$objCategories->singleSRC = $objLanguage->singleSRC;
					}

				}
			}

			if($isTranslationFallback || $isTranslated || !$sp_extended) {
				$arrCategories[] = $this->parseCategory($objCategories, $blnAddArchive, ((++$count == 1) ? ' first' : '') . $class, $count, $divs);
			}
		}

		return $arrCategories;
	}

	/**
	 * Generate a URL and return it as string
	 * @param object
	 * @param boolean
	 * @return string
	 */
	protected function generateCategoryUrl($objItem, $blnAddArchive=false)
	{
		global $objPage;
		$strCacheKey = 'id_' . $objItem->id;

		// Load the URL from cache
		if (isset(self::$arrUrlCache[$strCacheKey]))
		{
			return self::$arrUrlCache[$strCacheKey];
		}

		// Initialize the cache
		self::$arrUrlCache[$strCacheKey] = null;

		// Link to the default page
		if (self::$arrUrlCache[$strCacheKey] === null)
		{
			$objJumpTo = \PageModel::findByPk($this->jumpTo);

			if ($objJumpTo === null)
			{
				$url = ampersand($this->generateFrontendUrl($objPage->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/category/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objItem->alias != '') ? $objItem->alias : $objItem->id)));
			}
			else
			{
				$url = ampersand($this->generateFrontendUrl($objJumpTo->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/category/') . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objItem->alias != '') ? $objItem->alias : $objItem->id)));
			}

      self::$arrUrlCache[$strCacheKey] = $url;

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
	protected function generateLink($strLink, $objCategory, $blnAddArchive=false, $blnIsReadMore=false)
	{
		// Internal link
		if ($objCategory->source != 'external')
		{
			return sprintf('<a href="%s" title="%s">%s%s</a>',
							$this->generateCategoryUrl($objCategory, $blnAddArchive),
							specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMoreCategory'], $objCategory->title), true),
							$strLink,
							($blnIsReadMore ? ' <span class="invisible">'.$objCategory->title.'</span>' : ''));
		}

		// Encode e-mail addresses
		if (substr($objCategory->url, 0, 7) == 'mailto:')
		{
			$objCategory->url = \StringUtil::encodeEmail($objCategory->url);
		}

		// Ampersand URIs
		else
		{
			$objCategory->url = ampersand($objCategory->url);
		}

		global $objPage;

		// External link
		return sprintf('<a href="%s" title="%s"%s>%s</a>',
						$objCategory->url,
						specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['open'], $objCategory->url)),
						($objCategory->target ? (($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"') : ''),
						$strLink);
	}
}
