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


class ProductListFeatures extends \ContentList
{

	public function __construct($arrItems)
	{
		$this->id = $objTemplate->id;
		$this->type = 'list feature_list';
    $this->tstamp = time();

		// Apply product feature list
		$this->listitems = $arrItems;
	}
}
