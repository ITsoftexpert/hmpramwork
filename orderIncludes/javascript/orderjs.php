<script>
  function matchWords(input) {
    var value = $(input).val();
    $.ajax({
      url: "conversations/match_words",
      method: "POST",
      data: {
        value: value
      },
      success: function(val) {
        if (val == "match") {
          $('#spamWords').removeClass("d-none");
        } else {
          $('#spamWords').addClass("d-none");
        }
      }
    });
  }

  $(document).ready(function() {

    // Sticky Code start //
    $("#order-status-bar").sticky({
      topSpacing: 0,
      zIndex: 5
    });
    // Sticky code ends //
    <?php if ($order_status != "completed" && $order_status != "pending") { ?>
      ////  Countdown Timer Code Starts  ////
      // Set the initial date we're counting down to
      var countDownDate = new Date("<?= $order_time; ?>").getTime();

      // Function to update countdown
      function updateCountdown() {
        var now = new Date();
        var nowUTC = new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), now.getUTCHours(), now.getUTCMinutes(), now.getUTCSeconds());
        var distance = countDownDate - nowUTC;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the countdown
        document.getElementById("days").innerHTML = days;
        document.getElementById("hours").innerHTML = hours;
        document.getElementById("minutes").innerHTML = minutes;
        document.getElementById("seconds").innerHTML = seconds;

        // Check if countdown is over
        if (distance < 0) {
          clearInterval(x);
          // Display appropriate message
          <?php if (isset($_GET["selling_order"])) { ?>
            document.getElementById("countdown-heading").innerHTML = "You Failed To Deliver Your Order On Time";
          <?php } elseif (isset($_GET["buying_order"])) { ?>
            document.getElementById("countdown-heading").innerHTML = "Your Seller Failed To Deliver Your Order On Time";
          <?php } ?>
          // Add CSS class for styling
          $("#countdown-timer .countdown-number").addClass("countdown-number-late");
          // Additional message if needed
          // Start countdown for $order_time_extend if available
          if ("<?= $order_time_extend; ?>") {
            countDownDate = new Date("<?= $order_time_extend; ?>").getTime();
            x = setInterval(updateCountdown, 1000); // Restart countdown with new date
          } else {
            document.getElementById("days").innerHTML = "<span class='red-color'>The</span>";
            document.getElementById("hours").innerHTML = "<span class='red-color'>Order</span>";
            document.getElementById("minutes").innerHTML = "<span class='red-color'>is</span>";
            document.getElementById("seconds").innerHTML = "<span class='red-color'>Late!</span>";

            document.getElementById("order_cancelation_action").style.display = "block";
          }

        }
      }

      // Update countdown every second
      var x = setInterval(updateCountdown, 1000);
      ////  Countdown Timer Code Ends  //// 
    <?php } ?>


    $('#insert-message-form').submit(function(e) {
      e.preventDefault();
      var form_data = new FormData(this);
      form_data.append('order_id', <?= $order_id; ?>);
      $("#insert-message-form button[type='submit']").html("<i class='fa fa-spinner fa-pulse fa-lg fa-fw'></i>");
      $.ajax({
        method: "POST",
        url: "orderIncludes/insert_order_message",
        data: form_data,
        cache: false,
        contentType: false,
        processData: false
      }).done(function(data) {
        $('#message_data_div').html(data);
        $("#insert-message-form button[type='submit']").html("Send");
        $("#insert-message-form").trigger("reset");
      });
    });

    setInterval(function() {
      order_id = "<?= $order_id; ?>";
      $.ajax({
        method: "GET",
        url: "orderIncludes/order_conversations",
        data: {
          order_id: order_id
        }
      }).done(function(data) {
        $("#order-conversations").empty();
        $("#order-conversations").append(data);
      });
    }, 1000);

  });
</script>