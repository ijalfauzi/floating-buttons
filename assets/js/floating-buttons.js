// assets/js/floating-buttons.js

document.addEventListener('DOMContentLoaded', function() {
    const backToTopBtn = document.getElementById('backToTopBtn');
    const floatingButtons = document.getElementById('floatingButtons');
    const whatsappBtn = document.getElementById('whatsappBtn');
    const formPopup = document.getElementById('formPopup');
    const closeForm = document.getElementById('closeForm');
    const contactForm = document.getElementById('contactForm');
    const verifyCheckbox = document.getElementById('verifyData');
    const submitButton = contactForm?.querySelector('button[type="submit"]');
    
    function toggleBackToTopButton() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.remove('opacity-0');
            backToTopBtn.classList.add('opacity-100');
        } else {
            backToTopBtn.classList.remove('opacity-100');
            backToTopBtn.classList.add('opacity-0');
        }
    }

    // Show/hide button when scrolling
    window.addEventListener('scroll', toggleBackToTopButton);

    // Initial check
    toggleBackToTopButton();

    // Smooth scroll to top
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Show popup when WhatsApp button is clicked
    whatsappBtn.addEventListener('click', function() {
        formPopup.classList.remove('hidden');
    });

    // Close popup
    closeForm.addEventListener('click', function() {
        formPopup.classList.add('hidden');
    });

    // Close popup when clicking outside
    formPopup.addEventListener('click', function(e) {
        if (e.target === formPopup) {
            formPopup.classList.add('hidden');
        }
    });

    // Clear form
    document.getElementById('clearForm')?.addEventListener('click', function() {
        contactForm.reset();
        submitButton.disabled = true;
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
    });

    // Handle checkbox change
    verifyCheckbox?.addEventListener('change', function() {
        if (this.checked) {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    });

    // Initial state of submit button
    if (submitButton && verifyCheckbox) {
        submitButton.disabled = !verifyCheckbox.checked;
        if (submitButton.disabled) {
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // Handle form submission
    contactForm?.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!verifyCheckbox.checked) {
            alert('Please verify that your information is correct');
            return;
        }

        const formData = new FormData(contactForm);
        formData.append('action', 'submit_whatsapp_form');
        formData.append('nonce', fbAjax.nonce);

        // Disable form while submitting
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        fetch(fbAjax.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to WhatsApp
                window.location.href = data.data.whatsapp_url;
                
                // Close popup and reset form
                formPopup.classList.add('hidden');
                contactForm.reset();
            } else {
                alert(data.data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            // Re-enable form
            submitBtn.disabled = true;
            submitBtn.innerHTML = originalText;
            verifyCheckbox.checked = false;
        });
    });
});