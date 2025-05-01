<?php
namespace App\Controller;

use App\Models\Bien;
use App\Models\BienImage;

class BienController
{
    /** Liste des biens */
    public function index(): void
    {
        // Récupération BDD
        $biens = Bien::getAll();
        $types = ['vente', 'location', 'location_etudiante'];

        include_once __DIR__ . '/../../public/view/biens.php';
    }

    /** Détail d'un bien */
    public function show(int $id): void
    {
        $bien = Bien::getById($id);
        if (!$bien) {
            header('Location: /index.php/biens');
            exit;
        }

        $images = BienImage::listByBien($id);

        include_once __DIR__ . '/../../public/view/bien-detail.php';
    }
}
