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

$strTable = 'tl_module';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['product_archive'] = array('Produktarchive', 'Bitte wählen Sie ein oder mehrere Produktarchive.');
$GLOBALS['TL_LANG'][$strTable]['product_category'] = array('Produktkategorien', 'Sie können festlegen, dass das Modul nur bestimmte Kategorien berücksichtigt. Wenn Sie keine Kategorie auswählen, werden alle Kategorien berücksichtigt.');
$GLOBALS['TL_LANG'][$strTable]['category_jumpTo'] = array('Weiterleitungsseite für Kategorien', 'Nur notwendig, wenn Sie in der Produktliste die Kategorien anzeigen wollen. Stellen Sie hier ein auf welche Seite weitergeleitet wird, wenn der Besucher auf eine Kategorie klickt.');
$GLOBALS['TL_LANG'][$strTable]['backlink_jumpTo'] = array('Zurück-Link', 'Wählen Sie hier eine übergeordnete Seite aus, wenn Sie beispielsweise über der Navigation einen Backlink zur Produktübersicht setzen wollen.');
$GLOBALS['TL_LANG'][$strTable]['product_featured'] = array('Hervorgehobene Produkte', 'Hier legen Sie fest, wie hervorgehobene Produkte gehandhabt werden.');
$GLOBALS['TL_LANG'][$strTable]['product_template'] = array('Produkt-Template', 'Hier können Sie das Produkt-Template auswählen.');

$GLOBALS['TL_LANG'][$strTable]['perRow'] = array('Elemente pro Zeile', 'Erfordert die Anpassung Ihrer CSS-Einstellungen. Wenn Sie diese Option wählen, wird der Quelltext so generiert, dass Sie mittels CSS Elemente nebeneinander darstellen könneb. Das letzte Element in einer Zeile erhält die Klasse "last".');

$GLOBALS['TL_LANG'][$strTable]['fallback_image'] = array('Platzhalter Bild', 'Dieses Bild wird angezeigt, wenn zu dem jeweiligen Element kein Bild existiert.');

$GLOBALS['TL_LANG'][$strTable]['product_fields'] = array('Produktfelder auflisten', 'Wählen Sie die Felder und die Reihenfolge aus, wenn Sie viele Felder einfach nur untereinander auflisten wollen.');

$GLOBALS['TL_LANG'][$strTable]['filter_ignore'] = array('Filter ignorieren', 'Wenn Sie diese Option wählen, werden die URL-Parameter zum Filtern ignoriert (Kategorie, Produktsuche).');

$GLOBALS['TL_LANG'][$strTable]['sort_fields'] = array('Standard-Sortierung', 'Wählen Sie aus wie die Ergebnisse sortiert werden sollen. Standardmäßig werden die Ergebnisse nach dem Sortierindex sortiert.');

$GLOBALS['TL_LANG'][$strTable]['show_count'] = array('Anzahl anzeigen', 'Wenn Sie diese Option wählen, wird über der Produktliste die Anzahl der Produkte angezeigt.');
$GLOBALS['TL_LANG'][$strTable]['show_sort'] = array('Sortieren nach (Selectbox)', 'Wenn Sie hier etwas auswählen, wird über der Produktliste eine Selectbox zum Sortieren der Ergebnisse angezeigt.');
$GLOBALS['TL_LANG'][$strTable]['show_limit'] = array('Ergebnisse pro Seite (Selectbox)', 'Wenn Sie hier etwas auswählen, wird über der Produktliste eine Selectbox zum Ändern der Ergebnisse pro Seite angezeigt.');
$GLOBALS['TL_LANG'][$strTable]['show_view'] = array('Anpassung der Darstellung (Auswahlmenü)', 'Wenn Sie diese Option wählen, hat der Besucher Ihrer Website die Möglichkeit über der Produktliste die Darstellung der Liste zu ändern (Galerie/Liste).');

$GLOBALS['TL_LANG'][$strTable]['email_admin']	= array('E-Mail an den Administrator', 'Wählen Sie diese Option um dem Website-Administrator die Anfrage per E-Mail zu senden.');

$GLOBALS['TL_LANG'][$strTable]['changeLevel'] = array('Hierarchie ändern', 'Diese Option ist für eine zusätzliche Navigation sinnvoll. Übergeordnete Kategorien werden ausgeblendet. Es werden nur die Kategorie-Ebenen angezeigt, die sich innerhalb der aktuellen Kategorie befinden.');
$GLOBALS['TL_LANG'][$strTable]['redirectParent'] = array('Hauptkategorie weiterleiten', 'Wählen Sie diese Option, um die Hauptkategorie umzuleiten.');

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['list_legend'] = 'Auflistung';
$GLOBALS['TL_LANG'][$strTable]['view_legend'] = 'Ansichts-Einstellungen im Frontend';
$GLOBALS['TL_LANG'][$strTable]['restriction_legend'] = 'Einschränkungen';
