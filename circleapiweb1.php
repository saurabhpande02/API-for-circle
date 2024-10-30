<?php

$file = 'filefordata.txt';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postData = file_get_contents('php://input');

    // Log the received data
    file_put_contents($file, $postData . PHP_EOL, FILE_APPEND | LOCK_EX);

    // Add a timestamp to the log
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($file, "Data received at: $timestamp" . PHP_EOL, FILE_APPEND | LOCK_EX);

    // Extract community_member_id from the POST data
    $postArray = json_decode($postData, true);
    $community_member_id = isset($postArray['data']['community_member_id']) ? $postArray['data']['community_member_id'] : null;

    if ($community_member_id === null) {
        echo 'Error: community_member_id is missing.';
        exit();
    }

    echo "Success: community_member_id is $community_member_id";


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://app.circle.so/api/v1/community_members/$community_member_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
        ),
    ));

    $response = curl_exec($curl);

    if ($response === false) {
        echo 'Curl error: ' . curl_error($curl);
        curl_close($curl);
        exit();
    } else {
        curl_close($curl);

        // Decode JSON response
        $data = json_decode($response, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            // Check if the required keys exist
            if (isset($data['first_name']) && isset($data['last_name']) && isset($data['email'])) {
                $first_name = $data['first_name'];
                $last_name = $data['last_name'];
                $email = $data['email'];

                // Display the details
                echo 'First Name: ' . $first_name . "\n";
                echo 'Last Name: ' . $last_name . "\n";
                echo 'Email: ' . $email . "\n";

                // Prepare data for HubSpot
                $hubspot_url = 'https://api.hsforms.com/submissions/v3/integration/submit/XXXXXXXXX/XXXXXXXXXXX-XXXXXXXXXXX-XXXXXXXXXX';
                $hubspot_data = array(
                    "fields" => array(
                        array(
                            "name" => "firstname",
                            "value" => $first_name
                        ),
                        array(
                            "name" => "lastname",
                            "value" => $last_name
                        ),
                        array(
                            "name" => "email",
                            "value" => $email
                        ),
                        array(
                            "name" => "leadsource",
                            "value" => "XXXXXXXX"
                        ),
                        array(
                            "name" => "lead_source_detail",
                            "value" => "XXXXXXXXXXXXXXXXXXXXXXX"
                        )
                    )
                );

                $json_data = json_encode($hubspot_data);

                $hubspot_curl = curl_init();

                curl_setopt_array($hubspot_curl, array(
                    CURLOPT_URL => $hubspot_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $json_data,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));

                $hubspot_response = curl_exec($hubspot_curl);

                if ($hubspot_response === false) {
                    echo 'HubSpot Curl error: ' . curl_error($hubspot_curl);
                } else {
                    echo 'HubSpot Response: ' . $hubspot_response . "\n";
                }

                curl_close($hubspot_curl);

            } else {
                echo 'Error: Required fields are missing in the response.';
            }
        } else {
            echo 'Error decoding JSON response: ' . json_last_error_msg();
        }
    }

    echo "Data received and saved.";
} else {
    echo "No data received.";
}
?>