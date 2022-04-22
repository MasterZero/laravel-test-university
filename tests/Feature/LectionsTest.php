<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LectionsTest extends TestCase
{

    const TEST_DESCRIPTION = 'Some description of lection';

    const CREATE_API_URL = '/api/lection';
    const UPDATE_API_URL = '/api/lection/';
    const DELETE_API_URL = '/api/lection/';
    const INFO_API_URL = '/api/lection/';
    const LIST_API_URL = '/api/lections';

    protected function createParams() : array
    {
        return [
            'subject' => 'Some test lection',
            'description' => 'some test description',
        ];
    }

    protected function createTestLection() : int
    {
        $response = $this->postJson(self::CREATE_API_URL, $this->createParams());
        $response->assertStatus(200);
        return $response->json('id');
    }

    public function test_create()
    {
        //create no params
        $this->postJson(self::CREATE_API_URL)->assertStatus(422);

        //no description
        $this->postJson(self::CREATE_API_URL, [
            'subject' => 'Some test lection',
        ])->assertStatus(422);

        //no subject
        $this->postJson(self::CREATE_API_URL, [
            'description' => 'some test description',
        ])->assertStatus(422);

        $lection_id = $this->createTestLection();

        //already exists
        $this->postJson(self::CREATE_API_URL, $this->createParams())->assertStatus(422);

        $this->deleteJson(self::DELETE_API_URL . $lection_id)->assertStatus(200);
    }


    public function test_delete()
    {
        //delete bad id
        $this->deleteJson(self::DELETE_API_URL . 0)->assertStatus(404);

        $lection_id = $this->createTestLection();

        //OK
        $this->deleteJson(self::DELETE_API_URL . $lection_id)->assertStatus(200);

        //delete already deleted
        $this->deleteJson(self::DELETE_API_URL . $lection_id)->assertStatus(404);
    }


    public function test_get_info()
    {
        //get uknown
        $this->getJson(self::INFO_API_URL . 0)->assertStatus(404);

        $lection_id = $this->createTestLection();

        //OK
        $this->getJson(self::INFO_API_URL . $lection_id)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $lection_id]);

        /**
         * @TODO: add check to get info with class and students
        */

        $this->deleteJson(self::DELETE_API_URL . $lection_id)->assertStatus(200);
    }


    public function test_list()
    {
        $params = $this->createParams();
        $this->getJson(self::LIST_API_URL)->assertStatus(200)->assertJsonMissing($params);
        $lection_id = $this->createTestLection();
        $this->getJson(self::LIST_API_URL)->assertStatus(200)->assertJsonFragment($params);

        $this->deleteJson(self::DELETE_API_URL . $lection_id)->assertStatus(200);
    }


    public function test_update()
    {
        $lection_id = $this->createTestLection();

        $changeParams = [
            'subject' => 'New test lection subject',
            'description' => 'New test lection description',
        ];

        // update uknown
        $this->putJson(self::UPDATE_API_URL . 0, $changeParams)->assertStatus(404);

        // update no data given
        $this->putJson(self::UPDATE_API_URL . $lection_id)->assertStatus(422);

        //no description
        $this->putJson(self::UPDATE_API_URL . $lection_id, [
            'subject' => 'Some test lection',
        ])->assertStatus(422);

        //no subject
        $this->putJson(self::UPDATE_API_URL . $lection_id, [
            'description' => 'some test description',
        ])->assertStatus(422);

        //OK
        $this->putJson(self::UPDATE_API_URL . $lection_id, $changeParams)
            ->assertStatus(200)
            ->assertJsonFragment($changeParams);

        $this->deleteJson(self::DELETE_API_URL . $lection_id)->assertStatus(200);
    }
}
