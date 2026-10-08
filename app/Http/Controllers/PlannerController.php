<?php

namespace App\Http\Controllers;

use App\Models\BlackoutDate;
use App\Models\Experience;
use Illuminate\View\View;

class PlannerController extends Controller
{
    public function index(): View
    {
        return view('pages.planner', [
            'experiences' => Experience::active()->get(),
            'categories' => config('experiences.categories'),
            'blackouts' => BlackoutDate::whereDate('date', '>=', today())->pluck('date')->map->toDateString()->all(),
        ]);
    }
}
