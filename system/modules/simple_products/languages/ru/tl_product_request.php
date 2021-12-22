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

$strTable = 'tl_product_request';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['name'] = array('Имя', 'Имя пользователя, который написал комментарий.');
$GLOBALS['TL_LANG'][$strTable]['gender'] = array('Обращение', 'Пожалуйста, выберите приветствие.');
$GLOBALS['TL_LANG'][$strTable]['company'] = array('Название компании', 'Название компании пользователя, который написал комментарий.');
$GLOBALS['TL_LANG'][$strTable]['email'] = array('E-mail', 'Адрес электронной почты человека, который написал комментарий.');
$GLOBALS['TL_LANG'][$strTable]['comment'] = array('Комментарий', 'комментарий.');
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Заголовок', 'Заголовок коментария');
$GLOBALS['TL_LANG'][$strTable]['phone'] = array('Телефон', 'Номер телефона человека, который написал комментарий.');
$GLOBALS['TL_LANG'][$strTable]['skype'] = array('Skype', 'Skype человека, который написал комментарий.');

$GLOBALS['TL_LANG'][$strTable]['ip'] = array('IP-адрес', 'IP-адрес автора.');

$GLOBALS['TL_LANG'][$strTable]['date'] = array('Дата', 'Дата коментария.');

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['main_legend'] = 'Общие положения';
$GLOBALS['TL_LANG'][$strTable]['comment_legend'] = 'Сообщение';
$GLOBALS['TL_LANG'][$strTable]['author_legend'] = 'Автор';

/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Редактировать комментарий', 'Комментарий ID %s редактировать');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Удалить комментарий', 'Комментарий ID %s удалить');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Детали комментария', 'Детали комментария ID %s показать');
