/* --------------------------------------------------
    Αυτό το αρχείο περιέχει όλα τα custom JavaScript scripts που χρειάζονται για τη λειτουργικότητα του site. 
                                                     ---------------------------------------------------------*/

//------------------------WEATHER WIDGET (https://weatherwidget.io/)------------------------
document.addEventListener('DOMContentLoaded', function () {
    // Φορτώνουμε το script για το widget του καιρού απευθείας από την υπηρεσία τους
    !function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (!d.getElementById(id)) {
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://weatherwidget.io/js/widget.min.js';
            fjs.parentNode.insertBefore(js, fjs);
        }
    }(document, 'script', 'weatherwidget-io-js');
});
///--------------------------END OF WEATHER WIDGET------------------------------------


// ---------------------------------DROPDOWN ΠΟΛΗΣ & ΧΡΗΣΤΗ ------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    const btnCity = document.getElementById("cityBtn");
    const menuCity = document.getElementById("cityMenu");
    const btnUser = document.getElementById("userIconBtn");
    const menuUser = document.getElementById("userMenu");

    // Ανοιγοκλείσιμο του dropdown για την πόλη
    if (btnCity && menuCity) {
        btnCity.addEventListener("click", () => {
            const isOpen = menuCity.style.display === "block";
            menuCity.style.display = isOpen ? "none" : "block";
            // Αλλάζουμε το βελάκι ανάλογα με το αν είναι ανοιχτό ή κλειστό
            btnCity.innerHTML = isOpen ? "Volos ▼" : "Volos ▲";
        });
    }

    // Ανοιγοκλείσιμο του dropdown του χρήστη
    if (btnUser && menuUser) {
        btnUser.addEventListener("click", (e) => {
            e.stopPropagation(); // Αποτρέπει το κλείσιμο του μενού όταν κάνουμε κλικ στο ίδιο το κουμπί
            const isUserOpen = menuUser.style.display === "block";
            menuUser.style.display = isUserOpen ? "none" : "block";
        });
    }

    // Κλείνουμε τα μενού αν ο χρήστης κάνει κλικ οπουδήποτε αλλού στη σελίδα
    window.addEventListener("click", (e) => {
        if (menuCity && !e.target.closest(".city-dropdown")) {
            menuCity.style.display = "none";
            if (btnCity) btnCity.innerHTML = "Volos ▼";
        }
        if (menuUser && !e.target.closest(".user-menu-container")) {
            menuUser.style.display = "none";
        }
    });

    // Εφέ στο μενού πλοήγησης (Navbar) όταν ο χρήστης σκρολάρει προς τα κάτω
    const nav = document.querySelector('.navbar');
    if (nav) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    }
});
// ---------------------------------ΤΈΛΟΣ DROPDOWNS ------------------------------------


