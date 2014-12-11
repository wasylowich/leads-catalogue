<?php
namespace Models;

class Lead extends \Eloquent {

    // Validation rules
    public static $rules = [
        'name'        => 'required',
        'email'       => 'required|email',
        'phone'       => 'required|regex:/^(\+45)? ?\d{2} ?\d{2} ?\d{2} ?\d{2}$/',
        'address'     => 'required',
        'postal_code' => 'required|integer|min:1000|max:9999',
        'city'        => 'required',
        'newsletter'  => 'required|boolean',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'postal_code',
        'city',
        'newsletter',
    ];

    public function newsletterSubscription()
    {
        return $this->hasOne('Models\NewsletterSubscription');
    }

}
