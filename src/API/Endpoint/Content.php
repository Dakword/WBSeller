<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use InvalidArgumentException;

class Content extends AbstractEndpoint
{

    /**
     * Создание нескольких КТ
     * 
     * Создание карточки товара происходит асинхронно, при отправке запроса на создание КТ ваш запрос становится
     * в очередь на создание КТ.
     * ПРИМЕЧАНИЕ: Карточка товара считается созданной, если успешно создалась хотя бы одна НМ.
     * ВАЖНО: Если во время обработки запроса в очереди выявляются ошибки, то НМ считается ошибочной.
     * Если запрос на создание прошел успешно, а карточка не создалась, то необходимо в первую очередь проверить
     * наличие карточки в методе cards/error/list. Если карточка попала в ответ к этому методу, то необходимо
     * исправить описанные ошибки в запросе на создание карточки и отправить его повторно.
     * 
     * @param array $cards [ 
     * 	    {vendorCode: string, characteristics: [ object, object, ...], sizes: [ object, object, ...]},
     * 	    ...
     *  ]
     * 
     * @return object {
     * 	    data: any,
     * 	    error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function createCards(array $cards): object
    {
        return $this->request('/content/v1/cards/upload', array_map(fn($card) => [$card], $cards), 'POST');
    }

    /**
     * Создание одной КТ
     * 
     * Создание карточки товара происходит асинхронно, при отправке запроса на создание КТ ваш запрос становится
     * в очередь на создание КТ.
     * ПРИМЕЧАНИЕ: Карточка товара считается созданной, если успешно создалась хотя бы одна НМ.
     * ВАЖНО: Если во время обработки запроса в очереди выявляются ошибки, то НМ считается ошибочной.
     * Если запрос на создание прошел успешно, а карточка не создалась, то необходимо в первую очередь проверить
     * наличие карточки в методе cards/error/list. Если карточка попала в ответ к этому методу, то необходимо
     * исправить описанные ошибки в запросе на создание карточки и отправить его повторно.
     * 
     * @param array $card {
     *      vendorCode: string,
     *      characteristics: [ object, object, ...],
     *      sizes: [ object, object, ...]
     * }
     * 
     * @return object {
     *  	data: any,
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function createCard(array $card): object
    {
        return $this->createCards([$card]);
    }

    /**
     * Редактирование нескольких КТ
     * 
     * Метод позволяет отредактировать несколько карточек за раз.
     * Редактирование КТ происходит асинхронно, после отправки запрос становится в очередь на обработку.
     * Важно: Баркоды (skus) не подлежат удалению или замене. Попытка заменить существующий баркод приведет
     * к добавлению нового баркода к существующему.
     * Номенклатуры, содержащие ошибки, не обновляются и попадают в раздел "Список несозданных НМ с ошибками"
     * с описанием допущенной ошибки. Для того, чтобы убрать НМ из ошибочных, необходимо повторно сделать запрос
     * с исправленными ошибками
     * 
     * Для успешного обновления карточки рекомендуем Вам придерживаться следующего порядка действий:
     * 1. Сначала существующую карточку необходимо запросить методом cards/filter.
     * 2. Забираем из ответа массив data.
     * 3. В этом массиве вносим необходимые изменения и отправляем его в cards/update
     * 
     * @param array $cards [ 
     *      {imtID: integer, nmID: integer, vendorCode: string,
     *       characteristics: [ object, object, ...],
     *       sizes: [ object, object, ...]
     *      }, ...
     *  ]
     * 
     * @return object {
     *      data: any,
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function updateCards(array $cards): object
    {
        return $this->request('/content/v1/cards/update', $cards, 'POST');
    }

    /**
     * Добавление НМ к КТ
     * 
     * Метод позволяет добавить к карточке товара новую номенклатуру.
     * Добавление НМ к КТ происходит асинхронно, после отправки запрос становится в очередь на обработку.
     * Важно: Если после успешной отправки запроса номенклатура не создалась, то необходимо проверить раздел
     * "Список несозданных НМ с ошибками". Для того чтобы убрать НМ из ошибочных, необходимо повторно сделать запрос
     * с исправленными ошибками.
     * 
     * @param string $vendorCode Артикул существующей НМ в КТ
     * @param array  $cards      Массив НМ которые хотим добавить к КТ [
     * 		                       {vendorCode: string, characteristics: [ object, object, ...], sizes: [ object, object, ...]}, ...
     *                           ]
     * 
     * @return object {
     *      data: any,
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function addCardNomenclature(string $vendorCode, array $cards)
    {
        return $this->request('/content/v1/cards/upload/add', [
            'vendorCode' => $vendorCode,
            'cards' => $cards,
        ], 'POST');
    }

    /**
     * Получить список НМ по фильтру (вендор код, баркод, номер номенклатуры) с сортировкой
     * V2
     * 
     * @param string $textSearch Поиск по номеру НМ, баркоду или артикулу товара
     * @param int    $limit      Количество запрашиваемых КТ
     * @param int    $withPhoto  -1 - Показать все КТ
     *                           0 - Показать КТ без фото
     *                           1 - Показать КТ с фото
     * @param string $sortColumn Поле по которому будет сортироваться список КТ (пока что поддерживается только updatedAt)
     * @param bool   $ascending  Направление сортировки. true - по возрастанию, false - по убыванию.
     * @param string $updatedAt  Время обновления последней КТ из предыдущего ответа на запрос списка КТ
     * @param int    $nmId       Номенклатура последней КТ из предыдущего ответа на запрос списка КТ
     * 
     * @return object {
     *      data: {
     *          cards: [ object, object, ... ],
     *          cursor: {updatedAt: string, nmID: int, total: int}
     *      },
     *      error: bool, errorText: string, additionalErrors: string
     * }
     * 
     * @throws InvalidArgumentException
     */
    public function getCardsList(string $textSearch = '', int $limit = 1_000, int $withPhoto = -1, string $sortColumn = 'updateAt', bool $ascending = false, string $updatedAt = '', int $nmId = 0): object
    {
        $maxCount = 1_000;
        if ($limit > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрошенных карточек: {$maxCount}");
        }
        if (!in_array($withPhoto, [-1, 0, 1])) {
            throw new InvalidArgumentException("Недопустимое значение параметра withPhoto: {$withPhoto}");
        }
        if ($sortColumn !== 'updateAt') {
            throw new InvalidArgumentException("Недопустимое поле для сортировки списка карточек: {$sortColumn}. Пока только updatedAt.");
        }
        return $this->request('/content/v1/cards/cursor/list', [
            'sort' => [
                'cursor' => array_merge(
                    ['limit' => $limit],
                    ($updatedAt && $nmId) ? ['updatedAt' => $updatedAt, 'nmID' => $nmId] : []
                ),
                'filter' => [
                    'textSearch' => $textSearch,
                    'withPhoto' => $withPhoto,
                ],
                'sort' => [
                    'sortColumn' => $sortColumn,
                    'ascending' => $ascending,
                ]
            ]
        ], 'POST');
    }

