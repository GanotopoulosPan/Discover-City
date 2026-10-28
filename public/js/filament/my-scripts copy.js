// Τοποθέτηση του δικού script για τις λειτουργίες της σελίδας
//<script src="{{ asset('js/filament/my-scripts.js') }}"></script>

//------------------------WEATHER WIDGET (https://weatherwidget.io/)------------------------
document.addEventListener('DOMContentLoaded', function () {
    // --- WEATHER WIDGET ---
    !function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (!d.getElementById(id)) {
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://weatherwidget.io/js/widget.min.js';
            fjs.parentNode.insertBefore(js, fjs);
        }
    }(document, 'script', 'weatherwidget-io-js');

    ///--------------------------END OF WEATHER WIDGET------------------------------------



    // ---------------------------------DROPDOWN ΠΟΛΗΣ ------------------------------------
    const cityBtn = document.getElementById("cityBtn");
    const cityMenu = document.getElementById("cityMenu");

    // Προσθήκη event listener για το κουμπί της πόλης
    if (cityBtn && cityMenu) {
        cityBtn.addEventListener("click", () => {
            const isOpen = cityMenu.style.display === "block";
            cityMenu.style.display = isOpen ? "none" : "block";
            cityBtn.innerHTML = isOpen ? "Volos ▼" : "Volos ▲";
        });
    }

    // ---------------------------------ΤΈΛΟΣ DROPDOWN ΠΟΛΗΣ ------------------------------------

    // ----------------------------------DROPDOWN ΧΡΗΣΤΗ ---------------------------------
    const userBtn = document.getElementById("userIconBtn");
    const userMenu = document.getElementById("userMenu");

    // Προσθήκη event listener για το κουμπί του χρήστη
    if (userBtn && userMenu) {
        userBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            const isUserOpen = userMenu.style.display === "block";
            userMenu.style.display = isUserOpen ? "none" : "block";
        });
    }
    // Κλείσιμο dropdowns όταν κάνουμε κλικ έξω από αυτά
    window.addEventListener("click", (e) => {
        if (cityMenu && !e.target.closest(".city-dropdown")) {
            cityMenu.style.display = "none";
            cityBtn.innerHTML = "Volos ▼";
        }
        if (userMenu && !e.target.closest(".user-menu-container")) {
            userMenu.style.display = "none";
        }
    });
});

// ---------------------------------ΤΈΛΟΣ DROPDOWN ΧΡΗΣΤΗ ------------------------------------

// ---------------------------------------------- LEAFLET MAP ---------------------------------------------------
/* --- MY-SCRIPTS.JS --- */
document.addEventListener("DOMContentLoaded", function () {
    // 1. Καθαρισμός και Αρχικοποίηση Container
    const mapContainer = document.getElementById('map');
    let map;

    document.addEventListener("DOMContentLoaded", () => {

        if (map) {
            map.remove();
        }

        map = L.map('map', {
            scrollWheelZoom: false
        }).setView([39.3621, 22.9423], 13);

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom: 19
            }
        ).addTo(map);

        placePins();

    }); if (!mapContainer) return;

    // Αν το Leaflet έχει ήδη δεσμεύσει το στοιχείο, το καθαρίζουμε
    if (mapContainer._leaflet_id) {
        mapContainer._leaflet_id = null;
    }

    // 2. Δημιουργία Χάρτη σε τοπική μεταβλητή (Local Scope) για αποφυγή conflicts
    const myMap = L.map('map', {
        scrollWheelZoom: false, // Απενεργοποίηση zoom με scroll
        doubleClickZoom: false, // Απενεργοποίηση zoom με διπλό κλικ
        boxZoom: false, // Απενεργοποίηση zoom με επιλογή περιοχής          
        dragging: true, // Επιτρέπουμε το dragging για καλύτερη εμπειρία χρήστη
    }).setView([39.3621, 22.9423], 13);

    // Προσθήκη των Tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(myMap);

    // 3. Δεδομένα από το Laravel (Διαβάζονται από την global μεταβλητή του window)
    const mapBusinesses = window.mapBusinesses;

    // 4. Ορισμός Custom Icons με σωστά Paths
    const customIcons = {
        'accommodation': L.icon({
            iconUrl: window.stayIconUrl || '/icons/stay.png',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        }),
        'food_drink': L.icon({
            iconUrl: window.restaurantIconUrl || '/icons/restaurant.png',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        }),
        'activity': L.icon({
            iconUrl: window.activityIconUrl || '/icons/activity.png',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -40]
        })
    };

    // 5. Σχεδιασμός των Pins
    if (Array.isArray(mapBusinesses)) {
        mapBusinesses.forEach(business => {
            if (business.lat && business.lon) {

                // Δημιουργία Popup HTML
                const popupText = `
                    <div style="text-align: center; min-width: 150px;">
                        <h3 style="margin: 0 0 5px 0; color: #004080; font-size: 16px;">${business.name}</h3>
                        <p style="margin: 0 0 10px 0; font-size: 13px; color: #555;">${business.address || ''}</p>
                        <a href="/business/${business.id}" 
                           style="display: inline-block; background: #ffd700; color: #004080; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 13px;">
                            Δείτε το
                        </a>
                    </div>
                `;

                // Επιλογή εικονιδίου (αν δεν βρει την κατηγορία, βάζει activity)
                const iconToUse = customIcons[business.category] || customIcons['activity'];

                // Προσθήκη του Marker στον χάρτη
                L.marker([parseFloat(business.lat), parseFloat(business.lon)], { icon: iconToUse })
                    .addTo(myMap)
                    .bindPopup(popupText);
            } else {
                console.warn("Η επιχείρηση '" + business.name + "' δεν έχει συντεταγμένες.");
            }
        });
    }
});



