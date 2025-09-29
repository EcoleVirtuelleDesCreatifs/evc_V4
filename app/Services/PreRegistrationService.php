<?php

namespace App\Services;

use App\Mail\AdminPreRegistrationNotification;
use App\Mail\PreRegistrationSubmitted;
use App\Models\PreRegistration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PreRegistrationService
{
    /**
     * Traite une candidature: normalisation, création, envoi d'e-mails.
     * @param array $input Données validées (StoreCandidatureRequest)
     * @return array [PreRegistration $pre, array $warnings]
     */
    public function handle(array $input): array
    {
        // Découpage ville / pays
        $ville = null; $pays = null;
        if (!empty($input['ville_pays'])) {
            $vp = trim($input['ville_pays']);
            if (str_contains($vp, '/')) {
                [$ville, $pays] = array_map('trim', explode('/', $vp, 2));
            } elseif (str_contains($vp, ',')) {
                [$ville, $pays] = array_map('trim', explode(',', $vp, 2));
            } else {
                $ville = $vp;
            }
        }
        if (empty($pays)) {
            $pays = !empty($input['nationalite']) ? $input['nationalite'] : 'Côte d’Ivoire';
        }

        // Mappings depuis config
        $programmeMap = config('evc.programme_map', []);
        $niveauFormationMap = config('evc.niveau_formation_map', []);
        $origineMap = config('evc.origine_map', []);
        $choixFormationMap = config('evc.choix_formation_map', []);

        $programme = $programmeMap[$input['programme']] ?? 'infographie';
        $choixFormation = $choixFormationMap[$programme] ?? 'design_graphique';
        $niveauDansFormation = $niveauFormationMap[$input['niveau_formation']] ?? 'aucune_notion';
        $howKnown = $origineMap[$input['origine']] ?? 'autre';

        // Construire payload
        $data = [
            'nom' => $input['nom_complet'],
            'prenom' => $input['prenom'],
            'age' => $input['age'],
            'date_naissance' => $input['date_naissance'],
            'sexe' => $input['sexe'],
            'nationalite' => $input['nationalite'],
            'email' => $input['email'],
            'whatsapp' => $input['whatsapp'],
            'ville' => $ville,
            'pays' => $pays,
            'niveau_etude' => $input['niveau_etude'],
            'domaine_etude' => $input['domaine_etude'],
            'competences' => $input['competences'],
            'programme' => $programme,
            'choix_formation' => $choixFormation,
            'niveau_dans_formation' => $niveauDansFormation,
            'how_known' => $howKnown,
            'has_computer' => $input['ordinateur'] === 'Oui',
            'has_smartphone' => $input['smartphone'] === 'Oui',
            'disponibilite' => $input['disponibilite'],
            'motivation' => $input['motivation'],
            'certify' => (bool) ($input['veracite'] ?? false),
            'consent' => (bool) ($input['consentement'] ?? false),
        ];

        // Upload photo si présent
        if (request()->hasFile('photo_profil')) {
            $data['photo'] = request()->file('photo_profil')->store('photos_preregistrations', 'public');
        }

        $pre = PreRegistration::create($data);

        // Envoi e-mails synchrone
        $warnings = [];
        try {
            Mail::to($pre->email)
                ->send((new PreRegistrationSubmitted($pre))
                    ->replyTo(config('mail.from.address'), config('mail.from.name')));
            Log::info('Mail candidat envoyé', ['to' => $pre->email, 'pre_id' => $pre->id]);
        } catch (\Throwable $e) {
            Log::error('Envoi mail candidat échec', ['error' => $e->getMessage()]);
            $warnings[] = "L'e-mail de confirmation n'a pas pu être envoyé au candidat.";
        }

        try {
            $adminEmail = config('mail.admin_address') ?? config('mail.from.address');
            if ($adminEmail) {
                Mail::to($adminEmail)
                    ->send(new AdminPreRegistrationNotification($pre));
                Log::info('Mail admin envoyé', ['to' => $adminEmail, 'pre_id' => $pre->id]);
            } else {
                $warnings[] = "L'adresse e-mail administrateur n'est pas configurée (MAIL_ADMIN_ADDRESS).";
            }
        } catch (\Throwable $e) {
            Log::error('Envoi mail admin échec', ['error' => $e->getMessage()]);
            $warnings[] = "La notification administrateur n'a pas pu être envoyée.";
        }

        return [$pre, $warnings];
    }
}
