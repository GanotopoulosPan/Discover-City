<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VolosInfoController extends Controller
{
    public function show($topic)
    {
        $title = ''; 

        // Ανάλογα με το $topic που λαμβάνουμε από το URL, ορίζουμε τον τίτλο που θα εμφανίζεται στο blade
        switch ($topic) {
            case 'history':
                $title = 'Η Ιστορία του Βόλου';
                break;
            case 'mythology':
                $title = 'Ο Μύθος των Αργοναυτών';
                break;
            case 'cuisine':
                $title = 'Γαστρονομία & Τσιπουράδικα';
                break;
            default:
                abort(404);
        }

        // Στέλνουμε τον τίτλο και τον τύπο στο blade για να εμφανιστούν σωστά
        return view('volos-info', [
            'title' => $title,
            'type'  => $topic
        ]);
    }
}