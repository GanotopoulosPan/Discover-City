<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $sight->name }} - Discover City</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
</head>
<body class="body-details">

    <div class="info-box">
        <a href="/sights" style="display:inline-flex; align-items:center; margin-bottom:20px; text-decoration:none; color:var(--primary); font-weight:bold;">
            <i class="fas fa-arrow-left" style="margin-right:10px;"></i> Πίσω
        </a>

        <div class="header-container">
            <div class="header-text">
                <h1>{{ $sight->name }}</h1>
                <p style="font-size: 1.1em;">{{ $sight->mini_description }}</p>
            </div>
            <div class="header-image">
                <img src="{{ asset('storage/' . $sight->photo_profile) }}" alt="{{ $sight->name }}">
            </div>
        </div>

        <h3>Περιγραφή</h3>
        <div style="line-height: 1.6;">{!! $sight->description !!}</div>

        @if(is_array($sight->photos) && count($sight->photos) > 0)
            <h3>Φωτογραφίες</h3>
            <div class="photo-gallery">
                @foreach($sight->photos as $index => $photo)
                    <img src="{{ asset('storage/' . $photo) }}" onclick="openModal();currentSlide({{ $index + 1 }})">
                @endforeach
            </div>
        @endif
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            @foreach($sight->photos ?? [] as $photo)
                <div class="mySlides" style="display:none;"><img src="{{ asset('storage/' . $photo) }}"></div>
            @endforeach
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
    </div>
    <footer class="simple-footer">
        <p>© 2026 Discover City</p>
    </footer>
    <script src="{{ asset('js/filament/my-scripts.js') }}"></script>
    
</body>
</html>