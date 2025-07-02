<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use App\Mail\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class InvitationController extends Controller
{
    public function store(Request $request)
    {
        Log::info('⏳ Start uitnodiging verwerken', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:invitations,email',
            'role' => 'required|in:user,admin'
        ]);

        try {
            Log::info('✅ Validatie gelukt');

            $invitation = Invitation::create($validated);

            Log::info('📧 Versturen mail naar ' . $invitation->email);

            // Verstuur de uitnodigingsmail
            Mail::to($invitation->email)->send(new UserInvitation($invitation));

            Log::info('📬 Mail verstuurd');

            return response()->json([
                'message' => 'Uitnodiging verzonden naar ' . $invitation->email,
                'invitation' => $invitation
            ], 201);
        } catch (\Exception $e) {
            Log::error('❌ Fout bij versturen uitnodiging: ' . $e->getMessage());
            return response()->json([
                'message' => 'Fout bij versturen uitnodiging: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($token)
    {
        $invitation = Invitation::where('token', $token)->first();
        if (!$invitation) {
            return response()->json(['message' => 'Uitnodiging niet gevonden'], 404);
        }
        if (!$invitation->isValid()) {
            return response()->json(['message' => 'Uitnodiging is verlopen of al gebruikt'], 400);
        }
        return response()->json([
            'name' => $invitation->name,
            'email' => $invitation->email,
            'role' => $invitation->role
        ]);
    }

    public function accept(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->first();
        if (!$invitation || !$invitation->isValid()) {
            return response()->json(['message' => 'Ongeldige of verlopen uitnodiging'], 400);
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        try {
            // Maak gebruiker aan
            $user = User::create([
                'name' => $invitation->name,
                'email' => $invitation->email,
                'password' => Hash::make($validated['password']),
                'role' => $invitation->role,
                'email_verified_at' => now()
            ]);

            // Markeer uitnodiging als geaccepteerd
            $invitation->update(['accepted' => true]);

            return response()->json([
                'message' => 'Account succesvol aangemaakt! Je kunt nu inloggen.',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Fout bij aanmaken account: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $invitations = Invitation::where('accepted', false)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($invitations);
    }

    public function destroy($id)
    {
        $invitation = Invitation::findOrFail($id);
        $invitation->delete();
        return response()->json(['message' => 'Uitnodiging ingetrokken']);
    }
}
