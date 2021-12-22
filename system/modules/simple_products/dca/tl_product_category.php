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

$strTable = 'tl_product_category';

//Check if extension 'widget_tree_picker' is installed
if (in_array('widget_tree_picker', $this->Config->getActiveModules()))
{
	$arrCategoryEval = array('fieldType'=>'radio', 'foreignTable'=>'tl_product_category', 'titleField'=>'title', 'searchField'=>'title', 'managerHref'=>'do=product&table=tl_product_category');
	$strCategoryInputType = 'treePicker';
}
else
{
  $arrCategoryEval = array('includeBlankOption'=>true, 'chosen'=>true);
	$strCategoryInputType = 'select';
}

/**
 * Load tl_content language file
 */
System::loadLanguageFile('tl_content');

//In der Palette Steuersatz eintragen
if (array_key_exists('sp_tax_reduced', $GLOBALS['TL_CONFIG']) && $GLOBALS['TL_CONFIG']['sp_tax_reduced'] === true) {
	$tax_reduced = ',tax_reduced';
}

/**
 * Table tl_product_category
 */
$GLOBALS['TL_DCA'][$strTable] = array
(

	// Config
	'config' => array
	(
		'dataContainer'				=> 'Table',
		'label'								=> &$GLOBALS['TL_LANG']['MOD']['product_categories'][0],
		'switchToEdit'				=> true,
		'enableVersioning'		=> true,
		'backlink'						=> 'do=product',
		'onload_callback'			=> array
		(
			array($strTable, 'checkPermission')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'							=> 5,
			'icon'							=> 'system/modules/simple_products/assets/category.png',
			'flag'							=> 1,
			'panelLayout'				=> 'filter;sort,search'
		),
		'label' => array
		(
			'fields'						=> array('title'),
			'label_callback'		=> array($strTable, 'generateLabel'),
			'format'						=> '%s',
		),
		'global_operations' => array
		(
			'toggleNodes' => array
			(
					'label'					=> &$GLOBALS['TL_LANG']['MSC']['toggleAll'],
					'href'					=> 'ptg=all',
					'class'					=> 'header_toggle'
			),
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
				'icon'						=> 'edit.svg'
			),
			'copy' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['copy'],
				'href'						=> 'act=paste&amp;mode=copy',
				'icon'						=> 'copy.svg',
				'button_callback'	=> array($strTable, 'copyCategory')
			),
			'copyChilds' => array
			(
					'label'					=> &$GLOBALS['TL_LANG'][$strTable]['copyChilds'],
					'href'					=> 'act=paste&amp;mode=copy&amp;childs=1',
					'icon'					=> 'copychilds.svg',
					'attributes'		=> 'onclick="Backend.getScrollOffset()"'
			),
			'cut' => array
			(
					'label'					=> &$GLOBALS['TL_LANG'][$strTable]['cut'],
					'href'					=> 'act=paste&amp;mode=cut',
					'icon'					=> 'cut.svg',
					'attributes'		=> 'onclick="Backend.getScrollOffset()"'
			),
			'delete' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['delete'],
				'href'						=> 'act=delete',
				'icon'						=> 'delete.svg',
				'attributes'			=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'	=> array($strTable, 'deleteCategory')
			),
			'toggle' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['toggle'],
				'icon'						=> 'visible.svg',
				'attributes'			=> 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'	=> array($strTable, 'toggleIcon')
			),
			'show' =>array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['show'],
				'href'						=> 'act=show',
				'icon'						=> 'show.svg'
			),
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'				=> array('addImage'),
		'default'							=> '{title_legend},title,alias,teaser;{image_legend:hide},addImage;{product_image_legend:hide},fallback_image;{tax_legend:hide}'.$tax_reduced.';{redirect_legend:hide},jumpTo;{expert_legend:hide},cssClass,featured;{publish_legend},published,start,stop',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addImage'						=> 'singleSRC,alt,size,imagemargin,imageUrl,fullsize,caption,floating'
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
			'foreignKey'				=> 'tl_product_category.title',
			'sql'								=> "int(10) unsigned NOT NULL default '0'",
			'relation'					=> array('type'=>'belongsTo', 'load'=>'eager')
		),
		'sorting' => array
		(
			'sql'								=> "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'								=> "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['title'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['alias'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('unique'=>true, 'rgxp'=>'alias', 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback'			=> array
			(
				array($strTable, 'generateAlias')
			),
			'sql'								=> "varchar(255) BINARY NOT NULL default ''"
		),
		'teaser' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['teaser'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'textarea',
			'eval'							=> array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
			'sql'								=> "text NULL"
		),
		'addImage' => array
		(
			'label'							=> &$GLOBALS['TL_LANG']['tl_content']['addImage'],
			'exclude'						=> true,
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
		'jumpTo' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['jumpTo'],
			'exclude'						=> true,
			'inputType'					=> $strCategoryInputType,
			'foreignKey'				=> 'tl_product_category.title',
			'eval'							=> $arrCategoryEval, //array('includeBlankOption'=>true),
			'sql'								=> "int(10) unsigned NOT NULL default '0'"
		),
		'fallback_image' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['fallback_image'],
			'exclude'						=> true,
			'inputType'					=> 'fileTree',
			'eval'							=> array('tl_class'=>'w50', 'fieldType' => 'radio', 'files' => true, 'filesOnly' => true),
			'sql'								=> "binary(16) NULL"
		),
		'tax_reduced' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['tax_reduced'],
			'exclude'						=> true,
			'filter'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50'),
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
		'published' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['published'],
			'exclude'						=> true,
			'search'						=> true,
			'default'						=> 1,
			'inputType'					=> 'checkbox',
			'sql'								=> "char(1) NOT NULL default ''",
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

// Contao 3.3
if (version_compare(VERSION . '.' . BUILD, '3.4.0', '<'))
{
	$GLOBALS['TL_DCA'][$strTable]['fields']['size']['options'] = $GLOBALS['TL_CROP'];
}
else {
	$GLOBALS['TL_DCA'][$strTable]['fields']['size']['options'] = \System::getImageSizes();
}


class tl_product_category extends Backend
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
* Add the correct indentation
* @param array
* @param string
* @param object
* @param string
* @return string
*/
	public function generateLabel($arrRow, $strLabel, $objDca, $strAttributes)
	{
			return \Image::getHtml('iconPLAIN.svg', '', $strAttributes) . ' ' . $strLabel;
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
			//Übergeordneten Alias auslesen
			if($dc->activeRecord->pid) {
				// Read translated category
        if(\Input::get('table') == 'tl_product_category_language') {
					// Find out original parent category id of current translated category
          $intParentId = \ProductCategoryModel::findPublishedByIdOrAlias($dc->activeRecord->pid)->pid;
					// Find out translation of original parent category
          $strParentAlias = $this->Database->prepare("SELECT alias FROM tl_product_category_language WHERE pid=? AND language=?")->execute($intParentId, $dc->activeRecord->language)->alias;
				}
				else {
          $strParentAlias = \ProductCategoryModel::findPublishedByIdOrAlias($dc->activeRecord->pid)->alias;
				}

				if($strParentAlias) {
          $varValue = $strParentAlias.'-'.$dc->activeRecord->title;
				}
			}

			if(!$varValue) {
				$varValue = $dc->activeRecord->title;
			}

			$varValue = \StringUtil::generateAlias($varValue);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_product_category WHERE alias=?")
			->execute($varValue);

		// Check whether the product alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}


	/**
	 * Check permissions to edit table tl_product_category
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		if (!$this->User->isAdmin && !$this->User->hasAccess('show', 'productcatp')) {
			$this->redirect('contao/main.php?act=error');
		}

		if (!$this->User->hasAccess('create', 'productcatp'))
		{
			$GLOBALS['TL_DCA']['tl_product_category']['config']['closed'] = true;
		}

		// Check current action
		switch (Input::get('act'))
		{
			case 'paste':
				// Allow
				break;

			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Add permissions on user level
				if ($this->User->inherit == 'custom' || !$this->User->groups[0])
				{
					if (!$this->User->hasAccess('create', 'productcatp'))
					{
						$this->log('Not enough permissions to '.Input::get('act').' product category ID "'.Input::get('id').'"', 'tl_product_category checkPermission', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
				}

				// Add permissions on group level
			elseif ($this->User->groups[0] > 0)
				{
					$objGroup = $this->Database->prepare("SELECT productcatp FROM tl_user_group WHERE id=?")
										->limit(1)
										->execute($this->User->groups[0]);

					$arrCalendarcatp = deserialize($objGroup->productcatp);

					if (is_array($arrCalendarcatp) && in_array('create', $arrCalendarcatp))
					{
					}
					else {
						$this->log('Not enough permissions to '.Input::get('act').' product category ID "'.Input::get('id').'"', 'tl_product_category checkPermission', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
				}

				break;
			case 'cut':
			case 'copy':
			case 'delete':
			case 'show':
				if (!$this->User->hasAccess('show', 'productcatp'))
				{
					$this->log('Not enough permissions to '.Input::get('act').' product category ID "'.Input::get('id').'"', 'tl_product_category checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if (Input::get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'productcatp'))
				{
					$session['CURRENT']['IDS'] = array();
				}
				else
				{
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen(Input::get('act')))
				{
					$this->log('Not enough permissions to '.Input::get('act').' product categories', 'tl_product_category checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Return the copy category button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyCategory($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'productcatp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
	}


	/**
	 * Return the delete archive button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteCategory($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'productcatp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
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

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.svg';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Publish/unpublish a category
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		$this->createInitialVersion('tl_product_category', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_product_category']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_product_category']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_product_category SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
				->execute($intId);

		$this->createNewVersion('tl_product_category', $intId);
	}
}
