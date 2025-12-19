<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiSeoController extends Controller
{
    /**
     * Génère automatiquement les métadonnées SEO avec IA
     */
    public function generateSeo(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'excerpt' => 'required|string'
            ]);

            $title = $request->input('title');
            $excerpt = $request->input('excerpt');

            // Générer le contenu SEO avec IA
            $seoData = $this->generateWithAI($title, $excerpt);

            return response()->json([
                'success' => true,
                'message' => 'Contenu SEO généré avec succès',
                'data' => $seoData
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur génération SEO IA: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère le contenu SEO avec l'IA
     */
    private function generateWithAI($title, $excerpt)
    {
        // Option 1: Utiliser OpenAI API si disponible
        if (config('services.openai.api_key')) {
            return $this->generateWithOpenAI($title, $excerpt);
        }

        // Option 2: Génération intelligente sans API externe
        return $this->generateWithSmartAlgorithm($title, $excerpt);
    }

    /**
     * Génération avec OpenAI (si clé API disponible)
     */
    private function generateWithOpenAI($title, $excerpt)
    {
        try {
            $apiKey = config('services.openai.api_key');

            $prompt = "Génère des métadonnées SEO optimisées en français pour l'article suivant:\n\n";
            $prompt .= "Titre: {$title}\n";
            $prompt .= "Description: {$excerpt}\n\n";
            $prompt .= "Fournis le résultat au format JSON avec:\n";
            $prompt .= "- meta_title: titre SEO optimisé (50-60 caractères max)\n";
            $prompt .= "- meta_description: description SEO (150-160 caractères max)\n";
            $prompt .= "- keywords: 5-8 mots-clés pertinents séparés par des virgules\n\n";
            $prompt .= "Le contexte est l'École Virtuelle des Créatifs (EVC) à Abidjan, Côte d'Ivoire, spécialisée en design graphique, marketing digital et technologies créatives.";

            $ch = curl_init('https://api.openai.com/v1/chat/completions');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $apiKey
                ],
                CURLOPT_POSTFIELDS => json_encode([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Tu es un expert SEO spécialisé dans l\'optimisation de contenu pour les moteurs de recherche. Tu réponds uniquement en JSON valide.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500
                ])
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);
                $content = $data['choices'][0]['message']['content'] ?? '';

                // Extraire le JSON de la réponse
                preg_match('/\{[^}]+\}/', $content, $matches);
                if (!empty($matches[0])) {
                    $seoData = json_decode($matches[0], true);
                    if ($seoData) {
                        return $seoData;
                    }
                }
            }

            // Si OpenAI échoue, utiliser l'algorithme de secours
            return $this->generateWithSmartAlgorithm($title, $excerpt);

        } catch (\Exception $e) {
            Log::warning('OpenAI API error, using fallback: ' . $e->getMessage());
            return $this->generateWithSmartAlgorithm($title, $excerpt);
        }
    }

    /**
     * Génération intelligente "Expert SEO" sans API externe
     */
    private function generateWithSmartAlgorithm($title, $excerpt)
    {
        // 1. Analyse et Nettoyage
        $cleanTitle = $this->cleanText($title);
        $cleanExcerpt = $this->cleanText($excerpt);

        // 2. Extraction du mot-clé principal (Focus Keyword)
        $focusKeyword = $this->extractFocusKeyword($cleanTitle);

        // 3. Génération Meta Title (Technique "Front-Loading")
        // Structure : [Mot-clé Principal] : [Promesse/Bénéfice] [Séparateur] [Marque]
        $metaTitle = $this->generateExpertMetaTitle($cleanTitle, $focusKeyword);

        // 4. Génération Meta Description (Technique "AIDA")
        // Structure : [Accroche/Question] + [Solution/Bénéfice] + [Preuve/Autorité] + [Call to Action]
        $metaDescription = $this->generateExpertMetaDescription($cleanTitle, $cleanExcerpt, $focusKeyword);

        // 5. Extraction de Mots-clés (Semantic & Long-tail)
        $keywords = $this->generateExpertKeywords($cleanTitle, $cleanExcerpt);

        return [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'keywords' => $keywords
        ];
    }

    private function cleanText($text)
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags($text)));
    }

    private function extractFocusKeyword($text)
    {
        // Enlève les stop words et prend les 2-3 premiers mots significatifs
        $words = $this->extractKeywords($text);
        return implode(' ', array_slice($words, 0, 3));
    }

    private function generateExpertMetaTitle($title, $focusKeyword)
    {
        $title = $this->cleanText($title);

        // Templates orientés bénéfice + différenciation
        $year = date('Y');
        $templates = [
            'Maîtrisez %s : stratégie & outils (%s)',
            '%s : programme intensif & résultats (%s)',
            '%s : méthodes concrètes + cas pratiques (%s)',
            'Devenez expert en %s : boostez votre profil (%s)',
            '%s : formation pratique à Abidjan (%s)'
        ];

        $subject = $focusKeyword ?: $title;
        $subject = mb_substr($subject, 0, 70);
        $template = $templates[array_rand($templates)];
        $core = sprintf($template, $subject, $year);

        // Variantes de marque (on ne force pas si ça casse la longueur)
        $brandSuffixes = [' | EVC Abidjan', ' - EVC Abidjan', ' | École EVC Abidjan'];
        $suffix = $brandSuffixes[array_rand($brandSuffixes)];

        // Déjà présent ?
        if (stripos($core, 'EVC') !== false || stripos($core, 'Abidjan') !== false) {
            $suffix = '';
        }

        // Ajuster la longueur sans "..." (on coupe sur frontière de mots)
        $max = 60;
        $metaTitle = $core . $suffix;
        if (mb_strlen($metaTitle) > $max) {
            // Essayer sans suffixe
            $metaTitle = $core;
        }
        if (mb_strlen($metaTitle) > $max) {
            $metaTitle = $this->truncateOnWord($metaTitle, $max);
        }

        return $metaTitle;
    }

    private function generateExpertMetaDescription($title, $excerpt, $focusKeyword)
    {
        // CTA (Call to Action) forts
        $ctas = [
            'Inscrivez-vous dès maintenant !',
            'Découvrez le programme complet.',
            'Boostez votre carrière aujourd\'hui.',
            'Places limitées, rejoignez-nous !',
            'Devenez un expert certifié.'
        ];
        $cta = $ctas[array_rand($ctas)];

        $cleanTitle = $this->cleanText($title);
        $cleanExcerpt = $this->cleanText($excerpt);

        // Accroches variées (évite les répétitions type "Découvrez")
        $kw = $focusKeyword ?: $cleanTitle;
        $hooks = [
            "Objectif : réussir {$kw} avec une méthode claire.",
            "Envie de passer un cap sur {$kw} ?",
            "Le plan d'action pour {$kw} (concret, actionnable).",
            "Apprenez {$kw} avec une approche orientée résultats.",
            "Un format pratique pour maîtriser {$kw} rapidement."
        ];
        $hook = $hooks[array_rand($hooks)];

        // Bénéfice depuis l'extrait (phrase courte, sans copier-coller brut)
        $benefit = mb_substr($cleanExcerpt, 0, 120);
        $benefit = preg_replace('/\s*\b(d\'écouvrez|découvrez|tout savoir|en savoir plus)\b\s*/iu', ' ', $benefit);
        $benefit = trim($benefit);
        if ($benefit !== '' && mb_substr($benefit, -1) !== '.') {
            $benefit .= '.';
        }

        // Autorité courte + CTA
        $authority = 'EVC Abidjan.';
        $parts = array_filter([$hook, $benefit, $authority, $cta]);
        $finalDesc = implode(' ', $parts);

        // Ajuster à 160 sans "..." (on coupe sur mots)
        $max = 160;
        if (mb_strlen($finalDesc) > $max) {
            // 1) enlever l'autorité si besoin
            $parts = array_filter([$hook, $benefit, $cta]);
            $finalDesc = implode(' ', $parts);
        }
        if (mb_strlen($finalDesc) > $max) {
            // 2) réduire bénéfice
            $benefitMax = 160 - (mb_strlen($hook) + 1 + mb_strlen($cta) + 1);
            $benefitMax = max(0, $benefitMax);
            $benefitShort = $benefitMax > 0 ? $this->truncateOnWord($benefit, $benefitMax) : '';
            $parts = array_filter([$hook, $benefitShort, $cta]);
            $finalDesc = implode(' ', $parts);
        }
        if (mb_strlen($finalDesc) > $max) {
            $finalDesc = $this->truncateOnWord($finalDesc, $max);
        }

        return $finalDesc;
    }

    private function truncateOnWord($text, $maxLen)
    {
        $text = trim($text);
        if (mb_strlen($text) <= $maxLen) {
            return $text;
        }

        $cut = mb_substr($text, 0, $maxLen);
        $cut = preg_replace('/\s+\S*$/u', '', $cut);
        $cut = trim($cut);

        // Si on a tout supprimé (mot trop long), fallback brute
        if ($cut === '') {
            return trim(mb_substr($text, 0, $maxLen));
        }

        // éviter fin avec séparateurs
        $cut = rtrim($cut, "-|:|,|");
        return trim($cut);
    }

    private function generateHook($title)
    {
        $hooks = [
            "Vous cherchez à maîtriser {$title} ?",
            "Découvrez tout sur {$title}.",
            "Formation exclusive : {$title}.",
            "Devenez expert en {$title}.",
            "Tout savoir sur {$title} :"
        ];
        return $hooks[array_rand($hooks)];
    }

    private function generateExpertKeywords($title, $excerpt)
    {
        // Mots-clés de base
        $baseKeywords = $this->extractKeywords($title . ' ' . $excerpt);

        // Mots-clés de contexte (LSI)
        $lsiKeywords = ['formation', 'cours', 'certificat', 'abidjan', 'côte d\'ivoire', 'école', 'digital'];

        // Fusion et pondération
        $allKeywords = array_unique(array_merge(array_slice($baseKeywords, 0, 5), $lsiKeywords));

        return implode(', ', array_slice($allKeywords, 0, 8));
    }

    /**
     * Extrait les mots-clés importants d'un texte
     */
    private function extractKeywords($text)
    {
        // Mots à ignorer (stop words en français)
        $stopWords = [
            'le', 'la', 'les', 'un', 'une', 'des', 'de', 'du', 'à', 'au',
            'et', 'ou', 'mais', 'pour', 'dans', 'sur', 'avec', 'sans',
            'est', 'sont', 'a', 'ont', 'ce', 'cette', 'ces', 'son', 'sa',
            'ses', 'mon', 'ma', 'mes', 'ton', 'ta', 'tes', 'notre', 'votre',
            'leur', 'qui', 'que', 'quoi', 'dont', 'où', 'par', 'en',
            'plus', 'très', 'bien', 'tout', 'tous', 'ils', 'elles', 'nous', 'vous'
        ];

        // Nettoyer et tokenizer
        $text = mb_strtolower($text, 'UTF-8'); // Utiliser mb_strtolower pour les accents
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $words = preg_split('/\s+/', $text);

        // Filtrer
        $keywords = array_filter($words, function($word) use ($stopWords) {
            return mb_strlen($word) > 2 && !in_array($word, $stopWords); // > 2 lettres
        });

        return array_values($keywords);
    }

    // Méthodes generateMetaTitle et generateMetaDescription originales supprimées car remplacées

}
