<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint;

use Dakword\WBSeller\API\AbstractEndpoint;

class Chat extends AbstractEndpoint
{

    /**
     * Список чатов
     *
     * Возвращает список всех чатов продавца.
     * Максимум 10 запросов за 10 секунд
     * @see https://openapi.wb.ru/buyers-chat/api/ru/#/paths/~1api~1v1~1seller~1chats/get
     *
     * @return object {result: [object, ...], errors: any}
     */
    public function list(): object
    {
        return $this->getRequest('/api/v1/seller/chats');
    }

    /**
     * События чатов
     * 
     * Возвращает список событий всех чатов
     * Максимум 10 запросов за 10 секунд
     * 
     * 1. Сделайте первый запрос без параметра next.
     * 2. Повторяйте запрос со значением параметра next из ответа на предыдущий запрос,
     *    пока totalEvents не станет равным 0. Это будет означать, что вы получили все события.
     * Чтобы получать только новые события, укажите параметр next со значением поля
     * addTimestamp из последнего полученного события.
     * @see https://openapi.wb.ru/buyers-chat/api/ru/#/paths/~1api~1v1~1seller~1events/get
     * 
     * @param string $next Пагинатор. С какого момента получить следующий пакет данных.
     *                     Формат Unix timestamp с миллисекундами
     * 
     * @return object {
     *     result: {
     *         next: int,
     *         newestEventTime: string,
     *         oldestEventTime: string,
     *         totalEvents: int,
     *         events: [object, ...]
     *     },
     *     errors: any
     * }
     */
    public function events(int $next = 0): object
    {
        return $this->getRequest('/api/v1/seller/events', $next ? ['next' => $next] : []);
    }

    /**
     * Отправить сообщение
     * 
     * @param string $replySign Подпись чата.
     *                          Можно получить из информации по чату или данных события, если в событии есть поле "isNewChat": true
     * @param string $message   Текст сообщения. Максимум 1000 символов. Лишнее отрежем!
     * @param array  $images    Файлы, формат JPEG, PDF или PNG, максимальный размер - 5 Мб каждый.
     *                          Максимальный суммарный размер файлов - 30 Мб.
     * @see https://openapi.wb.ru/buyers-chat/api/ru/#/paths/~1api~1v1~1seller~1message/post
     * 
     * @return object {
     *     result: {
     *         addTime: int,
     *         chatID: string
     *     },
     *     errors: array
     * }
     */
    public function message(string $replySign, string $message, array $images): object
    {
        return $this->multipartRequest('/api/v1/seller/message', [
            'replySign' => $replySign,
            'message' => mb_substr($message, 0, 1000),
            'file' => $images,
        ]);
    }
    
}
