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

$strTable = 'tl_product';

/**
 * Fields
 */
$GLOBALS['TL_LANG'][$strTable]['id'] = array('Товар ID', 'Идентификатор товара.');
$GLOBALS['TL_LANG'][$strTable]['pid'] = array('Родительский элемент', 'Идентификатор родительского архива.');
$GLOBALS['TL_LANG'][$strTable]['tstamp'] = array('Дата изменения', 'Дата и время последнего изменения');
$GLOBALS['TL_LANG'][$strTable]['title'] = array('Название товара', 'Пожалуйста, введите название товара.');
$GLOBALS['TL_LANG'][$strTable]['subtitle'] = array('Подзаголовок', 'Здесь вы можете ввести подзаголовок для названия товара.');
$GLOBALS['TL_LANG'][$strTable]['alias'] = array('Алиас товара', 'Псевдоним продукта-это уникальная ссылка, которую можно вызвать вместо числового идентификатора товара.');
$GLOBALS['TL_LANG'][$strTable]['category'] = array('Категория', 'Пожалуйста, выберите категория товаров.');
$GLOBALS['TL_LANG'][$strTable]['date'] = array('Дата выпуска', 'Пожалуйста, введите дату в соответствии с глобальным форматом даты.');
$GLOBALS['TL_LANG'][$strTable]['anr'] = array('Артикул', 'При необходимости вы можете ввести номер артикула');
$GLOBALS['TL_LANG'][$strTable]['new_product'] = array('Новый товар', 'Товар как новый.');
$GLOBALS['TL_LANG'][$strTable]['teaser'] = array('Текст тизера', 'Текст тизера может отображаться в списке товаров вместо текста товара. Ссылка "Подробнее ..." будет добавлена ​​автоматически.');
$GLOBALS['TL_LANG'][$strTable]['listitems'] = array('Особенности товара', 'Если JavaScript отключен, обязательно сохраните изменения перед изменением порядка.');
$GLOBALS['TL_LANG'][$strTable]['text'] = array('Описание товара', 'Здесь вы можете ввести полный текст товара.');

$GLOBALS['TL_LANG'][$strTable]['addGallery'] = array('Вставка галереи изображений', 'Если вы выберете эту опцию, к товару будет добавлена ​​галерея изображений.');
$GLOBALS['TL_LANG'][$strTable]['gallerySettings'] = array('Настройки галереи', 'Настройки размера изображения для галерей устанавливаются в настройках Contao. При выборе этого параметра можно создать исключение.');

$GLOBALS['TL_LANG'][$strTable]['addEnclosure'] = array('Добавить файл вложения', 'Добавьте один или несколько файлов в товар для загрузки.');
$GLOBALS['TL_LANG'][$strTable]['enclosure'] = array('Файлы для загрузки', 'Пожалуйста, выберите файлы, которые вы хотите добавить.');

$GLOBALS['TL_LANG'][$strTable]['source'] = array('Переадресация', 'Здесь можно переопределить переадресацию по умолчанию.');
$GLOBALS['TL_LANG'][$strTable]['default'] = array('Стандарт', 'При нажатии на ссылку «Подробнее ...» посетитель перенаправляется на стандартную страницу архива продукта.');
$GLOBALS['TL_LANG'][$strTable]['internal'] = array('Страница', 'При нажатии на ссылку «Подробнее ...» посетитель перенаправляется на страницу.');
$GLOBALS['TL_LANG'][$strTable]['article'] = array('Статья', 'При нажатии на ссылку «Подробнее ...» посетитель перенаправляется на статью.');
$GLOBALS['TL_LANG'][$strTable]['external'] = array('Внешний URL', 'При нажатии на ссылку «Подробнее ...» посетитель перенаправляется на внешний сайт.');
$GLOBALS['TL_LANG'][$strTable]['jumpTo'] = array('Страница переадресации', 'Пожалуйста, выберите страницу, на которую посетители будут перенаправлены при нажатии на товар.');
$GLOBALS['TL_LANG'][$strTable]['articleId'] = array('Статья', 'Пожалуйста, выберите статью, на которую посетители будут перенаправлены при нажатии на товар.');

$GLOBALS['TL_LANG'][$strTable]['demo'] = array('Ссылка на презентацию', 'Если вы создали отдельную страницу для демонстрации товара, вы можете указать соответствующую ссылку здесь.');
//$GLOBALS['TL_LANG'][$strTable]['none'] = array('Kein Link', 'Erzeugt keinen Link');
//$GLOBALS['TL_LANG'][$strTable]['internal'] = array('Seite', 'Beim Anklicken des Präsentations-Links wird der Besucher auf eine Seite weitergeleitet.');
//$GLOBALS['TL_LANG'][$strTable]['external'] = array('Externe URL', 'Beim Anklicken des Präsentations-Links wird der Besucher auf eine externe Webseite weitergeleitet.');

$GLOBALS['TL_LANG'][$strTable]['noComments'] = array('Отключить комментарии', 'Отключить комментирование для этого товара.');
$GLOBALS['TL_LANG'][$strTable]['featured'] = array('Выделить товар', 'Отобразить товар в списке выделенных товаров.');
$GLOBALS['TL_LANG'][$strTable]['noRequest'] = array('Без формы заказа', 'Здесь вы можете указать, что форма запроса не должна появляться в данном товаре.');
$GLOBALS['TL_LANG'][$strTable]['published'] = array('Опубликовать товар', 'Отобразить товар на сайте.');
$GLOBALS['TL_LANG'][$strTable]['start'] = array('Публиковать с', 'Не показывайте товар до этого дня на сайте.');
$GLOBALS['TL_LANG'][$strTable]['stop'] = array('Публиковать до', 'Показывать продукт только до этого дня на сайте.');

