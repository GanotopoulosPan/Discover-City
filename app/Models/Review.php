<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Το μοντέλο Review αντιπροσωπεύει μια κριτική που μπορεί να αφήσει ένας χρήστης για μια επιχείρηση
class Review extends Model
{
    // Ορίζουμε τα πεδία που μπορούν να γίνουν με μαζική ανάθεση
    protected $fillable = [
        'user_id', 
        'business_id', 
        'rating', 
        'comment'
    ];

    // Σχέση με τον χρήστη που έκανε την κριτική
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}