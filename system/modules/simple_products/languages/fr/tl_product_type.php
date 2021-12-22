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
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['tstamp'] = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG'][$strTable]['jumpTo'] = array('Weiterleitungsseite', 'Bitte wählen Sie die Produktleser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Beitrag anklicken.');
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Name', 'Bitte geben Sie einen Namen für die Produktart ein.');
$GLOBALS['TL_LANG'][$strTable]['alias'] = array('Alias', 'Der Alias der Produktart ist eine eindeutige Referenz, die die Zuordnung des Produktes zu einer Produktart in der Datenbank festlegt. Im Template wird der Alias der Produktart nur bei Bedarf angezeigt.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['new'] = array('Neue Produktart', 'Eine neue Produktart anlegen');
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Produktart verwalten', 'Produktart ID %s bearbeiten');
$GLOBALS['TL_LANG'][$strTable]['copy'] = array('Produktart duplizieren', 'Produktart ID %s duplizieren.');
$GLOBALS['TL_LANG'][$strTable]['cut'] = array('Produktart verschieben', 'Produktart ID %s verschieben');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Produktart löschen', 'Produktart ID %s löschen');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Details ansehen', 'Details zu Produktart ID %s');
$GLOBALS['TL_LANG'][$strTable]['pasteafter'] = array('Dannach einfügen', 'Nach Produktart ID %s einfügen');
$GLOBALS['TL_LANG'][$strTable]['pasteinto'] = array('Innerhalb einfügen', 'Füt die Produktart ID %s in');


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['title_legend']	= 'Name';
