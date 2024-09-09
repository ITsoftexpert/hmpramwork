<?php if ($proposal_price != 0) {
    $price = $proposal_price;
} ?>
<?php if (!isset($_SESSION['seller_user_name'])) { ?>
    <!--  <a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-order primary mb-3">-->
    <!--    <i class="fa fa-shopping-cart"></i> &nbsp;<strong>Add to Cart</strong>-->
    <!--  </a>-->
    <?php if ($row_proposal->direct_order == 2) { ?>
        <a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-order">
            <strong>Order Now (<?= showPrice($price, "total-price"); ?>)</strong>
        </a>
    <?php } else { ?>
        <?php
        if (!isset($_SESSION['seller_user_name'])) { ?>
            <a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-lg btn-block btn-success rounded-0 box-shadow-cheader">Message me</a>
        <?php } else { ?>
            <a href="<?= $site_url ?>/conversations/message?seller_id=<?= $proposal_seller_id; ?>" class="btn btn-lg btn-block btn-success rounded-0 box-shadow-cheader">Message me</a>
    <?php }
    }
} else { ?>
    <?php if ($proposal_seller_user_name == @$_SESSION['seller_user_name']) { ?>
        <a class="btn btn-order" href="../edit_proposal.php?proposal_id=<?= $proposal_id; ?>">
            <i class="fa fa-edit"></i> Edit Proposal
        </a>
    <?php } else { ?>
        <!--  --><?php //if($countcart == 1){ 
                    ?>
        <!--  <button type="button" class="btn btn-order primary added mb-3">-->
        <!--    <i class="fa fa-shopping-cart"></i> &nbsp;<strong>Already Added</strong>-->
        <!--  </button>-->
        <!--  --><?php //}else{ 
                    ?>
        <!--  <button type="submit" name="add_cart" value="1" class="btn btn-order primary mb-3">-->
        <!--   <i class="fa fa-shopping-cart"></i> &nbsp;<strong>Add to Cart</strong>-->
        <!--  </button>-->
        <!--  --><?php //} 
                    ?>

        <?php if ($row_proposal->direct_order == 2) { ?>
            <button type="submit" name="add_order" value="1" class="btn btn-order">
                <!-- <strong>Order Now (<?= $s_currency; ?><span class="<?= $priceClass; ?>"><?= $price; ?></span>)</strong> -->
                <strong>Order Now (<?= showPrice($price, $priceClass); ?>)</strong>
            </button>
        <?php } else { ?>
            <a href="../../conversations/message?seller_id=<?= $proposal_seller_id; ?>" class="btn btn-lg btn-block btn-success rounded-0 box-shadow-cheader">Message me</a>
<?php }
    }
} ?>