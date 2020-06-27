<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PhotoListApiTest extends TestCase
{
    use RefreshDatabase;

    public function testPhotoListJson()
    {
        factory(Photo::class, 5)->create();

        $response = $this->json('GET', route('photo.index'));

        $photos = Photo::with(['owner'])->orderBy('created_at', 'desc')->get();

        $expected_data = $photos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'url' => $photo->url,
                'owner' => [
                    'name' => $photo->owner->name,
                ],
            ];
        })
        ->all();

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment([
                "data" => $expected_data,
            ]);
    }
}
