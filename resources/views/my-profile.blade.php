<x-app-layout>
    <div style="padding-top: 120px; max-width: 800px; margin: 0 auto;">
        
        <h1 style="color: #004080;font-size: 2rem; margin-bottom: 20px;margin-top:-100px; text-align: center;">
            Το Προφίλ μου 
        </h1>

        <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <h3 style="margin-bottom: 15px; color: #333;">Πληροφορίες Λογαριασμού</h3>
            @include('profile.partials.update-profile-information-form')
        </div>
        
        <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <h3 style="margin-bottom: 15px; color: #333;">Αλλαγή Κωδικού Πρόσβασης</h3>
            @include('profile.partials.update-password-form')
        </div>

        <div style="background: #fff5f5; padding: 25px; border-radius: 15px; border: 1px solid #feb2b2; padding-bottom: 100px;">
            <h3 style="margin-bottom: 15px; color: #c53030;">Διαγραφή Λογαριασμού</h3>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
    <footer style="position:bottom;margin-top:60px;"><p>© 2025 Discover City</p></footer>
</x-app-layout>


