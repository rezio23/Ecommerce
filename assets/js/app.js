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
            label.textContent = 'Add to Cart';
            button.removeAttribute('aria-label');
        }, 1200);
    });
});

const searchTrigger = document.querySelector('.search-trigger');
const searchPanel = document.querySelector('.search-panel');
const searchInput = document.querySelector('#product-search');
const productCards = document.querySelectorAll('[data-product-card]');
const brandFilter = document.querySelector('[data-product-brand-filter]');
const audienceFilter = document.querySelector('[data-product-audience-filter]');
const groupFilters = document.querySelectorAll('[data-product-group-filter]');
const filterSelects = document.querySelectorAll('[data-filter-select]');
const filterToggles = document.querySelectorAll('[data-filter-toggle]');
const filterOptions = document.querySelectorAll('[data-filter-option]');
let selectedProductGroup = '';

searchTrigger?.addEventListener('click', () => {
    const isHidden = searchPanel.hasAttribute('hidden');
    searchPanel.toggleAttribute('hidden', !isHidden);

    if (isHidden) {
        searchInput.focus();
    }
});

const applyProductFilters = () => {
    const query = searchInput?.value.trim().toLowerCase() || '';
    const selectedBrand = brandFilter?.dataset.filterValue || brandFilter?.value || '';
    const selectedAudience = audienceFilter?.dataset.filterValue || audienceFilter?.value || '';

    productCards.forEach((card) => {
        const searchableText = `${card.dataset.name || ''} ${card.dataset.tags || ''}`;
        const groups = (card.dataset.groups || '').split(' ').filter(Boolean);
        const matchesSearch = query === '' || searchableText.includes(query);
        const matchesBrand = selectedBrand === '' || card.dataset.brand === selectedBrand;
        const matchesAudience = selectedAudience === '' || card.dataset.audience === selectedAudience;
        const matchesGroup = selectedProductGroup === '' || groups.includes(selectedProductGroup);

        card.classList.toggle('is-hidden', !(matchesSearch && matchesBrand && matchesAudience && matchesGroup));
    });
};

searchInput?.addEventListener('input', applyProductFilters);

groupFilters.forEach((button) => {
    button.addEventListener('click', () => {
        selectedProductGroup = button.dataset.filterValue || '';

        groupFilters.forEach((item) => {
            const isSelected = item === button;

            item.classList.toggle('is-active', isSelected);
            item.setAttribute('aria-pressed', String(isSelected));
        });

        applyProductFilters();
    });
});

const closeFilterSelect = (select) => {
    const toggle = select.querySelector('[data-filter-toggle]');

    select.classList.remove('is-open');
    toggle?.setAttribute('aria-expanded', 'false');
};

const closeFilterSelects = (activeSelect = null) => {
    filterSelects.forEach((select) => {
        if (select !== activeSelect) {
            closeFilterSelect(select);
        }
    });
};

filterToggles.forEach((toggle) => {
    toggle.addEventListener('click', () => {
        const select = toggle.closest('[data-filter-select]');

        if (!select) {
            return;
        }

        const shouldOpen = !select.classList.contains('is-open');
        closeFilterSelects(select);
        select.classList.toggle('is-open', shouldOpen);
        toggle.setAttribute('aria-expanded', String(shouldOpen));
    });
});

filterOptions.forEach((option) => {
    option.addEventListener('click', () => {
        const select = option.closest('[data-filter-select]');
        const toggle = select?.querySelector('[data-filter-toggle]');
        const currentLabel = toggle?.querySelector('[data-filter-current]');

        if (!select || !toggle || !currentLabel) {
            return;
        }

        select.querySelectorAll('[data-filter-option]').forEach((item) => {
            const isSelected = item === option;

            item.classList.toggle('is-selected', isSelected);
            item.setAttribute('aria-selected', String(isSelected));
        });

        toggle.dataset.filterValue = option.dataset.filterValue || '';
        currentLabel.textContent = option.textContent.trim();
        closeFilterSelect(select);
        applyProductFilters();
    });
});

document.addEventListener('click', (event) => {
    const clickedFilter = event.target instanceof Element
        ? event.target.closest('[data-filter-select]')
        : null;

    if (!clickedFilter) {
        closeFilterSelects();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeFilterSelects();
    }
});

const productToggles = document.querySelectorAll('[data-product-toggle]');

productToggles.forEach((button) => {
    const content = document.getElementById(button.getAttribute('aria-controls'));

    if (!content) {
        return;
    }

    const isExpanded = button.getAttribute('aria-expanded') === 'true';

    content.toggleAttribute('hidden', !isExpanded);
    content.classList.toggle('is-open', isExpanded);
    content.style.maxHeight = isExpanded ? 'none' : '0px';

    button.addEventListener('click', () => {
        const shouldOpen = button.getAttribute('aria-expanded') !== 'true';

        button.setAttribute('aria-expanded', String(shouldOpen));

        if (shouldOpen) {
            content.hidden = false;
            content.style.maxHeight = '0px';
            content.classList.add('is-open');

            window.requestAnimationFrame(() => {
                content.style.maxHeight = `${content.scrollHeight}px`;
            });

            return;
        }

        content.style.maxHeight = `${content.scrollHeight}px`;

        window.requestAnimationFrame(() => {
            content.classList.remove('is-open');
            content.style.maxHeight = '0px';
        });
    });

    content.addEventListener('transitionend', (event) => {
        if (event.propertyName !== 'max-height') {
            return;
        }

        const isOpen = button.getAttribute('aria-expanded') === 'true';

        if (isOpen) {
            content.style.maxHeight = 'none';
        } else {
            content.hidden = true;
        }
    });
});

