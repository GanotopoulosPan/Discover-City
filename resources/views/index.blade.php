<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Discover City</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  

</head>
<body class="index-page">

<header class="main-header">
    <div class="header-bg">
        <img src="{{ asset('images/volos_index.png') }}" alt="Volos Background">
        <div class="overlay"></div>
    </div>
    
    <x-header />

    <div class="hero-text index-hero">
        <h1>Καλώς ήρθατε στον Βόλο</h1>
        <p>Βουνό και Θάλασσα σε έναν προορισμό</p>
            
        <div class="hero-info-slider">
            <button class="slider-arrow" id="prevSlide">❮</button>
            <div class="slider-content">
                <div class="slide active">
                    <h3>Η Ιστορία του Βόλου</h3>
                    <p>Χτισμένος στον μυχό του Παγασητικού, ο Βόλος αποτελεί το ιδανικό σταυροδρόμι ανάμεσα στο βουνό των Κενταύρων και τη θάλασσα, με πλούσια βιομηχανική και αρχαία κληρονομιά...
                        <a href="{{ route('volos.info', ['topic' => 'history']) }}" class="btn slide-btn">Διαβάστε Περισσότερα</a>
                    </p>
                </div>
                <div class="slide">
                    <h3>Ο Μύθος των Αργοναυτών</h3>
                    <p>Από εδώ ξεκίνησε η θρυλική Αργοναυτική Εκστρατεία. Η σύγχρονη "Αργώ", αντίγραφο του αρχαίου πλοίου, κοσμεί την παραλία της πόλης θυμίζοντας το ένδοξο παρελθόν...
                    <a href="{{ route('volos.info', ['topic' => 'mythology']) }}" class="btn slide-btn">Διαβάστε Περισσότερα</a>
                    </p>
                </div>

                <div class="slide">
                    <h3>Γαστρονομία & Τσιπουράδικα</h3>
                    <p>Σήμα κατατεθέν της πόλης είναι τα φημισμένα τσιπουράδικα. Μια παράδοση που ξεκίνησε από τους Μικρασιάτες πρόσφυγες και εξελίχθηκε σε αγαπημένο τρόπο ζωής...
                    <a href="{{ route('volos.info', ['topic' => 'cuisine']) }}" class="btn slide-btn">Διαβάστε Περισσότερα</a>
                    </p>
                </div>
            </div>
            <button class="slider-arrow" id="nextSlide">❯</button>
        </div>
            
        <div class="slider-dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</header>

