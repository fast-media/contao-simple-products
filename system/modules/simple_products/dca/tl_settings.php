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

$strTable = 'tl_settings';

/**
 * Palettes
 */
$GLOBALS['TL_DCA'][$strTable]['palettes']['default'] .= ';{simple_products_legend:hide},sp_image_size,sp_image_fullsize,sp_gal_size,sp_gal_fullsize,sp_gal_perRow;{simple_products_backend_legend:hide},switch_product,sp_tax_reduced,sp_units';

$GLOBALS['TL_DCA'][$strTable]['palettes']['__selector__'][] = 'sp_tax_reduced';

/**
 * Subpalettes
 */
$GLOBALS['TL_DCA'][$strTable]['subpalettes']['sp_tax_reduced'] = 'sp_product_tax';


/**
 * Fields
 */
$GLOBALS['TL_DCA'][$strTable]['fields']['switch_product'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['switch_product'],
	'inputType'					=> 'checkbox',
	'eval'							=> array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA'][$strTable]['fields']['sp_tax_reduced'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['sp_tax_reduced'],
	'inputType'					=> 'checkbox',
	'eval'							=> array('tl_class'=>'w50', 'submitOnChange'=>true)
);

$GLOBALS['TL_DCA'][$strTable]['fields']['sp_product_tax'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['sp_product_tax'],
	'inputType'					=> 'checkbox',
	'eval'							=> array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA'][$strTable]['fields']['sp_units'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['sp_units'],
	'exclude'						=> true,
	'inputType'					=> 'text',
	'eval'							=> array('maxlength'=>255, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA'][$strTable]['fields']['sp_image_size'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['sp_image_size'],
	'inputType'					=> 'imageSize',
	'reference'					=> &$GLOBALS['TL_LANG']['MSC'],
	'eval'							=> array('rgxp'=>'natural', 'includeBlankOption'=>true, 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50 clr'),
	'options_callback' => function ()
	{
		return System::getContainer()->get('contao.image.image_sizes')->getOptionsForUser(BackendUser::getInstance());
	}
);

$GLOBALS['TL_DCA'][$strTable]['fields']['sp_image_fullsize'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['sp_image_fullsize'],
	'inputType'					=> 'checkbox',
	'eval'							=> array('tl_class'=>'w50 m12'),
);

$GLOBALS['TL_DCA'][$strTable]['fields']['sp_gal_size'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['sp_gal_size'],
	'inputType'					=> 'imageSize',
	'reference'					=> &$GLOBALS['TL_LANG']['MSC'],
	'eval'							=> array('rgxp'=>'natural', 'includeBlankOption'=>true, 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50 clr'),
	'options_callback' => function ()
	{
		return System::getContainer()->get('contao.image.image_sizes')->getOptionsForUser(BackendUser::getInstance());
	}
);

$GLOBALS['TL_DCA'][$strTable]['fields']['sp_gal_fullsize'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['sp_gal_fullsize'],
	'inputType'					=> 'checkbox',
	'eval'							=> array('tl_class'=>'w50 m12'),
);

$GLOBALS['TL_DCA'][$strTable]['fields']['sp_gal_perRow'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['sp_gal_perRow'],
	'exclude'						=> true,
	'inputType'					=> 'select',
	'options'						=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12),
	'eval'							=> array('tl_class'=>'w50', 'includeBlankOption'=>true),
	'sql'								=> "smallint(5) unsigned NOT NULL default '0'"
);
