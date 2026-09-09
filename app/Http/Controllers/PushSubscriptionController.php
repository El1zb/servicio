<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Alta/baja de la suscripción push del navegador del usuario autenticado
 * (ver resources/js/push-notifications.js, cargado solo en el portal del
 * estudiante). El guardado/borrado real lo resuelve el trait
 * HasPushSubscriptions del paquete (ver App\Models\User).
 */
class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth'   => 'required|string',
        ]);

        $request->user()->updatePushSubscription(
            endpoint: $data['endpoint'],
            key: $data['keys']['p256dh'],
            token: $data['keys']['auth'],
        );

        return response()->noContent();
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
        ]);

        $request->user()->deletePushSubscription($data['endpoint']);

        return response()->noContent();
    }
}
