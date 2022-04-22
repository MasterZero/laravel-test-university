<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_crud()
    {
        //get uknown
        $this->getJson('/api/student/0')->assertStatus(404);

        //create no params
        $this->postJson('/api/student')->assertStatus(422);


        $response = $this->postJson('/api/class', ['name' => 'test student class name']);
        $response->assertStatus(200);
        $class_id = $response->json('id');

        $createParams = [
            'name' => 'Joe',
            'email' => 'joe@google.com',
            'class_id' => $class_id,
        ];

        $changeParams = [
            'name' => 'Doe',
            'email' => 'doe@google.com',
            'class_id' => $class_id,
        ];

        //create
        $response = $this->postJson('/api/student', $createParams);
        $response->assertStatus(200);
        $student_id = $response->json('id');

        // //double create error
        $this->postJson('/api/student', $createParams)->assertStatus(422);

        //get
        $this->getJson('/api/student/' . $student_id)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $student_id]);

        // update uknown
        $this->putJson('/api/student/0', $changeParams)->assertStatus(404);

        // update no data given
        $this->putJson('/api/student/' . $student_id)->assertStatus(422);

        //update
        $this->putJson('/api/student/' . $student_id, $changeParams)
            ->assertStatus(200)
            ->assertJsonFragment($changeParams);

        //get
        $this->getJson('/api/student/' . $student_id)
            ->assertStatus(200)
            ->assertJsonFragment($changeParams);

        //list
        $this->getJson('/api/students')->assertStatus(200)->assertJsonFragment($changeParams);

        //delete test class
        $this->deleteJson('/api/class/' . $class_id)->assertStatus(200);

        //is student still here after delete class
        $this->getJson('/api/student/' . $student_id)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $student_id]);

        //delete
        $this->deleteJson('/api/student/' . $student_id)->assertStatus(200);

        //list with no data
        $this->getJson('/api/students')->assertStatus(200)->assertJsonMissing($changeParams);

        //delete already deleted
        $this->getJson('/api/student/' . $student_id)->assertStatus(404);
    }

    /**
     * @TODO: add check to get class and lections
    */
}
