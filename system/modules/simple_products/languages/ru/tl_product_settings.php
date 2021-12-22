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

$strTable = 'tl_product_settings';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['tax'] = array('НДС', 'Пожалуйста, введите налоговую ставку вашей страны.');
$GLOBALS['TL_LANG'][$strTable]['tax_reduced'] = array('НДС (уменьшенный)', 'При необходимости вы можете ввести сниженную ставку налога здесь.');

$GLOBALS['TL_LANG'][$strTable]['noprice'] = array('Товары без цены', 'Выберите, что должно отображаться, если цена товара не указана.');
$GLOBALS['TL_LANG'][$strTable]['country'] = array('Страна', 'Пожалуйста, выберите свою страну.');
$GLOBALS['TL_LANG'][$strTable]['currency'] = array('Валюта', 'Выберите здесь только то, что вы хотите использовать валюту, которая не соответствует указанной выше стране.');
$GLOBALS['TL_LANG'][$strTable]['currency_sign'] = array('Знак валюты', 'По возможности используются символы валют, такие как - € вместо EUR, $ вместо USD, £ вместо GBP');
$GLOBALS['TL_LANG'][$strTable]['currency_prefix'] = array('Валюта перед ценой', 'Если выбран этот параметр, сначала отображается знак валюты, а затем цена.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['show_tax']	= array('Показать налог', 'Включить налоги');

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['config_legend']	= 'Конфигурация';
$GLOBALS['TL_LANG'][$strTable]['price_legend']	= 'Настройки цен';
$GLOBALS['TL_LANG'][$strTable]['tax_legend']	= 'Налоговая ставка';
