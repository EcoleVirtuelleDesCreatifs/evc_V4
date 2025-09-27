document.addEventListener('DOMContentLoaded', function () {
    // --- SELECTORS --- //
    const form = document.getElementById('creationForm');
    const titleInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const imageUploadZone = document.querySelector('.image-upload-zone');
    const imageInput = document.getElementById('image');
    const imagePreviewContainer = document.querySelector('.image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeImageBtn = document.getElementById('remove-image-btn');
    const vimeoCodeInput = document.getElementById('vimeo_code');
    const vimeoValidationMessage = document.getElementById('vimeo-validation-message');
    const submitButton = document.getElementById('submit-button');
    const statusOptionLabels = document.querySelectorAll('.publication-status-option');
    const submitButtons = document.querySelectorAll('button[name="action"]');
    const destinataireSelect = document.getElementById('destinataire');
    // const studentsSelectContainer = document.getElementById('students-select-container');

    // --- INITIALIZATION --- //
    $('#category_id, #module, #type, #destinataire, #is_featured').select2({
        minimumResultsForSearch: Infinity,
        width: '100%'
    });

    /* $('#student_ids').select2({
        placeholder: 'Rechercher et sélectionner des étudiants',
        width: '100%'
    }); */

    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{'list': 'ordered'}, {'list': 'bullet'}],
                ['link'],
                ['clean']
            ]
        },
        placeholder: 'Décrivez les objectifs, le contenu et les prérequis de la formation...',
    });

    const descriptionInput = document.getElementById('description-input');
    quill.on('text-change', function() {
        descriptionInput.value = quill.root.innerHTML;
    });

    // --- EVENT LISTENERS --- //

    // Publication Status Logic
    function selectStatus(statusValue) {
        statusOptionLabels.forEach(label => {
            const isSelected = label.getAttribute('for') === `status-${statusValue}`;
            label.classList.toggle('selected', isSelected);
            document.getElementById(`status-${statusValue}`).checked = true;
        });
    }

    statusOptionLabels.forEach(label => {
        label.addEventListener('click', () => {
            const statusValue = label.getAttribute('for').replace('status-', '');
            selectStatus(statusValue);
        });
    });

    submitButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const action = this.value;
            selectStatus(action);
        });
    });

    // Conditional students select field (disabled)
    /* destinataireSelect.addEventListener('change', function() {
        if (this.value === 'etudiants-specifiques') {
            studentsSelectContainer.classList.remove('d-none');
        } else {
            studentsSelectContainer.classList.add('d-none');
        }
    }); */

    // Auto-generate slug from title
    titleInput.addEventListener('input', function () {
        slugInput.value = generateSlug(this.value);
    });

    // Image Upload Logic
    imageUploadZone.addEventListener('click', () => imageInput.click());
    imageInput.addEventListener('change', (e) => handleFile(e.target.files[0]));

    // Drag & Drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        imageUploadZone.addEventListener(eventName, preventDefaults, false);
    });
    ['dragenter', 'dragover'].forEach(eventName => {
        imageUploadZone.addEventListener(eventName, () => imageUploadZone.classList.add('highlight'), false);
    });
    ['dragleave', 'drop'].forEach(eventName => {
        imageUploadZone.addEventListener(eventName, () => imageUploadZone.classList.remove('highlight'), false);
    });
    imageUploadZone.addEventListener('drop', handleDrop, false);

    // Remove uploaded image
    removeImageBtn.addEventListener('click', () => {
        imageInput.value = ''; // Clear the file input
        imagePreview.src = '#';
        imagePreviewContainer.classList.add('d-none');
        imageUploadZone.classList.remove('d-none');
    });

    // Vimeo code validation
    vimeoCodeInput.addEventListener('input', validateVimeoCode);

    submitButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Sync status radio button with the clicked button
            const action = this.value;
            selectStatus(action);

            // Perform validation before submitting
            const isVimeoValid = validateVimeoCode();
            if (!form.checkValidity() || !isVimeoValid) {
                // If validation fails, prevent the form from submitting
                e.preventDefault(); 
                form.reportValidity();
            }
            // If validation passes, the form submits naturally with the button's 'action' value
        });
    });

    // --- HELPER FUNCTIONS --- //

    function generateSlug(text) {
        return text.toString().toLowerCase().trim()
            .replace(/\s+/g, '-')           // Replace spaces with -
            .replace(/[^a-z0-9-]/g, '')   // Remove all non-alphanumeric chars except - 
            .replace(/--+/g, '-');        // Replace multiple - with single -
    }

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function handleDrop(e) {
        handleFile(e.dataTransfer.files[0]);
    }

    function validateVimeoCode() {
        const value = vimeoCodeInput.value;
        if (value && !value.includes('vimeo.com/event/') && !value.includes('player.vimeo.com/video/')) {
            vimeoCodeInput.classList.add('is-invalid');
            vimeoValidationMessage.textContent = 'Le code d\'intégration doit être un code Vimeo valide.';
            return false;
        } else {
            vimeoCodeInput.classList.remove('is-invalid');
            vimeoValidationMessage.textContent = '';
            return true;
        }
    }

    function handleFile(file) {
        if (file && file.type.startsWith('image/')) {
            imageInput.files = new DataTransfer().files; // Clear previous files
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            imageInput.files = dataTransfer.files;

            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imageUploadZone.classList.add('d-none');
                imagePreviewContainer.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    }
});
