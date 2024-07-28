<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use DateTime;

class Documents extends AbstractEndpoint
{

    /**
     * Категории документов
     *
     * Максимум 1 запрос в 10 секунд
     * @link https://openapi.wb.ru/documents/api/ru/#/paths/~1api~1v1~1documents~1categories/get
     *
     * @return array [{name: string, title: string}, ...]
     */
    public function categories(): array
    {
        return $this->getRequest('/api/v1/documents/categories')
            ->data->categories;
    }

    /**
     * Список документов
     * 
     * Максимум 1 запрос в 10 секунд
     * @link https://openapi.wb.ru/documents/api/ru/#/paths/~1api~1v1~1documents~1list/get
     * 
     * @param DateTime $dateFrom    Начало периода. Используется только вместе с dateTo
     * @param DateTime $dateTo      Конец периода. Используется только вместе с dateFrom
     * @param string   $category    ID категории документов из поля name
     * @param string   $serviceName Уникальный ID документа
     * @param string   $orderBy     Сортировка:
     *                              date — по дате создания документа
     *                              category — по категории (только при locale=ru)
     * @param string   $sortOrder   Направление сортировки: desc - по убыванию, asc - по возрастанию
     * 
     * @return array [object, ...]
     * 
     * @throws InvalidArgumentException Неизвестная сортировка
     * @throws InvalidArgumentException Сортировка по caregory только при locale=ru
     * @throws InvalidArgumentException Неизвестное направление сортировки
     */
    public function list(
        DateTime $dateFrom = null, DateTime $dateTo = null,
        string $category = null, string $serviceName = null,
        string $orderBy = 'date', string $sortOrder = 'desc'
    ): array
    {
        if ($orderBy && !in_array($orderBy, ['category', 'date'])) {
            throw new InvalidArgumentException('Неизвестная сортировка: ' . $orderBy);
        }
        if ($orderBy == 'category' && $this->locale() !== 'ru') {
            throw new InvalidArgumentException('Сортировка по caregory только при locale=ru');
        }
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            throw new InvalidArgumentException('Неизвестное направление сортировки: ' . $sortOrder);
        }
        return $this->getRequest('/api/v1/documents/list',
            [
                'locale' => $this->locale(),
                'sort' => $orderBy,
                'order' => $sortOrder,
            ] + ($dateFrom && $dateTo ? [
                'beginTime' => $dateFrom->format('Y-m-d'),
                'endTime' => $dateFrom->format('Y-m-d'),
            ] : []) + ($category ? [
                'category' => $category,
            ] : []) + ($serviceName ? [
                'serviceName' => $serviceName,
            ] : [])
        )->data->documents;
    }

    /**
     * Получить документ
     * 
     * Возвращает один документ.
     * Максимум 1 запрос в 10 секунд
     * @link https://openapi.wb.ru/documents/api/ru/#/paths/~1api~1v1~1documents~1download/get
     * 
     * @param string $serviceName Уникальный ID документа
     * @param string $extension   Формат документа
     * 
     * @return object {
     *      fileName: string,
     *      extension: string,
     *      document: base64 string
     * }
     */
    public function get(string $serviceName, string $extension = 'zip'): object
    {
        return $this->getRequest('/api/v1/documents/download', [
            'serviceName' => $serviceName,
            'extension' => $extension,
        ])->data;
    }
    
    /**
     * Получить документы
     * 
     * Возвращает больше одного документа.
     * Все документы будут в одном архиве.
     * Можно получить неограниченное количество документов.
     * Максимум 1 запрос в 5 минут
     * @link https://openapi.wb.ru/documents/api/ru/#/paths/~1api~1v1~1documents~1download~1all/post
     * 
     * @param array  $documents Массив идентификаторов документов с указанием формата
     *                          [
     *                              { serviceName: string, extension: string },
     *                              ...
     *                          ]
     * 
     * @return object {
     *      fileName: string,
     *      extension: string,
     *      document: base64 string
     * }
     */
    public function getDocuments(array $documents): object
    {
        return $this->getRequest('/api/v1/documents/download/all', [
            'params' => $documents,
        ])->data;
    }
    
}