<section class="latest-places">
    <div class="content-layout">
        
        <aside class="filters-section">
            
            <div class="search">
                <form action="{{ url('/search') }}" method="GET" class="search-box">
                    <input type="text" name="user_input" placeholder="Αναζήτηση επιχείρησης..." required />
                    <button type="submit" title="Αναζήτηση">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.53 20.47l-3.73-3.73a8 8 0 10-1.06 1.06l3.73 3.73a.75.75 0 101.06-1.06zM4.75 10a5.25 5.25 0 1110.5 0 5.25 5.25 0 01-10.5 0z" /></svg>   
                    </button>
                </form>
            </div>
            
            <form id="filterForm" class="filter-form">
                <div class="filter-group">
                    <label class="filter-label">Θέλω να βρω:</label>
                    <select id="mainCategory" class="filter-select">
                        <option value="stay">Διαμονή</option>
                        <option value="food-drink">Φαγητό & Ποτό</option>
                        <option value="activities">Δραστηριότητες</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Περιοχή:</label>
                    <div class="checkbox-list" id="areaContainer">
                        <label class="checkbox-item"><input type="checkbox" name="area[]" value="center"> Κέντρο / Λιμάνι</label>
                        <label class="checkbox-item"><input type="checkbox" name="area[]" value="pilion"> Πήλιο / Βουνό</label>
                        <label class="checkbox-item"><input type="checkbox" name="area[]" value="neaionia"> Νέα Ιωνία</label>
                        <label class="checkbox-item"><input type="checkbox" name="area[]" value="alykes"> Αλυκές</label>
                        <label class="checkbox-item"><input type="checkbox" name="area[]" value="agria"> Αγριά</label>
                        <label class="checkbox-item"><input type="checkbox" name="area[]" value="neadimitriada"> Νέα Δημητριάδα</label>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Τύπος:</label>
                    <div class="checkbox-list" id="typeContainer">
                        <label class="checkbox-item"><input type="checkbox" name="type[]" value="hotel"> Ξενοδοχείο</label>
                        <label class="checkbox-item"><input type="checkbox" name="type[]" value="apartment"> Διαμέρισμα / Βίλα</label>
                    </div>
                </div>

                <div class="filter-group filter-group-bordered">
                    <label class="filter-label">Επιπλέον Παροχές:</label>
                    <div class="checkbox-list" id="amenitiesContainer">
                        <label class="checkbox-item"><input type="checkbox" name="amenities[]" value="wifi"> Δωρεάν WiFi</label>
                        <label class="checkbox-item"><input type="checkbox" name="amenities[]" value="parking"> Πάρκινγκ</label>
                        <label class="checkbox-item"><input type="checkbox" name="amenities[]" value="pool"> Πισίνα</label>
                        <label class="checkbox-item"><input type="checkbox" name="amenities[]" value="ac"> Κλιματισμός</label>
                        <label class="checkbox-item"><input type="checkbox" name="amenities[]" value="pets"> Φιλικό σε κατοικίδια</label>
                        <label class="checkbox-item"><input type="checkbox" name="amenities[]" value="breakfast"> Πρωινό</label>
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
                
                <button type="submit" class="filter-submit-btn">
                    ΑΝΑΖΗΤΗΣΗ
                </button>
            </form>
        </aside>

        <div class="latest-grid-wrapper">
            <h2 class="section-title">Νέες Καταχωρήσεις</h2>
            <div class="latest-grid {{ request()->routeIs('index') ? 'is-index' : '' }} with-margin" id="latestGrid">
                @foreach($latestBusinesses as $business)
                    <div class="latest-card" data-type="{{ $business->type }}">
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
                        <a href="{{ route('business.show', $business->id) }}" class="card-link">
                            <div class="new-badge">Νέο</div>
                            @if($business->profile_photo)
                                <img src="{{ asset('storage/' . $business->profile_photo) }}" alt="{{ $business->name }}" class="card-img">
                            @else
                                <img src="{{ asset('images/default.png') }}" alt="Χωρίς Εικόνα" class="card-img">
                            @endif
                            <h3>{{ $business->name }}</h3>
                            <div class="stars-container">
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

            <h2 class="section-title">Τα πιο δημοφιλή μέρη</h2>
            <div class="latest-grid {{ request()->routeIs('index') ? 'is-index' : '' }} ">
                @foreach($popularBusinesses as $business)
                    <div class="latest-card" data-type="{{ $business->type }}">
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
                        <a href="{{ route('business.show', $business->id) }}" class="card-link">
                            @if($business->profile_photo)
                                <img src="{{ asset('storage/' . $business->profile_photo) }}" alt="{{ $business->name }}" class="card-img">
                            @else
                                <img src="{{ asset('images/default.png') }}" alt="Χωρίς Εικόνα" class="card-img">
                            @endif
                            
                            <h3>{{ $business->name }}</h3>
                            
                            <div class="stars-container">
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

        <aside class="sidebar-right">
            <div class="weather-widget-container">
                <a class="weatherwidget-io" 
                   href="https://forecast7.com/el/39d3622d94/volos/" 
                   data-label_1="Ο ΚΑΙΡΟΣ ΣΤΟΝ" 
                   data-label_2="ΒΟΛΟ ΤΩΡΑ"
                   data-theme="pure" 
                   data-basecolor="rgba(0,0,0,0)" 
                   data-textcolor="#f3f3f3" 
                   data-suncolor="#F2C94C" 
                   data-cloudcolor="#909292" 
                   data-cloudfill="#B1B5BA"
                   data-highcolor="#eb6e6e"
                   data-lowcolor="#4a90e2"
                   data-raincolor="#62bff5"
                   data-icons="Climacons Animated">
                   VOLOS WEATHER
                </a>
                <script>
                !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
                </script>
            </div>

            <div class="ai-planner-container">
                <h3 class="ai-planner-title">Πλάνο Διακοπών</h3>
                <p class="ai-planner-desc">
                    Γράψε τι θα ήθελες να κάνεις (π.χ. "3 μέρες στο Βόλο με παιδιά, μας αρέσει το καλό φαγητό") και θα σου βγάλουμε εμείς το πρόγραμμα διακοπών!
                </p>
                
                <textarea id="aiPrompt" rows="4" class="ai-planner-textarea" placeholder="Θα πάω στο Βόλο για 3 μέρες..."></textarea>
                
                <button id="generatePlanBtn" onclick="generateAIPlan()" class="ai-planner-btn">
                    ΦΤΙΑΞΕ ΠΛΑΝΟ
                </button>
            </div> 
        </aside>
    </div>    
