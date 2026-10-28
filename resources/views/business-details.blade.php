<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $business->name }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body class="body-details">
    @php
        $translations = [
            'accommodation' => 'Διαμονή', 'food_drink' => 'Φαγητό & Ποτό', 'activity' => 'Δραστηριότητα',
            'hotel' => 'Ξενοδοχείο', 'apartment' => 'Διαμέρισμα / Βίλα', 'restaurant' => 'Εστιατόριο',
            'taverna' => 'Ταβέρνα', 'mezedopoleio' => 'Μεζεδοπωλείο', 'cafe' => 'Καφετέρια',
            'bar' => 'Bar', 'street_food' => 'Street Food', 'wifi' => 'Δωρεάν WiFi',
            'parking' => 'Πάρκινγκ', 'pool' => 'Πισίνα', 'ac' => 'Κλιματισμός',
            'pets' => 'Φιλικό σε κατοικίδια', 'breakfast' => 'Πρωινό', 'vegan' => 'Vegan Επιλογές',
            'live_music' => 'Ζωντανή Μουσική', 'accessibility' => 'Πρόσβαση ΑμεΑ', 'view' => 'Θέα',
            'equipment' => 'Παρέχεται Εξοπλισμός', 'guide' => 'Συνοδός / Ξεναγός', 'transfer' => 'Μεταφορά',
            'kids'=> 'Φιλικό για παιδιά', 'adults' => 'Μόνο για ενήλικες', 'all' => 'Κατάλληλο για όλους',
            'center' => 'Κέντρο / Λιμάνι', 'pilion' => 'Πήλιο / Βουνό', 'neaionia' => 'Νέα Ιωνία',
            'alykes' => 'Αλυκές', 'agria' => 'Αγριά', 'neadimitriada' => 'Νέα Δημητριάδα',
            'morning' => 'Πρωί', 'afternoon' => 'Απόγευμα', 'evening' => 'Βράδυ',
            'easy' => 'Εύκολη', 'medium' => 'Μέτρια', 'hard' => 'Δύσκολη',  
        ];
    @endphp

    <div class="info-box">
        <x-alerts />
        <a href="{{ url('/') }}" class="back-btn"><div class="arrow-left"></div></a>

        <div class="header-container">
            <div class="header-text">
                <h1>{{ $business->name }}</h1>
                <p class="category-text">
                    {{ $translations[$business->category] ?? $business->category }} | {{ $translations[$business->type] ?? $business->type }}
                </p>

                <div class="contact-info">
                    <p><strong><span style="font-size:25px;">&#128205;</span> Διεύθυνση:</strong> {{ $business->address }}, {{ $translations[$business->area]??$business->area }}</p>
                    <p><strong> <span style="font-size:25px;">&#128222;</span> Τηλέφωνο:</strong> {{ $business->phone }}</p>
                    <p><strong> <span style="font-size:25px;">&#128233;</span> Email:</strong> {{ $business->email }}</p>
                </div>
                <div class="extra-info">
                    @if($business->website)
                        <p><strong><span style="font-size:25px;">&#12710;</span> Ιστοσελίδα:</strong> <a href="{{ $business->website }}" target="_blank">{{ $business->website }}</a></p>
                    @endif
                    
                    @if($business->schedule)
                        <p><strong><span style="font-size:25px;">&#128337;</span> Ωράριο:</strong> {{ $translations[$business->schedule] ?? $business->schedule }}</p>
                    @endif

                    @if($business->ama)
                        <p><strong><span style="font-size:25px;">&#128196;</span> Α.Μ.Α.:</strong> {{ $business->ama }}</p>
                    @endif
                </div>


                
                
                <div class="fav-section" style="margin-top: 20px;">
        @auth
        {{-- Ελέγχουμε αν ο συνδεδεμένος χρήστης ΔΕΝ έχει δικές του επιχειρήσεις --}}
        @if(\App\Models\Business::where('user_id', auth()->id())->doesntExist())
            
            @php
                $isFavorite = $business->favorites()->where('user_id', auth()->id())->exists();
            @endphp

            <button onclick="toggleGeneralFavorite({{ $business->id }})" class="fav-btn-bd" id="generalFavBtn">
                <i class="{{ $isFavorite ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
            </button>

            <div class="custom-lists-dropdown">
                <button class="add-to-list-btn" onclick="toggleDropdown()">
                    <i class="fas fa-plus-circle"></i> Προσθήκη σε λίστα
                </button>
                <div id="listsMenu" class="dropdown-content">
                    <div id="checkboxesContainer">
                        @foreach($customLists as $list)
                            <label class="list-item-check">
                                <input type="checkbox" 
                                    class="list-checkbox" 
                                    value="{{ $list->id }}" 
                                    onchange="toggleListAssignment({{ $business->id }}, {{ $list->id }}, this)">
                                {{ $list->name }}
                            </label>
                        @endforeach
                    </div>

                    <div class="new-list-divider">
                        <div class="new-list-input-group">
                            <input type="text" id="newListNameInput" class="new-list-input" placeholder="Όνομα νέας λίστας...">
                            <button onclick="createNewListAndAssign({{ $business->id }})" class="new-list-submit-btn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
        @endif
    @else
        <p>Συνδεθείτε για αποθήκευση.</p>
    @endauth            
    <!-- @auth
        @php
            $isFavorite = $business->favorites()->where('user_id', auth()->id())->exists();
        @endphp

        <button onclick="toggleGeneralFavorite({{ $business->id }})" class="fav-btn-bd" id="generalFavBtn">
            <i class="{{ $isFavorite ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
        </button>


        <div class="custom-lists-dropdown">
            <button class="add-to-list-btn" onclick="toggleDropdown()">
                <i class="fas fa-plus-circle"></i> Προσθήκη σε λίστα
            </button>
            <div id="listsMenu" class="dropdown-content">
                <div id="checkboxesContainer">
                    @foreach($customLists as $list)
                        <label class="list-item-check">
                            <input type="checkbox" 
                                class="list-checkbox" 
                                value="{{ $list->id }}" 
                                onchange="toggleListAssignment({{ $business->id }}, {{ $list->id }}, this)">
                            {{ $list->name }}
                        </label>
                    @endforeach
                </div>

                <div class="new-list-divider">
                    <div class="new-list-input-group">
                        <input type="text" id="newListNameInput" class="new-list-input" placeholder="Όνομα νέας λίστας...">
                        <button onclick="createNewListAndAssign({{ $business->id }})" class="new-list-submit-btn">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p>Συνδεθείτε για αποθήκευση.</p>
    @endauth -->
