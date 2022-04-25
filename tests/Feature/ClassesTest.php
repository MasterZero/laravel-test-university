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
     * @depends test_get_lections
    */
    public function test_update_lections()
    {
        $class_id = $this->createTestClass();
        $lection_id1 = $this->createTestLection('test lection 1', 'test description');
        $lection_id2 = $this->createTestLection('test lection 2', 'test description');
        $lection_id3 = $this->createTestLection('test lection 3', 'test description');
        $timestamp = time();

        // no data error
        $this->putJson('/api/class/' . $class_id . '/lections')->assertStatus(422);

        // double lection_id error
        $this->putLections($class_id, [
            [
                'lection_id' => $lection_id2,
                'planned_at' => date('Y-m-d H:i:s', $timestamp),
            ],
            [
                'lection_id' => $lection_id2,
                'planned_at' => date('Y-m-d H:i:s', $timestamp + 1),
            ],
            [
                'lection_id' => $lection_id3,
                'planned_at' => date('Y-m-d H:i:s', $timestamp + 2),
            ],
        ])->assertStatus(422);

        // double timestamp error
        $this->putLections($class_id, [
            [
                'lection_id' => $lection_id1,
                'planned_at' => date('Y-m-d H:i:s', $timestamp),
            ],
            [
                'lection_id' => $lection_id2,
                'planned_at' => date('Y-m-d H:i:s', $timestamp),
            ],
            [
                'lection_id' => $lection_id3,
                'planned_at' => date('Y-m-d H:i:s', $timestamp + 2),
            ],
        ])->assertStatus(422);

        // bad json letion_id param
        $this->putLections($class_id, [
            [
                'letion_id' => $lection_id1,
                'planned_at' => date('Y-m-d H:i:s', $timestamp),
            ],
            [
                'lection_id' => $lection_id2,
                'planned_at' => date('Y-m-d H:i:s', $timestamp + 1),
            ],
            [
                'lection_id' => $lection_id3,
                'planned_at' => date('Y-m-d H:i:s', $timestamp + 2),
            ],
        ])->assertStatus(422);

        // bad json planned_at param
        $this->putLections($class_id, [
            [
                'lection_id' => $lection_id1,
                'planned_at' => date('Y-m-d H:i:s', $timestamp),
            ],
            [
                'lection_id' => $lection_id2,
                'planed_at' => date('Y-m-d H:i:s', $timestamp + 1),
            ],
            [
                'lection_id' => $lection_id3,
                'planned_at' => date('Y-m-d H:i:s', $timestamp + 2),
            ],
        ])->assertStatus(422);

        // OK
        $this->putLections($class_id, [
            [
                'lection_id' => $lection_id1,
                'planned_at' => date('Y-m-d H:i:s', $timestamp),
            ],
            [
                'lection_id' => $lection_id2,
                'planned_at' => date('Y-m-d H:i:s', $timestamp + 1),
            ],
            [
                'lection_id' => $lection_id3,
                'planned_at' => date('Y-m-d H:i:s', $timestamp + 2),
            ],
        ])->assertStatus(200);

        //check
        $this->getJson('/api/class/' . $class_id . '/lections')
            ->assertStatus(200)
            ->assertJsonFragment(['lection_id' => $lection_id1, 'planned_at' => date('Y-m-d H:i:s', $timestamp)])
            ->assertJsonFragment(['lection_id' => $lection_id2, 'planned_at' => date('Y-m-d H:i:s', $timestamp + 1)])
            ->assertJsonFragment(['lection_id' => $lection_id3, 'planned_at' => date('Y-m-d H:i:s', $timestamp + 2)]);

        //cleanup
        $this->deleteTestLection($lection_id1);
        $this->deleteTestLection($lection_id2);
        $this->deleteTestLection($lection_id3);
        $this->deleteJson(self::DELETE_API_URL . $class_id)->assertStatus(200);
    }

    protected function putLections(int $class_id, array $table)
    {
        return $this->putJson('/api/class/' . $class_id . '/lections',['table' => json_encode($table)]);
    }

    protected function createTestLection(string $subject, string $description) : int
    {
        $response = $this->postJson('/api/lection', ['subject' => $subject, 'description' => $description]);
        $response->assertStatus(200);
        return $response->json('id');
    }

    protected function deleteTestLection(int $lection_id)
    {
        $this->deleteJson('/api/lection/' . $lection_id)->assertStatus(200);
    }

    /**
     * @depends test_create
     * @depends test_delete
    */
    public function test_get_lections()
    {
        //bad class id
        $this->getJson('/api/class/' . 0 . '/lections')->assertStatus(404);
        $class_id = $this->createTestClass();

        //OK
        $this->getJson('/api/class/' . $class_id . '/lections')->assertStatus(200);
        $this->deleteJson(self::DELETE_API_URL . $class_id)->assertStatus(200);
    }
}
