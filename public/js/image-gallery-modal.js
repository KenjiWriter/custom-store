class ImageGalleryModal {
    constructor() {
        this.currentImageIndex = 0;
        this.images = [];
        this.productData = null;
        this.isModalOpen = false;
        this.isNavigating = false;
        this.touchStartX = 0;
        this.touchEndX = 0;
        this.init();
    }

    init() {
        // Obsługa klawiatury - TYLKO dla modala
        document.addEventListener('keydown', (e) => {
            if (this.isModalOpen) {
                if (e.key === 'Escape') this.closeModal();
                if (e.key === 'ArrowLeft') this.previousImage(e);
                if (e.key === 'ArrowRight') this.nextImage(e);
                if (e.key === 'Home') this.goToImage(0, e);
                if (e.key === 'End') this.goToImage(this.images.length - 1, e);
            }
        });

        // Obsługa gestów touch
        document.addEventListener('touchstart', (e) => {
            if (this.isModalOpen) {
                this.touchStartX = e.changedTouches[0].screenX;
            }
        });

        document.addEventListener('touchend', (e) => {
            if (this.isModalOpen) {
                this.touchEndX = e.changedTouches[0].screenX;
                this.handleSwipe();
            }
        });

        // Zapobiegaj zamykaniu modala przy kliknięciu na zawartość
        document.addEventListener('click', (e) => {
            if (e.target.closest('.modal-content') && !e.target.closest('.modal-close-btn')) {
                e.stopPropagation();
            }
        });

        // Zamykanie modala przy kliknięciu w tło
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('image-modal') && this.isModalOpen) {
                this.closeModal();
            }
        });
    }

    handleSwipe() {
        const swipeThreshold = 50;
        const swipeDistance = this.touchEndX - this.touchStartX;

        if (Math.abs(swipeDistance) > swipeThreshold) {
            if (swipeDistance > 0) {
                this.previousImage();
            } else {
                this.nextImage();
            }
        }
    }

    // Otwórz modal z danymi produktu
    openProductModal(productId, productName, productPrice, productUrl) {
        this.loadProductImages(productId, productName, productPrice, productUrl);
    }

    // Otwórz modal z pojedynczym obrazkiem
    openSingleImageModal(imageSrc, imageAlt, productName = '', productPrice = '', productUrl = '') {
        this.images = [{
            url: imageSrc,
            alt: imageAlt
        }];
        this.productData = {
            name: productName,
            price: productPrice,
            url: productUrl
        };
        this.currentImageIndex = 0;
        this.showModal();
    }

    // Otwórz modal z tablicą obrazków
    openImagesModal(images, startIndex = 0, productData = null) {
        this.images = images;
        this.productData = productData;
        this.currentImageIndex = startIndex;
        this.showModal();
    }

    // Załaduj obrazki produktu
    async loadProductImages(productId, productName, productPrice, productUrl) {
        try {
            this.showLoading();

            // Próbuj pobrać z cache lub API
            const cacheKey = `product_images_${productId}`;
            let images = localStorage.getItem(cacheKey);

            if (images) {
                images = JSON.parse(images);
            } else {
                // Pobierz obrazki z API
                const response = await fetch(`/products/${productId}/images`);
                if (response.ok) {
                    const data = await response.json();
                    images = data.images;
                    // Cache na 5 minut
                    localStorage.setItem(cacheKey, JSON.stringify(images));
                    setTimeout(() => localStorage.removeItem(cacheKey), 300000);
                } else {
                    // Fallback - użyj obrazek z DOM
                    images = this.getImagesFromDOM(productId);
                }
            }

            this.images = images || [];
            this.productData = {
                name: productName,
                price: productPrice,
                url: productUrl
            };
            this.currentImageIndex = 0;
            this.hideLoading();
            this.showModal();
        } catch (error) {
            console.error('Błąd ładowania obrazków:', error);
            this.hideLoading();
            // Fallback
            this.images = this.getImagesFromDOM(productId);
            this.showModal();
        }
    }

    // Pobierz obrazki z DOM jako fallback
    getImagesFromDOM(productId) {
        const productCard = document.querySelector(`[data-product-id="${productId}"]`);
        if (productCard) {
            const img = productCard.querySelector('img');
            if (img) {
                return [{
                    url: img.src,
                    alt: img.alt || 'Zdjęcie produktu'
                }];
            }
        }
        return [];
    }

    showModal() {
        if (this.images.length === 0) return;

        const modal = document.getElementById('imageModal');
        if (!modal) {
            console.error('Modal element not found');
            return;
        }

        // Pokaż modal z animacją
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Trigger reflow
        modal.offsetHeight;

        modal.classList.add('active');
        this.isModalOpen = true;

        // Ustaw obrazek
        this.updateModalImage();
        this.updateNavigation();
        this.updateCounter();
        this.updateProductInfo();
        this.createThumbnails();

        // Dodaj efekty parallax
        this.startParallaxEffects();
    }

    updateModalImage() {
        if (this.images.length === 0) return;

        const modalImage = document.getElementById('modalImage');
        if (!modalImage) return;

        const currentImage = this.images[this.currentImageIndex];

        // Animacja fade podczas zmiany obrazka
        modalImage.style.opacity = '0';
        modalImage.style.transform = 'scale(0.95)';

        setTimeout(() => {
            modalImage.src = currentImage.url;
            modalImage.alt = currentImage.alt || 'Zdjęcie produktu';

            modalImage.onload = () => {
                modalImage.style.opacity = '1';
                modalImage.style.transform = 'scale(1)';
            };
        }, 150);
    }

    updateNavigation() {
        const prevBtn = document.querySelector('.modal-nav-prev');
        const nextBtn = document.querySelector('.modal-nav-next');

        if (prevBtn && nextBtn) {
            const hasMultipleImages = this.images.length > 1;

            prevBtn.style.display = hasMultipleImages ? 'flex' : 'none';
            nextBtn.style.display = hasMultipleImages ? 'flex' : 'none';

            // Dodaj efekt bounce przy hover
            if (hasMultipleImages) {
                this.addNavigationEffects(prevBtn, nextBtn);
            }
        }
    }

    addNavigationEffects(prevBtn, nextBtn) {
        // Usuwaj poprzednie listenery
        prevBtn.removeEventListener('mouseenter', this.prevHoverEffect);
        nextBtn.removeEventListener('mouseenter', this.nextHoverEffect);

        // Dodaj nowe efekty
        this.prevHoverEffect = () => this.triggerNavigationEffect(prevBtn, 'left');
        this.nextHoverEffect = () => this.triggerNavigationEffect(nextBtn, 'right');

        prevBtn.addEventListener('mouseenter', this.prevHoverEffect);
        nextBtn.addEventListener('mouseenter', this.nextHoverEffect);
    }

    triggerNavigationEffect(button, direction) {
        // Animacja ripple
        const ripple = button.querySelector('.nav-ripple');
        if (ripple) {
            ripple.style.width = '0';
            ripple.style.height = '0';
            ripple.style.opacity = '0.3';

            setTimeout(() => {
                ripple.style.width = '120px';
                ripple.style.height = '120px';
                ripple.style.opacity = '0';
            }, 50);
        }

        // Lekka animacja strzałki
        const arrow = button.querySelector('.nav-arrow');
        if (arrow) {
            const moveDistance = direction === 'left' ? '-5px' : '5px';
            arrow.style.transform = `translateX(${moveDistance}) scale(1.2)`;

            setTimeout(() => {
                arrow.style.transform = 'translateX(0) scale(1.2)';
            }, 200);
        }
    }

    updateCounter() {
        const counter = document.getElementById('modalCounter');
        if (counter) {
            counter.textContent = `${this.currentImageIndex + 1} / ${this.images.length}`;

            // Animacja licznika
            counter.style.transform = 'scale(0.8)';
            counter.style.opacity = '0.5';

            setTimeout(() => {
                counter.style.transform = 'scale(1)';
                counter.style.opacity = '1';
            }, 100);
        }
    }

    updateProductInfo() {
        const productInfo = document.getElementById('modalProductInfo');
        const productName = document.getElementById('modalProductName');
        const productPrice = document.getElementById('modalProductPrice');
        const productLink = document.getElementById('modalProductLink');

        if (this.productData && this.productData.name) {
            if (productName) productName.textContent = this.productData.name;
            if (productPrice) productPrice.textContent = this.productData.price;
            if (productLink) productLink.href = this.productData.url;
            if (productInfo) productInfo.style.display = 'block';
        } else {
            if (productInfo) productInfo.style.display = 'none';
        }
    }

    createThumbnails() {
        const thumbnailsContainer = document.getElementById('modalThumbnails');
        if (!thumbnailsContainer || this.images.length <= 1) {
            if (thumbnailsContainer) thumbnailsContainer.style.display = 'none';
            return;
        }

        thumbnailsContainer.innerHTML = '';
        thumbnailsContainer.style.display = 'flex';

        this.images.forEach((image, index) => {
            const thumb = document.createElement('img');
            thumb.src = image.url;
            thumb.alt = image.alt || `Miniaturka ${index + 1}`;
            thumb.className = `modal-thumbnail ${index === this.currentImageIndex ? 'active' : ''}`;
            thumb.addEventListener('click', () => this.goToImage(index));
            thumbnailsContainer.appendChild(thumb);
        });
    }

    goToImage(index, event = null) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (index < 0 || index >= this.images.length || this.isNavigating) return;

        this.isNavigating = true;
        this.currentImageIndex = index;
        this.updateModalImage();
        this.updateCounter();
        this.updateThumbnailsActive();

        setTimeout(() => {
            this.isNavigating = false;
        }, 300);
    }

    updateThumbnailsActive() {
        const thumbnails = document.querySelectorAll('.modal-thumbnail');
        thumbnails.forEach((thumb, index) => {
            thumb.classList.toggle('active', index === this.currentImageIndex);
        });
    }

    previousImage(event = null) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (this.images.length <= 1 || this.isNavigating) return;

        const newIndex = this.currentImageIndex > 0
            ? this.currentImageIndex - 1
            : this.images.length - 1;

        this.goToImage(newIndex);

        // Efekt wizualny dla przycisku
        const prevBtn = document.querySelector('.modal-nav-prev');
        if (prevBtn) {
            this.triggerNavigationEffect(prevBtn, 'left');
        }
    }

    nextImage(event = null) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (this.images.length <= 1 || this.isNavigating) return;

        const newIndex = this.currentImageIndex < this.images.length - 1
            ? this.currentImageIndex + 1
            : 0;

        this.goToImage(newIndex);

        // Efekt wizualny dla przycisku
        const nextBtn = document.querySelector('.modal-nav-next');
        if (nextBtn) {
            this.triggerNavigationEffect(nextBtn, 'right');
        }
    }

    startParallaxEffects() {
        const parallaxLayers = document.querySelectorAll('.parallax-layer');
        parallaxLayers.forEach((layer, index) => {
            layer.style.animationDelay = `${index * 0.5}s`;
        });
    }

    showLoading() {
        const loader = document.getElementById('modalLoader');
        if (loader) {
            loader.style.display = 'flex';
        }
    }

    hideLoading() {
        const loader = document.getElementById('modalLoader');
        if (loader) {
            loader.style.display = 'none';
        }
    }

    closeModal() {
        const modal = document.getElementById('imageModal');
        if (!modal || !this.isModalOpen) return;

        // Animacja zamknięcia
        modal.classList.remove('active');

        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            this.isModalOpen = false;
            this.images = [];
            this.productData = null;
            this.currentImageIndex = 0;
        }, 400);
    }
}

