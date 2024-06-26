<?php

namespace Api\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Application\Model\Product;
use Application\Model\ProductTable;
use Application\InputFilter\ProductInputFilter;

class ProductController extends AbstractRestfulController
{
    private $productTable;

    public function __construct(ProductTable $productTable)
    {
        $this->productTable = $productTable;
    }

    public function get($id)
    {
        try {
            $product = $this->productTable->getProduct($id);
            if (!$product) {
                return new JsonModel(['status' => 'error', 'message' => 'Product not found']);
            }

            return new JsonModel(['status' => 'success', 'product' => $product->getArrayCopy()]);
        } catch (\Exception $e) {
            return new JsonModel(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function getList()
    {
        try {
            $products = $this->productTable->fetchAll();
            $data = array_map(function($product) {
                return $product->getArrayCopy();
            }, iterator_to_array($products));

            return new JsonModel([
                'status' => 'success',
                'products' => $data,
            ]);
        } catch (\Exception $e) {
            return new JsonModel(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function create($data)
    {
        $inputFilter = new ProductInputFilter();
        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new JsonModel(['status' => 'error', 'message' => 'Invalid data', 'errors' => $inputFilter->getMessages()]);
        }

        try {
            $product = new Product();
            $product->exchangeArray($inputFilter->getValues());
            $savedProduct = $this->productTable->saveProduct($product);
            return new JsonModel(['status' => 'success', 'product' => $savedProduct->getArrayCopy()]);
        } catch (\Exception $e) {
            return new JsonModel(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update($id, $data)
    {
        $inputFilter = new ProductInputFilter();
        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new JsonModel(['status' => 'error', 'message' => 'Invalid data', 'errors' => $inputFilter->getMessages()]);
        }

        try {
            $product = $this->productTable->getProduct($id);
            if (!$product) {
                return new JsonModel(['status' => 'error', 'message' => 'Product not found']);
            }
            $data['id'] = $id;
            $product->exchangeArray($inputFilter->getValues());
            $updatedProduct = $this->productTable->saveProduct($product);

            return new JsonModel(['status' => 'success', 'product' => $updatedProduct->getArrayCopy()]);
        } catch (\Exception $e) {
            return new JsonModel(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $product = $this->productTable->getProduct($id);
            if (!$product) {
                return new JsonModel(['status' => 'error', 'message' => 'Product not found']);
            }
            $this->productTable->deleteProduct($id);
            return new JsonModel(['status' => 'success', 'message' => 'Product deleted']);
        } catch (\Exception $e) {
            return new JsonModel(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
