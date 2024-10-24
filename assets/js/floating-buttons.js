document.addEventListener('DOMContentLoaded', function() {
    const backToTopBtn = document.getElementById('backToTopBtn');
    const floatingButtons = document.getElementById('floatingButtons');
    const whatsappBtn = document.getElementById('whatsappBtn');
    const formPopup = document.getElementById('formPopup');
    const closeForm = document.getElementById('closeForm');
    const contactForm = document.getElementById('contactForm');
    const verifyCheckbox = document.getElementById('verifyData');
    const submitButton = contactForm?.querySelector('button[type="submit"]');
    
    // Field validation patterns
    const validationPatterns = {
        name: {
            pattern: /^[a-zA-Z\s]{2,50}$/,
            message: 'Name should only contain letters and be between 2-50 characters'
        },
        email: {
            pattern: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
            message: 'Please enter a valid email address'
        },
        phone: {
            pattern: /^[0-9+\-() ]{10,15}$/,
            message: 'Please enter a valid phone number'
        },
        company: {
            pattern: /^[a-zA-Z0-9\s.,'-]{2,50}$/,
            message: 'Company name should be between 2-50 characters'
        }
    };

    function toggleBackToTopButton() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.remove('opacity-0');
            backToTopBtn.classList.add('opacity-100');
        } else {
            backToTopBtn.classList.remove('opacity-100');
            backToTopBtn.classList.add('opacity-0');
        }
    }

    window.addEventListener('scroll', toggleBackToTopButton);
    toggleBackToTopButton();

    backToTopBtn?.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Form popup controls
    whatsappBtn?.addEventListener('click', function() {
        formPopup.classList.remove('hidden');
    });

    closeForm?.addEventListener('click', function() {
        formPopup.classList.add('hidden');
        resetForm();
    });

    formPopup?.addEventListener('click', function(e) {
        if (e.target === formPopup) {
            formPopup.classList.add('hidden');
            resetForm();
        }
    });

    // Validation functions
    const validateField = (input) => {
        const field = input.id;
        const value = input.value.trim();
        const validationPattern = validationPatterns[field];

        const existingError = input.nextElementSibling;
        if (existingError?.classList.contains('error-message')) {
            existingError.remove();
        }

        input.classList.remove('border-red-500', 'border-green-500');

        if (!value) {
            showError(input, 'This field is required');
            return false;
        }

        if (!validationPattern.pattern.test(value)) {
            showError(input, validationPattern.message);
            return false;
        }

        input.classList.add('border-green-500');
        return true;
    };

    const showError = (input, message) => {
        input.classList.add('border-red-500');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
    };

    // Reset form function
    const resetForm = () => {
        contactForm.reset();
        submitButton.disabled = true;

        ['name', 'email', 'phone', 'company'].forEach(field => {
            const input = document.getElementById(field);
            input.classList.remove('border-red-500', 'border-green-500');
            const errorMsg = input.nextElementSibling;
            if (errorMsg?.classList.contains('error-message')) {
                errorMsg.remove();
            }
        });
    };

    // Add field validation listeners
    ['name', 'email', 'phone', 'company'].forEach(field => {
        const input = document.getElementById(field);
        input?.addEventListener('input', function() {
            if (this.value.trim()) {
                validateField(this);
            }
        });

        input?.addEventListener('blur', function() {
            validateField(this);
        });
    });

    // Handle checkbox change
    verifyCheckbox?.addEventListener('change', function() {
        submitButton.disabled = !this.checked;
    });

    // Initial submit button state
    if (submitButton && verifyCheckbox) {
        submitButton.disabled = !verifyCheckbox.checked;
    }

    document.getElementById('clearForm')?.addEventListener('click', resetForm);

    // Form submission
    let isSubmitting = false;

    contactForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (isSubmitting) return;

        const isValid = ['name', 'email', 'phone', 'company'].every(field => 
            validateField(document.getElementById(field))
        );

        if (!isValid) return;

        isSubmitting = true;
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        const formData = new FormData(this);
        formData.append('action', 'submit_whatsapp_form');
        formData.append('nonce', fbAjax.nonce);

        fetch(fbAjax.ajaxurl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const whatsappUrl = data.data.whatsapp_url;
                
                // Reset and close form first
                resetForm();
                formPopup.classList.add('hidden');
                
                // Single redirection in the same tab
                window.location.replace(whatsappUrl);
            } else {
                alert(data.data.message || 'Error submitting form');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            isSubmitting = false;
            submitBtn.disabled = !verifyCheckbox.checked;
            submitBtn.innerHTML = originalText;
        });
    });

    // Prevent form from being submitted by Enter key
    contactForm?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });
});