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
$GLOBALS['TL_LANG'][$strTable]['id'] = array('Produkt ID', 'Die ID des Produkts.');
$GLOBALS['TL_LANG'][$strTable]['pid'] = array('Elternelement', 'Die ID des übergeordneten Archivs.');
$GLOBALS['TL_LANG'][$strTable]['tstamp'] = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Produktname', 'Bitte geben Sie den Namen des Produktes ein.');
$GLOBALS['TL_LANG'][$strTable]['subtitle'] = array('Untertitel', 'Hier können Sie eine Unterüberschrift für den Produktnamen eingeben.');
$GLOBALS['TL_LANG'][$strTable]['alias'] = array('Produktalias', 'Der Produktalias ist eine eindeutige Referenz, die anstelle der numerischen Produkt-ID aufgerufen werden kann.');
$GLOBALS['TL_LANG'][$strTable]['category'] = array('Kategorien', 'Bitte wählen Sie die Produktkategorien aus.');
$GLOBALS['TL_LANG'][$strTable]['date'] = array('Erscheinungsdatum', 'Bitte geben Sie das Datum gemäß des globalen Datumsformats ein.');
$GLOBALS['TL_LANG'][$strTable]['anr'] = array('Artikelnummer', 'Sie können hier bei Bedarf eine Artikelnummer eintragen');
$GLOBALS['TL_LANG'][$strTable]['new_product'] = array('Neues Produkt', 'Das Produkt als Neu kennzeichen.');
$GLOBALS['TL_LANG'][$strTable]['teaser'] = array('Teasertext', 'Der Teasertext kann in einer Produktliste anstatt des Produkttextes angezeigt werden. Ein "Weiterlesen …"-Link wird automatisch hinzugefügt.');
$GLOBALS['TL_LANG'][$strTable]['listitems'] = array('Produktfeatures', 'Wenn JavaScript deaktiviert ist, speichern Sie unbedingt Ihre Änderungen bevor Sie die Reihenfolge ändern.');
$GLOBALS['TL_LANG'][$strTable]['text'] = array('Produktbeschreibung', 'Hier können Sie den vollständigen Produkttext eingeben.');

$GLOBALS['TL_LANG'][$strTable]['addGallery'] = array('Eine Bildergalerie einfügen', 'Wenn Sie diese Option wählen, wird dem Produkt eine Bildergalerie hinzugefügt.');
$GLOBALS['TL_LANG'][$strTable]['gallerySettings'] = array('Abweichende Bildeinstellungen', 'Die Bildgrößeneinstellungen für Galerien werden in den Contao Einstellungen festgelegt. Wenn Sie diese Option wählen, können Sie eine Ausnahme anlegen.');
$GLOBALS['TL_LANG'][$strTable]['addVideo'] = array('Video hinzufügen', 'Dem Produkt ein Video hinzufügen.');
$GLOBALS['TL_LANG'][$strTable]['video_type'] = array('Plattform', 'Wählen Sie die Video-Plattform aus auf der das Video gehostet wird.');
$GLOBALS['TL_LANG'][$strTable]['video_id'] = array('Video ID', 'Die ID des Videos auf der Plattform des gewählten Anbieters.');
$GLOBALS['TL_LANG'][$strTable]['addEnclosure'] = array('Anlagen hinzufügen', 'Dem Produkt eine oder mehrere Dateien als Download hinzufügen.');
$GLOBALS['TL_LANG'][$strTable]['enclosure'] = array('Anlagen', 'Bitte wählen Sie die Dateien aus, die Sie hinzufügen möchten.');

