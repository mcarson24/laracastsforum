<?php

namespace Tests\Feature;

use App\User;
use Tests\DatabaseTest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmYourEmailAddress;
use Illuminate\Auth\Events\Registered;

class RegistrationTest extends DatabaseTest
{
    /** @test */
    public function a_confirmation_email_is_sent_to_the_user_upon_registration()
    {
    	Mail::fake();
    	$user = create(User::class);
        event(new Registered($user));

        Mail::assertSent(ConfirmYourEmailAddress::class, function($mailable) use ($user) {
        	return $mailable->email = $user->email;
        });
    }

    /** @test */
    public function users_can_confirm_their_email_addresses()
    {
        $this->post('register', [
        	'name' => 'JaneDoe',
        	'email' => 'jane@example.com',
        	'password' => 'super-secret-password',
        	'password_confirmation' => 'super-secret-password',
    	]);

    	$user = User::whereName('JaneDoe')->first();

    	$this->assertFalse($user->confirmed);
    	$this->assertNotNull($user->confirmation_token);

    	$response = $this->get("register/confirm?token={$user->confirmation_token}");

    	$this->assertTrue($user->fresh()->confirmed);
    	$response->assertRedirect('threads');
    }
}
