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


class SimpleProductsRunonceJob extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

	public function run()
	{
		$tstamp = time();

		// Changes in version 4.4.0
		$this->Database->execute("UPDATE tl_module SET type = 'product_list' WHERE type = 'productlist'");
		$this->Database->execute("UPDATE tl_module SET type = 'product_reader' WHERE type = 'productreader'");
		$this->Database->execute("UPDATE tl_module SET type = 'product_category_navigation' WHERE type = 'productcategories'");
		$this->Database->execute("UPDATE tl_module SET type = 'product_category_list' WHERE type = 'product_categorylist'");
	}
}

$objRunonceJob = new SimpleProductsRunonceJob();
$objRunonceJob->run();
