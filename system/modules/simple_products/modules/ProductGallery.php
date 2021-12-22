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


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace SimpleProducts;


class ProductGallery extends \ContentGallery
{

	public function __construct($objTemplate)
	{
		$this->id = $objTemplate->id;
    $this->tstamp = time();

		$this->type = 'gallery product_gallery';

		// Apply product gallery settings
		$this->multiSRC = $objTemplate->multiSRC;
		$this->sortBy = $objTemplate->sortBy;
    $this->orderSRC = $objTemplate->orderSRC;

		//Gallery images per row
    if($GLOBALS['TL_CONFIG']['sp_gal_perRow'] && !$objTemplate->gallerySettings) {
			$strPerRow = $GLOBALS['TL_CONFIG']['sp_gal_perRow'];
		}
		elseif($objTemplate->perRow)
    {
			$strPerRow = $objTemplate->perRow;
		}
    else
    {
			$strPerRow = 3;
		}

		$this->perRow = $strPerRow;

		//Gallery fullsize
    if($GLOBALS['TL_CONFIG']['sp_gal_fullsize'] && !$objTemplate->gallerySettings) {
			$strFullsize = $GLOBALS['TL_CONFIG']['sp_gal_fullsize'];
		}
		elseif($objTemplate->gal_fullsize) {
			$strFullsize = $objTemplate->gal_fullsize;
		}
    else {
			$strFullsize = '';
		}

    $this->fullsize = $strFullsize;

		//Gallery fullsize
    if($GLOBALS['TL_CONFIG']['sp_gal_size'] && !$objTemplate->gallerySettings)
    {
			$strSize = $GLOBALS['TL_CONFIG']['sp_gal_size'];
		}
		elseif($objTemplate->gal_size)
    {
			$strSize = $objTemplate->gal_size;
		}
    else
    {
			$strSize = '';
		}

		$this->size = $strSize;

		$this->imagemargin = $objTemplate->gal_imagemargin;
		$this->numberOfItems = $objTemplate->numberOfItems;
		$this->perPage = $objTemplate->perPage;
	}
}
