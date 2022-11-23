<?php

declare(strict_types=1);

namespace Dakword\WBSeller\Query;

use Dakword\WBSeller\API;
use Dakword\WBSeller\API\Endpoint\Content;
use Dakword\WBSeller\Exception\ApiClientException;

class CardsList
{
    private const LIMIT = 1_000;
    private Content $Content;
    private $limit;
    private $textSearch;
    private $withPhoto;
    private $sort;

    public function __construct(API $API)
    {
        $this->Content = $API->Content();
        $this->textSearch = '';
        $this->withPhoto = -1;
        $this->sort = false;
        $this->limit = self::LIMIT;
    }

    /**
     * Поиск по номеру НМ, баркоду или артикулу товара
     * 
     * @param string $textSearch Искомое вхождение
     * 
     * @return self
     */
    public function find(string $textSearch): self
    {
        $this->textSearch = $textSearch;
        return $this;
    }

    /**
     * Направление сортировки
     * 
     * @param bool $sort true  - по возрастанию
     *                   false - по убыванию
     * 
     * @return self
     */
    public function sort(bool $sort): self
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * Направление сортировки "по убыванию"
     * 
     * @return self
     */
    public function sortDesc(): self
    {
        $this->sort = false;
        return $this;
    }

    /**
     * Направление сортировки "по возрастанию"
     * 
     * @return self
     */
    public function sortAsc(): self
    {
        $this->sort = true;
        return $this;
    }

    /**
     * Наличие фото
     * 
     * @param int $withPhoto 1 - КТ с фото
     *                       0 - КТ без фото
     *                      -1 = все КТ
     * 
     * @return self
     */
    public function photo(int $withPhoto): self
    {
        $this->withPhoto = $withPhoto;
        return $this;
    }

    /**
     * КТ с фото
     * 
     * @return self
     */
    public function withPhoto(): self
    {
        $this->withPhoto = 1;
        return $this;
    }

    /**
     * КТ без фото
     * 
     * @return self
     */
    public function withOutPhoto(): self
    {
        $this->withPhoto = 0;
        return $this;
    }

    /**
     * Количество КТ в запросе/на странице
     * 
     * @param int $limit
     * 
     * @return self
     */
    public function perPage(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Получить список всех НМ по заданным параметрам
     * 
     * @return array
     */
    public function getAll(): array
    {
        return iterator_to_array($this->getAllLazy());
    }

    /**
     * Первая страница результатов
     * 
     * @return array
     */
    function getFirst(): array
    {
        $response = $this->Content->getCardsList(
            $this->textSearch, $this->limit, $this->withPhoto, 'updateAt', $this->sort
        );
        return $response->data->cards;
    }

    /**
     * Наличие следующей страницы результатов
     * 
     * @return bool
     */
    function hasNext(): bool
    {
        $cursor = $this->getCursor();
        return $this->limit == $cursor->total;
    }

    /**
     * Следующая страница результатов
     * 
     * @param object|null $cursor
     * @return array
     */
    function getNext(?object $cursor = null): array
    {
        if(is_null($cursor)) {
            $cursor = $this->getCursor();
        }
        $response = $this->Content->getCardsList(
            $this->textSearch, $this->limit, $this->withPhoto, 'updateAt', $this->sort, $cursor->updatedAt, $cursor->nmID
        );

        return $response->data->cards;
    }

    /**
     * @return object
     * @throws ApiClientException
     */
    public function getCursor(): object
    {
        $lastResponse = $this->Content->response();
        if(is_null($lastResponse)) {
            throw new ApiClientException('Курсор будет доступен после запроса первой страницы.');
        }
        return $lastResponse->data->cursor;
    }

    private function getAllLazy()
    {
        foreach ($this->getFirst() as $value) {
            yield $value;
        }
        while ($this->hasNext()) {
            foreach ($this->getNext() as $value) {
                yield $value;
            }
        }
    }

}
