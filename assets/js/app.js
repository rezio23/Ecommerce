$(function () {
    const iconLibrary = window.lucide;

    if (iconLibrary) {
        iconLibrary.createIcons();
    }

    const $bagCount = $('.bag-count');
    const $addButtons = $('[data-add-to-cart]');
    let cartTotal = 0;

    $addButtons.on('click', function () {
        const $button = $(this);
        cartTotal += 1;
        $bagCount.text(cartTotal);

        const $label = $button.find('span');
        $label.text('Added');
        $button.attr('aria-label', 'Added to bag');

        window.setTimeout(function () {
            $label.text('Add to Cart');
            $button.removeAttr('aria-label');
        }, 1200);
    });

    const $searchTrigger = $('.search-trigger');
    const $headerSearch = $('[data-header-search]');
    const $headerSearchInput = $headerSearch.find('[data-product-search]');
    const $siteHeader = $headerSearch.closest('.site-header');
    const $searchPanel = $('.search-panel');
    const $searchInputs = $('[data-product-search]');
    const $searchInput = $searchInputs.first();
    const $productCards = $('[data-product-card]');
    const $shopProductGrid = $('[data-shop-product-grid]');
    const $shopPagination = $('[data-shop-pagination]');
    const $brandFilter = $('[data-product-brand-filter]');
    const $audienceFilter = $('[data-product-audience-filter]');
    const $groupFilters = $('[data-product-group-filter]');
    const $filterSelects = $('[data-filter-select]');
    const $filterToggles = $('[data-filter-toggle]');
    const $filterOptions = $('[data-filter-option]');
    const $productSizeGroups = $('[data-product-size-group]');
    const $productGalleryMain = $('[data-product-gallery-main]');
    const $productGalleryThumbs = $('[data-product-gallery-thumb]');
    const $productGalleryFrame = $productGalleryMain.closest('.product-hero-media');
    const $profileSections = $('[data-profile-section]');
    let selectedProductGroup = '';
    let selectedProductPage = 1;
    let productSearchQuery = $searchInput.val() || '';
    let productGallerySwitchId = 0;
    const productsPerPage = Number($shopPagination.attr('data-page-size')) || 8;
    const productPageExitMs = 220;
    const productPageEnterMs = 320;
    const productGridMotionClasses = [
        'is-sliding-out-left',
        'is-sliding-out-right',
        'is-sliding-in-left',
        'is-sliding-in-right',
    ];
    const reducedMotionQuery = window.matchMedia?.('(prefers-reduced-motion: reduce)');
    let isProductPageAnimating = false;

    const clearProductGridMotion = function () {
        $shopProductGrid.removeClass(productGridMotionClasses.join(' '));
    };

    const getPaginationItems = function (totalPages) {
        if (totalPages <= 4) {
            return Array.from({ length: totalPages }, function (_, index) { return index + 1; });
        }

        if (selectedProductPage <= 2) {
            return [1, 2, 'ellipsis', totalPages];
        }

        if (selectedProductPage >= totalPages - 1) {
            return [1, 'ellipsis', totalPages - 1, totalPages];
        }

        return [1, 'ellipsis', selectedProductPage, 'ellipsis', totalPages];
    };

    const renderShopPagination = function (totalPages) {
        if (!$shopPagination.length) {
            return;
        }

        $shopPagination.prop('hidden', totalPages <= 1);
        $shopProductGrid.toggleClass('has-pagination', totalPages > 1);
        $shopPagination.empty();

        getPaginationItems(totalPages).forEach(function (item) {
            if (item === 'ellipsis') {
                const $ellipsis = $('<span></span>');
                $ellipsis.text('...');
                $ellipsis.attr('aria-hidden', 'true');
                $shopPagination.append($ellipsis);
                return;
            }

            const $pageButton = $('<button></button>');
            const isActive = item === selectedProductPage;

            $pageButton.attr('type', 'button');
            $pageButton.text(String(item));
            $pageButton.attr('data-product-page', String(item));
            $pageButton.attr('aria-label', 'Show product page ' + item);

            if (isActive) {
                $pageButton.addClass('is-active');
                $pageButton.attr('aria-current', 'page');
            }

            $shopPagination.append($pageButton);
        });
    };

    const setHeaderSearchOpen = function (shouldOpen) {
        if (!$headerSearch.length) {
            return;
        }

        $headerSearch.toggleClass('is-open', shouldOpen);
        $siteHeader.toggleClass('has-header-search', shouldOpen);
        $searchTrigger.attr('aria-expanded', String(shouldOpen));
        $searchTrigger.attr('aria-label', shouldOpen ? 'Search products' : 'Open search');

        if (!$headerSearchInput.length) {
            return;
        }

        $headerSearchInput.prop('tabIndex', shouldOpen ? 0 : -1);
        $headerSearchInput.attr('aria-hidden', String(!shouldOpen));

        if (shouldOpen) {
            window.requestAnimationFrame(function () {
                $headerSearchInput.focus();
            });
        } else if (document.activeElement === $headerSearchInput[0]) {
            $headerSearchInput.blur();
        }
    };

    const syncSearchInputs = function (sourceInput) {
        const $source = sourceInput ? $(sourceInput) : $searchInput;
        productSearchQuery = $source.val() || '';

        $searchInputs.each(function () {
            if (this !== $source[0]) {
                $(this).val(productSearchQuery);
            }
        });
    };

    $searchTrigger.on('click', function () {
        if ($headerSearch.length) {
            setHeaderSearchOpen(true);
            return;
        }

        if (!$searchPanel.length || !$searchInput.length) {
            return;
        }

        const isHidden = $searchPanel.prop('hidden');
        $searchPanel.prop('hidden', !isHidden);

        if (isHidden) {
            $searchInput.focus();
        }
    });

    const $navToggle = $('.nav-toggle');
    const $siteNav = $('.site-nav');

    const setMobileNavOpen = function (shouldOpen) {
        if (!$siteNav.length || !$navToggle.length) {
            return;
        }

        $siteNav.toggleClass('is-open', shouldOpen);
        $navToggle.attr('aria-expanded', String(shouldOpen));
        $navToggle.attr('aria-label', shouldOpen ? 'Close menu' : 'Open menu');
    };

    $navToggle.on('click', function () {
        setMobileNavOpen(!$siteNav.hasClass('is-open'));
    });

    $(document).on('click', function (event) {
        const $target = $(event.target);
        if (!$target.closest('.site-header').length && $siteNav.hasClass('is-open')) {
            setMobileNavOpen(false);
        }
    });

    $(document).on('keydown', function (event) {
        if (event.key === 'Escape' && $siteNav.hasClass('is-open')) {
            setMobileNavOpen(false);
        }
    });

    const applyProductFilters = function (opts) {
        opts = opts || {};
        const keepPage = opts.keepPage || false;
        const query = productSearchQuery.trim().toLowerCase();
        const selectedBrand = $brandFilter.attr('data-filter-value') || $brandFilter.val() || '';
        const selectedAudience = $audienceFilter.attr('data-filter-value') || $audienceFilter.val() || '';
        const matchingCards = [];

        $productCards.each(function () {
            const $card = $(this);
            const searchableText = ($card.attr('data-name') || '') + ' ' + ($card.attr('data-tags') || '');
            const groups = ($card.attr('data-groups') || '').split(' ').filter(Boolean);
            const matchesSearch = query === '' || searchableText.includes(query);
            const matchesBrand = selectedBrand === '' || $card.attr('data-brand') === selectedBrand;
            const matchesAudience = selectedAudience === '' || $card.attr('data-audience') === selectedAudience;
            const matchesGroup = selectedProductGroup === '' || groups.includes(selectedProductGroup);
            const isMatch = matchesSearch && matchesBrand && matchesAudience && matchesGroup;

            if (isMatch) {
                matchingCards.push(this);
            }
        });

        if (!$shopPagination.length) {
            $productCards.each(function () {
                const $card = $(this);
                $card.toggleClass('is-hidden', matchingCards.indexOf(this) < 0);
            });
            return;
        }

        const totalPages = Math.max(1, Math.ceil(matchingCards.length / productsPerPage));

        if (!keepPage) {
            selectedProductPage = 1;
        }

        selectedProductPage = Math.min(selectedProductPage, totalPages);

        const startIndex = (selectedProductPage - 1) * productsPerPage;
        const currentPageCards = matchingCards.slice(startIndex, startIndex + productsPerPage);
        const currentPageSet = new Set(currentPageCards);

        $productCards.each(function () {
            const $card = $(this);
            $card.toggleClass('is-hidden', !currentPageSet.has(this));
        });

        renderShopPagination(totalPages);
    };

    $shopPagination.on('click', function (event) {
        const $pageButton = $(event.target).closest('[data-product-page]');

        if (!$pageButton.length || !$.contains($shopPagination[0], $pageButton[0])) {
            return;
        }

        event.preventDefault();

        const nextPage = Number($pageButton.attr('data-product-page'));

        if (!Number.isFinite(nextPage) || nextPage === selectedProductPage || isProductPageAnimating) {
            return;
        }

        const scrollX = $(window).scrollLeft();
        const scrollY = $(window).scrollTop();
        const isNextPage = nextPage > selectedProductPage;

        if (!$shopProductGrid.length || reducedMotionQuery?.matches) {
            selectedProductPage = nextPage;
            applyProductFilters({ keepPage: true });
            window.scrollTo(scrollX, scrollY);
            return;
        }

        isProductPageAnimating = true;
        $shopPagination.addClass('is-busy');
        clearProductGridMotion();
        void $shopProductGrid[0].offsetWidth;
        $shopProductGrid.addClass(isNextPage ? 'is-sliding-out-left' : 'is-sliding-out-right');

        window.setTimeout(function () {
            selectedProductPage = nextPage;
            applyProductFilters({ keepPage: true });
            window.scrollTo(scrollX, scrollY);

            clearProductGridMotion();
            void $shopProductGrid[0].offsetWidth;
            $shopProductGrid.addClass(isNextPage ? 'is-sliding-in-right' : 'is-sliding-in-left');

            window.setTimeout(function () {
                clearProductGridMotion();
                $shopPagination.removeClass('is-busy');
                isProductPageAnimating = false;
            }, productPageEnterMs);
        }, productPageExitMs);
    });

    const urlParams = new URLSearchParams(window.location.search);
    const brandParam = urlParams.get('brand');

    if (brandParam && $brandFilter.length) {
        const $matchingOption = $filterOptions.filter('[data-filter-value="' + brandParam + '"]').first();

        if ($matchingOption.length) {
            const $select = $matchingOption.closest('[data-filter-select]');
            const $toggle = $select.find('[data-filter-toggle]');
            const $currentLabel = $toggle.find('[data-filter-current]');

            $select.find('[data-filter-option]').each(function () {
                const $item = $(this);
                const isSelected = $item[0] === $matchingOption[0];
                $item.toggleClass('is-selected', isSelected);
                $item.attr('aria-selected', String(isSelected));
            });

            $toggle.attr('data-filter-value', brandParam);

            if ($currentLabel.length) {
                $currentLabel.text($matchingOption.text().trim());
            }
        }
    }

    const categoryParam = urlParams.get('category');

    if (categoryParam && $groupFilters.length) {
        const $matchingGroupButton = $groupFilters.filter('[data-filter-value="' + categoryParam + '"]').first();

        if ($matchingGroupButton.length) {
            selectedProductGroup = categoryParam;

            $groupFilters.each(function () {
                const $item = $(this);
                const isSelected = $item[0] === $matchingGroupButton[0];

                $item.toggleClass('is-active', isSelected);
                $item.attr('aria-pressed', String(isSelected));
            });
        }
    }

    applyProductFilters({ keepPage: true });

    $searchInputs.on('input', function (event) {
        syncSearchInputs(event.currentTarget);
        applyProductFilters();
    });

    $groupFilters.on('click', function () {
        const $button = $(this);
        selectedProductGroup = $button.attr('data-filter-value') || '';

        $groupFilters.each(function () {
            const $item = $(this);
            const isSelected = $item[0] === $button[0];

            $item.toggleClass('is-active', isSelected);
            $item.attr('aria-pressed', String(isSelected));
        });

        applyProductFilters();
    });

    const closeFilterSelect = function ($select) {
        const $toggle = $select.find('[data-filter-toggle]');

        $select.removeClass('is-open');
        $toggle.attr('aria-expanded', 'false');
    };

    const closeFilterSelects = function ($activeSelect) {
        $filterSelects.each(function () {
            const $select = $(this);
            if (!$activeSelect || $select[0] !== $activeSelect[0]) {
                closeFilterSelect($select);
            }
        });
    };

    $filterToggles.on('click', function () {
        const $toggle = $(this);
        const $select = $toggle.closest('[data-filter-select]');

        if (!$select.length) {
            return;
        }

        const shouldOpen = !$select.hasClass('is-open');
        closeFilterSelects($select);
        $select.toggleClass('is-open', shouldOpen);
        $toggle.attr('aria-expanded', String(shouldOpen));
    });

    $filterOptions.on('click', function () {
        const $option = $(this);
        const $select = $option.closest('[data-filter-select]');
        const $toggle = $select.find('[data-filter-toggle]');
        const $currentLabel = $toggle.find('[data-filter-current]');

        if (!$select.length || !$toggle.length || !$currentLabel.length) {
            return;
        }

        $select.find('[data-filter-option]').each(function () {
            const $item = $(this);
            const isSelected = $item[0] === $option[0];

            $item.toggleClass('is-selected', isSelected);
            $item.attr('aria-selected', String(isSelected));
        });

        $toggle.attr('data-filter-value', $option.attr('data-filter-value') || '');
        $currentLabel.text($option.text().trim());
        closeFilterSelect($select);
        applyProductFilters();
    });

    const setActiveProductSize = function ($activeButton) {
        const $group = $activeButton.closest('[data-product-size-group]');

        if (!$group.length) {
            return;
        }

        const selectedSize = $activeButton.attr('data-size-value') || $activeButton.text().trim();
        $group.attr('data-selected-size', selectedSize);

        $group.find('[data-product-size-option]').each(function () {
            const $button = $(this);
            const isActive = $button[0] === $activeButton[0];

            $button.toggleClass('is-active', isActive);
            $button.attr('aria-pressed', String(isActive));
        });
    };

    $productSizeGroups.each(function () {
        const $group = $(this);
        const $initialSize = $group.find('[data-product-size-option].is-active').first();
        const $firstSize = $group.find('[data-product-size-option]').first();
        const $sizeToUse = $initialSize.length ? $initialSize : $firstSize;

        if ($sizeToUse.length) {
            setActiveProductSize($sizeToUse);
        }

        $group.on('click', function (event) {
            const $button = $(event.target).closest('[data-product-size-option]');

            if (!$button.length || !$.contains($group[0], $button[0])) {
                return;
            }

            setActiveProductSize($button);
        });
    });

    const setActiveProductGalleryThumb = function ($activeButton) {
        $productGalleryThumbs.each(function () {
            const $item = $(this);
            const isActive = $item[0] === $activeButton[0];

            $item.toggleClass('is-active', isActive);
            $item.attr('aria-selected', String(isActive));
        });
    };

    $productGalleryThumbs.on('click', function (event) {
        event.preventDefault();

        if (!$productGalleryMain.length) {
            return;
        }

        const $button = $(this);
        const nextImage = $button.attr('data-gallery-image');
        const nextAlt = $button.attr('data-gallery-alt') || 'Selected product image';

        if (!nextImage) {
            return;
        }

        setActiveProductGalleryThumb($button);

        if ($productGalleryMain.attr('src') === nextImage) {
            return;
        }

        const switchId = ++productGallerySwitchId;
        const nextGalleryImage = new Image();

        $productGalleryFrame.addClass('is-switching');

        nextGalleryImage.onload = function () {
            window.setTimeout(function () {
                if (switchId !== productGallerySwitchId) {
                    return;
                }

                $productGalleryMain.attr('src', nextImage);
                $productGalleryMain.attr('alt', nextAlt);

                window.requestAnimationFrame(function () {
                    $productGalleryFrame.removeClass('is-switching');
                });
            }, 120);
        };

        nextGalleryImage.onerror = function () {
            if (switchId === productGallerySwitchId) {
                $productGalleryFrame.removeClass('is-switching');
            }
        };

        nextGalleryImage.src = nextImage;
    });

    const getProfileCarouselStep = function ($panel) {
        const $track = $panel.find('[data-profile-carousel]');
        const $card = $track.find('.profile-product-card').first();

        if (!$track.length || !$card.length) {
            return $panel[0].clientWidth;
        }

        const trackGap = parseFloat($track.css('columnGap')) || parseFloat($track.css('gap')) || 0;

        return $card[0].getBoundingClientRect().width + trackGap;
    };

    const moveProfileCarousel = function ($section, direction) {
        const $panel = $section.find('[data-profile-carousel-panel]');
        const $toggle = $section.find('[data-product-toggle]');

        if (!$panel.length) {
            return;
        }

        if ($toggle.attr('aria-expanded') === 'false') {
            $toggle.trigger('click');
        }

        const maxScroll = Math.max(0, $panel[0].scrollWidth - $panel[0].clientWidth);

        if (maxScroll <= 1) {
            return;
        }

        const step = getProfileCarouselStep($panel);
        const current = $panel.scrollLeft();
        let nextPosition = current + (step * direction);

        if (direction > 0 && current >= maxScroll - 4) {
            nextPosition = 0;
        } else if (direction < 0 && current <= 4) {
            nextPosition = maxScroll;
        } else {
            nextPosition = Math.max(0, Math.min(maxScroll, nextPosition));
        }

        $panel[0].scrollTo({
            left: nextPosition,
            behavior: reducedMotionQuery?.matches ? 'auto' : 'smooth',
        });
    };

    $profileSections.each(function () {
        const $section = $(this);
        const $previousButton = $section.find('[data-profile-carousel-prev]');
        const $nextButton = $section.find('[data-profile-carousel-next]');

        $previousButton.on('click', function () {
            moveProfileCarousel($section, -1);
        });

        $nextButton.on('click', function () {
            moveProfileCarousel($section, 1);
        });
    });

    $(document).on('click', function (event) {
        const $clickedFilter = $(event.target).closest('[data-filter-select]');

        if (!$clickedFilter.length) {
            closeFilterSelects();
        }

        const $clickedHeaderSearch = $(event.target).closest('[data-header-search]');

        if ($headerSearch.length && !$clickedHeaderSearch.length && !$headerSearchInput.val()) {
            setHeaderSearchOpen(false);
        }
    });

    $(document).on('keydown', function (event) {
        if (event.key === 'Escape') {
            closeFilterSelects();
            setHeaderSearchOpen(false);
        }
    });

    const $productToggles = $('[data-product-toggle]');

    $productToggles.each(function () {
        const $button = $(this);
        const $content = $('#' + $button.attr('aria-controls'));

        if (!$content.length) {
            return;
        }

        const isExpanded = $button.attr('aria-expanded') === 'true';

        $content.prop('hidden', !isExpanded);
        $content.toggleClass('is-open', isExpanded);
        $content.css('maxHeight', isExpanded ? 'none' : '0px');

        $button.on('click', function () {
            const shouldOpen = $button.attr('aria-expanded') !== 'true';

            $button.attr('aria-expanded', String(shouldOpen));

            if (shouldOpen) {
                $content.prop('hidden', false);
                $content.css('maxHeight', '0px');
                $content.addClass('is-open');

                window.requestAnimationFrame(function () {
                    $content.css('maxHeight', $content[0].scrollHeight + 'px');
                });

                return;
            }

            $content.css('maxHeight', $content[0].scrollHeight + 'px');

            window.requestAnimationFrame(function () {
                $content.removeClass('is-open');
                $content.css('maxHeight', '0px');
            });
        });

        $content.on('transitionend', function (event) {
            if (event.originalEvent.propertyName !== 'max-height') {
                return;
            }

            const isOpen = $button.attr('aria-expanded') === 'true';

            if (isOpen) {
                $content.css('maxHeight', 'none');
            } else {
                $content.prop('hidden', true);
            }
        });
    });

    const $brandStackImages = $('[data-brand-stack]');
    const $brandSwitchers = $('[data-brand-trigger]');
    const $brandItems = $('[data-brand-item]');
    const $momentStack = $brandStackImages.first().closest('.moment-stack');
    const $seeProductLink = $('[data-see-product]');
    let activeBrandIndex = $brandItems.index($brandItems.filter('.is-active').first());
    const brandMotionClasses = ['is-moving-up', 'is-moving-down', 'is-wrapping-up', 'is-wrapping-down'];

    if (activeBrandIndex < 0) {
        activeBrandIndex = 0;
    }

    const getBrandSlot = function (itemIndex, selectedIndex) {
        const total = $brandItems.length;
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

    const getSlotTransform = function (slot) {
        const transforms = {
            '-2': 'calc(-50% - var(--brand-edge-gap))',
            '-1': 'calc(-50% - var(--brand-step-gap))',
            '0': '-50%',
            '1': 'calc(-50% + var(--brand-step-gap))',
            '2': 'calc(-50% + var(--brand-edge-gap))',
        };

        return transforms[slot] || '-50%';
    };

    const getSlotOpacity = function (slot) {
        const opacities = {
            '-2': '0.86',
            '-1': '0.9',
            '0': '1',
            '1': '0.9',
            '2': '0.86',
        };

        return opacities[slot] || '0.88';
    };

    const clearBrandMotion = function ($item) {
        $item.removeClass(brandMotionClasses.join(' '));
        $item[0].style.removeProperty('--brand-from-y');
        $item[0].style.removeProperty('--brand-from-opacity');
    };

    const applyBrandSlots = function (selectedIndex, direction) {
        direction = direction || 0;

        $brandItems.each(function () {
            clearBrandMotion($(this));
        });

        if (direction !== 0) {
            $brandItems.each(function () {
                void $(this)[0].offsetWidth;
            });
        }

        $brandItems.each(function (itemIndex) {
            const $item = $(this);
            const previousSlot = Number($item.attr('data-slot') || getBrandSlot(itemIndex, activeBrandIndex));
            const nextSlot = getBrandSlot(itemIndex, selectedIndex);
            const isWrappingUp = direction > 0 && nextSlot > previousSlot;
            const isWrappingDown = direction < 0 && nextSlot < previousSlot;

            if (direction !== 0) {
                $item[0].style.setProperty('--brand-from-y', getSlotTransform(previousSlot));
                $item[0].style.setProperty('--brand-from-opacity', getSlotOpacity(previousSlot));
            }

            $item.attr('data-slot', String(nextSlot));

            if (isWrappingUp) {
                $item.addClass('is-wrapping-up');
            } else if (isWrappingDown) {
                $item.addClass('is-wrapping-down');
            } else if (direction > 0) {
                $item.addClass('is-moving-up');
            } else if (direction < 0) {
                $item.addClass('is-moving-down');
            }
        });
    };

    applyBrandSlots(activeBrandIndex);

    const getBrandStack = function ($button) {
        return $brandStackImages.map(function (index, image) {
            const $image = $(image);
            const position = $image.attr('data-brand-stack') || '';
            const key = position.charAt(0).toUpperCase() + position.slice(1);

            return {
                image: image,
                src: $button.attr('data-brand-' + key.toLowerCase() + '-image') || '',
                alt: $button.attr('data-brand-' + key.toLowerCase() + '-alt') || $button.attr('data-brand-name') || 'Selected brand image',
            };
        }).get().filter(function (item) { return item.src; });
    };

    const preloadBrandStack = function (items) {
        return Promise.all(items.map(function (item) {
            return new Promise(function (resolve) {
                const preload = new Image();

                preload.onload = function () { resolve(item); };
                preload.onerror = function () { resolve(null); };
                preload.src = item.src;
            });
        })).then(function (results) {
            return results.filter(Boolean);
        });
    };

    const stackExitMs = 240;
    const stackRevealMs = 760;
    let brandStackSwitchId = 0;

    const setActiveBrand = function ($selectedButton) {
        const $selectedItem = $selectedButton.closest('[data-brand-item]');
        const selectedIndex = $brandItems.index($selectedItem);

        if (selectedIndex < 0) {
            return;
        }

        const selectedSlot = Number($selectedItem.attr('data-slot') || 0);
        const direction = Math.sign(selectedSlot);

        $brandSwitchers.each(function () {
            const $button = $(this);
            const isSelected = $button[0] === $selectedButton[0];
            $button.attr('aria-pressed', String(isSelected));
            $button.closest('li').toggleClass('is-active', isSelected);
        });

        applyBrandSlots(selectedIndex, direction);
        activeBrandIndex = selectedIndex;

        if ($seeProductLink.length) {
            const brandFilter = $selectedButton.attr('data-brand-filter') || '';
            $seeProductLink.attr('href', 'src/shop.php?brand=' + encodeURIComponent(brandFilter) + '#brand_selector');
        }

        const nextStack = getBrandStack($selectedButton);
        const switchId = ++brandStackSwitchId;
        const hasStackChange = nextStack.some(function (item) {
            return $(item.image).attr('src') !== item.src;
        });

        if (!nextStack.length || !hasStackChange) {
            $momentStack.removeClass('is-switching is-revealing');
            return;
        }

        preloadBrandStack(nextStack).then(function (loadedStack) {
            if (switchId !== brandStackSwitchId || !loadedStack.length) {
                return;
            }

            if (!$momentStack.length) {
                loadedStack.forEach(function (item) {
                    $(item.image).attr('src', item.src);
                    $(item.image).attr('alt', item.alt);
                });
                return;
            }

            $momentStack.removeClass('is-revealing');
            $momentStack.addClass('is-switching');

            window.setTimeout(function () {
                if (switchId !== brandStackSwitchId) {
                    return;
                }

                loadedStack.forEach(function (item) {
                    $(item.image).attr('src', item.src);
                    $(item.image).attr('alt', item.alt);
                });

                window.requestAnimationFrame(function () {
                    if (switchId !== brandStackSwitchId) {
                        return;
                    }

                    $momentStack.removeClass('is-switching');
                    $momentStack.addClass('is-revealing');

                    window.setTimeout(function () {
                        if (switchId === brandStackSwitchId) {
                            $momentStack.removeClass('is-revealing');
                        }
                    }, stackRevealMs);
                });
            }, stackExitMs);
        });
    };

    $brandSwitchers.on('click', function () {
        setActiveBrand($(this));
    });

    const $editProfileModal = $('#edit-profile-modal');
    const $editProfileOpeners = $('[data-edit-open]');
    const $editProfileClosers = $('[data-edit-close]');

    const setEditProfileOpen = function (shouldOpen) {
        if (!$editProfileModal.length) {
            return;
        }

        $editProfileModal.toggleClass('is-open', shouldOpen);
        $editProfileModal.attr('aria-hidden', String(!shouldOpen));

        if (shouldOpen) {
            const $firstInput = $editProfileModal.find('.edit-form-input').first();
            window.requestAnimationFrame(function () {
                $firstInput.focus();
            });
        }
    };

    $editProfileOpeners.on('click', function (event) {
        event.preventDefault();
        setEditProfileOpen(true);
    });

    $editProfileClosers.on('click', function () {
        setEditProfileOpen(false);
    });

    $editProfileModal.on('click', function (event) {
        if (event.target === $editProfileModal[0]) {
            setEditProfileOpen(false);
        }
    });

    $(document).on('keydown', function (event) {
        if (event.key === 'Escape' && $editProfileModal.hasClass('is-open')) {
            setEditProfileOpen(false);
        }
    });

    const $editFileInput = $('#edit-profile-pic');
    const $editFileText = $('#edit-file-text');

    if ($editFileInput.length && $editFileText.length) {
        $editFileInput.on('change', function () {
            const files = $editFileInput.prop('files');
            $editFileText.text(files[0]?.name || 'Browser File');
        });
    }

    const $shopHeroModel = $('.shop-hero-model');

    if ($shopHeroModel.length) {
        let maxTranslate = $(window).width() - $shopHeroModel[0].clientWidth;
        let ticking = false;

        const updateShopHeroModel = function () {
            const scrollY = $(window).scrollTop();
            const translateX = Math.min(scrollY * 1.5, maxTranslate);
            const rotation = scrollY * 0.15;
            $shopHeroModel.css('transform', 'translateX(' + translateX + 'px) rotate(' + rotation + 'deg)');
            ticking = false;
        };

        const recalcMaxTranslate = function () {
            maxTranslate = Math.max(0, $(window).width() - $shopHeroModel[0].clientWidth);
            updateShopHeroModel();
        };

        $(window).on('scroll', function () {
            if (!ticking) {
                window.requestAnimationFrame(updateShopHeroModel);
                ticking = true;
            }
        });

        $(window).on('resize', recalcMaxTranslate);
        recalcMaxTranslate();
    }
});