$GLOBALS['TL_LANG'][$strTable]['source'] = array('Weiterleitungsziel', 'Hier können Sie die Standard-Weiterleitung überschreiben.');
$GLOBALS['TL_LANG'][$strTable]['default'] = array('Standard', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf die Standardseite des Produktarchivs weitergeleitet.');
$GLOBALS['TL_LANG'][$strTable]['internal'] = array('Seite', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf eine Seite weitergeleitet.');
$GLOBALS['TL_LANG'][$strTable]['article'] = array('Artikel', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf einen Artikel weitergeleitet.');
$GLOBALS['TL_LANG'][$strTable]['external'] = array('Externe URL', 'Beim Anklicken des "Weiterlesen …"-Links wird der Besucher auf eine externe Webseite weitergeleitet.');
$GLOBALS['TL_LANG'][$strTable]['jumpTo'] = array('Weiterleitungsseite', 'Bitte wählen Sie die Seite aus, zu der Besucher weitergeleitet werden, wenn sie ein Produkt anklicken.');
$GLOBALS['TL_LANG'][$strTable]['articleId'] = array('Artikel', 'Bitte wählen Sie den Artikel aus, zu der Besucher weitergeleitet werden, wenn Sie ein Produkt anklicken.');

$GLOBALS['TL_LANG'][$strTable]['demo'] = array('Präsentations-Link', 'Falls Sie eine separate Seite zur Demonstration des Produkts angelegt haben, können Sie den entsprechenden Link hier angeben.');
//$GLOBALS['TL_LANG'][$strTable]['none'] = array('Kein Link', 'Erzeugt keinen Link');
//$GLOBALS['TL_LANG'][$strTable]['internal'] = array('Seite', 'Beim Anklicken des Präsentations-Links wird der Besucher auf eine Seite weitergeleitet.');
//$GLOBALS['TL_LANG'][$strTable]['external'] = array('Externe URL', 'Beim Anklicken des Präsentations-Links wird der Besucher auf eine externe Webseite weitergeleitet.');

$GLOBALS['TL_LANG'][$strTable]['noComments'] = array('Kommentare deaktivieren', 'Die Kommentarfunktion für dieses Produkt deaktivieren.');
$GLOBALS['TL_LANG'][$strTable]['featured'] = array('Produkt hervorheben', 'Das Produkt in einer Liste hervorgehobener Produkte anzeigen.');
$GLOBALS['TL_LANG'][$strTable]['noRequest'] = array('Nicht anfragbar', 'Hier können Sie festlegen, dass das Anfrageformular nicht bei diesem Produkt erscheinen soll.');
$GLOBALS['TL_LANG'][$strTable]['published'] = array('Produkt veröffentlichen', 'Das Produkt auf der Webseite anzeigen.');
$GLOBALS['TL_LANG'][$strTable]['start'] = array('Anzeigen ab', 'Das Produkt erst ab diesem Tag auf der Webseite anzeigen.');
$GLOBALS['TL_LANG'][$strTable]['stop'] = array('Anzeigen bis', 'Das Produkt nur bis zu diesem Tag auf der Webseite anzeigen.');

$GLOBALS['TL_LANG'][$strTable]['author'] = array('Kontaktperson', 'Wählen Sie einen Benutzer aus, der speziell für dieses Produkt verantwortlich ist.');
$GLOBALS['TL_LANG'][$strTable]['producer'] = array('Hersteller', 'Sie können hier den Hersteller des Produkts angeben');
$GLOBALS['TL_LANG'][$strTable]['mark'] = array('Marke', 'Hier können Sie die Marke eingeben.');

$GLOBALS['TL_LANG'][$strTable]['type'] = array('Produktart', 'Hier können Sie angeben um was für ein Produkt es sich handelt.');
$GLOBALS['TL_LANG'][$strTable]['color'] = array('Farbe', 'Hier können Sie die Farbe des Produkts angeben.');
$GLOBALS['TL_LANG'][$strTable]['dimension'] = array('Größe', 'Hier können Sie die Maße des Produkts angeben.');
$GLOBALS['TL_LANG'][$strTable]['weight'] = array('Gewicht', 'Hier können Sie das Gewicht des Produkts angeben.');
$GLOBALS['TL_LANG'][$strTable]['amount'] = array('Bestand', 'Sie können hier die verfügbaren Warenbestand (Anzahl) und die Einheit für dieses Produkt angeben.');
$GLOBALS['TL_LANG'][$strTable]['price'] = array('Preis', 'Geben Sie hier den Preis des Produkts an.');
$GLOBALS['TL_LANG'][$strTable]['tax_reduced'] = array('Ermäßigte Mehrwertsteuer', 'Wenn Sie diese Option wählen, wird dem Produkt der ermäßigte Steuersatz zugewiesen.');

$GLOBALS['TL_LANG'][$strTable]['available'] = array('Verfügbarkeit', 'Geben Sie die Verfügbarkeit des Produkts an.');
$GLOBALS['TL_LANG'][$strTable]['available_options'] = array('instant' => 'Auf Lager', 'days' => 'Verfügbar in den nächsten Tagen', 'weeks' => 'Verfügbar in den nächsten Wochen', 'sold_out' => 'Derzeit ausverkauft', 'unavailable' => 'Nicht mehr im Sortiment', 'future' => 'Noch nicht erschienen');
$GLOBALS['TL_LANG'][$strTable]['cssClass'] = array('CSS-Klasse', 'Sie können dem Produkt hier eine oder mehrere CSS-Klassen zuweisen.');

$GLOBALS['TL_LANG'][$strTable]['newsletter'] = array('Newsletter zuweisen', 'Wählen Sie aus welche Newsletter diesem Produkt zugewiesen werden sollen. Um das Abonnement der Newsletter auf der Website anzubieten, müssen Sie auf die Seite des Produktlesers das Modul "Produkt-Newsletter" einbinden.');

if (TL_MODE == 'FE')
{
	$GLOBALS['TL_LANG'][$strTable]['gallery'] = array('Galerie', 'Die Galerie zu diesem Produkt.');
}

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['title_legend'] = 'Name';
$GLOBALS['TL_LANG'][$strTable]['teaser_legend'] = 'Unterüberschrift und Teasertext';
$GLOBALS['TL_LANG'][$strTable]['image_legend']	= 'Teaserbild';
$GLOBALS['TL_LANG'][$strTable]['category_legend'] = 'Produktkategorien';
$GLOBALS['TL_LANG'][$strTable]['info_legend'] = 'Produktinformationen';
$GLOBALS['TL_LANG'][$strTable]['features_legend'] = 'Produktfeatures';
$GLOBALS['TL_LANG'][$strTable]['text_legend'] = 'Produktbeschreibung';
$GLOBALS['TL_LANG'][$strTable]['demo_legend'] = 'Präsentation';
$GLOBALS['TL_LANG'][$strTable]['gallery_legend'] = 'Galerie';
$GLOBALS['TL_LANG'][$strTable]['enclosure_legend'] = 'Anlagen';
$GLOBALS['TL_LANG'][$strTable]['source_legend'] = 'Weiterleitungsziel';
$GLOBALS['TL_LANG'][$strTable]['newsletter_legend'] = 'Newsletter';
$GLOBALS['TL_LANG'][$strTable]['expert_legend'] = 'Experten-Einstellungen';
$GLOBALS['TL_LANG'][$strTable]['publish_legend'] = 'Veröffentlichung';
$GLOBALS['TL_LANG'][$strTable]['price_legend'] = 'Preis und Verfügbarkeit';
$GLOBALS['TL_LANG'][$strTable]['video_legend'] = 'Video';


/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['new'] = array('Neues Produkt', 'Ein neues Produkt hinzufügen');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Produktdetails', 'Die Details des Produkts ID %s anzeigen');
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Produkt bearbeiten', 'Produkt ID %s bearbeiten');
$GLOBALS['TL_LANG'][$strTable]['editheader'] = array('Produkteinstellungen bearbeiten', 'Die Produkteinstellungen bearbeiten');
$GLOBALS['TL_LANG'][$strTable]['copy'] = array('Produkt duplizieren', 'Produkt ID %s duplizieren');
$GLOBALS['TL_LANG'][$strTable]['cut'] = array('Produkt verschieben', 'Produkt ID %s verschieben');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Produkt löschen', 'Produkt ID %s löschen');
$GLOBALS['TL_LANG'][$strTable]['toggle'] = array('Produkt veröffentlichen/unveröffentlichen', 'Produkt ID %s veröffentlichen/unveröffentlichen');
$GLOBALS['TL_LANG'][$strTable]['feature'] = array('Produkt hervorheben/zurücksetzen', 'Produkt ID %s hervorheben/zurücksetzen');

$GLOBALS['TL_LANG'][$strTable]['categories'] = array('Kategorien verwalten', 'Neue Kategorien erstellen oder bestehende bearbeiten.');


/**
 * Labels
 */
$GLOBALS['TL_LANG'][$strTable]['sort_label'] = 'Sortieren';
$GLOBALS['TL_LANG'][$strTable]['sort_label_option'] = 'Sortierung ändern';