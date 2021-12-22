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


class ProductArchiveModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_product_archive';


	/**
	 * Find multiple news archives by their IDs
	 *
	 * @param array $arrIds     An array of archive IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no news archives
	 */
	public static function findMultipleByIds($arrIds, array $arrOptions=array())
	{
		if (!is_array($arrIds) || empty($arrIds))
		{
			return null;
		}

		$t = static::$strTable;

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = \Database::getInstance()->findInSet("$t.id", $arrIds);
		}
		return static::findBy(array("$t.id IN(" . implode(',', array_map('intval', $arrIds)) . ")"), null, $arrOptions);
	}


	/**
	 * Find published archive by ID or alias
	 *
	 * @param mixed $varId The numeric ID or alias name
	 *
	 * @return \Model|null The NewsArchiveModel or null if there is no archive
	 */
	public static function findPublishedById($varId)
	{
		$t = static::$strTable;
		$arrColumns = array("($t.id=?)");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			//$arrColumns[] = "$t.published=1";
		}

		return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId));
	}
}
