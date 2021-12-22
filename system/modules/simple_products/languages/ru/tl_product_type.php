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

$strTable = 'tl_product_type';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['tstamp'] = array('Дата изменения', 'Дата и время последнего изменения');
$GLOBALS['TL_LANG'][$strTable]['jumpTo'] = array('Страница переадресации', 'Пожалуйста, выберите страницу, на которую будут перенаправлены посетители при нажатии на сообщение.');
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Название', 'Введите название типа товара.');
$GLOBALS['TL_LANG'][$strTable]['alias'] = array('Алиас', 'Псевдоним типа товара-это уникальная ссылка, определяющая сопоставление продукта с типом товара в базе данных. В шаблоне псевдоним типа товара отображается только при необходимости.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['new'] = array('Новый тип товара', 'Создать новый тип товара');
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Управление типом товара', 'Тип товара ID %s редактировать');
$GLOBALS['TL_LANG'][$strTable]['copy'] = array('Дублировать тип товара', 'Тип товара ID %s дублировать.');
$GLOBALS['TL_LANG'][$strTable]['cut'] = array('Переместить тип товара', 'Тип товара ID %s переместить');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Удалить тип продукта', 'Тип продукта ID %s удалить');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Детали типа товара', 'Детали типа товара ID %s');
$GLOBALS['TL_LANG'][$strTable]['pasteafter'] = array('Вставить после данного типа товара', 'По типу продукта ID %s вставить');
$GLOBALS['TL_LANG'][$strTable]['pasteinto'] = array('Вставить в данный тип товара', 'Для типа продукта ID %s');


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['title_legend']	= 'Название';
