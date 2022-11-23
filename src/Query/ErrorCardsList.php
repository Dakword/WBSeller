<?php

declare(strict_types=1);

namespace Dakword\WBSeller\Query;

use Dakword\WBSeller\API;

class ErrorCardsList
{
    private $Content;

    public function __construct(API $API)
    {
        $this->Content = $API->Content();
    }

    public function getAll(): array
    {
        $response = $this->Content->getErrorCardsList();
        return $this->setIndex($response->data);
    }

    /**
     * @param string|array $vendorCode
     * 
     * @return array|object|null
     */
    public function find($vendorCode)
    {
        $response = $this->Content->getErrorCardsList();
        $data = array_filter($response->data, function ($item) use ($vendorCode) {
            return in_array($item->vendorCode, is_array($vendorCode) ? $vendorCode : [$vendorCode]);
        });
        return is_array($vendorCode) ? $this->setIndex($data) : ($data ? array_shift($data) : null);
    }

    /**
     * @param array $data
     * 
     * @return array [
     *     (vendorCode) => {object: string, vendorCode: string, updatedAt: string, errors: [string, ...]},
     *     ...
     * ]
     */
    private function setIndex(array $data): array
    {
        if(!count($data)) {
            return $data;
        }
        return array_reduce($data, function ($result, $item) {
            $result[$item->vendorCode] = $item;
            return $result;
        });
    }

}
