<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $event = Event::paginate(9);
        if ($event) {
            return response()->json([
                'status' => 'success',
                'data' => $event,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => $event,
            ], 402);
        }
    }

    public function store(EventRequest $request)
    {
        $event = new Event();
        $event->course_name = $request->course_name ;
        $event->date = $request->date;
        $event->time = $request->time;
        $event->end_time = $request->end_time;
        $event->locations = $request->locations;
        $event->descriptions = $request->descriptions;
        if ($request->file('image')) {
            if (!empty($event->image)) {
                removeImage($event->image);
            }
            $event->image = saveImage($request, 'image');
        }
        $event->save();
        return response()->json(['message' => 'Event created!'], 200);

    }
    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        if ($event) {
            return response()->json([
                'status' => 'success',
                'data' => $event,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => 'Record can not be found',
            ],404);
        }
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);
        $event->course_name = $request->course_name ?? $event->course_name;
        $event->date = $request->date ?? $event->date;
        $event->time = $request->time ?? $event->time;
        $event->end_time = $request->end_time ?? $event->end_time;
        $event->locations = $request->locations ?? $event->locations;
        $event->descriptions = $request->descriptions ?? $event->descriptions;
        if ($request->file('image')) {
            if (!empty($event->image)) {
                removeImage($event->image);
            }
            $event->image = saveImage($request, 'image');
        }
        $event->save();
        if ($event) {
            return response()->json([
                'status' => 'success',
                'message' => 'Event updated successfully',
                'data' => $event,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        if ($event) {
            $event->delete();
            if (!empty($event->image)) {
                removeImage($event->image);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Event deleted successfully',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Record can not found',
            ]);
        }
    }
}
