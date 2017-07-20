<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Notifications\DatabaseNotification;
use Tests\DatabaseTest;

class MakeNotificationsTest extends DatabaseTest
{
    public function setUp()
    {
        parent::setUp();

        $this->user = $this->signIn();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_recieves_a_new_reply_from_another_user()
    {
        $thread = create(Thread::class)->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        // Shouldn't prepare a notification for the user if they were the one who left a reply.
        $thread->addReply([
        	'user_id' => auth()->id(),
        	'body'    => 'YoYoYo!'
    	]);

    	$this->assertCount(0, auth()->user()->fresh()->notifications);

        // Each time a new reply is left (from a different user) on a subscribed thread
        $thread->addReply([
        	'user_id' => create(User::class)->id,
        	'body'    => 'First!'
    	]);

        // Then a reply should be prepared for the user.
    	$this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        create(DatabaseNotification::class);

    	$this->assertCount(1, $this->getJson("profiles/{$this->user->name}/notifications")->json());
    }

    /** @test */
    public function a_user_can_mark_their_notifications_as_read()
    {
        $notification = create(DatabaseNotification::class);

        $this->assertCount(1, $this->user->unreadNotifications);

        $this->delete("profiles/{$this->user->name}/notifications/{$notification->id}");

        $this->assertCount(0, $this->user->fresh()->unreadNotifications);
    }
}
