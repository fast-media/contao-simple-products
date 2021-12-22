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

$strTable = 'tl_settings';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['switch_product'] = array('Komplexe Produktdetails', 'Ermöglicht alle Inhaltselemente innerhalb eines Produkts (ähnlich wie bei Nachrichten und Events).');

$GLOBALS['TL_LANG'][$strTable]['sp_tax_reduced'] = array('Ermäßigten Steuersatz ermöglichen', 'Wählen Sie diese Option nur aus, wenn Sie Produkte mit dem Normal-Steuersatz und dem ermäßigten Steuersatz anbieten. Sie können die Steuersätze anschließend in der Verwaltung von Simple Products festlegen.');
$GLOBALS['TL_LANG'][$strTable]['sp_product_tax'] = array('Steuersatz für jedes Produkt angeben', 'Wählen Sie diese Option nur aus, wenn Sie innerhalb einer Produktkategorie sowohl Produkte mit Normal-Steuersatz als auch Produkte mit ermäßigtem Steuersatz anbieten. ');

$GLOBALS['TL_LANG'][$strTable]['sp_units'] = array('Einheiten', 'Tragen Sie hier Kommagetrennt die Werte ein, die bei der Mengenangabe von Produkten erscheinen sollen.');

$GLOBALS['TL_LANG'][$strTable]['sp_image_size'] = array('Standard Produktbildgröße', 'Hier können Sie die Abmessungen der Produktbilder und den Skalierungsmodus für neue Produkte festlegen.');
$GLOBALS['TL_LANG'][$strTable]['sp_image_fullsize'] = array('Produktbild Großansicht', 'Wählen Sie diese Option damit Bilder von neuen Produkten beim Import oder beim Erstellen automatisch die Großansicht zugewiesen bekommen.');
$GLOBALS['TL_LANG'][$strTable]['sp_gal_size'] = array('Standard Galeriebildgröße', 'Hier können Sie die Abmessungen der Galeriebilder und den Skalierungsmodus für alle Produkte festlegen. Sie können beim Produkt selbst eine Ausnahme festlegen.');
$GLOBALS['TL_LANG'][$strTable]['sp_gal_fullsize'] = array('Galeriebild Großansicht', 'Wählen Sie diese Option damit Galeriebilder von neuen Produkten beim Import oder beim Erstellen automatisch die Großansicht zugewiesen bekommen.');
$GLOBALS['TL_LANG'][$strTable]['sp_gal_perRow'] = array('Vorschaubilder pro Reihe', 'Die Anzahl an Vorschaubildern pro Reihe.');

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['simple_products_backend_legend'] = 'Produktkatalog (Backend-Einstellungen)';
$GLOBALS['TL_LANG'][$strTable]['simple_products_legend'] = 'Produktkatalog';
