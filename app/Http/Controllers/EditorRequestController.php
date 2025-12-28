<?php

namespace App\Http\Controllers;

use App\Models\EditorRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditorRequestController extends Controller
{
    /**
     * Créer une demande pour devenir éditeur (user simple uniquement)
     */
    public function store(Request $request)
    {

    }

    /**
     * Liste des demandes d'éditeur
     */
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Approuver une demande d'éditeur
     */
    public function approve(EditorRequest $editorRequest)
    {
        // Vérifier que la demande est en attente

        // Changer le rôle de l'utilisateur

        // Mettre à jour le statut de la demande
    }

    /**
     * Rejeter une demande d'éditeur
     */
    public function reject(EditorRequest $editorRequest)
    {
        
    }
}
