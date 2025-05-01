<?php
namespace App\Controller;

use App\Models\Partenaire;

class PartenaireController {
    public function index() {
        $partenaires = Partenaire::getAll();
        $types = ['Institutions', 'Associations', 'Entreprises'];
        
        include_once __DIR__ . '/../../public/view/partenaires.php';
    }
} 