<?php
$countRequestsActive = $db->count("buyer_requests", array("seller_id" => $login_seller_id, "request_status" => 'active'));
$countRequestsPause = $db->count("buyer_requests", array("seller_id" => $login_seller_id, "request_status" => 'pause'));
$countRequestsPending = $db->count("buyer_requests", array("seller_id" => $login_seller_id, "request_status" => 'pending'));
$countRequestsModification = $db->count("buyer_requests", array("seller_id" => $login_seller_id, "request_status" => 'modification'));
$countRequestsUnapproved = $db->count("buyer_requests", array("seller_id" => $login_seller_id, "request_status" => 'unapproved'));
$display_milestone = $db->select("milestone", array("seller_id" => $login_seller_id));
$milstoneRowCount = $display_milestone->rowCount();

$activeReqTab = isset($_GET['tab']) ? $_GET['tab'] : "approved_request";
?>
<style>
    @media (max-width:768px) {
        .badge-float-right {
            float: right;
            margin-top: -3px;
            padding-top: 5px;
            margin-right: -9px !important;
        }
    }

    @media (min-width:992px) {
        .width-increase1 {
            width: 18.5%;
            margin: 5px 0px;
        }

        .width-increase12 {
            width: 24%;
            margin: 5px 0px;
        }

        .width-increase13 {
            width: 18%;
            margin: 5px 0px;
        }
    }


    .ram_1_dropbtn {
        background-color: #00CEDC;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
    }


    .ram_1_dropdown {
        position: relative;
        display: inline-block;
    }

    .ram_1_dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        top: 100%;
    }


    .ram_1_dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s, color 0.3s;
    }


    .ram_1_dropdown-content a:hover {
        background-color: #00CEDC;
        color: white;
    }


    .ram_1_dropdown.show .ram_1_dropdown-content {
        display: block;
    }


    .ram_1_dropdown.show .ram_1_dropbtn {
        background-color: #00CEDC;
        color: white;
    }


    .tab-content {
        margin-top: 20px;
    }

    .ram_1_dropbtn {
        padding: 6px 16px;
    }

    @media (max-width: 768px) {
        .ram_hide {
            display: none;
        }
    }

    @media (min-width: 768px) {
        #dropdownContainer {
            display: none;
        }
    }
</style>

<div class="ram_1_dropdown" id="dropdownContainer">
    <button class="ram_1_dropbtn"> <?= $lang['tabs']['active_requests']; ?> </button>
    <div class="ram_1_dropdown-content">
        <ul class="nav nav-tabs flex-column flex-sm-row mt-1">
            <li class="nav-item width-increase1">
                <a href="#activeBuyerReq" data-toggle="tab" class="nav-link make-black <?= $activeReqTab == 'approved_request' ? "active" : "" ?> padding-10">
                    <?= $lang['tabs']['active_requests']; ?> <span class="badge badge-success badge-float-right"><?= $countRequestsActive; ?></span>
                </a>
            </li>
            <li class="nav-item width-increase1">
                <a href="#pauseBuyerReq" data-toggle="tab" class="nav-link make-black <?= $activeReqTab == 'pause_request' ? "active" : "" ?> padding-10">
                    <?= $lang['tabs']['pause_requests']; ?> <span class="badge badge-success badge-float-right"><?= $countRequestsPause; ?></span>
                </a>
            </li>
            <li class="nav-item width-increase1">
                <a href="#pendingBuyerReq" data-toggle="tab" class="nav-link make-black <?= $activeReqTab == 'pending_request' ? "active" : "" ?> padding-10">
                    <?= $lang['tabs']['pending_approval']; ?> <span class="badge badge-success badge-float-right"><?= $countRequestsPending; ?></span>
                </a>
            </li>
            <li class="nav-item width-increase12">
                <a href="#modificationBuyerReq" data-toggle="tab" class="nav-link make-black <?= $activeReqTab == 'modification_request' ? "active" : "" ?> padding-10">
                    <?= $lang['tabs']['requires_modification']; ?> <span class="badge badge-success badge-float-right"><?= $countRequestsModification; ?></span>
                </a>
            </li>
            <li class="nav-item width-increase13">
                <a href="#unapprovedBuyerReq" data-toggle="tab" class="nav-link make-black <?= $activeReqTab == 'unapproved_request' ? "active" : "" ?> padding-10">
                    <?= $lang['tabs']['unapproved']; ?> <span class="badge badge-success badge-float-right"><?= $countRequestsUnapproved; ?></span>
                </a>
            </li>
        </ul>
    </div>
</div>