/*---------------------------------End of Business Details scripts------------------------------------------------------*/

/*---------------------------------------Edit-Business scripts------------------------------------------------------------------*/
window.onload = function () {
    toggleDynamicFields();
};

function toggleDynamicFields() {
    const category = document.getElementById('businessCategory').value;

    const accFields = document.getElementById('accFields');
    const foodFields = document.getElementById('foodFields');
    const actFields = document.getElementById('actFields');
    const commonBottom = document.getElementById('commonBottom');

    const descLabel = document.getElementById('descLabel');

    accFields.style.display = 'none';
    foodFields.style.display = 'none';
    actFields.style.display = 'none';

    document.getElementById('accType').required = false;
    document.getElementById('foodType').required = false;
    document.getElementById('actType').required = false;
    document.getElementById('duration').required = false;
    document.getElementById('schedule').required = false;

    if (category === 'accommodation') {
        accFields.style.display = 'block';
        document.getElementById('accType').required = true;
        descLabel.innerText = "Περιγραφή Καταλύματος *";
        commonBottom.style.display = 'block';

    } else if (category === 'food_drink') {
        foodFields.style.display = 'block';
        document.getElementById('foodType').required = true;
        descLabel.innerText = "Περιγραφή Επιχείρησης & Σπεσιαλιτέ *";
        commonBottom.style.display = 'block';

    } else if (category === 'activity') {
        actFields.style.display = 'block';
        document.getElementById('actType').required = true;
        document.getElementById('duration').required = true;
        document.getElementById('schedule').required = true;
        descLabel.innerText = "Περιγραφή Εμπειρίας *";
        commonBottom.style.display = 'block';

    } else {
        commonBottom.style.display = 'none';
    }
}
/*---------------------------------End of Edit-Business scripts------------------------------------------------------*/



/*-------------------------------------View-Business Scripts----------------------------------------------------------*/

// Η συνάρτηση πλέον δέχεται και τον πίνακα με τις φωτογραφίες (photosArr)
function showDetails(name, cat, type, addr, phone, email, desc, amenities, mapsUrl, photosArr) {
    document.getElementById('m-name').innerText = name;

    // Φτιάχνουμε όμορφο κείμενο για την κατηγορία
    let catText = cat === 'accommodation' ? 'Διαμονή' : (cat === 'food_drink' ? 'Φαγητό & Ποτό' : 'Δραστηριότητα');
    document.getElementById('m-type').innerText = catText + (type ? ' - ' + type : '');

    document.getElementById('m-address').innerText = addr;
    document.getElementById('m-contact').innerText = "Τηλ: " + phone + " | Email: " + email;
    document.getElementById('m-desc').innerText = desc;

    // 1. ΕΜΦΑΝΙΣΗ ΠΑΡΟΧΩΝ
    const amenDiv = document.getElementById('m-amenities');
    amenDiv.innerHTML = '';
    if (amenities && amenities.length > 0) {
        amenities.forEach(item => {
            const span = document.createElement('span');
            span.className = 'tag';
            span.innerText = item;
            amenDiv.appendChild(span);
        });
    } else {
        amenDiv.innerText = '-';
    }

    // 2. ΕΜΦΑΝΙΣΗ ΦΩΤΟΓΡΑΦΙΩΝ
    const photosDiv = document.getElementById('m-photos-container');
    photosDiv.innerHTML = ''; // Καθαρίζουμε τις παλιές

    if (photosArr && photosArr.length > 0) {
        photosArr.forEach(photoPath => {
            const img = document.createElement('img');
            // Το Laravel ψάχνει στο storage/
            img.src = '/storage/' + photoPath;
            img.style.height = '200px';
            img.style.minWidth = '250px';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '10px';
            img.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
            photosDiv.appendChild(img);
        });
        photosDiv.style.display = 'flex';
    } else {
        photosDiv.style.display = 'none'; // Αν δεν έχει φώτος, κρύβουμε το container
    }

    // 3. ΕΜΦΑΝΙΣΗ GOOGLE MAPS
    const mapsBtn = document.getElementById('m-maps');
    if (mapsUrl && mapsUrl.trim() !== '' && mapsUrl !== '#') {
        mapsBtn.href = mapsUrl;
        mapsBtn.style.display = 'inline-block';
    } else {
        mapsBtn.style.display = 'none';
    }

    // Ανοίγουμε το παράθυρο
    document.getElementById('infoModal').style.display = "block";
}

