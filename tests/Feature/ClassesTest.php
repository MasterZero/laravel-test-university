<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassesTest extends TestCase
{

    const TEST_CLASS_NAME = 'Tester Joe';
    const TEST_CHANGE_NAME = 'Tester Doe';


    public function test_crud()
    {
        //get uknown
        $this->getJson('/api/class/0')->assertStatus(404);

        //create noname
        $this->postJson('/api/class')->assertStatus(422);

        //create
        $response = $this->postJson('/api/class', ['name' => self::TEST_CLASS_NAME]);
        $response->assertStatus(200);
        $class_id = $response->json('id');

        //double create error
        $this->postJson('/api/class', ['name' => self::TEST_CLASS_NAME])->assertStatus(422);

        //get
        $this->getJson('/api/class/' . $class_id)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $class_id, 'name' => self::TEST_CLASS_NAME]);

        // update uknown
        $this->putJson('/api/class/0', ['name' => self::TEST_CHANGE_NAME])->assertStatus(404);

        // update no data given
        $this->putJson('/api/class/' . $class_id)->assertStatus(422);

        //update
        $this->putJson('/api/class/' . $class_id, ['name' => self::TEST_CHANGE_NAME])
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $class_id, 'name' => self::TEST_CHANGE_NAME]);

        //get
        $this->getJson('/api/class/' . $class_id)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $class_id, 'name' => self::TEST_CHANGE_NAME]);

        //list
        $this->getJson('/api/classes')->assertStatus(200)->assertJsonFragment(['id' => $class_id, 'name' => self::TEST_CHANGE_NAME]);

        //delete
        $this->deleteJson('/api/class/' . $class_id)->assertStatus(200);

        //list with no data
        $this->getJson('/api/classes')->assertStatus(200)->assertJsonMissing(['id' => $class_id, 'name' => self::TEST_CHANGE_NAME]);

        //delete already deleted
        $this->getJson('/api/class/' . $class_id)->assertStatus(404);
    }
}
