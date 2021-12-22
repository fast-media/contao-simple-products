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

$strTable = 'tl_product_type';

/**
 * Table tl_product_type
 */
$GLOBALS['TL_DCA'][$strTable] = array
(

	// Config
	'config' => array
	(
		'dataContainer'						=> 'Table',
		'switchToEdit'				=> true,
		'enableVersioning'		=> true,
		'backlink'						=> 'do=product',
		'onload_callback'			=> array
		(
			array('tl_product_types', 'checkPermission')
		),
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
			'mode'						=> 1,
			'fields'					=> array('title'),
			'flag'						=> 1,
			'panelLayout'				=> 'search,limit'
		),
		'label' => array
		(
			'fields'					=> array('title'),
			'format'					=> '%s',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'					=> 'act=select',
				'class'					=> 'header_edit_all',
				'attributes'			=> 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'					=> &$GLOBALS['TL_LANG'][$strTable]['edit'],
    		'href'          => 'act=edit',
				'icon'					=> 'edit.svg',
				'button_callback'     => array('tl_product_types', 'editType')
			),
			'copy' => array
			(
				'label'					=> &$GLOBALS['TL_LANG'][$strTable]['copy'],
				'href'					=> 'act=copy',
				'icon'					=> 'copy.svg',
				'button_callback'     => array('tl_product_types', 'copyType')
			),
			'delete' => array
			(
				'label'					=> &$GLOBALS['TL_LANG'][$strTable]['delete'],
				'href'					=> 'act=delete',
				'icon'					=> 'delete.svg',
				'attributes'			=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_product_types', 'deleteType')
			),
			'show' =>array
			(
				'label'					=> &$GLOBALS['TL_LANG'][$strTable]['show'],
				'href'					=> 'act=show',
				'icon'					=> 'show.svg'
			),
		)
	),

	// Palettes
	'palettes' => array
	(
    '__selector__'                => array(),
		'default'						=> '{title_legend},title,alias',
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG'][$strTable]['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG'][$strTable]['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_product_types', 'generateAlias')
			),
			'sql'                     => "varchar(255) BINARY NOT NULL default ''"
		),
	)
);


class tl_product_types extends Backend
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
	 * Check permissions to edit table tl_product_type
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
			$GLOBALS['TL_DCA']['tl_product_type']['config']['closed'] = true;
		}

		// Check current action
		switch (Input::get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Add permissions on user level
				if ($this->User->inherit == 'custom' || !$this->User->groups[0])
				{
					if (!$this->User->hasAccess('create', 'producttypp'))
					{
						$this->log('Not enough permissions to '.Input::get('act').' product type ID "'.Input::get('id').'"', 'tl_product_type checkPermission', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
				}

				// Add permissions on group level
			elseif ($this->User->groups[0] > 0)
				{
					$objGroup = $this->Database->prepare("SELECT producttypp FROM tl_user_group WHERE id=?")
											   ->limit(1)
											   ->execute($this->User->groups[0]);

					$arrCalendartypp = deserialize($objGroup->producttypp);

					if (is_array($arrCalendartypp) && in_array('create', $arrCalendartypp))
					{
					}
					else {
						$this->log('Not enough permissions to '.Input::get('act').' product type ID "'.Input::get('id').'"', 'tl_product_type checkPermission', TL_ERROR);
						$this->redirect('contao/main.php?act=error');
					}
				}

			case 'copy':
			case 'delete':
			case 'show':
				if (!$this->User->hasAccess('show', 'producttypp'))
				{
					$this->log('Not enough permissions to '.Input::get('act').' product type ID "'.Input::get('id').'"', 'tl_product_type checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if (Input::get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'producttypp'))
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
					$this->log('Not enough permissions to '.Input::get('act').' producttyps archives', 'tl_product_type checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}

	/**
	 * Return the edit category button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editType($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'producttypp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
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
	public function copyType($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'producttypp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
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
	public function deleteType($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'producttypp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
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
			$varValue = \StringUtil::generateAlias($dc->activeRecord->title);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_product_type WHERE alias=?")
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

}
