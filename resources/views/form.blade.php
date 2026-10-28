<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($business) ? 'Επεξεργασία Επιχείρησης' : 'Καταχώρηση Επιχείρησης' }} - Discover City</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bform-body">

<div class="bform-container">
    <a href="{{ url()->previous() }}" class="bform-back-btn"><div class="bform-arrow-left"></div></a>
    <h2 class="bform-title">{{ isset($business) ? 'Επεξεργασία Επιχείρησης' : 'Εγγραφή Επιχείρησης' }}</h2>

    @if ($errors->any())
        <div class="bform-errors-box">
            <ul class="bform-errors-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($business) ? route('business.update', $business->id) : route('business.store') }}" method="POST" enctype="multipart/form-data" id="businessForm">
        @csrf
        @if(isset($business))
            @method('PUT')
        @endif

        <div class="bform-group">
            <label class="bform-label" for="businessName">Όνομα Επιχείρησης*</label>
            <input class="bform-input" type="text" id="businessName" name="businessName" value="{{ old('businessName', $business->name ?? '') }}">
        </div>

        <div class="bform-row bform-group">
            <div>
                <label class="bform-label" for="address">Διεύθυνση Επιχείρησης*</label>
                <input class="bform-input" type="text" id="address" name="address" required placeholder="Οδός και αριθμός" value="{{ old('address', $business->address ?? '') }}">
            </div>
            <div>
                <label class="bform-label" for="tk">Τ.Κ. *</label>
                <input class="bform-input" type="text" id="tk" name="tk" maxlength="5" required placeholder="Τ.Κ." value="{{ old('tk', $business->tk ?? '') }}">
            </div>
        </div>

        <div class="bform-group">
            <label class="bform-label" for="area">Περιοχή *</label>
            <select class="bform-input" id="area" name="area" required>
                <option value="">Επιλέξτε περιοχή...</option>
                @php $currentArea = old('area', $business->area ?? ''); @endphp
                <option value="center" {{ $currentArea == 'center' ? 'selected' : '' }}>Κέντρο</option>
                <option value="pilion" {{ $currentArea == 'pilion' ? 'selected' : '' }}>Πήλιο</option>
                <option value="agria" {{ $currentArea == 'agria' ? 'selected' : '' }}>Αγριά</option>
                <option value="alykes" {{ $currentArea == 'alykes' ? 'selected' : '' }}>Αλυκές</option>
                <option value="neadimitriada" {{ $currentArea == 'neadimitriada' ? 'selected' : '' }}>Νέα Δημητριάδα</option>
                <option value="neaionia" {{ $currentArea == 'neaionia' ? 'selected' : '' }}>Νέα Ιωνία</option>
            </select>
        </div>

        <div class="bform-row bform-group">
            <div>
                <label class="bform-label" for="phone">Τηλέφωνο Επικοινωνίας *</label>
                <input class="bform-input" type="tel" id="phone" name="phone" value="{{ old('phone', $business->phone ?? '') }}">
            </div>
            <div>
                <label class="bform-label" for="email">Email Επιχείρησης *</label>
                <input class="bform-input" type="email" id="email" name="email" value="{{ old('email', $business->email ?? '') }}">
            </div>
        </div>

        <div class="bform-group">
            <label class="bform-label" for="website">Σύνδεσμος Ιστοσελίδας (Προαιρετικό)</label>
            <input class="bform-input" type="url" id="website" name="website" placeholder="https://www.mybusiness.com" value="{{ old('website', $business->website ?? '') }}">
        </div>

        <div class="bform-group bform-main-category">
            <label class="bform-label" for="businessCategory">Κατηγορία Επιχείρησης *</label>
            <select class="bform-input" id="businessCategory" name="businessCategory" required onchange="toggleDynamicFields()">
                <option value="">Επιλέξτε Κατηγορία...</option>
                @php $currentCategory = old('businessCategory', $business->category ?? ''); @endphp
                <option value="accommodation" {{ $currentCategory == 'accommodation' ? 'selected' : '' }}>Διαμονή</option>
                <option value="food_drink" {{ $currentCategory == 'food_drink' ? 'selected' : '' }}>Φαγητό & Ποτό</option>
                <option value="activity" {{ $currentCategory == 'activity' ? 'selected' : '' }}>Δραστηριότητα</option>
            </select>
        </div>

        <div id="accFields" class="bform-dynamic-section">
            <div class="bform-group">
                <label class="bform-label" for="AMA">Αριθμός Μητρώου Ακινήτου*</label>
                <input class="bform-input" type="text" id="AMA" name="AMA" value="{{ old('AMA', $business->AMA ?? '') }}">
            </div>
            <div class="bform-group">
                <label class="bform-label" for="accType">Τύπος Καταλύματος *</label>
                <select class="bform-input" id="accType" name="accType">
                    <option value="">Επιλέξτε τύπο...</option>
                    @php $currentAccType = old('accType', $business->accType ?? ''); @endphp
                    <option value="hotel" {{ $currentAccType == 'hotel' ? 'selected' : '' }}>Ξενοδοχείο</option>
                    <option value="apartment" {{ $currentAccType == 'apartment' ? 'selected' : '' }}>Διαμέρισμα / Βίλα</option>
                </select>
            </div>
            <div class="bform-group">
                <label class="bform-label">Παροχές Καταλύματος</label>
                <div class="bform-checkbox-group">
                    @php 
                        $dbAmenities = isset($business) && is_string($business->amenities) ? json_decode($business->amenities, true) : ($business->amenities ?? []);
                        $oldAmenities = old('amenities', $dbAmenities ?? []); 
                    @endphp
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="wifi" {{ in_array('wifi', $oldAmenities) ? 'checked' : '' }}> Δωρεάν WiFi</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="parking" {{ in_array('parking', $oldAmenities) ? 'checked' : '' }}> Πάρκινγκ</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="pool" {{ in_array('pool', $oldAmenities) ? 'checked' : '' }}> Πισίνα</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="ac" {{ in_array('ac', $oldAmenities) ? 'checked' : '' }}> Κλιματισμός</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="pets" {{ in_array('pets', $oldAmenities) ? 'checked' : '' }}> Φιλικό σε κατοικίδια</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="breakfast" {{ in_array('breakfast', $oldAmenities) ? 'checked' : '' }}> Πρωινό</label>
                </div>
            </div>
        </div>
        

        <div id="foodFields" class="bform-dynamic-section">
            <div class="bform-group">
                <label class="bform-label" for="foodType">Είδος Εστίασης *</label>
                <select class="bform-input" id="foodType" name="foodType">
                    <option value="">Επιλέξτε είδος...</option>
                    @php $currentFoodType = old('foodType', $business->foodType ?? ''); @endphp
                    <option value="restaurant" {{ $currentFoodType == 'restaurant' ? 'selected' : '' }}>Εστιατόριο</option>
                    <option value="taverna" {{ $currentFoodType == 'taverna' ? 'selected' : '' }}>Ταβέρνα</option>
                    <option value="cafe" {{ $currentFoodType == 'cafe' ? 'selected' : '' }}>Καφετέρια</option>
                    <option value="mezedopoleio" {{ $currentFoodType == 'mezedopoleio' ? 'selected' : '' }}>Μεζεδοπωλείο</option>
                    <option value="bar" {{ $currentFoodType == 'bar' ? 'selected' : '' }}>Bar</option>
                    <option value="street_food" {{ $currentFoodType == 'street_food' ? 'selected' : '' }}>Street Food</option>
                </select>
            </div>

            <div class="bform-group">
                <label class="bform-label">Παροχές Εστίασης</label>
                <div class="bform-checkbox-group">
                    @php 
                        $dbAmenities = isset($business) && is_string($business->amenities) ? json_decode($business->amenities, true) : ($business->amenities ?? []);
                        $oldAmenities = old('amenities', $dbAmenities ?? []); 
                    @endphp
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="vegan" {{ in_array('vegan', $oldAmenities) ? 'checked' : '' }}> Vegan Επιλογές</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="live_music" {{ in_array('live_music', $oldAmenities) ? 'checked' : '' }}> Ζωντανή Μουσική</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="accessibility" {{ in_array('accessibility', $oldAmenities) ? 'checked' : '' }}> Πρόσβαση ΑμεΑ</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="view" {{ in_array('view', $oldAmenities) ? 'checked' : '' }}> Θέα</label>
                </div>
            </div>
        </div>

        <div id="actFields" class="bform-dynamic-section">
            <div class="bform-group">
                <label class="bform-label" for="actType">Είδος Δραστηριότητας *</label>
                <select class="bform-input" id="actType" name="actType">
                    <option value="">Επιλέξτε κατηγορία...</option>
                    @php $currentActType = old('actType', $business->actType ?? ''); @endphp
                    <option value="watersports" {{ $currentActType == 'watersports' ? 'selected' : '' }}>Θαλάσσια Σπορ / Καταδύσεις</option>
                    <option value="outdoor" {{ $currentActType == 'outdoor' ? 'selected' : '' }}>Υπαίθριες Δραστηριότητες</option>
                    <option value="tours" {{ $currentActType == 'tours' ? 'selected' : '' }}>Ξεναγήσεις</option>
                </select>
            </div>
            <div class="bform-row bform-group">
                <div>
                    <label class="bform-label" for="duration">Μέση Διάρκεια *</label>
                    <input class="bform-input" type="text" id="duration" name="duration" value="{{ old('duration', $business->duration ?? '') }}">
                </div>
            </div>

            <div class="bform-group">
                <label class="bform-label">Παροχές / Υπηρεσίες</label>
                <div class="bform-checkbox-group">
                    @php 
                        $dbAmenities = isset($business) && is_string($business->amenities) ? json_decode($business->amenities, true) : ($business->amenities ?? []);
                        $oldAmenities = old('amenities', $dbAmenities ?? []); 
                    @endphp
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="equipment" {{ in_array('equipment', $oldAmenities) ? 'checked' : '' }}> Παρέχεται Εξοπλισμός</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="guide" {{ in_array('guide', $oldAmenities) ? 'checked' : '' }}> Συνοδός / Ξεναγός</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="transfer" {{ in_array('transfer', $oldAmenities) ? 'checked' : '' }}> Μεταφορά</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="kids" {{ in_array('kids', $oldAmenities) ? 'checked' : '' }}> Φιλικό για παιδιά</label>
                    <label class="bform-checkbox-label"><input class="bform-checkbox" type="checkbox" name="amenities[]" value="english" {{ in_array('english', $oldAmenities) ? 'checked' : '' }}> Αγγλόφωνο προσωπικό</label>
                </div>
            </div>
        </div>

        <div id="commonBottom" class="bform-common-bottom">
            <div class="bform-group">
                <label class="bform-label" id="descLabel" for="description">Aναλυτική Περιγραφή *</label>
                <textarea class="bform-input bform-textarea" id="description" name="description" rows="5">{{ old('description', $business->description ?? '') }}</textarea>
            </div>
            
            <div class="bform-group">
                <label class="bform-label" id="minidescLabel" for="minidescription">Σύντομη Περιγραφή *</label>
                <textarea class="bform-input bform-textarea" id="minidescription" name="minidescription" maxlength="75" rows="3">{{ old('minidescription', $business->minidescription ?? '') }}</textarea>
                <div id="charCount" class="bform-char-count">0/75</div>
            </div>

            <div class="bform-row bform-group">
                <div>
                    <label class="bform-label" for="profile_input">Εικόνα Προφίλ {{ isset($business) ? '(Επιλέξτε μόνο για αλλαγή)' : '*' }}</label>
                    <div class="bform-file-upload">
                        <input class="bform-file-input" type="file" id="profile_input" name="profile_photo" accept="image/*" {{ isset($business) ? '' : 'required' }}>
                    </div>
                    <div id="profile_preview" class="bform-profile-preview-box">
                        @if(isset($business) && $business->profile_photo)
                            <div class="bform-img-wrapper">
                                <img src="{{ asset('storage/' . $business->profile_photo) }}" class="bform-preview-img-large">
                            </div>
                        @endif
                    </div>
                </div>
                
                <div>
                    <label class="bform-label" for="gallery_input">Φωτογραφίες Συλλογής (Προσθήκη νέων)</label>
                    <div class="bform-file-upload">
                        <input class="bform-file-input" type="file" id="gallery_input" name="photos[]" multiple accept="image/*">
                    </div>
                    <div id="gallery_preview" class="bform-gallery-preview-box">
                        @if(isset($business) && $business->photos)
                            @php 
                                $galleryPhotos = is_string($business->photos) ? json_decode($business->photos, true) : $business->photos;
                            @endphp
                            @if(is_array($galleryPhotos))
                                @foreach($galleryPhotos as $photo)
                                    <div class="bform-img-wrapper existing-photo">
                                        <img src="{{ asset('storage/' . $photo) }}" class="bform-preview-img-small">
                                        <button type="button" onclick="removeExistingGalleryImage(this, '{{ $photo }}')" class="bform-remove-img-btn-small">✕</button>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <button type="submit" class="bform-submit-btn">{{ isset($business) ? 'Ενημέρωση Καταχώρησης' : 'Ολοκληρωση Καταχωρησης' }}</button>
        </div>
    </form>