<ul class="nav nav-tabs flex-column flex-sm-row mt-1 ram_hide">
    <li class="nav-item ">
        <a href="#activeBuyerReq" data-toggle="tab" class="nav-link make-black <?= $activeReqTab == 'approved_request' ? "active" : "" ?> padding-10">
            <?= $lang['tabs']['active_requests']; ?> <span class="badge badge-success badge-float-right ml-5"><?= $countRequestsActive; ?></span>
        </a>
    </li>
    <li class="nav-item ">
        <a href="#pauseBuyerReq" data-toggle="tab" class="nav-link make-black <?= $activeReqTab == 'pause_request' ? "active" : "" ?> padding-10">
            <?= $lang['tabs']['pause_requests']; ?> <span class="badge badge-success badge-float-right ml-5"><?= $countRequestsPause; ?></span>
        </a>
    </li>
    <li class="nav-item ">
        <a href="#pendingBuyerReq" data-toggle="tab" class="nav-link make-black <?= $activeReqTab == 'pending_request' ? "active" : "" ?> padding-10">
            <?= $lang['tabs']['pending_approval']; ?> <span class="badge badge-success badge-float-right ml-5"><?= $countRequestsPending; ?></span>
        </a>
    </li>
    <li class="nav-item ">
        <a href="#modificationBuyerReq" data-toggle="tab" class="nav-link make-black <?= $activeReqTab == 'modification_request' ? "active" : "" ?> padding-10">
            <?= $lang['tabs']['requires_modification']; ?> <span class="badge badge-success badge-float-right ml-5"><?= $countRequestsModification; ?></span>
        </a>
    </li>
    <li class="nav-item ">
        <a href="#unapprovedBuyerReq" data-toggle="tab" class="nav-link make-black <?= $activeReqTab == 'unapproved_request' ? "active" : "" ?> padding-10">
            <?= $lang['tabs']['unapproved']; ?> <span class="badge badge-success badge-float-right ml-5"><?= $countRequestsUnapproved; ?></span>
        </a>
    </li>
    <li class="nav-item ">

        <a href="#milestoneBuyerReq" data-toggle="tab" class="nav-link make-black padding-10">
            Milestone <span class="badge badge-success badge-float-right ml-5"><?= $milstoneRowCount; ?></span>
        </a>
    </li>
</ul>

<div class="tab-content mt-4">
    <div id="activeBuyerReq" class="tab-pane fade <?= $activeReqTab == 'approved_request' ? "show active" : "" ?>">
        <?php require_once("manage-requests/active.php") ?>
    </div>
    <div id="pauseBuyerReq" class="tab-pane fade <?= $activeReqTab == 'pause_request' ? "show active" : "" ?>">
        <?php require_once("manage-requests/pause.php") ?>
    </div>
    <div id="pendingBuyerReq" class="tab-pane fade <?= $activeReqTab == 'pending_request' ? "show active" : "" ?>">
        <?php require_once("manage-requests/pending.php") ?>
    </div>
    <div id="modificationBuyerReq" class="tab-pane fade <?= $activeReqTab == 'modification_request' ? "show active" : "" ?>">
        <?php require_once("manage-requests/modification.php") ?>
    </div>
    <div id="unapprovedBuyerReq" class="tab-pane fade <?= $activeReqTab == 'unapproved_request' ? "show active" : "" ?>">
        <?php require_once("manage-requests/unapproved.php") ?>
    </div>
    <div id="milestoneBuyerReq" class="tab-pane">
        <?php require_once("manage-requests/milestone.php") ?>
    </div>
</div>

<!-- Include jQuery if not already included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add JavaScript to update the button text on dropdown item click and handle dropdown visibility -->
<script>
    $(document).ready(function() {
        var dropdownButton = document.querySelector(".ram_1_dropbtn");
        var dropdown = document.querySelector(".ram_1_dropdown");
        var dropdownContent = document.querySelector(".ram_1_dropdown-content");

        dropdownButton.addEventListener("click", function() {
            dropdown.classList.toggle("show");
        });

        dropdownContent.addEventListener("click", function(event) {
            var target = event.target;
            if (target.tagName === 'A') {
                dropdownButton.textContent = target.textContent;
                // Trigger a click event on the corresponding tab
                var tabId = target.getAttribute('href').substring(1);
                var tab = document.getElementById(tabId);
                if (tab) {
                    var tabPane = new bootstrap.Tab(tab);
                    tabPane.show();
                }
                dropdown.classList.remove("show");
            }
        });

    });
</script>

<!-- Bootstrap JavaScript (required for dropdown functionality) -->