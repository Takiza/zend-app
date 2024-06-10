<?php

namespace Application\Model;

use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\InputFilter\InputFilter;

class Task implements InputFilterAwareInterface
{
    public $id;
    public $title;
    public $description;
    public $status;
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->title = !empty($data['title']) ? $data['title'] : null;
        $this->description = !empty($data['description']) ? $data['description'] : null;
        $this->status = !empty($data['status']) ? $data['status'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add([
                'name' => 'id',
                'required' => true,
                'filters' => [
                    ['name' => 'Int'],
                ],
            ]);

            $inputFilter->add([
                'name' => 'title',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name' => 'description',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 255,
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name' => 'status',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'InArray',
                        'options' => [
                            'haystack' => ['pending', 'completed'],
                            'strict' => \Laminas\Validator\InArray::COMPARE_STRICT,
                        ],
                    ],
                ],
            ]);

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
