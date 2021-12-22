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
 * Global operations
 */
$GLOBALS['TL_LANG'][$strTable]['categories'] = array('Kategorien', 'Produkt-Kategorien hinzufügen oder bearbeiten.');
$GLOBALS['TL_LANG'][$strTable]['types'] = array('Produktarten', 'Produktarten hinzufügen oder bearbeiten.');
$GLOBALS['TL_LANG'][$strTable]['settings'] = array('Einstellungen', 'Einstellungen des Produktkatalogs ändern.');

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['tstamp'] = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG'][$strTable]['jumpTo'] = array('Weiterleitungsseite', 'Bitte wählen Sie die Produktleser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Artikel anklicken.');
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Name', 'Bitte geben Sie einen Namen für das Archiv ein.');
$GLOBALS['TL_LANG'][$strTable]['alias'] = array('Alias', 'Der Alias wird für die Erstellung des URL-Pfads benötigt.');
$GLOBALS['TL_LANG'][$strTable]['protected'] = array('Archiv schützen', 'Produkte aus diesem Archiv nur bestimmten Frontend-Gruppen anzeigen.');
$GLOBALS['TL_LANG'][$strTable]['groups'] = array('Erlaubte Mitgliedergruppen', 'Nur diese Mitgliedergruppen können die Produkte aus diesem Archiv sehen.');
$GLOBALS['TL_LANG'][$strTable]['allowComments'] = array('Kommentare aktivieren', 'Besuchern das Kommentieren von Produkten erlauben.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['new'] = array('Neues Archiv', 'Ein neues Archiv anlegen');
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Archiv verwalten', 'Archiv ID %s bearbeiten');
$GLOBALS['TL_LANG'][$strTable]['editheader'] = array('Archiv-Einstellungen bearbeiten', 'Einstellungen des Archivs ID %s bearbeiten');
$GLOBALS['TL_LANG'][$strTable]['copy'] = array('Archiv duplizieren', 'Archiv ID %s duplizieren.');
$GLOBALS['TL_LANG'][$strTable]['cut'] = array('Archiv verschieben', 'Archiv ID %s verschieben');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Archiv löschen', 'Archiv ID %s löschen');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Details ansehen', 'Details zu Archiv ID %s');
$GLOBALS['TL_LANG'][$strTable]['pasteafter'] = array('Dannach einfügen', 'Nach Archiv ID %s einfügen');
$GLOBALS['TL_LANG'][$strTable]['pasteinto'] = array('Innerhalb einfügen', 'Füt das Archiv ID %s in');


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['title_legend'] = 'Titel und Weiterleitung';
$GLOBALS['TL_LANG'][$strTable]['protected_legend'] = 'Zugriffsschutz';
$GLOBALS['TL_LANG'][$strTable]['comments_legend'] = 'Kommentare';
