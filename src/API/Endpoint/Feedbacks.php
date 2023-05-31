<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;
use InvalidArgumentException;


class Feedbacks extends AbstractEndpoint
{

    /**
     * Наличие непросмотренных отзывов
     * 
     * Метод отображает информацию о наличии у продавца непросмотренных отзывов и вопросов
     * 
     * @return object {
     * 	    data: {hasNewQuestions: bool, hasNewFeedbacks: bool},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     */
    public function hasNew(): object
    {
        return $this->getRequest('/api/v1/new-feedbacks-questions');
    }

    /**
     * Необработанные отзывы
     * 
     * Метод позволяет получить количество необработанных отзывов за сегодня,
     * за всё время, и среднюю оценку всех отзывов
     * 
     * @return object {
     * 	    data: {countUnanswered: int, countUnansweredToday: int, valuation: string},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     * }
     */
    public function unansweredCount(): object
    {
        return $this->getRequest('/api/v1/feedbacks/count-unanswered');
    }

    /**
     * Родительские категории товаров
     * 
     * Метод позволяет получить список родительских категорий товаров, которые есть у продавца
     * 
     * @return object {
     * 	    data: [{subjectId: int, subjectName: string}, ...],
     * 	    error: bool, errorText: string, additionalErrors: ?string
     */
    public function parentSubjects(): object
    {
        return $this->getRequest('/api/v1/parent-subjects');
    }

    /**
     * Средняя оценка товаров по родительской категории
     * 
     * @param int $subjectId id категории товара
     * 
     * @return object {
     * 	    data: [{feedbacksCount: int, valuation: string}, ...],
     * 	    error: bool, errorText: string, additionalErrors: ?string
     */
    public function subjectRating(int $subjectId): object
    {
        return $this->getRequest('/api/v1/feedbacks/products/rating', ['subjectId' => $subjectId]);
    }
    
    /**
     * Товары с наибольшей и наименьшей средней оценкой по родительской категории
     * 
     * Метод позволяет получить список из двух товаров,
     * с наибольшей и наименьшей средней оценкой, по родительской категории
     * 
     * @param int $subjectId id категории товара
     * 
     * @return object {
     * 	    data: {productMinRating: object, productMaxRating: object},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     */
    public function subjectRatingTop(int $subjectId): object
    {
        return $this->getRequest('/api/v1/feedbacks/products/rating/top', ['subjectId' => $subjectId]);
    }

    /**
     * Список отзывов
     * 
     * Метод позволяет получить список отзывов по заданным параметрам с пагинацией и сортировкой
     * 
     * @param int         $page                 Номер страницы
     * @param int         $onPage               Количество отзывов на странице
     * @param bool        $isAnswered           Обработанные отзывы (true) или необработанные отзывы (false)
     * @param int         $nmId                 Идентификатор номенклатуры 
     * @param bool|null   $hasSupplierComplaint Отзывы с жалобой продавца (true) или без жалобы (false)
     * @param string|null $order                Сортировка отзывов по дате "dateAsc" / "dateDesc"
     * 
     * @return object {
     * 	    data: {countUnanswered: int, countArchive: int, feedbacks: [object, ...]},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     * 
     * @throws InvalidArgumentException Превышение максимального количества запрошенных отзывов
     * @throws InvalidArgumentException Недопустимое значение для сортировки результатов
     */
    public function list(int $page = 1, int $onPage = 5_000, bool $isAnswered = false, int $nmId = 0, ?bool $hasSupplierComplaint = null, ?string $order = null): object
    {
        $maxCount = 5_000;
        if ($onPage > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрошенных отзывов: {$maxCount}");
        }
        $this->checkOrder($order);
        return $this->getRequest('/api/v1/feedbacks', [
                'isAnswered' => $isAnswered,
                'skip' => --$page * $onPage,
                'take' => $onPage,
            ]
            + ($nmId ? ['nmId' => $nmId] : [])
            + (!is_null($hasSupplierComplaint) ? ['hasSupplierComplaint' => $hasSupplierComplaint] : [])
            + (!is_null($order) ? ['order' => $order] : [])
        );
    }
    
