<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LectionsTest extends TestCase
{

    const TEST_DESCRIPTION = 'Some description of lection';


    public function test_crud()
    {
        $createParams = [
            'subject' => 'How to basic',
            'description' => 'Some description of lection'
        ];

        $changeParams = [
            'subject' => 'How to change basic',
            'description' => 'Some changed description of lection'
        ];

        //get uknown
        $this->getJson('/api/lection/0')->assertStatus(404);

        //create noname
        $this->postJson('/api/lection')->assertStatus(422);

        //create
        $response = $this->postJson('/api/lection', $createParams);
        $response->assertStatus(200);
        $lection_id = $response->json('id');

        //double create error
        $this->postJson('/api/lection', $createParams)->assertStatus(422);

        //get
        $this->getJson('/api/lection/' . $lection_id)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $lection_id]);

        // update uknown
        $this->putJson('/api/lection/0', $changeParams)->assertStatus(404);

        // update no data given
        $this->putJson('/api/lection/' . $lection_id)->assertStatus(422);

        //update
        $this->putJson('/api/lection/' . $lection_id, $changeParams)
            ->assertStatus(200)
            ->assertJsonFragment($changeParams);

        //get
        $this->getJson('/api/lection/' . $lection_id)
            ->assertStatus(200)
            ->assertJsonFragment($changeParams);

        //list
        $this->getJson('/api/lections')->assertStatus(200)->assertJsonFragment($changeParams);

        //delete
        $this->deleteJson('/api/lection/' . $lection_id)->assertStatus(200);

        //list with no data
        $this->getJson('/api/lections')->assertStatus(200)->assertJsonMissing($changeParams);

        //delete already deleted
        $this->getJson('/api/lection/' . $lection_id)->assertStatus(404);
    }

    /**
     * @TODO: add student and classes check
    */
}
