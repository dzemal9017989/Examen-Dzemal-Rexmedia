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
    // This function handles the creation and sending of user invitations
    public function store(Request $request)
    {
        // This begins with a message in the log file
        Log::info('Start uitnodiging verwerken', $request->all());

        // This validates things such as name, email and role
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:invitations,email',
            'role' => 'required|in:user,admin'
        ]);

        // This try catch block looks if the validation has succeeded or failed
        try {
            // Validation has succeeded
            Log::info('Validatie gelukt');

            // This makes a invitation in the database with validated data
            $invitation = Invitation::create($validated);

            // This sends a message from the admin who want to sends an invitation message to the user
            Log::info('📧 Versturen mail naar ' . $invitation->email);

            // This sends the invitation email form the bsackend to the user
            Mail::to($invitation->email)->send(new UserInvitation($invitation));

            // This sends an aproved message that the email is sent
            Log::info('📬 Mail verstuurd');

            // This returns a JSON response message with the email address of the user that is invited
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
