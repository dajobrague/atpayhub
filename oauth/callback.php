<?php
// Check if the "code" parameter is present in the URL
if (isset($_GET['code'])) {
    $auth_code = $_GET['code'];

    // Set up Stripe API credentials
    $client_id = 'ca_R8lPy1MMdeMlEqv5OwDjF1UzfOJnmUbV';
    $client_secret = process.env.STRIPE_KEY;

    // Prepare data for the request to get the access_token
    $data = array(
        'client_secret' => $client_secret,
        'code' => $auth_code,
        'grant_type' => 'authorization_code',
        'client_id' => $client_id
    );

    // Initialize cURL request to Stripe
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://connect.stripe.com/oauth/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response from Stripe
    $response_data = json_decode($response, true);

    // Check if an access_token was received
    if (isset($response_data['access_token'])) {
        $access_token = $response_data['access_token'];
        $stripe_user_id = $response_data['stripe_user_id'];

        // Supabase API credentials
        $supabase_url = process.env.SUPABASE_URL;
        $supabase_key = process.env.SUPABASE_KEY;

        // Data to insert in Supabase
        $data = json_encode(array(
            "customer_id" => "123e4567-e89b-12d3-a456-426614174000",
            "stripe_user_id" => $stripe_user_id,
            "access_token" => $access_token
        ));

        // Initialize cURL to send data to Supabase
        $ch = curl_init("$supabase_url/rest/v1/stripe_connections");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            "Authorization: Bearer $supabase_key",
            "apikey: $supabase_key" // Add apikey header for Supabase
        ));

        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get HTTP response code for debugging
        curl_close($ch);

        // Output result and HTTP response code for debugging
        echo "HTTP Code: " . $http_code . "<br>";
        echo "Result from Supabase: " . $result;

        if ($http_code === 201) {
            echo "Access token successfully saved in Supabase.";
        } else {
            echo "Failed to save access token in Supabase.";
        }
    } else {
        echo "Error retrieving access token: " . json_encode($response_data);
    }
} else {
    echo "No authorization code received.";
}
?>