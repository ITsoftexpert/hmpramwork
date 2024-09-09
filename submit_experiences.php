<?php
session_start();

require_once("includes/db.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $experiences = json_decode($_POST['experiences'], true);

    // Process the data (e.g., save to the database)
    foreach ($experiences as $experience) {
        // Example: Save to the database
        // $job_title = $experience['job_title'];
        // $start_from = $experience['start_from'];
        // $end_to = $experience['end_to'];
        // $organization = $experience['organization'];
        // $description = $experience['description'];
        // Save to your database
        $job_title = $experience['job_title'];
        $organization = $experience['organization'];
        $start_from = $experience['start_from'];
        $still_working = $experience['still_working'];
        $end_to = $experience['end_to'];
        $description = $experience['description'];
        $seller_id = $login_seller_id;

        $add_experience = $db->insert("past_experience", array("job_title" => $job_title, "organization" => $organization, "start_from" => $start_from, "still_working" => $still_working, "end_to" => $end_to, "description" => $description, "seller_id" =>  $seller_id));

        if ($add_experience) {
            echo "<p>Experience added successfully!</p>";
        } else {
            echo "<p>Failed to add experience. Please try again.</p>";
        }
    }

    // Respond to the AJAX request
    echo json_encode(['status' => 'success']);
}
