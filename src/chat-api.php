<?php

require 'includes/security.php';
startSecureSession();

header('Content-Type: application/json');

$token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['csrf_token'] ?? '');
if (!validateCsrfToken($token)) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid CSRF token.']);
    exit;
}

$message = trim(getPost('message'));
if ($message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required.']);
    exit;
}

$apiKey = env('AI_API_KEY', '');
if ($apiKey === '') {
    http_response_code(503);
    echo json_encode(['error' => 'AI service is not configured. Please add an API key to the environment.']);
    exit;
}

$_SESSION['chat_count'] = ($_SESSION['chat_count'] ?? 0) + 1;
if ($_SESSION['chat_count'] > 20) {
    http_response_code(429);
    echo json_encode(['error' => 'Message limit reached. Please start a new session.']);
    exit;
}

if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [];
}

$_SESSION['chat_history'][] = ['role' => 'user', 'content' => $message];

$messages = array_slice($_SESSION['chat_history'], -10);

$systemPrompt = <<<'PROMPT'
You are the AI support assistant for The DS, a premium e-commerce store based in Phnom Penh, Cambodia.

Store facts:
- Name: The DS
- Location: Phnom Penh, Cambodia
- Contact: +855 112 233, thedaservice@store.com
- Brands sold: Nike, Prada, Balenciaga, Ralph Lauren, Puma, Chanel, Gucci, Adidas, Venezianico
- Product categories: Clothes, Perfumes, Accessories, Bags, Sneakers, Premium
- Return policy: 30 days, unused/unworn, original packaging, tags attached. Refunds in 5-10 business days.
- Shipping: Orders ship within 1-3 business days. Delivery time varies by location.
- Payments: KHQR, debit card, and other checkout methods.
- Authenticity: 100% authentic products sourced from brand-authorized distributors.
- Terms page: /src/terms.php

Guidelines:
- Be concise, friendly, and professional.
- Answer questions about products, shipping, returns, sizing, payments, and account issues.
- If you do not know the answer, direct the user to email thedaservice@store.com or call +855 112 233.
- Do not make up specific product prices or stock levels. General info only.
- Keep responses short (2-4 sentences when possible).
PROMPT;

$payload = [
    'model' => 'claude-3-5-sonnet-20241022',
    'max_tokens' => 512,
    'system' => $systemPrompt,
    'messages' => $messages,
];

$ch = curl_init('https://api.anthropic.com/v1/messages');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'x-api-key: ' . $apiKey,
        'anthropic-version: 2023-06-01',
    ],
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError !== '') {
    http_response_code(502);
    echo json_encode(['error' => 'Unable to reach AI service. Please try again later.']);
    exit;
}

$data = json_decode($response, true);

if ($httpCode !== 200 || empty($data['content'][0]['text'])) {
    $errorMessage = $data['error']['message'] ?? 'AI service returned an error. Please try again later.';
    http_response_code(502);
    echo json_encode(['error' => $errorMessage]);
    exit;
}

$reply = $data['content'][0]['text'];
$_SESSION['chat_history'][] = ['role' => 'assistant', 'content' => $reply];

echo json_encode(['reply' => $reply]);
exit;
