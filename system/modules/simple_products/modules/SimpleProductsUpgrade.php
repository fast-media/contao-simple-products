<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2021 Leo Feyer
 *
 * @package   event_manager
 * @author    Fast & Media | Christian Schmidt <info@fast-end-media.de>
 * @license   Commercial License
 * @copyright Fast & Media 2013-2021 <https://www.fast-end-media.de>
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace SimpleProducts;


class SimpleProductsUpgrade extends \Backend
{

  /**
   * Initialize the controller
   */
  public function __construct()
  {
  	parent::__construct();
  }

	/**
	 * Template
	 * @var string
	 */
	//protected $strTemplate = 'be_event_participant_list';

  /**
   * Implement the commands to run by this batch program
   */
  public function generate()
  {

		if(\Input::get('act') == 'export') {  }

		$this->import('BackendUser', 'User');

		//$this->Template->id = $this->id;

   // $objEvent = $this->Database->prepare("SELECT title, alias FROM tl_calendar_events WHERE id = ?")->execute($this->id);
		//$this->Template->title = $objEvent->alias;
return '
	<div id="tl_buttons">
		<a href="" class="header_back" title="" accesskey="b" onclick="Backend.getScrollOffset()">Zurück</a>
	</div>

	<h2 class="sub_headline">Produktkatalog Hilfe</h2>



	<div class="tl_listing_container simple_products_help">
		<div id="tl_overview">

<div class="product">
	<div class="headline headline_sub">
		<h1>
			<span class="subtitle">[simple_products_extended]</span> Erweiterter Produktkatalog
			<span class="price_sale" style="" title="Preis um 5% reduziert bis 31. Januar">-5%
			</span>
		</h1>
	</div>
	<div class="product_start block">
		<div class="product_picture">
			<figure class="image_container" itemprop="image">
				<img src="https://www.fast-end-media.de/files/gfx/produkte/contao-simple-products-extended.png" width="140" height="158" alt="" title="Erweiterter Produktkatalog">
			</figure>
			<div class="product_price" title="Alter Preis: 119,00 €">
				<span class="price price_reduced" itemprop="price">113,05 €</span>
				<span class="price_obsolete">119,00 €*</span>

				<div class="price_info">Endpreis, keine Ausweisung der Mehrwertsteuer gemäß § 19 UStG</div>
			</div>

			<div class="submit_button submit_cart">
			<a href="https://www.fast-end-media.de/produkte/simple-products-extended/" target="blank" class="tl_submit">Mehr Informationen</a>
			</div>
		</div>
		<div class="product_teaser">
			<div class="teaser" itemprop="description">
				<p>Erweitert den kostenlosen Produktkatalog um viele zusätzliche Funktionen wie z.B. beliebig viele Kategorien, Produktsuche und Filter. Mit Hilfe von Produktarten können Sie im Backend unterschiedliche Felder anlegen.
				</p>
			</div>
			<div class="product_features">
				<div class="ce_list feature_list block">
					<ul>
						<li class="first">Produktsuche
						</li>
						<li>Eigene Felder im Backend je nach Produktart definieren
						</li>
						<li>Unterschiedliche Produktfilter je nach Kategorie
						</li>
						<li>Kategorie-Navigation / Kategorie-Filter
						</li>
						<li>Ähnliche Produkte darstellen
						</li>
						<li>Rabatte je Produkt oder Kategorie oder global einstellbar
						</li>
						<li>Brotkrumen Navigation (Breadcrumb)
						</li>
						<li>Varianten
						</li>
						<li>Übersetzung von Produkten in bis zu 24 Sprachen
						</li>
						<li>Suchmaschinenoptimierung für jedes einzelne Produkt
						</li>
						<li class="last">Unterschiedliche Währungen und Steuern je Land (Coming Soon)
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
	</div>
</div>';

	}
}

