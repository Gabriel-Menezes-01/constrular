$(document).ready(function() {
    // Máscara para telefone
    $('#telefone').on('focus', function() {
        if (!this.value.startsWith('+351')) {
            this.value = '+351 ';
        }
    });

    $('#telefone').on('input', function() {
        let value = this.value.replace(/\D/g, '');
        // Adiciona o prefixo +351 ao começar a digitar
        if (value.length > 0) {
            value = '+351 ' + value;
        }
        // Limita a 9 dígitos após o prefixo
        let digits = value.replace(/\D/g, '').slice(0, 9);
        // Formata: +351 9XX XXX XXX
        if (digits.length > 0) {
            value = '+351 ' + digits.replace(/(\d{1})(\d{3})(\d{0,3})/, function(_, p1, p2, p3) {
                return `${p1} ${p2}${p3 ? ' ' + p3 : ''}`;
            });
        }
        this.value = value;
    });

    // Validação em tempo real
    $('.contato-form input, .contato-form select, .contato-form textarea').on('blur', function() {
        validateField($(this));
    });

    // Submit do formulário
    $('#contatoForm').on('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            sendMessage();
        }
    });

    // Cliques nos cards de contato
    $('.contact-card').on('click', function() {
        const contactType = $(this).data('contact');
        handleContactClick(contactType);
    });

    // Função de validação individual
    function validateField($field) {
        const fieldType = $field.attr('type') || $field.prop('tagName').toLowerCase();
        const fieldValue = $field.val().trim();
        let isValid = true;

        // Remover classes anteriores
        $field.removeClass('error success');

        if (!fieldValue) {
            isValid = false;
        } else {
            switch(fieldType) {
                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    isValid = emailRegex.test(fieldValue);
                    break;
                case 'tel':
                    isValid = fieldValue.startsWith('+351') && fieldValue.replace(/\D/g, '').length === 12;
                    break;
                case 'text':
                    isValid = fieldValue.length >= 2;
                    break;
                case 'textarea':
                    isValid = fieldValue.length >= 10;
                    break;
                case 'select':
                    isValid = fieldValue !== '';
                    break;
            }
        }

        // Aplicar classe visual
        $field.addClass(isValid ? 'success' : 'error');
        return isValid;
    }

    // Validação completa do formulário
    function validateForm() {
        let isValid = true;
        const fields = $('#contatoForm input[required], #contatoForm select[required], #contatoForm textarea[required]');
        
        fields.each(function() {
            if (!validateField($(this))) {
                isValid = false;
            }
        });

        return isValid;
    }

    // Envio da mensagem
    function sendMessage() {
        const $btn = $('.btn-enviar');
        $btn.addClass('loading');

        // Coletar dados do formulário
        const formData = {
            nome: $('#nome').val(),
            email: $('#email').val(),
            telefone: $('#telefone').val(),
            assunto: $('#assunto').val(),
            mensagem: $('#mensagem').val()
        };

        // Simular envio (substitua por sua API)
        setTimeout(() => {
            // Aqui você faria a requisição AJAX real
            console.log('Dados do formulário:', formData);
            
            // Mostrar mensagem de sucesso
            $('#successMessage').addClass('show').slideDown();
            
            // Limpar formulário
            $('#contatoForm')[0].reset();
            $('.contato-form input, .contato-form select, .contato-form textarea').removeClass('success error');
            
            // Remover loading
            $btn.removeClass('loading');
            
            // Notificação
            showNotification('Mensagem enviada com sucesso!', 'success');
            
        }, 2000);
    }

    // Ações dos cards de contato
    function handleContactClick(type) {
        switch(type) {
            case 'phone':
                window.open('tel:+351 999 999 999');
                break;
            case 'email':
                window.open('mailto:constrular@gmail.com');
                break;
            case 'whatsapp':
                const message = 'Olá! Gostaria de mais informações sobre os serviços da ConstruLar.';
                const whatsappUrl = `https://wa.me/5511999999999?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
                break;
            case 'location':
                window.open('https://maps.google.com/?q=Rua das Construções, 123, São Paulo', '_blank');
                break;
        }
    }

    // Sistema de notificação
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

    // Adicionar estilos de validação dinâmicos
    if (!$('#contactValidationStyles').length) {
        $('head').append(`
            <style id="contactValidationStyles">
                .input-wrapper input.success,
                .input-wrapper select.success,
                .input-wrapper textarea.success {
                    border-color: #27ae60;
                    box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
                }
                
                .input-wrapper input.error,
                .input-wrapper select.error,
                .input-wrapper textarea.error {
                    border-color: #e74c3c;
                    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
                }
                
                .success-message.show {
                    display: flex !important;
                }
            </style>
        `);
    }
});