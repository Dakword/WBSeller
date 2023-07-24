<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Feedbacks;
use Dakword\WBSeller\API\Endpoint\Questions;
use InvalidArgumentException;

class Templates
{
    private $Endpoint;
    private $type;

    public function __construct($Endpoint)
    {
        $this->Endpoint = $Endpoint;
        if($Endpoint instanceof Questions) {
            $this->type = 1;
        } elseif($Endpoint instanceof Feedbacks) {
            $this->type = 2;
        } else {
            throw new InvalidArgumentException('Не поддерживаемый тип шаблонов.');
        }
    }

    /**
     * Cписок шаблонов
     * 
     * @return object {
     * 	    data: {templates: [object, ...]},
     * 	    error: bool, errorText: string, additionalErrors: object
     * }
     */
    public function list(): object
    {
        return $this->Endpoint->getRequest('/api/v1/templates', ['templateType' => $this->type]);
    }

    /**
     * Создать шаблон
     * 
     * Всего можно создать 20 шаблонов. 10 на отзывы и 10 на вопросы.
     * Допустимы любые символы.
     * 
     * @param string $name Название шаблона (от 1 до 100 символов)
     * @param string $text Текст шаблона (от 2 до 1000 символов)
     * 
     * @return object {
     * 	    data: {id: string},
     * 	    error: bool, errorText: string, additionalErrors: any
     * }
     */
    public function create(string $name, string $text): object
    {
        return $this->Endpoint->postRequest('/api/v1/templates', [
            'name' => mb_substr($name, 0, 200),
            'text' => mb_substr($text, 0, 1000),
            'templateType' => $this->type,
        ]);
    }

    /**
     * Обновить шаблон
     * 
     * @param string $id   Идентификатор шаблона
     * @param string $name Название шаблона (от 1 до 100 символов)
     * @param string $text Текст шаблона (от 2 до 1000 символов)
     * 
     * @return object {
     * 	    data: any,
     * 	    error: bool, errorText: string, additionalErrors: any
     * }
     */
    public function update(string $id, string $name, string $text): object
    {
        return $this->Endpoint->patchRequest('/api/v1/templates', [
            'name' => mb_substr($name, 0, 200),
            'text' => mb_substr($text, 0, 1000),
            'templateID' => $id,
        ]);
    }

    /**
     * Удалить шаблон
     * 
     * @param string $id Идентификатор шаблона
     * 
     * @return object {
     * 	    data: any,
     * 	    error: bool, errorText: string, additionalErrors: any
     * }
     */
    public function delete(string $id): object
    {
        return $this->Endpoint->deleteRequest('/api/v1/templates', ['templateID' => $id]);
    }    
    
}
