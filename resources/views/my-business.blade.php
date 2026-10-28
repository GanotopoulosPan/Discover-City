<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Οι Επιχειρήσεις Μου - Discover City</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        
    </style>
</head>
<body class="mybus-body">
<header class="sec-header">
    <x-header />
</header>

<div class="mybus-container">
    <div class="mybus-header">
        <h1 class="mybus-title">Οι Επιχειρήσεις Μου</h1>
        <a href="{{ route('business.create') }}" class="mybus-btn-add">
            <i class="fas fa-plus"></i> Νέα Καταχώρηση
        </a>
    </div>

    @if(session('success'))
        <div class="mybus-alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($businesses->isEmpty())
        <div class="mybus-empty-state">
            <i class="fas fa-store-slash mybus-empty-icon"></i>
            <p>Δεν έχετε καταχωρήσει καμία επιχείρηση ακόμα.</p>
        </div>
    @else
        <div class="mybus-grid">
            @foreach($businesses as $business)
                <div class="mybus-card">
                    @php 
                        $imagePath = (is_array($business->photos) && count($business->photos) > 0) 
                                     ? asset('storage/' . $business->photos[0]) 
                                     : 'https://via.placeholder.com/400x250?text=No+Image';

                        $catTranslations = [
                            'accommodation' => 'Διαμονή',
                            'food_drink' => 'Φαγητό - Ποτό',
                            'activity' => 'Δραστηριότητα',
                        ];
                    @endphp

                    <a href="{{ route('business.show', $business->id) }}" class="mybus-card-link">
                        @if($business->profile_photo)
                                <img src="{{ asset('storage/' . $business->profile_photo) }}" 
                                    alt="{{ $business->name }}" 
                                    class="mybus-card-img">
                             @else
                                <img src="{{ asset('images/default.png') }}" 
                                    alt="Χωρίς Εικόνα" 
                                    class="mybus-card-img">
                            @endif

                        <div class="mybus-card-body">
                            <span class="mybus-category-tag">
                                {{ $catTranslations[$business->category] ?? $business->category }}
                            </span>
                            
                            <h3 class="mybus-name">{{ $business->name }}</h3>
                            
                            <div class="mybus-info-row">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $business->address }}</span>
                            </div>
                            
                            <div class="mybus-info-row">
                                <i class="fas fa-phone"></i>
                                <span>{{ $business->phone }}</span>
                            </div>

                            <div class="mybus-info-row">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $business->email }}</span>
                            </div>
                        </div>
                    </a>

                    <div class="mybus-card-footer">
                        <a href="{{ route('business.edit', $business->id) }}" class="mybus-btn mybus-btn-edit">
                            <i class="fas fa-pen"></i> Επεξεργασία
                        </a>

                        <form action="{{ route('business.destroy', $business->id) }}" method="POST" class="mybus-delete-form" onsubmit="return confirm('Οριστική διαγραφή;');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="mybus-btn mybus-btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="{{ asset('js/filament/my-scripts.js') }}"></script>

</body>
</html>