<?php

declare(strict_types=1);

namespace Dakword\WBSeller\API\Endpoint\Subpoint;

use Dakword\WBSeller\API\Endpoint\Marketplace;

class Passes
{
    private Marketplace $Marketplace;

    public function __construct(Marketplace $Marketplace)
    {
        $this->Marketplace = $Marketplace;
    }

    /**
     * Cписок складов, для которых требуется пропуск
     * 
     * @return array [{id: int, name: string, address: string, ...}, ...]
     */
    public function offices(): array
    {
        return $this->Marketplace->getRequest('/api/v3/passes/offices');
    }

    /**
     * Cписок пропусков
     * 
     * @return array [{object}, ...]
     */
    public function list(): array
    {
        return $this->Marketplace->getRequest('/api/v3/passes');
    }
    /**
     * Создать пропуск
     * 
     * Пропуск действует 48 часов со времени создания. Метод ограничен одним вызовом в 10 минут.
     * 
     * @param int    $officeId  ID склада WB
     * @param string $carModel  Марка машины
     * @param string $carNumber Номер машины
     * @param string $firstName Имя водителя
     * @param string $lastName  Фамилия водителя
     * 
     * @return object {id: int}
     */
    public function create(int $officeId, string $carModel, string $carNumber, string $firstName, string $lastName): object
    {
        return $this->Marketplace->postRequest('/api/v3/passes', [
            'officeId' => $officeId,
            'carModel' => mb_substr($carModel, 0, 100),
            'carNumber' => $carNumber,
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);
    }

    /**
     * Обновить пропуск
     * 
     * @param int    $id        ID пропуска
     * @param int    $officeId  ID склада WB
     * @param string $carModel  Марка машины
     * @param string $carNumber Номер машины
     * @param string $firstName Имя водителя
     * @param string $lastName  Фамилия водителя
     * 
     * @return bool
     */
    public function update(int $id, int $officeId, string $carModel, string $carNumber, string $firstName, string $lastName): bool
    {
        $this->Marketplace->putRequest('/api/v3/passes/' . $id, [
            'officeId' => $officeId,
            'carModel' => mb_substr($carModel, 0, 100),
            'carNumber' => $carNumber,
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);
        
        return $this->Marketplace->responseCode() == 204;
    }

    /**
     * Удалить пропуск
     * 
     * @param int $id ID пропуска
     * 
     * @return bool
     */
    public function delete(int $id): bool
    {
        $this->Marketplace->deleteRequest('/api/v3/passes/' . $id,);
        
        return $this->Marketplace->responseCode() == 204;
    }    
    
}
