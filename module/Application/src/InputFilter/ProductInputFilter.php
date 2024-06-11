<?php

namespace Application\InputFilter;

use Laminas\InputFilter\InputFilter;
use Laminas\Validator\StringLength;
use Laminas\Validator\Digits;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;

class ProductInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 255,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'sku',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'price',
            'required' => true,
            'validators' => [
                [
                    'name' => Digits::class,
                ],
            ],
        ]);
    }
}
