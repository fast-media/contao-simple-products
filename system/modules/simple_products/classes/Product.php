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

class Product extends \Frontend
{

	/**
	 * Delete old files and generate all feeds
	 */
	public function getSettings()
	{
		$objSettings = \ProductSettingsModel::findOneByLanguage();

		// Search for other settings, if current language wasn't found
		if(!$objSettings->id) {
      $objSettings = \ProductSettingsModel::findOne();
		}

		return $objSettings;
	}

	/**
	 * Delete old files and generate all feeds
	 */
	public function generateFeeds()
	{
		$this->import('Automator');
		$this->Automator->purgeXmlFiles();
	}


	/**
	 * Add product items to the indexer
	 * @param array
	 * @param integer
	 * @param boolean
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0, $blnIsSitemap=false)
	{
    global $arrProcessed;
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->Database->getChildRecords($intRoot, 'tl_page');
		}

		$time = time();
		$arrProcessed = array();

		// Get all product archives
		$objArchive = \ProductArchiveModel::findByProtected('');

		// Walk through each archive
		if ($objArchive !== null)
		{
			while ($objArchive->next())
			{
				// Skip product archives without target page
				if (!$objArchive->jumpTo)
				{
					continue;
				}

				// Skip product archives outside the root nodes
				if (!empty($arrRoot) && !in_array($objArchive->jumpTo, $arrRoot))
				{
					continue;
				}

				// Get the URL of the jumpTo page
				$this->arrProcessed($objArchive->jumpTo);
        $strUrl = $arrProcessed[$objArchive->jumpTo];

				// Get the items
				$objArticle = \ProductModel::findPublishedDefaultByPid($objArchive->id);

				if ($objArticle !== null)
				{
					while ($objArticle->next())
					{
						$arrPages[] = $this->getLink($objArticle, $strUrl);
					}
				}

				// Check if extension 'simple_products_extended' is installed
				if (in_array('simple_products_extended', $this->Config->getActiveModules()))
				{
					$sp_extended = true;
		      $objArchiveLanguage = $this->Database->prepare("SELECT language, jumpTo FROM tl_product_archive_language WHERE pid=?")->execute($objArchive->id);

					// Walk through each language archive
					if ($objArchiveLanguage !== null)
					{
						while ($objArchiveLanguage->next())
						{
              // Get the URL of the jumpTo page
							$this->arrProcessed($objArchiveLanguage->jumpTo);
              $strUrl = $arrProcessed[$objArchiveLanguage->jumpTo];

							//Sprachen Switch für Produkte
				      $objArticleLanguage = $this->Database->prepare("SELECT t1.id, t1.alias FROM tl_product_language t1 LEFT JOIN tl_product t2 ON t1.pid = t2.id WHERE t1.published=1 AND t2.pid=? AND t1.language=?")->execute($objArchive->id, $objArchiveLanguage->language);

							if ($objArticleLanguage !== null)
							{
								while ($objArticleLanguage->next())
								{
									$arrPages[] = $this->getLink($objArticleLanguage, $strUrl);
								}
							}

						}
					}
				}
			}
		}

		return $arrPages;
	}


	function arrProcessed($jumpTo) {
		global $arrProcessed;
		if (!isset($arrProcessed[$jumpTo]))
		{
			$objParent = \PageModel::findWithDetails($jumpTo);

			// The target page does not exist
			if ($objParent === null)
			{
				return '';
			}

			// The target page has not been published (see #5520)
			if (!$objParent->published || ($objParent->start != '' && $objParent->start > $time) || ($objParent->stop != '' && $objParent->stop <= ($time + 60)))
			{
				return '';
			}

			// The target page is exempt from the sitemap (see #6418)
			if ($blnIsSitemap && $objParent->sitemap == 'map_never')
			{
				return '';
			}

			// Set the domain (see #6421)
			$domain = ($objParent->rootUseSSL ? 'https://' : 'http://') . ($objParent->domain ?: \Environment::get('host')) . TL_PATH . '/';

			// Generate the URL
			return $arrProcessed[$jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/%s' : '/items/%s'), $objParent->language);
		}
	}


	/**
	 * Return the link of a news article
	 *
	 * @param \NewsModel $objItem
	 * @param string     $strUrl
	 * @param string     $strBase
	 *
	 * @return string
	 */
	protected function getLink($objItem, $strUrl, $strBase='')
	{
		switch ($objItem->source)
		{
			// Link to an external page
			case 'external':
				return $objItem->url;
				break;

			// Link to an internal page
			case 'internal':
				if (($objTarget = $objItem->getRelated('jumpTo')) !== null)
				{
					return $strBase . $this->generateFrontendUrl($objTarget->row());
				}
				break;

			// Link to an article
			case 'article':
				if (($objArticle = \ArticleModel::findByPk($objItem->articleId, array('eager'=>true))) !== null && ($objPid = $objArticle->getRelated('pid')) !== null)
				{
					return $strBase . ampersand($this->generateFrontendUrl($objPid->row(), '/articles/' . ((!\Config::get('disableAlias') && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)));
				}
				break;
		}

		// Link to the default page
		return $strBase . sprintf($strUrl, (($objItem->alias != '' && !\Config::get('disableAlias')) ? $objItem->alias : $objItem->id));
	}
}
