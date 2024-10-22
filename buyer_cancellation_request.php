<div id="response_return"></div> <!-- This will show the result -->

<script>
    // Function to check the time by calling the PHP script
    function checkTime() {
        var sender_id = '<?= $login_seller_id; ?>';
        var receiver_id = '<?= $receiver_id; ?>';
        var order_id = '<?= $order_id; ?>';

        $.ajax({
            url: 'buyer_cancellation_emails', // PHP file that checks the time
            type: 'GET',
            data: {
                sender_id: sender_id,
                receiver_id: receiver_id,
                order_id: order_id,
            }, // Pass the data as an object
            success: function(response) {
                // Update the message div with the result
                $('#response_return').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error); // For debugging any AJAX errors
            }
        });
    }
    // Call the function every 10 seconds to update the time check
    setInterval(checkTime, 1000); // 10000 ms = 10 seconds

    // Call the function immediately when the page loads
    checkTime();
</script>