</section>
    
<section class="map-section">
    <div class="map-container">
      <div id="map"></div>
    </div>
</section>

@if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
    <section class="recently-viewed-section">
        <div class="recently-viewed-container">
            <h2 class="recently-viewed-title">Είδατε πρόσφατα</h2>
            
            <div class="recently-viewed-grid-wrapper">
                <div class="latest-grid {{ request()->routeIs('index') ? 'is-index' : '' }} recently-viewed-grid">
                    @foreach($recentlyViewed as $business)
                        <div class="latest-card">
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
                            <a href="{{ route('business.show', $business->id) }}" class="card-link">
                                
                                @if($business->profile_photo)
                                    <img src="{{ asset('storage/' . $business->profile_photo) }}" alt="{{ $business->name }}" class="card-img">
                                @else
                                    <img src="{{ asset('images/default.png') }}" alt="Χωρίς Εικόνα" class="card-img">
                                @endif
                                
                                <h3>{{ $business->name }}</h3>
                                
                                <div class="stars-container">
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
                                        <span class="rating-count">({{ $totalReviews }})</span>
                                    </div>
                                </div>
                                <p>{{ Str::limit($business->minidescription, 80) }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

<footer>
    <p>© 2026 Discover City</p>
</footer>


<script>
    window.mapBusinesses = @json($mapBusinesses); // Μετατρέπουμε τα δεδομένα σε JSON για να τα χρησιμοποιήσουμε στο JavaScript
    // Ορίζουμε τις μεταβλητές με τα URLs των εικονιδίων
    window.stayIconUrl = "{{ asset('icons/stay.png') }}"; 
    window.restaurantIconUrl = "{{ asset('icons/restaurant.png') }}"; 
    window.activityIconUrl = "{{ asset('icons/activity.png') }}";
</script>

<script src="{{ asset('js/filament/my-scripts.js') }}"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    

    const filterData = {
        'stay': {
            types: [
                { val: 'hotel', label: 'Ξενοδοχείο' },
                { val: 'apartment', label: 'Διαμέρισμα / Βίλα' }
            ],
            amenities: [
                { val: 'wifi', label: 'Δωρεάν WiFi' },
                { val: 'parking', label: 'Πάρκινγκ' },
                { val: 'pool', label: 'Πισίνα' },
                { val: 'ac', label: 'Κλιματισμός' },
                { val: 'pets', label: 'Φιλικό σε κατοικίδια' },
                { val: 'breakfast', label: 'Πρωινό' }
            ]
        },
        'food-drink': {
            types: [
                { val: 'restaurant', label: 'Εστιατόριο' },
                { val: 'taverna', label: 'Ταβέρνα' },
                { val: 'mezedopoleio', label: 'Μεζεδοπωλείο' },
                { val: 'cafe', label: 'Καφετέρια' },
                { val: 'bar', label: 'Bar' },
                { val: 'street_food', label: 'Street Food' }
            ],
            amenities: [
                { val: 'vegan', label: 'Vegan Επιλογές' },
                { val: 'live_music', label: 'Ζωντανή Μουσική' },
                { val: 'accessibility', label: 'Πρόσβαση ΑμεΑ' },
                { val: 'view', label: 'Θέα' }
            ]
        },
        'activities': {
            types: [
                { val: 'watersports', label: 'Θαλάσσια Σπορ / Καταδύσεις' },
                { val: 'outdoor', label: 'Υπαίθριες Δραστηριότητες' },
                { val: 'tours', label: 'Ξεναγήσεις' }
            ],
            amenities: [
                { val: 'equipment', label: 'Παρέχεται Εξοπλισμός' },
                { val: 'guide', label: 'Συνοδός / Ξεναγός' },
                { val: 'transfer', label: 'Μεταφορά (Transfer)' },
                { val: 'kids', label: 'Φιλικό για παιδιά' },
                { val: 'english', label: 'Αγγλόφωνο Προσωπικό' }
            ]
        }
    };

    document.getElementById('mainCategory').addEventListener('change', function() {
        const val = this.value;
        const data = filterData[val];
        
        // Αλλαγή των Types
        const typeContainer = document.getElementById('typeContainer');
        let typeHtml = '';
        data.types.forEach(item => {
            typeHtml += `<label style="display: block; margin-bottom: 8px;"><input type="checkbox" name="type[]" value="${item.val}"> ${item.label}</label>`;
        });
        typeContainer.innerHTML = typeHtml;

        // Αλλαγή των Amenities
        const amenitiesContainer = document.getElementById('amenitiesContainer');
        let amenitiesHtml = '';
        data.amenities.forEach(item => {
            amenitiesHtml += `<label style="display: block; margin-bottom: 8px;"><input type="checkbox" name="amenities[]" value="${item.val}"> ${item.label}</label>`;
        });
        amenitiesContainer.innerHTML = amenitiesHtml;
    });

    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const category = document.getElementById('mainCategory').value;
        const selectedTypes = Array.from(document.querySelectorAll('input[name="type[]"]:checked')).map(cb => cb.value);
        const selectedAmenities = Array.from(document.querySelectorAll('input[name="amenities[]"]:checked')).map(cb => cb.value);
        const selectedAreas = Array.from(document.querySelectorAll('input[name="area[]"]:checked')).map(cb => cb.value);
        
        // ΝΕΟ: Διαβάζουμε την επιλεγμένη βαθμολογία
        const minRatingObj = document.querySelector('input[name="min_rating"]:checked');
        const minRating = minRatingObj ? minRatingObj.value : "";

        let targetUrl = `/${category}`;
        const params = new URLSearchParams();

        selectedTypes.forEach(t => params.append('type[]', t));
        selectedAmenities.forEach(a => params.append('amenities[]', a));
        selectedAreas.forEach(area => params.append('area[]', area));
        
        // ΝΕΟ: Αν έχει επιλέξει βαθμολογία (όχι "Οποιαδήποτε"), τη βάζουμε στο URL
        if (minRating !== "") {
            params.append('min_rating', minRating);
        }

        if (params.toString()) {
            targetUrl += `?${params.toString()}`;
        }

        window.location.href = targetUrl;
    });


