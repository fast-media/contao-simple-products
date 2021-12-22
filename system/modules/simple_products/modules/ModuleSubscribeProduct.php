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


class ModuleSubscribeProduct extends \ModuleSubscribe
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'nl_default';


	public function generate()
	{
    $this->product_archive = deserialize($this->product_archive);
		$objProduct = \ProductModel::findPublishedByParentAndIdOrAlias(\Input::get('items'), $this->product_archive);

		if($objProduct->newsletter) {
			if($this->nl_inherit == 'product') { $this->nl_channels = deserialize($objProduct->newsletter); }
			else { $this->nl_channels = array_merge(deserialize($this->nl_channels), deserialize($objProduct->newsletter)); }
		}
		elseif($this->nl_inherit != 'product') { $this->nl_channels = deserialize($this->nl_channels); }

		// Return if there are no channels
		if (!is_array($this->nl_channels) || empty($this->nl_channels))
		{
			return '';
		}

		return parent::generate();
	}

}
