<?php
class Task
{
    private $description;
    private $id;
    private $due_date;
    private $category_id;

    function __construct($description, $id = null, $due_date, $category_id)
    {
        $this->description = $description;
        $this->id = $id;
        $this->due_date = $due_date;
        $this->category_id = $category_id;
    }

    function setDescription($new_description)
    {
        $this->description = (string) $new_description;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getId()
    {
        return $this->id;
    }

    function getDueDate() {
        return $this->due_date;
    }

    function getCategoryId() {
        return $this->category_id;
    }

    function save()
    {
        $statement = $GLOBALS['DB']->exec("INSERT INTO tasks (description, due_date, category_id) VALUES ('{$this->getDescription()}', '{$this->getDueDate()}', {$this->getCategoryId()})");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks ORDER BY due_date;");
        $tasks = array();
        foreach($returned_tasks as $task) {
            $description = $task['description'];
            $id = $task['id'];
            $due_date = $task['due_date'];
            $category_id = $task['category_id'];
            $new_task = new Task($description, $id, $due_date, $category_id);
            array_push($tasks, $new_task);
        }
        return $tasks;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM tasks;");
    }

    static function find($search_id) {
        $found_task = null;
        $tasks = Task::getAll();
        foreach($tasks as $task) {
            $task_id = $task->getId();
            if ($task_id == $search_id) {
                $found_task = $task;
            }
        }
        return $found_task;
    }

    // static function sortByDate() {
    //     $GLOBALS['DB']->exec("SELECT * FROM tasks ORDER BY due_date;");
    //     //$GLOBALS['DB']->exec("ALTER TABLE tasks ORDER BY due_date;");
    // }

}
?>