// ---------------------------------------------- LEAFLET MAP ---------------------------------------------------
document.addEventListener("DOMContentLoaded", function () {
    const mapDiv = document.getElementById('map');
    
    // Αν η τρέχουσα σελίδα δεν έχει χάρτη, σταματάμε εδώ για να μην πετάξει error
    if (!mapDiv) return;

    // Αν υπάρχει ήδη φορτωμένος χάρτης στο συγκεκριμένο div, τον "καθαρίζουμε" για να μην έχουμε προβλήματα 
    // με διπλές πινέζες ή παλιά δεδομένα όταν επιστρέφουμε στη σελίδα με τον χάρτη μετά από επεξεργασία επιχείρησης.
    if (mapDiv._leaflet_id) {
        mapDiv._leaflet_id = null;
    }

    // Στήσιμο του χάρτη με κάποιους βασικούς περιορισμούς στο zoom για καλύτερη εμπειρία χρήστη 
    // και ενεργοποίηση του dragging για να μπορεί ο χρήστης να κινείται στον χάρτη με το ποντίκι.
    const myMap = L.map('map', {
        scrollWheelZoom: false, 
        doubleClickZoom: false, 
        boxZoom: false,           
        dragging: true, 
    }).setView([39.3621, 22.9423], 13);

    // Προσθέτουμε το OpenStreetMap layer στον χάρτη, που είναι δωρεάν και δεν απαιτεί API key.
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(myMap);

    // Διαβάζουμε τα δεδομένα των επιχειρήσεων που μας έστειλε η PHP (από τον Controller)
    const places = window.mapBusinesses;

    // Φτιάχνουμε δικά μας εικονίδια για κάθε κατηγορία. 
    // Αν δεν βρεθούν τα custom urls, βάζουμε τα default paths.
    const icons = {
        'accommodation': L.icon({
            iconUrl:'/icons/stay.png',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        }),
        'food_drink': L.icon({
            iconUrl:'/icons/restaurant.png',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        }),
        'activity': L.icon({
            iconUrl:'/icons/activity.png',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        })
    };

    // Ελέγχουμε αν όντως έχουμε δεδομένα σε μορφή πίνακα και βάζουμε τις πινέζες στον χάρτη    
    if (Array.isArray(places)) {
        places.forEach(place => {
            // Σιγουρευόμαστε ότι το μαγαζί έχει συντεταγμένες
            if (place.lat && place.lon) {
                
                // Το HTML της μικρής "καρτέλας" που ανοίγει όταν πατάμε πάνω στην πινέζα
                const popUpHtml = `
                    <div style="text-align: center; min-width: 150px;">
                        <h3 style="margin: 0 0 5px 0; color: #004080; font-size: 16px;">${place.name}</h3>
                        <p style="margin: 0 0 10px 0; font-size: 13px; color: #555;">${place.address || ''}</p>
                        <a href="/business/${place.id}" 
                           style="display: inline-block; background: #ffd700; color: #004080; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 13px;">
                            Δείτε το
                        </a>
                    </div>
                `;

                // Διαλέγουμε το σωστό εικονίδιο, αλλιώς βάζουμε αυτό της δραστηριότητας ως fallback
                const pin = icons[place.category] || icons['activity'];

                // Προσθέτουμε την πινέζα στον χάρτη
                L.marker([parseFloat(place.lat), parseFloat(place.lon)], { icon: pin })
                    .addTo(myMap)
                    .bindPopup(popUpHtml);
            }
        });
    }
});
/*---------------------------------End of Leaflet Map scripts------------------------------------------------------*/


// /*---------------------------------------Edit-Business scripts------------------------------------------------------------------*/
// document.addEventListener("DOMContentLoaded", function () {
//     const catSelect = document.getElementById('businessCategory');
//     if (catSelect) {
//         // Τρέχουμε τη συνάρτηση μια φορά μόλις φορτώσει η σελίδα για να εμφανίσει τα σωστά πεδία
//         checkCategoryFields(); 
        
//         // Και της λέμε να ξανατρέχει κάθε φορά που ο χρήστης αλλάζει επιλογή στο dropdown
//         catSelect.addEventListener('change', checkCategoryFields); 
//     }
// });

// function checkCategoryFields() {
//     const select = document.getElementById('businessCategory');
//     if (!select) return;

//     const val = select.value;
    
//     // Βρίσκουμε τα divs που περιέχουν τα πεδία για κάθε κατηγορία, το κοινό κάτω μέρος και την ετικέτα της περιγραφής
//     const accDiv = document.getElementById('accFields');
//     const foodDiv = document.getElementById('foodFields');
//     const actDiv = document.getElementById('actFields');
//     const bottomDiv = document.getElementById('commonBottom');
//     const label = document.getElementById('descLabel');

//     // Κρύβουμε τα πάντα αρχικά
//     if (accDiv) accDiv.style.display = 'none';
//     if (foodDiv) foodDiv.style.display = 'none';
//     if (actDiv) actDiv.style.display = 'none';

//     // Βρίσκουμε τα input fields για να τους αφαιρέσουμε τον κανόνα 'required',
//     // ώστε να μην "σκάει" η υποβολή της φόρμας σε πεδία που είναι κρυμμένα.
//     const accType = document.getElementById('accType');
//     const foodType = document.getElementById('foodType');
//     const actType = document.getElementById('actType');
//     const dur = document.getElementById('duration');
//     const sched = document.getElementById('schedule');

