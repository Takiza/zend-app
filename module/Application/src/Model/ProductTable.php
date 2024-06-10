<?php

namespace Application\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;

class ProductTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getProduct($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            throw new \RuntimeException("Could not find row with id $id");
        }
        return $row;
    }

    public function saveProduct(Product $product)
    {
        $data = $product->getArrayCopy();

        $id = (int) $product->id;
        if ($id === 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            try {
                $this->getProduct($id);
            } catch (\RuntimeException $e) {
                throw new \RuntimeException("Cannot update product with id $id; does not exist");
            }

            $this->tableGateway->update($data, ['id' => $id]);
        }

        return $this->getProduct($id);
    }

    public function deleteProduct($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
