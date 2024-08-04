<?php
/*
Plugin Name: OneSignal Gravity Form Integration
Description: Send OneSignal notifications when a Gravity Form is submitted.
Version: 1.0
Author: Luveedu
Website: https://www.luveedu.com
*/

// Install Onesignal PHP SDK from their Site.
// Include the OneSignal SDK
require_once(plugin_dir_path(__FILE__) . 'onesignal-php-sdk/vendor/autoload.php');

// Hook into Gravity Forms submission
add_action('gform_after_submission', 'send_onesignal_notification', 10, 2);

function send_onesignal_notification($entry, $form) {
    // Get the OneSignal App ID and REST API Key from your OneSignal account
    $app_id = 'YOUR_ONESIGNAL_APP_ID';
    $rest_api_key = 'YOUR_ONESIGNAL_REST_API_KEY';

    // Initialize the OneSignal client
    $oneSignal = new OneSignal\OneSignal($app_id, $rest_api_key);

    // Find the field IDs for "heading" and "content"
    $heading_field_id = null;
    $content_field_id = null;

    foreach ($form['fields'] as $field) {
        if ($field['label'] === 'heading') {
            $heading_field_id = $field['id'];
        }
        if ($field['label'] === 'content') {
            $content_field_id = $field['id'];
        }
    }

    // Get the submitted form data
    $heading = isset($entry[$heading_field_id]) ? $entry[$heading_field_id] : 'No Heading';
    $content = isset($entry[$content_field_id]) ? $entry[$content_field_id] : 'No Content';

    // Define the notification content
    $notification_content = "Heading: {$heading}\nContent: {$content}";

    // Send the notification
    $oneSignal->sendNotificationCustom([
        'headings' => ['en' => $heading],
        'contents' => ['en' => $content],
        'included_segments' => ['All'],
    ]);
}
