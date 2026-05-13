<?php
require '../includes/security.php';
startSecureSession();

$helpFaqs = [
    [
        'question' => 'How do I place an order?',
        'answer' => 'Browse our Shop page, select a product, choose your size, and click Add to Cart. When you are ready, go to your Cart and proceed to Checkout. You can pay with KHQR or debit card.',
    ],
    [
        'question' => 'What payment methods do you accept?',
        'answer' => 'We accept KHQR, debit card, and other secure payment methods available at checkout. All transactions are processed safely.',
    ],
    [
        'question' => 'How long does shipping take?',
        'answer' => 'We process orders within 1-3 business days. Delivery times vary based on your location and selected shipping method. You will receive tracking details once your order ships.',
    ],
    [
        'question' => 'What is your return policy?',
        'answer' => 'We accept returns within 30 days of delivery for items that are unused, unworn, and in their original packaging with all tags attached. Refunds are processed within 5-10 business days.',
    ],
    [
        'question' => 'Are your products authentic?',
        'answer' => 'Yes. The DS guarantees 100% authentic products sourced directly from brand-authorized distributors. Every item undergoes rigorous verification before listing.',
    ],
    [
        'question' => 'How do I contact customer support?',
        'answer' => 'You can reach us via email at thedaservice@store.com or by phone at +855 112 233. Our team is based in Phnom Penh, Cambodia. You can also use the AI chat below for instant answers.',
    ],
];

