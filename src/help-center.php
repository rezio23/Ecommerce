<?php
require 'includes/security.php';
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
    <link rel="stylesheet" href="../assets/css/styles.css?v=100">
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

<?php include 'includes/navbar.php'; ?>
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
            <img src="https://static.vecteezy.com/system/resources/thumbnails/060/178/330/small/bold-neon-geometric-designs-pop-on-these-black-nike-high-tops-the-purple-laces-and-orange-accents-add-a-vibrant-90s-vibe-a-stylish-and-energetic-sneaker-free-png.png" alt="Customer support illustration">
        </figure>
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
                <a href="mailto:thedaservice@store.com" class="help-cta-btn help-cta-btn--primary">
                    <i data-lucide="mail"></i> Email Support
                </a>
                <a href="tel:+855112233" class="help-cta-btn">
                    <i data-lucide="phone"></i> Call Us
                </a>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="../assets/js/app.js?v=23"></script>
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
