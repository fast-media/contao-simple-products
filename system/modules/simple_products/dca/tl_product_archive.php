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

$strTable = 'tl_product_archive';

/**
 * Table tl_product_archive
 */
$GLOBALS['TL_DCA'][$strTable] = array
(

	// Config
	'config' => array
	(
		'dataContainer'					=> 'Table',
		'ctable'											=> array('tl_product'),
		'label'							=> &$GLOBALS['TL_LANG']['MOD']['productcategories'][0],
		'switchToEdit'								=> true,
		'enableVersioning'						=> true,
		'onload_callback' => array
		(
			array($strTable, 'checkPermission'),
			array($strTable, 'generateFeed')
		),
		'onsubmit_callback' => array
		(
			array($strTable, 'scheduleUpdate')
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
			'mode'							=> 1,
			'fields'						=> array('title'),
			'flag'							=> 1,
			'panelLayout'				=> 'search,limit'
		),
		'label' => array
		(
			'fields'						=> array('title'),
			'format'						=> '%s',
		),
		'global_operations' => array
		(
			'categories' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['categories'],
				'href'						=> 'table=tl_product_category',
				'class'						=> 'header_categories',
				'icon'						=> 'system/modules/simple_products/assets/category.png',
				'attributes'			=> 'onclick="Backend.getScrollOffset()" accesskey="c"'
			),
			'types' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['types'],
				'href'						=> 'table=tl_product_type',
				'class'						=> 'header_types',
				'icon'						=> 'system/modules/simple_products/assets/type.png',
				'attributes'			=> 'onclick="Backend.getScrollOffset()" accesskey="t"'
			),
			'settings' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['settings'],
				'href'						=> 'table=tl_product_settings',
				'class'						=> 'header_settings',
				'icon'						=> 'system/modules/simple_products/assets/settings.png',
				'attributes'			=> 'onclick="Backend.getScrollOffset()" accesskey="s"'
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
				'href'						=> 'table=tl_product',
				'icon'						=> 'edit.svg'
			),
			'editheader' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['editheader'],
				'href'						=> 'act=edit',
				'icon'						=> 'header.svg',
				'button_callback'	=> array($strTable, 'editHeader')
			),
			'copy' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['copy'],
				'href'						=> 'act=copy',
				'icon'						=> 'copy.svg',
				'button_callback'=> array($strTable, 'copyArchive')
			),
			'delete' => array
			(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['delete'],
				'href'						=> 'act=delete',
				'icon'						=> 'delete.svg',
				'attributes'			=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'	=> array($strTable, 'deleteArchive')
			),
			'show' => array
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
		'__selector__'				=> array('protected', 'allowComments'),
		'default'							=> '{title_legend},title,jumpTo;{protected_legend:hide},protected;{comments_legend:hide},allowComments',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'protected'						=> 'groups',
		'allowComments'				=> 'notify,sortOrder,perPage,moderate,bbcode,requireLogin,disableCaptcha'
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
		'title' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['title'],
			'exclude'						=> true,
			'search'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('mandatory'=>true, 'maxlength'=>255),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'jumpTo' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['jumpTo'],
			'exclude'						=> true,
			'inputType'					=> 'pageTree',
			'foreignKey'				=> 'tl_page.title',
			'eval'							=> array('fieldType'=>'radio'),
			'sql'								=> "int(10) unsigned NOT NULL default '0'",
			'relation'					=> array('type'=>'hasOne', 'load'=>'eager')
		),
		'protected' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['protected'],
			'exclude'						=> true,
			'filter'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('submitOnChange'=>true),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'groups' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['groups'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'foreignKey'				=> 'tl_member_group.name',
			'eval'							=> array('mandatory'=>true, 'multiple'=>true),
			'sql'								=> "blob NULL",
			'relation'					=> array('type'=>'hasMany', 'load'=>'lazy')
		),
		'allowComments' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['allowComments'],
			'exclude'						=> true,
			'filter'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('submitOnChange'=>true),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'notify' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['notify'],
			'default'						=> 'notify_admin',
			'exclude'						=> true,
			'inputType'					=> 'select',
			'options'						=> array('notify_admin', 'notify_author', 'notify_both'),
			'reference'					=> &$GLOBALS['TL_LANG'][$strTable],
			'sql'								=> "varchar(16) NOT NULL default ''"
		),
		'sortOrder' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['sortOrder'],
			'default'						=> 'ascending',
			'exclude'						=> true,
			'inputType'					=> 'select',
			'options'						=> array('ascending', 'descending'),
			'reference'					=> &$GLOBALS['TL_LANG']['MSC'],
			'eval'							=> array('tl_class'=>'w50'),
			'sql'								=> "varchar(32) NOT NULL default ''"
		),
		'perPage' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['perPage'],
			'exclude'						=> true,
			'inputType'					=> 'text',
			'eval'							=> array('rgxp'=>'digit', 'tl_class'=>'w50'),
			'sql'								=> "smallint(5) unsigned NOT NULL default '0'"
		),
		'moderate' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['moderate'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'bbcode' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['bbcode'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'requireLogin' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['requireLogin'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50'),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'disableCaptcha' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['disableCaptcha'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('tl_class'=>'w50'),
			'sql'								=> "char(1) NOT NULL default ''"
		)
	)
);


