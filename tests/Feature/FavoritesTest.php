<?php

namespace Tests\Feature;

use App\Reply;
use Tests\TestCase;
use Tests\DatabaseTest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FavoritesTest extends DatabaseTest
{
	/** @test */
	public function unauthenticated_users_cannot_favorite_anything()
	{
        $this->withExceptionHandling()
        	 ->post('replies/1/favorites')
        	 ->assertRedirect('login');
	}

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
    	$this->signIn();

    	$reply = create(Reply::class);

        // If I post to a 'favorites' endpoint
        $this->post('replies/' . $reply->id . '/favorites');

        // It should be recored in the database
        $this->assertCount(1, $reply->favorites);
    }
}
