<?php
namespace Models;

class NewsletterSubscription extends \Eloquent {

    // Validation rules
    public static $rules = [
        'lead_id' => 'required|exists:leads,id',
        'format'  => 'required',
    ];

    protected $fillable = [
        'lead_id',
        'format',
    ];

    public function lead()
    {
        return $this->belongsTo('Models\Lead');
    }

}
