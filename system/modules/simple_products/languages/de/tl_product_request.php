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

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['name'] = array('Name', 'Der Name der Person, die den Kommentar verfasst hat.');
$GLOBALS['TL_LANG'][$strTable]['gender'] = array('Anrede', 'Bitte wählen Sie die Anrede.');
$GLOBALS['TL_LANG'][$strTable]['company'] = array('Firma', 'Der Firmenname der Person, die den Kommentar verfasst hat.');
$GLOBALS['TL_LANG'][$strTable]['email'] = array('E-Mail', 'Die Email-Adresse der Person, die den Kommentar verfasst hat.');
$GLOBALS['TL_LANG'][$strTable]['comment'] = array('Ihre Fragen / Kommentar', 'Der Kommentar.');
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Betreff', 'Das Thema des Beitrags.');
$GLOBALS['TL_LANG'][$strTable]['phone'] = array('Telefon', 'Die Telefonnummer der Person, die den Kommentar verfasst hat.');
$GLOBALS['TL_LANG'][$strTable]['skype'] = array('Skype', 'Skype-Name der Person, die den Kommentar verfasst hat.');

$GLOBALS['TL_LANG'][$strTable]['ip'] = array('IP-Adresse', 'Die Netzwerk-Adresse des Autors.');

$GLOBALS['TL_LANG'][$strTable]['privacy'] = array('Datenschutz', 'Ich stimme zu, dass meine Angaben aus dem Anfrageformular zur Beantwortung meiner Anfrage erhoben und verarbeitet werden. Es erfolgt keine Weitergabe der Daten an Unberechtigte. Hinweis: Sie können Ihre Einwilligung jederzeit per E-Mail widerrufen. Detaillierte Informationen zum Umgang mit Nutzerdaten finden Sie in unserer Datenschutzerklärung');


$GLOBALS['TL_LANG'][$strTable]['date'] = array('Datum', 'Das Datum an dem der Beitrag erstellt wurde.');

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['main_legend'] = 'Allgemeines';
$GLOBALS['TL_LANG'][$strTable]['comment_legend'] = 'Nachricht';
$GLOBALS['TL_LANG'][$strTable]['author_legend'] = 'Autor';

/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Kommentar bearbeiten', 'Kommentar ID %s bearbeiten');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Kommentar löschen', 'Kommentar ID %s löschen');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Kommentar Details', 'Details von Kommentar ID %s anzeigen');
