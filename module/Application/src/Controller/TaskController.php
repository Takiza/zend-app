<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\DbSelect;
use Application\Model\TaskTable;
use Application\Form\TaskForm;
use Application\Model\Task;
use Application\InputFilter\TaskInputFilter;

class TaskController extends AbstractActionController
{
    private $table;
    private $config;

    public function __construct(TaskTable $table, array $config)
    {
        $this->table = $table;
        $this->config = $config;
    }

    public function indexAction()
    {
        $sql = $this->table->getSql();
        $select = $sql->select();

        $paginatorAdapter = new DbSelect(
            $select,
            $this->table->getAdapter(),
            $this->table->getResultSetPrototype()
        );

        $paginator = new Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage($this->config['pagination']['items_per_page']);

        return new ViewModel([
            'tasks' => $paginator,
        ]);
    }

    public function addAction()
    {
        $form = new TaskForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = new TaskInputFilter();
            $form->setInputFilter($inputFilter);
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $task = new Task();
                $task->exchangeArray($form->getData());
                $this->table->saveTask($task);
                return $this->redirect()->toRoute('task');
            }
        }

        return ['form' => $form];
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('task', ['action' => 'add']);
        }

        try {
            $task = $this->table->getTask($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('task', ['action' => 'index']);
        }

        $form = new TaskForm();
        $form->bind($task);
        $form->get('submit')->setValue('Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form, 'task' => $task];

        if (!$request->isPost()) {
            return $viewData;
        }

        $inputFilter = new TaskInputFilter();
        $form->setInputFilter($inputFilter);
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->table->saveTask($task);

        return $this->redirect()->toRoute('task', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) {
            return $this->redirect()->toRoute('task');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->table->deleteTask((int) $request->getPost('id'));
            return $this->redirect()->toRoute('task');
        }

        return new ViewModel([
            'id' => $id,
            'task' => $this->table->getTask($id),
        ]);
    }
}
