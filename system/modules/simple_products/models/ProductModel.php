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


class ProductModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_product';

	/**
	 * Get the categories cache and return it as array
	 * @return array
	 */
	public static function getCategoriesCache()
	{
		static $arrCache;

		if (!is_array($arrCache))
		{
			$arrCache = array();
			$objCategories = \Database::getInstance()->execute("SELECT * FROM tl_product_category");

			while ($objCategories->next())
			{
				$arrCache[$objCategories->category_id][] = $objCategories->id;
			}
		}

		return $arrCache;
	}


	/**
	 * Find published product items by their parent ID and ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of parent IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return \Model|null The ProductModel or null if there are no product
	 */
	public static function findPublishedByParentAndIdOrAlias($varId, $arrPids, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?) AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!static::isPreviewMode($arrOptions))
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
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
	public static function findPublishedByPids($arrPids, $arrCats, $blnFeatured=null, $intLimit=0, $intOffset=0, array $arrOptions=array())
	{

		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

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

		// Set the item from the auto_item parameter
		if (!isset($_GET['category']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
      $category = \Input::get('auto_item');
		}
		else {
    	$category = \Input::get('category');
		}

		// Try to find by category
		if ($category)
		{
			$objCategory = \ProductCategoryModel::findPublishedByIdOrAlias($category);

			if ($objCategory === null)
			{
				return null;
			}

			if($arrOptions['deep_categories']) {
	      //Try to find sub category
	      $objSubCategories = \ProductCategoryModel::findPublishedByParent($objCategory->id);
			}

			if ($objSubCategories)
			{
				foreach ($objSubCategories AS $cat)
				{
    			$arrColumnsTemp .= " OR $t.category LIKE '%\"$cat->id\"%'";
					//Try to find sub sub category
	        $objSubCategories2 = \ProductCategoryModel::findPublishedByParent($cat->id);
          if ($objSubCategories2)
					{
						foreach($objSubCategories2 AS $cat2)
						{
		    			$arrColumnsTemp .= " OR $t.category LIKE '%\"$cat2->id\"%'";
							//Try to find sub sub category
					 	}
					}
			 	}
        $arrColumns['category'] = "($t.category LIKE '%\"$objCategory->id\"%'".$arrColumnsTemp.")";
        $arrColumnsTemp = '';
			}
			else
			{
				$arrColumns['category'] = "$t.category LIKE '%\"$objCategory->id\"%'";
			}
		}

		if (is_array($arrCats) && !empty($arrCats))
		{
			foreach ($arrCats AS $cat) {
				$arrColumnsTemp .= "$t.category LIKE '%\"$cat\"%' OR ";
			}
      $arrColumns[] = "(".substr($arrColumnsTemp,0,-4).")";
		}

		//Try to find by search
		//multiple search
    $replace = '';

		if (\Input::get('search'))
		{
      $search = \Input::get('search');
			$arrSearch = explode(',',$search);
			foreach($arrSearch AS $field)
			{
				if($GLOBALS['TL_DCA']['tl_product']['fields'][$field]) {
					if ($for = \Input::get('for'))
					{
						// ignore spaces
						if($arrOptions['search_nospace'])
						{
               //$arrOr[] = " OR REPLACE($t.$field, ' ', '') LIKE '%".$for."%'";
							//$arrOr[] = " OR REPLACE(".$t.".".$field.", ' ', '') LIKE '".$for."'";
						}

						// fulltext search
						if($arrOptions['fulltext'])
						{
	            $arrOr[] = "MATCH ($t.$field) AGAINST ('+".$for."' IN BOOLEAN MODE) AS score";
              //$arrOptions['column'] = "MATCH ($t.$field) AGAINST ('+".$for."' IN BOOLEAN MODE) AS score";
              //$arrOptions['order'] = 'score DESC';
						}
						// standard search
						else
						{
							$arrOr[] = "$t.$field LIKE '% ".$for."%' OR $t.$field LIKE '%>".$for."%' OR $t.$field LIKE '".$for."%'";
						}
					}

	        if($value = \Input::get($field))
					{
	        	$arrColumns[] = "($t.$field LIKE '% ".$value."%' OR $t.$field LIKE '".$value."%')";
					}
				}
			}
			if($arrOr) {
				$arrOr = implode(' OR ',$arrOr);
				$arrColumns[] = '('.$arrOr.')';
			}
		}
    //$arrColumns[] = "MATCH ($t.teaser) AGAINST ('+".\Input::get('for')."' IN BOOLEAN MODE)";

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.id ASC";
		}
//print_r($arrColumns);
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
	public static function countPublishedByPids($arrPids, $arrCats, $blnFeatured=null, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return 0;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if ($blnFeatured === true)
		{
			$arrColumns[] = "$t.featured=1";
		}
		elseif ($blnFeatured === false)
		{
			$arrColumns[] = "$t.featured=''";
		}

		if (!static::isPreviewMode($arrOptions))
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}
		//print_r($arrColumns);

		// Set the item from the auto_item parameter
		if (!isset($_GET['category']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
      $category = \Input::get('auto_item');
		}
		else {
    	$category = \Input::get('category');
		}

		// Try to find by category
		if ($category)
		{
			$objCategory = \ProductCategoryModel::findPublishedByIdOrAlias($category);

			if ($objCategory === null)
			{
				return null;
			}

			if($arrOptions['deep_categories']) {
	      //Try to find sub category
	      $objSubCategories = \ProductCategoryModel::findPublishedByParent($objCategory->id);
			}

			if ($objSubCategories)
			{
				foreach ($objSubCategories AS $cat)
				{
    			$arrColumnsTemp .= " OR $t.category LIKE '%\"$cat->id\"%'";
					//Try to find sub sub category
	        $objSubCategories2 = \ProductCategoryModel::findPublishedByParent($cat->id);
          if ($objSubCategories2)
					{
						foreach($objSubCategories2 AS $cat2)
						{
		    			$arrColumnsTemp .= " OR $t.category LIKE '%\"$cat2->id\"%'";
							//Try to find sub sub category
					 	}
					}
			 	}
        $arrColumns['category'] = "($t.category LIKE '%\"$objCategory->id\"%'".$arrColumnsTemp.")";
        $arrColumnsTemp = '';
			}
			else
			{
				$arrColumns['category'] = "$t.category LIKE '%\"$objCategory->id\"%'";
			}
		}

		if (is_array($arrCats) && !empty($arrCats))
		{
			foreach($arrCats AS $cat)
			{
				$arrColumnsTemp .= "$t.category LIKE '%\"$cat\"%' OR ";
			}
      $arrColumns['category'] = "(".substr($arrColumnsTemp,0,-4).")";
		}

		//Try to find by search
		//multiple search
    $replace = '';

		if (\Input::get('search'))
		{
      $search = \Input::get('search');
			$arrSearch = explode(',',$search);
			foreach($arrSearch AS $field)
			{
				if($GLOBALS['TL_DCA']['tl_product']['fields'][$field]) {
					if ($for = \Input::get('for'))
					{
						// ignore spaces
						if($arrOptions['search_nospace'])
						{
							//$arrOr[] = "REPLACE($t.$field, ' ', '') LIKE '%".$for."%'";
						}

						// fulltext search
						if($arrOptions['fulltext'])
						{
	            $arrOr[] = "MATCH ($t.$field) AGAINST ('+".$for."' IN BOOLEAN MODE)";
						}
						// standard search
						else
						{
							$arrOr[] = "$t.$field LIKE '% ".$for."%' OR $t.$field LIKE '%>".$for."%' OR $t.$field LIKE '".$for."%'";
						}
					}

	        if($value = \Input::get($field))
					{
	        	$arrColumns[] = "($t.$field LIKE '% ".$value."%' OR $t.$field LIKE '".$value."%')";
					}
				}
			}
			if($arrOr) {
				$arrOr = implode(' OR ',$arrOr);
				$arrColumns[] = '('.$arrOr.')';
			}
		}

		return static::countBy($arrColumns, null, $arrOptions);
	}


	/**
	 * Find published product items with the default redirect target by their parent ID
	 *
	 * @param integer $intPid     The product archive ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no product
	 */
	public static function findPublishedDefaultByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.source='none'");

		if (!static::isPreviewMode($arrOptions))
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.date DESC";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
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
	public static function findById($intId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		if (!static::isPreviewMode($arrOptions))
		{
			$arrColumns[] = "$t.published=1";
		}

		return static::findOneBy($arrColumns, $intId);
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
			$arrOptions['order']  = "$t.sorting, $t.title ASC";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		return static::findBy($arrColumns, null, $arrOptions);
	}


	/**
	 * Find published product items by their parent ID
	 *
	 * @param integer $intId      The product archive ID
	 * @param integer $intLimit   An optional limit
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no product
	 */
	public static function findPublishedByPid($intId, $intLimit=0, array $arrOptions=array())
	{
		$time = time();
		$t = static::$strTable;

		$arrColumns = array("$t.pid=? AND ($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1");

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.date DESC";
		}

		if ($intLimit > 0)
		{
			$arrOptions['limit'] = $intLimit;
		}

		return static::findBy($arrColumns, $intId, $arrOptions);
	}


	/**
	 * Find all published product items of a certain period of time by their parent ID
	 *
	 * @param integer $intFrom    The start date as Unix timestamp
	 * @param integer $intTo      The end date as Unix timestamp
	 * @param array   $arrPids    An array of product archive IDs
	 * @param integer $intLimit   An optional limit
	 * @param integer $intOffset  An optional offset
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no product
	 */
	public static function findPublishedFromToByPids($intFrom, $intTo, $arrPids, $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.date>=? AND $t.date<=? AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!static::isPreviewMode($arrOptions))
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.date DESC";
		}

		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		return static::findBy($arrColumns, array($intFrom, $intTo), $arrOptions);
	}


	/**
	 * Count all published product items of a certain period of time by their parent ID
	 *
	 * @param integer $intFrom    The start date as Unix timestamp
	 * @param integer $intTo      The end date as Unix timestamp
	 * @param array   $arrPids    An array of product archive IDs
	 * @param array   $arrOptions An optional options array
	 *
	 * @return integer The number of product items
	 */
	public static function countPublishedFromToByPids($intFrom, $intTo, $arrPids, array $arrOptions=array())
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.date>=? AND $t.date<=? AND $t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");

		if (!static::isPreviewMode($arrOptions))
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::countBy($arrColumns, array($intFrom, $intTo), $arrOptions);
	}
}