$helpTickerBrands = ['POLO', 'BALENCIAGA', 'ADIDAS', 'NIKE', 'PUMA', 'GUCCI', 'PRADA', 'CHANEL'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?v=111">
</head>
<body class="help-page">

<?php
$headerId = 'help-top';
$searchId = 'header-help-search';
$bagCount = array_sum(array_column($_SESSION['cart'] ?? [], 'quantity'));
$activeButton = '';
$currentPage = 'help';
$searchTrigger = 'button';
$rootPath = '../';
$srcPath = '';
?>

<?php include '../includes/navbar.php'; ?>
<main class="help-main">
    <section class="help-hero" aria-labelledby="help-hero-heading">
        <div class="help-hero-copy">
            <p class="pixel-note"><span>Home / Help /</span> Support</p>
            <h1 id="help-hero-heading">Help <span>Center</span></h1>
            <p class="help-hero-lead">Find answers to common questions below, or chat with our AI assistant for instant support.</p>
            <a href="#faq" class="help-hero-cta">
                Browse FAQs
                <i data-lucide="arrow-down" aria-hidden="true"></i>
            </a>
        </div>
        <figure class="help-hero-model">
            <img src="https://parkaccess.com.ph/cdn/shop/files/AURORA_FD2596-602_PHSLH000-2000.png?v=1770200188" alt="Customer support illustration">
        </figure>
    </section>

    <section class="brand-ticker help-brand-ticker" aria-label="Featured brands">
        <div class="brand-track">
            <?php for ($i = 0; $i < 4; $i++): ?>
                <?php foreach ($helpTickerBrands as $brand): ?>
                    <span><?= htmlspecialchars($brand); ?></span>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>
    </section>

    <section class="help-contact" aria-label="Contact options">
        <div class="help-contact-grid">
            <article class="help-contact-card">
                <span class="help-contact-icon"><i data-lucide="bot"></i></span>
                <h3>AI Chat</h3>
                <p>Get instant answers 24/7 from our AI assistant.</p>
                <a href="#chat">Start Chatting <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            </article>
            <article class="help-contact-card">
                <span class="help-contact-icon"><i data-lucide="mail"></i></span>
                <h3>Email Us</h3>
                <p>thedaservice@store.com</p>
                <a href="mailto:thedaservice@store.com">Send Email <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            </article>
            <article class="help-contact-card">
                <span class="help-contact-icon"><i data-lucide="phone"></i></span>
                <h3>Call Us</h3>
                <p>+855 112 233</p>
                <a href="tel:+855112233">Call Now <i data-lucide="arrow-right" aria-hidden="true"></i></a>
            </article>
        </div>
    </section>

    <section class="help-faq" id="faq" aria-labelledby="faq-heading">
        <div class="help-faq__intro">
            <h2 id="faq-heading">Frequently Asked Questions</h2>
            <p class="pixel-note">Quick answers to the most common topics.</p>
        </div>

        <div class="help-faq-search">
            <span class="help-faq-search__icon"><i data-lucide="search"></i></span>
            <input type="search" id="faq-search" placeholder="Search questions..." aria-label="Search FAQs">
        </div>

        <div class="help-faq-list">
            <?php foreach ($helpFaqs as $index => $faq): ?>
                <article class="help-faq-item">
                    <button
                        class="help-faq-toggle"
                        type="button"
                        data-product-toggle
                        aria-expanded="<?= $index === 0 ? 'true' : 'false'; ?>"
                        aria-controls="faq-panel-<?= $index; ?>"
                    >
                        <span class="help-faq-toggle__num"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></span>
                        <span class="help-faq-toggle__text"><?= htmlspecialchars($faq['question']); ?></span>
                        <i data-lucide="chevron-down" aria-hidden="true"></i>
                    </button>
                    <div
                        class="help-faq-content"
                        id="faq-panel-<?= $index; ?>"
                        data-product-content
                        <?= $index !== 0 ? 'hidden' : ''; ?>
                    >
                        <p><?= htmlspecialchars($faq['answer']); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="help-faq-empty" data-faq-empty hidden>No questions match your search.</p>
    </section>

    <section class="help-chat" id="chat" aria-labelledby="chat-heading">
        <div class="help-chat__card">
            <div class="help-chat__sidebar">
                <div class="help-chat__brand">
                    <span class="help-chat__brand-icon"><i data-lucide="bot"></i></span>
                    <div>
                        <h2 id="chat-heading">AI Support</h2>
                        <p>Powered by DS</p>
                    </div>
                </div>
                <div class="help-chat__info">
                    <p>Our AI assistant can help with orders, shipping, returns, sizing, and product questions.</p>
                    <ul>
                        <li><i data-lucide="check-circle-2"></i> Instant replies</li>
                        <li><i data-lucide="check-circle-2"></i> 24/7 available</li>
                        <li><i data-lucide="check-circle-2"></i> Secure &amp; private</li>
                    </ul>
                </div>
                <div class="help-chat__status">
                    <span class="help-chat__dot"></span> Online now
                </div>
            </div>
            <div class="help-chat__body">
                <div class="help-chat__window" data-help-chat-window>
                    <div class="help-chat__messages" data-help-chat-messages>
                        <div class="help-chat__message help-chat__message--assistant">
                            <span class="help-chat__avatar"><i data-lucide="bot"></i></span>
                            <div class="help-chat__bubble">
                                <p>Hi there! I am The DS AI assistant. How can I help you today?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <form class="help-chat__input" data-help-chat-form>
                    <?= csrfField(); ?>
                    <input
                        type="text"
                        data-help-chat-input
                        placeholder="Type your question..."
                        aria-label="Chat message"
                        autocomplete="off"
                        maxlength="500"
                    >
                    <button type="submit" aria-label="Send message">
                        <i data-lucide="send"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="help-cta" aria-labelledby="help-cta-heading">
        <div class="help-cta-card">
            <h2 id="help-cta-heading">Still need help?</h2>
            <p>Our human support team is ready to assist you with anything the AI could not resolve.</p>
            <div class="help-cta-actions">
                <button type="button" class="help-cta-btn help-cta-btn--primary" id="help-request-open">
                    <i data-lucide="file-text"></i> Send Request
                </button>
                <a href="mailto:thedaservice@store.com" class="help-cta-btn">
                    <i data-lucide="mail"></i> Email Support
                </a>
                <a href="tel:+855112233" class="help-cta-btn">
                    <i data-lucide="phone"></i> Call Us
                </a>
            </div>
        </div>
    </section>

    <!-- Request Modal -->
    <div class="admin-product-overlay" id="help-request-overlay" aria-hidden="true">
        <div class="admin-product-modal" role="dialog" aria-modal="true" aria-labelledby="help-request-modal-title">
            <button type="button" class="admin-product-modal__close" id="help-request-modal-close" aria-label="Close">
                <i data-lucide="x"></i>
            </button>
            <div class="admin-product-modal__content">
                <div style="text-align:center;margin-bottom:22px;">
                    <h2 id="help-request-modal-title" style="margin:0 0 6px;">Send a Personal Request</h2>
                    <p class="pixel-note" style="margin:0;">Describe what you need and we will review it as soon as possible.</p>
                </div>
                <form class="help-request-form" id="help-request-form" action="submit-request.php" method="post" enctype="multipart/form-data">
                    <?= csrfField(); ?>
                    <div class="help-request-form__group">
                        <label class="help-request-form__label" for="request-email">Email</label>
                        <input class="help-request-form__input" id="request-email" name="email" type="email" value="<?= htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" placeholder="you@example.com" required>
                    </div>
                    <div class="help-request-form__group">
                        <label class="help-request-form__label" for="request-phone">Phone</label>
                        <input class="help-request-form__input" id="request-phone" name="phone" type="tel" value="" placeholder="+855 112 233" required>
                    </div>
                    <div class="help-request-form__group">
                        <label class="help-request-form__label" for="request-subject">Subject</label>
                        <input class="help-request-form__input" id="request-subject" name="subject" type="text" maxlength="120" placeholder="What is this about?" required>
                    </div>
                    <div class="help-request-form__group">
                        <label class="help-request-form__label" for="request-message">Message</label>
                        <textarea class="help-request-form__textarea" id="request-message" name="message" rows="4" maxlength="2000" placeholder="Tell us the details..." required></textarea>
                    </div>
                    <div class="help-request-form__group">
                        <label class="help-request-form__label" for="request-file">Attachment (optional)</label>
                        <div class="help-request-form__file">
                            <span class="help-request-form__file-icon"><i data-lucide="upload-cloud"></i></span>
                            <span class="help-request-form__file-text" id="request-file-text">Click to choose a file</span>
                            <span class="help-request-form__file-meta">Images & PDF only</span>
                            <input id="request-file" name="attachment" type="file" accept="image/*,application/pdf" aria-label="Attachment">
                        </div>
                    </div>
                    <div class="help-request-form__actions">
                        <button type="submit" class="help-request-form__submit">Submit Request</button>
                    </div>
                    <p class="help-request__success" id="request-success" hidden><i data-lucide="check-circle-2"></i> Your request has been sent successfully.</p>
                    <p class="help-request__error" id="request-error" hidden></p>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="../assets/js/app.js?v=26"></script>
<script>
(function () {
    var $form = $('[data-help-chat-form]');
    var $input = $('[data-help-chat-input]');
    var $messages = $('[data-help-chat-messages]');
    var $window = $('[data-help-chat-window]');
    var csrfToken = $form.find('input[name="csrf_token"]').val();

    var scrollToBottom = function () {
        if ($window.length) {
            $window[0].scrollTop = $window[0].scrollHeight;
        }
    };

    var escapeHtml = function (text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    };

    var appendMessage = function (role, text) {
        var isUser = role === 'user';
        var icon = isUser ? 'user-round' : 'bot';
        var html = '<div class="help-chat__message help-chat__message--' + role + '">' +
            '<span class="help-chat__avatar"><i data-lucide="' + icon + '"></i></span>' +
            '<div class="help-chat__bubble"><p>' + escapeHtml(text) + '</p></div></div>';
        $messages.append(html);
        if (window.lucide) {
            window.lucide.createIcons();
        }
        scrollToBottom();
    };

    var setLoading = function (isLoading) {
        $form.toggleClass('is-loading', isLoading);
        $input.prop('disabled', isLoading);
        if (isLoading) {
            var typingHtml = '<div class="help-chat__message help-chat__message--assistant" data-typing-indicator>' +
                '<span class="help-chat__avatar"><i data-lucide="bot"></i></span>' +
                '<div class="help-chat__bubble">' +
                '<span class="help-chat__typing"><span></span><span></span><span></span></span>' +
                '</div></div>';
            $messages.append(typingHtml);
            if (window.lucide) {
                window.lucide.createIcons();
            }
            scrollToBottom();
        } else {
            $('[data-typing-indicator]').remove();
        }
    };

    $form.on('submit', function (event) {
        event.preventDefault();
        var message = $input.val().trim();
        if (!message) return;

        appendMessage('user', message);
        $input.val('');
        setLoading(true);

        $.ajax({
            url: 'chat-api.php',
            type: 'POST',
            data: { message: message, csrf_token: csrfToken },
            dataType: 'json',
        }).done(function (response) {
            setLoading(false);
            if (response.reply) {
                appendMessage('assistant', response.reply);
            } else if (response.error) {
                appendMessage('assistant', 'Sorry: ' + response.error);
            }
        }).fail(function (xhr) {
            setLoading(false);
            var errorText = 'Something went wrong. Please try again later.';
            try {
                var resp = JSON.parse(xhr.responseText);
                if (resp.error) errorText = resp.error;
            } catch (e) {}
            appendMessage('assistant', 'Sorry: ' + errorText);
        });
    });

    // Personal Request form
    var $requestForm = $('#help-request-form');
    var $requestSuccess = $('#request-success');
    var $requestError = $('#request-error');
    var $requestFileInput = $('#request-file');
    var $requestFileText = $('#request-file-text');

    if ($requestFileInput.length && $requestFileText.length) {
        $requestFileInput.on('change', function () {
            $requestFileText.text(this.files[0]?.name || 'Choose file');
        });
    }

    $requestForm.on('submit', function (event) {
        event.preventDefault();
        $requestSuccess.prop('hidden', true);
        $requestError.prop('hidden', true);

        var formData = new FormData(this);
        var $submitBtn = $requestForm.find('.help-request-form__submit');
        $submitBtn.prop('disabled', true).text('Sending...');

        $.ajax({
            url: 'submit-request.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
        }).done(function (response) {
            $submitBtn.prop('disabled', false).text('Submit Request');
            if (response.success) {
                $requestForm[0].reset();
                $requestFileText.text('Click to choose a file');
                $requestSuccess.prop('hidden', false);
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            } else {
                $requestError.text(response.error || 'Something went wrong. Please try again.').prop('hidden', false);
            }
        }).fail(function (xhr) {
            $submitBtn.prop('disabled', false).text('Submit Request');
            var errorText = 'Something went wrong. Please try again later.';
            try {
                var resp = JSON.parse(xhr.responseText);
                if (resp.error) errorText = resp.error;
            } catch (e) {}
            $requestError.text(errorText).prop('hidden', false);
        });
    });

    // Request modal
    var $requestOverlay = $('#help-request-overlay');
    var $requestOpenBtn = $('#help-request-open');
    var $requestCloseBtn = $('#help-request-modal-close');

    function openRequestModal() {
        $requestOverlay.addClass('is-open').attr('aria-hidden', 'false');
        $('body').css('overflow', 'hidden');
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    function closeRequestModal() {
        $requestOverlay.removeClass('is-open').attr('aria-hidden', 'true');
        $('body').css('overflow', '');
    }

    $requestOpenBtn.on('click', openRequestModal);
    $requestCloseBtn.on('click', closeRequestModal);

    $requestOverlay.on('click', function (e) {
        if (e.target === this) {
            closeRequestModal();
        }
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $requestOverlay.hasClass('is-open')) {
            closeRequestModal();
        }
    });

    scrollToBottom();

    // FAQ search
    var $faqSearch = $('#faq-search');
    var $faqItems = $('.help-faq-item');
    var $faqEmpty = $('[data-faq-empty]');

    $faqSearch.on('input', function () {
        var query = $(this).val().trim().toLowerCase();
        var visibleCount = 0;
        $faqItems.each(function () {
            var $item = $(this);
            var text = $item.text().toLowerCase();
            var matches = query === '' || text.includes(query);
            $item.toggleClass('is-hidden', !matches);
            if (matches) visibleCount++;
        });
        $faqEmpty.prop('hidden', visibleCount > 0);
    });
})();
</script>
</body>
</html>