document.addEventListener('DOMContentLoaded', function() {
    
    // --- NAVBAR SCROLL EFFECT ---
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
          if (window.scrollY > 50) navbar.classList.add('scrolled');
          else navbar.classList.remove('scrolled');
        });
    }

    // --- HERO SLIDER ---
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');
    
    let currentSlide = 0;
    let slideInterval;

    // Έλεγχος αν υπάρχουν slides πριν κάνουμε οτιδήποτε άλλο
    if (slides.length > 0) {
        
        function showSlide(index) {
            // Αφαιρούμε το active από παντού
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Το προσθέτουμε στο σωστό slide
            slides[index].classList.add('active');
            
            // Check για να μην "σκάσει" αν δεν υπάρχουν dots στη σελίδα
            if (dots.length > 0 && dots[index]) {
                dots[index].classList.add('active');
            }
        }

        // Επόμενο slide
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        // Προηγούμενο slide
        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        // Προσθήκη event listeners για τα κουμπιά
        if (nextBtn) {
            nextBtn.addEventListener('click', () => { 
                nextSlide(); 
                resetInterval(); 
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => { 
                prevSlide(); 
                resetInterval(); 
            });
        }

        // Προσθήκη event listeners για τις τελείες
        if (dots.length > 0) {
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    showSlide(currentSlide);
                    resetInterval();
                });
            });
        }

        // Αυτόματη εναλλαγή κάθε 7 δευτερόλεπτα
        function startInterval() { 
            slideInterval = setInterval(nextSlide, 7000); 
        }
        
        function resetInterval() { 
            clearInterval(slideInterval); 
            startInterval(); 
        }
        
        // Ξεκινάμε τον χρόνο
        startInterval();
    }
});
    
