@php
    // Contexte commun pour les partials du profil étudiant
    $sf = optional($student ?? null);
    $pr = optional($preReg ?? null);

    if (!isset($photoUrl)) {
        $studentPhoto = $sf->profile_photo;
        $prePhoto = $pr->profile_photo ?? $pr->photo ?? $pr->image ?? $pr->image_url ?? $pr->avatar;
        $rawPhoto = $studentPhoto ?: $prePhoto;
        $photoUrl = $rawPhoto ? (preg_match('/^https?:\/\//', $rawPhoto) ? $rawPhoto : asset($rawPhoto)) : asset('assets/img/avatar.png');
    }

    // Résoudre l'utilisateur courant sans générer d'avertissements si $user n'est pas passé à la vue
    $userObj = isset($user) ? $user : (auth()->check() ? auth()->user() : null);

    $fullName = $fullName ?? (function() use ($sf, $pr, $userObj) {
        $tmp = trim(($sf->first_name ?? '') . ' ' . ($sf->last_name ?? ''));
        if ($tmp === '') {
            $tmp = (($userObj->name ?? '') ?: trim(($pr->first_name ?? '') . ' ' . ($pr->last_name ?? '')));
        }
        return $tmp;
    })();

    $email = $email ?? (($sf->email ?? '') ?: ((($userObj->email ?? '') ?: ($pr->email ?? ''))));
    $gender = $gender ?? (($sf->gender ?? '') ?: ($pr->gender ?? ''));
    $phone = $phone ?? (($sf->phone ?? '') ?: ($pr->phone ?? ''));
    $whatsapp = $whatsapp ?? (($sf->whatsapp ?? '') ?: ($pr->whatsapp ?? ''));
    $quartier = $quartier ?? (($sf->quartier ?? '') ?: ($pr->quartier ?? ''));
    $city = $city ?? (($sf->city ?? '') ?: ($pr->city ?? ''));
    $country = $country ?? (($sf->country ?? '') ?: ($pr->country ?? ''));

    $program = $program ?? (($sf->program ?? '') ?: ($pr->program ?? ''));
    $level = $level ?? (($sf->level ?? '') ?: ($pr->level ?? ''));
    $domain = $domain ?? (($sf->specialization ?? '') ?: ($pr->specialization ?? ''));
    $status = $status ?? ($sf->status ?? '');

    $dob = $dob ?? ($sf->date_of_birth ?? null);
    $age = $age ?? ($dob ? optional($dob)->age : null);
    $experience = $experience ?? ($sf->years_experience ?? null);
    $sector = $sector ?? ($sf->industry_sector ?? '');

    $gpa = $gpa ?? ($sf->gpa ?? null);
    $credits = $credits ?? ($sf->credits_earned ?? null);
@endphp
