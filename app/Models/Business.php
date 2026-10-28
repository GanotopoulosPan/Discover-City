<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;
    use Searchable;
    // Ορίζουμε τα πεδία που μπορούν να γίνουν με μαζική ανάθεση
    protected $fillable = [
        'user_id',
        'name', 
        'address','tk','lat','lon', 'phone', 'email', 'website',
        'category', 'area','ama', 'age_group', 'type', 
        'amenities', 'duration', 'difficulty', 'schedule', 
        'description', 'minidescription', 'photos','profile_photo'
    ];
    // Μετατροπή των πεδίων amenities και photos σε array
    protected $casts = [
        'amenities' => 'array',
        'photos' => 'array',
    ];

    // Σχέση με τον χρήστη που δημιούργησε το business
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Σχέση με τα reviews
    public function reviews() {
        return $this->hasMany(Review::class);
    }

    // Μέθοδος για να υπολογίζει τον μέσο όρο των αξιολογήσεων
    public function averageRating() {
        return round($this->reviews()->avg('rating'), 1) ?: 0;
    }

    // Μέθοδος για να υπολογίζει τον αριθμό των αξιολογήσεων
    public function favoredBy()
    {
         return $this->belongsToMany(User::class, 'favorites');
    }

    // Μέθοδος για να μετατρέπει το μοντέλο σε array για αναζήτηση με Laravel Scout
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'type' => $this->type,
        ];
    }
    
    // Σχέση με τα αγαπημένα
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /*
      Scope για να φορτώνει τις λεπτομέρειες μαζί με τα reviews και αν είναι στα αγαπημένα του χρήστη
      q ->To main query, όταν καλούμε το scope, θα προσθέτει αυτόματα τα reviews και το is_favorited
      sq -> Subquery, για να ελέγχει αν το business είναι στα αγαπημένα του τρέχοντος χρήστη */

    public function scopeWithDetails($query)
    {
        return $query->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->when(auth()->check(), function ($q) {
                $q->withExists(['favorites as is_favorited' => function ($sq) {
                    $sq->where('user_id', auth()->id());
                }]);
            });
    }

}


