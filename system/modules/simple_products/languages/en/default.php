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
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['show'] = 'Show';
$GLOBALS['TL_LANG']['MSC']['emptyProductList'] = 'In this range are currently no products available.';
$GLOBALS['TL_LANG']['MSC']['emptyCategoryList'] = 'In diesem Bereich sind keine Unterkategorien vorhanden.';
$GLOBALS['TL_LANG']['MSC']['moreProduct'] = 'Product details';
$GLOBALS['TL_LANG']['MSC']['moreCategory'] = 'View products';
$GLOBALS['TL_LANG']['MSC']['readMoreProduct'] = 'Find out more about the product \'%s\'';
$GLOBALS['TL_LANG']['MSC']['readMoreCategory'] = 'View all products of the category %s';
$GLOBALS['TL_LANG']['MSC']['invalidProduct'] = 'The product does not exist or has been removed.';

$GLOBALS['TL_LANG']['CUR']['CHF'] = 'Fr.';
$GLOBALS['TL_LANG']['CUR']['DKK'] = 'Kr.';
$GLOBALS['TL_LANG']['CUR']['EUR'] = '€';
$GLOBALS['TL_LANG']['CUR']['GBP'] = '£';
$GLOBALS['TL_LANG']['CUR']['JPY'] = '¥';
$GLOBALS['TL_LANG']['CUR']['NOK'] = 'Kr.';
$GLOBALS['TL_LANG']['CUR']['SEK'] = 'Kr.';
$GLOBALS['TL_LANG']['CUR']['USD'] = '$';

$GLOBALS['TL_LANG']['MSC']['product_noprice'] = array
(
	'request'	=> 'On request',
	'none'	=> 'Show no price',
	'free'	=> 'Free'
);

$GLOBALS['TL_LANG']['product']['search'] = array
(
	'asc'	=> 'ascending',
	'desc'	=> 'descending'
);

$GLOBALS['TL_LANG']['product_list']['list_sort']['sort'] = 'Sort by';
$GLOBALS['TL_LANG']['product_list']['list_sort']['sorting'] = 'Relevance';

$GLOBALS['TL_LANG']['product_list']['list_view']['view'] = 'Ansicht';
$GLOBALS['TL_LANG']['product_list']['list_view']['list'] = array('Liste', 'Zur Listenansicht wechseln');
$GLOBALS['TL_LANG']['product_list']['list_view']['gallery'] = array('Galerie', 'Zur Galerieansicht wechseln');

$GLOBALS['TL_LANG']['product_info']['tax'] = 'inkl. MwSt.';
$GLOBALS['TL_LANG']['product_info']['tax_list'] = 'Alle Preise inkl. MwSt.';
$GLOBALS['TL_LANG']['product_info']['tax_small_business'] = 'Endpreis, keine Ausweisung der Mehrwertsteuer gemäß § 19 UStG';
$GLOBALS['TL_LANG']['product_info']['tax_list_small_business'] = 'Die angegebenen Preise sind Endpreise. Aufgrund der Kleinunternehmerregelung gemäß § 19 UStG erfolgt kein Ausweis von Umsatzsteuer.';

$GLOBALS['TL_LANG']['product_request']['captcha'] = 'Sicherheits-Code';
$GLOBALS['TL_LANG']['product_request']['submit'] = 'Anfrage senden';
$GLOBALS['TL_LANG']['product_request']['success'] = 'Vielen Dank für Ihre Anfrage.';

$GLOBALS['TL_LANG']['product_request']['email']['admin_subject'] = '[Neue Produkt Anfrage] {{product_title}}';
$GLOBALS['TL_LANG']['product_request']['email']['admin_text'] = 'Sehr geehrte Damen und Herren,

es wurde eine neue Anfrage zu einem Artikel auf Ihrer Webseite versendet:
{{product_url}}

---

{{comment}}

---

Von {{name}}';
