const iconLibrary = window.lucide;

if (iconLibrary) {
    iconLibrary.createIcons();
}

const bagCount = document.querySelector('.bag-count');
const addButtons = document.querySelectorAll('[data-add-to-cart]');
let cartTotal = 0;

addButtons.forEach((button) => {
    button.addEventListener('click', () => {
        cartTotal += 1;
        bagCount.textContent = cartTotal;

        const label = button.querySelector('span');
        label.textContent = 'Added';
        button.setAttribute('aria-label', 'Added to bag');

        window.setTimeout(() => {
            label.textContent = 'Add';
            button.removeAttribute('aria-label');
        }, 1200);
    });
});

const searchTrigger = document.querySelector('.search-trigger');
const searchPanel = document.querySelector('.search-panel');
const searchInput = document.querySelector('#product-search');
const productCards = document.querySelectorAll('[data-product-card]');

searchTrigger?.addEventListener('click', () => {
    const isHidden = searchPanel.hasAttribute('hidden');
    searchPanel.toggleAttribute('hidden', !isHidden);

    if (isHidden) {
        searchInput.focus();
    }
});

searchInput?.addEventListener('input', (event) => {
    const query = event.target.value.trim().toLowerCase();

    productCards.forEach((card) => {
        const name = card.dataset.name || '';
        card.classList.toggle('is-hidden', query !== '' && !name.includes(query));
    });
});

const brandImage = document.querySelector('[data-brand-image]');
const brandSwitchers = document.querySelectorAll('[data-brand-trigger]');
const brandItems = Array.from(document.querySelectorAll('[data-brand-item]'));
const momentStack = brandImage?.closest('.moment-stack');
let activeBrandImage = brandImage?.getAttribute('src') || '';
let activeBrandIndex = brandItems.findIndex((item) => item.classList.contains('is-active'));

if (activeBrandIndex < 0) {
    activeBrandIndex = 0;
}

const getBrandSlot = (itemIndex, selectedIndex) => {
    const total = brandItems.length;
    const edge = Math.floor(total / 2);
    let slot = itemIndex - selectedIndex;

    if (slot > edge) {
        slot -= total;
    }

    if (slot < -edge) {
        slot += total;
    }

    return slot;
};

const applyBrandSlots = (selectedIndex, directionClass = '') => {
    brandItems.forEach((item, itemIndex) => {
        item.classList.remove('is-moving-up', 'is-moving-down');

        if (directionClass) {
            void item.offsetWidth;
            item.classList.add(directionClass);
        }

        item.dataset.slot = String(getBrandSlot(itemIndex, selectedIndex));
    });
};

applyBrandSlots(activeBrandIndex);

const setActiveBrand = (selectedButton) => {
    const nextImage = selectedButton.dataset.brandImage;
    const nextAlt = selectedButton.dataset.brandAlt || selectedButton.dataset.brandName || 'Selected brand image';
    const selectedItem = selectedButton.closest('[data-brand-item]');
    const selectedIndex = brandItems.indexOf(selectedItem);

    if (selectedIndex < 0) {
        return;
    }

    const selectedSlot = Number(selectedItem.dataset.slot || 0);
    const directionClass = selectedSlot > 0 ? 'is-moving-up' : 'is-moving-down';

    brandSwitchers.forEach((button) => {
        const isSelected = button === selectedButton;
        button.setAttribute('aria-pressed', String(isSelected));
        button.closest('li')?.classList.toggle('is-active', isSelected);
    });

    applyBrandSlots(selectedIndex, selectedSlot === 0 ? '' : directionClass);
    activeBrandIndex = selectedIndex;

    if (!brandImage || !nextImage || nextImage === activeBrandImage) {
        return;
    }

    const preload = new Image();

    momentStack?.classList.add('is-switching');

    preload.onload = () => {
        brandImage.src = nextImage;
        brandImage.alt = nextAlt;
        activeBrandImage = nextImage;

        window.setTimeout(() => {
            momentStack?.classList.remove('is-switching');
        }, 80);
    };

    preload.onerror = () => {
        momentStack?.classList.remove('is-switching');
    };

    preload.src = nextImage;
};

brandSwitchers.forEach((button) => {
    button.addEventListener('click', () => setActiveBrand(button));
});
