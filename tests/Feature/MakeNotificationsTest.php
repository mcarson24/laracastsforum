<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\DatabaseTest;

class MakeNotificationsTest extends DatabaseTest
{
    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_recieves_a_new_reply_from_another_user()
    {
        $this->signIn();
        $thread = create(Thread::class)->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

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
    	$user = $this->signIn();
        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
        	'user_id' => create(User::class)->id,
        	'body'    => 'I agree completely!'
    	]);

    	$response = $this->getJson("profiles/{$user->name}/notifications")->json();

    	$this->assertCount(1, $response);
    }

    /** @test */
    public function a_user_can_mark_their_notifications_as_read()
    {
        $user = $this->signIn();
        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
        	'user_id' => create(User::class)->id,
        	'body' 	  => 'Something amazing!'
    	]);

        $this->assertCount(1, $user->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete("profiles/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