$GLOBALS['TL_LANG'][$strTable]['author'] = array('Контакт', 'Выберите пользователя, ответственного за данный товар.');
$GLOBALS['TL_LANG'][$strTable]['producer'] = array('Производитель', 'Здесь можно указать производителя товара');
$GLOBALS['TL_LANG'][$strTable]['mark'] = array('Марка товара', 'Здесь вы можете ввести марку.');

$GLOBALS['TL_LANG'][$strTable]['type'] = array('Тип товара', 'Здесь вы можете указать, что это за товар.');
$GLOBALS['TL_LANG'][$strTable]['color'] = array('Цвет', 'Здесь вы можете указать цвет товара.');
$GLOBALS['TL_LANG'][$strTable]['dimension'] = array('Размер', 'Здесь вы можете указать размеры товара.');
$GLOBALS['TL_LANG'][$strTable]['weight'] = array('Вес', 'Здесь вы можете указать вес товара.');
$GLOBALS['TL_LANG'][$strTable]['amount'] = array('Количество и ед.', 'Здесь можно указать количество доступных товаров и единицы измерения для данного товара.');
$GLOBALS['TL_LANG'][$strTable]['price'] = array('Цена', 'Укажите здесь цену продукта.');
$GLOBALS['TL_LANG'][$strTable]['tax_reduced'] = array('Сниженный НДС', 'Если вы выберете эту опцию, продукту будет предоставлена ​​сниженная налоговая ставка в 6 процентов.');

$GLOBALS['TL_LANG'][$strTable]['available'] = array('Наличие', 'Укажите наличие товара.');
$GLOBALS['TL_LANG'][$strTable]['available_options'] = array('instant' => 'В наличии', 'days' => 'Появится со дня на день', 'weeks' => 'Появится в течение месяца', 'sold_out' => 'Продан', 'unavailable' => 'Отсутствует', 'future' => 'Ожидается поступление');
$GLOBALS['TL_LANG'][$strTable]['cssClass'] = array('CSS-класс', 'Вы можете назначить один или несколько классов CSS для товара здесь.');

$GLOBALS['TL_LANG'][$strTable]['newsletter'] = array('Назначить новостную рассылку', 'Выберите новостную рассылку, которую следует назначить этому продукту. Чтобы предложить подписаться на новостные рассылки на веб-сайте, необходимо включить модуль "Новостная рассылка" на странице читателя продукта.');

if (TL_MODE == 'FE')
{
	$GLOBALS['TL_LANG'][$strTable]['gallery'] = array('Галерея', 'Галерея для этого продукта.');
}

/**
 * Legends
 */
$GLOBALS['TL_LANG'][$strTable]['title_legend'] = 'Название';
$GLOBALS['TL_LANG'][$strTable]['teaser_legend'] = 'Подзаголовок и тизер';
$GLOBALS['TL_LANG'][$strTable]['image_legend']	= 'Тизер изображение';
$GLOBALS['TL_LANG'][$strTable]['category_legend'] = 'Категории товаров';
$GLOBALS['TL_LANG'][$strTable]['info_legend'] = 'Информация о товаре';
$GLOBALS['TL_LANG'][$strTable]['features_legend'] = 'Особенности продукта';
$GLOBALS['TL_LANG'][$strTable]['text_legend'] = 'Описание продукта';
$GLOBALS['TL_LANG'][$strTable]['demo_legend'] = 'Презентация';
$GLOBALS['TL_LANG'][$strTable]['gallery_legend'] = 'Галерея';
$GLOBALS['TL_LANG'][$strTable]['enclosure_legend'] = 'Прикрепить файлы';
$GLOBALS['TL_LANG'][$strTable]['source_legend'] = 'Цель пересылки';
$GLOBALS['TL_LANG'][$strTable]['newsletter_legend'] = 'Новостная рассылка';
$GLOBALS['TL_LANG'][$strTable]['expert_legend'] = 'Экспертные настройки';
$GLOBALS['TL_LANG'][$strTable]['publish_legend'] = 'Публикация';
$GLOBALS['TL_LANG'][$strTable]['price_legend'] = 'Цена и доступность';


/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$strTable]['new'] = array('Новый товар', 'Добавление нового товара');
$GLOBALS['TL_LANG'][$strTable]['show'] = array('Подробнее о товаре', 'Детали товара ID %s показывать');
$GLOBALS['TL_LANG'][$strTable]['edit'] = array('Изменить товар', 'Товар ID %s редактировать');
$GLOBALS['TL_LANG'][$strTable]['editheader'] = array('Настройки товара', 'Изменить настройки товара');
$GLOBALS['TL_LANG'][$strTable]['copy'] = array('Дублировать товар', 'Товар ID %s дублировать');
$GLOBALS['TL_LANG'][$strTable]['cut'] = array('Переместить товар', 'Товар ID %s переместить');
$GLOBALS['TL_LANG'][$strTable]['delete'] = array('Удалить товар', 'Товар ID %s удалить');
$GLOBALS['TL_LANG'][$strTable]['toggle'] = array('Опубликовать товар', 'Товар ID %s опубликовать / отменить публикацию');
$GLOBALS['TL_LANG'][$strTable]['feature'] = array('Популярный товар/обычный', 'Товар ID %s популярный/обычный');

$GLOBALS['TL_LANG'][$strTable]['categories'] = array('Управление категориями', 'Создавать новые категории или редактировать существующие.');


/**
 * Labels
 */
$GLOBALS['TL_LANG'][$strTable]['sort_label'] = 'Сортировать';
$GLOBALS['TL_LANG'][$strTable]['sort_label_option'] = 'Изменить порядок сортировки';

