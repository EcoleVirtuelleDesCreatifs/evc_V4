@extends('layouts.app')

@section('title', 'Faire un don - EVC')
@section('description', 'Soutenez l\'École Virtuelle des Créatifs (EVC) en effectuant un don. Formulaire sécurisé et réponse rapide de notre équipe.')

@section('content')
<section class="relative pt-[500px] pb-16 sm:pt-[500px] sm:pb-20">
    <div class="absolute inset-0 opacity-30">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0a1128] via-[#001f54] to-[#034078]"></div>
    </div>

    <div class="relative mx-auto max-w-5xl px-6">
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm text-white/90">
                <i class="fas fa-hand-holding-heart"></i>
                Soutenir l'EVC
            </div>
            <h1 class="mt-4 text-3xl sm:text-4xl font-extrabold text-white">Faire un don</h1>
            <p class="mt-3 text-white/70 max-w-2xl mx-auto">
                Votre soutien contribue à renforcer nos programmes, nos initiatives pédagogiques et l'accompagnement de nos apprenants.
                Remplissez ce formulaire et notre équipe vous enverra les modalités (Mobile Money, virement, etc.).
            </p>
        </div>

        @if(session('success'))
            <div class="mb-8 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-6 py-4 text-emerald-200">
                <div class="flex items-start gap-3">
                    <i class="fas fa-check-circle mt-1"></i>
                    <div>
                        <div class="font-semibold">{{ session('success') }}</div>
                        <div class="text-sm text-emerald-200/80 mt-1">Si vous ne recevez pas d'email, vérifiez vos spams ou contactez-nous sur WhatsApp.</div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 rounded-2xl border border-red-400/30 bg-red-500/10 px-6 py-4 text-red-200">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle mt-1"></i>
                    <div class="font-semibold">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 rounded-2xl border border-red-400/30 bg-red-500/10 px-6 py-4 text-red-200">
                <div class="font-semibold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Veuillez corriger les erreurs suivantes :</div>
                <ul class="list-disc list-inside text-sm text-red-200/80 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-6 sm:p-8">
                <form method="POST" action="{{ route('donation.submit', [], false) }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-white/90 mb-2">Nom complet <span class="text-red-400">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-[#4fc3f7]" placeholder="Ex: KOUAKOU Kévin N'Guessan">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-white/90 mb-2">Email <span class="text-red-400">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-[#4fc3f7]" placeholder="vous@email.com">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-white/90 mb-2">Téléphone (WhatsApp)</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-[#4fc3f7]" placeholder="+225 07 xx xx xx xx">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-white/90 mb-2">Montant (optionnel)</label>
                            <input type="number" step="0.01" min="1" name="amount" value="{{ old('amount') }}" class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-[#4fc3f7]" placeholder="5000">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-white/90 mb-2">Devise</label>
                            <select name="currency" class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#4fc3f7]">
                                <option value="XOF" @selected(old('currency', 'XOF')==='XOF')>XOF</option>
                                <option value="EUR" @selected(old('currency')==='EUR')>EUR</option>
                                <option value="USD" @selected(old('currency')==='USD')>USD</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white/90 mb-2">Moyen de paiement souhaité (optionnel)</label>
                        <select name="payment_method" class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#4fc3f7]">
                            <option value="" @selected(old('payment_method')==='')>Choisir</option>
                            <option value="Orange Money" @selected(old('payment_method')==='Orange Money')>Orange Money</option>
                            <option value="MTN Mobile Money" @selected(old('payment_method')==='MTN Mobile Money')>MTN Mobile Money</option>
                            <option value="Moov Money" @selected(old('payment_method')==='Moov Money')>Moov Money</option>
                            <option value="Wave" @selected(old('payment_method')==='Wave')>Wave</option>
                            <option value="Virement bancaire" @selected(old('payment_method')==='Virement bancaire')>Virement bancaire</option>
                            <option value="Autre" @selected(old('payment_method')==='Autre')>Autre</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white/90 mb-2">Message (optionnel)</label>
                        <textarea name="message" rows="5" class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-[#4fc3f7]" placeholder="Expliquez votre intention (anonyme, projet à soutenir, etc.)">{{ old('message') }}</textarea>
                    </div>

                    <label class="flex items-start gap-3 text-sm text-white/80">
                        <input type="checkbox" name="consent" value="1" class="mt-1 h-5 w-5 rounded border-white/20 bg-black/40 text-[#4fc3f7]" {{ old('consent') ? 'checked' : '' }} required>
                        <span>
                            J'accepte d'être contacté(e) par l'équipe EVC au sujet des modalités de don.
                            <span class="text-red-400">*</span>
                        </span>
                    </label>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-3 font-bold text-white shadow-lg shadow-emerald-500/20 hover:from-emerald-600 hover:to-teal-700 transform hover:scale-[1.01] transition-all">
                        <i class="fas fa-paper-plane"></i>
                        Envoyer ma demande
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-6">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-shield-heart text-emerald-300"></i>
                        Transparence & engagement
                    </h2>
                    <p class="mt-3 text-sm text-white/70 leading-relaxed">
                        Les dons sont utilisés pour soutenir nos actions pédagogiques, renforcer nos outils, et accompagner davantage d'apprenants.
                        Notre équipe peut vous proposer un reçu/attestation selon le mode de versement.
                    </p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl p-6">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fab fa-whatsapp text-green-400"></i>
                        Contact direct
                    </h3>
                    <p class="mt-3 text-sm text-white/70">
                        Vous préférez un échange immédiat ?
                    </p>
                    <a href="https://wa.me/2250747259507?text={{ urlencode('Bonjour EVC, je souhaite faire un don. Pouvez-vous me donner les modalités ?') }}" target="_blank" rel="noopener" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-full border border-white/15 bg-black/30 px-6 py-3 text-sm font-semibold text-white hover:border-emerald-400/40 hover:bg-emerald-500/10 transition-all">
                        <i class="fab fa-whatsapp"></i>
                        Ouvrir WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