const brandStackImages = Array.from(document.querySelectorAll('[data-brand-stack]'));
const brandSwitchers = document.querySelectorAll('[data-brand-trigger]');
const brandItems = Array.from(document.querySelectorAll('[data-brand-item]'));
const momentStack = brandStackImages[0]?.closest('.moment-stack');
let activeBrandIndex = brandItems.findIndex((item) => item.classList.contains('is-active'));
const brandMotionClasses = ['is-moving-up', 'is-moving-down', 'is-wrapping-up', 'is-wrapping-down'];

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

const getSlotTransform = (slot) => {
    const transforms = {
        '-2': 'calc(-50% - var(--brand-edge-gap))',
        '-1': 'calc(-50% - var(--brand-step-gap))',
        0: '-50%',
        1: 'calc(-50% + var(--brand-step-gap))',
        2: 'calc(-50% + var(--brand-edge-gap))',
    };

    return transforms[slot] || '-50%';
};

const getSlotOpacity = (slot) => {
    const opacities = {
        '-2': '0.86',
        '-1': '0.9',
        0: '1',
        1: '0.9',
        2: '0.86',
    };

    return opacities[slot] || '0.88';
};

const clearBrandMotion = (item) => {
    item.classList.remove(...brandMotionClasses);
    item.style.removeProperty('--brand-from-y');
    item.style.removeProperty('--brand-from-opacity');
};

const applyBrandSlots = (selectedIndex, direction = 0) => {
    brandItems.forEach(clearBrandMotion);

    if (direction !== 0) {
        brandItems.forEach((item) => {
            void item.offsetWidth;
        });
    }

    brandItems.forEach((item, itemIndex) => {
        const previousSlot = Number(item.dataset.slot || getBrandSlot(itemIndex, activeBrandIndex));
        const nextSlot = getBrandSlot(itemIndex, selectedIndex);
        const isWrappingUp = direction > 0 && nextSlot > previousSlot;
        const isWrappingDown = direction < 0 && nextSlot < previousSlot;

        if (direction !== 0) {
            item.style.setProperty('--brand-from-y', getSlotTransform(previousSlot));
            item.style.setProperty('--brand-from-opacity', getSlotOpacity(previousSlot));
        }

        item.dataset.slot = String(nextSlot);

        if (isWrappingUp) {
            item.classList.add('is-wrapping-up');
        } else if (isWrappingDown) {
            item.classList.add('is-wrapping-down');
        } else if (direction > 0) {
            item.classList.add('is-moving-up');
        } else if (direction < 0) {
            item.classList.add('is-moving-down');
        }
    });
};

applyBrandSlots(activeBrandIndex);

const getBrandStack = (button) => brandStackImages.map((image) => {
    const position = image.dataset.brandStack || '';
    const key = position.charAt(0).toUpperCase() + position.slice(1);

    return {
        image,
        src: button.dataset[`brand${key}Image`] || '',
        alt: button.dataset[`brand${key}Alt`] || button.dataset.brandName || 'Selected brand image',
    };
}).filter((item) => item.src);

const preloadBrandStack = (items) => Promise.all(items.map((item) => new Promise((resolve) => {
    const preload = new Image();

    preload.onload = () => resolve(item);
    preload.onerror = () => resolve(null);
    preload.src = item.src;
}))).then((results) => results.filter(Boolean));

const stackExitMs = 240;
const stackRevealMs = 760;
let brandStackSwitchId = 0;

const setActiveBrand = (selectedButton) => {
    const selectedItem = selectedButton.closest('[data-brand-item]');
    const selectedIndex = brandItems.indexOf(selectedItem);

    if (selectedIndex < 0) {
        return;
    }

    const selectedSlot = Number(selectedItem.dataset.slot || 0);
    const direction = Math.sign(selectedSlot);

    brandSwitchers.forEach((button) => {
        const isSelected = button === selectedButton;
        button.setAttribute('aria-pressed', String(isSelected));
        button.closest('li')?.classList.toggle('is-active', isSelected);
    });

    applyBrandSlots(selectedIndex, direction);
    activeBrandIndex = selectedIndex;

    const nextStack = getBrandStack(selectedButton);
    const switchId = ++brandStackSwitchId;
    const hasStackChange = nextStack.some((item) => item.image.getAttribute('src') !== item.src);

    if (!nextStack.length || !hasStackChange) {
        momentStack?.classList.remove('is-switching', 'is-revealing');
        return;
    }

    preloadBrandStack(nextStack).then((loadedStack) => {
        if (switchId !== brandStackSwitchId || !loadedStack.length) {
            return;
        }

        if (!momentStack) {
            loadedStack.forEach((item) => {
                item.image.src = item.src;
                item.image.alt = item.alt;
            });
            return;
        }

        momentStack.classList.remove('is-revealing');
        momentStack.classList.add('is-switching');

        window.setTimeout(() => {
            if (switchId !== brandStackSwitchId) {
                return;
            }

            loadedStack.forEach((item) => {
                item.image.src = item.src;
                item.image.alt = item.alt;
            });

            window.requestAnimationFrame(() => {
                if (switchId !== brandStackSwitchId) {
                    return;
                }

                momentStack.classList.remove('is-switching');
                momentStack.classList.add('is-revealing');

                window.setTimeout(() => {
                    if (switchId === brandStackSwitchId) {
                        momentStack.classList.remove('is-revealing');
                    }
                }, stackRevealMs);
            });
        }, stackExitMs);
    });
};

brandSwitchers.forEach((button) => {
    button.addEventListener('click', () => setActiveBrand(button));
});
