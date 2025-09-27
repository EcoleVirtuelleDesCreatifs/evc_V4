/**
 * Profile Edit JavaScript
 * Gestion des fonctionnalités interactives du formulaire de profil
 */

// Configuration globale
const ProfileConfig = {
    maxLength: 1000,
    summernoteHeight: 300,
    countries: [] // Sera chargé dynamiquement
};

/**
 * Initialisation principale
 */
$(document).ready(function() {
    console.log('Initialisation du profil...');
    
    // Initialiser les composants
    initCountryList();
    initSummernoteEditors();
    initPhotoPreview();
    initFormValidation();
    
    console.log('Profil initialisé avec succès');
});

/**
 * Initialiser la liste des pays
 */
function initCountryList() {
    const countrySelect = $('#country');
    if (countrySelect.length === 0) return;
    
    console.log('Chargement de la liste des pays...');
    
    // Liste des pays (version simplifiée pour la démo)
    const countries = [
        'Afghanistan', 'Afrique du Sud', 'Albanie', 'Algérie', 'Allemagne', 'Andorre', 'Angola',
        'Antigua-et-Barbuda', 'Arabie saoudite', 'Argentine', 'Arménie', 'Australie', 'Autriche',
        'Azerbaïdjan', 'Bahamas', 'Bahreïn', 'Bangladesh', 'Barbade', 'Belgique', 'Belize',
        'Bénin', 'Bhoutan', 'Biélorussie', 'Birmanie', 'Bolivie', 'Bosnie-Herzégovine', 'Botswana',
        'Brésil', 'Brunei', 'Bulgarie', 'Burkina Faso', 'Burundi', 'Cambodge', 'Cameroun',
        'Canada', 'Cap-Vert', 'Centrafrique', 'Chili', 'Chine', 'Chypre', 'Colombie', 'Comores',
        'Congo', 'Congo démocratique', 'Corée du Nord', 'Corée du Sud', 'Costa Rica', 'Côte d\'Ivoire',
        'Croatie', 'Cuba', 'Danemark', 'Djibouti', 'Dominique', 'Égypte', 'Émirats arabes unis',
        'Équateur', 'Érythrée', 'Espagne', 'Estonie', 'États-Unis', 'Éthiopie', 'Fidji', 'Finlande',
        'France', 'Gabon', 'Gambie', 'Géorgie', 'Ghana', 'Grèce', 'Grenade', 'Guatemala', 'Guinée',
        'Guinée-Bissau', 'Guinée équatoriale', 'Guyana', 'Haïti', 'Honduras', 'Hongrie', 'Îles Cook',
        'Îles Marshall', 'Inde', 'Indonésie', 'Irak', 'Iran', 'Irlande', 'Islande', 'Israël',
        'Italie', 'Jamaïque', 'Japon', 'Jordanie', 'Kazakhstan', 'Kenya', 'Kirghizistan', 'Kiribati',
        'Koweït', 'Laos', 'Lesotho', 'Lettonie', 'Liban', 'Liberia', 'Libye', 'Liechtenstein',
        'Lituanie', 'Luxembourg', 'Macédoine du Nord', 'Madagascar', 'Malaisie', 'Malawi', 'Maldives',
        'Mali', 'Malte', 'Maroc', 'Maurice', 'Mauritanie', 'Mexique', 'Micronésie', 'Moldavie',
        'Monaco', 'Mongolie', 'Monténégro', 'Mozambique', 'Namibie', 'Nauru', 'Népal', 'Nicaragua',
        'Niger', 'Nigeria', 'Niue', 'Norvège', 'Nouvelle-Zélande', 'Oman', 'Ouganda', 'Ouzbékistan',
        'Pakistan', 'Palaos', 'Palestine', 'Panama', 'Papouasie-Nouvelle-Guinée', 'Paraguay', 'Pays-Bas',
        'Pérou', 'Philippines', 'Pologne', 'Portugal', 'Qatar', 'République dominicaine', 'République tchèque',
        'Roumanie', 'Royaume-Uni', 'Russie', 'Rwanda', 'Saint-Kitts-et-Nevis', 'Saint-Marin',
        'Saint-Vincent-et-les-Grenadines', 'Sainte-Lucie', 'Salomon', 'Salvador', 'Samoa', 'São Tomé-et-Principe',
        'Sénégal', 'Serbie', 'Seychelles', 'Sierra Leone', 'Singapour', 'Slovaquie', 'Slovénie',
        'Somalie', 'Soudan', 'Soudan du Sud', 'Sri Lanka', 'Suède', 'Suisse', 'Suriname', 'Swaziland',
        'Syrie', 'Tadjikistan', 'Tanzanie', 'Tchad', 'Thaïlande', 'Timor oriental', 'Togo', 'Tonga',
        'Trinité-et-Tobago', 'Tunisie', 'Turkménistan', 'Turquie', 'Tuvalu', 'Ukraine', 'Uruguay',
        'Vanuatu', 'Vatican', 'Venezuela', 'Viêt Nam', 'Yémen', 'Zambie', 'Zimbabwe'
    ];
    
    // Vider et remplir la liste
    countrySelect.empty().append('<option value="">Sélectionnez votre pays</option>');
    
    countries.forEach(country => {
        countrySelect.append(`<option value="${country}">${country}</option>`);
    });
    
    console.log(`${countries.length} pays chargés`);
}

