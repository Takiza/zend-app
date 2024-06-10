<?php

namespace Application\Model;

class Product
{
    public $id;
    public $name;
    public $sku;
    public $price;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->sku = !empty($data['sku']) ? $data['sku'] : null;
        $this->price = !empty($data['price']) ? $data['price'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
        ];
    }
}
