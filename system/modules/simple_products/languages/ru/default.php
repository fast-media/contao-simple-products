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
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['show'] = 'Шоу';
$GLOBALS['TL_LANG']['MSC']['emptyProductList'] = 'В настоящее время нет товаров, доступных для показа.';
$GLOBALS['TL_LANG']['MSC']['emptyCategoryList'] = 'В этой области нет подкатегорий.';
$GLOBALS['TL_LANG']['MSC']['moreProduct'] = 'Подробнее';
$GLOBALS['TL_LANG']['MSC']['moreCategory'] = 'Список товаров';
$GLOBALS['TL_LANG']['MSC']['readMoreProduct'] = 'Подробнее о товаре \'%s\' узнать';
$GLOBALS['TL_LANG']['MSC']['readMoreCategory'] = 'Все товары категории %s смотреть';
$GLOBALS['TL_LANG']['MSC']['invalidProduct'] = 'Товар не существует или был удален.';

$GLOBALS['TL_LANG']['CUR']['CHF'] = 'Fr.';
$GLOBALS['TL_LANG']['CUR']['DKK'] = 'Kr.';
$GLOBALS['TL_LANG']['CUR']['EUR'] = '€';
$GLOBALS['TL_LANG']['CUR']['GBP'] = '£';
$GLOBALS['TL_LANG']['CUR']['JPY'] = '¥';
$GLOBALS['TL_LANG']['CUR']['NOK'] = 'Kr.';
$GLOBALS['TL_LANG']['CUR']['SEK'] = 'Kr.';
$GLOBALS['TL_LANG']['CUR']['USD'] = '$';
$GLOBALS['TL_LANG']['CUR']['RUB'] = 'руб.';

$GLOBALS['TL_LANG']['MSC']['product_noprice'] = array
(
  'request'	=> 'По запросу',
  'none'	=> 'Не показывать цену',
	'free'	=> 'Бесплатно'
);

$GLOBALS['TL_LANG']['product']['search'] = array
(
	'asc'	=> 'восходящая',
	'desc'	=> 'нисходящий'
);

$GLOBALS['TL_LANG']['product_list']['list_sort']['sort'] = 'Сортировать по';
$GLOBALS['TL_LANG']['product_list']['list_sort']['sorting'] = 'Релевантности';

$GLOBALS['TL_LANG']['product_list']['list_view']['view'] = 'Вид';
$GLOBALS['TL_LANG']['product_list']['list_view']['list'] = array('Список', 'Переключение в режим списка');
$GLOBALS['TL_LANG']['product_list']['list_view']['gallery'] = array('Галерея', 'Переключение в режим галереи');

$GLOBALS['TL_LANG']['product_info']['tax'] = 'с учетом НДС';
$GLOBALS['TL_LANG']['product_info']['tax_list'] = 'Все цены указаны с учетом НДС.';
$GLOBALS['TL_LANG']['product_info']['tax_small_business'] = 'Окончательная цена, без исключения НДС в соответствии с § 19 USTG';
$GLOBALS['TL_LANG']['product_info']['tax_list_small_business'] = 'Указанные цены являются окончательными. Из-за регулирования малого бизнеса в соответствии с § 19 UStG нет никаких доказательств налога на добавленную стоимость.';

$GLOBALS['TL_LANG']['product_request']['captcha'] = 'Защитный код';
$GLOBALS['TL_LANG']['product_request']['submit'] = 'Отправить запрос';
$GLOBALS['TL_LANG']['product_request']['success'] = 'Спасибо за ваш запрос.';

$GLOBALS['TL_LANG']['product_request']['email']['admin_subject'] = '[Запрос нового продукта] {{product_title}}';
$GLOBALS['TL_LANG']['product_request']['email']['admin_text'] = 'Уважаемые дамы и господа,

Новый запрос был отправлен для статьи на вашем сайте:
{{product_url}}

---

{{comment}}

---

Von {{name}}';
