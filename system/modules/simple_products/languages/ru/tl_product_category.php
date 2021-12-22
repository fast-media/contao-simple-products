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

$strTable = 'tl_product_category';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['id'] = array('Категория ID', 'ID категории.');
$GLOBALS['TL_LANG'][$strTable]['pid'] = array('Родительский элемент', 'ID родительского архива.');
$GLOBALS['TL_LANG'][$strTable]['tstamp'] = array('Дата изменения', 'Дата и время последнего изменения');
$GLOBALS['TL_LANG'][$strTable]['jumpTo'] = array('Страница переадресации', 'Пожалуйста, выберите страницу, на которую будут перенаправлены посетители при нажатии на сообщение.');
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Название категории', 'Пожалуйста, введите название для категории.');
$GLOBALS['TL_LANG'][$strTable]['alias'] = array('Алиас', 'Псевдоним необходим для создания URL-адреса.');

$GLOBALS['TL_LANG'][$strTable]['teaser'] = array('Текст тизера', 'Текст тизера можно увидеть ниже списка товаров.');

$GLOBALS['TL_LANG'][$strTable]['tax_reduced'] = array('Сниженная ставка налога', 'В этой категории указаны все товары со сниженной ставкой налога. Размер налоговой ставки устанавливается в настройках магазина.');

$GLOBALS['TL_LANG'][$strTable]['fallback_image'] = array('Изображение товара по умолчанию', 'Это изображение отображается, если для соответствующего товара этой категории нет изображения.');

$GLOBALS['TL_LANG'][$strTable]['cssClass'] = array('CSS-класс', 'Вы можете назначить один или несколько классов CSS для категории здесь.');
$GLOBALS['TL_LANG'][$strTable]['featured'] = array('Выделить категорию', 'Показать категорию в списке выделенных категорий.');

$GLOBALS['TL_LANG'][$strTable]['published'] = array('Опубликовать категорию', 'Показать категорию на веб-странице.');
$GLOBALS['TL_LANG'][$strTable]['start'] = array('Публиковать с', 'Показывать категорию только с этого дня на сайте.');
$GLOBALS['TL_LANG'][$strTable]['stop'] = array('Публиковать до', 'Показывать категорию только до этого дня на сайте.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['new'] = array('Новая категория', 'Создать новую категорию');
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Редактировать категорию', 'Категория ID %s редактировать');
$GLOBALS['TL_LANG'][$strTable]['copy'] = array('Дублировать категорию', 'Категорию ID %s дублировать.');
$GLOBALS['TL_LANG'][$strTable]['cut'] = array('Переместить категорию', 'Категорию ID %s переместить');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Удалить категорию', 'Категорию ID %s удалить');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Детали категории', 'Сведения о категории ID %s');
$GLOBALS['TL_LANG'][$strTable]['toggle'] = array('Опубликовать категорию', 'Категория товара ID %s опубликовать / отменить публикацию');
$GLOBALS['TL_LANG'][$strTable]['pasteafter'] = array('Вставить после категории', 'Вставить после категории ID %s');
$GLOBALS['TL_LANG'][$strTable]['pasteinto'] = array('Вставить в категорию', 'Вставить в категорию ID %s');


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['title_legend'] = 'Название и тизер';
$GLOBALS['TL_LANG'][$strTable]['image_legend'] = 'Изображение тизера';
$GLOBALS['TL_LANG'][$strTable]['product_image_legend'] = 'Настройки для изображений товаров';
$GLOBALS['TL_LANG'][$strTable]['redirect_legend'] = 'Настройка перенаправления';
$GLOBALS['TL_LANG'][$strTable]['tax_legend'] = 'Настройки управления';
$GLOBALS['TL_LANG'][$strTable]['expert_legend'] = 'Экспертные настройки';
$GLOBALS['TL_LANG'][$strTable]['publish_legend'] = 'Публикация';
