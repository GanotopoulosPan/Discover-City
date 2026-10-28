<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Business;

class User extends Authenticatable implements FilamentUser
{
    
    use HasFactory, Notifiable;

    // Ορίζουμε τα πεδία που μπορούν να γίνουν με μαζική ανάθεση
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', 
    ];

    // Ορίζουμε τα πεδία που θα αποκρύπτονται  
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Ορίζουμε τα πεδία που θα μετατραπούν σε συγκεκριμένους τύπους
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // Μέθοδος για να ελέγξουμε αν ο χρήστης έχει πρόσβαση στο Filament Admin Panel
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin === true;
    }

    // Σχέση με τις επιχειρήσεις που έχει δημιουργήσει ο χρήστης
    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    // Σχέση με τα αγαπημένα που έχει προσθέσει ο χρήστης
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    
}