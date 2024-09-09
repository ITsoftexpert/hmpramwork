<style>
	@media (max-width:768px) {
		.badge-float-right {
			float: right;
			margin-top: -3px;
			padding-top: 5px;
			margin-right: -9px !important;
		}

		.font-size-3 {
			font-size: 13px !important;
			padding: 10px !important;
			text-align: center;
		}
	}
@media(min-width:550px) and (max-width:768px){
	.width-increase {
			/* border:1px solid green; */
			width: 150px;
			text-align: center;
		}
	}
	@media(min-width:768px) {
		.width-increase {
			/* border:1px solid green; */
			width: 170px;
			text-align: center;
		}
	}

	.badge-float-right {
		float: right;
		margin-top: -3px;
		padding-top: 5px;
		margin-right: -9px !important;
	}

	.padding-13 {
		padding: 9px 15px;
	}

	.nav-tabs .respo_drop_menu {
		z-index: 10;
	}

	.font-size-3 {
		
		padding: 13px !important;
		text-align: center;
		
	}

	@media (max-width:768px) {
    .respo_drop_menu .respo_drop_item.active {
        background-color: #00CEDC;
        color: white;
    }
}

/* Style for active tab in larger screens */
@media (min-width:768px) {
    .nav-tabs .nav-item .nav-link.active {
        background-color: #00CEDC;
        color: white;
    }
}

#navbarDropdown{
	color: white !important;
	background-color: #00CEDC !important;
	width: fit-content;
	/* margin-left: auto; */
	
}

</style>
<ul class="nav nav-tabs flex-column flex-sm-row box-shadow-buyer-order">
    <!-- Dropdown for smaller screens -->
    <li class="nav-item dropdown d-block d-sm-none">
        <a class="nav-link respo_drop_toggle make-black padding-13 text-blue" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= $lang['tabs']['active']; ?>
        </a>
        <div class="respo_drop_menu" aria-labelledby="navbarDropdown">
            <?php
            $statuses = ['active', 'delivered', 'completed', 'cancelled', 'all'];
            foreach ($statuses as $status) {
                $count_orders = $db->count("orders", array("buyer_id" => $login_seller_id, "order_status" => $status));
                $label = $lang['tabs'][$status];
                $active_class = $status == 'active' ? 'active' : '';
                echo "<a class='respo_drop_item $active_class' href='#$status' data-toggle='tab'>$label <span class='badge badge-success'>$count_orders</span></a>";
            }
            ?>
        </div>
    </li>
    
    <!-- Tabs for larger screens -->
    <?php
    $statuses = ['active', 'delivered', 'completed', 'cancelled', 'all'];
    foreach ($statuses as $status) {
        $count_orders = $db->count("orders", array("buyer_id" => $login_seller_id, "order_status" => $status));
        $label = $lang['tabs'][$status];
        $active_class = $status == 'active' ? 'active' : '';
        echo "<li class='nav-item width-increase d-none d-sm-block'>
            <a href='#$status' data-toggle='tab' class='nav-link $active_class make-black padding-13'>
                $label <span class='badge badge-success badge-float-right'>$count_orders</span>
            </a>
        </li>";
    }
    ?>
</ul>
<div class="tab-content">
	<div class="tab-pane fade show active" id="active">
		<?php require_once("manage_orders/order_active_buying.php") ?>
	</div>

	<div class="tab-pane" id="delivered">
		<?php require_once("manage_orders/order_delivered_buying.php") ?>
	</div>

	<div class="tab-pane" id="completed">
		<?php require_once("manage_orders/order_completed_buying.php") ?>
	</div>
	<div class="tab-pane" id="cancelled">
		<?php require_once("manage_orders/order_cancelled_buying.php") ?>
	</div>
	<div class="tab-pane" id="all">
		<?php require_once("manage_orders/order_all_buying.php") ?>
	</div>
</div>

<script>
    // Document ready function
    document.addEventListener("DOMContentLoaded", function() {
        // Get all dropdown items
        var dropdownItems = document.querySelectorAll(".respo_drop_menu .respo_drop_item");

        // Add click event listener to each dropdown item
        dropdownItems.forEach(function(item) {
            item.addEventListener("click", function() {
                // Get the clicked item's label
                var selectedText = this.textContent.trim();

                // Set the dropdown button's label to the selected item's label
                var dropdownButton = document.getElementById("navbarDropdown");
                dropdownButton.textContent = selectedText;
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Handle dropdown item click
        $('.respo_drop_menu .respo_drop_item').on('click', function() {
            // Close the dropdown menu
            $('.respo_drop_menu').removeClass('show');
        });
        
        // Handle dropdown toggle click
        $('.respo_drop_toggle').on('click', function() {
            // Toggle the dropdown menu
            $('.respo_drop_menu').toggleClass('show');
        });
    });
</script>

