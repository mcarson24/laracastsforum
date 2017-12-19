<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadsTest extends TestCase
{
	use RefreshDatabase;

	public function setUp()
	{
		parent::setUp();

		$this->withExceptionHandling();

		$this->signIn();
	}

    /** @test */
    public function a_thread_can_be_updated_by_its_creator()
    {
        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $response = $this->patch($thread->path(), [
        	'title' => 'Updated Title',
        	'body'  => 'This is the updated body.'
        ]);

        $response->assertStatus(201);
        tap($thread->fresh(), function($thread) {
	        $this->assertEquals('Updated Title', $thread->title);
	        $this->assertEquals('This is the updated body.', $thread->body);
        });
    }

    /** @test */
    public function guests_cannot_update_threads()
    {
    	auth()->logOut();
    	
        $thread = create(Thread::class, [
        	'title' => 'Original Title',
        	'body'  => 'This is the original body.'
        ]);

        $response = $this->patch($thread->path(), [
        	'title' => 'Updated Title',
        	'body'  => 'This is the updated body.'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('login');
        tap($thread->fresh(), function($thread) {
	        $this->assertEquals('Original Title', $thread->title);
	        $this->assertEquals('This is the original body.', $thread->body);
        });
    }

   	/** @test */
   	public function a_thread_cannot_be_updated_by_anyone_other_than_its_creator()
   	{
   	    $thread = create(Thread::class);

   	    $response = $this->patch($thread->path(), [
   	    	'title' => 'Updated Title',
   	    	'body'  => 'This is the updated body.'
   	    ]);

   	    $response->assertStatus(403);
   	    tap($thread->fresh(), function($thread) {
   	    	$this->assertNotEquals('Updated Title', $thread->title);
	        $this->assertNotEquals('This is the updated body.', $thread->body);
   	    });
   	}

   	/** @test */
   	public function a_title_is_required_to_update_a_thread()
   	{
   	    $thread = create(Thread::class, ['user_id' => auth()->id()]);

   	    $response = $this->from($thread->path())->patch($thread->path(), [
   	    	'body'  => 'This is the updated body.'
   	    ]);

   	    $response->assertStatus(302);
   	    $response->assertRedirect($thread->path());
   	    $response->assertSessionHasErrors('title');
        $this->assertNotEquals('This is the updated body.', $thread->fresh()->body);
   	}

   	/** @test */
   	public function a_body_is_required_to_update_a_thread()
   	{
   		$this->withExceptionHandling();

   	    $this->signIn();
   	    $thread = create(Thread::class, ['user_id' => auth()->id()]);

   	    $response = $this->from($thread->path())->patch($thread->path(), [
   	    	'title'  => 'UpdatedTitle',
   	    ]);

   	    $response->assertStatus(302);
   	    $response->assertRedirect($thread->path());
   	    $response->assertSessionHasErrors('body');
    	$this->assertNotEquals('Updated Title', $thread->fresh()->title);
   	}
}
