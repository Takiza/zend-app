<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\DbSelect;
use Application\Model\TaskTable;
use Application\Form\TaskForm;
use Application\Model\Task;

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
        // Создание SQL-запроса
        $sql = $this->table->getSql();
        $select = $sql->select();

        // Создание адаптера для пагинатора
        $paginatorAdapter = new DbSelect(
            $select,
            $this->table->getAdapter(),
            $this->table->getResultSetPrototype()
        );

        // Создание объекта пагинатора
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
        if ($request->isPost() && $form->setData($request->getPost())->isValid()) {
            $task = new Task();
            $task->exchangeArray($form->getData());
            $this->table->saveTask($task);
            return $this->redirect()->toRoute('task');
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
        $viewData = ['id' => $id, 'form' => $form, 'task' => $task]; // передаем task в представление

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($task->getInputFilter());
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
