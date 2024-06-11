<?php

namespace Application\InputFilter;

use Laminas\InputFilter\InputFilter;
use Laminas\Validator\StringLength;
use Laminas\Validator\Digits;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;

class TaskInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'title',
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
            'name' => 'description',
            'required' => false,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'max' => 65535,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'status',
            'required' => true,
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
            ],
        ]);
    }
}