//     if (accType) accType.required = false;
//     if (foodType) foodType.required = false;
//     if (actType) actType.required = false;
//     if (dur) dur.required = false;
//     if (sched) sched.required = false;

//     // Εμφανίζουμε μόνο τα σχετικά πεδία και τα κάνουμε υποχρεωτικά
//     if (val === 'accommodation') {
//         if (accDiv) accDiv.style.display = 'block';
//         if (accType) accType.required = true;
//         if (label) label.innerText = "Περιγραφή Καταλύματος *";
//         if (bottomDiv) bottomDiv.style.display = 'block';

//     } else if (val === 'food_drink') {
//         if (foodDiv) foodDiv.style.display = 'block';
//         if (foodType) foodType.required = true;
//         if (label) label.innerText = "Περιγραφή Επιχείρησης & Σπεσιαλιτέ *";
//         if (bottomDiv) bottomDiv.style.display = 'block';

//     } else if (val === 'activity') {
//         if (actDiv) actDiv.style.display = 'block';
//         if (actType) actType.required = true;
//         if (dur) dur.required = true;
//         if (sched) sched.required = true;
//         if (label) label.innerText = "Περιγραφή Εμπειρίας *";
//         if (bottomDiv) bottomDiv.style.display = 'block';

//     } else {
//         // Αν δεν έχει επιλεγεί τίποτα (π.χ. το default "Επιλέξτε..."), κρύβουμε και το κάτω μέρος
//         if (bottomDiv) bottomDiv.style.display = 'none';
//     }
// }
// /*---------------------------------End of Edit-Business scripts------------------------------------------------------*/
/*--------------------------------------- Φόρμα Καταχώρησης/Επεξεργασίας Επιχείρησης --------------------------------------------------*/
window.toggleDynamicFields = function(isUserInteraction = false) {
    const categorySelect = document.getElementById('businessCategory');
    if (!categorySelect) return; 

    const category = categorySelect.value;
    const sections = {
        accommodation: document.getElementById('accFields'),
        food_drink: document.getElementById('foodFields'),
        activity: document.getElementById('actFields')
    };
    const commonBottom = document.getElementById('commonBottom');

    // Κρύβουμε όλα τα sections
    Object.keys(sections).forEach(key => { 
        if (sections[key]) {
            sections[key].style.display = 'none';
            
            // Αν η αλλαγή έγινε από τον χρήστη, καθαρίζουμε τα checkboxes των κατηγοριών που κρύβονται.
            if (isUserInteraction && key !== category) {
                const checkboxes = sections[key].querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(cb => cb.checked = false);
            }
        }
    });
    
    if (commonBottom) commonBottom.style.display = 'none';

    // Εμφανίζουμε το σωστό section
    if (category && sections[category]) {
        sections[category].style.display = 'block';
        if (commonBottom) commonBottom.style.display = 'block';
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // Ελέγχουμε αν υπάρχει η φόρμα. Αν όχι, σταματάμε εδώ.
    const bForm = document.getElementById('businessForm');
    if (!bForm) return; 

    // Αρχική εκτέλεση
    window.toggleDynamicFields();

    // --- 1. Μετρητής χαρακτήρων ---
    const miniDesc = document.getElementById('minidescription');
    const charCount = document.getElementById('charCount');
    if (miniDesc && charCount) {
        charCount.innerText = `${miniDesc.value.length}/75`;
        miniDesc.addEventListener('input', function() {
            charCount.innerText = `${this.value.length}/75`;
        });
    }

    // --- 2. Image Profile Preview ---
    const profileInput = document.getElementById('profile_input');
    const profilePreview = document.getElementById('profile_preview');
    if (profileInput && profilePreview) {
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

        window.removeProfile = function() {
            profileInput.value = ''; 
            profilePreview.innerHTML = ''; 
        };
    }

    // --- 3. Gallery Preview ---
    const galleryInput = document.getElementById('gallery_input');
    const galleryPreview = document.getElementById('gallery_preview');
    let dtGallery = new DataTransfer(); 
    const MAX_PHOTOS = 9;

    if (galleryInput && galleryPreview) {
        galleryInput.addEventListener('change', function(e) {
            // Βρίσκουμε πόσες παλιές φωτογραφίες υπάρχουν ήδη (αν κάνουμε επεξεργασία)
            const oldPhotosCount = galleryPreview.querySelectorAll('.existing-photo').length;
            
            // Βρίσκουμε πόσες νέες φωτογραφίες έχουν μπει ήδη σε αυτό το session
            const addedPhotosCount = dtGallery.files.length;
            
            // Βρίσκουμε πόσες φωτογραφίες επιλέχθηκαν μόλις τώρα
            const incomingPhotosCount = this.files.length;

            // Ελέγχουμε αν το γενικό σύνολο ξεπερνάει τις 9 φωτογραφίες
            if (oldPhotosCount + addedPhotosCount + incomingPhotosCount > MAX_PHOTOS) {
                alert(`Μπορείτε να έχετε το πολύ ${MAX_PHOTOS} φωτογραφίες συνολικά.`);
                this.value = ''; // Καθαρίζουμε την τρέχουσα επιλογή
                return; // Σταματάμε τη διαδικασία
            }

            // Αν το όριο δεν έχει ξεπεραστεί, προσθέτουμε τις φωτογραφίες
            for (let file of this.files) {
                dtGallery.items.add(file);
            }
            galleryInput.files = dtGallery.files; 
            updateGalleryPreview();
        });

        function updateGalleryPreview() {
            const oldPhotos = galleryPreview.querySelectorAll('div:not(.new-preview)');
            galleryPreview.innerHTML = '';
            oldPhotos.forEach(p => galleryPreview.appendChild(p));
            
            Array.from(dtGallery.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const div = document.createElement('div');
                    div.classList.add('new-preview', 'bform-img-wrapper');
                    div.innerHTML = `
                        <img src="${event.target.result}" class="bform-preview-img-small">
                        <button type="button" onclick="removeGalleryImage(${index})" class="bform-remove-img-btn-small">✕</button>
                    `;
                    galleryPreview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }

        window.removeGalleryImage = function(indexToRemove) {
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
        };
    }

    // --- 4. Remove existing gallery image (Edit mode) ---
    window.removeExistingGalleryImage = function(btn, photoPath) {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'delete_photos[]'; 
        hiddenInput.value = photoPath;
        document.getElementById('businessForm').appendChild(hiddenInput);
        btn.closest('.bform-img-wrapper').remove();
    };
});
/*---------------------------------End of Edit-Business scripts------------------------------------------------------*/


/*------------------------------------------- Αξιολογήσεις (Reviews) -------------------------------------------------------*/
window.toggleEdit = function(reviewId) {
    const commentText = document.getElementById('comment-text-' + reviewId);
    const editForm = document.getElementById('edit-form-' + reviewId);

    if (editForm && commentText) {
        if (editForm.style.display === "none" || editForm.style.display === "") {
            editForm.style.display = "block";
            commentText.style.display = "none";
        } else {
            editForm.style.display = "none";
            commentText.style.display = "block";
        }
    }
};
/*------------------------------------------- Τέλος Αξιολογήσεων -------------------------------------------------------*/


/*-------------------------------------View-Business Scripts----------------------------------------------------------*/
// Αυτή η συνάρτηση καλείται όταν ο χρήστης κάνει κλικ στο κουμπί "Περισσότερα" σε μια κάρτα επιχείρησης.
function showBusinessModal(name, cat, type, addr, tel, email, desc, amenities, mapLink, photos) {
    
    document.getElementById('m-name').innerText = name;

    // Μετατροπή της κατηγορίας από τα Αγγλικά της βάσης σε Ελληνικά
    let greekCat = cat === 'accommodation' ? 'Διαμονή' : (cat === 'food_drink' ? 'Φαγητό & Ποτό' : 'Δραστηριότητα');
    document.getElementById('m-type').innerText = greekCat + (type ? ' - ' + type : '');

    document.getElementById('m-address').innerText = addr;
    document.getElementById('m-contact').innerText = "Τηλ: " + tel + " | Email: " + email;
    document.getElementById('m-desc').innerText = desc;

    // --- Διαχείριση Παροχών (Amenities) ---
    const amDiv = document.getElementById('m-amenities');
    amDiv.innerHTML = ''; // Καθαρίζουμε τυχόν παλιά δεδομένα από προηγούμενο κλικ
    
    if (amenities && amenities.length > 0) {
        amenities.forEach(item => {
            const span = document.createElement('span');
            span.className = 'tag';
            span.innerText = item;
            amDiv.appendChild(span);
        });
    } else {
        amDiv.innerText = '-';
    }

    // --- Διαχείριση Φωτογραφιών ---
    const photoDiv = document.getElementById('m-photos-container');
    photoDiv.innerHTML = ''; 

    if (photos && photos.length > 0) {
        photos.forEach(path => {
            const img = document.createElement('img');
            img.src = '/storage/' + path;
            
            // Βασικό inline styling για να φαίνονται ομοιόμορφες οι φωτογραφίες στο modal
            img.style.height = '200px';
            img.style.minWidth = '250px';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '10px';
            img.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
            
            photoDiv.appendChild(img);
        });
        photoDiv.style.display = 'flex';
    } else {
        photoDiv.style.display = 'none'; // Κρύβουμε το container αν δεν υπάρχουν φωτογραφίες για να μην φαίνεται άδειο και να μην πιάνει χώρο.
    }

    // --- Εμφάνιση Κουμπιού Google Maps ---
    const mapBtn = document.getElementById('m-maps');
    if (mapLink && mapLink.trim() !== '' && mapLink !== '#') {
        mapBtn.href = mapLink;
        mapBtn.style.display = 'inline-block';
    } else {
        mapBtn.style.display = 'none';
    }

    // Τέλος, εμφανίζουμε το Modal
    document.getElementById('infoModal').style.display = "block";
}

function closeBusinessModal() {
    document.getElementById('infoModal').style.display = "none";
}

// Αν ο χρήστης κάνει κλικ στο σκοτεινό φόντο γύρω από το Modal, το κλείνουμε
window.addEventListener('click', function (e) {
    const modal = document.getElementById('infoModal');
    if (e.target === modal) {
        closeBusinessModal();
    }
});


document.addEventListener('DOMContentLoaded', function() {
    // Ψάχνουμε το κρυφό input που βάλαμε στο Blade για να πάρουμε το ID
    const businessIdInput = document.getElementById('current-business-id');
    
    // Αν δεν υπάρχει (δηλαδή δεν είμαστε στη σελίδα της επιχείρησης), σταματάμε την εκτέλεση
    if (!businessIdInput) return; 

    const businessId = businessIdInput.value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Αυτή η συνάρτηση τρέχει όταν φορτώνει η σελίδα για να ελέγξει σε ποια λίστα/λίστες ανήκει η επιχείρηση και να τσεκάρει τα αντίστοιχα checkboxes.
    async function initFavStatus() {
        try {
            const checkboxes = document.querySelectorAll('.list-checkbox');
            for (let cb of checkboxes) {
                const listId = cb.value;
                const res = await fetch(`/favorites/${businessId}/status?custom_list_id=${listId}`);
                const data = await res.json();
                cb.checked = data.is_favorite;
            }
        } catch (error) { console.error("Checkbox status check failed", error); }
    }
    // Αυτή η συνάρτηση ενημερώνει το εικονίδιο της καρδιάς στο γενικό κουμπί αγαπημένων ανάλογα με το αν η επιχείρηση είναι σε κάποια λίστα ή όχι.
    window.updateHeartUI = function(isFavorite) {
        const heartIcon = document.querySelector('#generalFavBtn i');
        if (heartIcon) {
            if (isFavorite) {
                heartIcon.classList.replace('fa-regular', 'fa-solid');
            } else {
                heartIcon.classList.replace('fa-solid', 'fa-regular');
            }
        }
    };
    // Αυτή η συνάρτηση καλείται όταν ο χρήστης κάνει κλικ στο γενικό κουμπί αγαπημένων για να προσθέσει ή να αφαιρέσει την επιχείρηση από τα αγαπημένα του.
    window.toggleGeneralFavorite = async function() {
        const heartIcon = document.querySelector('#generalFavBtn i');
        const wasFavorite = heartIcon.classList.contains('fa-solid');
        
        updateHeartUI(!wasFavorite);

        if (wasFavorite) {
            const checkboxes = document.querySelectorAll('.list-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
        }
        // Κάνουμε την AJAX κλήση για να ενημερώσουμε τον server για την αλλαγή στα αγαπημένα.
        //  Αν υπάρχει πρόβλημα, κάνουμε reload τη σελίδα για να συγχρονιστεί η κατάσταση.
        try {
            const response = await fetch(`/favorites/${businessId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ custom_list_id: null })
            });
            
            if (!response.ok) {
                location.reload(); 
            }
        } catch (error) { 
            console.error(error); 
            location.reload();
        }
    };
    // Αυτή η συνάρτηση καλείται όταν ο χρήστης κάνει κλικ σε ένα από τα checkboxes για να προσθέσει 
    // ή να αφαιρέσει την επιχείρηση από μια συγκεκριμένη λίστα.
  window.toggleListAssignment = async function(businessId, listId, checkbox) {
    // Παίρνουμε το token απευθείας μέσα στη συνάρτηση για να μην έχουμε ποτέ πρόβλημα scope
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    try {
        const response = await fetch(`/favorites/${businessId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ custom_list_id: listId })
        });

        // Διαβάζουμε την απάντηση (για debugging)
        const data = await response.json();
        
        if (data.error) {
            console.error("Σφάλμα από τον server:", data.error);
            checkbox.checked = !checkbox.checked; // Επαναφέρουμε το checkbox
            return;
        }

        // Οπτική ενημέρωση της κεντρικής καρδιάς
        if (checkbox.checked) {
            if (typeof updateHeartUI === 'function') updateHeartUI(true);
        } else {
            const anyChecked = Array.from(document.querySelectorAll('.list-checkbox')).some(cb => cb.checked);
            if (!anyChecked) {
                if (typeof updateHeartUI === 'function') updateHeartUI(false);
            }
        }
    } catch (error) { 
        // Αν σκάσει το δίκτυο
        checkbox.checked = !checkbox.checked;
        console.error("Αποτυχία σύνδεσης:", error); 
    }
};

    // Αυτή η συνάρτηση καλείται όταν ο χρήστης δημιουργεί μια νέα λίστα από το dropdown για να την προσθέσει απευθείας στα αγαπημένα του.
    window.toggleDropdown = function() {
        const menu = document.getElementById("listsMenu");
        if (menu) {
            menu.classList.toggle("show");
        }
    };  

    // Αυτή η συνάρτηση καλείται όταν ο χρήστης θέλει να δημιουργήσει μια νέα λίστα και να προσθέσει την επιχείρηση σε αυτήν ταυτόχρονα.
    window.createNewListAndAssign = async function(businessId) {
        const input = document.getElementById('newListNameInput');
        const listName = input.value.trim();

        if (!listName) {
            alert("Παρακαλώ δώστε ένα όνομα για τη λίστα.");
            return;
        }

        try {
            const createRes = await fetch('/lists', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: listName })
            });

            const newList = await createRes.json();

            if (newList.success) {
                await fetch(`/favorites/${businessId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ custom_list_id: newList.list.id })
                });

                input.value = '';
                if (typeof updateHeartUI === 'function') {
                    updateHeartUI(true);
                }
                
                alert("Η λίστα δημιουργήθηκε και η επιχείρηση προστέθηκε!");
                location.reload(); 
            }
        } catch (error) {
            console.error("Σφάλμα κατά τη δημιουργία λίστας:", error);
        }
    };

    initFavStatus();
});

/* --- Photo Gallery / Slider Modal --- */
var slideIndex = 1;
function openModal() { document.getElementById("myModal").style.display = "block"; }
function closeModal() { document.getElementById("myModal").style.display = "none"; }
function plusSlides(n) { showSlides(slideIndex += n); }
function currentSlide(n) { showSlides(slideIndex = n); }
function showSlides(n) {
    var slides = document.getElementsByClassName("mySlides");
    if (slides.length === 0) return;
    if (n > slides.length) slideIndex = 1;
    if (n < 1) slideIndex = slides.length;
    for (var i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    if(slides[slideIndex-1]) slides[slideIndex-1].style.display = "block";
}
/*---------------------------------End of View-Business Scripts------------------------------------------------------*/


/*-------------------------------------------All Business Scripts (Filters & Favorites)-------------------------------------------------------*/

// --- ΦΙΛΤΡΑΡΙΣΜΑ ΚΑΡΤΩΝ ---
function applyFilters() {
    // Μαζεύουμε τις τιμές (values) από όλα τα τσεκαρισμένα checkboxes
    const locs = Array.from(document.querySelectorAll('.loc-filter:checked')).map(cb => cb.value);
    const cats = Array.from(document.querySelectorAll('.cat-filter:checked')).map(cb => cb.value);
    
    const cards = document.querySelectorAll('.latest-card');

    cards.forEach(card => {
        const cardLoc = card.getAttribute('data-location');
        const cardCat = card.getAttribute('data-category');

        // Αν το array είναι άδειο, σημαίνει ότι δεν έχει επιλεγεί κανένα φίλτρο (άρα τα δείχνουμε όλα).
        // Αλλιώς, ελέγχουμε αν τα δεδομένα της κάρτας υπάρχουν μέσα στις επιλογές του χρήστη.
        const showLoc = locs.length === 0 || locs.includes(cardLoc);
        const showCat = cats.length === 0 || cats.includes(cardCat);

        card.style.display = (showLoc && showCat) ? "flex" : "none";
    });
}

// Προσθέτουμε Event Listener σε κάθε checkbox για να φιλτράρει δυναμικά τη λίστα των επιχειρήσεων κάθε φορά που ο χρήστης αλλάζει μια επιλογή
document.querySelectorAll('.filter-check').forEach(cb => {
    cb.addEventListener('change', applyFilters);
});

// Συνάρτηση για το κουμπί "Καθαρισμός Φίλτρων"
function resetAllFilters() {
    document.querySelectorAll('.filter-check').forEach(cb => cb.checked = false);
    applyFilters();
}


/* ------------------------ JAVASCRIPT ΓΙΑ ΤΑ ΑΓΑΠΗΜΕΝΑ (AJAX) ---------------------------------- */
document.addEventListener("click", async function (e) {
    // Ψάχνουμε αν το κλικ έγινε πάνω σε κουμπί αγαπημένων (ή μέσα στο εικονίδιο SVG του)
    const favBtn = e.target.closest(".fav-btn");

    if (favBtn) {
        e.preventDefault(); // Αποτρέπουμε το default behavior του κουμπιού 
        e.stopPropagation();

        // Διαβάζουμε το ID της επιχείρησης από το data attribute του κουμπιού και το CSRF token από το meta tag
        const id = favBtn.dataset.id;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const svg = favBtn.querySelector("svg");

        // Κρατάμε το τρέχον χρώμα της καρδιάς, για να το επαναφέρουμε σε περίπτωση που το API επιστρέψει Error
        const isFav = favBtn.classList.contains('active');
        const oldColor = svg.style.fill;

        // Αλλάζουμε ΑΜΕΣΩΣ το χρώμα για να φαίνεται γρήγορο το site στον χρήστη, 
        // πριν καν πάρουμε απάντηση από τον server.
        if (!isFav) {
            svg.style.fill = "#ff4757"; 
            favBtn.classList.add('active');
        } else {
            svg.style.fill = "#ccc"; 
            favBtn.classList.remove('active');
        }

        try {
            // Κάνουμε την AJAX κλήση στον server για να προσθέσουμε ή να αφαιρέσουμε την επιχείρηση από τα αγαπημένα του χρήστη
            const res = await fetch(`/favorite/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            });

            const data = await res.json();

            // Διαβάζουμε τι μας απάντησε ο server και σιγουρεύουμε ότι η καρδιά έχει το σωστό χρώμα
            if (data.status === "added") {
                svg.style.fill = "#ff4757";
                favBtn.classList.add('active');
            } else if (data.status === "removed") {
                svg.style.fill = "#ccc";
                favBtn.classList.remove('active');
            }

        } catch (err) {
            // Αν υπάρχει πρόβλημα με την κλήση (π.χ. ο server δεν απαντάει), επαναφέρουμε το χρώμα της καρδιάς 
            // στην προηγούμενη κατάσταση και ενημερώνουμε τον χρήστη με ένα alert.
            console.error("Πρόβλημα με την προσθήκη στα αγαπημένα:", err);
            
            svg.style.fill = oldColor;
            if (isFav) {
                favBtn.classList.add('active');
            } else {
                favBtn.classList.remove('active');
            }
            
            alert("Σφάλμα σύνδεσης. Η αλλαγή δεν αποθηκεύτηκε.");
        }
    }
});
/*---------------------------------------End of All Business Scripts------------------------------------------------------*/

