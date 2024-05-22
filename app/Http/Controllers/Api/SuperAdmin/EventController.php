<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $event = Event::get();
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

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        // Create the event
        $event = Event::create($request->validated());
        if ($event) {
            return response()->json([
                'status' => 'success',
                'message' => 'Event created successfully',
                'data' => $event,
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'internal serve errror',
                'data' => $event,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
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
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /** Update the specified resource in storage. */
    // public function update(Request $request, string $id)
    // {
    //     $event = Event::findOrFail($id);
    //     if ($event) {
    //         $event->update($request->validated());

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Event updated successfully',
    //             'data' => $event,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 'errro',
    //             'message' => 'Record cand not be found',
    //             'data' => $event,
    //         ]);
    //     }
    // }
    
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);
        $event->course_name = $request->course_name ?? $event->course_name;
        $event->date = $request->date ?? $event->date;
        $event->time = $request->time ?? $event->time;
        $event->end_time = $request->end_time ?? $event->end_time;
        $event->locations = $request->locations ?? $event->locations;
        $event->descriptions = $request->descriptions ?? $event->descriptions;
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        if ($event) {
            $event->delete();

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
