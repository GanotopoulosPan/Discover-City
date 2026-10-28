<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Αξιοθέατα - Discover City</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="main-header">
        <div class="header-bg">
            <img src="{{ asset('images/volos_sights.png') }}" alt="Volos Sights Background">
            <div class="overlay"></div>
        </div>

        <x-header />

        <div class="hero-text">
            <h1>Αξιοθέατα</h1>
            <p>Ανακαλύψτε την ιστορία και τον πολιτισμό του Βόλου</p>
        </div>
    </header>

    <div class="main-content-wrapper">
        <div class="main-layout">

            <main class="content-container">
                <div class="places-grid" id="placesGrid">
                    <div class="latest-grid">
                        
                        @foreach($sights as $sight)
                        <div class="latest-card" data-location="center">

                            <a href="/sights/{{ $sight->id }}" class="business-card-link">
                                
                                @if($sight->photo_profile)
                                    <img src="{{ asset('storage/' . $sight->photo_profile) }}" 
                                        alt="{{ $sight->name }}" 
                                        class="business-img">
                                @else
                                    <img src="{{ asset($sight->photo_profile) }}" 
                                        alt="{{ $sight->name }}" 
                                        class="business-img">
                                @endif
                                
                                <div class="stars-container">
                                    <h3>{{ $sight->name }}</h3>
                                </div>
                                    
                                <p>{{ Str::limit($sight->mini_description, 80) }}</p>
                            </a>
                        </div>
                        @endforeach

                    </div>
                </div>
            </main>
        </div>

        <footer>
            <p>© 2026 Discover City</p>
        </footer>
    </div>

    <script src="{{ asset('js/filament/my-scripts.js') }}"></script>
    

</body>
</html>