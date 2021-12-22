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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'SimpleProducts',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
	'SimpleProducts\ProductSettingsModel'     => 'system/modules/simple_products/models/ProductSettingsModel.php',
	'SimpleProducts\ProductArchiveModel'      => 'system/modules/simple_products/models/ProductArchiveModel.php',
	'SimpleProducts\ProductCategoryModel'     => 'system/modules/simple_products/models/ProductCategoryModel.php',
	'SimpleProducts\ProductTypeModel'         => 'system/modules/simple_products/models/ProductTypeModel.php',
	'SimpleProducts\ProductModel'             => 'system/modules/simple_products/models/ProductModel.php',
	'SimpleProducts\ModuleModel'              => 'system/modules/simple_products/models/ModuleModel.php',

	// Classes
	'SimpleProducts\Currencies'               => 'system/modules/simple_products/classes/Currencies.php',
	'SimpleProducts\Product'                  => 'system/modules/simple_products/classes/Product.php',
	'SimpleProducts\ProductPrice'             => 'system/modules/simple_products/classes/ProductPrice.php',

	// Modules
	'SimpleProducts\ModuleProductList'        => 'system/modules/simple_products/modules/ModuleProductList.php',
	'SimpleProducts\ModuleCategory'           => 'system/modules/simple_products/modules/ModuleCategory.php',
	'SimpleProducts\ModuleProductReader'      => 'system/modules/simple_products/modules/ModuleProductReader.php',
	'SimpleProducts\ProductGallery'           => 'system/modules/simple_products/modules/ProductGallery.php',
	'SimpleProducts\ModuleSubscribeProduct'   => 'system/modules/simple_products/modules/ModuleSubscribeProduct.php',
	'SimpleProducts\ProductCategory'          => 'system/modules/simple_products/modules/ProductCategory.php',
	'SimpleProducts\SimpleProductsUpgrade'    => 'system/modules/simple_products/modules/SimpleProductsUpgrade.php',
	'SimpleProducts\ModuleProductRequest'     => 'system/modules/simple_products/modules/ModuleProductRequest.php',
	'SimpleProducts\ModuleCategoryList'       => 'system/modules/simple_products/modules/ModuleCategoryList.php',
	'SimpleProducts\ModuleCategoryNavigation' => 'system/modules/simple_products/modules/ModuleCategoryNavigation.php',
	'SimpleProducts\ModuleProduct'            => 'system/modules/simple_products/modules/ModuleProduct.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'category_list'            => 'system/modules/simple_products/templates/category',
	'product_full_tabs'        => 'system/modules/simple_products/templates/products',
	'product_features'         => 'system/modules/simple_products/templates/products',
	'product_full'             => 'system/modules/simple_products/templates/products',
	'product_list'             => 'system/modules/simple_products/templates/products',
	'product_simple'           => 'system/modules/simple_products/templates/products',
	'mod_product_request'      => 'system/modules/simple_products/templates/modules',
	'mod_product_list'          => 'system/modules/simple_products/templates/modules',
	'mod_product_category_list' => 'system/modules/simple_products/templates/modules',
	'mod_product_reader'        => 'system/modules/simple_products/templates/modules',
	'nav_image'                => 'system/modules/simple_products/templates/navigation',
	'j_tabs'                   => 'system/modules/simple_products/templates/jquery',
));
