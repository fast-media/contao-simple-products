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

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['id'] = array('Kategorie ID', 'Die ID der Kategorie.');
$GLOBALS['TL_LANG'][$strTable]['pid'] = array('Elternelement', 'Die ID des übergeordneten Archivs.');
$GLOBALS['TL_LANG'][$strTable]['tstamp'] = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG'][$strTable]['jumpTo'] = array('Weiterleitungsseite', 'Bitte wählen Sie die Produktleser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Beitrag anklicken.');
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Kategoriename', 'Bitte geben Sie einen Namen für die Kategorie ein.');
$GLOBALS['TL_LANG'][$strTable]['alias'] = array('Alias', 'Der Alias wird für die Erstellung des URL-Pfads benötigt.');

$GLOBALS['TL_LANG'][$strTable]['teaser'] = array('Teasertext', 'Der Teasertext ist später über der Produktliste zu sehen.');

$GLOBALS['TL_LANG'][$strTable]['tax_reduced'] = array('Ermäßigter Steuersatz', 'In dieser Kategorie werden alle Produkte mit dem ermäßigten Steuersatz ausgewiesen. Die Höhe des Steuersatzes wird in der Verwaltung von Simple Products eingestellt.');

$GLOBALS['TL_LANG'][$strTable]['fallback_image'] = array('Platzhalter Bild', 'Dieses Bild wird angezeigt, wenn zu dem jeweiligen Produkt dieser Kategorie kein Bild existiert.');

$GLOBALS['TL_LANG'][$strTable]['cssClass'] = array('CSS-Klasse', 'Sie können der Kategorie hier eine oder mehrere CSS-Klassen zuweisen.');
$GLOBALS['TL_LANG'][$strTable]['featured'] = array('Kategorie hervorheben', 'Die Kategorie in einer Liste hervorgehobener Kategorien anzeigen.');

$GLOBALS['TL_LANG'][$strTable]['published'] = array('Kategorie veröffentlichen', 'Die Kategorie auf der Webseite anzeigen.');
$GLOBALS['TL_LANG'][$strTable]['start'] = array('Anzeigen ab', 'Die Kategorie erst ab diesem Tag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG'][$strTable]['stop'] = array('Anzeigen bis', 'Die Kategorie nur bis zu diesem Tag auf der Webseite anzeigen.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['new'] = array('Neue Kategorie', 'Eine neue Kategorie anlegen');
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Kategorie verwalten', 'Kategorie ID %s bearbeiten');
$GLOBALS['TL_LANG'][$strTable]['copy'] = array('Kategorie duplizieren', 'Kategorie ID %s duplizieren.');
$GLOBALS['TL_LANG'][$strTable]['cut'] = array('Kategorie verschieben', 'Kategorie ID %s verschieben');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Kategorie löschen', 'Kategorie ID %s löschen');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Details ansehen', 'Details zu Kategorie ID %s');
$GLOBALS['TL_LANG'][$strTable]['toggle'] = array('Kategorie veröffentlichen/unveröffentlichen', 'Produkt-Kategorie ID %s veröffentlichen/unveröffentlichen');
$GLOBALS['TL_LANG'][$strTable]['pasteafter'] = array('Dannach einfügen', 'Nach Kategorie ID %s einfügen');
$GLOBALS['TL_LANG'][$strTable]['pasteinto'] = array('Innerhalb einfügen', 'Füt die Kategorie ID %s in');


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['title_legend'] = 'Name und Teaser';
$GLOBALS['TL_LANG'][$strTable]['image_legend'] = 'Teaserbild';
$GLOBALS['TL_LANG'][$strTable]['product_image_legend'] = 'Einstellungen für Produktbilder';
$GLOBALS['TL_LANG'][$strTable]['tax_legend'] = 'Steuer-Einstellungen';
$GLOBALS['TL_LANG'][$strTable]['redirect_legend'] = 'Weiterleitung';
$GLOBALS['TL_LANG'][$strTable]['expert_legend'] = 'Experten-Einstellungen';
$GLOBALS['TL_LANG'][$strTable]['publish_legend'] = 'Veröffentlichung';
