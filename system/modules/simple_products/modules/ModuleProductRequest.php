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
 * Run in a custom namespace, so the class can be replaced
 */
namespace SimpleProducts;


class ModuleProductRequest extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_product_request';


	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['product_request'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->request_fields = deserialize($this->request_fields);

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
		}

	  // Get the product item
		if(\Input::get('items'))
		{
			$objProduct = $this->Database->prepare("SELECT id, noRequest FROM tl_product WHERE id=? OR alias=?")->execute(\Input::get('items'), \Input::get('items'));
		}

		if (!$objProduct->id)
		{
			return '';
		}

		// Do not index or cache the page if no product item has been specified
		if (!\Input::get('items') || $objProduct->noRequest)
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		return parent::generate();

	}


 /**
	 * Generate module
	 */
	protected function compile()
	{

    $this->member_id = 0;
    $this->import('FrontendUser', 'User');
		if(FE_USER_LOGGED_IN) { $this->member_id = $this->User->id;}

		$arrFields = array();

		$this->loadLanguageFile('tl_product_request');
    $this->loadDataContainer('tl_product_request');

		// Define all form fields
		if($this->request_fields)
		{
			foreach($this->request_fields AS $strField)
			{
        $arrField = $GLOBALS['TL_DCA']['tl_product_request']['fields'][$strField];
        $strType = $arrField['inputType'];

        if(\Input::post('FORM_SUBMIT') == 'product_request')
				{
        	$strValue = \Input::post($strField);
				}

				if(!$this->member_id || $strField == 'title' || $strField == 'comment' || $strField == 'privacy')
				{
					// Evaluation
	        $arrEval = $arrField['eval'];

					if($strField == 'comment')
					{
            $arrEval['allowHtml'] = true;
            $arrEval['rows'] = 8;
            $arrEval['cols'] = 50;
					}

					$strLabel = $GLOBALS['TL_LANG']['tl_product_request'][$strField];

					$arrFields[$strField] = array(
						'label'			=> $strLabel,
						'name'			=> $strField,
						'value'			=> $strValue,
						'inputType'	=> $strType,
						'eval'			=> $arrEval
					);

					// Set options for fields, e.g. salutation
					if($strType == 'select' || $strTypeReal == 'checkboxWizard' || $strType == 'radio')
					{
						//echo $arrField['flag'];
		        $arrFields[$strField]['options'] = $arrField['options'];
		        $arrFields[$strField]['reference'] = $arrField['reference'];
					}
				}
			}
		}

		// Captcha
    if(!$this->com_disableCaptcha && !$this->member_id)
		{
			$arrFields['captcha'] = array
			(
				'label'		=> &$GLOBALS['TL_LANG']['product_request']['captcha'],
				'name'		=> 'product_request_captcha',
				'inputType'	=> 'captcha',
				'eval'		=> array('mandatory'=>true)
			);
		}

		// Create all form fields
		$formFields = array();
    $arrSet = array();
		$doNotSubmit = false;

		foreach($arrFields as $arrField)
		{
      $strField = $arrField['name'];

			// Check if input type is available
			$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];
			if(!$this->classFileExists($strClass))
			{
				continue;
			}

			// Set fields and define widget
			$arrField['eval']['required'] = $arrField['eval']['mandatory'];
			$objWidget = new $strClass($this->prepareForWidget($arrField, $strField, $arrField['value']));

			// Validate the widget
			if(\Input::post('FORM_SUBMIT') == 'product_request')
			{
				$objWidget->validate();

        $strValue = \Input::post($strField);

				if($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}

				// Prevent database errors
				if($arrField['inputType'] != 'captcha')
				{
					$arrSet[$strField] = $strValue;
				}
			}

			// Add to fields array
			$formFields[] = $objWidget;
		}

		$this->Template->fields = $formFields;
		$this->Template->submit = $GLOBALS['TL_LANG']['product_request']['submit'];
		$this->Template->action = ampersand($this->Environment->request);

		// Confirm message
		if($_SESSION['tl_product_request_added'])
		{
      $this->Template->info = $GLOBALS['TL_LANG']['product_request']['success'];
			$_SESSION['tl_product_request_added'] = false;
		}

		// Sucessfull post
		if(\Input::post('FORM_SUBMIT') == 'product_request' && !$doNotSubmit)
		{
			$_SESSION['tl_product_request_added'] = true;

			// Add new entry
			$this->addEntry($arrSet);

			// Redirect if jumpTo page exists
			if (($jumpTo = \PageModel::findByPk($this->jumpTo)) !== null)
			{
				$strUrl = \Controller::generateFrontendUrl($jumpTo->row());
	      $this->redirect($strUrl);
			}
      // Reload page
			else
			{
				$this->reload();
			}
		}
	}


	/**
	 * Called to add an support entry.
	 */
	protected function addEntry($arrSet)
	{
		// Set additional fields
    $arrSet['tstamp'] = time();
    $arrSet['date'] = time();

		if($this->member_id)
		{
			$arrSet['member_id'] = $this->member_id;
		}
		else
		{
			$arrSet['ip'] = $this->Environment->ip;
		}

		if($arrSet['comment']) {
      // Prevent for cross-site scripting
			$arrSet['comment'] = nl2br_pre(trim(\Input::post('comment')));
		}

	  // Get the product item
    $strItem = \Input::get('items');

		if($strItem) {
			$objProduct = $this->Database->prepare("SELECT id, title, price FROM tl_product WHERE id=? OR alias=?")->execute($strItem, $strItem);
      $arrSet['pid'] = $objProduct->id;
		}

		// Create new ticket
		$insertId = $this->Database->prepare("INSERT INTO tl_product_request %s")->set($arrSet)->execute()->insertId;

		// Notify admin
		if($this->email_admin)
		{
			$arrSettings = array(
				'subject'		=> $this->admin_subject,
				'text'			=> $this->admin_text,
				'email'			=> $this->admin_email
			);

   		$this->notifyForRequest('admin', $arrSet, $arrSettings, $objProduct);
		}

		// Notify user
		if($this->email_user && $arrSet['email'])
		{
			$arrSettings = array(
				'subject'		=> $this->user_subject,
				'text'			=> $this->user_text,
				'email'			=> $arrSet['email']
			);

   		$this->notifyForRequest('user', $arrSet, $arrSettings, $objProduct);
		}
	}

	/**
	 * Notify the admin for new tickets
	 *
	 * @param \CommentsModel $objComment
	 */
	public static function notifyForRequest($type, $arrSet, $arrSettings, $objProduct)
	{
		// Prepare the URL
		$strFrontendUrl = \Idna::decode(\Environment::get('base')) . \Environment::get('request');
    //$strBackendUrl = '<a href="'.$this->Environment->base . 'contao/main.php?do=event_cart&table=tl_event_cart_item&id=' . $cart_id.'">'.$this->Environment->base . 'contao/main.php?do=event_cart&table=tl_event_cart_item&id=' . $cart_id.'</a>';

		if($arrSet['member_id'])
		{
      $objMember = \Database::getInstance()->prepare("SELECT email, gender, firstname, lastname, phone, company FROM tl_member WHERE id=?")->execute($arrSet['member_id']);
      $strEmail = $objMember->email;
      $strName = $objMember->firstname.' '.$objMember->lastname;
      $strCompany = $objMember->company;
      $strPhone = $objMember->phone;
      $strGender = $objMember->gender;
      $strSkype = $arrSet['skype'];
		}
		else
		{
			$strEmail = $arrSet['email'];
      $strName = $arrSet['name'];
      $strCompany = $arrSet['company'];
      $strPhone = $arrSet['phone'];
      $strGender = $arrSet['gender'];
      $strSkype = $arrSet['skype'];
		}

    $strGender = $GLOBALS['TL_LANG']['MSC'][$arrSet['gender']];

		$objEmail = new \Email();

    $strFrom = $GLOBALS['TL_ADMIN_EMAIL'];

		$objEmail->from = $strFrom;
		//$objEmail->fromName = $strFromName;

		// Subject
		if($arrSettings['subject']) { $strSubject = $arrSettings['subject']; }
		else { $strSubject = $GLOBALS['TL_LANG']['product_request']['email']['admin_subject']; }

		$objEmail->subject = str_replace
		(
			array(
        '{{product_title}}',
        '{{product_price}}',
        '{{title}}',
        '{{name}}',
        '{{company}}'
			),
			array(
        $objProduct->title,
        $objProduct->price,
        $arrSet['title'],
				$strName,
				$strCompany
			),
			$strSubject
		);

		// Text
		if($arrSettings['text'])
		{
			$text = $arrSettings['text'];
		}
		else
		{
			$text = $GLOBALS['TL_LANG']['product_request']['email']['admin_text'];
		}

    $text = strip_tags($text);

		$objEmail->text = str_replace(
			array(
				'{{product_url}}',
        '{{product_title}}',
        '{{product_price}}',
        '{{title}}',
        '{{comment}}',
        '{{name}}',
        '{{gender}}',
        '{{email}}',
        '{{company}}',
        '{{phone}}',
        '{{skype}}',
				'<br>',
				'[nbsp]'
			),
			array(
        $strFrontendUrl,
        $objProduct->title,
        $objProduct->price,
        $arrSet['title'],
				$arrSet['comment'],
				$strName,
        $strGender,
        $strEmail,
        $strCompany,
        $strPhone,
        $strSkype,
				'
				',
				' '
			),
			$text
		);

		// Empfänger
    if($arrSettings['email'])
		{
			$arrSendTo = explode(',', $arrSettings['email']);
			foreach($arrSendTo AS $sendTo)
			{
        $objEmail->sendTo($sendTo);
			}
		}
		else
		{
			$objEmail->sendTo($GLOBALS['TL_ADMIN_EMAIL']);
		}
	}
}
