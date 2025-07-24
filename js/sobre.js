$(document).ready(function() {
    let currentImageIndex = 0;
    let galleryImages = [];

    // Inicializar funcionalidades
    initCounters();
    initGalleryFilters();
    initGalleryModal();
    initScrollAnimations();

    // Contador animado para estatísticas
    function initCounters() {
        const counters = $('.stat-item');
        let triggered = false;

        $(window).on('scroll', function() {
            if (!triggered && isElementInViewport(counters.first()[0])) {
                triggered = true;
                animateCounters();
            }
        });
    }

    function animateCounters() {
        $('.stat-item').each(function() {
            const $this = $(this);
            const target = parseInt($this.data('count'));
            const $number = $this.find('.stat-number');
            
            $({ countNum: 0 }).animate({
                countNum: target
            }, {
                duration: 2000,
                easing: 'swing',
                step: function() {
                    $number.text(Math.floor(this.countNum));
                },
                complete: function() {
                    $number.text(target + '+');
                }
            });
        });
    }

    // Filtros da galeria
    function initGalleryFilters() {
        $('.filter-btn').on('click', function() {
            const filter = $(this).data('filter');
            
            // Atualizar botão ativo
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            
            // Filtrar itens
            if (filter === 'all') {
                $('.gallery-item').show().addClass('fade-in');
            } else {
                $('.gallery-item').hide().removeClass('fade-in');
                $(`.gallery-item[data-category="${filter}"]`).show().addClass('fade-in');
            }
        });
    }

    // Modal da galeria
    function initGalleryModal() {
        // Coletar todas as imagens
        $('.gallery-item img').each(function() {
            galleryImages.push($(this).attr('src'));
        });

        // Abrir modal
        $('.view-btn').on('click', function(e) {
            e.stopPropagation();
            const imageSrc = $(this).data('image');
            currentImageIndex = galleryImages.indexOf(imageSrc);
            showModal(imageSrc);
        });

        // Fechar modal
        $('.modal-close, .modal-overlay').on('click', function(e) {
            if (e.target === this) {
                hideModal();
            }
        });

        // Navegação do modal
        $('.prev-btn').on('click', function() {
            currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
            updateModalImage();
        });

        $('.next-btn').on('click', function() {
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
            updateModalImage();
        });

        // Teclas de navegação
        $(document).on('keydown', function(e) {
            if ($('.gallery-modal').is(':visible')) {
                if (e.key === 'Escape') {
                    hideModal();
                } else if (e.key === 'ArrowLeft') {
                    $('.prev-btn').click();
                } else if (e.key === 'ArrowRight') {
                    $('.next-btn').click();
                }
            }
        });
    }

    function showModal(imageSrc) {
        $('#modalImage').attr('src', imageSrc);
        $('.gallery-modal').fadeIn(300);
        $('body').css('overflow', 'hidden');
    }

    function hideModal() {
        $('.gallery-modal').fadeOut(300);
        $('body').css('overflow', 'auto');
    }

    function updateModalImage() {
        $('#modalImage').attr('src', galleryImages[currentImageIndex]);
    }

    // Animações de scroll
    function initScrollAnimations() {
        $(window).on('scroll', function() {
            $('.info-card').each(function() {
                if (isElementInViewport(this)) {
                    $(this).addClass('animate-in');
                }
            });

            $('.gallery-item').each(function() {
                if (isElementInViewport(this)) {
                    $(this).addClass('animate-in');
                }
            });
        });
    }

    // Função auxiliar para verificar se elemento está visível
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Efeito parallax suave no hero
    $(window).on('scroll', function() {
        const scrolled = $(window).scrollTop();
        const parallax = $('.hero-sobre');
        const speed = scrolled * 0.5;
        
        parallax.css('transform', `translateY(${speed}px)`);
    });

    // Smooth scroll para links internos
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            }, 800);
        }
    });

    // Adicionar estilos dinâmicos
    if (!$('#sobreAnimationStyles').length) {
        $('head').append(`
            <style id="sobreAnimationStyles">
                .animate-in {
                    animation: fadeInUp 0.6s ease-out;
                }
                
                .fade-in {
                    animation: fadeIn 0.5s ease-out;
                }
                
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                
                .gallery-item.hidden {
                    display: none !important;
                }
                
                .gallery-modal.show {
                    display: flex !important;
                }
            </style>
        `);
    }

    // Lazy loading para imagens
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    $('.gallery-item img[data-src]').each(function() {
        imageObserver.observe(this);
    });
});