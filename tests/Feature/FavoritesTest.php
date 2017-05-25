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

    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create(Reply::class);

        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('Should not have inserted the same record twice.');
        }
        
        $this->assertCount(1, $reply->favorites);
    }
}
