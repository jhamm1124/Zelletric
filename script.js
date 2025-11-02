// Header scroll effect
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.site-header');
    
    function updateHeader() {
        header.classList.toggle('scrolled', window.scrollY > 50);
    }
    
    // Initial check and add scroll event listener
    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });

    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data and add CSRF token
            const formData = new FormData(contactForm);
            formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
            const submitButton = contactForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            
            // Show loading message
            const loadingMessage = document.createElement('div');
            loadingMessage.className = 'status-message loading';
            loadingMessage.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending your message...';
            contactForm.appendChild(loadingMessage);

            // Send form data to server
            fetch('send_email.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Remove loading message
                if (loadingMessage.parentNode === contactForm) {
                    contactForm.removeChild(loadingMessage);
                }
                
                if (!data.success) {
                    throw new Error(data.message || 'Failed to send message');
                }
                
                // Show success message
                const successMessage = document.createElement('div');
                successMessage.className = 'status-message success';
                successMessage.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <h3>Message Sent!</h3>
                    <p>Thank you for contacting us. We'll get back to you soon!</p>
                    <button class="close-message">Close</button>
                `;
                
                // Create overlay
                const overlay = document.createElement('div');
                overlay.className = 'overlay';
                
                // Add elements to the page
                document.body.append(overlay, successMessage);
                
                // Close functionality
                const closeModal = () => {
                    document.body.removeChild(successMessage);
                    document.body.removeChild(overlay);
                    contactForm.reset();
                    document.removeEventListener('keydown', handleEscape);
                };
                
                // Close button
                successMessage.querySelector('.close-message').addEventListener('click', closeModal);
                
                // Close on overlay click
                overlay.addEventListener('click', closeModal);
                
                // Close with Escape key
                const handleEscape = (e) => e.key === 'Escape' && closeModal();
                document.addEventListener('keydown', handleEscape);
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Remove loading message if it exists
                if (loadingMessage.parentNode === contactForm) {
                    contactForm.removeChild(loadingMessage);
                }
                
                // Show error message
                const errorMessage = document.createElement('div');
                errorMessage.className = 'status-message error';
                errorMessage.innerHTML = `
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <h3>Error Sending Message</h3>
                        <p>${error.message || 'An error occurred while sending your message. Please try again later.'}</p>
                    </div>
                `;
                
                // Insert error message after the form
                contactForm.insertBefore(errorMessage, contactForm.firstChild);
                
                // Auto-remove error message after 10 seconds
                setTimeout(() => {
                    if (errorMessage.parentNode === contactForm) {
                        contactForm.removeChild(errorMessage);
                    }
                }, 10000);
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            });
        });
    }
});