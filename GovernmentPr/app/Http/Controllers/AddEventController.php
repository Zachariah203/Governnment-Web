<?php

namespace App\Http\Controllers;

use App\Models\AddEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddEventController extends Controller
{
    public function index()
    {
        return view('components.CMS.Event.add-event', ['event' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|max:255',
            'url'           => 'nullable|url',
            'content'       => 'required|min:10', // Min after strip_tags
            'post_date'     => 'required|date',
            'StartDate'     => 'required|date|after_or_equal:post_date',
            'EndDate'       => 'required|date|after_or_equal:StartDate',
            'description'   => 'required|max:1000',
            'category'      => 'required|max:255',
            'status'        => 'nullable|in:draft,published,archived,active,inactive',
        ]);

        // Clean content for length check
        $cleanContent = strip_tags($validated['content']);
        if (Str::length($cleanContent) < 10) {
            return redirect()->back()->withInput()->withErrors(['content' => 'Content must have at least 10 characters of text.']);
        }

        // Map form fields to table columns
        $data = [
            'Event' => $validated['title'],
            'UrlName' => $validated['url'],
            'content' => $validated['content'], // Keep HTML
            'post_date' => $validated['post_date'],
            'StartDate' => $validated['StartDate'],
            'EndDate' => $validated['EndDate'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'status' => $validated['status'] ?? 'draft',
            // 'categoryID' => null, // Set if needed
        ];

        Log::info('Store: Mapped data', $data);

        DB::enableQueryLog();
        $event = AddEvent::create($data);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        Log::info('Store: Queries', $queries);
        Log::info('Store: Created', ['event' => $event ? $event->toArray() : 'NULL']);

        if (!$event || !$event->exists) {
            Log::error('Store failed', ['data' => $data]);
            return redirect()->back()->withInput()->with('error', 'Failed to create event.');
        }

        return redirect()->route('CMS.event')->with('success', 'Event created successfully!');
    }

    public function edit(AddEvent $event) // Auto-binds via {event}
    {
        return view('components.CMS.Event.add-event', compact('event'));
    }

    public function update(Request $request, AddEvent $event)
    {
        $validated = $request->validate([
            'title'         => 'required|max:255',
            'url'           => 'nullable|url',
            'content'       => 'required|min:10',
            'post_date'     => 'required|date',
            'StartDate'     => 'required|date|after_or_equal:post_date',
            'EndDate'       => 'required|date|after_or_equal:StartDate',
            'description'   => 'required|max:1000',
            'category'      => 'required|max:255',
            'status'        => 'nullable|in:draft,published,archived,active,inactive',
        ]);

        // Clean content check
        $cleanContent = strip_tags($validated['content']);
        if (Str::length($cleanContent) < 10) {
            return redirect()->back()->withInput()->withErrors(['content' => 'Content must have at least 10 characters of text.']);
        }

        // Map form fields to table columns
        $data = [
            'Event' => $validated['title'],
            'UrlName' => $validated['url'],
            'content' => $validated['content'],
            'post_date' => $validated['post_date'],
            'StartDate' => $validated['StartDate'],
            'EndDate' => $validated['EndDate'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'status' => $validated['status'] ?? $event->status,
        ];

        Log::info('Update: Mapped data for ID ' . $event->EventID, $data);

        DB::enableQueryLog();
        $updated = $event->update($data);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        Log::info('Update: Queries', $queries);
        Log::info('Update: Rows affected', ['updated' => $updated]);

        if (!$updated) {
            Log::error('Update failed', ['data' => $data, 'id' => $event->EventID]);
            return redirect()->back()->withInput()->with('error', 'Failed to update event.');
        }

        return redirect()->route('CMS.event')->with('success', 'Event updated successfully!');
    }

    public function destroy(AddEvent $event) // Auto-binds
    {
        $deleted = $event->delete();

        if (!$deleted) {
            Log::error('Delete failed for event ID: ' . $event->EventID);
            return redirect()->back()->with('error', 'Failed to delete event. Check logs.');
        }

        Log::info('Event deleted successfully', ['id' => $event->EventID]);
        return redirect()->route('CMS.event')->with('success', 'Event deleted successfully!');
    }
};