document.addEventListener('DOMContentLoaded', function () {
    const chatBody = document.getElementById('chat-body');
    const inputContainer = document.getElementById('input-container');
    const sendButton = document.getElementById('send-button');
    const hiddenForm = document.getElementById('creationForm');

    const conversationSteps = [
        { key: 'title', question: 'Bonjour ! Prêt à créer une formation ? Commençons par le titre.', inputType: 'text', placeholder: 'Ex: Maîtriser Photoshop de A à Z' },
        { key: 'category', question: 'Excellent titre ! Dans quelle catégorie thématique la classeriez-vous ?', inputType: 'text', placeholder: 'Ex: Branding, Retouche...' },
        { key: 'module', question: 'Parfait. Quel est le domaine principal de cette formation ?', inputType: 'select', options: { 'design-graphique': 'Design Graphique', 'community-manager': 'Community Manager', 'informatique': 'Informatique', 'intelligence-artificielle': 'Intelligence Artificielle' } },
        { key: 'type', question: 'Bien noté. Sera-t-elle dispensée en ligne ou en présentiel ?', inputType: 'select', options: { 'en_ligne': 'En ligne', 'presentiel': 'Présentiel' } },
        { key: 'destinataire', question: 'À qui cette formation est-elle principalement destinée ?', inputType: 'select', options: { 'etudiants-actifs': 'Étudiants actifs', 'etudiants-specifiques': 'Étudiants spécifiques' } },
        { key: 'image', question: 'Maintenant, ajoutons une belle image de couverture (glissez-déposez ou cliquez).', inputType: 'file' },
        { key: 'vimeo_code', question: 'Presque terminé ! Si vous avez une vidéo de présentation, indiquez son code ou ID Vimeo.', inputType: 'text', placeholder: 'Ex: 872456315' },
        { key: 'summary', question: 'Super ! Nous avons tous les éléments. Voici le résumé. Tout est correct ?', inputType: 'summary' }
    ];

    let currentStepIndex = 0;
    let formData = {};

    function displayBotMessage(text, isSummary = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'chat-message bot-message';
        messageDiv.innerHTML = `
            <div class="avatar"><i class="fas fa-robot"></i></div>
            <div class="message-content">${text}</div>
        `;
        chatBody.appendChild(messageDiv);
        scrollToBottom();
        if (!isSummary) renderInputForStep(conversationSteps[currentStepIndex]);
    }

    function displayUserMessage(text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'chat-message user-message';
        messageDiv.innerHTML = `
            <div class="avatar"><i class="fas fa-user"></i></div>
            <div class="message-content">${text}</div>
        `;
        chatBody.appendChild(messageDiv);
        scrollToBottom();
    }

    function renderInputForStep(step) {
        inputContainer.innerHTML = '';
        let inputElement;

        switch (step.inputType) {
            case 'select':
                inputElement = document.createElement('select');
                inputElement.className = 'form-select';
                inputElement.innerHTML = `<option value="" disabled selected>Choisir...</option>`;
                for (const [value, text] of Object.entries(step.options)) {
                    inputElement.innerHTML += `<option value="${value}">${text}</option>`;
                }
                inputContainer.appendChild(inputElement);
                $(inputElement).select2({ minimumResultsForSearch: Infinity });
                break;
            case 'file':
                inputElement = document.createElement('div');
                inputElement.className = 'image-upload-zone text-center p-3';
                inputElement.innerHTML = `<i class="fas fa-cloud-upload-alt"></i><p class="m-0">Glissez-déposez ou cliquez</p>`;
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.className = 'd-none';
                inputElement.addEventListener('click', () => fileInput.click());
                fileInput.addEventListener('change', (e) => handleFile(e.target.files[0]));
                inputContainer.appendChild(inputElement);
                inputContainer.appendChild(fileInput);
                break;
            case 'summary':
                inputContainer.innerHTML = `<button id="confirm-submit" class="btn btn-success">Confirmer et Enregistrer</button>`;
                document.getElementById('confirm-submit').addEventListener('click', submitForm);
                sendButton.style.display = 'none';
                break;
            default:
                inputElement = document.createElement('input');
                inputElement.type = 'text';
                inputElement.className = 'form-control';
                inputElement.placeholder = step.placeholder || '';
                inputContainer.appendChild(inputElement);
                inputElement.focus();
                break;
        }
    }
    
    function handleFile(file) {
        if(file) {
            formData[conversationSteps[currentStepIndex].key] = file;
            displayUserMessage(`Fichier sélectionné : ${file.name}`);
            nextStep();
        }
    }

    function processStep() {
        const step = conversationSteps[currentStepIndex];
        if (step.inputType === 'file' || step.inputType === 'summary') return; 

        const input = inputContainer.querySelector('input, select');
        const value = $(input).val();

        if (!value) {
            // Simple validation
            alert('Veuillez fournir une réponse.');
            return;
        }

        let userResponseText = value;
        if(input.tagName === 'SELECT') {
            userResponseText = input.options[input.selectedIndex].text;
        }

        displayUserMessage(userResponseText);
        formData[step.key] = value;
        nextStep();
    }

    function nextStep() {
        currentStepIndex++;
        if (currentStepIndex < conversationSteps.length) {
            const nextStepDetails = conversationSteps[currentStepIndex];
            if(nextStepDetails.inputType === 'summary') {
                displayBotMessage(buildSummary(), true);
                renderInputForStep(nextStepDetails);
            } else {
                displayBotMessage(nextStepDetails.question);
            }
        } 
    }

    function buildSummary(){
        let summaryHtml = '<strong>Récapitulatif :</strong><ul class="list-unstyled mt-2">';
        for(const key in formData){
            if(key === 'image') {
                summaryHtml += `<li><strong>Image :</strong> ${formData[key].name}</li>`;
            } else {
                summaryHtml += `<li><strong>${key.charAt(0).toUpperCase() + key.slice(1)} :</strong> ${formData[key]}</li>`;
            }
        }
        summaryHtml += '</ul>';
        return summaryHtml;
    }

    function submitForm(){
        for(const key in formData) {
            const hiddenInput = hiddenForm.querySelector(`[name="${key}"]`);
            if(hiddenInput) {
                if(key === 'image') {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(formData[key]);
                    hiddenInput.files = dataTransfer.files;
                } else {
                     hiddenInput.value = formData[key];
                }
            }
        }
        // Auto-generate slug
        hiddenForm.querySelector('[name="slug"]').value = formData.title.toLowerCase().trim().replace(/[^a-z0-9\s-]/g, '').replace(/[\s-]+/g, '-');
        hiddenForm.submit();
    }

    function scrollToBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    sendButton.addEventListener('click', processStep);
    inputContainer.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
            processStep();
        }
    });

    // Start conversation
    displayBotMessage(conversationSteps[0].question);
});
