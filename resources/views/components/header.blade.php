

  <div class="header-content">
    
    <nav class="navbar">
      <div class="logo-area">
        <div class="city-dropdown">
          <button class="city-btn" id="cityBtn">Volos ▼</button>
          <div class="dropdown-content" id="cityMenu">
            <a href="#">Λάρισα</a>
            <a href="#">Τρίκαλα</a>
            <a href="#">Καρδίτσα</a>
            <a href="#">Αθήνα</a>
          </div>
        </div>
      </div>

      <ul class="nav-links">
        <li><a href="/sights">Αξιοθέατα</a></li>
        <li><a href="/food-drink">Φαγητό & Ποτό</a></li>
        <li class="nav-brand"><a href="{{ url('/') }}" class="logo active" id="title">Discover <span>City</span></a></li>
        <li><a href="/stay">Διαμονή</a></li>
        <li><a href="/activities">Δραστηριότητες</a></li>
      </ul>

      <div class="auth-section">
        <div class="nav-search">
          <!-- <input type="text" placeholder="Αναζήτηση..." />
          <button title="Αναζήτηση">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.53 20.47l-3.73-3.73a8 8 0 10-1.06 1.06l3.73 3.73a.75.75 0 101.06-1.06zM4.75 10a5.25 5.25 0 1110.5 0 5.25 5.25 0 01-10.5 0z" /></svg>
          </button> -->
        </div>

       @guest
            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="login-btn btn-link">Σύνδεση</a>
                <a href="{{ route('register') }}" class="signup-btn btn-link">Εγγραφή</a>
            </div>
        @endguest

        @auth
            <div class="user-menu-container">
                <div class="user-icon" id="userIconBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" />
                    </svg>
                </div>

                <div class="user-dropdown" id="userMenu">
                    <a href="{{ route('profile.edit') }}">Προφίλ</a>
                    @auth
                        @if(\App\Models\Business::where('user_id', auth()->id())->doesntExist())
                            <a href="{{ url('/favorites') }}">Αγαπημένα</a>
                        @endif
                    @endauth
                    <a href="{{ url('/form') }}">Καταχώρηση Επιχείρησης</a>
                    @if(auth()->user()->businesses->count() > 0)
                      <a href="{{ route('my.businesses') }}" class="dropdown-item">Οι Επιχειρήσεις μου</a>
                    @endif
                    @if(auth()->user()->is_admin == 1)
                        <a href="{{ url('/admin') }}">Filament Admin</a>
                    @endif
                    <a href="{{ route('logout') }}" class="logout" 
                      onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Αποσύνδεση
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        @endauth
      </div>
    </nav>

    @if(session('success'))
        <div id="success-alert" style="position: fixed; top: 100px; left: 50%; transform: translateX(-50%); background-color: #28a745; color: white; padding: 10px 25px; border-radius: 50px; font-weight: 500; font-size: 14px; box-shadow: 0 5px 20px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 15px; z-index: 9999; animation: slideDown 0.4s ease forwards;">
            <span style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-check-circle" style="font-size: 16px;"></i> 
                {{ session('success') }}
            </span>
            <button onclick="document.getElementById('success-alert').style.display='none'" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer; padding: 0; line-height: 1; opacity: 0.8; transition: 0.2s;">
                &times;
            </button>
        </div>

        <script>
            // Εξαφάνιση με ομαλό animation προς τα πάνω
            setTimeout(function() {
                var alertBox = document.getElementById('success-alert');
                if(alertBox) {
                    alertBox.style.transition = "opacity 0.4s ease, transform 0.4s ease";
                    alertBox.style.opacity = "0";
                    alertBox.style.transform = "translate(-50%, -20px)"; // Φεύγει ελαφρώς προς τα πάνω
                    setTimeout(() => alertBox.style.display = "none", 400); 
                }
            }, 4000); // Φεύγει πιο γρήγορα (στα 4 δευτερόλεπτα)
        </script>
    @endif

    