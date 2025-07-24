$(document).ready(function() {
    // Máscara para telefone
    $('#telefone').on('input', function() {
        let value = this.value.replace(/\D/g, '');
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        this.value = value;
    });

    // Validação em tempo real
    $('.orcamento-form input, .orcamento-form select').on('blur', function() {
        validateField($(this));
    });

    // Submit do formulário
    $('#orcamentoForm').on('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            calculateOrcamento();
        }
    });

    // Função de validação individual
    function validateField($field) {
        const fieldType = $field.attr('type');
        const fieldValue = $field.val().trim();
        let isValid = true;

        // Remover classes anteriores
        $field.removeClass('error success');

        if (!fieldValue) {
            isValid = false;
        } else {
            switch(fieldType) {
                case 'tel':
                    isValid = fieldValue.length >= 14; // (11) 99999-9999
                    break;
                case 'number':
                    isValid = parseFloat(fieldValue) > 0;
                    break;
                case 'text':
                    isValid = fieldValue.length >= 2;
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
        const fields = $('#orcamentoForm input[required], #orcamentoForm select[required]');
        
        fields.each(function() {
            if (!validateField($(this))) {
                isValid = false;
            }
        });

        // Mostrar/ocultar mensagem de erro
        if (!isValid) {
            $('#errorMessage').slideDown();
        } else {
            $('#errorMessage').slideUp();
        }

        return isValid;
    }

    // Cálculo do orçamento
    function calculateOrcamento() {
        const $btn = $('.btn-calcular');
        $btn.addClass('loading');

        // Simular processamento
        setTimeout(() => {
            const largura = parseFloat($('#largura').val());
            const comprimento = parseFloat($('#comprimento').val());
            const tipo = $('#tipo-construcao').val();
            const acabamento = $('#acabamento').val();
            
            const area = largura * comprimento;
            const precosPorM2 = {
                'basico': 1200,
                'medio': 1500,
                'alto': 2000,
                'luxo': 2800
            };
            
            const precoM2 = precosPorM2[acabamento];
            const valorTotal = area * precoM2;
            
            // Multiplicadores por tipo
            const multiplicadores = {
                'residencial': 1.0,
                'comercial': 1.2,
                'apartamento': 0.9,
                'sobrado': 1.3
            };
            
            const valorFinal = valorTotal * (multiplicadores[tipo] || 1.0);
            
            // Atualizar resultado
            updateResult(area, tipo, acabamento, valorFinal);
            
            $btn.removeClass('loading');
            $('#resultSection').addClass('show').slideDown();
            
            // Scroll suave para o resultado
            $('html, body').animate({
                scrollTop: $('#resultSection').offset().top - 100
            }, 800);
            
        }, 2000);
    }

    // Atualizar resultado na tela
    function updateResult(area, tipo, acabamento, valor) {
        $('#areaTotal').text(`${area.toFixed(2)} m²`);
        $('#tipoResult').text(getTipoLabel(tipo));
        $('#padraoResult').text(getAcabamentoLabel(acabamento));
        $('#valorTotal').text(formatCurrency(valor));
        
        // Armazenar dados para WhatsApp e PDF
        window.orcamentoData = {
            nome: $('#nome').val(),
            telefone: $('#telefone').val(),
            area: area.toFixed(2),
            tipo: getTipoLabel(tipo),
            acabamento: getAcabamentoLabel(acabamento),
            valor: formatCurrency(valor)
        };
    }

    // Labels para exibição
    function getTipoLabel(tipo) {
        const tipos = {
            'residencial': 'Casa Residencial',
            'comercial': 'Estabelecimento Comercial',
            'apartamento': 'Apartamento',
            'sobrado': 'Sobrado'
        };
        return tipos[tipo] || tipo;
    }

    function getAcabamentoLabel(acabamento) {
        const acabamentos = {
            'basico': 'Básico',
            'medio': 'Médio',
            'alto': 'Alto',
            'luxo': 'Luxo'
        };
        return acabamentos[acabamento] || acabamento;
    }

    // Formatação de moeda
    function formatCurrency(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    }

    // WhatsApp
    $('#btnWhatsapp').on('click', function() {
        if (window.orcamentoData) {
            const data = window.orcamentoData;
            const message = `🏠 *Orçamento ConstruLar*\n\n` +
                          `📋 *Cliente:* ${data.nome}\n` +
                          `📞 *Telefone:* ${data.telefone}\n` +
                          `📐 *Área:* ${data.area} m²\n` +
                          `🏗️ *Tipo:* ${data.tipo}\n` +
                          `✨ *Acabamento:* ${data.acabamento}\n` +
                          `💰 *Valor Estimado:* ${data.valor}\n\n` +
                          `Gostaria de mais informações sobre este orçamento.`;
            
            const whatsappUrl = `https://wa.me/5511999999999?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }
    });

    // PDF (simulação)
    $('#btnPdf').on('click', function() {
        if (window.orcamentoData) {
            // Aqui você integraria com uma biblioteca de PDF como jsPDF
            showNotification('Funcionalidade PDF em desenvolvimento!', 'info');
        }
    });

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
});