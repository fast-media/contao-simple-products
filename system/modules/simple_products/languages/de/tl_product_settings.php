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

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['show_tax'] = array('Steuern ausweisen', 'Wählen Sie diese Option, wenn Sie die enthaltenen Steuern oder zuzüglichen Steuern anzeigen wollen (Simple Products Shop).');
$GLOBALS['TL_LANG'][$strTable]['tax'] = array('Mehrwertsteuer', 'Bitte tragen Sie den Steuersatz Ihres Landes ein.');
$GLOBALS['TL_LANG'][$strTable]['tax_reduced'] = array('Mehrwertsteuer (ermäßigt)', 'Sie können hier bei Bedarf den ermäßigten Steuersatz eintragen.');

$GLOBALS['TL_LANG'][$strTable]['noprice'] = array('Produkte ohne Preis', 'Wählen Sie aus was angezeigt werden soll, wenn der Preis des Produktes nicht angegeben wurde.');
$GLOBALS['TL_LANG'][$strTable]['country'] = array('Land', 'Bitte wählen Sie Ihr Land aus.');
$GLOBALS['TL_LANG'][$strTable]['currency'] = array('Währung', 'Wählen Sie hier nur etwas aus, wenn Sie eine Währung einsetzen wollen, die nicht dem oben angegeben Land entspricht.');
$GLOBALS['TL_LANG'][$strTable]['currency_sign'] = array('Währungszeichen', 'Wenn möglich werden Währungszeichen eingesetzt wie - € statt EUR, $ statt USD, £ statt GBP');
$GLOBALS['TL_LANG'][$strTable]['currency_prefix'] = array('Währung vor dem Preis', 'Wenn Sie diese Einstellung wählen, wird zuerst die Währung/Währungszeichen und danach der Preis dargestellt.');

/**
 * Buttons
 */


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['config_legend'] = 'Konfiguration';
$GLOBALS['TL_LANG'][$strTable]['price_legend'] = 'Preiseinstellungen';
$GLOBALS['TL_LANG'][$strTable]['tax_legend'] = 'Steuern';
