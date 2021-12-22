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
 * Reads and writes product categories
 */
class ProductCategoryModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_product_category';


	/**
	 * Find published product categories by their archives
	 *
	 * @param array $arrPids An array of archives
	 * @param array $arrIds An array of categories
	 *
	 * @return \Model|null The ProductModelCategpry or null if there are no categories
	 */
	public static function findPublishedByParent($varId)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "$t.published=1";
		}

		return static::findBy($arrColumns, array($varId));
	}


	/**
	 * Find published category by ID or alias
	 *
	 * @param mixed $varId The numeric ID or alias name
	 *
	 * @return \Model|null The ProductCategoryModel or null if there is no category
	 */
	public static function findPublishedByIdOrAlias($varId)
	{
		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?)");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "$t.published=1";
		}

		return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId));
	}


	/**
	 * Find published category by title
	 *
	 * @param mixed $varId The numeric ID or alias name
	 *
	 * @return \Model|null The ProductCategoryModel or null if there is no category
	 */
	public static function findPublishedByTitle($varId)
	{
		$t = static::$strTable;
		$arrColumns = array("$t.title=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "$t.published=1";
		}

		return static::findBy($arrColumns, array($varId));
	}


	/**
	 * Find published categories by IDs
	 *
	 * @param array $arrIds An array of category IDs
	 *
	 * @return \Model|null The ProductCategoryModel or null if there is no category
	 */
	public static function findPublishedByIds($arrIds, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.id IN (" . implode(',', array_map('intval', $arrIds)) . ")");

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns[] = "$t.published=1";
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}


	/**
	 * Find published product items by their parent ID
	 *
	 * @param array   $arrPids     An array of product archive IDs
	 * @param boolean $blnFeatured If true, return only featured product, if false, return only unfeatured product
	 * @param integer $intLimit    An optional limit
	 * @param integer $intOffset   An optional offset
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no product
	 */
	public static function findPublishedByPids($pid, $arrCats, $blnFeatured=null, $intLimit=0, $intOffset=0, array $arrOptions=array())
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

		// Never return unpublished elements in the back end, so they don't end up in the RSS feed
		if (!BE_USER_LOGGED_IN || TL_MODE == 'BE')
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (is_array($arrCats) && !empty($arrCats))
		{
			$arrColumns[] = "$t.id IN (" . implode(',', array_map('intval', $arrCats)) . ")";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.sorting ASC, $t.id ASC";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		return static::findBy($arrColumns, null, $arrOptions);
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
