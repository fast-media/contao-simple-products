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
 * Reads and writes product types
 */
class ProductSettingsModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_product_settings';


	/**
	 * Find current settings
	 *
	 * @param mixed $varId The numeric ID or alias name
	 *
	 * @return \Model|null The ProductCategoryModel or null if there is no category
	 */
	public static function findOne(array $arrOptions=array())
	{
		$t = static::$strTable;

    \Controller::loadDataContainer('tl_product_settings');

		if ($GLOBALS['TL_DCA']['tl_product_settings']['fields']['start'])
		{
			$time = time();
			$arrColumns = array("($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time)");
		}

		return static::findOneBy($arrColumns, array(), $arrOptions);
	}


	/**
	 * Find current settings by language
	 *
	 * @param mixed $varId The numeric ID or alias name
	 *
	 * @return \Model|null The ProductCategoryModel or null if there is no category
	 */
	public static function findOneByLanguage(array $arrOptions=array())
	{
		$t = static::$strTable;

    \Controller::loadDataContainer('tl_product_settings');

    $arrColumns = array("$t.id != 0");

		if ($GLOBALS['TL_DCA']['tl_product_settings']['fields']['language'])
		{
			$strLanguage = $GLOBALS['TL_LANGUAGE'];
			$arrColumns[] = "$t.language='$strLanguage'";
		}

		if ($GLOBALS['TL_DCA']['tl_product_settings']['fields']['start'])
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time)";
		}

		return static::findOneBy($arrColumns, array(), $arrOptions);
	}


	/**
	 * Count published product items by their parent ID
	 *
	 * @param array   $arrPids     An array of product archive IDs
	 * @param boolean $blnFeatured If true, return only featured product, if false, return only unfeatured product
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return integer The number of product items
	 */
	public static function countPublishedByPids($pid, $arrCats, $blnFeatured=null, array $arrOptions=array())
	{
		$t = static::$strTable;

    $arrColumns = array("$t.pid = '$pid'");

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

		if (is_array($arrCats) && !empty($arrCats))
		{
			$arrColumns = array("$t.id IN (" . implode(',', array_map('intval', $arrCats)) . ")");
		}

		return static::countBy($arrColumns, null, $arrOptions);
	}
}
