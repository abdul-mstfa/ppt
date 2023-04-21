<?php
if (isset($_POST['issue'])) {
    $issue = $_POST['issue'];
    
    // Replace with your WHMCS API credentials and URL
    $api_identifier = 'MJ6x12vcR3MU9KYVWR7kbMqsw9AQmMJu';
    $api_secret = 'ub2CSjvkYGtnGEU0nBDBfCG5ST3e86mq';
    $whmcs_url = 'https://legacygroups.com/billing/includes/api.php';
    
    // Replace with your client, department, and subject information
    $client_id = 76;
    $department_id = 2;
    $subject = 'Support Request';

    $postData = array(
        'identifier' => $api_identifier,
        'secret' => $api_secret,
        'action' => 'OpenTicket',
        'clientid' => $client_id,
        'deptid' => $department_id,
        'subject' => $subject,
        'message' => $issue,
        'priority' => 'Medium',
        'responsetype' => 'json',
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $whmcs_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $responseData = json_decode($response, true);
    if ($responseData['result'] === 'success') {
        echo 'Ticket submitted successfully. Ticket ID: ' . $responseData['tid'];
    } else {
        echo 'Error: ' . $responseData['message'];
    }
} else {
    echo 'Error: Invalid request.';
}
?>
