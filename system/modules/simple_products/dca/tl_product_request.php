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

$strTable = 'tl_product_request';

//$arrFilter = array('status != ?', 'cart');

$GLOBALS['TL_DCA'][$strTable] = array(
	// Config
	'config' => array(
		'dataContainer' => 'Table',
		'ptable' => 'tl_product',
		'closed' => true,
		'enableVersioning' => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array(
		'sorting' => array(
			'mode'							=> 1,
      //'filter'						=> array($arrFilter),
      //'panel_callback'		=> array('cart_filter' => array($strTable, 'generateFilter')),
			'fields' 						=> array('date'),
			'disableGrouping' 	=> false,
			'headerFields' 			=> array('title', 'id'),
			'panelLayout' 			=> 'cart_filter,filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'						  => array('title'),
			'format'						  => '%s',
			'label_callback'   	=> array($strTable, 'listFields')
		),
		'global_operations' => array(
			'all' => array(
				'label' 					=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href' 						=> 'act=select',
				'class' 					=> 'header_edit_all',
				'attributes'			=> 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array(
			'edit' => array(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['edit'],
				'href'						=> 'act=edit',
				'icon'						=> 'edit.svg',
			),
			'delete' => array(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['delete'],
				'href' 						=> 'act=delete',
				'icon' 						=> 'delete.svg',
				'attributes' 			=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array(
				'label'						=> &$GLOBALS['TL_LANG'][$strTable]['show'],
				'href' 						=> 'act=show',
				'icon' 						=> 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'				=> array(),
		'default'							=> '{main_legend},title,date,company,name,member_id;{comment_legend},comment;{author_legend},email,phone,skype'
	),

	// Fields
	'fields' => array(
		'id' => array
		(
			'sql'								=> "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
      'filter'						=> true,
			'foreignKey'				=> 'tl_product.title',
			'sql'								=> "int(10) unsigned NOT NULL default '0'",
			'relation'					=> array('type'=>'belongsTo', 'load'=>'eager')
		),
		'archive' => array
		(
      //'filter'						=> true,
			'options'						=> array('1'=>'Umbscheiden', '2'=>'Dieselzentrum')
		),
		'tstamp' => array
		(
			'sql'								=> "int(10) unsigned NOT NULL default '0'"
		),
		'date' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['date'],
			'default'						=> time(),
			'inputType'					=> 'text',
			'exclude'						=> true,
			'sorting'						=> true,
			'filter'						=> true,
			'flag'							=> 8,
			'eval'							=> array('rgxp'=>'datim', 'datepicker'=>time(), 'tl_class'=>'w50'),
			'sql'								=> "varchar(64) NOT NULL default ''"
		),
		'title' => array(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['title'],
			'search' 						=> true,
			'sorting'						=> true,
			'inputType'					=> 'text',
			'exclude'						=> true,
			'eval'							=> array('maxlength'=>128, 'feEditable'=>true, 'tl_class'=>'w50'),
			'sql'								=> "varchar(128) NOT NULL default ''"
		),
		'comment' => array(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['comment'],
			'search' 						=> true,
			'inputType'					=> 'textarea',
			'exclude'						=> true,
			'eval'							=> array('mandatory'=>true, 'rte'=>'tinyMCE', 'helpwizard'=>true, 'tl_class'=>'clr', 'feEditable'=>true),
			'sql'								=> "text NULL"
		),
		'company' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['company'],
			'exclude'						=> true,
			'search'						=> true,
			'sorting'						=> true,
			'flag'							=> 1,
			'inputType'					=> 'text',
			'eval'							=> array('maxlength'=>255, 'feEditable'=>true, 'tl_class'=>'w50'),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'gender' => array
		(
			'label'						=> &$GLOBALS['TL_LANG'][$strTable]['gender'],
			'exclude'					=> true,
			'inputType'				=> 'select',
			'options'					=> array('male', 'female', 'other'),
			'reference'				=> &$GLOBALS['TL_LANG']['MSC'],
			'eval'						=> array('feEditable'=>true, 'tl_class'=>'w50'),
			'sql'							=> "varchar(32) NOT NULL default ''"
		),
		'name' => array
		(
			'label' 						=> &$GLOBALS['TL_LANG'][$strTable]['name'],
			'inputType' 				=> 'text',
			'search'						=> true,
			'sorting'						=> true,
			'exclude'						=> true,
			'eval'							=> array('maxlength'=>128, 'feEditable'=>true),
			'sql'								=> "varchar(128) NOT NULL default ''"
		),
		'email' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['email'],
			'inputType'					=> 'text',
			'search'						=> true,
			'sorting'						=> true,
			'exclude'						=> true,
			'eval'							=> array('maxlength'=>255, 'minlength'=>5, 'rgxp'=>'email', 'tl_class' => 'w50', 'feEditable'=>true),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'phone' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['phone'],
			'inputType'					=> 'text',
			'search'						=> true,
			'exclude'						=> true,
			'eval'							=> array('maxlength'=>255, 'minlength'=>5, 'rgxp'=>'phone', 'tl_class' => 'w50', 'feEditable'=>true),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'skype' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['skype'],
			'inputType'					=> 'text',
			'search'						=> true,
			'exclude'						=> true,
			'eval'							=> array('maxlength'=>255, 'minlength'=>5, 'tl_class' => 'w50', 'feEditable'=>true),
			'sql'								=> "varchar(255) NOT NULL default ''"
		),
		'privacy' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['privacy'],
			'exclude'						=> true,
			'inputType'					=> 'checkbox',
			'eval'							=> array('mandatory'=>true, 'feEditable'=>true),
			'sql'								=> "char(1) NOT NULL default ''"
		),
		'member_id' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['member_id'],
			'exclude'						=> true,
			'inputType'					=> 'select',
			'foreignKey'				=> "tl_member.CONCAT(firstname,' ', lastname, ' (ID ', id, ')')",
			'eval'							=> array('chosen'=>true, 'doNotCopy'=>true, 'includeBlankOption'=>true, 'tl_class'=>'clr'),
			'sql'								=> "int(10) unsigned NOT NULL default '0'",
			'relation'					=> array('type'=>'belongsTo', 'load'=>'eager')
		),
		'ip' => array
		(
			'label'							=> &$GLOBALS['TL_LANG'][$strTable]['ip'],
			'sql'								=> "varchar(64) NOT NULL default ''"
		)
	)
);

if(TL_MODE == 'FE')
{
  $GLOBALS['TL_DCA'][$strTable]['fields']['name']['eval']['mandatory'] = true;
  $GLOBALS['TL_DCA'][$strTable]['fields']['email']['eval']['mandatory'] = true;
}


class tl_product_request extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct() {
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


  public function generateFilter(DataContainer $dc)
  {

		if (\Input::get('id') > 0) {
			return '';
		}

		$objArchive = $this->Database->prepare("SELECT id, title FROM tl_product_archive")->execute();

		while($objArchive->next())
		{
			$strFilter .= '<option value="'.$objArchive->id.'">'.$objArchive->title.'</option>';
		}

    $filter = '<div class="tl_filter tl_subpanel">';
    $filter .= '<select name="archive" id="archive" class="tl_select"><option value="tl_archive">Produktarchiv</option><option value="tl_archive">---</option>'.$strFilter.'</select>';
    $filter .= '</div>';

		return $filter;
  }


	/**
	 * Renders the an entry field.
	 * @param $rowArray The array with the data of the field.
	 */
	public function listFields($row)
	{

		//Parsing
		$date = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $row['date']);

		if($row['member_id']) {
      $account_type = 'member';
      $objMember = $this->Database->prepare("SELECT email, firstname, lastname, phone, city FROM tl_member WHERE id=?")->execute($row['member_id']);
			$strName = $objMember->firstname.' '.$objMember->lastname;
      $strEmail = $objMember->email;
      $strPhone = $objMember->phone;
      $strGender = $objMember->gender;
      $city = $objMember->city;

			// Get number of user requests
			$intTotal = $this->Database->prepare("SELECT COUNT(*) AS count FROM `tl_product_request` WHERE member_id=?")
				->execute($row['member_id'], 1)
				->count;
		}
		else {
      $strName = $row['name'];
			$strEmail = $row['email'];
      $strPhone = $row['phone'];
      $strGender = $row['gender'];
      $account_type = 'guest';
		}

    $strPhoneSimple = str_replace(array(' ', ')', '('), '', $strPhone);
		if(strpos($strPhoneSimple, '0') === 0)
		{
      $strPhoneSimple = '+49' . substr($strPhoneSimple, 1);
		}

		// Get the archive and product
    $objProduct = $this->Database->prepare("SELECT p.title, p.alias, pa.title AS archive, pa.jumpTo FROM tl_product p LEFT JOIN tl_product_archive pa ON p.pid = pa.id WHERE p.id=?")->execute($row['pid']);

		if (($objTarget = \PageModel::findByPk($objProduct->jumpTo)) !== null)
		{
			$strUrl = \Controller::generateFrontendUrl($objTarget->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/' . $objProduct->alias : '/item/' . $objProduct->alias));
		}

		//HTML
		$strList = '<div class="" style="margin:10px 6px 8px"><strong>' . $strName . '</strong> - ' . $date;

		if($intTotal == 1)
		{
			$strList .= ' - Erste Anfrage';
		}
		elseif($intTotal)
		{
			$strList .= ' - ' . $intTotal . ' Anfragen bis jetzt';
		}

		$strList .= '</div>';
		$strList .= '

<div class="tl_content toggle_select hover-div" style="color:#999;position:relative;">
<div style="font-weight:bold;margin-bottom:5px;"><a href="contao/main.php?do=product&table=tl_product&amp;act=edit&amp;id=' . $row['pid'] . '&amp;rt=' . REQUEST_TOKEN . '" title="Die Veranstaltung im Backend bearbeiten" style="color: #589b0e">' . $objProduct->title . '</a></strong> <a href="' . $strUrl . '" target="_blank" title="Die Veranstaltung im Frontend ansehen" style="background: url(/system/modules/simple_products/assets/external-link.svg) 0px 3px no-repeat;width:30px;background-size:15px;margin-left:6px;">&nbsp;</a> (' . $objProduct->archive . ')</div>

		<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h40' : '') . ' block">'.$row['comment'].'</div>';

		$strList .= '<div class="limit_toggler"><button class="unselectable" data-state="0"><span>...</span></button></div>
</div>';

		$strList .= '<div class="request_info" style="margin:10px 0 0;">';

		if($strPhone) { $strList .= '<span style="margin-right:30px"><strong>Telefon:</strong> <a href="tel:' . $strPhoneSimple . '">' . $strPhone . '</a></span>'; }

		$strList .= '<span><strong>E-Mail:</strong> <a href="mailto:' . $strEmail . '">' . $strEmail . '</a></span>';

		if($row['skype']) { $strList .= '<span style="margin-left:30px"><strong>Skype:</strong> <a href="mailto:' . $row['skype'] . '">' . $row['skype'] . '</a></span>'; }

		$strList .= '</div>';

		return $strList;
	}
}
