class ImageGalleryModal {
    constructor() {
        this.currentImageIndex = 0;
        this.images = [];
        this.productData = null;
        this.isModalOpen = false;
        this.init();
    }

    init() {
        // Obsługa klawiatury - TYLKO dla modala
        document.addEventListener('keydown', (e) => {
            if (this.isModalOpen) {
                if (e.key === 'Escape') this.closeModal();
                if (e.key === 'ArrowLeft') this.previousImage(e);
                if (e.key === 'ArrowRight') this.nextImage(e);
            }
        });

        // Zapobiegaj zamykaniu modala przy kliknięciu na zawartość
        document.addEventListener('click', (e) => {
            if (e.target.closest('.modal-content')) {
                e.stopPropagation();
            }
        });
    }

    // Otwórz modal z danymi produktu
    openProductModal(productId, productName, productPrice, productUrl) {
        // Pobierz dane produktu z API lub localStorage
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
            this.showModal();
        } catch (error) {
            console.error('Błąd ładowania obrazków:', error);
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
        const modalImage = document.getElementById('modalImage');
        const modalCaption = document.getElementById('modalCaption');
        const modalCounter = document.getElementById('modalCounter');

        // Sprawdź czy elementy istnieją
        if (!modal || !modalImage || !modalCaption || !modalCounter) {
            console.error('Nie można znaleźć elementów modala');
            return;
        }

        // Pokaż modal
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        this.isModalOpen = true;

        // Ustaw obrazek
        this.updateModalImage();

        // Pokaż/ukryj nawigację - TYLKO przyciski w modalu
        const navButtons = modal.querySelectorAll('.modal-nav-btn');
        navButtons.forEach(btn => {
            btn.style.display = this.images.length > 1 ? 'block' : 'none';
        });

        // Ustaw licznik
        modalCounter.textContent = `${this.currentImageIndex + 1} / ${this.images.length}`;
    }

    updateModalImage() {
        const modalImage = document.getElementById('modalImage');
        const modalCaption = document.getElementById('modalCaption');
        const modalCounter = document.getElementById('modalCounter');

        if (modalImage && modalCaption && modalCounter && this.images[this.currentImageIndex]) {
            modalImage.src = this.images[this.currentImageIndex].url;
            modalImage.alt = this.images[this.currentImageIndex].alt;
            modalCaption.textContent = this.images[this.currentImageIndex].alt;
            modalCounter.textContent = `${this.currentImageIndex + 1} / ${this.images.length}`;
        }
    }

    closeModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            this.isModalOpen = false;
        }
    }

    previousImage(event) {
        if (event) event.stopPropagation();
        if (this.images.length <= 1) return;

        this.currentImageIndex = this.currentImageIndex > 0
            ? this.currentImageIndex - 1
            : this.images.length - 1;
        this.updateModalImage();
    }

    nextImage(event) {
        if (event) event.stopPropagation();
        if (this.images.length <= 1) return;

        this.currentImageIndex = this.currentImageIndex < this.images.length - 1
            ? this.currentImageIndex + 1
            : 0;
        this.updateModalImage();
    }
}

// Globalne funkcje dla kompatybilności wstecznej
let imageGalleryModal;

function initImageGalleryModal() {
    if (!imageGalleryModal) {
        imageGalleryModal = new ImageGalleryModal();
        console.log('Image Gallery Modal initialized');
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
    if (imageGalleryModal) imageGalleryModal.previousImage(event);
}

function modalNextImage(event) {
    if (imageGalleryModal) imageGalleryModal.nextImage(event);
}

// Inicjalizuj po załadowaniu DOM
document.addEventListener('DOMContentLoaded', initImageGalleryModal);

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { ImageGalleryModal, initImageGalleryModal };
}