// Globalne funkcje dla kompatybilności wstecznej
let imageGalleryModal;

function initImageGalleryModal() {
    if (!imageGalleryModal) {
        imageGalleryModal = new ImageGalleryModal();
        console.log('Enhanced Image Gallery Modal initialized with parallax effects');
    }
}

function openImageModal(src, alt, productName = '', productPrice = '', productUrl = '') {
    if (!imageGalleryModal) initImageGalleryModal();
    imageGalleryModal.openSingleImageModal(src, alt, productName, productPrice, productUrl);
}

function openProductImageModal(productId, productName, productPrice, productUrl) {
    if (!imageGalleryModal) initImageGalleryModal();
    imageGalleryModal.openProductModal(productId, productName, productPrice, productUrl);
}

function closeImageModal() {
    if (imageGalleryModal) imageGalleryModal.closeModal();
}

function modalPreviousImage(event) {
    if (imageGalleryModal) {
        imageGalleryModal.previousImage(event);
    }
}

function modalNextImage(event) {
    if (imageGalleryModal) {
        imageGalleryModal.nextImage(event);
    }
}

// Inicjalizuj po załadowaniu DOM
document.addEventListener('DOMContentLoaded', initImageGalleryModal);

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { ImageGalleryModal, initImageGalleryModal };
}
