<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Content;
use InvalidArgumentException;

class Tags
{
    private Content $Content;

    public function __construct(Content $Content)
    {
        $this->Content = $Content;
    }

    /**
     * Управление тегами в КТ
     * 
     * Метод позволяет добавить теги к КТ и снять их с КТ.
     * Что бы снять теги с КТ, необходимо передать пустой массив.
     * При снятии тега с КТ сам тег не удаляется.
     * Чтобы добавить теги к уже имеющимся в КТ, необходимо в запросе
     * передать новые теги и теги, которые уже есть в КТ.
     * @see https://openapi.wb.ru/content/api/ru/#tag/Tegi/paths/~1content~1v2~1tag~1nomenclature~1link/post
     * 
     * @param int   $nmID Артикул WB
     * @param array $tags Массив числовых идентификаторов тегов (К карточке можно добавить 8 тегов)
     * 
     * @return object {data: any, error: bool, errorText: string, additionalErrors: any}
     * 
     * @throws InvalidArgumentException Превышение максимального количества тегов
     */
    public function setNomenclatureTags(int $nmID, array $tags): object
    {
        $maxTags = 8;
        if (count($tags) > $maxTags) {
            throw new InvalidArgumentException("Превышение максимального количества тегов: {$maxTags}");
        }
        return $this->Content->postRequest('/content/v2/tag/nomenclature/link', [
            'nmID' => $nmID,
            'tagsIDs' => $tags,
        ]);
    }
        
    
    /**
     * Список тегов
     * 
     * Метод позволяет получить список существующих тегов продавца
     * @see https://openapi.wb.ru/content/api/ru/#tag/Tegi/paths/~1content~1v2~1tags/get
     * 
     * @return object {data: [object, ...], error: bool, errorText: string, additionalErrors: string}
     */
    public function list(): object
    {
        return $this->Content->getRequest('/content/v2/tags');
    }

    /**
     * Создание тега
     * 
     * Метод позволяет создать тег.
     * @see https://openapi.wb.ru/content/api/ru/#tag/Tegi/paths/~1content~1v2~1tag/post
     * 
     * @param string $name  Имя тега (Максимальная длина 15 символов)
     * @param string $color Цвет тега
     *                      Доступные цвета: D1CFD7 - серый, FEE0E0 - красный, ECDAFF - фиолетовый,
     *                                       E4EAFF - синий, DEF1DD - зеленный, FFECC7 - желтый
     * 
     * @return object {data: any, error: bool, errorText: string, additionalErrors: any}
     * 
     * @throws InvalidArgumentException Превышение максимального длины имени тега
     * @throws InvalidArgumentException Неизвестный цвет
     */
    public function create(string $name, string $color): object
    {
        $this->checkName($name);
        $this->checkColor($color);
        return $this->Content->postRequest('/content/v2/tag', [
            'name' => $name,
            'color' => $color,
        ]);
    }
 
    /**
     * Удаление тега
     * @see https://openapi.wb.ru/content/api/ru/#tag/Tegi/paths/~1content~1v2~1tag~1{id}/delete
     * 
     * @param int $id Числовой идентификатор тега
     * 
     * @return object {data: any, error: bool, errorText: string, additionalErrors: any}
     */
    public function delete(int $id): object
    {
        return $this->Content->deleteRequest('/content/v1/tag/' . $id);
    }
    
    /**
     * Изменение тега
     * @see https://openapi.wb.ru/content/api/ru/#tag/Tegi/paths/~1content~1v2~1tag~1{id}/patch
     * 
     * Метод позволяет изменять информацию о теге (имя и цвет)
     * 
     * @param int    $id   Числовой идентификатор тега
     * @param string $name  Имя тега (Максимальная длина 15 символов)
     * @param string $color Цвет тега
     *                      Доступные цвета: D1CFD7 - серый, FEE0E0 - красный, ECDAFF - фиолетовый,
     *                                       E4EAFF - синий, DEF1DD - зеленный, FFECC7 - желтый
     * 
     * @return object {data: any, error: bool, errorText: string, additionalErrors: any}
     * 
     * @throws InvalidArgumentException Превышение максимального длины имени тега
     * @throws InvalidArgumentException Неизвестный цвет
     */
    public function update(int $id, string $name, string $color): object
    {
        $this->checkName($name);
        $this->checkColor($color);
        return $this->Content->patchRequest('/content/v2/tag/' . $id, [
            'name' => $name,
            'color' => $color,
        ]);
    }
    
    private function checkName(string $name)
    {
        $maxLength = 15;
        if (mb_strlen($name) > $maxLength) {
            throw new InvalidArgumentException("Превышение максимального длины имени тега: {$maxLength}");
        }
    }

    private function checkColor(string $color)
    {
        $colors = ['D1CFD7', 'FEE0E0', 'ECDAFF', 'E4EAFF', 'DEF1DD', 'FFECC7'];
        if (!in_array($color, $colors)) {
            throw new InvalidArgumentException("Неизвестный цвет: {$color}");
        }
    }
}
