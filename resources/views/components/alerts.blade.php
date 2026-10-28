<div>
    <!-- Εμφάνιση επιτυχίας -->
    @if (session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Εμφάνιση σφαλμάτων (είτε από το ->with('error') είτε από το ->withErrors()) -->
    @if (session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 10px;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>