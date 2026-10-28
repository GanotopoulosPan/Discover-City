<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Φαγητό & Ποτό - Discover City</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="main-header">
        <div class="header-bg">
            <img src="{{ asset('images/volos_foodanddrink.png') }}" alt="Volos Food Background">
            <div class="overlay"></div>
        </div>

        <x-header />

        <div class="hero-text">
            <h1>Φαγητό & Ποτό</h1>
            <p>Απολαύστε τσιπουράδικα, καφέ και νυχτερινή ζωή</p>
        </div>
    </header>

    <div class="main-content-wrapper">
        <div class="main-layout">
            
            <aside class="filters-section">
                <h2>Φίλτρα</h2>
                
                <form action="{{ url()->current() }}" method="GET">
                    
                    <div class="filter-group filter-group-primary">
                        <label class="filter-label-blue">Περιοχή:</label>
                        <div class="checkbox-list" id="areaContainer">
                            <label class="checkbox-item"><input type="checkbox" name="area[]" value="center" {{ request()->has('area') && in_array('center', request('area')) ? 'checked' : '' }}> Κέντρο / Λιμάνι</label>
                            <label class="checkbox-item"><input type="checkbox" name="area[]" value="pilion" {{ request()->has('area') && in_array('pilion', request('area')) ? 'checked' : '' }}> Πήλιο / Βουνό</label>
                            <label class="checkbox-item"><input type="checkbox" name="area[]" value="neaionia" {{ request()->has('area') && in_array('neaionia', request('area')) ? 'checked' : '' }}> Νέα Ιωνία</label>
                            <label class="checkbox-item"><input type="checkbox" name="area[]" value="alykes" {{ request()->has('area') && in_array('alykes', request('area')) ? 'checked' : '' }}> Αλυκές</label>
                            <label class="checkbox-item"><input type="checkbox" name="area[]" value="agria" {{ request()->has('area') && in_array('agria', request('area')) ? 'checked' : '' }}> Αγριά</label>
                            <label class="checkbox-item"><input type="checkbox" name="area[]" value="neadimitriada" {{ request()->has('area') && in_array('neadimitriada', request('area')) ? 'checked' : '' }}> Νέα Δημητριάδα</label>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Είδος:</label>
                        <div class="checkbox-list">
                            <label class="checkbox-item">
                                <input type="checkbox" name="type[]" value="restaurant" {{ in_array('restaurant', request('type', [])) ? 'checked' : '' }}> Εστιατόριο
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="type[]" value="taverna" {{ in_array('taverna', request('type', [])) ? 'checked' : '' }}> Ταβέρνα
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="type[]" value="mezedopoleio" {{ in_array('mezedopoleio', request('type', [])) ? 'checked' : '' }}> Μεζεδοπωλείο
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="type[]" value="cafe" {{ in_array('cafe', request('type', [])) ? 'checked' : '' }}> Καφετέρια
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="type[]" value="bar" {{ in_array('bar', request('type', [])) ? 'checked' : '' }}> Bar
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="type[]" value="street_food" {{ in_array('street_food', request('type', [])) ? 'checked' : '' }}> Street Food
                            </label>
                        </div>
                    </div>

                    <div class="filter-group filter-group-divider">
                        <label class="filter-label">Παροχές:</label>
                        <div class="checkbox-list">
                            <label class="checkbox-item">
                                <input type="checkbox" name="amenities[]" value="vegan" {{ in_array('vegan', request('amenities', [])) ? 'checked' : '' }}> Vegan Επιλογές
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="amenities[]" value="live_music" {{ in_array('live_music', request('amenities', [])) ? 'checked' : '' }}> Ζωντανή Μουσική
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="amenities[]" value="accessibility" {{ in_array('accessibility', request('amenities', [])) ? 'checked' : '' }}> Πρόσβαση ΑμεΑ
                            </label>
                            <label class="checkbox-item">
                                <input type="checkbox" name="amenities[]" value="view" {{ in_array('view', request('amenities', [])) ? 'checked' : '' }}> Θέα
                            </label>
                        </div>
                    </div>
                    <div class="filter-group">
                    <label class="filter-label">Ελάχιστη Βαθμολογία:</label>
                    <div class="checkbox-list" id="ratingContainer">
                        <label class="checkbox-item">
                            <input type="radio" name="min_rating" value="" checked> 
                            Οποιαδήποτε
                        </label>
                        <label class="checkbox-item">
                            <input type="radio" name="min_rating" value="1"> 
                            1+ Αστέρια <span style="color: #ffd700;">★☆☆☆☆</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="radio" name="min_rating" value="2"> 
                            2+ Αστέρια <span style="color: #ffd700;">★★☆☆☆</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="radio" name="min_rating" value="3"> 
                            3+ Αστέρια <span style="color: #ffd700;">★★★☆☆</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="radio" name="min_rating" value="4"> 
                            4+ Αστέρια <span style="color: #ffd700;">★★★★☆</span>
                        </label>
                        
                        <label class="checkbox-item">
                            <input type="radio" name="min_rating" value="5"> 
                            5 Αστέρια <span style="color: #ffd700;">&nbsp;&nbsp;★★★★★</span>
                        </label>
                    </div>
                </div>
                    
                    <button type="submit" class="btn-apply">ΕΦΑΡΜΟΓΗ</button>
                    
                    <a href="{{ url()->current() }}" class="btn-clear">Καθαρισμός</a>
                </form>
            </aside>

            <main class="content-container">
                <div class="places-grid" id="placesGrid">
                    <div class="latest-grid">

                    @foreach($businesses->where('category','food_drink') as $business)
                    <div class="latest-card" data-location="{{ $business->location_key }}" data-category="{{ $business->subcategory_key }}">

                        @auth
                            @if(\App\Models\Business::where('user_id', auth()->id())->doesntExist())
                                <button 
                                    type="button"
                                    class="fav-btn {{ $business->is_favorited ? 'active' : '' }}"
                                    data-id="{{ $business->id }}">
                                        <svg viewBox="0 0 24 24" class="fav-icon">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                </button>
                            @endif
                        @endauth

                        <a href="{{ route('business.show',$business->id) }}" class="business-card-link">
                            @if($business->profile_photo)
                                <img src="{{ asset('storage/' . $business->profile_photo) }}" alt="{{ $business->name }}" class="business-img">
                            @else
                                <img src="{{ asset('images/default.png') }}" alt="Χωρίς Εικόνα" class="business-img">
                            @endif
                            
                            <div class="stars-container">
                                <h3>{{ $business->name }}</h3>
                                
                                @php
                                    $avgRating = $business->reviews_avg_rating ?: 0;
                                    $totalReviews = $business->reviews_count ?: 0;
                                    $fullStars = floor($avgRating);
                                    $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                @endphp
                                
                                <div class="stars">
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor

                                    @if($hasHalfStar)
                                        <i class="fas fa-star-half-alt"></i>
                                    @endif

                                    @for($i = 0; $i < $emptyStars; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                </div>

                                <div class="rating-text">
                                    {{ number_format($avgRating, 1) }} 
                                    <span class="rating-count">({{ $totalReviews }} {{ $totalReviews == 1 ? 'αξιολόγηση' : 'αξιολογήσεις' }})</span>
                                </div>
                            </div>
                            
                            <p>{{ Str::limit($business->minidescription, 80) }}</p>
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