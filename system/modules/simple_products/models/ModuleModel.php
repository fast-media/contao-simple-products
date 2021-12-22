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


class ModuleModel extends \Contao\ModuleModel
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_module';


	/**
	 * Find an active member by his/her e-mail-address and username
	 *
	 * @param string $strEmail    The e-mail address
	 * @param string $strUsername The username
	 * @param array  $arrOptions  An optional options array
	 *
	 * @return \Model|null The model or null if there is no member
	 */
	public static function findById($strId)
	{
		$time = time();
		$t = static::$strTable;

		$arrColumns = array("$t.id=?");

		return static::findOneBy($arrColumns, array($strId));
	}
}