/**
 * Initialiser les éditeurs Summernote
 */
function initSummernoteEditors() {
    // Vérifier que jQuery et Summernote sont chargés
    if (typeof $ === 'undefined') {
        console.error('jQuery n\'est pas chargé');
        return;
    }
    
    if (typeof $.fn.summernote === 'undefined') {
        console.error('Summernote n\'est pas chargé');
        return;
    }
    
    console.log('Initialisation de Summernote...');
    
    // Configuration Summernote pour les éditeurs de texte
    $('#biography, #expectations').summernote({
        height: ProfileConfig.summernoteHeight,
        lang: 'fr-FR',
        placeholder: function() {
            if (this.id === 'biography') {
                return 'Parlez-nous de vous, votre parcours, vos passions...';
            } else {
                return 'Quels sont vos objectifs ? Que souhaitez-vous apprendre ?';
            }
        },
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['forecolor', 'backcolor']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
            ['misc', ['undo', 'redo']]
        ],
        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana'],
        fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '36', '48'],
        colors: [
            ['#003366', '#3399ff', '#ff6633', '#FF9900'], // Couleurs EVC
            ['#000000', '#424242', '#636363', '#9C9C94', '#CEC6CE', '#EFEFEF', '#F7F3F7', '#FFFFFF'],
            ['#FF0000', '#FF9C00', '#FFFF00', '#00FF00', '#00FFFF', '#0000FF', '#9C00FF', '#FF00FF'],
            ['#F7C6CE', '#FFE7CE', '#FFEFC6', '#D6EFD6', '#CEDEE7', '#CEE7F7', '#D6D6E7', '#E7D6DE']
        ],
        styleTags: [
            'p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
        ],
        callbacks: {
            onInit: function() {
                console.log('Summernote initialisé pour #' + this.id);
            },
            onKeyup: function(e) {
                handleCharacterLimit(this);
            },
            onPaste: function(e) {
                const self = this;
                setTimeout(function() {
                    handleCharacterLimit(self);
                }, 10);
            }
        }
    });
    
    // Ajouter des compteurs de caractères
    addCharacterCounters();
    
    console.log('Configuration Summernote terminée');
}

/**
 * Gérer la limitation de caractères
 */
function handleCharacterLimit(editor) {
    const maxLength = ProfileConfig.maxLength;
    const currentLength = $(editor).summernote('code').replace(/<[^>]*>/g, '').length;
    
    if (currentLength > maxLength) {
        const content = $(editor).summernote('code');
        const textContent = content.replace(/<[^>]*>/g, '');
        const truncatedText = textContent.substring(0, maxLength);
        
        // Reconstituer le HTML avec le texte tronqué
        $(editor).summernote('code', '<p>' + truncatedText + '</p>');
        
        // Afficher un message d'alerte
        showCharacterLimitWarning(maxLength);
    }
    
    // Mettre à jour le compteur de caractères
    updateCharacterCounter(editor.id, currentLength, maxLength);
}

/**
 * Afficher l'avertissement de limite de caractères
 */
function showCharacterLimitWarning(maxLength) {
    if ($('#char-limit-warning').length) return;
    
    const warning = $(`
        <div id="char-limit-warning" class="alert alert-warning mt-2">
            <i class="fas fa-exclamation-triangle"></i> 
            Limite de ${maxLength} caractères atteinte !
        </div>
    `);
    
    $('.note-editor').first().after(warning);
    
    setTimeout(function() {
        warning.fadeOut(function() {
            $(this).remove();
        });
    }, 3000);
}

/**
 * Ajouter les compteurs de caractères
 */
