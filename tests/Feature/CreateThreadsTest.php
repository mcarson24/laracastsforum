<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use App\Activity;
use Tests\DatabaseTest;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends DatabaseTest
{

    public function setUp()
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function() {
            return \Mockery::mock(Recaptcha::class, function($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    /** @test */
    public function guests_cannot_create_threads()
    {
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);

        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    public function guests_cannot_see_new_forum_creation_page()
    {
        $this->withExceptionHandling();

        $this->get('threads/create')
             ->assertRedirect('login');
    }

    /** @test */
    public function a_new_user_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory(User::class)->create();
        $this->signIn($user);
        $thread = create(Thread::class);

        $response = $this->post('threads', $thread->toArray());

        $response->assertRedirect('threads');
        $response->assertSessionHas('flash', 'You must confirm your email address before creating threads.');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $response = $this->publishThread(['title' => 'Test title', 'body' => 'A test body.']);

        $this->get($response->headers->get('Location'))
             ->assertSee('Test title')
             ->assertSee('A test body.');
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread([
            'title' => null,
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread([
            'body' => null
        ])->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_recaptcha_verification()
    {
        unset(app()[Recaptcha::class]);

        $this->publishThread([
            'g-recaptcha-response' => 'test-token'
        ])->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->signIn(factory(User::class)->states('confirmed')->create());

        create(Thread::class, [], 2);
        $thread = create(Thread::class, ['title' => 'Foo Title']);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');
        
        $thread = $this->postJson('threads', $thread->toArray() + ['g-recaptcha-response' => 'test-token'])->json();

        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
    }

    /** @test */
    public function a_thread_with_a_title_ending_in_a_number_generates_the_proper_slug()
    {
        $this->signIn(factory(User::class)->states('confirmed')->create());
        
        create(Thread::class, ['title' => 'We are number 1']);
        
        $thread = create(Thread::class, ['title' => 'We are number 1']);
        $thread = $this->postJson('threads', $thread->toArray() + ['g-recaptcha-response' => 'test-token'])->json();

        $this->assertEquals("we-are-number-1-{$thread['id']}", $thread['slug']);
    }

    /** @test */
    public function a_thread_requires_a_valid_channel_id()
    {
        factory(Channel::class, 2)->create();

        $this->publishThread([
            'channel_id' => null
        ])->assertSessionHasErrors('channel_id');

        $this->publishThread([
            'channel_id' => 99999
        ])->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_can_be_deleted_by_authorized_users()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create(Thread::class);

        $this->delete($thread->path())
             ->assertRedirect('login');
        $this->assertDatabaseHas('threads', ['id' => $thread->id]);

        $this->signIn();
        $this->delete($thread->path())
             ->assertStatus(403);
    }

    /** @test */
    public function a_threads_replies_are_also_deleted_when_a_thread_is_deleted()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    public function a_threads_activity_is_also_deleted_when_a_thread_is()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertEquals(0, Activity::count());
    }

    protected function publishThread($attributes = [])
    {
        $this->withExceptionHandling()
             ->signIn(factory(User::class)->states('confirmed')->create());

        $thread = make(Thread::class, $attributes);

        return $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'test-token']);
    }
}
