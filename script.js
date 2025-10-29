// Simple scroll effect
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.site-header');
    
    // Debug: Add a temporary style to make sure the header is visible
    header.style.border = '2px solid blue';
    
    function updateHeader() {
        console.log('Scroll position:', window.scrollY);
        if (window.scrollY > 50) {
            console.log('Adding scrolled class');
            header.classList.add('scrolled');
        } else {
            console.log('Removing scrolled class');
            header.classList.remove('scrolled');
        }
    }
    
    // Initial check
    updateHeader();
    
    // Listen for scroll events
    window.addEventListener('scroll', updateHeader);
    
    // Debug: Add a button to manually trigger the effect
    const debugButton = document.createElement('button');
    debugButton.textContent = 'Test Header Effect';
    debugButton.style.position = 'fixed';
    debugButton.style.bottom = '20px';
    debugButton.style.right = '20px';
    debugButton.style.zIndex = '9999';
    debugButton.style.padding = '10px 20px';
    debugButton.style.background = '#f00';
    debugButton.style.color = 'white';
    debugButton.style.border = 'none';
    debugButton.style.borderRadius = '5px';
    debugButton.style.cursor = 'pointer';
    
    debugButton.addEventListener('click', function() {
        console.log('Test button clicked');
        if (header.classList.contains('scrolled')) {
            header.classList.remove('scrolled');
        } else {
            header.classList.add('scrolled');
        }
    });
    
    document.body.appendChild(debugButton);

    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(contactForm);
            const formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });
            
            // Send the form data to the server
            fetch('send_email.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData).toString()
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Failed to send message');
                }
                // Show success message
                const successMessage = document.createElement('div');
                successMessage.className = 'success-message';
                successMessage.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <h3>Thank You!</h3>
                    <p>Your message has been sent successfully. We'll get back to you soon!</p>
                    <button class="close-message">Close</button>
                `;
                
                // Add styles for the success message
                const style = document.createElement('style');
                style.textContent = `
                    .success-message {
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background: white;
                        padding: 2rem;
                        border-radius: 8px;
                        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                        text-align: center;
                        max-width: 400px;
                        width: 90%;
                        z-index: 1000;
                    }
                    
                    .success-message i {
                        font-size: 4rem;
                        color: #10b981;
                        margin-bottom: 1rem;
                    }
                    
                    .success-message h3 {
                        color: #1a365d;
                        margin-bottom: 1rem;
                    }
                    
                    .success-message p {
                        color: #4b5563;
                        margin-bottom: 1.5rem;
                    }
                    
                    .success-message .close-message {
                        background-color: #1a365d;
                        color: white;
                        border: none;
                        padding: 0.75rem 1.5rem;
                        border-radius: 4px;
                        cursor: pointer;
                        font-weight: 600;
                        transition: background-color 0.3s ease;
                    }
                    
                    .success-message .close-message:hover {
                        background-color: #0f2942;
                    }
                    
                    .overlay {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background-color: rgba(0, 0, 0, 0.7);
                        z-index: 999;
                    }
                `;
                
                // Create overlay
                const overlay = document.createElement('div');
                overlay.className = 'overlay';
                
                // Add elements to the page
                document.head.appendChild(style);
                document.body.appendChild(overlay);
                document.body.appendChild(successMessage);
                
                // Close button functionality
                const closeButton = successMessage.querySelector('.close-message');
                closeButton.addEventListener('click', function() {
                    document.body.removeChild(successMessage);
                    document.body.removeChild(overlay);
                    contactForm.reset();
                });
                
                // Close when clicking outside the message
                overlay.addEventListener('click', function() {
                    document.body.removeChild(successMessage);
                    document.body.removeChild(overlay);
                    contactForm.reset();
                });
                
                // Close with Escape key
                function handleEscape(e) {
                    if (e.key === 'Escape') {
                        document.body.removeChild(successMessage);
                        document.body.removeChild(overlay);
                        contactForm.reset();
                        document.removeEventListener('keydown', handleEscape);
                    }
                }
                
                document.addEventListener('keydown', handleEscape);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to send message: ' + error.message);
            });
        });
    }
});