    /**
     * Список несозданных НМ с ошибками
     * 
     * Метод позволяет получить список НМ и список ошибок которые произошли во время создания КТ.
     * Для того чтобы убрать НМ из ошибочных, надо повторно сделать запрос с исправленными ошибками на создание КТ.
     * 
     * @return object {
     *      "data": [ {object: string, vendorCode: string, updatedAt: RFC3336, errors: [ string, ... ]}, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * 	}
     */
    public function getErrorCardsList(): object
    {
        return $this->request('/content/v1/cards/error/list');
    }

    /**
     * Получение КТ по вендор кодам (артикулам)
     * 
     * Метод позволяет получить полную информацию по КТ с помощью вендор кода(-ов) номенклатуры из КТ (артикулов).
     * 
     * @param string|array $vendorCodes Идентификатор или массив идентификаторов НМ поставщика
     * 									(Максимальное количество в запросе 100)
     * 
     * @return object {
     *      data: [ object, object, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     * @throws InvalidArgumentException
     */
    public function getCardsByVendorCodes($vendorCodes): object
    {
        $maxCount = 100;
        $codes = (is_array($vendorCodes) ? $vendorCodes : [$vendorCodes]);
        if (count($codes) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества переданных артикулов: {$maxCount}");
        }
        return $this->request('/content/v1/cards/filter', [
            'vendorCodes' => $codes,
        ], 'POST');
    }

    /**
     * Генерация баркодов
     * 
     * Метод позволяет сгенерировать массив уникальных баркодов для создания размера НМ в КТ.
     * 
     * @param int $count Количество баркодов которые надо сгенерировать, максимальное количество - 5000
     * 
     * @return object {
     *      data: [ string, string, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     * @throws InvalidArgumentException
     */
    public function generateBarcodes(int $count): object
    {
        $maxCount = 5_000;
        if ($count > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрошенных баркодов: {$maxCount}");
        }
        return $this->request('/content/v1/barcodes', [
            'count' => $count,
        ], 'POST');
    }

