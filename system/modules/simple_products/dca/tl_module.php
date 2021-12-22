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

$strTable = 'tl_module';

/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA'][$strTable]['palettes']['product_list'] = '{title_legend},name,headline,type;{config_legend},product_archive,category_jumpTo,numberOfItems,perPage,skipFirst,filter_ignore,sort_fields;{restriction_legend},product_category,product_featured;{template_legend:hide},product_template,customTpl;{image_legend},imgSize,fallback_image;{redirect_legend:hide},jumpTo;{view_legend:hide},perRow,show_count,show_view,show_sort,show_limit;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA'][$strTable]['palettes']['product_reader'] = '{title_legend},name,headline,type;{config_legend},product_archive,category_jumpTo;{list_legend:hide},product_fields;{template_legend:hide},product_template,customTpl;{image_legend},imgSize,fallback_image;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA'][$strTable]['palettes']['product_category_navigation'] = '{title_legend},name,headline,type;{nav_legend},levelOffset,showLevel,changeLevel,redirectParent,backlink_jumpTo;{restriction_legend},product_category;{redirect_legend:hide},jumpTo;{template_legend:hide},navigationTpl,customTpl;{image_legend},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA'][$strTable]['palettes']['product_category_list'] = '{title_legend},name,headline,type;{config_legend},numberOfItems,perPage,skipFirst;{restriction_legend},product_category;{redirect_legend:hide},jumpTo;{template_legend:hide},category_template,customTpl;{image_legend},imgSize;{view_legend:hide},perRow;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA'][$strTable]['palettes']['product_newsletter'] = str_replace(',nl_channels', ',product_archive,nl_channels,nl_inherit', $GLOBALS['TL_DCA'][$strTable]['palettes']['subscribe']);

$GLOBALS['TL_DCA'][$strTable]['palettes']['product_request'] = '{title_legend},name,headline,type;{config_legend},product_archive,request_fields,com_disableCaptcha;{email_legend:hide},email_admin,email_user;{redirect_legend:hide},jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'email_admin';
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'email_user';

