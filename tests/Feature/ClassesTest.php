<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassesTest extends TestCase
{

    const CREATE_API_URL = '/api/class';
    const UPDATE_API_URL = '/api/class/';
    const DELETE_API_URL = '/api/class/';
    const INFO_API_URL = '/api/class/';
    const LIST_API_URL = '/api/classes';


    protected function createParams() : array
    {
        return [
            'name' => 'Tester Joe\'s class',
        ];
    }

    protected function createTestClass() : int
    {
        $response = $this->postJson(self::CREATE_API_URL, $this->createParams());
        $response->assertStatus(200);
        return $response->json('id');
    }

    public function test_create()
    {
        //create no params
        $this->postJson(self::CREATE_API_URL)->assertStatus(422);

        $class_id = $this->createTestClass();

        //already exists
        $this->postJson(self::CREATE_API_URL, $this->createParams())->assertStatus(422);

        $this->deleteJson(self::DELETE_API_URL . $class_id)->assertStatus(200);
    }


    public function test_delete()
    {
        //delete bad id
        $this->deleteJson(self::DELETE_API_URL . 0)->assertStatus(404);

        $class_id = $this->createTestClass();

        //OK
        $this->deleteJson(self::DELETE_API_URL . $class_id)->assertStatus(200);

        //delete already deleted
        $this->deleteJson(self::DELETE_API_URL . $class_id)->assertStatus(404);
    }


    public function test_get_info()
    {
        //get uknown
        $this->getJson(self::INFO_API_URL . 0)->assertStatus(404);

        $class_id = $this->createTestClass();

        //OK
        $this->getJson(self::INFO_API_URL . $class_id)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $class_id]);

        /**
         * @TODO: add check to get info with students
        */

        $this->deleteJson(self::DELETE_API_URL . $class_id)->assertStatus(200);
    }


    public function test_list()
    {
        $params = $this->createParams();
        $this->getJson(self::LIST_API_URL)->assertStatus(200)->assertJsonMissing($params);
        $class_id = $this->createTestClass();
        $this->getJson(self::LIST_API_URL)->assertStatus(200)->assertJsonFragment($params);

        $this->deleteJson(self::DELETE_API_URL . $class_id)->assertStatus(200);
    }


    public function test_update()
    {
        $class_id = $this->createTestClass();

        $changeParams = [
            'name' => 'New test class name',
        ];

        // update uknown
        $this->putJson(self::UPDATE_API_URL . 0, $changeParams)->assertStatus(404);

        // update no data given
        $this->putJson(self::UPDATE_API_URL . $class_id)->assertStatus(422);


        //OK
        $this->putJson(self::UPDATE_API_URL . $class_id, $changeParams)
            ->assertStatus(200)
            ->assertJsonFragment($changeParams);

        $this->deleteJson(self::DELETE_API_URL . $class_id)->assertStatus(200);
    }

    /**
     * @TODO: get and update lection list
    */
}
