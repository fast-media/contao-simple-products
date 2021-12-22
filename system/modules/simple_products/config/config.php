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
 * Back end modules
*/
$GLOBALS['BE_MOD']['content']['product'] = array
(
	'tables'						=> array('tl_product_archive', 'tl_product_category', 'tl_product', 'tl_content', 'tl_product_type', 'tl_product_settings'),
	'list'							=> array('ListWizard', 'importList'),
	'table' 						=> array('TableWizard', 'importTable'),
	'stylesheet'				=> 'system/modules/simple_products/assets/style.css',
	'icon'							=> 'system/modules/simple_products/assets/product.png'
);

if(\Input::get('do') == 'product' && \Input::get('help')) {
	$GLOBALS['BE_MOD']['content']['product']['callback'] = 'SimpleProducts\SimpleProductsUpgrade';
  $GLOBALS['BE_MOD']['content']['product']['stylesheet'] = 'system/modules/simple_products/assets/upgrade.css';
}

/**
 * Hooks
 */

/**
 * Register hook to add product items to the indexer
 */
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('Product', 'getSearchablePages');
$GLOBALS['TL_HOOKS']['generateXmlFiles'][] = array('Product', 'generateFeeds');

/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD']['simple_products'], 0, array
(
	'product_list'								=> 'SimpleProducts\ModuleProductList',
	'product_reader'							=> 'SimpleProducts\ModuleProductReader',
	'product_category_navigation'	=> 'SimpleProducts\ModuleCategoryNavigation',
	'product_category_list'				=> 'SimpleProducts\ModuleCategoryList',
	'product_newsletter'					=> 'SimpleProducts\ModuleSubscribeProduct',
	'product_request'							=> 'SimpleProducts\ModuleProductRequest'
));


/**
 * Add permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'products';
$GLOBALS['TL_PERMISSIONS'][] = 'productp';
$GLOBALS['TL_PERMISSIONS'][] = 'productcatp';
$GLOBALS['TL_PERMISSIONS'][] = 'producttypp';
