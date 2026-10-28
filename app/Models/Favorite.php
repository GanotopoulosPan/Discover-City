<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Το μοντέλο Favorite αντιπροσωπεύει ένα αγαπημένο που μπορεί να προσθέσει ένας χρήστης σε μια λίστα
class Favorite extends Model
{
    // Ορίζουμε τα πεδία που μπορούν να γίνουν με μαζική ανάθεση
    protected $fillable = ['user_id', 'business_id', 'custom_list_id'];

    // Σχέση με το business που είναι το αγαπημένο
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    // Σχέση με τον χρήστη που πρόσθεσε το αγαπημένο
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Σχέση με τη λίστα στην οποία ανήκει το αγαπημένο
    public function customList() {
        return $this->belongsTo(CustomList::class);
    }
}