function addCharacterCounters() {
    $('#biography').after('<div id="biography-char-count" class="char-count">0/1000 caractères</div>');
    $('#expectations').after('<div id="expectations-char-count" class="char-count">0/1000 caractères</div>');
    
    // Initialiser les compteurs
    $('#biography, #expectations').each(function() {
        const charCount = $(this).summernote('code').replace(/<[^>]*>/g, '').length;
        updateCharacterCounter(this.id, charCount, ProfileConfig.maxLength);
    });
}

/**
 * Mettre à jour le compteur de caractères
 */
function updateCharacterCounter(editorId, currentLength, maxLength) {
    const counterId = editorId + '-char-count';
    const counter = $('#' + counterId);
    
    if (counter.length) {
        counter.text(currentLength + '/' + maxLength + ' caractères');
        
        // Changer la couleur selon le pourcentage
        counter.removeClass('warning danger');
        if (currentLength > maxLength * 0.9) {
            counter.addClass('danger');
        } else if (currentLength > maxLength * 0.8) {
            counter.addClass('warning');
        }
    }
}

/**
 * Initialiser la prévisualisation de photo
 */
function initPhotoPreview() {
    const photoInput = $('#profile_photo');
    const photoPreview = $('#photo-preview');
    
    if (photoInput.length === 0 || photoPreview.length === 0) return;
    
    photoInput.on('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Vérifier le type de fichier
            if (!file.type.match('image.*')) {
                alert('Veuillez sélectionner un fichier image valide.');
                return;
            }
            
            // Vérifier la taille (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('La taille de l\'image ne doit pas dépasser 5MB.');
                return;
            }
            
            // Créer la prévisualisation
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });
}

/**
 * Initialiser la validation du formulaire
 */
function initFormValidation() {
    const form = $('#profile-form');
    
    if (form.length === 0) return;
    
    form.on('submit', function(e) {
        let isValid = true;
        const errors = [];
        
        // Validation des champs requis
        const requiredFields = ['first_name', 'last_name', 'email'];
        
        requiredFields.forEach(fieldName => {
            const field = $(`[name="${fieldName}"]`);
            if (field.val().trim() === '') {
                errors.push(`Le champ ${getFieldLabel(fieldName)} est obligatoire.`);
                field.addClass('is-invalid');
                isValid = false;
            } else {
                field.removeClass('is-invalid');
            }
        });
        
        // Validation de l'email
        const email = $('[name="email"]').val();
        if (email && !isValidEmail(email)) {
            errors.push('Veuillez saisir une adresse email valide.');
            $('[name="email"]').addClass('is-invalid');
            isValid = false;
        }
        
        // Validation des mots de passe
        const newPassword = $('[name="new_password"]').val();
        const confirmPassword = $('[name="new_password_confirmation"]').val();
        
        if (newPassword && newPassword !== confirmPassword) {
            errors.push('Les mots de passe ne correspondent pas.');
            $('[name="new_password"], [name="new_password_confirmation"]').addClass('is-invalid');
            isValid = false;
        }
        
        if (newPassword && newPassword.length < 6) {
            errors.push('Le mot de passe doit contenir au moins 6 caractères.');
            $('[name="new_password"]').addClass('is-invalid');
            isValid = false;
        }
        
        // Afficher les erreurs
        if (!isValid) {
            e.preventDefault();
            showValidationErrors(errors);
        }
    });
}

/**
 * Obtenir le libellé d'un champ
 */
function getFieldLabel(fieldName) {
    const labels = {
        'first_name': 'Prénom',
        'last_name': 'Nom',
        'email': 'Email'
    };
    
    return labels[fieldName] || fieldName;
}

/**
 * Valider une adresse email
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Afficher les erreurs de validation
 */
function showValidationErrors(errors) {
    // Supprimer les anciennes alertes
    $('.validation-errors').remove();
    
    if (errors.length === 0) return;
    
    const errorHtml = `
        <div class="alert alert-danger validation-errors">
            <h6><i class="fas fa-exclamation-circle"></i> Erreurs de validation :</h6>
            <ul class="mb-0">
                ${errors.map(error => `<li>${error}</li>`).join('')}
            </ul>
        </div>
    `;
    
    $('#profile-form').prepend(errorHtml);
    
    // Faire défiler vers le haut pour voir les erreurs
    $('html, body').animate({
        scrollTop: $('#profile-form').offset().top - 100
    }, 500);
}

/**
 * Utilitaires pour le debug
 */
window.ProfileDebug = {
    getConfig: () => ProfileConfig,
    testSummernote: () => {
        console.log('Test Summernote:', typeof $.fn.summernote !== 'undefined');
        console.log('Éditeurs trouvés:', $('#biography, #expectations').length);
    },
    testCountries: () => {
        console.log('Pays chargés:', $('#country option').length - 1);
    }
};
