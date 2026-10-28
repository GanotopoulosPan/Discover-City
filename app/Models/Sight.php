<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Το μοντέλο Sight αντιπροσωπεύει ένα αξιοθέατο που μπορεί να επισκεφθεί ένας χρήστης
class Sight extends Model
{
    
    // Ορίζουμε τα πεδία που μπορούν να γίνουν με μαζική ανάθεση
    protected $fillable = [
        'name',
        'photo_profile',
        'photos',
        'mini_description',
        'description',
    ];

    // Μετατροπή του πεδίου photos σε array
    protected $casts = [
        'photos' => 'array',
    ];

}