class tl_product_archive extends Backend
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
			$varValue = standardize(String::restoreBasicEntities($dc->activeRecord->title));
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_product_archive WHERE alias=?")
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
	 * Check permissions to edit table tl_product_archive
	 */
	public function checkPermission()
	{
		// HOOK: comments extension required
		if (!in_array('comments', $this->Config->getActiveModules()))
		{
			unset($GLOBALS['TL_DCA']['tl_product_archive']['fields']['allowComments']);
		}

		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->products) || empty($this->User->products))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->products;
		}

		$GLOBALS['TL_DCA']['tl_product_archive']['list']['sorting']['root'] = $root;

		// Check permissions to add archives
		if (!$this->User->hasAccess('create', 'productp'))
		{
			$GLOBALS['TL_DCA']['tl_product_archive']['config']['closed'] = true;
		}

		// Check current action
		switch (Input::get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record to the user profile
				if (!in_array(Input::get('id'), $root))
				{
					$arrProduct = $this->Session->get('new_records');

					if (is_array($arrProduct['tl_product_archive']) && in_array(Input::get('id'), $arrProduct['tl_product_archive']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT products, productp FROM tl_user WHERE id=?")
												->limit(1)
												->execute($this->User->id);

							$arrProductp = deserialize($objUser->productp);

							if (is_array($arrProductp) && in_array('create', $arrProductp))
							{
								$arrProducts = deserialize($objUser->products);
								$arrProducts[] = Input::get('id');

								$this->Database->prepare("UPDATE tl_user SET products=? WHERE id=?")
										->execute(serialize($arrProducts), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT products, productp FROM tl_user_group WHERE id=?")
												->limit(1)
												->execute($this->User->groups[0]);

							$arrProductp = deserialize($objGroup->productp);

							if (is_array($arrProductp) && in_array('create', $arrProductp))
							{
								$arrProducts = deserialize($objGroup->products);
								$arrProducts[] = Input::get('id');

								$this->Database->prepare("UPDATE tl_user_group SET products=? WHERE id=?")
									->execute(serialize($arrProducts), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = Input::get('id');
						$this->User->products = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array(Input::get('id'), $root) || (Input::get('act') == 'delete' && !$this->User->hasAccess('delete', 'productp')))
				{
					$this->log('Not enough permissions to '.Input::get('act').' product archive ID "'.Input::get('id').'"', 'tl_product_archive checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if (Input::get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'productp'))
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
					$this->log('Not enough permissions to '.Input::get('act').' product archives', 'tl_product_archive checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}

	/**
	 * Return the edit header button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || count(preg_grep('/^tl_product_archive::/', $this->User->alexf)) > 0) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
	}


	/**
	 * Return the copy archive button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyArchive($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'productp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
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
	public function deleteArchive($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'productp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '_.svg', $icon)).' ';
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


	/**
	 * Schedule a product feed update
	 *
	 * This method is triggered when a single product archive or multiple product
	 * archives are modified (edit/editAll).
	 * @param \DataContainer
	 */
	public function scheduleUpdate(DataContainer $dc)
	{
		// Return if there is no ID
		if (!$dc->id)
		{
			return;
		}

		// Store the ID in the session
		$session = $this->Session->get('product_feed_updater');
		$session[] = $dc->id;
		$this->Session->set('product_feed_updater', array_unique($session));
	}
}
