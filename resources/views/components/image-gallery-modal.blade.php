<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <div class="modal-content">
        <span class="close-btn" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="">
        <div class="modal-nav">
            <button class="modal-nav-btn prev" onclick="modalPreviousImage(event)">‹</button>
            <button class="modal-nav-btn next" onclick="modalNextImage(event)">›</button>
        </div>
        <div class="modal-caption" id="modalCaption"></div>
        <div class="modal-counter">
            <span id="modalCounter">1 / 1</span>
        </div>
    </div>
</div>