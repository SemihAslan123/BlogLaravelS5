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
        $user = Auth::user();

        // Vérifier que l'utilisateur est bien un user simple
        if ($user->role !== 'user') {
            return back()->with('error', 'Vous êtes déjà éditeur.');
        }

        // Vérifier qu'il n'a pas déjà une demande en cours
        if ($user->editorRequest) {
            return back()->with('error', 'Vous avez déjà une demande en attente.');
        }

        // Créer la demande
        EditorRequest::create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Votre demande pour devenir éditeur a été envoyée avec succès !');
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
        if ($editorRequest->status !== 'pending') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        // Changer le rôle de l'utilisateur
        $user = $editorRequest->user;
        $user->role = 'editor';
        $user->save();

        // Mettre à jour le statut de la demande
        $editorRequest->status = 'approved';
        $editorRequest->processed_at = now();
        $editorRequest->save();

        return back()->with('success', "{$user->name} est maintenant éditeur !");
    }

    /**
     * Rejeter une demande d'éditeur
     */
    public function reject(EditorRequest $editorRequest)
    {
        // Vérifier que la demande est en attente
        if ($editorRequest->status !== 'pending') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $userName = $editorRequest->user->name;

        // Mettre à jour le statut de la demande
        $editorRequest->status = 'rejected';
        $editorRequest->processed_at = now();
        $editorRequest->save();

        return back()->with('success', "La demande de {$userName} a été rejetée.");
    }
}
