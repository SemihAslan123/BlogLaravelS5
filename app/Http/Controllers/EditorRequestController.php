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
        // vérifié si l'user à déjà une demande en cours
        $existingRequest = Auth::user()->editorRequest;
        if ($existingRequest && $existingRequest->status === 'pending') {
            return back()->with('error', 'Vous avez déjà une demande en attente.');
        }

        EditorRequest::create([
            'user_id' => Auth::id(),
            'status' => 'pending'
        ]);
        return back()->with('success', 'Votre demande a été envoyée à l\'administrateur.');

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
        // maj du statut de la demande
        $editorRequest->update(['status' => 'approved']);

        // Changer le rôle de l'utilisateur
        $user = $editorRequest->user;
        $user->update(['role' => 'editor']);

        return back()->with('success', 'Demande approuvée. L\'utilisateur est maintenant Rédacteur.');
    }

    /**
     * Rejeter une demande d'éditeur
     */
    public function reject(EditorRequest $editorRequest)
    {
        // rejette la demande et met le statut de la demande en 'rejected'
        $editorRequest->update(['status' => 'rejected']);
        return back()->with('success', 'Demande refusée.');

    }
}
