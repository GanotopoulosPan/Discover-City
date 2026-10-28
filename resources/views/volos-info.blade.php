<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Αγαπημένα & Λίστες - Discover City</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
  <style>
    body {
        background: #728fa5;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        color: #2c3e50;
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }

    .page-wrapper {
        display: flex;
        gap: 40px;
        max-width: 1700px;
        margin: 0 auto;
        padding: 0px 50px;
        align-items: flex-start;
        min-height: 100vh;
    }

    .main-content-wrapper {
        flex: 1; 
        min-width: 0; 
        display: flex;
        flex-direction: column;
    }

    .container {
        width: 100%;
        background: #c9c9c9;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        padding: 40px 20px;
        margin-top: 60px;
        box-sizing: border-box;
    }

    .right-page-sidebar {
        flex: 0 0 350px; 
        position: sticky; 
        top: 40px;
        
        background: #c9c9c9; 
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 40px 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex;
        flex-direction: column;
        box-sizing: border-box;
        margin-top: 60px;
    }

    .right-page-sidebar h3 {
        color: #164574;
        font-size: 1.6rem;
        font-weight: 800;
        margin-top: 0;
        margin-bottom: 30px;
        text-align: center;
        border-bottom: 2px solid rgba(255, 255, 255, 0.4);
        padding-bottom: 20px;
    }

    .side-card-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #c9c9c9;
        padding: 30px 20px;
        border-radius: 16px;
        text-decoration: none;
        color: #164574;
        margin-bottom: 35px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
       
      
    }

    .side-card-link i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #728fa5;
        transition: all 0.3s ease;
    }

    .side-card-link span {
        font-size: 1.2rem;
        font-weight: 700;
    }

    .side-card-link:hover {
        background: rgba(255, 255, 255, 0.4);
        color: #164574;
        transform: translateY(-8px);
        border-color: #ffd700; 
        box-shadow: 0 15px 35px rgba(22, 69, 116, 0.3);
    }

    .side-card-link:hover i {
        color: #164574; 
    }

    h1 {
        color: #164574;
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 20px;
        margin-top: 0; 
        text-transform: capitalize;
        letter-spacing: -0.02em;
        text-align: center;
    }

    .intro-quote {
        font-size: 1.3rem;
        line-height: 1.7;
        color: #164574;
        font-style: italic;
        padding: 25px 30px 25px 60px;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 16px;
        border-left: 6px solid #728fa5;
        margin-bottom: 40px;
        position: relative;
    }

    .intro-quote::before {
        content: "\f10d";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        top: 25px;
        left: 20px;
        color: #728fa5;
        font-size: 1.5rem;
        opacity: 0.5;
    }

    .split-layout {
        display: flex;
        gap: 40px;
        align-items: flex-start;
    }

    .sidebar-menu {
        flex: 0 0 280px; 
        display: flex;
        flex-direction: column;
        gap: 12px;
        position: sticky;
        top: 20px;
    }

    .nav-btn {
        background: transparent;
        color: #4a4a4a;
        border: none;
        padding: 18px 25px;
        border-radius: 14px;
        font-size: 1.1rem;
        font-weight: 600;
        text-align: left;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        font-family: inherit;
    }

    .nav-btn i {
        width: 25px;
        font-size: 1.2rem;
        margin-right: 15px;
        color: #728fa5;
        transition: color 0.3s ease;
    }

    .nav-btn:hover {
        background: rgba(255, 255, 255, 0.5);
        color: #164574;
        transform: translateX(5px);
    }

    .nav-btn.active {
        background: #164574;
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(22, 69, 116, 0.3);
        transform: translateX(10px);
    }

    .nav-btn.active i { color: #ffffff; }

    .content-area {
        flex: 1;
        background: rgba(255, 255, 255, 0.6);
        padding: 10px 45px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        min-height: 400px;
    }

    .tab-pane {
        display: none;
        animation: fadeSlideUp 0.5s ease forwards;
    }

    .tab-pane.active { display: block; }

    .tab-pane h2 {
        color: #164574;
        font-size: 1.8rem;
        margin-top: 0;
        margin-bottom: 25px;
        font-weight: 700;
        border-bottom: 1px solid #c9c9c9;
        padding-bottom: 15px;
    }

    .tab-pane p {
        font-size: 1.1rem;
        line-height: 1.85;
        color: #555;
        margin-bottom: 20px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0px;
        width: 50px;
        height: 50px;
        background: #bdbdbd;
        border-radius: 14px;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .back-btn:hover { 
        background: #164574; 
        transform: translateY(-2px);
    }

    .back-btn:hover .arrow-left { border-color: #ffffff; }

    .arrow-left {
        width: 12px;
        height: 12px;
        border-left: 4px solid #164574; 
        border-bottom: 4px solid #164574; 
        transform: rotate(45deg); 
        margin-left: 6px;
        transition: border-color 0.3s ease;
    }

    footer {
        text-align: center;
        margin-top: 0;

        font-size: 0.9rem;
        color: #e0e0e0;
        letter-spacing: 1px;
    }

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 1250px) {
        .page-wrapper {
            flex-direction: column;
            padding: 20px;
        }
        .right-page-sidebar {
            flex: none;
            width: 100%;
            height: auto;
            position: static;
            flex-direction: row;
            justify-content: space-between;
            gap: 20px;
        }
        .right-page-sidebar h3 {
            display: none; 
        }
        .side-card-link {
            flex: 1;
            padding: 15px;
            margin-bottom: 0;
        }
        .side-card-link i { font-size: 2rem; margin-bottom: 10px; }
        .side-card-link span { font-size: 1rem; }
    }

    @media (max-width: 950px) {
        .split-layout { flex-direction: column; }
        .sidebar-menu {
            flex: none; width: 100%; position: static; flex-direction: row; overflow-x: auto; padding-bottom: 15px;
        }
        .nav-btn { flex: 0 0 auto; white-space: nowrap; }
        .container { padding: 30px 25px; }
        .content-area { padding: 30px 20px; }
        .right-page-sidebar { flex-direction: column; }
    }
  </style>
</head>

<body>
    
    <div class="page-wrapper">
        
        
        <div class="main-content-wrapper">
            <div class="container">
                
                <a href="{{ url('/') }}" class="back-btn" aria-label="Επιστροφή">
                    <div class="arrow-left"></div>
                </a>
                
                <h1>{{ $title }}</h1>

                <div class="info-content">
                    
                    @if($type === 'history')
                        <p class="intro-quote">Χτισμένος στον μυχό του Παγασητικού, ο Βόλος αποτελεί το ιδανικό σταυροδρόμι ανάμεσα στο βουνό των Κενταύρων και τη θάλασσα, με πλούσια βιομηχανική και αρχαία κληρονομιά.</p>
                        
                        <div class="split-layout">
                            <div class="sidebar-menu">
                                <button class="nav-btn active" onclick="switchTab(event, 'tab-1')"><i class="fa-solid fa-building-columns"></i> Η Αρχαιότητα</button>
                                <button class="nav-btn" onclick="switchTab(event, 'tab-2')"><i class="fa-solid fa-scroll"></i> Βυζάντιο & Τουρκοκρατία</button>
                                <button class="nav-btn" onclick="switchTab(event, 'tab-3')"><i class="fa-solid fa-city"></i> Σύγχρονη Πόλη</button>
                                <button class="nav-btn" onclick="switchTab(event, 'tab-4')"><i class="fa-solid fa-users"></i> Μικρασιατική Καταστροφή</button>
                                <button class="nav-btn" onclick="switchTab(event, 'tab-5')"><i class="fa-solid fa-house-crack"></i> Σεισμοί & Αναγέννηση</button>
                                <button class="nav-btn" onclick="switchTab(event, 'tab-6')"><i class="fa-solid fa-map-location-dot"></i> Ο Βόλος Σήμερα</button>
                            </div>

                            <div class="content-area">
                                <div id="tab-1" class="tab-pane active">
                                    <h2>Η Μυθολογική Αφετηρία και η Αρχαιότητα</h2>
                                    <p>Η ιστορία της περιοχής χάνεται στα βάθη των αιώνων. Εδώ τοποθετείται η αρχαία Ιωλκός, η πατρίδα του Ιάσονα. Από τις ακτές αυτές ξεκίνησε η Αργώ για το θρυλικό ταξίδι της Αργοναυτικής Εκστρατείας προς αναζήτηση του Χρυσόμαλλου Δέρατος.</p>
                                    <p>Στην ευρύτερη περιοχή άκμασαν σημαντικοί οικισμοί, όπως το Σέσκλο και το Διμήνι, που αποτελούν μερικά από τα αρχαιότερα δείγματα νεολιθικού πολιτισμού στην Ευρώπη. Κατά τους κλασικούς χρόνους, η Δημητριάδα, που ιδρύθηκε από τον Δημήτριο τον Πολιορκητή το 293 π.Χ., αναδείχθηκε σε ένα από τα ισχυρότερα ναυτικά και στρατιωτικά κέντρα της Μακεδονίας.</p>
                                </div>
                                <div id="tab-2" class="tab-pane">
                                    <h2>Βυζάντιο και Τουρκοκρατία</h2>
                                    <p>Στα βυζαντινά χρόνια, η κατοίκηση μετατοπίστηκε προς το λόφο των Παλαιών, όπου χτίστηκε το κάστρο του Βόλου. Η ονομασία "Βόλος" εμφανίζεται κατά τον 14ο αιώνα, πιθανώς ως παραφθορά του "Γόλος" ή της λέξης "βόλος" (ρίψη διχτυών).</p>
                                    <p>Κατά την περίοδο της Τουρκοκρατίας, η δραστηριότητα μεταφέρθηκε στις πλαγιές του Πηλίου, καθώς οι κάτοικοι αναζητούσαν ασφάλεια από τους πειρατές. Εκεί αναπτύχθηκε μια σπουδαία πνευματική και οικονομική κίνηση.</p>
                                </div>
                                <div id="tab-3" class="tab-pane">
                                    <h2>Η Γέννηση της Σύγχρονης Πόλης</h2>
                                    <p>Η καθοριστική στιγμή για τον Βόλο ήταν το 1881, όταν η Θεσσαλία απελευθερώθηκε και προσαρτήθηκε στο ελληνικό κράτος. Η πόλη άρχισε να χτίζεται "εκ του μηδενός" με βάση το ιπποδάμειο πολεοδομικό σύστημα.</p>
                                </div>
                                <div id="tab-4" class="tab-pane">
                                    <h2>Η Μικρασιατική Καταστροφή</h2>
                                    <p>Το 1922, ο Βόλος δέχτηκε χιλιάδες πρόσφυγες από τη Μικρά Ασία. Η εγκατάστασή τους στη Νέα Ιωνία άλλαξε ριζικά την κοινωνική και πολιτιστική δομή της πόλης, φέρνοντας την παράδοση των τσιπουράδικων.</p>
                                </div>
                                <div id="tab-5" class="tab-pane">
                                    <h2>Οι Σεισμοί και η Αναγέννηση</h2>
                                    <p>Η πόλη δοκιμάστηκε σκληρά το 1955 από καταστροφικούς σεισμούς που ισοπέδωσαν μεγάλο μέρος της νεοκλασικής αρχιτεκτονικής της. Ωστόσο, ο Βόλος κατάφερε να ανοικοδομηθεί γρήγορα.</p>
                                </div>
                                <div id="tab-6" class="tab-pane">
                                    <h2>Ο Βόλος Σήμερα</h2>
                                    <p>Σήμερα, ο Βόλος είναι μια ζωντανή φοιτητούπολη, ένας κορυφαίος τουριστικός προορισμός και ένα σύγχρονο αστικό κέντρο που δεν ξεχνά τις ρίζες του.</p>
                                </div>
                            </div>
                        </div>

                    @elseif($type === 'mythology')
                        <p class="intro-quote">Από εδώ ξεκίνησε η θρυλική Αργοναυτική Εκστρατεία. Η σύγχρονη "Αργώ", αντίγραφο του αρχαίου πλοίου, κοσμεί την παραλία της πόλης θυμίζοντας το ένδοξο παρελθόν.</p>
                        <div class="split-layout">
                            <div class="sidebar-menu">
                                <button class="nav-btn active" onclick="switchTab(event, 'myth-tab-1')"><i class="fa-solid fa-crown"></i> Η Αφετηρία</button>
                                <button class="nav-btn" onclick="switchTab(event, 'myth-tab-2')"><i class="fa-solid fa-sailboat"></i> Η Αργώ & Οι Ήρωες</button>
                                <button class="nav-btn" onclick="switchTab(event, 'myth-tab-3')"><i class="fa-solid fa-water"></i> Οι Δοκιμασίες</button>
                                <button class="nav-btn" onclick="switchTab(event, 'myth-tab-4')"><i class="fa-solid fa-dragon"></i> Κολχίδα & Μήδεια</button>
                            </div>
                            <div class="content-area">
                                <div id="myth-tab-1" class="tab-pane active">
                                    <h2>Η Αφετηρία και η Προφητεία</h2>
                                    <p>Όλα ξεκίνησαν στην αρχαία Ιωλκό (Βόλος), όταν ο σφετεριστής του θρόνου, Πελίας, διέταξε τον Ιάσονα να φέρει το Χρυσόμαλλο Δέρας από τη μακρινή Κολχίδα.</p>
                                </div>
                                <div id="myth-tab-2" class="tab-pane">
                                    <h2>Η Αργώ και οι Ήρωες</h2>
                                    <p>Για το ταξίδι ναυπηγήθηκε η Αργώ, το ταχύτερο πλοίο της εποχής. Το πλήρωμα, οι Αργοναύτες, αποτελούνταν από τους κορυφαίους ήρωες της Ελλάδας.</p>
                                </div>
                                <div id="myth-tab-3" class="tab-pane">
                                    <h2>Οι Δοκιμασίες του Ταξιδιού</h2>
                                    <p>Στη διαδρομή τους, οι Αργοναύτες ήρθαν αντιμέτωποι με τρομερά εμπόδια, με πιο γνωστό τις Συμπληγάδες Πέτρες.</p>
                                </div>
                                <div id="myth-tab-4" class="tab-pane">
                                    <h2>Η Κολχίδα και η Μήδεια</h2>
                                    <p>Φτάνοντας στον προορισμό τους, ο Ιάσονας χρειάστηκε τη βοήθεια της μάγισσας Μήδειας. Με τα μαγικά της φίλτρα κατάφερε να πάρει το Δέρας.</p>
                                </div>
                            </div>
                        </div>
                    
                    @elseif($type === 'cuisine')
                        <p class="intro-quote">Σήμα κατατεθέν της πόλης είναι τα φημισμένα τσιπουράδικα. Μια παράδοση που ξεκίνησε από τους Μικρασιάτες πρόσφυγες και εξελίχθηκε σε αγαπημένο τρόπο ζωής.</p>
                        <div class="split-layout">
                            <div class="sidebar-menu">
                                <button class="nav-btn active" onclick="switchTab(event, 'cuis-tab-1')"><i class="fa-solid fa-anchor"></i> Η Προσφυγική Καταγωγή</button>
                                <button class="nav-btn" onclick="switchTab(event, 'cuis-tab-2')"><i class="fa-solid fa-fish-fins"></i> Η "Ιεροτελεστία" του Μεζέ</button>
                                <button class="nav-btn" onclick="switchTab(event, 'cuis-tab-3')"><i class="fa-solid fa-comments"></i> Κοινωνικό Σημείο Αναφοράς</button>
                            </div>
                            <div class="content-area">
                                <div id="cuis-tab-1" class="tab-pane active">
                                    <h2>Η Προσφυγική Καταγωγή</h2>
                                    <p>Όλα ξεκίνησαν μετά το 1922, όταν οι Μικρασιάτες πρόσφυγες εγκαταστάθηκαν στον Βόλο. Οι άνθρωποι αυτοί συνήθιζαν να μαζεύονται μετά τη δουλειά για να πιουν ένα τσιπουράκι.</p>
                                </div>
                                <div id="cuis-tab-2" class="tab-pane">
                                    <h2>Η "Ιεροτελεστία" του Μεζέ</h2>
                                    <p>Στο παραδοσιακό βολιώτικο τσιπουράδικο υπάρχει ένας απαράβατος κανόνας: δεν παραγγέλνεις ποτέ φαγητό. Παραγγέλνεις μόνο το τσίπουρο και ο μεζές έρχεται "δώρο".</p>
                                </div>
                                <div id="cuis-tab-3" class="tab-pane">
                                    <h2>Το Κοινωνικό Σημείο Αναφοράς</h2>
                                    <p>Το τσιπουράδικο δεν είναι απλά ένα εστιατόριο, αλλά ένας τρόπος ζωής και επικοινωνίας. Από τα στενά της Νέας Ιωνίας μέχρι την παραλία, αποτελούν την "καρδιά" της πόλης.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div> 
        <aside class="right-page-sidebar">
            <h3><i class="fa-solid fa-compass" style="color:#728fa5; margin-right:8px;"></i> Δείτε Επίσης</h3>
            
            <!-- Link: Μύθος Αργοναυτών -->
            <a href="{{ url('/info/mythology') }}" class="side-card-link">
                <i class="fa-solid fa-sailboat"></i>
                <span>Ο Μύθος των Αργοναυτών</span>
            </a>

            <!-- Link: Γαστρονομία & Τσιπουράδικα -->
            <a href="{{ url('/info/cuisine') }}" class="side-card-link">
                <i class="fa-solid fa-martini-glass-citrus"></i>
                <span>Γαστρονομία & Τσιπουράδικα</span>
            </a>
            
            <a href="{{ url('/info/history') }}" class="side-card-link">
                <i class="fa-solid fa-landmark"></i>
                <span>Ιστορία της Πόλης</span>
            </a>
        </aside>

        
    </div>
<footer><p>© 2026 Discover City</p></footer>
    <script>
        function switchTab(event, tabId) {
            let panes = document.querySelectorAll('.tab-pane');
            panes.forEach(pane => pane.classList.remove('active'));

            let btns = document.querySelectorAll('.nav-btn');
            btns.forEach(btn => btn.classList.remove('active'));

            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }
    </script>

</body>
</html>