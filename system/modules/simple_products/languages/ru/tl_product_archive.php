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

$strTable = 'tl_product_archive';

/**
 * Global operations
 */
$GLOBALS['TL_LANG'][$strTable]['categories'] = array('Категории', 'Добавить или изменить категории товаров.');
$GLOBALS['TL_LANG'][$strTable]['types'] = array('Типы товаров', 'Добавление или изменение типов товаров.');
$GLOBALS['TL_LANG'][$strTable]['settings'] = array('Настройки', 'Изменение настроек каталога товаров.');

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['tstamp'] = array('Дата изменения', 'Дата и время последнего изменения');
$GLOBALS['TL_LANG'][$strTable]['jumpTo'] = array('Страница переадресации', 'Пожалуйста, выберите страницу, на которую будут перенаправлены посетители при нажатии на статью.');
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Название', 'Пожалуйста, введите имя архива.');
$GLOBALS['TL_LANG'][$strTable]['alias'] = array('Алиас', 'Псевдоним необходим для создания URL-адреса.');
$GLOBALS['TL_LANG'][$strTable]['protected'] = array('Защита архива', 'Показывать товары из этого архива только определенным интерфейсным группам.');
$GLOBALS['TL_LANG'][$strTable]['groups'] = array('Разрешенные группы', 'Только эти группы пользователей могут просматривать товары из этого архива.');
$GLOBALS['TL_LANG'][$strTable]['allowComments'] = array('Включить комментарии', 'Разрешить посетителям комментировать товары.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['new'] = array('Новый архив', 'Создать новый архив');
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Управление архивом', 'Архив ID %s редактировать');
$GLOBALS['TL_LANG'][$strTable]['editheader'] = array('Изменить настройки архива', 'Настройки архива ID %s редактировать');
$GLOBALS['TL_LANG'][$strTable]['copy'] = array('Дублировать архив', 'Архив ID %s дублировать.');
$GLOBALS['TL_LANG'][$strTable]['cut'] = array('Переместить архив', 'Архив ID %s переместить');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Удалить архив', 'Архив ID %s удалить');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Детали архива', 'Детали архива ID %s');
$GLOBALS['TL_LANG'][$strTable]['pasteafter'] = array('Вставить после архива', 'После архива ID %s вставить');
$GLOBALS['TL_LANG'][$strTable]['pasteinto'] = array('Вставить в архив', 'Добавить в архив ID %s');


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['title_legend'] = 'Заголовок и переадресация';
$GLOBALS['TL_LANG'][$strTable]['protected_legend'] = 'Защита доступа';
$GLOBALS['TL_LANG'][$strTable]['comments_legend'] = 'Комментарии';