    /**
     * Категория товаров
     * 
     * С помощью данного метода можно получить список категорий товаров по текстовому фильтру (названию категории).
     * 
     * @param string $name Поиск по названию категории
     * @param int    $top  Количество запрашиваемых значений
     * 
     * @return object {
     *      data: [ {objectName: string, parentName: striing, isVisible: bool}, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function searchCategory(string $name, int $top = 50): object
    {
        return $this->request('/content/v1/object/all', [
            'name' => $name,
            'top' => $top,
        ]);
    }

    /**
     * Родительские категории товаров
     * 
     * С помощью данного метода можно получить список всех родительских категорий товаров.
     * 
     * @return object {
     *      data: [ {name: string, isVisible: bool},	... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getParentCategories(): object
    {
        return $this->request('/content/v1/object/parent/all');
    }

    /**
     * Характеристики для создания КТ для категории товара
     * 
     * С помощью данного метода можно получить список характеристик, которые можно или нужно заполнить при создании КТ
     * для определенной категории товаров.
     * Важно: обязательная к заполнению характеристика при создании карточки любого товара - Предмет.
     * Значение характеристики Предмет соответствует значению параметра objectName в запросе.
     * 
     * @param string $objectName Поиск по наименованию категории
     * 
     * @return object {
     *      data: [
     *          {
     *              objectName: string,	- Наименование подкатегории
     *              name: string,		- Наименование характеристики
     *              required: bool,		- Характеристика обязательна к заполенению
     *              unitName: string,	- Единица имерения (см, гр и т.д.)
     *              maxCount: int,		- Максимальное кол-во значений, которое можно присвоить данной характеристике.
     *                                    Если 0, то нет ограничения.
     *              popular:bool,		- Характеристика популярна у пользователей
     *              charcType: int		- Тип характеристики (1 - строка или массив строк; 4 - число или массив чисел)
     *          }, ...
     *      ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getCategoryCharacteristics(string $objectName): object
    {
        return $this->request('/content/v1/object/characteristics/' . $objectName);
    }

    /**
     * Характеристики для создания КТ по всем подкатегориям
     * 
     * С помощью данного метода можно получить список характеристик которые можно или нужно заполнить при создании КТ
     * в подкатегории определенной родительской категории.
     * 
     * @param string $name Поиск по родительской категории
     * 
     * @return object {
     *      data: [
     *          {
     *              objectName: string,	- Наименование подкатегории
     *              name: string,		- Наименование характеристики
     *              required: bool,		- Характеристика обязательна к заполенению
     *              unitName: string,	- Единица имерения (см, гр и т.д.)
     *              maxCount: int,		- Максимальное кол-во значений, которое можно присвоить данной характеристике.
     *                                    Если 0, то нет ограничения.
     *              popular:bool,		- Характеристика популярна у пользователей
     *              charcType: int		- Тип характеристики (1 - строка или массив строк; 4 - число или массив чисел)
     *          }, ...
     *      ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getCategoriesCharacteristics(string $name): object
    {
        return $this->request('/content/v1/object/characteristics/list/filter', [
            'name' => $name,
        ]);
    }

    /**
     * Получение значений характеристики
     * 
     * colors		Цвет
     * kinds		Пол
     * countries	Страна производства
     * collections	Коллекция
     * seasons		Сезон
     * contents		Комплектация
     * consists		Состав
     * brands		Бренд
     * tnved		ТНВЭД код
     * 
     * @param string $name   Имя характеристики
     * @param array  $params Параметры (для некоторых характеристик)
     * 
     * @return object {
     *      data: [ object, object, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     * @throws InvalidArgumentException
     */
    public function getDirectory(string $name, array $params = []): object
    {
        $directories = ['colors', 'kinds', 'countries', 'collections', 'seasons', 'contents', 'consists', 'brands', 'tnved'];
        $directory = ltrim(strtolower($name), '/');
        if (!in_array($directory, $directories)) {
            throw new InvalidArgumentException("Неизвестная ссылка на характеристику: {$directory}");
        }
        return $this->request('/content/v1/directory/' . $directory, $params);
    }

