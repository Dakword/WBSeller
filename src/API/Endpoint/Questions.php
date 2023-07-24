<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use Dakword\WBSeller\API\Endpoint\Subpoint\Templates;
use InvalidArgumentException;


class Questions extends AbstractEndpoint
{

    /**
     * Сервис для работы с шаблонами ответов.
     * 
     * @return Templates
     */
    public function Templates(): Templates
    {
        return new Templates($this);
    }

    public function __call($method, $parameters)
    {
        if(method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $parameters);
        }
        throw new InvalidArgumentException('Magic request methods not exists');
    }

    /**
     * Количество необработанных вопросов за период
     * 
     * @param DateTime $dateFrom Дата начала периода
     * @param DateTime $dateTo   Дата окончания периода
     * 
     * @return object {
     * 	    data: int,
     * 	    error: bool, errorText: string, additionalErrors: ?string
     * }
     */
    public function unansweredCountByPeriod(\DateTime $dateFrom, \DateTime $dateTo): object
    {
        return $this->getRequest('/api/v1/questions/count', [
            'dateFrom' => $dateFrom->getTimestamp(),
            'dateTo' => $dateTo->getTimestamp(),
            'isAnswered' => false,
        ]);
    }

    /**
     * Количество обработанных вопросов за период
     * 
     * @param DateTime $dateFrom Дата начала периода
     * @param DateTime $dateTo   Дата окончания периода
     * 
     * @return object {
     * 	    data: int,
     * 	    error: bool, errorText: string, additionalErrors: ?string
     * }
     */
    public function answeredCountByPeriod(\DateTime $dateFrom, \DateTime $dateTo): object
    {
        return $this->getRequest('/api/v1/questions/count', [
            'dateFrom' => $dateFrom->getTimestamp(),
            'dateTo' => $dateTo->getTimestamp(),
            'isAnswered' => true,
        ]);
    }

    /**
     * Неотвеченные вопросы за сегодня и за всё время
     * 
     * @return object {
     * 	    data: {countUnanswered: int, countUnansweredToday: int, valuation: string},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     * }
     */
    public function unansweredCount(): object
    {
        return $this->getRequest('/api/v1/questions/count-unanswered');
    }

    /**
     * Наличие непросмотренных вопросов
     * 
     * Метод отображает информацию о наличии у продавца непросмотренных отзывов и вопросов
     * 
     * @return object {
     * 	    data: {hasNewQuestions: bool, hasNewFeedbacks: bool},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     * }
     */
    public function hasNew(): object
    {
        return $this->getRequest('/api/v1/new-feedbacks-questions');
    }

    /**
     * Часто спрашиваемые товары
     * 
     * @param int $limit Количество запрашиваемых товаров
     * 
     * @return object {
     * 	    data: {products: [object, ...]},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     * }
     * @throws InvalidArgumentException Превышение максимального количества запрошенных результатов
     */
    public function productRating(int $limit = 100): object
    {
        $maxCount = 100;
        if ($limit > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрошенных результатов: {$maxCount}");
        }
        return $this->getRequest('/api/v1/questions/products/rating', [
            'size' => $limit,
        ]);
    }
    
    /**
     * Список вопросов
     * 
     * Метод позволяет получить список вопросов по заданным параметрам с пагинацией и сортировкой
     * 
     * @param int         $page                 Номер страницы
     * @param int         $onPage               Количество вопросов на странице (max. 10000)
     * @param bool        $isAnswered           Отвеченные вопросы (true) или неотвеченные вопросы (false)
     * @param int         $nmId                 Идентификатор номенклатуры 
     * @param string|null $order                Сортировка отзывов по дате "dateAsc" / "dateDesc"
     * @param DateTime    $dateFrom             Дата начала периода
     * @param DateTime    $dateTo               Дата окончания периода
     * 
     * @return object {
     * 	    data: {countUnanswered: int, countArchive: int, questions: [object, ...]},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     * }
     * @throws InvalidArgumentException Превышение максимального количества запрошенных отзывов
     * @throws InvalidArgumentException Недопустимое значение для сортировки результатов
     */
    public function list(int $page = 1, int $onPage = 10_000, bool $isAnswered = false, int $nmId = 0, ?string $order = null,
        ?\DateTime $dateFrom = null, ?\DateTime $dateTo = null
    ): object
    {
        $maxCount = 10_000;
        if ($onPage > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрошенных отзывов: {$maxCount}");
        }
        if (!is_null($order) && !in_array($order, ['dateAsc', 'dateDesc'])) {
            throw new InvalidArgumentException("Недопустимое значение для сортировки результатов: {$order}");
        }
        return $this->getRequest('/api/v1/questions', [
                'isAnswered' => $isAnswered,
                'skip' => --$page * $onPage,
                'take' => $onPage,
            ]
            + ($nmId ? ['nmId' => $nmId] : [])
            + (!is_null($order) ? ['order' => $order] : [])
            + (!is_null($dateFrom) ? ['dateFrom' => $dateFrom->getTimestamp()] : [])
            + (!is_null($dateTo) ? ['dateTo' => $dateTo->getTimestamp()] : [])
        );
    }

    /**
     * Получение вопросов в формате XLSX
     * 
     * Метод позволяет получить XLSX файл с вопросами в кодировке BASE64
     * 
     * @param bool $isAnswered Обработанные вопросы (true) или необработанные вопросы (false)
     * 
     * @return object {
     * 	    data: {filename: string, contentType: string, file: base64},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     * }
     */
    public function xlsReport(bool $isAnswered = false): object
    {
        return $this->getRequest('/api/v1/questions/report', [
                'isAnswered' => $isAnswered,
            ]
        );
    }
    
    /**
     * Изменение статуса "просмотра" вопроса
     * 
     * @param string $id        Идентификатор вопроса
     * @param bool   $wasViewed Просмотрен (true) или не просмотрен (false)
     * 
     * @return bool true - успешно, false - неудача
     */
    public function changeViewed(string $id, bool $wasViewed): bool
    {
        $this->patchRequest('/api/v1/questions', [
            'id' => $id,
            'wasViewed' => $wasViewed,
        ]);
        return $this->responseCode() == 200;
    }
    
    /**
     * Ответить на вопрос
     * 
     * @param string $id         Идентификатор вопроса
     * @param string $answerText Текст ответа
     * 
     * @return bool true - успешно, false - неудача
     */
    public function sendAnswer(string $id, string $answerText): bool
    {
        $this->patchRequest('/api/v1/questions', [
            'id' => $id,
            'answer' => (object)[
                'text' => $answerText,
            ],
            'state' => 'wbRu',
        ]);
        return $this->responseCode() == 200;
    }

    /**
     * Отклонить вопрос
     * 
     * Отклонить вопрос (такой вопрос не отображается на портале покупателей)
     * 
     * @param string $id         Идентификатор вопроса
     * @param string $answerText Текст ответа
     * 
     * @return bool true - успешно, false - неудача
     */
    public function reject(string $id, string $answerText): bool
    {
        $this->patchRequest('/api/v1/questions', [
            'id' => $id,
            'answer' => (object)[
                'text' => $answerText,
            ],
            'state' => 'none',
        ]);
        return $this->responseCode() == 200;
    }

    /**
     * Получить вопрос
     * 
     * @param string $id Идентификатор вопроса
     * 
     * @return object {
     * 	    data: object,
     * 	    error: bool, errorText: string, additionalErrors: any
     * }
     */
    public function get(string $id): object
    {
        return $this->getRequest('/api/v1/question', [
            'id' => $id,
        ]);
    }

}
