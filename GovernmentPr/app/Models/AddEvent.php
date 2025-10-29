<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddEvent extends Model
{
    use HasFactory; 
    protected $table = 'events'; // Matches unified migration
    protected $primaryKey = 'EventID';
    public $incrementing = true;

    protected $fillable = [
        'categoryID',
        'Event', // Maps to 'title' in form
        'UrlName', // Maps to 'url'
        'content',     
        'post_date',
        'StartDate',
        'EndDate',
        'description',
        'category',
        'status',
    ];

    public $timestamps = true;

    protected $casts = [
        'post_date' => 'date',
        'StartDate' => 'date',
        'EndDate' => 'date',
        // 'content' => 'array', // Removed; store as HTML string
    ];

    // Route binding for EventID
    public function getRouteKeyName()
    {
        return $this->primaryKey;
    }

    // Accessor for title (use $event->title in blades)
    public function getTitleAttribute()
    {
        return $this->Event ?? '';
    }

    // Accessor for url
    public function getUrlAttribute()
    {
        return $this->UrlName ?? '';
    }
}