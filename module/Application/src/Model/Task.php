<?php

namespace Application\Model;

use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Application\InputFilter\TaskInputFilter;

class Task implements InputFilterAwareInterface
{
    public $id;
    public $title;
    public $description;
    public $status;
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id          = !empty($data['id']) ? $data['id'] : null;
        $this->title       = !empty($data['title']) ? $data['title'] : null;
        $this->description = !empty($data['description']) ? $data['description'] : null;
        $this->status      = !empty($data['status']) ? $data['status'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \RuntimeException('Not used');
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $this->inputFilter = new TaskInputFilter();
        }

        return $this->inputFilter;
    }
}
