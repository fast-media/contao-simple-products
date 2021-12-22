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

$strTable = 'tl_product';

/**
 * Load tl_content language file
 */
System::loadLanguageFile('tl_content');

/**
 * Table tl_product
 */

//Check if extension 'widget_tree_picker' is installed
if (in_array('widget_tree_picker', $this->Config->getActiveModules()))
{
	$arrCategoryEval = array('multiple'=>true, 'fieldType'=>'checkbox', 'foreignTable'=>'tl_product_category', 'titleField'=>'title', 'searchField'=>'title', 'managerHref'=>'do=product&table=tl_product_category');
	$strCategoryInputType = 'treePicker';
}
else
{
  $arrCategoryEval = array('multiple'=>true);
	$strCategoryInputType = 'checkbox';
}

//In der Palette Steuersatz eintragen
if (array_key_exists('sp_tax_reduced', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['sp_tax_reduced'] === true && array_key_exists('sp_product_tax', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['sp_product_tax'] === true) {
	$tax_reduced = ',tax_reduced';
}

//Einheiten für Stückzahl
$amount_options = array();
if ($GLOBALS['TL_CONFIG']['sp_units']) {
	$units = explode(',', $GLOBALS['TL_CONFIG']['sp_units']);
	foreach($units AS $unit) {
		$amount_options[] = $unit;
	}
}


$GLOBALS['TL_DCA'][$strTable] = array
(

	// Config
	'config' => array
	(
		'dataContainer'							=> 'Table',
		'ptable'										=> 'tl_product_archive',
		'ctable'										=> array('tl_content'),
		'switchToEdit'								=> true,
		'enableVersioning'						=> true,
		'onload_callback' => array
		(
			array($strTable, 'generateFeed')
		),
		'oncut_callback' => array
		(
			array($strTable, 'scheduleUpdate')
		),
		'ondelete_callback' => array
		(
			array($strTable, 'scheduleUpdate')
		),
		'onsubmit_callback' => array
		(
			array($strTable, 'scheduleUpdate')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'alias' => 'index',
				'pid,start,stop,published' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'							=> 4,
			'fields'						=> array('sorting', 'title'),
			'headerFields'			=> array('title', 'jumpTo', 'tstamp', 'protected', 'allowComments'),
			'panelLayout'				=> 'filter;sort,search,limit',
			'child_record_callback'   => array($strTable, 'listObjects')
		),
		'label' => array
		(
			'fields'						=> array('title'),
			'format'						=> '%s'
		),
		'global_operations' => array
		(
			'help' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['help'],
				'href'						=> 'help=1',
				'class'						=> 'header_help',
				'icon'						=> 'system/modules/simple_products/assets/key.png',
				'attributes'			=> 'onclick="Backend.getScrollOffset()" accesskey="s"'
			),
			'all' => array
			(
				'label'						=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'						=> 'act=select',
				'class'						=> 'header_edit_all',
				'attributes'			=> 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['edit'],
				'href'						=> 'table=tl_content',
				'icon'						=> 'edit.svg'
			),
			'editheader' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['editheader'],
				'href'						=> 'act=edit',
				'icon'						=> 'header.svg'
			),
			'copy' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['copy'],
				'href'						=> 'act=paste&amp;mode=copy',
				'icon'						=> 'copy.svg'
			),
			'cut' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['cut'],
				'href'						=> 'act=paste&amp;mode=cut',
				'icon'						=> 'cut.svg'
			),
			'delete' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['delete'],
				'href'						=> 'act=delete',
				'icon'						=> 'delete.svg',
				'attributes'			=> 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['toggle'],
				'icon'						=> 'visible.svg',
				'attributes'			=> 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'	=> array($strTable, 'toggleIcon')
			),
			'feature' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['feature'],
				'icon'						=> 'featured.svg',
				'attributes'			=> 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleFeatured(this,%s)"',
				'button_callback'	=> array($strTable, 'iconFeatured')
			),
			'show' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['show'],
				'href'						=> 'act=show',
				'icon'						=> 'show.svg'
			)
		)
	),

	// Edit
	'edit' => array
	(
		'buttons_callback' => array()
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'				=> array('type', 'addGallery', 'addVideo', 'gallerySettings', 'addImage', 'demo', 'addEnclosure', 'source', 'published'),
		'default'							=> '{title_legend},title,alias,type;{category_legend},category;{teaser_legend},subtitle,teaser;{image_legend},addImage;{info_legend},anr,new,date,producer,mark,weight,dimension,color;{price_legend},price'.$tax_reduced.',amount,available;{features_legend:hide},listitems;{text_legend:hide},text;{demo_legend:hide},demo;{gallery_legend:hide},addGallery,gallerySettings;{video_legend:hide},addVideo;{enclosure_legend:hide},addEnclosure;{source_legend:hide},source;{newsletter_legend:hide},newsletter;{expert_legend:hide},cssClass,featured,noRequest,author;{publish_legend},published'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addGallery'					=> 'multiSRC,gal_imagemargin,perPage,numberOfItems,sortBy',
    'gallerySettings'			=> 'gal_size,perRow,gal_fullsize',
		'addImage'						=> 'singleSRC,alt,size,imageUrl,fullsize,caption',
		'addVideo'						=> 'video_type,video_id',
		'demo_internal'				=> 'demo_jumpTo,demo_target',
		'demo_external'				=> 'demo_url,demo_target',
		'addEnclosure'				=> 'enclosure',
		'source_internal'			=> 'jumpTo',
		'source_article'			=> 'articleId',
		'source_external'			=> 'url,target',
		'published'						=> 'start,stop'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['id'],
			'sql'								=> "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['pid'],
      'inputType'					=> 'select',
			'foreignKey'				=> 'tl_product_archive.title',
			'sql'								=> "int(10) unsigned NOT NULL default '0'",
			'relation'					=> array('type'=>'belongsTo', 'load'=>'eager')
		),
		'sorting' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['MSC']['sorting'],
			'sorting'						=> true,
			'flag'							=> 1,
			'sql'								=> "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['tstamp'],
			'sql'								=> "int(10) unsigned NOT NULL default '0'",
			'sorting'						=> true,
      'flag'							=> 6
		),
		'anr' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['anr'],
			'exclude'						=> true,
			'search'						=> true,
			'sorting'						=> true,
			'flag'							=> 4,
			'length'						=> 3,
			'inputType'					=> 'text',
			'eval'							=> array('maxlength'=>255, 'tl_class'=>'w50', 'unique' => true, 'feViewable'=>true),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'new' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['new_product'],
			'exclude'						=> true,
			'filter'						=> true,
      'sorting'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50 m12'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'title' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['title'],
			'exclude'						=> true,
			'search'						=> true,
			'sorting'						=> true,
			'flag'							=> 1,
			'inputType'					=> 'text',
			'eval'							=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'feViewable'=>true),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'type' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['type'],
			'exclude'						=> true,
			'filter'						=> true,
			'default'						=> '',
			'inputType'					=> 'select',
			//'foreignKey'				=> 'tl_product_field.title',
			'options_callback'	=> array($strTable, 'getTypes'),
			'eval'							=> array('tl_class'=>'w50', 'submitOnChange'=>true, 'includeBlankOption'=>true, 'feViewable'=>true),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'subtitle' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['subtitle'],
			'exclude'						=> true,
			'search'						=> true,
			'sorting'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('maxlength'=>255, 'tl_class'=>'w50', 'feViewable'=>true),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['alias'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('rgxp'=>'alias', 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array($strTable, 'generateAlias')
			),
			'sql'								=> "varchar(255) BINARY NOT NULL default ''"
		),
		'category' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['category'],
			'exclude'						=> true,
			'filter'						=> true,
			'inputType'					=> $strCategoryInputType,
			'foreignKey'				=> 'tl_product_category.title',
			'eval'							=> $arrCategoryEval,
			'sql'								=> "blob NULL",
			'relation'					=> array('type'=>'belongsToMany', 'load'=>'lazy', 'feViewable'=>true)
		),
		'price' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['price'],
			'sorting'						=> true,
			'flag'							=> 12,
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('rgxp'=>'digit', 'tl_class'=>'w50', 'nullIfEmpty' => true, 'feViewable'=>true),
			'sql'								=> "decimal(12,2) NULL"
		),
		'amount' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['amount'],
			'exclude'						=> true,
			'inputType'					=> 'inputUnit',
			'options'						=> $amount_options,
			//'reference'					=> array(1 => 'Stück', 'm','m²','m³','lfm'),
			'eval'							=> array('includeBlankOption' => true, 'rgxp'=>'digit', 'tl_class'=>'clr w50', 'feViewable'=>true),
			'sql'								=> "varchar(64) NOT NULL default ''"
		),
		'available' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['available'],
			'exclude'						=> true,
			'sorting'						=> true,
			'inputType'					=> 'select',
			'options'						=> array('instant', 'days', 'weeks', 'sold_out', 'unavailable', 'future'),
			'reference'					=> &$GLOBALS['TL_LANG'][$strTable]['available_options'],
			'eval'							=> array('includeBlankOption' => true, 'tl_class'=>'w50', 'feViewable'=>true),
			'sql'								=> "varchar(32) NOT NULL default ''"
		),
		'tax_reduced' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['tax_reduced'],
			'exclude'						=> true,
			'filter'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'clr w50', 'feViewable'=>true),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'teaser' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['teaser'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'textarea',
			'eval'							=> array('rte'=>'tinyMCE', 'tl_class'=>'clr', 'feViewable'=>true),
			'sql'								=> "text NULL"
		),
		'addImage' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['addImage'],
			'exclude'						=> true,
			'filter'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('submitOnChange'=>true),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'singleSRC' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['singleSRC'],
			'exclude'						=> true,
			'inputType'					=> 'fileTree',
			'eval'							=> array('filesOnly'=>true, 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes'], 'fieldType'=>'radio', 'mandatory'=>true),
			'sql'								=> "binary(16) NULL"
		),
		'alt' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['alt'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('maxlength'=>255, 'tl_class'=>'long'),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'size' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['size'],
			'exclude'						=> true,
			'inputType'					=> 'imageSize',
			'reference'					=> &$GLOBALS['TL_LANG']['MSC'],
			'eval'							=> array('rgxp'=>'natural', 'includeBlankOption'=>true, 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
			'options_callback' => static function ()
			{
				return Contao\System::getContainer()->get('contao.image.image_sizes')->getOptionsForUser(Contao\BackendUser::getInstance());
			},
			'sql'								=> "varchar(64) NOT NULL default ''"
		),
		'fullsize' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['fullsize'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50 m12'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'caption' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['caption'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'listitems' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['listitems'],
			'exclude'						=> true,
			'inputType'					=> 'listWizard',
			'eval'							=> array('allowHtml'=>true),
			'xlabel' => array
			(
				array($strTable, 'listImportWizard')
			),
			'sql'								=> "blob NULL"
		),
		'text' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['text'],
			'exclude'						=> true,
			'inputType'					=> 'textarea',
			'eval'							=> array('rte'=>'tinyMCE', 'helpwizard'=>true),
			'explanation'				=> 'insertTags',
			'sql'								=> "text NULL"
		),
		'addGallery' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['addGallery'],
			'exclude'						=> true,
			'filter'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('submitOnChange'=>true),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'multiSRC' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['multiSRC'],
			'exclude'						=> true,
			'inputType'					=> 'fileTree',
			'eval'							=> array('multiple'=>true, 'fieldType'=>'checkbox', 'orderField'=>'orderSRC', 'isGallery'=>true, 'extensions'=>\Config::get('validImageTypes'), 'files'=>true, 'mandatory'=>true),
			'sql'								=> "blob NULL",
		),
		'orderSRC' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['orderSRC'],
			'sql'								=> "blob NULL"
		),
		'gal_size' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['size'],
			'exclude'						=> true,
			'inputType'					=> 'imageSize',
			'reference'					=> &$GLOBALS['TL_LANG']['MSC'],
			'eval'							=> array('rgxp'=>'natural', 'includeBlankOption'=>true, 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
			'sql'								=> "varchar(64) NOT NULL default ''"
		),
		'gal_imagemargin' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['imagemargin'],
			'exclude'						=> true,
			'inputType'					=> 'trbl',
			'options'						=> array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'							=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'								=> "varchar(128) NOT NULL default ''"
		),
		'gallerySettings' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['gallerySettings'],
			'exclude'						=> true,
			'filter'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('submitOnChange'=>true),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'gal_fullsize' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['fullsize'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50 m12'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'gal_floating' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['floating'],
			'exclude'						=> true,
			'inputType'					=> 'radioTable',
			'options'						=> array('above', 'left', 'right', 'below'),
			'eval'							=> array('cols'=>4, 'tl_class'=>'w50'),
			'reference'					=> &$GLOBALS['TL_LANG']['MSC'],
			'sql'								=> "varchar(12) NOT NULL default ''"
		),
		'perRow' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['perRow'],
			'exclude'						=> true,
			'inputType'					=> 'select',
			'options'						=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12),
			'eval'							=> array('tl_class'=>'w50', 'includeBlankOption'=>true),
			'sql'								=> "smallint(5) unsigned NOT NULL default '0'"
		),
		'perPage' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['perPage'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('rgxp'=>'digit', 'tl_class'=>'w50'),
			'sql'								=> "smallint(5) unsigned NOT NULL default '0'"
		),
		'sortBy' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['sortBy'],
			'exclude'						=> true,
			'inputType'					=> 'select',
			'options'						=> array('custom', 'name_asc', 'name_desc', 'date_asc', 'date_desc', 'random'),
			'reference'					=> &$GLOBALS['TL_LANG']['tl_content'],
			'eval'							=> array('tl_class'=>'w50'),
			'sql'								=> "varchar(32) NOT NULL default ''"
		),
		'numberOfItems' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['numberOfItems'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('rgxp'=>'digit', 'tl_class'=>'w50'),
			'sql'								=> "smallint(5) unsigned NOT NULL default '0'"
		),
		'addVideo' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['addVideo'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('submitOnChange'=>true),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'video_type' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['video_type'],
			'exclude'						=> true,
			'inputType'					=> 'select',
			'options'           => array('youtube', 'vimeo'),
			'eval'							=> array('tl_class'=>'w50'),
			'sql'								=> "varchar(32) NOT NULL default ''"
		),
		'video_id' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['video_id'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'addEnclosure' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['addEnclosure'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('submitOnChange'=>true),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'enclosure' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['enclosure'],
			'exclude'						=> true,
			'inputType'					=> 'fileTree',
			'eval'							=> array('multiple'=>true, 'fieldType'=>'checkbox', 'filesOnly'=>true, 'isDownloads'=>true, 'extensions'=>Config::get('allowedDownload'), 'mandatory'=>true),
			'sql'								=> "blob NULL"
		),
		'source' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['source'],
			'default'						=> 'none',
			'exclude'						=> true,
			'inputType'					=> 'radio',
			'options'						=> array('none', 'internal', 'article', 'external'),
			'reference'					=> &$GLOBALS['TL_LANG'][$strTable],
			'eval'							=> array('mandatory'=>true, 'submitOnChange'=>true, 'helpwizard'=>true),
			'sql'								=> "varchar(12) NOT NULL default ''"
		),
		'jumpTo' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['jumpTo'],
			'exclude'						=> true,
			'inputType'					=> 'pageTree',
			'foreignKey'				=> 'tl_page.title',
			'eval'							=> array('mandatory'=>true, 'fieldType'=>'radio'),
			'sql'								=> "int(10) unsigned NOT NULL default '0'",
			'relation'					=> array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'articleId' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['articleId'],
			'exclude'						=> true,
			'inputType'					=> 'select',
			'options_callback'				=> array($strTable, 'getArticleAlias'),
			'eval'							=> array('chosen'=>true, 'mandatory'=>true),
			'sql'								=> "int(10) unsigned NOT NULL default '0'"
		),
		'url' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['MSC']['url'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'target' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['MSC']['target'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50 m12'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'noRequest' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['noRequest'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50 m12'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'demo' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['demo'],
			'default'						=> 'none',
			'exclude'						=> true,
			'inputType'					=> 'radio',
			'options'						=> array('none','internal', 'external'),
			'reference'					=> &$GLOBALS['TL_LANG'][$strTable],
			'eval'							=> array('submitOnChange'=>true, 'helpwizard'=>true, 'tl_class'=>'long'),
			'sql'								=> "varchar(12) NOT NULL default ''"
		),
		'demo_jumpTo' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['jumpTo'],
			'exclude'						=> true,
			'inputType'					=> 'pageTree',
			'foreignKey'				=> 'tl_page.title',
			'eval'							=> array('fieldType'=>'radio', 'tl_class'=>'w50'),
			'sql'								=> "int(10) unsigned NOT NULL default '0'",
			'relation'					=> array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'demo_url' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['MSC']['url'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'demo_target' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['MSC']['target'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50 m12'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'cssClass' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['cssClass'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('tl_class'=>'w50'),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'featured' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['featured'],
			'exclude'						=> true,
			'filter'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50 m12'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'author' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['author'],
			'default'						=> BackendUser::getInstance()->id,
			'exclude'						=> true,
			'filter'						=> true,
			'sorting'						=> true,
			'flag'							=> 11,
			'inputType'					=> 'select',
			'foreignKey'				=> 'tl_user.name',
			'eval'							=> array('doNotCopy'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50', 'feViewable'=>true),
			'sql'								=> "int(10) unsigned NOT NULL default '0'",
			'relation'					=> array('type'=>'hasOne', 'load'=>'eager')
		),
		'date' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['date'],
			'exclude'						=> true,
			'sorting'						=> true,
			'flag'							=> 8,
			'inputType'					=> 'text',
			'eval'							=> array('rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'clr w50 wizard', 'nullIfEmpty' => true, 'feViewable'=>true),
			'sql'								=> "int(10) unsigned NULL"
		),
		'producer' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['producer'],
			'exclude'						=> true,
			'sorting'						=> true,
      'search'						=> true,
			'variants'					=> true,
			'inputType'					=> 'text',
			'eval'							=> array('tl_class'=>'w50', 'feViewable'=>true),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'mark' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['mark'],
			'exclude'						=> true,
			'sorting'						=> true,
      'search'						=> true,
			'variants'					=> true,
			'inputType'					=> 'text',
			'eval'							=> array('tl_class'=>'w50', 'feViewable'=>true),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'color' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['color'],
			'exclude'						=> true,
			'sorting'						=> true,
      'search'						=> true,
			'variants'					=> true,
			'inputType'					=> 'text',
			'eval'							=> array('tl_class'=>'w50', 'feViewable'=>true),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'weight' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['weight'],
			'exclude'						=> true,
			'sorting'						=> true,
      'filter'						=> true,
			'variants'					=> true,
			'inputType'					=> 'inputUnit',
			'options'						=> array('kg', 'g', 'mg', 'l', 'ml', 't'),
			'eval'							=> array('maxlength'=>7, 'includeBlankOption'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50', 'feViewable'=>true),
			'sql'								=> "varchar(64) NOT NULL default ''"
		),
		'dimension' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['dimension'],
			'exclude'						=> true,
			'variants'					=> true,
			'inputType'					=> 'text',
			'eval'							=> array('tl_class'=>'w50', 'feViewable'=>true),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'published' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['published'],
			'exclude'						=> true,
			'filter'						=> true,
			'flag'							=> 1,
			'inputType'					=> 'checkbox',
			'eval'							=> array('doNotCopy'=>true),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'start' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['start'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'								=> "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['stop'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'								=> "varchar(10) NOT NULL default ''"
		)
	)
);

//Change complexity
if (\Config::get('switch_product') !== true) {
	//Change link from settings to edit button
	$GLOBALS['TL_DCA'][$strTable]['list']['operations']['edit']['href'] = $GLOBALS['TL_DCA'][$strTable]['list']['operations']['editheader']['href'];
  $GLOBALS['TL_DCA'][$strTable]['list']['operations']['edit']['label'] = $GLOBALS['TL_LANG'][$strTable]['editheader'];

	//Remove button
	unset($GLOBALS['TL_DCA'][$strTable]['list']['operations']['editheader']);

	//Add text element to event details
	$GLOBALS['TL_DCA'][$strTable]['fields']['text'] = array(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['text'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'textarea',
			'eval'							=> array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
			'sql'								=> "text NULL"
	);
}

//Newsletter
if (in_array('newsletter', $this->Config->getActiveModules())) {
	$GLOBALS['TL_DCA'][$strTable]['fields']['newsletter'] = array
	(
		'label'								=> &$GLOBALS['TL_LANG'][$strTable]['newsletter'],
		'exclude'							=> true,
		'filter'							=> true,
		'inputType'						=> 'checkbox',
		'foreignKey'					=> 'tl_newsletter_channel.title',
		'eval'								=> array('multiple'=>true),
		'sql'									=> "text NULL",
		'relation'						=> array('type'=>'belongsToMany', 'load'=>'lazy')
	);
}

// Contao 3.3
if (version_compare(VERSION . '.' . BUILD, '3.4.0', '<'))
{
	$GLOBALS['TL_DCA'][$strTable]['fields']['size']['options'] = $GLOBALS['TL_CROP'];
  $GLOBALS['TL_DCA'][$strTable]['fields']['gal_size']['options'] = $GLOBALS['TL_CROP'];
}
else {
	$GLOBALS['TL_DCA'][$strTable]['fields']['size']['options'] = \System::getImageSizes();
  $GLOBALS['TL_DCA'][$strTable]['fields']['gal_size']['options'] = \System::getImageSizes();
}

if(TL_MODE == 'FE'|| \Input::get('table') == 'tl_module')
{
	$GLOBALS['TL_DCA'][$strTable]['fields']['pid']['filter'] = true;
}


unset($GLOBALS['TL_DCA'][$strTable]['list']['global_operations']['help']);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 */
class tl_product extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Check permissions to edit table tl_product
	 */
	public function checkPermission()
	{
		// HOOK: comments extension required
		if (!in_array('comments', ModuleLoader::getActive()))
		{
			$key = array_search('allowComments', $GLOBALS['TL_DCA']['tl_product']['list']['sorting']['headerFields']);
			unset($GLOBALS['TL_DCA']['tl_product']['list']['sorting']['headerFields'][$key]);
		}

		if ($this->User->isAdmin)
		{
			return;
		}

		// Set the root IDs
		if (!is_array($this->User->products) || empty($this->User->products))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->products;
		}

		$id = strlen(Input::get('id')) ? Input::get('id') : CURRENT_ID;

		// Check current action
		switch (Input::get('act'))
		{
			case 'paste':
				// Allow
				break;

			case 'create':
				if (!strlen(Input::get('pid')) || !in_array(Input::get('pid'), $root))
				{
					$this->log('Not enough permissions to create product items in product archive ID "'.Input::get('pid').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'cut':
			case 'copy':
				if (!in_array(Input::get('pid'), $root))
				{
					$this->log('Not enough permissions to '.Input::get('act').' product item ID "'.$id.'" to product archive ID "'.Input::get('pid').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				// NO BREAK STATEMENT HERE

			case 'edit':
			case 'show':
			case 'delete':
			case 'toggle':
			case 'feature':
				$objArchive = $this->Database->prepare("SELECT pid FROM tl_product WHERE id=?")
											 ->limit(1)
											 ->execute($id);

				if ($objArchive->numRows < 1)
				{
					$this->log('Invalid product item ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				if (!in_array($objArchive->pid, $root))
				{
					$this->log('Not enough permissions to '.Input::get('act').' product item ID "'.$id.'" of product archive ID "'.$objArchive->pid.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'select':
			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				if (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access product archive ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objArchive = $this->Database->prepare("SELECT id FROM tl_product WHERE pid=?")
											 ->execute($id);

				if ($objArchive->numRows < 1)
				{
					$this->log('Invalid product archive ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objArchive->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen(Input::get('act')))
				{
					$this->log('Invalid command "'.Input::get('act').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access product archive ID ' . $id, __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Auto-generate the product alias if it has not been set yet
	 * @param mixed
	 * @param \DataContainer
	 * @return string
	 * @throws \Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;

      $category_alias = true;

			if($category_alias)
			{
        $arrCategories = deserialize($dc->activeRecord->category);

        $strCategoryAlias = \ProductCategoryModel::findPublishedByIdOrAlias($arrCategories[0])->alias;

				if($strCategoryAlias)
				{
					$varValue = $strCategoryAlias . '-' . $dc->activeRecord->title;
				}
			}

			if($varValue == '')
			{
        $varValue = $dc->activeRecord->title;
			}

			$varValue = \StringUtil::generateAlias($varValue);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_product WHERE alias=?")
			->execute($varValue);

		// Check if the product alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias to prevent duplicate alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}


	/**
	 * Add the type of input field
	 * @param array
	 * @return string
	 */
	public function listObjects($arrRow)
	{
    $this->import('ProductPrice', 'Price');

		$key = $arrRow['published'] ? 'published' : 'unpublished';
		if($arrRow['date']) {
			$date = Date::parse($GLOBALS['TL_CONFIG']['dateFormat'], $arrRow['date']);
		}
		else {
			$date = Date::parse($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['tstamp']);
		}

		if($arrRow['addImage'] && $arrRow['singleSRC'])
		{
			$objFile = \FilesModel::findByUuid($arrRow['singleSRC']);

			if($objFile) {
				$image = Image::get($objFile->path, 60, 60, 'center_center');
				$image = '<img src="' . $image . '" style="float: left; margin-right: 10px;" alt="" />';
			}
		}

    $strAmount = '';
		$arrAmount = deserialize($arrRow['amount']);
		if($arrAmount) {
			if($amount['value']) {	$strAmount = $arrAmount['value'].' '.$arrAmount['unit']; }
		}

		$strPrice = $this->Price->priceFormat($arrRow['price']);

		$arrCategories = deserialize($arrRow['category']);
		$objCategories = \ProductCategoryModel::findPublishedByIds($arrCategories);

		if($objCategories) {
			$arrCategories = array();

			while ($objCategories->next())
			{
				$arrCategories[] = $objCategories->title;
			}
			$strCategories = implode(',', $arrCategories);
		}
		else {
			$strCategories = '';
		}

		if($arrRow['available']) { $available = $GLOBALS['TL_DCA']['tl_product']['fields']['available']['reference'][$arrRow['available']]; }

  return '
<div class="' . ($arrRow['published'] ? '' : 'inactive') . '">
<div style="width:8%;padding-right:2%;float:left;min-height:60px">'.$image.'<br></div>
<div style="width:82%;float:left;">
<div style="width:40%;padding-right:2%;float:left;"><strong>' . $arrRow['title'] . '</strong> ' . ($arrRow['addGallery'] ? ' <img src="system/modules/simple_products/assets/images.png" alt="" style="position:absolute; margin-left:10px" />' : '') . '
'. ($strCategories ? '<div style="margin-top:4px">'.$strCategories.'</div>' : '') . '
</div>
<div style="width:15%;float:left;">' . $strPrice . '</div>
<div style="width:18%;float:left;">' . ($strAmount ? 'Bestand: ' . $strAmount : '') . ($available ? '<div style="margin-top:4px">'.$available.'</div>' : '') . '</div>
</div>
</div>
';
	}


	/**
	 * Get all modules and return them as array
	 * @return array
	 */
	public function getTypes()
	{
		$arrItems = array();
  	//$arrItems['ware'] = 'Ware';
		$objItems = $this->Database->execute("SELECT id,title,alias FROM tl_product_type ORDER BY title ASC");

		while ($objItems->next())
		{
			//$date = date('d.m.Y',$objItems->time);
			$arrItems[$objItems->alias] = $objItems->title;
		}

		return $arrItems;
	}


	/**
	 * Get all articles and return them as array
	 * @param \DataContainer
	 * @return array
	 */
	public function getArticleAlias(DataContainer $dc)
	{
		$arrPids = array();
		$arrAlias = array();

		if (!$this->User->isAdmin)
		{
			foreach ($this->User->pagemounts as $id)
			{
				$arrPids[] = $id;
				$arrPids = array_merge($arrPids, $this->Database->getChildRecords($id, 'tl_page'));
			}

			if (empty($arrPids))
			{
				return $arrAlias;
			}

			$objAlias = $this->Database->prepare("SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid WHERE a.pid IN(". implode(',', array_map('intval', array_unique($arrPids))) .") ORDER BY parent, a.sorting")
									   ->execute($dc->id);
		}
		else
		{
			$objAlias = $this->Database->prepare("SELECT a.id, a.title, a.inColumn, p.title AS parent FROM tl_article a LEFT JOIN tl_page p ON p.id=a.pid ORDER BY parent, a.sorting")
									   ->execute($dc->id);
		}

		if ($objAlias->numRows)
		{
			System::loadLanguageFile('tl_article');

			while ($objAlias->next())
			{
				$arrAlias[$objAlias->parent][$objAlias->id] = $objAlias->title . ' (' . ($GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn] ?: $objAlias->inColumn) . ', ID ' . $objAlias->id . ')';
			}
		}

		return $arrAlias;
	}


	/**
	 * Return the "feature/unfeature element" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function iconFeatured($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('fid')))
		{
			$this->toggleFeatured(Input::get('fid'), (Input::get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the fid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_product::featured', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;fid='.$row['id'].'&amp;state='.($row['featured'] ? '' : 1);

		if (!$row['featured'])
		{
			$icon = 'featured_.svg';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}


	/**
	 * Feature/unfeature a product item
	 * @param integer
	 * @param boolean
	 * @return string
	 */
	public function toggleFeatured($intId, $blnVisible)
	{
		// Check permissions to edit
		Input::setGet('id', $intId);
		Input::setGet('act', 'feature');
		$this->checkPermission();

		// Check permissions to feature
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_product::featured', 'alexf'))
		{
			$this->log('Not enough permissions to feature/unfeature product item ID "'.$intId.'"', 'tl_product toggleFeatured', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objVersions = new Versions('tl_product', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_product']['fields']['featured']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_product']['fields']['featured']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_product SET tstamp=". time() .", featured='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_product.id='.$intId.'" has been created'.$this->getParentEntries('tl_product', $intId), 'tl_product toggleFeatured()', TL_GENERAL);
	}


	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_product::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.svg';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_product::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish product item ID "'.$intId.'"', 'tl_product toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objVersions = new Versions('tl_product', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_product']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_product']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_product SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_product.id='.$intId.'" has been created'.$this->getParentEntries('tl_product', $intId), 'tl_product toggleVisibility()', TL_GENERAL);

	}


	/**
	 * Add a link to the list items import wizard
	 * @return string
	 */
	public function listImportWizard()
	{
		return ' <a href="' . $this->addToUrl('key=list') . '" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['lw_import'][1]) . '" onclick="Backend.getScrollOffset()">' . Image::getHtml('tablewizard.svg', $GLOBALS['TL_LANG']['MSC']['tw_import'][0], 'style="vertical-align:text-bottom"') . '</a>';
	}


	/**
	 * Schedule a product feed update
	 *
	 * This method is triggered when a single product item or multiple product
	 * items are modified (edit/editAll), moved (cut/cutAll) or deleted
	 * (delete/deleteAll). Since duplicated items are unpublished by default,
	 * it is not necessary to schedule updates on copyAll as well.
	 * @param \DataContainer
	 */
	public function scheduleUpdate(DataContainer $dc)
	{
		// Return if there is no ID
		if (!$dc->activeRecord || !$dc->activeRecord->pid || Input::get('act') == 'copy')
		{
			return;
		}

		// Store the ID in the session
		$session = $this->Session->get('product_feed_updater');
		$session[] = $dc->activeRecord->pid;
		$this->Session->set('product_feed_updater', array_unique($session));
	}


	/**
	 * Check for modified product feeds and update the XML files if necessary
	 */
	public function generateFeed()
	{
		$session = $this->Session->get('product_feed_updater');

		if (!is_array($session) || empty($session))
		{
			return;
		}

		$this->import('Automator');
		$this->Automator->generateSitemap();

		$this->Session->set('product_feed_updater', null);
	}
}
