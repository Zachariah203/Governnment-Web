<?php

namespace App\Http\Controllers;

use App\Models\AddEvent;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = AddEvent::latest('EventID')->paginate(10); // From 'events' table
        return view('components.CMS.Event.event', compact('events'));
    }
}