/*---------------------------------------Scripts για τη σελίδα των αγαπημένων (Favorites)------------------------------------------------------*/
/*------------------------------------------- Σελίδα Αγαπημένων & Λιστών -------------------------------------------------------*/

// 1. Συνάρτηση για αλλαγή λίστας
window.filterList = function(listId) {
    window.location.href = `/favorites?list=${listId}`;
};

// 2. Συνάρτηση για δημιουργία νέας λίστας
window.createNewList = function() {
    const listName = prompt("Δώστε όνομα για τη νέα λίστα:");
    
    if (listName && listName.trim() !== "") {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/lists', { // Αντικατάσταση του route('lists.store')
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: listName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Κάνουμε reload τη σελίδα για να εμφανιστεί η νέα λίστα σωστά
                window.location.reload();
            } else {
                alert('Υπήρξε ένα πρόβλημα κατά τη δημιουργία της λίστας.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Σφάλμα σύνδεσης.');
        });
    }
};

// 3. Συνάρτηση Μετονομασίας Λίστας
window.renameList = function(listId, oldName) {
    const newName = prompt("Νέο όνομα λίστας:", oldName);
    
    if (newName && newName.trim() !== "" && newName !== oldName) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/lists/${listId}`, {
            method: 'PATCH', 
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: newName })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => console.error("Σφάλμα μετονομασίας:", error));
    }
};

// 4. Συνάρτηση Διαγραφής Λίστας
window.deleteList = function(listId) {
    if (!confirm("Είσαι σίγουρος πως θέλεις να διαγράψεις αυτή τη λίστα;")) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/lists/${listId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/favorites'; 
        }
    })
    .catch(error => console.error("Σφάλμα διαγραφής:", error));
};

// 5. Διαγραφή Αγαπημένου (Από τη λίστα)
window.removeFavorite = async function(businessId) {
    if(!confirm('Αφαίρεση από αυτή τη λίστα;')) return;

    const card = document.getElementById(`fav-card-${businessId}`);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Κάνουμε ένα απλό εφέ εξαφάνισης της κάρτας για καλύτερη εμπειρία χρήστη, ενώ περιμένουμε την απάντηση από τον server.
    card.style.opacity = '0';
    setTimeout(() => { if (card) card.style.display = 'none'; }, 300);

    try {
        const response = await fetch(`/favorite/${businessId}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({}) 
        });

        const data = await response.json();

        if(data.status === "removed" || data.status === "removed_globally" || !data.is_favorite) {
            window.location.reload();
        } else {
            // Αν για κάποιο λόγο ο server δεν αφαιρεί την επιχείρηση, επαναφέρουμε την κάρτα στην προηγούμενη κατάσταση και ενημερώνουμε τον χρήστη.
            card.style.opacity = '1';
            card.style.display = 'block';
            alert("Το στοιχείο δεν αφαιρέθηκε.");
        }

    } catch(err) {
        card.style.opacity = '1';
        card.style.display = 'block';
        alert("Σφάλμα σύνδεσης με τον server.");
    }
};

/*---------------------------------------End of Scripts για τη σελίδα των αγαπημένων (Favorites)------------------------------------------------------*/


