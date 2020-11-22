<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Lesson;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\Factories\Traits\CreateUser;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreateUser;

    /**
     * @dataProvider dataShow
     * @param int $capacity
     * @param int $reservationCount
     * @param string $expectedMark
     * @param string $button
     */
    public function testShow(int $capacity, int $reservationCount, string $expectedMark, string $button)
    {
        $lesson = factory(Lesson::class)->create(['name' => '楽しいヨガレッスン', 'capacity' => $capacity]);
        for ($i = 0; $i < $reservationCount; $i++) {
            $user = $this->createUser();
            $lesson->reservations()->save(factory(Reservation::class)->make(['user_id' => $user]));
        }

        $user = factory(User::class)->create();
        factory(UserProfile::class)->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->get("/lessons/{$lesson->id}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($lesson->name);
        $response->assertSee('空き状況: ' . $expectedMark);

        $response->assertSee($button, false);
    }

    public function dataShow()
    {
        $button = '<button class="btn btn-primary">このレッスンを予約する</button>';
        $span = '<span class="btn btn-primary disabled">予約できません</span>';

        return [
            '空きなし' => [
                'capacity' => 1,
                'reservationCount' => 1,
                'expectedMark' => '×',
                'span' => $span
            ],
            '残りわずか' => [
                'capacity' => 5,
                'reservationCount' => 4,
                'expectedMark' => '△',
                'button' => $button
            ],
            '空き十分　' => [
                'capacity' => 5,
                'reservationCount' => 0,
                'expectedMark' => '◎',
                'button' => $button
            ],
        ];
    }
}