    /**
     * Получение значений характеристики "Цвет"
     * 
     * @return object {
     *      data: [ {name: string, parentName: string}, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getDirectoryColors(): object
    {
        return $this->getDirectory('colors');
    }

    /**
     * Получение значений характеристики "Пол"
     * 
     * @return object {
     *      data: [ string, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getDirectoryKinds(): object
    {
        return $this->getDirectory('kinds');
    }

    /**
     * Получение значений характеристики "Страна производства"
     * 
     * @return object {
     *      data: [ {name: string, fullName: string }, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getDirectoryCountries(): object
    {
        return $this->getDirectory('countries');
    }

    /**
     * Поиск значений характеристики "Коллекция"
     * 
     * @param string $pattern Вхождение для поиска
     * @param int    $top     Количество запрашиваемых значений (максимум 5000)
     * 
     * @return object {
     *      data: [ {id: int, name: string }, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function searchDirectoryCollections(string $pattern, int $top): object
    {
        return $this->getDirectory('collections', [
            'pattern' => $pattern,
            'top' => $top
        ]);
    }

    /**
     * Получение значений характеристики "Сезон"
     * 
     * @return object {
     *      data: [ string, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getDirectorySeasons(): object
    {
        return $this->getDirectory('seasons');
    }

    /**
     * Поиск значений характеристики "Комплектация"
     * 
     * @param string $pattern Вхождение для поиска по наименованию значения характеристики
     * @param int    $top     Количество запрашиваемых значений (максимум 5000)
     * 
     * @return object {
     *      data: [ {id: int, name: string }, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function searchDirectoryContents(string $pattern, int $top): object
    {
        return $this->getDirectory('contents', [
            'pattern' => $pattern,
            'top' => $top
        ]);
    }

    /**
     * Поиск значений характеристики "Состав"
     * 
     * @param string $pattern Вхождение для поиска по наименованию значения характеристики
     * @param int    $top     Количество запрашиваемых значений (максимум 5000)
     * 
     * @return object {
     *      data: [ {id: int, name: string }, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     * 
     * @throws InvalidArgumentException
     */
    public function searchDirectoryConsists(string $pattern, int $top): object
    {
        $maxCount = 5_000;
        if ($top > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых значений: {$maxCount}");
        }
        return $this->getDirectory('consists', [
            'pattern' => $pattern,
            'top' => $top
        ]);
    }

    /**
     * Поиск значений характеристики "Бренд"
     * 
     * @param string $pattern Вхождение для поиска по наименованию значения характеристики
     * @param int    $top     Количество запрашиваемых значений (максимум 5000)
     * 
     * @return object {
     *      data: [ string, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function searchDirectoryBrands(string $pattern, int $top): object
    {
        $maxCount = 5_000;
        if ($top > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрашиваемых значений: {$maxCount}");
        }
        return $this->getDirectory('brands', [
            'pattern' => $pattern,
            'top' => $top
        ]);
    }

    /**
     * Поиск значений характеристики "ТНВЭД код"
     * 
     * С помощью данного метода можно получить список ТНВЭД кодов по имени категории и фильтру по тнвэд коду
     * 
     * @param string $objectName Наименование категории
     * @param int    $tnvedsLike Поиск по коду ТНВЭД
     * 
     * @return object {
     *      data: [ {subjectName: string, tnvedName: string, description: string, isKiz: bool }, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function searchDirectoryTNVED(string $objectName, string $tnvedsLike = ''): object
    {
        return $this->getDirectory('tnved', [
            'objectName' => $objectName,
            'tnvedsLike' => $tnvedsLike
        ]);
    }

    /**
     * Изменение медиа контента КТ
     * 
     * Метод позволяет изменить порядок изображений или удалить медиафайлы с НМ в КТ,
     * а также загрузить изображения в НМ со сторонних ресурсов по URL.
     * Текущие изображения заменяются на переданные в массиве.
     * 
     * @param string $vendorCode Артикул номенклатуры
     * @param array  $mediaList  Ссылки на изображения в том порядке, в котором мы хотим их увидеть в карточке товара
     * 
     * @return object {data: any, error: bool, errorText: string, additionalErrors: string}
     */
    public function updateMedia(string $vendorCode, array $mediaList): object
    {
        return $this->request('/content/v1/media/save', [
            'vendorCode' => $vendorCode,
            'data' => $mediaList,
        ], 'POST');
    }

    /**
     * Добавление медиа контента в КТ
     * 
     * Метод позволяет загрузить и добавить медиафайл к НМ в КТ.
     * 
     * @param string $vendorCode  Артикул номенклатуры
     * @param int    $photoNumber Номер медиафайла на загрузку
     * @param string $file        Загружаемый файл
     * 
     * @return object {data: any, error: bool, errorText: string, additionalErrors: string}
     */
    public function uploadMedia(string $vendorCode, int $photoNumber, string $file): object
    {
        return $this->request('/content/v1/media/file', [
                [
                    'name' => 'uploadfile',
                    'contents' => $file,
                    'filename' => 'image.jpg',
                ]
            ], 'MULTIPART', [
            'X-Vendor-Code' => $vendorCode,
            'X-Photo-Number' => $photoNumber,
        ]);
    }

}
