<?php

namespace App\Http\Controllers;

use App\Models\WebtvSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WebtvSubscriptionController extends Controller
{
    /**
     * Abonnement à la WebTV
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:webtv_subscribers,email',
            'name' => 'nullable|string|max:255',
        ], [
            'email.required' => 'L\'adresse email est requise',
            'email.email' => 'L\'adresse email n\'est pas valide',
            'email.unique' => 'Cette adresse email est déjà abonnée à la WebTV',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Créer l'abonné
            $subscriber = WebtvSubscriber::create([
                'email' => $request->email,
                'name' => $request->name,
                'verification_token' => WebtvSubscriber::generateVerificationToken(),
            ]);

            // Envoyer l'email de confirmation
            $this->sendConfirmationEmail($subscriber);

            return response()->json([
                'success' => true,
                'message' => 'Merci pour votre abonnement ! Un email de confirmation vous a été envoyé.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Vérification de l'email
     */
    public function verify($token)
    {
        $subscriber = WebtvSubscriber::where('verification_token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('homepage')->with('error', 'Token de vérification invalide.');
        }

        if ($subscriber->isVerified()) {
            return redirect()->route('homepage')->with('info', 'Votre email est déjà vérifié.');
        }

        $subscriber->markAsVerified();

        return redirect()->route('homepage')->with('success', 'Votre abonnement à la WebTV est confirmé ! Vous recevrez des notifications pour les prochaines diffusions.');
    }

    /**
     * Désabonnement
     */
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:webtv_subscribers,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $subscriber = WebtvSubscriber::where('email', $request->email)->first();
        
        if ($subscriber) {
            $subscriber->update(['is_active' => false]);
            
            return response()->json([
                'success' => true,
                'message' => 'Vous avez été désabonné avec succès.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email non trouvé.'
        ], 404);
    }

    /**
     * Envoie l'email de confirmation
     */
    private function sendConfirmationEmail($subscriber)
    {
        $verificationUrl = route('webtv.verify', ['token' => $subscriber->verification_token]);
        
        Mail::send('emails.webtv_subscription_confirmation', [
            'subscriber' => $subscriber,
            'verificationUrl' => $verificationUrl
        ], function ($message) use ($subscriber) {
            $message->to($subscriber->email)
                ->subject('Confirmez votre abonnement à la WebTV EVC');
        });
    }
}
