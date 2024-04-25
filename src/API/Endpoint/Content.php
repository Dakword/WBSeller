<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use Dakword\WBSeller\API\Endpoint\Subpoint\News;
use Dakword\WBSeller\API\Endpoint\Subpoint\Tags;
use Dakword\WBSeller\API\Endpoint\Subpoint\Trash;
use InvalidArgumentException;

class Content extends AbstractEndpoint
{

    /**
     * Сервис для получения новостей с портала продавцов.
     * 
     * @return News
     */
    public function News(): News
    {
        return new News($this);
    }

    /**
     * Сервис для работы с тегами КТ.
     * Теги предназначены для быстрого поиска КТ в вашем лк.
     * 
     * @return Tags
     */
    public function Tags(): Tags
    {
        return new Tags($this);
    }

    /**
     * Сервис для работы с корзиной.
     * 
     * @return Tags
     */
    public function Trash(): Trash
    {
        return new Trash($this);
    }

    public function __call($method, $parameters)
    {
        if(method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $parameters);
        }
        throw new InvalidArgumentException('Magic request methods not exists');
    }

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
     * За раз можно создать 100 КТ по 30 вариантов товара (НМ) в каждой.
     * 
     * @param array $cards  Массив КТ [ [
     *                        subjectID: int, variants: [
     *                           {
     *                              vendorCode: string, title: string, description: string, brand: string,
     *                              dimensions: object, characteristics: [object, ...], sizes: [object, ...]
     *                           }, ...
     *                        ] ], ...
     *                      ] 
     * 
     * @return object {
     * 	    data: null,
     * 	    error: bool, errorText: string, additionalErrors: object
     * }
     * 
     * @throws InvalidArgumentException
     */
    public function createCards(array $cards): object
    {
        $maxCountCards = 100;
        if (count($cards) > $maxCountCards) {
            throw new InvalidArgumentException("Превышение максимального количества КТ: {$maxCountCards}");
        }
        return $this->postRequest('/content/v2/cards/upload', $cards);
    }

    /**
     * Создание КТ
     * 
     * Создание карточки товара происходит асинхронно, при отправке запроса на создание КТ ваш запрос становится
     * в очередь на создание КТ.
     * ПРИМЕЧАНИЕ: Карточка товара считается созданной, если успешно создалась хотя бы одна НМ.
     * ВАЖНО: Если во время обработки запроса в очереди выявляются ошибки, то НМ считается ошибочной.
     * Если запрос на создание прошел успешно, а карточка не создалась, то необходимо в первую очередь проверить
     * наличие карточки в методе cards/error/list. Если карточка попала в ответ к этому методу, то необходимо
     * исправить описанные ошибки в запросе на создание карточки и отправить его повторно.
     * 
     * @param array $card  [ 
     *                        subjectID: int, variants: [
     *                           {
     *                              vendorCode: string, title: string, description: string, brand: string,
     *                              dimensions: object, characteristics: [object, ...], sizes: [object, ...]
     *                           }, ...
     *                        ] 
     *                      ] 
     * 
     * @return object {
     * 	    data: null,
     * 	    error: bool, errorText: string, additionalErrors: object
     * }
     */
    public function createCard(array $card): object
    {
        return $this->createCards([$card]);
    }

    /**
     * Редактирование КТ
     * 
     * Редактирование КТ происходит асинхронно, после отправки запрос становится в очередь на обработку.
     * Важно: Баркоды редактированию/удалению не подлежат. Добавить баркод к уже существующему можно.
     * photos, video и tags в запросе передавать не обязательно, редактирование и удаление этих структур
     * данным методом не предусмотрено.
     * Если запрос прошел успешно, но какие-то карточки не изменились, значит были допущены ошибки -
     * запросите Список несозданных НМ с ошибками (метод cards/error/list) с описанием ошибок.
     * В одном запросе можно отредактировать максимум 3000 номенклатур (nmID). Максимальный размер запроса 10 Мб.
     * Габариты товаров можно указать только в сантиметрах.
     * 
     * Для успешного обновления карточки рекомендуем Вам придерживаться следующего порядка действий:
     * 1. Сначала существующую карточку необходимо запросить методом get/card/full.
     * 2. Забираем из ответа данные карточки, до поля createdAt.
     * 3. Данные помещаем в массив.
     * 4. В этом массиве вносим необходимые изменения и отправляем его в cards/update.
     * 
     * @param array $cards [
     *      [ 
     *          imtID: integer, nmID: integer, vendorCode: string, ...
     *          characteristics: [ object, object, ...],
     *          sizes: [ object, object, ...]
     *      ], ...
     *  ]
     * 
     * @return object {
     *      data: null,
     *      error: bool, errorText: string, additionalErrors: string
     * }
     * 
     * @throws InvalidArgumentException Превышение максимального количества номенклатур
     */
    public function updateCards(array $cards): object
    {
        $maxCount = 3000;
        if (count($cards) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества номенклатур: {$maxCount}");
        }
        return $this->postRequest('/content/v2/cards/update', $cards);
    }

    /**
     * Редактирование 1 КТ
     * 
     * Редактирование КТ происходит асинхронно, после отправки запрос становится в очередь на обработку.
     * Важно: Баркоды редактированию/удалению не подлежат. Добавить баркод к уже существующему можно.
     * photos, video и tags в запросе передавать не обязательно, редактирование и удаление этих структур
     * данным методом не предусмотрено.
     * Если запрос прошел успешно, а информация в карточке не обновилась, значит были допущены ошибки
     * и карточка попала в Список несозданных НМ с ошибками (метод cards/error/list) с описанием ошибок.
     * Необходимо исправить ошибки в запросе и отправить его повторно.
     * 
     * Для успешного обновления карточки рекомендуем Вам придерживаться следующего порядка действий:
     * 1. Сначала существующую карточку необходимо запросить методом get/card/full.
     * 2. Забираем из ответа данные карточки, до поля createdAt.
     * 3. Данные помещаем в массив.
     * 4. В этом массиве вносим необходимые изменения и отправляем его в cards/update.
     * 
     * @param array $card [ 
     *      imtID: integer, nmID: integer, vendorCode: string, ...
     *      characteristics: [ object, object, ...],
     *      sizes: [ object, object, ...]
     *  ]
     * 
     * @return object {
     *      data: null,
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function updateCard(array $card): object
    {
        return $this->postRequest('/content/v2/cards/update', [$card]);
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
     * @param int    $imtID imtID КТ, к которой добавляется НМ
     * @param array  $cards Массив НМ которые хотим добавить к КТ [
     * 		                  { vendorCode: string, characteristics: [ object, object, ...], sizes: [ object, object, ...] }, ...
     *                      ]
     * 
     * @return object {
     *      data: null,
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function addCardNomenclature(string $imtID, array $cards)
    {
        return $this->postRequest('/content/v2/cards/upload/add', [
            'imtID' => $imtID,
            'cardsToAdd' => $cards,
        ]);
    }

    /**
     * Объединение НМ
     * 
     * Метод позволяет объединить номенклатуры (nmID) под одним imtID.
     * Объединить можно только номенклатуры с одинаковыми предметами.
     * В одной КТ (под одним imtID) не может быть больше 30 номенклатур (nmID).
     * 
     * @param int   $targetImt imtID, под которым необходимо объединить НМ
     * @param array $nmIds     nmID, которые необходимо объединить
     * 
     * @return object {
     *      data: { },
     *      error: bool, errorText: string, additionalErrors: string
     * }
     * 
     * @throws InvalidArgumentException Превышение максимального количества номенклатур
     */
    public function moveNms(int $targetImt, array $nmIds): object
    {
        $maxCount = 30;
        if (count($nmIds) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества номенклатур: {$maxCount}");
        }
        return $this->postRequest('/content/v2/cards/moveNm', [
            'targetIMT' => $targetImt,
            'nmIDs' => $nmIds,
        ]);
    }

    /**
     * Разъединение НМ
     * 
     * Метод позволяет отсоединить номенклатуры (nmID) от карточки товара.
     * 
     * @param array $nmIds nmID, которые необходимо отсоединить (не более 30)
     * 
     * @return object {
     *      data: { },
     *      error: bool, errorText: string, additionalErrors: string
     * }
     * 
     * @throws InvalidArgumentException Превышение максимального количества номенклатур
     */
    public function removeNms(array $nmIds): object
    {
        $maxCount = 30;
        if (count($nmIds) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества номенклатур: {$maxCount}");
        }
        return $this->postRequest('/content/v1/cards/moveNm', [
            'nmIDs' => $nmIds,
        ]);
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
        return $this->postRequest('/content/v2/barcodes', [
            'count' => $count,
        ]);
    }
    
    /**
     * Получить список НМ по фильтру
     * 
     * Карточки, находящиеся в корзине, в ответе метода не выдаются
     * 
     * @param string $textSearch Поиск по артикулу продавца, артикулу WB
     * @param int    $limit      Количество запрашиваемых КТ
     * @param string $updatedAt  Время обновления последней КТ из предыдущего ответа на запрос списка КТ
     * @param int    $nmId       Номенклатура последней КТ из предыдущего ответа на запрос списка КТ
     * @param bool   $ascending  Направление сортировки по updatedAt. true - по возрастанию, false - по убыванию.
     * @param int    $withPhoto  -1 - Выдать все КТ
     *                            0 - Выдать КТ без фото
     *                            1 - Выдать КТ с фото
     * @param array  $tagIDs     Поиск по id тегов
     * 
     * @return object {
     *     cards: [ object, object, ... ],
     *     cursor: {updatedAt: string, nmID: int, total: int}
     * }
     * 
     * @throws InvalidArgumentException
     */
    public function getCardsList(
        string $textSearch = '', int $limit = 100, string $updatedAt = '', int $nmId = 0,
        bool $ascending = false, int $withPhoto = -1, array $objectIDs = [], array $brands = [], array $tagIDs = [], int $imtID = 0,
        bool $allowedCategoriesOnly = false): object
    {
        $maxCount = 100;
        if ($limit > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрошенных карточек: {$maxCount}");
        }
        if (!in_array($withPhoto, [-1, 0, 1])) {
            throw new InvalidArgumentException("Недопустимое значение параметра withPhoto: {$withPhoto}");
        }
        return $this->postRequest('/content/v2/get/cards/list?locale=' . (getenv('WBSELLER_LOCALE')?:'ru'), [
            'settings' => [
                'cursor' => array_merge(
                    ['limit' => $limit],
                    ($updatedAt && $nmId) ? ['updatedAt' => $updatedAt, 'nmID' => $nmId] : []
                ),
                'filter' => array_merge(
                    ['withPhoto' => $withPhoto],
                    ['allowedCategoriesOnly' => $allowedCategoriesOnly],
                    $textSearch ? ['textSearch' => $textSearch] : [],
                    $objectIDs ? ['objectIDs' => $objectIDs] : [],
                    $brands ? ['brands' => $brands] : [],
                    $tagIDs ? ['tagIDs' => $tagIDs] : [],
                    $imtID ? ['imtID' => $imtID] : [],
                ),
                'sort' => [
                    'ascending' => $ascending,
                ]
            ]
        ]);
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
        return $this->getRequest('/content/v2/cards/error/list', ['locale' => getenv('WBSELLER_LOCALE')?:'ru']);
    }

    /**
     * Получение КТ по артикулу продавца
     * (Перенаправление на метод getCardsList "Получить список НМ по фильтру")
     * 
     * Метод позволяет получить полную информацию по КТ.
     * Карточки, находящиеся в корзине, в ответе метода не выдаются.
     * 
     * @param string $vendorCode Артикул продавца
     * 
     * @return object {
     *      data: {
     *          cards: [ object, object, ... ],
     *          cursor: {updatedAt: string, nmID: int, total: int}
     *      },
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getCardByVendorCode($vendorCode): object
    {
        return $this->getCardsList($vendorCode);
    }

    /**
     * Получение КТ по артикулу WB
     * (Перенаправление на метод getCardsList "Получить список НМ по фильтру")
     * 
     * Метод позволяет получить полную информацию по КТ.
     * Карточки, находящиеся в корзине, в ответе метода не выдаются.
     * 
     * @param string $nmId Артикул WB
     * 
     * @return object {
     *      data: {
     *          cards: [ object, object, ... ],
     *          cursor: {updatedAt: string, nmID: int, total: int}
     *      },
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getCardByNmID($nmId): object
    {
        return $this->getCardsList($nmId);
    }

    /**
     * Лимиты по КТ
     * 
     * Метод позволяет получить отдельно бесплатные и платные лимиты продавца на создание карточек товаров
     * 
     * @return object {
     *      data: { freeLimits: int, paidLimits: int },
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getCardsLimits(): object
    {
        return $this->getRequest('/content/v2/cards/limits');
    }

    /**
     * Список предметов
     * 
     * С помощью данного метода можно получить список всех доступных предметов,
     * родительских категорий преметов, и их идентификаторов. 
     * 
     * @param string $name     Поиск по наименованию предмета (Носки), поиск работает по подстроке,
     *                         искать можно на любом из поддерживаемых языков
     * @param int    $parentId Идентификатор родительской категории предмета
     * @param int    $offset   Номер позиции, с которой необходимо получить ответ
     * @param int    $limit    Ограничение по количеству выдваемых предметов
     * 
     * @return object {
     *      data: [ {subjectID: int, subjectName: string, parentID: int,  parentName: striing}, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function searchCategory(string $name = '', int $parentId = 0, int $offset = 0, int $limit = 1_000): object
    {
        return $this->getRequest('/content/v2/object/all', [
            'name' => $name,
            'offset' => $offset,
            'limit' => $limit,
            'parentID' => $parentId,
            'locale' => getenv('WBSELLER_LOCALE')?:'ru',
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
        return $this->getRequest('/content/v2/object/parent/all?locale=' . (getenv('WBSELLER_LOCALE')?:'ru'));
    }

    /**
     * Характеристики для создания КТ для категории товара
     * 
     * С помощью данного метода можно получить список характеристик для определенной категории товаров.
     * 
     * @param string $objectId Идентификатор предмета
     * 
     * @return object {
     *      data: [object, ...],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getCategoryCharacteristics(int $objectId): object
    {
        return $this->getRequest('/content/v2/object/charcs/' . $objectId . '?locale=' . (getenv('WBSELLER_LOCALE')?:'ru'));
    }

    /**
     * Получение значений характеристики
     * 
     * colors       Цвет
     * kinds        Пол
     * countries    Страна производства
     * seasons      Сезон
     * tnved        ТНВЭД код
     * vat          Ставка НДС
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
        $directories = ['colors', 'kinds', 'countries', 'seasons', 'tnved', 'vat'];
        $directory = ltrim(strtolower($name), '/');
        if (!in_array($directory, $directories)) {
            throw new InvalidArgumentException("Неизвестная ссылка на характеристику: {$directory}");
        }
        return $this->getRequest('/content/v2/directory/' . $directory, array_merge(['locale' => getenv('WBSELLER_LOCALE')?:'ru'], $params));
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
     * Ставка НДС
     * 
     * С помощью данного метода можно получить список значений для характеристики Ставка НДС.
     * 
     * @return object {
     *      data: [ string, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function getDirectoryNDS(): object
    {
        return $this->getDirectory('vat');
    }

    /**
     * Поиск значений характеристики "ТНВЭД код"
     * 
     * С помощью данного метода можно получить список ТНВЭД кодов по ID предмета и фильтру по тнвэд коду.
     * 
     * @param string $subjectID Идентификатор предмета
     * @param int    $search    Поиск по ТНВЭД-коду. Работает только в паре с subjectID
     * 
     * @return object {
     *      data: [object, ... ],
     *      error: bool, errorText: string, additionalErrors: string
     * }
     */
    public function searchDirectoryTNVED(int $subjectID, string $search = ''): object
    {
        return $this->getDirectory('tnved', [
            '$subjectID' => $subjectID,
            'search' => $search,
        ]);
    }

    /**
     * Изменить медиафайлы
     * @see https://openapi.wb.ru/content/api/ru/#tag/Mediafajly/paths/~1content~1v3~1media~1save/post
     * 
     * Метод позволяет изменить порядок изображений или удалить медиафайлы с НМ в КТ,
     * а также загрузить изображения в НМ со сторонних ресурсов по URL.
     * Текущие изображения заменяются на переданные в массиве.
     * Если хотя бы одно изображение в запросе не соответствует требованиям к медиафайлам,
     * то даже при коде ответа 200 ни одно изображение не загрузится в КТ.
     * Всё, что передаётся в массиве data полностью заменяет собой содержимое массива photos в КТ.
     * Если Вы добавляете фото к уже имеющимся в КТ, то вместе с новыми передайте в запросе все ссылки на фото и видео,
     * которые уже содержатся в КТ. В противном случае в карточке окажутся только передаваемые фото.
     * 
     * @param string $nmID      Артикул Wildberries
     * @param array  $mediaList Ссылки на изображения в том порядке, в котором мы хотим их увидеть в карточке товара
     * 
     * @return object {data: any, error: bool, errorText: string, additionalErrors: string}
     */
    public function updateMedia(string $nmID, array $mediaList): object
    {
        return $this->postRequest('/content/v3/media/save', [
            'nmID' => $nmID,
            'data' => $mediaList,
        ]);
    }

    /**
     * Добавить медиафайлы
     * @see https://openapi.wb.ru/content/api/ru/#tag/Mediafajly/paths/~1content~1v3~1media~1file/post
     * 
     * Метод позволяет загрузить и добавить медиафайл к НМ в КТ.
     * 
     * @param string $nmID        Артикул Wildberries
     * @param int    $photoNumber Номер медиафайла на загрузку
     * @param string $file        Загружаемый файл
     * 
     * @return object {data: any, error: bool, errorText: string, additionalErrors: string}
     */
    public function uploadMedia(string $nmID, int $photoNumber, string $file): object
    {
        return $this->multipartRequest('/content/v3/media/file', [[
                'name' => 'uploadfile',
                'contents' => $file,
                'filename' => 'image.jpg',
            ]], [
            'X-Nm-Id' => $nmID,
            'X-Photo-Number' => $photoNumber,
        ]);
    }

}
