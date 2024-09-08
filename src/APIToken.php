<?php

declare(strict_types=1);

namespace Dakword\WBSeller;

use Dakword\WBSeller\Exception\WBSellerException;
use DateTime;

class APIToken
{
    const BIT = [
        1 => 'Контент',
        2 => 'Аналитика',
        3 => 'Цены и скидки',
        4 => 'Маркетплейс',
        5 => 'Статистика',
        6 => 'Продвижение',
        7 => 'Вопросы и отзывы',
        8 => 'Рекомендации',
        9 => 'Чат с покупателями',
        10 => 'Поставки',
        11 => 'Возвраты покупателями',
        12 => 'Документы',
    ];
    const BIT_READONLY = 30;

    private array $apiFlagPosition = [
        'common' => 0,
        'tariffs' => 0,
        'content' => 1,
        'analytics' => 2,
        'calendar' => 3,
        'prices' => 3,
        'marketplace' => 4,
        'statistics' => 5,
        'adv' => 6,
        'feedbacks' => 7,
        'questions' => 7,
        'recommends' => 8,
        'chat' => 9,
        'supplies' => 10,
        'returns' => 11,
        'documents' => 12,
    ];
    private string $token;
    private ?object $payload;

    function __construct(string $token)
    {
        $this->token = $token;

        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new WBSellerException('Неверный формат токена');
        }
        $this->payload = json_decode(base64_decode($parts[1]));

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new WBSellerException('Неверный формат токена');
        }

        foreach (['exp', 's', 'oid', 'sid', 't'] as $param) {
            if (!property_exists($this->payload, $param)) {
                throw new WBSellerException('Неверный формат токена');
            }
        }
    }

    public function __toString(): string
    {
        return $this->token;
    }

    public function getPayload(): object
    {
        return $this->payload;
    }

    public function expireDate(): DateTime
    {
        return (new DateTime())->setTimestamp($this->payload->exp);
    }

    public function isExpired(): bool
    {
        return (new DateTime()) > $this->expireDate();
    }

    public function isTest(): bool
    {
        return $this->payload->t;
    }

    public function isReadOnly(): bool
    {
        return $this->isFlagSet(self::BIT_READONLY);
    }

    public function sellerId(): int
    {
        return $this->payload->oid;
    }

    public function sellerUUID(): string
    {
        return $this->payload->sid;
    }

    public function accessList(): array
    {
        return array_filter(self::BIT, fn($position) => $this->isFlagSet($position), ARRAY_FILTER_USE_KEY);
    }

    public function accessTo(string $apiName): bool
    {
        $position = $this->apiFlagPosition[$apiName] ?? null;
        if (is_null($position)) {
            return false;
        }
        if ($position) {
            return $this->isFlagSet($position);
        }
        return true;
    }

    private function isFlagSet($position): bool
    {
        return (bool) ($this->payload->s & (0b1 << $position));
    }
}