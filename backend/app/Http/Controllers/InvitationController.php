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
            // This ends the try catch block
        } catch (\Exception $e) {
            // This catches the error if the validation has failed
            Log::error('❌ Fout bij versturen uitnodiging: ' . $e->getMessage());
            // This returns a 500 error and JSON response message with the error message
            return response()->json([
                'message' => 'Fout bij versturen uitnodiging: ' . $e->getMessage()
            ], 500);
        }
    }

    // This function shows the invitation details based on the token
    public function show($token)
    {
        // This looks for the invitation by token
        $invitation = Invitation::where('token', $token)->first();
        // This if statement checks if the invitation exists
        if (!$invitation) {
            return response()->json(['message' => 'Uitnodiging niet gevonden'], 404);
        }
        // This shows if the invitation is valid or not
        if (!$invitation->isValid()) {
            return response()->json(['message' => 'Uitnodiging is verlopen of al gebruikt'], 400);
        }
        // Return the invitation details if the invitation is valid
        return response()->json([
            'name' => $invitation->name,
            'email' => $invitation->email,
            'role' => $invitation->role
        ]);
    }

    // This function accepts the invitation and creates a new user account
    public function accept(Request $request, $token)
    {
        // 
        $invitation = Invitation::where('token', $token)->first();
        // This displays an error message if the invitation does not exist or is invalid
        if (!$invitation || !$invitation->isValid()) {
            return response()->json(['message' => 'Ongeldige of verlopen uitnodiging'], 400);
        }

        // This validates the password input from the request
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        try {
            // This creates a new user account with the invitation details
            $user = User::create([
                'name' => $invitation->name,
                'email' => $invitation->email,
                'password' => Hash::make($validated['password']),
                'role' => $invitation->role,
                'email_verified_at' => now()
            ]);

            // $this marks the invitation as accepted
            $invitation->delete();

            // This returns a JSON response with a success message and the user details
            return response()->json([
                'message' => 'Account succesvol aangemaakt! Je kunt nu inloggen.',
                'user' => $user
            ], 201);
            
        } catch (\Exception $e) {
            // This catches any errors that occur during user creation
            return response()->json([
                'message' => 'Fout bij aanmaken account: ' . $e->getMessage()
            ], 500);
        }
    }

    // This function displays a list of all invitations that are active but not accepted
    public function index()
    {
        // This only shows invitations that are not accepted and have not expired
        $invitations = Invitation::where('accepted', false)
            ->where('expires_at', '>', now())
            // This sorts the invitations by creation date in descending order
            ->orderBy('created_at', 'desc')
            ->get();
            // This returns a JSON response with the list of invitations
        return response()->json($invitations);
    }

    // This function deletes a existing invitation
    public function destroy($id)
    {
        // This searches the invitation based on an ID
        $invitation = Invitation::findOrFail($id);
        // This deletes the invitation from the database if it exists
        $invitation->delete();
        // This returns a JSON response with a success message
        return response()->json(['message' => 'Uitnodiging ingetrokken']);
    }
}
