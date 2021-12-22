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

$strTable = 'tl_product_settings';

if (array_key_exists('sp_tax_reduced', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['sp_tax_reduced'] === true) {
 $tax_reduced = true;
}

/**
 * Table tl_location
 */
$GLOBALS['TL_DCA'][$strTable] = array
(

	// Config
	'config' => array
	(
		'dataContainer'				=> 'Table',
		//'closed'											=> true,
		//'notCreatable' => true,
		'onload_callback'			=> array
		(
			array($strTable, 'checkConfig'),
		),
		'backlink'						=> 'do=product_settings',
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'							=> 0,
			'fields'						=> array('country'),
			'flag'							=> 1,
			'panelLayout'				=> 'sort,filter;search,limit'
		),
		'label' => array
		(
			'fields'						=> array('country', 'currency', 'tax', 'tax_reduced'),
			'showColumns'				=> true
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'						=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'						=> 'act=select',
				'class'						=> 'header_edit_all',
				'attributes'			=> 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['edit'],
				'href'						=> 'act=edit',
				'icon'						=> 'edit.gif'
			),
			'copy' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['copy'],
				'href'						=> 'act=copy',
				'icon'						=> 'copy.gif'
			),
			'delete' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['delete'],
				'href'						=> 'act=delete',
				'icon'						=> 'delete.gif',
				'attributes'			=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' =>array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['show'],
				'href'						=> 'act=show',
				'icon'						=> 'show.gif'
			),
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'				=> array('show_tax'),
		'default'							=> '{config_legend},country;{price_legend},noprice,currency,currency_sign,currency_prefix;{tax_legend},show_tax',
	),


	// Subpalettes
	'subpalettes' => array
	(
		'show_tax'						=> 'tax' . ($tax_reduced ? ',tax_reduced' : '') . '',
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'								=> "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'								=> "int(10) unsigned NOT NULL default '0'"
		),
		'show_tax' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['show_tax'],
			'exclude'						=> true,
			'value'             => 1,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'clr', 'submitOnChange'=>true),
			'sql'								=> "char(1) NOT NULL default '1'"
		),
		'tax' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['tax'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('maxlength'=>4, 'rgxp'=>'digit', 'tl_class'=>'w50'),
			'sql'								=> "decimal(3,1) NOT NULL default '0.0'"
		),
		'tax_reduced' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['tax_reduced'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('maxlength'=>4, 'rgxp'=>'digit', 'tl_class'=>'w50'),
			'sql'								=> "decimal(3,1) NOT NULL default '0.0'"
		),
		'country' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['country'],
			'exclude'						=> true,
			'default'						=> 'de',
			'filter'						=> true,
			'sorting'						=> true,
			'inputType'					=> 'select',
			'options'						=> System::getCountries(),
			'eval'							=> array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
			'sql'								=> "varchar(2) NOT NULL default 'de'"
		),
		'currency' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['currency'],
			'exclude'						=> true,
			'inputType'					=> 'select',
			'options'						=> Currencies::getCurrency('unique'),
			'eval'							=> array('includeBlankOption' => true, 'tl_class'=>'w50'),
			'sql'								=> "char(3) NOT NULL default ''"
		),
		'currency_sign' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['currency_sign'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50 m12'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'currency_prefix' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['currency_prefix'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50 m12'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'noprice' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['noprice'],
			'inputType'					=> 'select',
			'options'						=> array('request', 'none', 'free'),
			'reference'					=> &$GLOBALS['TL_LANG']['MSC']['product_noprice'],
			'eval'							=> array('tl_class'=>'w50'),
			'sql'								=> "varchar(10) NOT NULL default ''"
		)
	)
);


/**
 * Class tl_product_settings
 */
class tl_product_settings extends Backend {

	/**
	 * Database result
	 * @var array
	 */
	protected $arrData = null;

	public function __construct()
	{
		parent::__construct();
	}

	public function checkConfig()
	{
		$objConfig = \Database::getInstance()->prepare("SELECT id FROM tl_product_settings")->execute();

		//if(\Input::get('key')) return;

		if(!$objConfig->numRows && !\Input::get('act'))
		{//echo 'Weiter create';
			$this->redirect($this->addToUrl('act=create'));
		}
		elseif($objConfig->numRows) {
			$GLOBALS['TL_DCA']['tl_product_settings']['config']['closed'] = true;
		}

		//Weiterleitung, um zurück-Button zu ermöglichen
		if($this->getReferer() == 'contao/main.php')
		{
			$this->redirect('contao/main.php?do=product');
		}

		//Temporäre Weiterleitung
		elseif(!\Input::get('id') && !\Input::get('act') && !stristr($this->getReferer(), 'tl_product_settings'))
		{
			$this->redirect('contao/main.php?do=product&table=tl_product_settings&amp;act=edit&amp;id=' . $objConfig->id . '&amp;rt=' . REQUEST_TOKEN);
		}

		elseif(\Input::get('id') && !\Input::get('act'))
		{
			$this->redirect($this->addToUrl('act=edit'));
		}
	}

}
