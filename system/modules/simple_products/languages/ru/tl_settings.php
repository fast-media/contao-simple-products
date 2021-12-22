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
$GLOBALS['TL_LANG'][$strTable]['switch_product'] = array('Сложные детали птовара', 'Включает все элементы контента в товаре (аналогично новостям и событиям).');

$GLOBALS['TL_LANG'][$strTable]['sp_tax_reduced'] = array('Включить налоги', 'Выберите эту опцию, только если вы предлагаете продукты со стандартной налоговой ставкой и сниженной налоговой ставкой. Затем вы можете установить налоговые ставки в настройках магазина.');
$GLOBALS['TL_LANG'][$strTable]['sp_product_tax'] = array('Указывать налоговую ставку для каждого товара', 'Выберите этот параметр, только если вы предлагаете продукты с обычной налоговой ставкой и пониженной налоговой ставкой товаров в категории товаров. ');

$GLOBALS['TL_LANG'][$strTable]['sp_units'] = array('Единицы измерения товров', 'Укажите единицу измерения товаров шт. кг. и так далее.');

$GLOBALS['TL_LANG'][$strTable]['sp_image_size'] = array('Размер изображения товаров', 'Здесь можно задать размеры изображений товаров и режим масштабирования для новых товаров.');
$GLOBALS['TL_LANG'][$strTable]['sp_image_fullsize'] = array('Большое изображение товара', 'Выберите этот параметр, чтобы при импорте или создании новых товаров изображения автоматически отображались в большом виде.');
$GLOBALS['TL_LANG'][$strTable]['sp_gal_size'] = array('Стандартный размер изображения галереи', 'Здесь можно указать размеры изображений галереи и режим масштабирования для всех товаров. Можно задать исключение для самого товара.');
$GLOBALS['TL_LANG'][$strTable]['sp_gal_fullsize'] = array('Большое изображение галереи товров', 'Выберите этот параметр, чтобы при импорте или создании галереи новые продукты автоматически получали большие изображения.');
$GLOBALS['TL_LANG'][$strTable]['sp_gal_perRow'] = array('Миниатюр в ряду', 'Количество миниатюр в ряду.');

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['simple_products_backend_legend'] = 'Каталог товаров (Настройки Серверной Части)';
$GLOBALS['TL_LANG'][$strTable]['simple_products_legend'] = 'Каталог товаров';