/**
 * Add subpalettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['email_admin'] = 'admin_email,admin_subject,admin_text';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['email_user'] = 'user_subject,user_text';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA'][$strTable]['fields']['product_archive'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['product_archive'],
	'exclude'						=> true,
	'inputType'					=> 'checkbox',
	'options_callback'	=> array('tl_module_product', 'getProductArchives'),
	'eval'							=> array('multiple'=>true, 'mandatory'=>true),
	'sql'								=> "blob NULL"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['product_category'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['product_category'],
	'exclude'						=> true,
	'inputType'					=> 'checkbox',
	'eval'							=> array('multiple'=>true),
	'sql'								=> "blob NULL"
);

//Check if extension 'widget_tree_picker' is installed
if (in_array('widget_tree_picker', $this->Config->getActiveModules()))
{
	$GLOBALS['TL_DCA'][$strTable]['fields']['product_category']['eval'] = array('multiple'=>true, 'fieldType'=>'checkbox', 'foreignTable'=>'tl_product_category', 'titleField'=>'title', 'searchField'=>'title', 'managerHref'=>'do=product&table=tl_product_category');
	$GLOBALS['TL_DCA'][$strTable]['fields']['product_category']['inputType'] = 'treePicker';
}
else
{
  $GLOBALS['TL_DCA'][$strTable]['fields']['product_category']['options_callback'] = array('tl_module_product', 'getProductCategories');
}

$GLOBALS['TL_DCA'][$strTable]['fields']['product_featured'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['product_featured'],
	'default'						=> 'all_items',
	'exclude'						=> true,
	'inputType'					=> 'select',
	'options'						=> array('all_items', 'featured', 'unfeatured'),
	'reference'					=> &$GLOBALS['TL_LANG'][$strTable],
	'eval'							=> array('tl_class'=>'w50'),
	'sql'								=> "varchar(16) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['product_template'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['product_template'],
	'default'						=> 'product_list',
	'exclude'						=> true,
	'inputType'					=> 'select',
	'options_callback'	=> array('tl_module_product', 'getProductTemplates'),
	'eval'							=> array('tl_class'=>'w50'),
	'sql'								=> "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['filter_ignore'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['filter_ignore'],
	'exclude'						=> true,
	'inputType'					=> 'checkbox',
	'eval'							=> array('tl_class'=>'w50 m12'),
	'sql'								=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['show_count'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['show_count'],
	'exclude'						=> true,
	'inputType'					=> 'checkbox',
	'eval'							=> array('tl_class'=>'w50 m12'),
	'sql'								=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['show_limit'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['show_limit'],
	'exclude'						=> true,
	'inputType'					=> 'text',
	'eval'							=> array('tl_class'=>'clr'),
	'sql'								=> "varchar(128) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['show_view'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['show_view'],
	'exclude'						=> true,
	'inputType'					=> 'checkbox',
	'eval'							=> array('tl_class'=>'w50 m12'),
	'sql'								=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['email_admin'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['email_admin'],
	'exclude'						=> true,
	'inputType'					=> 'checkbox',
	'eval'							=> array('submitOnChange'=>true),
	'sql'								=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['admin_email'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['admin_email'],
	'inputType'					=> 'text',
	'exclude'						=> true,
	'eval'							=> array('tl_class' => 'long'),
	'sql'								=> "text NULL"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['admin_subject'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['admin_subject'],
	'exclude'						=> true,
	'search'						=> true,
	'inputType'					=> 'text',
	'eval'							=> array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'long'),
	'sql'								=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['admin_text'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['admin_text'],
	'exclude'						=> true,
	'inputType'					=> 'textarea',
	'eval'							=> array('style'=>'height:120px', 'decodeEntities'=>true, 'alwaysSave'=>true, 'allowHtml' => true, 'rte' => 'tinyMCE'),
	'sql'								=> "text NULL"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['email_user'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['email_user'],
	'exclude'						=> true,
	'inputType'					=> 'checkbox',
	'eval'							=> array('submitOnChange'=>true),
	'sql'								=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['user_subject'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['user_subject'],
	'exclude'						=> true,
	'search'						=> true,
	'inputType'					=> 'text',
	'eval'							=> array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'long'),
	'sql'								=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['user_text'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['user_text'],
	'exclude'						=> true,
	'inputType'					=> 'textarea',
	'eval'							=> array('style'=>'height:120px', 'decodeEntities'=>true, 'alwaysSave'=>true, 'allowHtml' => true, 'rte' => 'tinyMCE'),
	'sql'								=> "text NULL"
);

if(!$GLOBALS['TL_DCA'][$strTable]['fields']['sort_fields']) {
	$GLOBALS['TL_DCA'][$strTable]['fields']['sort_fields'] = array
	(
		'label'							=> &$GLOBALS['TL_LANG'][$strTable]['sort_fields'],
		'exclude'						=> true,
		'inputType'					=> 'checkboxWizard',
		'eval'							=> array('multiple'=>true, 'tl_class'=>'clr'),
		'sql'								=> "blob NULL"
	);
}

if(!$GLOBALS['TL_DCA'][$strTable]['fields']['show_sort']) {
	$GLOBALS['TL_DCA'][$strTable]['fields']['show_sort'] = array
	(
		'label'							=> &$GLOBALS['TL_LANG'][$strTable]['show_sort'],
		'exclude'						=> true,
		'inputType'					=> 'checkboxWizard',
		'eval'							=> array('multiple'=>true, 'tl_class'=>'clr'),
		'sql'								=> "blob NULL"
	);
}

if (TL_MODE == 'BE')
{
	if(\Input::get('table') == $strTable && \Input::get('act') == 'edit' && \Input::get('id'))
	{
		$strModuleType = \ModuleModel::findById(\Input::get('id'))->type;
		// fields for event manager modules only
		if($strModuleType == 'product_list' || $strModuleType == 'productlist_booking')
		{
      $GLOBALS['TL_DCA'][$strTable]['fields']['sort_fields']['options_callback'] = array('tl_module_product', 'getSortFields');
			$GLOBALS['TL_DCA'][$strTable]['fields']['show_sort']['options_callback'] = array('tl_module_product', 'getSortFields');
		}
    if($strModuleType == 'product_request')
		{
      $GLOBALS['TL_DCA'][$strTable]['fields']['request_fields']['options_callback'][] = array('tl_module_product', 'getRequestFields');

      $GLOBALS['TL_DCA'][$strTable]['fields']['admin_subject']['load_callback'][] = array('tl_module_product', 'getRequestSubjectDefault');
      $GLOBALS['TL_DCA'][$strTable]['fields']['admin_text']['load_callback'][] = array('tl_module_product', 'getRequestTextDefault');
      $GLOBALS['TL_DCA'][$strTable]['fields']['admin_text']['eval']['rte'] = '';

      $GLOBALS['TL_DCA'][$strTable]['fields']['user_subject']['load_callback'][] = array('tl_module_product', 'getRequestSubjectDefault');
      $GLOBALS['TL_DCA'][$strTable]['fields']['user_text']['load_callback'][] = array('tl_module_product', 'getRequestTextDefault');
      $GLOBALS['TL_DCA'][$strTable]['fields']['user_text']['eval']['rte'] = '';
		}

		//Bezeichner ändern für alle Module
		if(stristr($strModuleType, 'product')) {
			//$GLOBALS['TL_DCA'][$strTable]['fields']['numberOfItems']['label'] = $GLOBALS['TL_LANG'][$strTable]['product_numberOfItems'];
		}
	}
}

$GLOBALS['TL_DCA'][$strTable]['fields']['product_fields'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['product_fields'],
	'exclude'						=> true,
	'inputType'					=> 'checkboxWizard',
  'options_callback'  => array('tl_module_product', 'getListFields'),
	'eval'							=> array('multiple'=>true, 'tl_class'=>'clr'),
	'sql'								=> "blob NULL"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['category_template'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['category_template'],
	'default'						=> 'product_list',
	'exclude'						=> true,
	'inputType'					=> 'select',
	'options_callback'	=> array('tl_module_product', 'getCategoryTemplates'),
	'eval'							=> array('tl_class'=>'w50'),
	'sql'								=> "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['category_jumpTo'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['category_jumpTo'],
	'exclude'						=> true,
	'inputType'					=> 'pageTree',
	'foreignKey'					=> 'tl_page.title',
	'eval'							=> array('fieldType'=>'radio'),
	'sql'								=> "int(10) unsigned NOT NULL default '0'",
	'relation'					=> array('type'=>'hasOne', 'load'=>'eager')
);

$GLOBALS['TL_DCA'][$strTable]['fields']['backlink_jumpTo'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['backlink_jumpTo'],
	'exclude'						=> true,
	'inputType'					=> 'pageTree',
	'foreignKey'		  	=> 'tl_page.title',
	'eval'							=> array('fieldType'=>'radio', 'tl_class'=>'clr'),
	'sql'								=> "int(10) unsigned NOT NULL default '0'",
	'relation'					=> array('type'=>'hasOne', 'load'=>'eager')
);

$GLOBALS['TL_DCA'][$strTable]['fields']['fallback_image'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['fallback_image'],
	'exclude'						=> true,
	'inputType'					=> 'fileTree',
	'eval'							=> array('tl_class'=>'clr', 'fieldType' => 'radio', 'files' => true, 'filesOnly' => true),
	'sql'								=> "binary(16) NULL"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['perRow'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['perRow'],
	'default'						=> 4,
	'exclude'						=> true,
	'inputType'					=> 'select',
	'options'						=> array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12),
	'eval'							=> array('tl_class'=>'w50'),
	'sql'								=> "smallint(5) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['nl_inherit'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['nl_inherit'],
	'exclude'						=> true,
	'inputType'					=> 'select',
	'options'						=> array('', 'product'),
  'reference'					=> array('' => 'Newsletter von Modul und Produkt', 'product' => 'Nur Newsletter vom Produkt'),
	'eval'							=> array('tl_class'=>'clr'),
	'sql'								=> "varchar(16) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['changeLevel'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['changeLevel'],
	'exclude'						=> true,
	'inputType'					=> 'checkbox',
	'eval'							=> array('tl_class'=>'w50 m12'),
	'sql'								=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA'][$strTable]['fields']['request_fields'] = array
(
	'label'							=> &$GLOBALS['TL_LANG'][$strTable]['request_fields'],
	'exclude'						=> true,
	'inputType'					=> 'checkboxWizard',
  'options_callback'	=> array('tl_module_product', 'getRequestFields'),
	'eval'							=> array('mandatory'=>true, 'multiple'=>true, 'tl_class'=>'clr'),
	'sql'								=> "blob NULL"
);

/**
 * Add the comments template drop-down menu
 */
