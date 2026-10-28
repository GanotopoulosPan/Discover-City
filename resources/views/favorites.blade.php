<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Αγαπημένα & Λίστες - Discover City</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
</head>
<body class="favlist-body">

  <header class="sec-header">
     <x-header />
  </header>

  <div class="favlist-main-wrapper">
    <aside class="favlist-sidebar">
        <h3>Οι Λίστες μου</h3>
        <div id="listsContainer">
            
            <div class="favlist-item {{ $selectedListId === 'all' ? 'active' : '' }}" onclick="filterList('all')">
                <span>Όλα τα αγαπημένα</span>
                <span class="favlist-count" id="totalCount">{{ $totalFavoritesCount }}</span>
            </div>

            @foreach($customLists as $list)
                <div class="favlist-item {{ $selectedListId == $list->id ? 'active' : '' }}" onclick="filterList({{ $list->id }})">
                    <span id="list-name-{{ $list->id }}">{{ $list->name }}</span>
                    
                    <div class="favlist-actions">
                        <span class="favlist-count">{{ $list->favorites_count }}</span>
                        <button onclick="event.stopPropagation(); renameList({{ $list->id }}, '{{ $list->name }}')" class="favlist-action-btn edit" title="Μετονομασία">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="event.stopPropagation(); deleteList({{ $list->id }})" class="favlist-action-btn delete" title="Διαγραφή">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach

        </div>
        
        <button class="favlist-add-btn" onclick="createNewList()">
            <i class="fas fa-plus"></i> Νέα Λίστα
        </button>
    </aside>

    <main class="favlist-content">
        <div class="favlist-header">
            <h2 id="currentListTitle">{{ $currentListTitle }}</h2>
        </div>

        <div class="favlist-grid" id="favoritesGrid">
          @forelse($favorites as $favorite)
              @php $business = $favorite->business; @endphp
              @if($business)
                  <div class="favlist-card" id="fav-card-{{ $business->id }}">
                      
                      {{-- Κουμπί Διαγραφής --}}
                      <button class="favlist-remove-btn" onclick="removeFavorite('{{ $business->id }}')" title="Διαγραφή">✕</button>
                      
                      {{-- Link προς τη σελίδα της επιχείρησης --}}
                      <a href="{{ route('business.show', $business->id) }}" class="favlist-card-link">
                          
                            @if($business->profile_photo)
                                <img src="{{ asset('storage/' . $business->profile_photo) }}" 
                                    alt="{{ $business->name }}" 
                                    class="favlist-card-img">
                            @else
                                <img src="{{ asset('images/default.png') }}" 
                                    alt="Χωρίς Εικόνα" 
                                    class="favlist-card-img">
                            @endif

                          <div class="favlist-card-content">
                              <h3>{{ $business->name }}</h3>
                              <div class="favlist-stars">
                                  @php $rating = $business->reviews->avg('rating') ?: 0; @endphp
                                  @for($i = 1; $i <= 5; $i++)
                                      <i class="{{ $i <= round($rating) ? 'fas' : 'far' }} fa-star"></i>
                                  @endfor
                                  <span class="favlist-rating-text">({{ number_format($rating, 1) }})</span>
                              </div>
                              <p class="favlist-category-text">
                                  <i class="fas fa-tag"></i> {{ ucfirst(str_replace('_', ' ', $business->subcategory_key)) }}
                              </p>
                          </div>
                      </a>

                  </div>
              @endif
          @empty
              <p id="emptyMsg" class="favlist-empty-msg">Δεν υπάρχουν αγαπημένα σε αυτή τη λίστα.</p>
          @endforelse
      </div>
    </main>
  </div>
  <footer class="favlist-footer"><p>© 2026 Discover City</p></footer>

  <script src="{{ asset('js/filament/my-scripts.js') }}"></script>

  
</body>
</html>