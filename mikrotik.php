function openai_generate_text2() {
// Get the topic from the AJAX request
$topic = $_POST['prompt'];
$prompt = "Anda adalah senior mikrotik engineer dengan pengalaman lebih dari 10 tahun di mikrotik router dan jaringan komputer sehingga anda sangat paham mengenai permasalahan terkait router mikrotik dan jaringan internet bantu saya berikan saran dan solusi terkait permasalahan jaringan dan router mikrotik mengenai topik " . $topic. "Tulis dengan gaya penulisan singkat dan padat dengan outputnya dalam bahasa indonesia";

// OpenAI API URL and key
$api_url = 'https://api.openai.com/v1/chat/completions';
$api_key = 'sk-JKakSg3ru2ywhM3sdSc94T3Bl2887alsisjeKA22'; // Replace with your actual OpenAI API key

// Headers for the OpenAI API
$headers = [
'Content-Type' => 'application/json',
'Authorization' => 'Bearer ' . $api_key
];

// Body for the OpenAI API
$body = [
'model' => 'gpt-3.5-turbo',
'messages' => [['role' => 'user', 'content' => $prompt]],
'temperature' => 0.7
];

// Args for the WordPress HTTP API
$args = [
'method' => 'POST',
'headers' => $headers,
'body' => json_encode($body),
'timeout' => 120
];

// Send the request
$response = wp_remote_request($api_url, $args);

// Handle the response
if (is_wp_error($response)) {
$error_message = $response->get_error_message();
wp_send_json_error("Something went wrong: $error_message");
} else {
$body = wp_remote_retrieve_body($response);
$data = json_decode($body, true);

if (json_last_error() !== JSON_ERROR_NONE) {
wp_send_json_error('Invalid JSON in API response');
} elseif (!isset($data['choices'])) {
wp_send_json_error('API request failed. Response: ' . $body);
} else {
wp_send_json_success($data);
}
}
// Always die in functions echoing AJAX content
wp_die();
}

add_action('wp_ajax_openai_generate_text2', 'openai_generate_text2');
add_action('wp_ajax_nopriv_openai_generate_text2', 'openai_generate_text2');