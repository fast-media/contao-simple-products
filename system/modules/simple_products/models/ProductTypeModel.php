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
class ProductTypeModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_product_type';

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

		return static::findBy($arrColumns, array($varId));
	}


	/**
	 * Find published product items by their IDs
	 *
	 * @param array   $arrIds     An array of news archive IDs
	 * @param boolean $blnFeatured If true, return only featured news, if false, return only unfeatured news
	 * @param integer $intLimit    An optional limit
	 * @param integer $intOffset   An optional offset
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no news
	 */
	public static function findPublishedByIds($arrIds=array(), $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		$t = static::$strTable;
		if($arrIds) {
			$arrColumns = array("($t.id = ".implode(' OR '.$t.'.id = ',array_map('intval', $arrIds)).")");
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.title DESC";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		return static::findBy($arrColumns, null, $arrOptions);
	}


	/**
	 * Count published news items by their parent ID
	 *
	 * @param array   $arrIds     An array of news archive IDs
	 * @param boolean $blnFeatured If true, return only featured news, if false, return only unfeatured news
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return integer The number of news items
	 */
	public static function countPublishedByPids($arrIds, $currentCategory=null, $blnFeatured=null, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return 0;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrIds)) . ")");

		if ($blnFeatured === true)
		{
			$arrColumns[] = "$t.featured=1";
		}
		elseif ($blnFeatured === false)
		{
			$arrColumns[] = "$t.featured=''";
		}

		if($currentCategory) {
      $arrColumns[] = "$t.pid IN (SELECT id FROM tl_calendar WHERE alias = '$currentCategory')";
		}

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::countBy($arrColumns, null, $arrOptions);
	}

}
