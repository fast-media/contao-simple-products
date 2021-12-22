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

$strTable = 'tl_product';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['id'] = array('Product ID', 'The id of the Product.');
$GLOBALS['TL_LANG'][$strTable]['pid'] = array('Parent element', 'Die ID des übergeordneten Archivs.');
$GLOBALS['TL_LANG'][$strTable]['tstamp'] = array('Modification date', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Product name', 'Bitte geben Sie den Namen des Produktes ein.');
$GLOBALS['TL_LANG'][$strTable]['subtitle'] = array('Subheadline', 'Here you can enter a subheadline for the product.');
$GLOBALS['TL_LANG'][$strTable]['alias'] = array('Product alias', 'The product alias is a unique reference to the article which can be called instead of its numeric ID.');
$GLOBALS['TL_LANG'][$strTable]['category'] = array('Categories', 'Bitte wählen Sie die Produktkategorien aus.');
$GLOBALS['TL_LANG'][$strTable]['date'] = array('Release Date', 'Please enter the date according to the global date format.');
$GLOBALS['TL_LANG'][$strTable]['anr'] = array('Item number', 'Sie können hier bei Bedarf eine Artikelnummer eintragen');
$GLOBALS['TL_LANG'][$strTable]['new_product'] = array('New Product', 'Das Produkt als Neu kennzeichen.');
$GLOBALS['TL_LANG'][$strTable]['teaser'] = array('Product teaser', 'The product teaser can be shown in a product list instead of the full article. A "read more …" link will be added automatically.');
$GLOBALS['TL_LANG'][$strTable]['listitems'] = array('Productfeatures', 'Wenn JavaScript deaktiviert ist, speichern Sie unbedingt Ihre Änderungen bevor Sie die Reihenfolge ändern.');
$GLOBALS['TL_LANG'][$strTable]['text'] = array('Productdescription', 'Hier können Sie den vollständigen Produkttext eingeben.');

$GLOBALS['TL_LANG'][$strTable]['addGallery'] = array('Eine Bildergalerie einfügen', 'Wenn Sie diese Option wählen, wird dem Produkt eine Bildergalerie hinzugefügt.');
$GLOBALS['TL_LANG'][$strTable]['gallerySettings'] = array('Abweichende Bildeinstellungen', 'Die Bildgrößeneinstellungen für Galerien werden in den Contao Einstellungen festgelegt. Wenn Sie diese Option wählen, können Sie eine Ausnahme anlegen.');

$GLOBALS['TL_LANG'][$strTable]['addEnclosure'] = array('Add enclosures', 'Add one or more downloadable files to the product item.');
$GLOBALS['TL_LANG'][$strTable]['enclosure'] = array('Enclosures', 'Please choose the files you want to attach to this product.');

$GLOBALS['TL_LANG'][$strTable]['source'] = array('Redirect target', 'Here you can override the default redirect target.');
$GLOBALS['TL_LANG'][$strTable]['default'] = array('Use default', 'By clicking the "read more …" button, visitors will be redirected to the default page of the product archive.');
$GLOBALS['TL_LANG'][$strTable]['internal'] = array('Page', 'By clicking the "read more …" button, visitors will be redirected to a page.');
$GLOBALS['TL_LANG'][$strTable]['article'] = array('Article', 'By clicking the "read more …" button, visitors will be redirected to an article.');
$GLOBALS['TL_LANG'][$strTable]['external'] = array('External URL', 'By clicking the "read more …" button, visitors will be redirected to an external website.');
$GLOBALS['TL_LANG'][$strTable]['jumpTo'] = array('Redirect page', 'Please choose the page to which visitors will be redirected when clicking the product item.');
$GLOBALS['TL_LANG'][$strTable]['articleId'] = array('Article', 'Please choose the article to which visitors will be redirected when clicking the product item.');

$GLOBALS['TL_LANG'][$strTable]['demo'] = array('Präsentations-Link', 'Falls Sie eine separate Seite zur Demonstration des Produkts angelegt haben, können Sie den entsprechenden Link hier angeben.');
//$GLOBALS['TL_LANG'][$strTable]['none'] = array('Kein Link', 'Erzeugt keinen Link');
//$GLOBALS['TL_LANG'][$strTable]['internal'] = array('Seite', 'Beim Anklicken des Präsentations-Links wird der Besucher auf eine Seite weitergeleitet.');
//$GLOBALS['TL_LANG'][$strTable]['external'] = array('Externe URL', 'Beim Anklicken des Präsentations-Links wird der Besucher auf eine externe Webseite weitergeleitet.');
//$GLOBALS['TL_LANG'][$strTable]['jumpTo'] = array('Weiterleitungsseite', 'Bitte wählen Sie die Seite aus, zu der Besucher weitergeleitet werden, wenn sie den Präsentations-Link anklicken.');

$GLOBALS['TL_LANG'][$strTable]['noComments'] = array('Disable comments', 'Do not allow comments for this particular product item.');
$GLOBALS['TL_LANG'][$strTable]['featured'] = array('Feature item', 'Show the product item in a featured product list.');
$GLOBALS['TL_LANG'][$strTable]['noRequest'] = array('Nicht anfragbar', 'Hier können Sie festlegen, dass das Anfrageformular nicht bei diesem Produkt erscheinen soll.');
$GLOBALS['TL_LANG'][$strTable]['published'] = array('Publish product', 'Make the product item publicly visible on the website.');
$GLOBALS['TL_LANG'][$strTable]['start'] = array('Show from', 'Do not show the product item on the website before this day.');
$GLOBALS['TL_LANG'][$strTable]['stop'] = array('Show until', 'Do not show the product item on the website on and after this day.');

$GLOBALS['TL_LANG'][$strTable]['author'] = array('Contact person', 'Wählen Sie einen Benutzer aus, der speziell für dieses Produkt verantwortlich ist.');
$GLOBALS['TL_LANG'][$strTable]['producer'] = array('Manufacturer', 'Sie können hier den Hersteller des Produkts angeben');
$GLOBALS['TL_LANG'][$strTable]['mark'] = array('Brand', 'Hier können Sie die Marke eingeben.');

$GLOBALS['TL_LANG'][$strTable]['type'] = array('Produktart', 'Hier können Sie angeben um was für ein Produkt es sich handelt.');
$GLOBALS['TL_LANG'][$strTable]['color'] = array('Color', 'Hier können Sie die Farbe des Produkts angeben.');
$GLOBALS['TL_LANG'][$strTable]['dimension'] = array('Size', 'Hier können Sie die Maße des Produkts angeben.');
$GLOBALS['TL_LANG'][$strTable]['weight'] = array('Weight', 'Hier können Sie das Gewicht des Produkts angeben.');
$GLOBALS['TL_LANG'][$strTable]['amount'] = array('Stock', 'Sie können hier die verfügbaren Warenbestand (Anzahl) und die Einheit für dieses Produkt angeben.');
$GLOBALS['TL_LANG'][$strTable]['price'] = array('Price', 'Geben Sie hier den Preis des Produkts an.');
$GLOBALS['TL_LANG'][$strTable]['tax_reduced'] = array('Reduced VAT', 'Wenn Sie diese Option wählen, wird dem Produkt der ermäßigte Steuersatz zugewiesen.');

$GLOBALS['TL_LANG'][$strTable]['available'] = array('Availability', 'Geben Sie die Verfügbarkeit des Produkts an.');
$GLOBALS['TL_LANG'][$strTable]['available_options'] = array('instant' => 'Sofort lieferbar', 'days' => 'Lieferbar in 5-8 Tagen', 'weeks' => 'Lieferbar in 2-3 Wochen', 'sold_out' => 'Derzeit ausverkauft', 'unavailable' => 'Nicht mehr im Sortiment', 'future' => 'Noch nicht erschienen');
$GLOBALS['TL_LANG'][$strTable]['cssClass'] = array('CSS class', 'Sie können dem Produkt hier eine oder mehrere CSS-Klassen zuweisen.');

$GLOBALS['TL_LANG'][$strTable]['newsletter'] = array('Newsletter zuweisen', 'Wählen Sie aus welche Newsletter diesem Produkt zugewiesen werden sollen. Um das Abonnement der Newsletter auf der Website anzubieten, müssen Sie auf die Seite des Produktlesers das Modul "Produkt-Newsletter" einbinden.');

if (TL_MODE == 'FE')
{
	$GLOBALS['TL_LANG'][$strTable]['gallery'] = array('Galerie', 'Die Galerie zu diesem Produkt.');
}

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['title_legend'] = 'Name';
$GLOBALS['TL_LANG'][$strTable]['teaser_legend'] = 'Subheadline and teaser';
$GLOBALS['TL_LANG'][$strTable]['image_legend']	= 'Image settings';
$GLOBALS['TL_LANG'][$strTable]['category_legend'] = 'Produkt categories';
$GLOBALS['TL_LANG'][$strTable]['info_legend'] = 'Product informationen';
$GLOBALS['TL_LANG'][$strTable]['features_legend'] = 'Product features';
$GLOBALS['TL_LANG'][$strTable]['text_legend'] = 'Product description';
$GLOBALS['TL_LANG'][$strTable]['demo_legend'] = 'Presentation';
$GLOBALS['TL_LANG'][$strTable]['gallery_legend'] = 'Galerie';
$GLOBALS['TL_LANG'][$strTable]['enclosure_legend'] = 'Enclosures';
$GLOBALS['TL_LANG'][$strTable]['source_legend'] = 'Redirect target';
$GLOBALS['TL_LANG'][$strTable]['newsletter_legend'] = 'Newsletter';
$GLOBALS['TL_LANG'][$strTable]['expert_legend'] = 'Expert settings';
$GLOBALS['TL_LANG'][$strTable]['publish_legend'] = 'Publish settings';
$GLOBALS['TL_LANG'][$strTable]['price_legend'] = 'Preis und Verfügbarkeit';

/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['new'] = array('New product', 'Create a new product item');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Article details', 'Show the details of product item ID %s');
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Edit product', 'Edit product item ID %s');
$GLOBALS['TL_LANG'][$strTable]['editheader'] = array('Produkteinstellungen bearbeiten', 'Die Produkteinstellungen bearbeiten');
$GLOBALS['TL_LANG'][$strTable]['copy'] = array('Duplicate product', 'Duplicate product item ID %s');
$GLOBALS['TL_LANG'][$strTable]['cut'] = array('Move product', 'Move product item ID %s');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Delete product', 'Delete product item ID %s');
$GLOBALS['TL_LANG'][$strTable]['toggle'] = array('Publish/unpublish product', 'Publish/unpublish product item ID %s');
$GLOBALS['TL_LANG'][$strTable]['feature'] = array('Feature/unfeature product', 'Feature/unfeature product item ID %s');

$GLOBALS['TL_LANG'][$strTable]['categories'] = array('Kategorien verwalten', 'Neue Kategorien erstellen oder bestehende bearbeiten.');

/**
 * Labels
 */
$GLOBALS['TL_LANG'][$strTable]['sort_label'] = 'Sortieren';
$GLOBALS['TL_LANG'][$strTable]['sort_label_option'] = 'Sortierung ändern';

