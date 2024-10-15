<?php
// Function to generate random background color

// Function to generate the email body with job data
function generate_email_body($data, $jobs) {
    $email_body = "
    <html>
    <head>
        <title>{$data['subject']}</title>
    </head>
    <body style='font-family: Arial, sans-serif; color: #333;'>
        <div class='box' align='center'>
            <div class='container' style='max-width: 632px; margin: 0 auto; padding: 0px;'>
               

                <div class='row' style='margin-top: 20px;'>
                    <h3 style='text-align: left;'>Recent Jobs</h3>";
    
    // Loop through jobs to display them as cards
    foreach ($jobs as $job) {
        // Get the first letter of the username
        $first_letter = strtoupper(substr($job['user_name'], 0, 1));

        // Generate a random background color for the avatar
        $bg_color = get_random_color();

        $email_body .= "
        <div class='job-card' style='border: 1px solid #ddd; padding: 15px; margin-bottom: 10px;'>
            <div class='job-header' style='display: flex; align-items: center;'>
                <!-- User's Avatar (Initial with Random Color) -->
                <div class='avatar' style='
                    width: 50px;
                    height: 50px;
                    background-color: {$bg_color};
                    color: white;
                    font-size: 24px;
                    font-weight: bold;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    border-radius: 50%;
                    margin-right: 15px;
                '>
                    {$first_letter}
                </div>
                <div class='job-details'>
                    <h4 style='margin: 0;'>{$job['request_title']}</h4>
                    <p style='margin: 0; font-size: 14px;'>Budget: {$job['request_budget']} | Delivery: {$job['delivery_time']} days</p>
                </div>
            </div>
            <p style='font-size: 14px; text-align: left;'>{$job['request_description']}</p>
            <a href='{$job['request_link']}' style='color: {$data['site_color']}; text-decoration: none;'>View Details</a>
        </div>";
    }

    $email_body .= "
                </div>
            </div>
        </div>
    </body>
    </html>";

    return $email_body;
}

// Example data for jobs
$jobs = [
    [
        'user_name' => 'John Doe',
        'request_title' => 'Website Design Project',
        'request_description' => 'Looking for a professional web designer to create a responsive website.',
        'request_budget' => '$500',
        'delivery_time' => '10',
        'request_link' => 'https://hiremyprofile.com/request/1'
    ],
    [
        'user_name' => 'Alice Smith',
        'request_title' => 'Mobile App Development',
        'request_description' => 'Need an Android and iOS app developed for my e-commerce business.',
        'request_budget' => '$1000',
        'delivery_time' => '15',
        'request_link' => 'https://hiremyprofile.com/request/2'
    ]
];

// Example use
$data = [
    'subject' => 'Recent Jobs on Hiremyprofile.com',
    'project_post_url' => 'https://hiremyprofile.com/post',
    'site_color' => '#4CAF50', // Button and link color
];

$email_body = generate_email_body($data, $jobs);

// Output the email body (for testing, use this in your send_mail function)
echo $email_body;
?>
