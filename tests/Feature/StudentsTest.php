<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentsTest extends TestCase
{
    const CREATE_API_URL = '/api/student';
    const UPDATE_API_URL = '/api/student/';
    const DELETE_API_URL = '/api/student/';
    const INFO_API_URL = '/api/student/';
    const LIST_API_URL = '/api/students';


    protected function createTestClass() : int
    {
        $response = $this->postJson('/api/class', ['name' => 'Joe\'s class']);
        $response->assertStatus(200);
        return $response->json('id');
    }

    protected function createStudentParams($class_id) : array
    {
        return [
            'name' => 'Joe',
            'email' => 'joe@google.com',
            'class_id' => $class_id,
        ];
    }

    protected function createTestStudent($class_id) : int
    {
        $response = $this->postJson(self::CREATE_API_URL, $this->createStudentParams($class_id));
        $response->assertStatus(200);
        return $response->json('id');
    }

    protected function deleteTestStudent($student_id, $class_id)
    {
        //delete student
        $this->deleteJson(self::DELETE_API_URL . $student_id)->assertStatus(200);

        //delete test class
        $this->deleteJson('/api/class/' . $class_id)->assertStatus(200);
    }


    public function test_create()
    {
        //create no params
        $this->postJson(self::CREATE_API_URL)->assertStatus(422);

        $class_id = $this->createTestClass();

        //bad class id
        $this->postJson(self::CREATE_API_URL, [
            'name' => 'Joe',
            'email' => 'joe@google.com',
            'class_id' => 0,
        ])->assertStatus(422);

        //no class id
        $this->postJson(self::CREATE_API_URL, [
            'name' => 'Joe',
            'email' => 'joe@google.com',
        ])->assertStatus(422);

        //no email
        $this->postJson(self::CREATE_API_URL, [
            'name' => 'Joe',
            'class_id' => $class_id,
        ])->assertStatus(422);

        //no name
        $this->postJson(self::CREATE_API_URL, [
            'email' => 'joe@google.com',
            'class_id' => $class_id,
        ])->assertStatus(422);

        //invalid email
        $this->postJson(self::CREATE_API_URL, [
            'name' => 'Joe',
            'email' => 'joe invalid email',
            'class_id' => $class_id,
        ])->assertStatus(422);

        $student_id = $this->createTestStudent($class_id);

        //already exists
        $this->postJson(self::CREATE_API_URL, $this->createStudentParams($class_id))->assertStatus(422);

        $this->deleteTestStudent($student_id, $class_id);
    }


    public function test_delete()
    {
        $this->deleteJson(self::DELETE_API_URL . 0)->assertStatus(404); //delete bad id
        $class_id = $this->createTestClass();
        $student_id = $this->createTestStudent($class_id);
        $this->deleteTestStudent($student_id, $class_id);
        $this->deleteJson(self::DELETE_API_URL . $student_id)->assertStatus(404); //delete already deleted
    }


    public function test_get_info()
    {
        $this->getJson(self::INFO_API_URL . 0)->assertStatus(404); //get uknown
        $class_id = $this->createTestClass();
        $student_id = $this->createTestStudent($class_id);
        $this->getJson(self::INFO_API_URL . $student_id) //OK
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $student_id]);
        /**
         * @TODO: add check to get info with class and lections
        */
        $this->deleteTestStudent($student_id, $class_id);
    }


    public function test_list()
    {
        $class_id = $this->createTestClass();
        $params = $this->createStudentParams($class_id);
        $this->getJson(self::LIST_API_URL)->assertStatus(200)->assertJsonMissing($params);
        $student_id = $this->createTestStudent($class_id);
        $this->getJson(self::LIST_API_URL)->assertStatus(200)->assertJsonFragment($params);
        $this->deleteTestStudent($student_id, $class_id);
    }


    public function test_update()
    {
        $class_id = $this->createTestClass();
        $student_id = $this->createTestStudent($class_id);
        $changeParams = [
            'name' => 'Doe',
            'email' => 'doe@google.com',
            'class_id' => $class_id,
        ];
        // update uknown
        $this->putJson(self::UPDATE_API_URL . 0, $changeParams)->assertStatus(404);

        // update no data given
        $this->putJson(self::UPDATE_API_URL . $student_id)->assertStatus(422);

        //no name
        $this->putJson(self::UPDATE_API_URL . $student_id, [
            'email' => 'doe@google.com',
            'class_id' => $class_id,
        ])->assertStatus(422);

        //no email
        $this->putJson(self::UPDATE_API_URL . $student_id, [
            'name' => 'Doe',
            'class_id' => $class_id,
        ])->assertStatus(422);

        //no class
        $this->putJson(self::UPDATE_API_URL . $student_id, [
            'name' => 'Doe',
            'email' => 'doe@google.com',
        ])->assertStatus(422);

        //bad class
        $this->putJson(self::UPDATE_API_URL . $student_id, [
            'name' => 'Doe',
            'email' => 'doe@google.com',
            'class_id' => 0,
        ])->assertStatus(422);

        //bad email
        $this->putJson(self::UPDATE_API_URL . $student_id, [
            'name' => 'Doe',
            'email' => 'doe invalid email',
            'class_id' => $class_id,
        ])->assertStatus(422);

        //OK
        $this->putJson(self::UPDATE_API_URL . $student_id, $changeParams)
            ->assertStatus(200)
            ->assertJsonFragment($changeParams);

        $this->deleteTestStudent($student_id, $class_id);
    }


    public function test_class_deletion()
    {
        $class_id = $this->createTestClass();
        $student_id = $this->createTestStudent($class_id);

        //delete test class
        $this->deleteJson('/api/class/' . $class_id)->assertStatus(200);

        //get info is still student here
        $this->getJson(self::INFO_API_URL . $student_id)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $student_id]);

        //delete student
        $this->deleteJson(self::DELETE_API_URL . $student_id)->assertStatus(200);
    }
}
