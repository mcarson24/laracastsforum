<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\DatabaseTest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AddAvatarTest extends DatabaseTest
{
    /** @test */
    public function only_members_can_add_avatars()
    {
    	$this->withExceptionHandling();

        $response = $this->postJson('api/users/1/avatar');

        $response->assertStatus(401);
    }

    /** @test */
    public function an_avatar_is_required()
    {
     	$this->withExceptionHandling()->signIn();

        $response = $this->postJson("api/users/{auth()->id()}/avatar", []);        

    	$response->assertStatus(422);   
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
		$this->withExceptionHandling()->signIn();

        $response = $this->postJson("api/users/{auth()->id()}/avatar", [
        	'avatar' => 'this-is-not-an-image'
    	]);        

    	$response->assertStatus(422);
    }

    /** @test */
    public function authenticated_users_may_add_avatars_to_their_profiles()
    {
		$this->signIn();

		Storage::fake('public');

        $response = $this->postJson("api/users/{auth()->id()}/avatar", [
        	'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
    	]);        

    	$this->assertEquals(asset("avatars/{$file->hashName()}"), auth()->user()->avatar_path);

    	Storage::disk('public')->assertExists("avatars/{$file->hashName()}");
    }
}
