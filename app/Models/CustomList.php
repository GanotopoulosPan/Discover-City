<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Το μοντέλο CustomList αντιπροσωπεύει μια λίστα αγαπημένων που μπορεί να δημιουργήσει ένας χρήστης
class CustomList extends Model
{
    // Ορίζουμε τα πεδία που μπορούν να γίνουν με μαζική ανάθεση
    protected $fillable = ['user_id', 'name'];
    // Σχέση με τον χρήστη που δημιούργησε τη λίστα
    public function user() {
    return $this->belongsTo(User::class);
    }
    
    // Σχέση με τα αγαπημένα που ανήκουν σε αυτή τη λίστα
    public function favorites() {
        return $this->hasMany(Favorite::class);
    }
}