function closeModal() {
    document.getElementById('infoModal').style.display = "none";
}

// Κλείσιμο αν πατήσει εκτός του παραθύρου
window.onclick = function (event) {
    if (event.target == document.getElementById('infoModal')) {
        closeModal();
    }
}


/*---------------------------------End of View-Business Scripts------------------------------------------------------*/

/*-------------------------------------------All Business Scripts-------------------------------------------------------*/
/* --- JAVASCRIPT ΓΙΑ ΤΑ DROPDOWNS --- */
const cityBtn = document.getElementById("cityBtn");
const cityMenu = document.getElementById("cityMenu");
const userBtn = document.getElementById("userIconBtn");
const userMenu = document.getElementById("userMenu");



/* --- ΑΥΤΟΜΑΤΟ ΦΙΛΤΡΑΡΙΣΜΑ ΜΕ CHECKBOXES --- */
function filterCards() {
    const activeLocs = Array.from(document.querySelectorAll('.loc-filter:checked')).map(cb => cb.value);
    const activeCats = Array.from(document.querySelectorAll('.cat-filter:checked')).map(cb => cb.value);
    const cards = document.querySelectorAll('.latest-card');

    cards.forEach(card => {
        const cardLoc = card.getAttribute('data-location');
        const cardCat = card.getAttribute('data-category');

        const locMatch = activeLocs.length === 0 || activeLocs.includes(cardLoc);
        const catMatch = activeCats.length === 0 || activeCats.includes(cardCat);

        card.style.display = (locMatch && catMatch) ? "flex" : "none";
    });
}

document.querySelectorAll('.filter-check').forEach(checkbox => {
    checkbox.addEventListener('change', filterCards);
});

function resetFilters() {
    document.querySelectorAll('.filter-check').forEach(cb => cb.checked = false);
    filterCards();
}

/* ------------------------ JAVASCRIPT ΓΙΑ ΤΑ ΑΓΑΠΗΜΕΝΑ  ---------------------------------- */


/* --- FAVORITES (Optimistic Update) --- */
document.addEventListener("click", async function (e) {
    const btn = e.target.closest(".fav-btn");

    if (btn) {
        e.preventDefault();
        e.stopPropagation();

        const businessId = btn.dataset.id;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const svg = btn.querySelector("svg");

        // Κρατάμε το παλιό χρώμα σε περίπτωση που αποτύχει το fetch
        const isCurrentlyActive = btn.classList.contains('active');
        const originalFill = svg.style.fill;

        // --- ΒΗΜΑ 1: Ακαριαία αλλαγή στο UI (Optimistic) ---
        if (!isCurrentlyActive) {
            svg.style.fill = "#ff4757"; // Γίνεται αμέσως κόκκινο
            btn.classList.add('active');
        } else {
            svg.style.fill = "#ccc"; // Γίνεται αμέσως γκρι
            btn.classList.remove('active');
        }

        try {
            const response = await fetch(`/favorite/${businessId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            });

            const data = await response.json();

            // ΒΗΜΑ 2: Επιβεβαίωση από τον server
            // Αν ο server επιστρέψει κάτι διαφορετικό από αυτό που κάναμε, διορθώνουμε
            if (data.status === "added") {
                svg.style.fill = "#ff4757";
                btn.classList.add('active');
            } else if (data.status === "removed") {
                svg.style.fill = "#ccc";
                btn.classList.remove('active');
            }

        } catch (err) {
            // ΒΗΜΑ 3: Rollback αν υπάρξει σφάλμα (π.χ. έπεσε το ίντερνετ)
            console.error(err);
            svg.style.fill = originalFill;
            if (isCurrentlyActive) btn.classList.add('active');
            else btn.classList.remove('active');
            alert("Σφάλμα σύνδεσης. Η αλλαγή δεν αποθηκεύτηκε.");
        }
    }
});

/* --- NAVBAR SCROLL EFFECT --- */
const navbar = document.querySelector('.navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) navbar.classList.add('scrolled');
    else navbar.classList.remove('scrolled');
});

/*---------------------------------------End of All Business Scripts------------------------------------------------------*/


