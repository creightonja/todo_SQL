<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Task.php";

    $server = 'mysql:host=localhost;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);



    class TaskTest extends PHPUnit_Framework_TestCase {


        protected function tearDown() {
            Task::deleteAll();
            Category::deleteAll();
        }


        function testGetDescription() {
          //Arrange
          $description = "Do dishes.";
          $test_task = new Task($description);

          //Act
          $result = $test_task->getDescription();

          //Assert
          $this->assertEquals($description, $result);


        }

        function testSetDescription() {
            //Arrange
            $description = "Do Dishes.";
            $test_task = new Task($description);

            //Act
            $test_task->setDescription("Drink coffee.");
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals("Drink coffee.", $result);

        }

        function testGetId() {
          //Arrange
          $id = 1;
          $description = "Wash the dog.";
          $test_task = new Task($description, $id);

          //Act
          $result = $test_task->getId();

          //Assert
          $this->assertEquals(1, $result);
        }

        function testSave() {
          //Arrange
          $description = "Wash the dog.";
          $id = 1;
          $test_task = new Task($description, $id);

          //Act
          $test_task->save();

          //Assert
          $result = Task::getAll();
          $this->assertEquals($test_task, $result[0]);

        }

        function testSaveSetsId() {
          $description = "Wash the dog.";
          $id = 1;
          $test_task = new Task($description, $id);

          //Act
          $test_task->save();

          //Assert
          $this->assertEquals(true, is_numeric($test_task->getId()));
        }

        function testGetAll() {
          //Arrange
          $description = "Wash the dog.";
          $id = 1;
          $test_task = new Task($description, $id);
          $test_task->save();

          $description2 = "Water the lawn";
          $id2 = 2;
          $test_task2 = new Task($description2, $id2);
          $test_task2->save();

          //Act
          $result = Task::getAll();

          //Assert
          $this->assertEquals([$test_task, $test_task2], $result);
        }

        function testDeleteAll() {
          //Arrange
          $description = "Wash the dog";
          $id = 1;
          $test_task = new Task($description, $id);
          $test_task->save();

          $description2 = "Water the lawn";
          $id2 = 2;
          $test_task2 = new Task($description2, $id2);
          $test_task2->save();

          //Act
          Task::deleteAll();

          //Assert
          $result = Task::getAll();
          $this->assertEquals([], $result);

        }

        function testFind() {
          //Assert
          $description = "Wash the dog.";
          $id = 1;
          $test_task = new Task($description, $id);
          $test_task->save();

          $description2 = "Water the lawn.";
          $id2 = 2;
          $test_task2 = new Task($description2, $id2);
          $test_task2->save();

          //Act
          $result = Task::find($test_task->getId());

          //Assert
          $this->assertEquals($test_task, $result);
        }

        function testUpdate()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_task = new Task($description, $id);
            $test_task->save();

            $new_description = "Clean the dog";

            //Act
            $test_task->update($new_description);

            //Assert
            $this->assertEquals("Clean the dog", $test_task->getDescription());
        }

        function testDeleteTask()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_task = new Task($description, $id);
            $test_task->save();

            $description2 = "Water the lawn";
            $id2 = 2;
            $test_task2 = new Task($description2, $id2);
            $test_task2->save();


            //Act
            $test_task->delete();

            //Assert
            $this->assertEquals([$test_task2], Task::getAll());
        }

        function testAddCategory()
        {
        //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "File reports";
            $id2 = 2;
            $test_task = new Task($description, $id2);
            $test_task->save();

            //Act
            $test_task->addCategory($test_category);

            //Assert
            $this->assertEquals($test_task->getCategories(), [$test_category]);
        }

        function testGetCategories()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $name2 = "Volunteer stuff";
            $id2 = 2;
            $test_category2 = new Category($name2, $id2);
            $test_category2->save();

            $description = "File reports";
            $id3 = 3;
            $test_task = new Task($description, $id3);
            $test_task->save();

            //Act
            $test_task->addCategory($test_category);
            $test_task->addCategory($test_category2);

            //Assert
            $this->assertEquals($test_task->getCategories(), [$test_category, $test_category2]);
        }

        function testDelete()
        {
            //Arrange
            $name = "Work stuff";
            $id = 1;
            $test_category = new Category($name, $id);
            $test_category->save();

            $description = "File reports";
            $id2 = 2;
            $test_task = new Task($description, $id2);
            $test_task->save();

            //Act
            $test_task->addCategory($test_category);
            $test_task->delete();

            //Assert
            $this->assertEquals([], $test_category->getTasks());
        }
        
        // function test_getId() {
        //
        //     //Arrange
        //     $name = "Home stuff";
        //     $id = null;
        //     $test_category = new Category($name, $id);
        //     $test_category->save();
        //
        //     $description = "Wash the dog";
        //     $due_date = "2015-08-22";
        //     $category_id = $test_category->getId();
        //     $test_task = new Task($description, $id, $due_date, $category_id);
        //     $test_task->save();
        //
        //     //Act
        //     $result = $test_task->getId();
        //
        //     //Assert
        //     $this->assertEquals(true, is_numeric($result));
        //
        //
        // }
        //
        // function test_getCategoryId() {
        //     //Arrange
        //     $name = "Home stuff";
        //     $id = null;
        //     $test_category = new Category($name, $id);
        //     $test_category->save();
        //
        //     $description = "Wash the dog";
        //     $due_date = "2015-08-22";
        //     $category_id = $test_category->getId();
        //     $test_task = new Task($description, $id, $due_date, $category_id);
        //     $test_task->save();
        //
        //     //Act
        //     $result = $test_task->getCategoryId();
        //
        //     //Assert
        //     $this->assertEquals(true, is_numeric($result));
        // }
        //
        // function test_getDueDate() {
        //     //Arrange
        //     $name = "Home stuff";
        //     $id = null;
        //     $test_category = new Category($name, $id);
        //     $test_category->save();
        //
        //     $description = "Wash the dog";
        //     $due_date = "2015-08-22";
        //     $category_id = $test_category->getId();
        //     $test_task = new Task($description, $id, $due_date, $category_id);
        //     $test_task->save();
        //
        //     //Act
        //     $result = $test_task->getDueDate();
        //
        //     //Assert
        //     $this->assertEquals($result, "2015-08-22");
        // }
        //
        // function test_save() {
        //     //Arrange
        //     $name = "Home stuff";
        //     $id = null;
        //     $test_category = new Category($name, $id);
        //     $test_category->save();
        //
        //     $description = "Wash the dog";
        //     $due_date = "2015-08-22";
        //     $category_id = $test_category->getId();
        //     $test_task = new Task($description, $id, $due_date, $category_id);
        //
        //     //Act
        //     $test_task->save();
        //
        //     //Assert
        //     $result = Task::getAll();
        //     $this->assertEquals($test_task, $result[0]);
        //
        // }
        //
        // function test_getAll() {
        //     //Arrange
        //     $name = "Home stuff";
        //     $id = null;
        //     $test_category = new Category($name, $id);
        //     $test_category->save();
        //
        //     $description = "Wash the dog";
        //     $due_date = "2015-08-22";
        //     $category_id = $test_category->getId();
        //     $test_Task = new Task($description, $id, $due_date, $category_id);
        //     $test_Task->save();
        //
        //     $description2 = "Water the lawn";
        //     $due_date2 = "2015-08-24";
        //     $test_Task2 = new Task($description2, $id, $due_date2, $category_id);
        //     $test_Task2->save();
        //
        //     //Act
        //     $result = Task::getAll();
        //
        //     //Assert
        //     $this->assertEquals([$test_Task, $test_Task2], $result);
        //
        // }
        //
        // function test_deleteAll() {
        //
        //     //Arrange
        //     $name = "Home stuff";
        //     $id = null;
        //     $test_category = new Category($name, $id);
        //     $test_category->save();
        //
        //     $description = "Wash the dog";
        //     $due_date = "2015-08-25";
        //     $category_id = $test_category->getId();
        //     $test_Task = new Task($description, $id, $due_date, $category_id);
        //     $test_Task->save();
        //
        //     $description2 = "Water the lawn";
        //     $due_date2 = "2015-08-22";
        //     $test_Task2 = new Task($description2, $id, $due_date2, $category_id);
        //     $test_Task2->save();
        //
        //     //Act
        //     Task::deleteAll();
        //
        //     //Assert
        //     $result = Task::getAll();
        //     $this->assertEquals([], $result);
        //
        //
        // }
        //
        // function test_find() {
        //     //Arrange
        //     $name = "Home stuff";
        //     $id = null;
        //     $test_category = new Category($name, $id);
        //     $test_category->save();
        //
        //     $description = "Wash the dog";
        //     $due_date = "2015-08-22";
        //     $category_id = $test_category->getId();
        //     $test_Task = new Task($description, $id, $due_date, $category_id);
        //     $test_Task->save();
        //
        //     $description2 = "Water the lawn";
        //     $due_date2 = "2015-08-25";
        //     $test_Task2 = new Task($description2, $id, $due_date2, $category_id);
        //     $test_Task2->save();
        //
        //     //Act
        //     $id = $test_Task->getId();
        //     $result = Task::find($id);
        //
        //     //Assert
        //     $this->assertEquals($test_Task, $result);
        // }
        //
        // function test_sort() {
        //     //Arrange
        //     $name = "Home stuff";
        //     $id = null;
        //     $test_category = new Category($name, $id);
        //     $test_category->save();
        //
        //     $description = "Wash the dog2";
        //     $due_date = "2015-08-25";
        //     $category_id = $test_category->getId();
        //     $test_Task = new Task($description, $id, $due_date, $category_id);
        //     $test_Task->save();
        //
        //     $description2 = "Water the lawn1";
        //     $due_date2 = "2015-08-22";
        //     $test_Task2 = new Task($description2, $id, $due_date2, $category_id);
        //     $test_Task2->save();
        //
        //     $description3 = "Wash the dog3";
        //     $due_date3 = "2015-08-28";
        //     $test_Task3 = new Task($description, $id, $due_date3, $category_id);
        //     $test_Task3->save();
        //
        //     //Act
        //     $id = $test_Task->getId();
        //     $result = Task::getAll();
        //
        //
        //     //Assert
        //     $this->assertEquals([$test_Task2, $test_Task, $test_Task3], $result);
        // }

    }
?>