</div>
<footer class="simple-footer">
    <p>© 2026 Discover City</p>    
</footer>
<script>
    // Για την εμφάνιση/απόκρυψη των πεδίων ανάλογα με την κατηγορία
    function toggleDynamicFields() {
        const category = document.getElementById('businessCategory').value;
        const sections = {
            accommodation: document.getElementById('accFields'),
            food_drink: document.getElementById('foodFields'),
            activity: document.getElementById('actFields')
        };
        const commonBottom = document.getElementById('commonBottom');

        Object.values(sections).forEach(s => s.style.display = 'none');
        commonBottom.style.display = 'none';

        if (category && sections[category]) {
            sections[category].style.display = 'block';
            commonBottom.style.display = 'block';
        }
    }

    window.onload = function() {
        toggleDynamicFields();
        const length = document.getElementById('minidescription').value.length;
        document.getElementById('charCount').innerText = `${length}/75`;
    };

    document.getElementById('minidescription').addEventListener('input', function() {
        document.getElementById('charCount').innerText = `${this.value.length}/75`;
    });

    
    const profileInput = document.getElementById('profile_input');
    const profilePreview = document.getElementById('profile_preview');
    // Για την προεπισκόπηση της εικόνας προφίλ
    profileInput.addEventListener('change', function(e) {
        profilePreview.innerHTML = ''; 
        const file = this.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                profilePreview.innerHTML = `
                    <div class="bform-img-wrapper">
                        <img src="${event.target.result}" class="bform-preview-img-large">
                        <button type="button" onclick="removeProfile()" class="bform-remove-img-btn-large">✕</button>
                    </div>
                `;
            }
            reader.readAsDataURL(file);
        }
    });

    function removeProfile() {
        profileInput.value = ''; 
        profilePreview.innerHTML = ''; 
    }

    const galleryInput = document.getElementById('gallery_input');
    const galleryPreview = document.getElementById('gallery_preview');
    let dtGallery = new DataTransfer(); 
    const MAX_PHOTOS = 9;

    if (galleryInput && galleryPreview) {
        galleryInput.addEventListener('change', function(e) {
            // Μετράμε παλιές (από Edit) + υπάρχουσες στο DataTransfer + νέες
            const oldPhotosCount = galleryPreview.querySelectorAll('.existing-photo').length;
            const addedPhotosCount = dtGallery.files.length;
            const incomingPhotosCount = this.files.length;

            // Αν το σύνολο ξεπερνάει τις 9, σταματάμε τη διαδικασία
            if ((oldPhotosCount + addedPhotosCount + incomingPhotosCount > MAX_PHOTOS) && (oldPhotosCount !== MAX_PHOTOS)) {
                const allowedNow = MAX_PHOTOS - (oldPhotosCount + addedPhotosCount);
                alert(`Το όριο είναι ${MAX_PHOTOS} φωτογραφίες. Μπορείτε να προσθέσετε ακόμα ${allowedNow} φωτογραφίες.`);
                this.value = ''; // Καθαρίζει την άκυρη επιλογή
                return; 
            } else if (oldPhotosCount === MAX_PHOTOS) {
                alert(`Το όριο είναι ${MAX_PHOTOS} φωτογραφίες. Έχετε φτάσει το μέγιστο επιτρεπτό αριθμό φωτογραφιών.`);
                this.value = ''; // Καθαρίζει την άκυρη επιλογή
                return; 
            }
            

            for (let file of this.files) {
                dtGallery.items.add(file);
            }
            galleryInput.files = dtGallery.files; 
            updateGalleryPreview();
        });
    }

    function updateGalleryPreview() {
        const oldPhotos = galleryPreview.querySelectorAll('div:not(.new-preview)');
        galleryPreview.innerHTML = '';
        
        oldPhotos.forEach(p => galleryPreview.appendChild(p));
        
        Array.from(dtGallery.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const div = document.createElement('div');
                div.classList.add('new-preview');
                div.classList.add('bform-img-wrapper');
                div.innerHTML = `
                    <img src="${event.target.result}" class="bform-preview-img-small">
                    <button type="button" onclick="removeGalleryImage(${index})" class="bform-remove-img-btn-small">✕</button>
                `;
                galleryPreview.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    }

    function removeGalleryImage(indexToRemove) {
        const newDt = new DataTransfer();
        const files = dtGallery.files;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== indexToRemove) {
                newDt.items.add(files[i]);
            }
        }
        
        dtGallery = newDt; 
        galleryInput.files = dtGallery.files; 
        updateGalleryPreview(); 
    }

    function removeExistingGalleryImage(btn, photoPath) {
        // Φτιάχνουμε ένα κρυφό input
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'delete_photos[]'; // Array για το backend
        hiddenInput.value = photoPath;

        // Προσθέτουμε το κρυφό input στη φόρμα
        document.getElementById('businessForm').appendChild(hiddenInput);
        
        // Εξαφανίζουμε τη φωτογραφία από το UI 
        btn.closest('.bform-img-wrapper').remove();
    }
</script>

</body>
</html>