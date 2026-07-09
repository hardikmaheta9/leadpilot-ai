<?php

namespace App\Http\Controllers;

use App\Models\CallLog;
use App\Models\Meeting;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        return view('calendar.index');
    }

    public function events(): JsonResponse
    {
        $events = collect();

        Task::whereNotNull('due_date')->get()->each(function ($task) use ($events) {
            $events->push([
                'title' => 'Task: ' . $task->title,
                'start' => $task->due_date->format('Y-m-d'),
                'color' => $task->status === 'completed' ? '#16A34A' : '#F59E0B',
            ]);
        });

        Meeting::all()->each(function ($meeting) use ($events) {
            $events->push([
                'title' => 'Meeting: ' . $meeting->title,
                'start' => $meeting->meeting_date->format('Y-m-d') . 'T' . $meeting->start_time,
                'color' => '#2563EB',
            ]);
        });

        CallLog::all()->each(function ($call) use ($events) {
            $events->push([
                'title' => 'Call: ' . $call->subject,
                'start' => $call->call_date->format('Y-m-d') . 'T' . $call->call_time,
                'color' => '#F97316',
            ]);
        });

        return response()->json($events->values());
    }
}