</div>
            </div>

            <div class="header-image">
                @if($business->profile_photo)
                    <img src="{{ asset('storage/' . $business->profile_photo) }}" 
                        alt="{{ $business->name }}" >
                 @else
                    <img src="{{ asset('images/default.png') }}" 
                        alt="Χωρίς Εικόνα" >
                @endif
            </div>
        </div>

        @if(!empty($business->amenities) && $business->amenities != 'null')
                    <h3>Παροχές</h3>
                    <ul class="amenities-list" style="list-style-type: none; padding: 10px;">
                        @php 
                            $amenitiesArray = is_string($business->amenities) ? json_decode($business->amenities, true) : $business->amenities;
                        @endphp
                        
                        @if(is_array($amenitiesArray))
                            @foreach($amenitiesArray as $amenity)
                                <li style="display: inline-block; background: #f1f1f1; padding: 5px 10px; margin: 5px 5px 0 0; border-radius: 15px; font-size: 14px;">
                                    <i class="fa-solid fa-check" style="color: green;"></i> {{ $translations[$amenity] ?? $amenity }}
                                </li>
                            @endforeach
                        @endif
                    </ul>
                @endif

        @if($business->duration || $business->difficulty || $business->age_group)
        <h3>Λεπτομέρειες Δραστηριότητας</h3>
                    <div class="activity-details" style="margin-top: 20px; background: #dfdfdf; padding:15px 10px 10px 10px; border-radius: 8px;">
                        
                        <ul style="list-style: none; padding: 15px; display: flex; flex-wrap: wrap; gap: 80px;">
                            @if($business->duration) 
                                <li><strong>Διάρκεια:</strong> {{ $translations[$business->duration] ?? $business->duration }}</li> 
                            @endif
                            @if($business->difficulty) 
                                <li><strong>Βαθμός Δυσκολίας:</strong> {{ $translations[$business->difficulty] ?? $business->difficulty }}</li> 
                            @endif
                            @if($business->age_group) 
                                <li><strong>Κατάλληλο για:</strong> {{ $translations[$business->age_group] ?? $business->age_group }}</li> 
                            @endif
                        </ul>
                    </div>
                @endif

        <h3>Περιγραφή</h3>
        <p class="business-description" style="margin-top: 20px; background: #dfdfdf; padding:15px 10px 10px 10px; border-radius: 8px;">{{ $business->description }}</p>

        @if(is_array($business->photos) && count($business->photos) > 0)
            <h3>Φωτογραφίες</h3>
            <div class="photo-gallery" >
                @foreach($business->photos as $index => $photo)
                    <img src="{{ asset('storage/' . $photo) }}" onclick="openModal();currentSlide({{ $index + 1 }})">
                @endforeach
            </div>
        @endif

        <!-- <h3>Αξιολογήσεις ({{ $business->reviews->count() }})</h3>
        @auth
            @if(!$business->reviews()->where('user_id', auth()->id())->exists())
                <form action="{{ route('reviews.store', $business->id) }}" method="POST">
                    @csrf
                    <div class="star-rating">
                        @for($i=5; $i>=1; $i--)
                            <input type="radio" id="star{{$i}}" name="rating" value="{{$i}}" required />
                            <label for="star{{$i}}">★</label>
                        @endfor
                    </div>
                    <textarea name="comment" class="review-textarea" placeholder="Σχολιάστε..." rows="3"></textarea>
                    <button type="submit" class="comment-btn">Υποβολή</button>
                </form>
            @endif
        @endauth -->
        <h3>Αξιολογήσεις ({{ $business->reviews->count() }})</h3>
        @auth
            @if(\App\Models\Business::where('user_id', auth()->id())->doesntExist())
                
                @if(!$business->reviews()->where('user_id', auth()->id())->exists())
                    <form action="{{ route('reviews.store', $business->id) }}" method="POST">
                        @csrf
                        <div class="star-rating">
                            @for($i=5; $i>=1; $i--)
                                <input type="radio" id="star{{$i}}" name="rating" value="{{$i}}" required />
                                <label for="star{{$i}}">★</label>
                            @endfor
                        </div>
                        <textarea name="comment" class="review-textarea" placeholder="Σχολιάστε..." rows="3"></textarea>
                        <button type="submit" class="comment-btn">Υποβολή</button>
                    </form>
                @endif

            @else
                <div style="padding: 15px; background-color: #f8f9fa; border-left: 4px solid #3b5d7c; margin-bottom: 20px; font-size: 0.9em; color: #555;">
                    <i>* Οι λογαριασμοί που διαχειρίζονται επιχειρήσεις δεν έχουν δυνατότητα να αφήσουν αξιολόγηση.</i>
                </div>
            @endif
        @endauth

        <div id="reviews-container" >
            @include('partials.reviews')
        </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            @if(is_array($business->photos))
                @foreach($business->photos as $photo)
                    <div class="mySlides"><img src="{{ asset('storage/' . $photo) }}"></div>
                @endforeach
            @endif
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
    </div>
    <footer class="simple-footer">
        <p>© 2026 Discover City</p>
    </footer>
    <input type="hidden" id="current-business-id" value="{{ $business->id }}">

    <script src="{{ asset('js/filament/my-scripts.js') }}"></script>

</body>
</html>