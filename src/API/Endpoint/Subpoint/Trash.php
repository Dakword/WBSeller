<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Content;
use InvalidArgumentException;

class Trash
{
    private Content $Content;

    public function __construct(Content $Content)
    {
        $this->Content = $Content;
    }

    /**
     * Список НМ, находящихся в корзине
     * 
     * Метод позволяет получить список НМ, которые находятся в корзине по фильтру (баркод (skus),
     * артикул продавца (vendorCode), артикул WB (nmID)) с пагинацией и сортировкой.
     * 
     * Повторять пока total в ответе не станет меньше чем limit в запросе.
     * Это будет означать, что получили все карточки.
     * @see https://openapi.wb.ru/content/api/ru/#tag/Korzina/paths/~1content~1v2~1cards~1trash~1list/post
     * 
     * @param string $textSearch Значение, по которому будет осуществляться поиск
     * @param int    $limit      Количество карточек на странице
     * @param string $trashedAt  Время обновления последней КТ из предыдущего ответа на запрос списка КТ
     * @param int    $nmId       Номенклатура последней КТ из предыдущего ответа на запрос списка КТ
     * @param bool   $ascending  Направление сортировки. true - по возрастанию, false - по убыванию
     *                           Поле, по которому будет сортироваться список - trashedAt
     * @return object {
     *      cards: [object, object, ...],
     *      cursor: { trashedAt: datetime, nmID: int, total: int }
     * }
     * 
     * @throws InvalidArgumentException Превышение максимального количества запрошенных карточек
     */
    public function list(string $textSearch = '', int $limit = 100, string $trashedAt = '', int $nmId = 0, bool $ascending = false)
    {
        $maxLimit = 100;
        if ($limit > $maxLimit) {
            throw new InvalidArgumentException("Превышение максимального количества запрошенных карточек: {$maxLimit}");
        }
        return $this->Content->postRequest('/content/v2/get/cards/trash?locale=' . (getenv('WBSELLER_LOCALE')?:'ru'), [
            'settings' => [
                'cursor' => array_merge(
                    ['limit' => $limit],
                    ($trashedAt && $nmId) ? ['trashedAt' => $trashedAt, 'nmID' => $nmId] : []
                ),
                'filter' => array_merge(
                    ['withPhoto' => -1],
                    $textSearch ? ['textSearch' => $textSearch] : [],
                ),
                'sort' => [
                    'ascending' => $ascending,
                ]
            ],
        ]);
    }

    /**
     * Перенос НМ в корзину
     * 
     * Метод позволяет перенести НМ в корзину. Перенос карточки в корзину не является удалением карточки.
     * При переносе НМ в корзину, данная НМ выходит из КТ, то есть ей присваивается новый imtID.
     * Карточка товара удаляется автоматически, если лежит в корзине больше 30 дней.
     * Корзина зачищается от карточек, лежащих в ней более 30 дней.
     * @see https://openapi.wb.ru/content/api/ru/#tag/Korzina/paths/~1content~1v2~1cards~1delete~1trash/post
     * 
     * @param array $nmIDs Артикулы WB
     * 
     * @return object {
     *      data: null,
     *      error: bool, errorText: string, additionalErrors: string
     * }
     * 
     * @throws InvalidArgumentException Превышение максимального количества удаляемых карточек
     */
    public function add(array $nmIDs): object
    {
        $maxCount = 1_000;
        if (count($nmIDs) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества удаляемых карточек: {$maxCount}");
        }
        return $this->Content->postRequest('/content/v2/cards/delete/trash', ['nmIDs' => $nmIDs]);
    }

    /**
     * Восстановление НМ из корзины
     * 
     * Метод позволяет восстановить НМ из корзины.
     * При восстановлении НМ из корзины она не возвращается в КТ в которой была до переноса в корзину,
     * то есть imtID остается тот же, что и был у НМ в корзине.
     * @see https://openapi.wb.ru/content/api/ru/#tag/Korzina/paths/~1content~1v2~1cards~1recover/post
     * 
     * @param array $nmIDs Артикулы WB
     * 
     * @return object {
     *      data: null,
     *      error: bool, errorText: string, additionalErrors: string
     * }
     * 
     * @throws InvalidArgumentException Превышение максимального количества удаляемых карточек
     */
    public function recover(array $nmIDs): object
    {
        $maxCount = 1_000;
        if (count($nmIDs) > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества восстанавливаемых карточек: {$maxCount}");
        }
        return $this->Content->postRequest('/content/v2/cards/recover', ['nmIDs' => $nmIDs]);
    }
}