    /**
     * Список архивных отзывов
     * 
     * Отзыв становится архивным если на него предоставлен ответ
     * или ответ не предоставлен в течение 30 дней со дня его публикации
     * 
     * @param int         $page                 Номер страницы
     * @param int         $onPage               Количество отзывов на странице
     * @param int         $nmId                 Идентификатор номенклатуры 
     * @param bool|null   $hasSupplierComplaint Отзывы с жалобой продавца (true) или без жалобы (false)
     * @param string|null $order                Сортировка отзывов по дате "dateAsc" / "dateDesc"
     * 
     * @return object {
     * 	    data: {feedbacks: [object, ...]},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     * 
     * @throws InvalidArgumentException Превышение максимального количества запрошенных отзывов
     * @throws InvalidArgumentException Недопустимое значение для сортировки результатов
     */
    public function archive(int $page = 1, int $onPage= 5_000, int $nmId = 0, ?bool $hasSupplierComplaint = null, ?string $order = null): object
    {
        $maxCount = 5_000;
        if ($onPage > $maxCount) {
            throw new InvalidArgumentException("Превышение максимального количества запрошенных отзывов: {$maxCount}");
        }
        $this->checkOrder($order);
        return $this->getRequest('/api/v1/feedbacks/archive', [
                'skip' => --$page * $onPage,
                'take' => $onPage,
            ]
            + ($nmId ? ['nmId' => $nmId] : [])
            + (!is_null($hasSupplierComplaint) ? ['hasSupplierComplaint' => $hasSupplierComplaint] : [])
            + (!is_null($order) ? ['order' => $order] : [])
        );
    }
    
    /**
     * Средняя оценка товара
     * 
     * @param int $nmId Идентификатор номенклатуры
     * 
     * @return object {
     * 	    data: {feedbacksCount: int, valuation: string},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     */
    public function productRating(int $nmId): object
    {
        return $this->getRequest('/api/v1/feedbacks/products/rating/nmid', [
            'nmId' => $nmId,
        ]);
    }
    
    /**
     * Получение отзывов в формате XLSX
     * 
     * Метод позволяет получить XLSX файл с отзывами в кодировке BASE64
     * За один запрос можно получить 5000 отзывов.
     * На данный момент всего можно получить 200 000 последних отзывов.
     * 
     * @param bool        $isAnswered           Обработанные отзывы (true) или необработанные отзывы (false)
     * @param bool|null   $hasSupplierComplaint Отзывы с жалобой продавца (true) или без жалобы (false)
     * @param int         $page                 Номер страницы
     * 
     * @return object {
     * 	    data: {filename: string, contentType: string, file: base64},
     * 	    error: bool, errorText: string, additionalErrors: ?string
     */
    public function xlsReport(bool $isAnswered = false, ?bool $hasSupplierComplaint = null, int $page = 1): object
    {
        return $this->getRequest('/api/v1/feedbacks/report', [
                'isAnswered' => $isAnswered,
                'skip' => --$page * 5_000,
            ]
            + (!is_null($hasSupplierComplaint) ? ['hasSupplierComplaint' => $hasSupplierComplaint] : [])
        );
    }
    
    /**
     * Изменение статуса "просмотра" отзыва
     * 
     * @param string $id        Идентификатор отзыва
     * @param bool   $wasViewed Просмотрен (true) или не просмотрен (false)
     * 
     * @return bool true - успешно, false - неудача
     */
    public function changeViewed(string $id, bool $wasViewed): bool
    {
        $this->patchRequest('/api/v1/feedbacks', [
            'id' => $id,
            'wasViewed' => $wasViewed,
        ]);
        return $this->responseCode() == 200;
    }
    
    /**
     * Ответить на отзыв
     * 
     * @param string $id         Идентификатор отзыва
     * @param string $answerText Текст ответа
     * 
     * @return bool true - успешно, false - неудача
     */
    public function sendAnswer(string $id, string $answerText): bool
    {
        $this->patchRequest('/api/v1/feedbacks', [
            'id' => $id,
            'text' => $answerText,
        ]);
        return $this->responseCode() == 200;
    }

    /**
     * Создать жалобу на отзыв
     * 
     * @param string $id Идентификатор отзыва
     * 
     * @return bool true - успешно, false - неудача
     */
    public function createComplaint(string $id): bool
    {
        $this->patchRequest('/api/v1/feedbacks', [
            'id' => $id,
            'createSupplierComplaint' => true,
        ]);
        return $this->responseCode() == 200;
    }

    
    private function checkOrder($order)
    {
        if (!is_null($order) && !in_array($order, ['dateAsc', 'dateDesc'])) {
            throw new InvalidArgumentException("Недопустимое значение для сортировки результатов: {$order}");
        }
    }
}