</script>






<script>
function generateAIPlan() {
    const promptInput = document.getElementById('aiPrompt');
    const btn = document.getElementById('generatePlanBtn');
    const promptText = promptInput.value.trim();

    if (!promptText) {
        alert('Γράψε πρώτα τι ψάχνεις (π.χ. "Θέλω ένα διήμερο για φαγητό και καφέ")');
        return;
    }

    // Κλειδώνουμε το κουμπί για να μην ξαναπατηθεί κατά τη διάρκεια του loading
    const originalBtnText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ΔΗΜΙΟΥΡΓΙΑ...';

    // Παίρνουμε το CSRF Token που διορθώσαμε πριν
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Αποστολή αιτήματος στο Laravel backend
    fetch('/generate-itinerary', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ prompt: promptText })
    })
    .then(response => response.json())
    .then(data => {
        // Ξεκλειδώνουμε το κουμπί
        btn.disabled = false;
        btn.innerHTML = originalBtnText;

        if (data.error) {
            alert('Σφάλμα: ' + data.error);
            return;
        }

        // Εμφάνιση αποτελέσματος στο Modal
        showAIModal(data.plan);
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalBtnText;
        alert('Σφάλμα συστήματος: ' + error.message);
        console.error(error);
    });
}

function showAIModal(text) {
    // 1. Μετατρέπουμε τα custom tags του AI σε κανονικά Links με στυλ Bold, χρώμα και υπογράμμιση
    // Αντικαθιστά το [shop id="5"]Μαγαζί[/shop] με <a href="/business/5" ...>Μαγαζί</a>
    let formattedText = text.replace(/\[shop id="(.*?)"\](.*?)\[\/shop\]/g, function(match, id, name) {
        return `<a href="/business/${id}" target="_blank" style="color: #004080; font-weight: bold; text-decoration: underline; hover: text-decoration: none;">${name}</a>`;
    });

    // 2. Μετατρέπουμε τα κλασικά Markdown αστεράκια (**) σε έντονη γραφή για το υπόλοιπο κείμενο
    formattedText = formattedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

    // 3. Μετατρέπουμε τις αλλαγές γραμμής σε <br>
    formattedText = formattedText.replace(/\n/g, '<br>');

    // 4. Δημιουργία του HTML DOM Element για το Modal
    const modalDiv = document.createElement('div');
    modalDiv.id = 'aiResultModal';
    modalDiv.style = "position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.6); display: flex; justify-content: center; align-items: center; z-index: 99999; font-family: inherit; backdrop-filter: blur(4px);";

    modalDiv.innerHTML = `
        <div style="background: white; width: 90%; max-width: 650px; max-height: 80vh; border-radius: 25px; padding: 30px; box-sizing: border-box; display: flex; flex-direction: column; box-shadow: 0 20px 50px rgba(0,0,0,0.3); animation: fadeIn 0.3s ease;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 20px;">
                <h2 style="margin: 0; color: #004080; font-size: 1.4rem; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-map-marked-alt" style="color: #ffd700;"></i> Το Πλάνο σου στον Βόλο
                </h2>
                <button onclick="document.getElementById('aiResultModal').remove()" style="background: none; border: none; font-size: 1.8rem; cursor: pointer; color: #aaa; line-height: 1;">&times;</button>
            </div>
            
            <div style="overflow-y: auto; flex-grow: 1; font-size: 1rem; line-height: 1.7; color: #333; padding-right: 10px;">
                ${formattedText}
            </div>
            
            <div style="margin-top: 25px; text-align: right; border-top: 1px solid #eee; padding-top: 15px;">
                <button onclick="document.getElementById('aiResultModal').remove()" style="padding: 12px 25px; background: #004080; border: none; border-radius: 10px; color: #ffd700; font-weight: bold; cursor: pointer; font-size: 0.95rem;">
                    ΤΕΛΕΙΑ, ΕΥΧΑΡΙΣΤΩ!
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(modalDiv);

}

</script>


</body>
</html>