if (in_array('comments', ModuleLoader::getActive()))
{
	$GLOBALS['TL_DCA']['tl_module']['palettes']['productreader'] = str_replace('{protected_legend:hide}', '{comment_legend:hide},com_template;{protected_legend:hide}', $GLOBALS['TL_DCA']['tl_module']['palettes']['productreader']);
}


class tl_module_product extends Backend
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
	 * Get all product archives and return them as array
	 * @return array
	 */
	public function getProductArchives()
	{
		if (!$this->User->isAdmin && !is_array($this->User->news))
		{
			return array();
		}

		$arrArchives = array();
		$objArchives = $this->Database->execute("SELECT id, title FROM tl_product_archive ORDER BY title");

		while ($objArchives->next())
		{
			if ($this->User->isAdmin || $this->User->hasAccess($objArchives->id, 'product'))
			{
				$arrArchives[$objArchives->id] = $objArchives->title;
			}
		}

		return $arrArchives;
	}

	/**
	 * Get all product categories and return them as array
	 * @return array
	 */
	public function getProductCategories()
	{
		if (!$this->User->isAdmin && !is_array($this->User->products))
		{
			return array();
		}

		$arrArchives = array();
		$objArchives = $this->Database->execute("SELECT t1.id, t1.title, t2.title AS category_title FROM tl_product_category t1 LEFT JOIN tl_product_category t2 ON t1.pid = t2.id ORDER BY t2.title, t1.title");

		while ($objArchives->next())
		{
			if ($this->User->isAdmin || $this->User->hasAccess($objArchives->id, 'product'))
			{
				if(!$objArchives->category_title) {
          $objArchives->category_title = 'Hauptkategorien';
				}
				$arrArchives[$objArchives->category_title][$objArchives->id] = $objArchives->title.' <span style="color:#bbb;display:inline;margin-left:3px">[ID '.$objArchives->id.']</span>';
			}
		}

		return $arrArchives;
	}


	/**
	 * Get all product reader modules and return them as array
	 * @return array
	 */
	public function getReaderModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='productreader' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}

	/**
	 * Return all product templates as array
	 * @return array
	 */
	public function getProductTemplates()
	{
		return $this->getTemplateGroup('product_');
	}

	/**
	 * Return all product templates as array
	 * @return array
	 */
	public function getCategoryTemplates()
	{
		return $this->getTemplateGroup('category_');
	}

	/**
	 * Return all mandatory fields of table tl_member
	 * @return array
	 */
	public function getSortFields()
	{
		\System::loadLanguageFile('tl_product');
		$this->loadDataContainer('tl_product');

		foreach ($GLOBALS['TL_DCA']['tl_product']['fields'] AS $key=>$field)
		{
			if($GLOBALS['TL_DCA']['tl_product']['fields'][$key]['sorting'] == true)
			{
				$arrFields[$key.' asc'] = $GLOBALS['TL_DCA']['tl_product']['fields'][$key]['label'][0].': '.$GLOBALS['TL_LANG']['product']['search']['asc'].' <span style="color:#bbb;display:inline;margin-left:3px">['.$key.' asc]</span>';
				$arrFields[$key.' desc'] = $GLOBALS['TL_DCA']['tl_product']['fields'][$key]['label'][0].': '.$GLOBALS['TL_LANG']['product']['search']['desc'].' <span style="color:#bbb;display:inline;margin-left:3px">['.$key.' desc]</span>';
			}

      $arrFields['rand'] = 'Zufall <span style="color:#bbb;display:inline;margin-left:3px">[rand]</span>';
		}

		return $arrFields;
	}

	/**
	 * Return all mandatory fields of table tl_member
	 * @return array
	 */
	public function getListFields()
	{
		System::loadLanguageFile('tl_product');
		$this->loadDataContainer('tl_product');

		foreach ($GLOBALS['TL_DCA']['tl_product']['fields'] AS $key=>$field)
		{
			if($GLOBALS['TL_DCA']['tl_product']['fields'][$key]['eval']['feViewable'] == true)
			{
				$arrFields[$key] = $GLOBALS['TL_DCA']['tl_product']['fields'][$key]['label'][0].' <span style="color:#bbb;display:inline;margin-left:3px">['.$key.']</span>';
			}
		}

		return $arrFields;
	}

	/**
	 * Return all public fields of table tl_guestbook_entry
	 * @return array
	 */
	public function getRequestFields()
	{

		System::loadLanguageFile('tl_product_request');
		$this->loadDataContainer('tl_product_request');

		foreach ($GLOBALS['TL_DCA']['tl_product_request']['fields'] AS $key=>$field)
		{
			if($GLOBALS['TL_DCA']['tl_product_request']['fields'][$key]['eval']['feEditable'] == true)
			{
				$arrFields[$key] = $GLOBALS['TL_DCA']['tl_product_request']['fields'][$key]['label'][0].' <span style="color:#bbb;display:inline;margin-left:3px">['.$key.']</span>';
			}
		}

		return $arrFields;
	}


	/**
	 * Load the default admin text
	 * @param mixed
	 * @return mixed
	 */
	public function getRequestTextDefault($varValue)
	{
		if (!trim($varValue))
		{
			$varValue = (is_array($GLOBALS['TL_LANG']['product_request']['email']['admin_text']) ? $GLOBALS['TL_LANG']['product_request']['email']['admin_text'][1] : $GLOBALS['TL_LANG']['product_request']['email']['admin_text']);
		}

		return $varValue;
	}

	/**
	 * Load the default admin text
	 * @param mixed
	 * @return mixed
	 */
	public function getRequestSubjectDefault($varValue)
	{
		if (!trim($varValue))
		{
			$varValue = (is_array($GLOBALS['TL_LANG']['product_request']['email']['admin_subject']) ? $GLOBALS['TL_LANG']['product_request']['email']['admin_subject'][1] : $GLOBALS['TL_LANG']['product_request']['email']['admin_subject']);
		}

		return $varValue;
	}
}
