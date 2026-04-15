<?php

use Illuminate\Support\Facades\Broadcast;

/*

|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// Canal par défaut pour l'utilisateur authentifié
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * Canal pour les statistiques d'énergie et la température.
 * On vérifie que l'utilisateur est bien celui à qui appartient le dashboard.
 */
Broadcast::channel('user.{id}.energy', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * Canal pour les alertes de sécurité.
 * Tu peux restreindre l'accès ou l'ouvrir à tous les membres de la maison.
 */
Broadcast::channel('user.{id}.security', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
