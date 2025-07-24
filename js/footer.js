$(document).ready(function() {
    // Newsletter form submission
    $('#newsletterForm').on('submit', function(e) {
        e.preventDefault();
        const email = $(this).find('input[type="email"]').val();
        
        // Simulação de envio (substitua por sua API)
        if (email) {
            showNotification('E-mail cadastrado com sucesso!', 'success');
            $(this).find('input[type="email"]').val('');
        }
    });
    
    // Contact item clicks
    $('.contact-item').on('click', function() {
        const contactType = $(this).data('contact');
        const contactText = $(this).find('span').text();
        
        switch(contactType) {
            case 'phone':
                window.open(`tel:${contactText.replace(/\s/g, '')}`);
                break;
            case 'email':
                window.open(`mailto:${contactText}`);
                break;
            case 'facebook':
                window.open('https://facebook.com/constrular', '_blank');
                break;
            case 'instagram':
                window.open('https://instagram.com/constrular_oficial', '_blank');
                break;
        }
    });
    
    // Social media links
    $('.social-link').on('click', function(e) {
        e.preventDefault();
        const platform = $(this).data('platform');
        
        const socialUrls = {
            facebook: 'https://facebook.com/constrular',
            instagram: 'https://instagram.com/constrular_oficial',
            whatsapp: 'https://wa.me/5511999999999',
            linkedin: 'https://linkedin.com/company/constrular'
        };
        
        if (socialUrls[platform]) {
            window.open(socialUrls[platform], '_blank');
        }
    });
    
    // Smooth scroll for quick links
    $('.quick-links a').on('click', function(e) {
        e.preventDefault();
        const target = $(this).attr('href');
        
        if ($(target).length) {
            $('html, body').animate({
                scrollTop: $(target).offset().top - 80
            }, 800);
        }
    });
    
    // Copy to clipboard functionality
    $('.contact-item').on('contextmenu', function(e) {
        e.preventDefault();
        const text = $(this).find('span').text();
        
        navigator.clipboard.writeText(text).then(() => {
            showNotification('Copiado para a área de transferência!', 'info');
        });
    });
    
    // Notification system
    function showNotification(message, type = 'info') {
        const notification = $(`
            <div class="notification notification-${type}">
                <i class="bi bi-check-circle"></i>
                <span>${message}</span>
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.addClass('show');
        }, 100);
        
        setTimeout(() => {
            notification.removeClass('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Add notification styles
    if (!$('#notificationStyles').length) {
        $('head').append(`
            <style id="notificationStyles">
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: #2c3e50;
                    color: white;
                    padding: 15px 20px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                    transform: translateX(400px);
                    transition: all 0.3s ease;
                    z-index: 10000;
                    border-left: 4px solid #4ECDC4;
                }
                
                .notification.show {
                    transform: translateX(0);
                }
                
                .notification-success {
                    border-left-color: #27ae60;
                }
                
                .notification-info {
                    border-left-color: #3498db;
                }
            </style>
        `);
    }
});