<?php

namespace App\Http\Controllers\Lesson;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Reservation;
use App\Notifications\ReservationCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class ReserveController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Lesson $lesson)
    {
        $user = Auth::user();
        try {
            $user->canReserve($lesson);
        } catch (Exception $e) {
            return back()->withErrors('予約できません。：' . $e->getMessage());
        }
        Reservation::create(['lesson_id' => $lesson->id, 'user_id' => $user->id]);

        $user->notify(new ReservationCompleted($lesson));
        return redirect()->route('lessons.show', ['lesson' => $lesson]);
    }
}
