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
    	
        $this->post(route('register'), [
            'name' => 'JaneDoe',
            'email' => 'jane@example.com',
            'password' => 'super-secret-password',
            'password_confirmation' => 'super-secret-password',
        ]);

        $user = User::first();

        Mail::assertSent(ConfirmYourEmailAddress::class, function($mailable) use ($user) {
        	return $mailable->email = $user->email;
        });
    }

    /** @test */
    public function users_can_confirm_their_email_addresses()
    {
        Mail::fake();

        $this->post(route('register'), [
        	'name' => 'JaneDoe',
        	'email' => 'jane@example.com',
        	'password' => 'super-secret-password',
        	'password_confirmation' => 'super-secret-password',
    	]);

    	$user = User::whereName('JaneDoe')->first();

    	$this->assertFalse($user->confirmed);
    	$this->assertNotNull($user->confirmation_token);

    	$this->get(route('register.confirm', ['token' => $user->confirmation_token]))
    	     ->assertRedirect(route('threads.index'));

        tap($user->fresh(), function($user) {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
    }

    /** @test */
    public function confirming_an_invalid_token()
    {
        $response = $this->get(route('register.confirm', ['token' => 'invalid-token']));

        $response->assertRedirect('threads')
                 ->assertSessionHas('flash');
    }
}
