<?php
session_start();
require_once("includes/db.php");

if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('login','_self');</script>";
}
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <title> <?= $site_name; ?> - Invoice PDF </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/categories_nav_styles.css" rel="stylesheet">
    <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="styles/owl.carousel.css" rel="stylesheet">
    <link href="styles/owl.theme.default.css" rel="stylesheet">
    <?php if (!empty($site_favicon)) { ?>
        <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
    <link href="styles/sweat_alert.css" rel="stylesheet">
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="js/ie.js"></script>
    <script type="text/javascript" src="js/sweat_alert.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>



</head>

<body class="is-responsive">
    <?php require_once("includes/header.php"); ?>


    <?php
    $select_seller_details = $db->select("sellers", array("seller_id" => $seller_id))->fetch();
    $seller_name = $select_seller_details->seller_name;
    $seller_city = $select_seller_details->seller_city;
    $seller_country = $select_seller_details->seller_country;   

    $get_order_details = $db->select("orders", array("buyer_id" => $seller_id));
    while ($row_order_details = $get_order_details->fetch()) {
        $order_number = $row_order_details->order_number;
        $order_id = $row_order_details->order_id;
        $order_qty = $row_order_details->order_qty;
        $order_duration = $row_order_details->order_duration;
        $order_price = $row_order_details->order_price;
        $get_purchases = $db->select("purchases", array("order_id" => $order_id))->fetch();
        $pay_method = $get_purchases->method;
        $pay_order_id = $get_purchases->order_id;
        $pay_date = $get_purchases->date;
        $pay_reason = $get_purchases->reason;
        $pay_processing_fee = $get_purchases->processing_fee;
        $pay_purchase_id = $get_purchases->purchase_id;
    ?>
 

        <div
            style="width: 800px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <div class="invoice-header"
                style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div style="width: 48%;">
                    <h1
                        style="color: #2e4ba7; font-size: 24px; margin: 0;">Invoice
                        FI46362753314</h1>
                    <small class="text-secondary">Original</small>
                    <p>To<br><?= $seller_name; ?> ( <?= $seller_user_name; ?> )<br>                   
                    <?= $seller_city; ?><br>
                    <?= $seller_country; ?></p>
                </div>
                <div style="width: 48%; text-align: right;">
                    <p><strong>Date issued</strong><br><?= $pay_date; ?></p>
                    <p><strong>Order number</strong><br><a
                            href="#"><?= $order_number; ?></a></p>
                </div>
            </div>

            <table
                style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th
                            style="padding: 10px; border: 1px solid #ccc;">Service</th>
                        <th
                            style="padding: 10px; border: 1px solid #ccc;">Quantity</th>
                        <th style="padding: 10px; border: 1px solid #ccc;">Unit
                            price (USD)</th>
                        <th style="padding: 10px; border: 1px solid #ccc;">Total
                            (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ccc;">Web
                            Analytics</td>
                        <td
                            style="padding: 10px; border: 1px solid #ccc;"><?= $order_qty; ?></td>
                        <td
                            style="padding: 10px; border: 1px solid #ccc;"><?= $order_price; ?></td>
                        <td
                            style="padding: 10px; border: 1px solid #ccc;"><?= $order_price; ?></td>
                    </tr>
                    <tr>
                        <td
                            style="padding: 10px; border: 1px solid #ccc;">Service
                            Fee</td>
                        <td
                            style="padding: 10px; border: 1px solid #ccc;"><?= $order_qty; ?></td>
                        <td
                            style="padding: 10px; border: 1px solid #ccc;"><?= $pay_processing_fee; ?></td>
                        <td
                            style="padding: 10px; border: 1px solid #ccc;"><?= $pay_processing_fee; ?></td>
                    </tr>
                </tbody>
            </table>

            <div style="text-align: right; margin-bottom: 20px;">
                <p><strong>Subtotal</strong>: <?= $order_price; ?> USD</p>
                <p><strong>GST (18%)</strong>: <?= $pay_processing_fee; ?> USD</p>
                <p><strong>Total (USD)</strong>: <?php echo $order_price + $pay_processing_fee ?> USD</p>
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th
                            style="padding: 10px; border: 1px solid #ccc;">Method</th>
                        <th
                            style="padding: 10px; border: 1px solid #ccc;">Date</th>
                        <th style="padding: 10px; border: 1px solid #ccc;">Total
                            (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ccc;">
                            <?= $pay_method; ?>
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc;">
                            <?= $pay_date; ?>
                        </td>
                        <td
                            style="padding: 10px; border: 1px solid #ccc;">
                            <?php echo $order_price + $pay_processing_fee ?> USD
                        </td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <p>Purchased on Hiremyprofile.com through Preferred Outsourcing Pvt Ltd</p>
                <p>India GST Identification Number (GSTIN): </p>
                <p>Have an invoice or billing question? <a href="info@hiremyprofile.com">Contact
                        us</a></p>
            </div>

            <div style="margin-top: 40px;">
                <p>Preferred Outsourcing Pvt Ltd.<br>
                    Rethink what’s possible with freelancers.<br>
                    126, FIRST FLOOR BANK ROAD,<br>
                    Ambala, Haryana , India 133001 <br>
                    Contact Us: info@hiremyprofile.com</p>

            </div>
        </div>







    <?php


    }
    ?>


    <!-- Button to download PDF -->
    <button id="download-pdf" class="btn btn-primary mt-3">Download Invoice
        as PDF</button>

    <script>
        document.getElementById('download-pdf').addEventListener('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(16);
            doc.text('Invoice FI46362753314', 14, 20);
            doc.setFontSize(12);
            doc.text('To:\nITSoftExpert\nNamrata Saxena\nHaryana\nIndia', 14, 30);

            doc.text('Date issued: Aug 29, 2024', 195, 30, {
                align: 'right'
            });
            doc.text('Order number: FO614920YFE93', 195, 40, {
                align: 'right'
            });

            doc.autoTable({
                startY: 50,
                head: [
                    ['Service', 'Quantity', 'Unit price (USD)', 'Total (USD)']
                ],
                body: [
                    ['Web Analytics', '1', '5,284.93', '5,284.93'],
                    ['Service Fee', '1', '554.92', '554.92']
                ],
                margin: {
                    top: 50
                }
            });

            doc.text('Subtotal: 5,839.85 USD', 195, doc.lastAutoTable.finalY + 10, {
                align: 'right'
            });
            doc.text('GST (18%): 1,050.82 USD', 195, doc.lastAutoTable.finalY + 20, {
                align: 'right'
            });
            doc.text('Total (USD): 6,890.67 USD', 195, doc.lastAutoTable.finalY + 30, {
                align: 'right'
            });

            // doc.text('Paid with Card', 20, doc.lastAutoTable.finalY + 50);
            // doc.text('Date: Aug 29, 2024', 150, doc.lastAutoTable.finalY + 50, { align: 'right' });
            // doc.text('Total (USD): 6,890.67', 150, doc.lastAutoTable.finalY + 60, { align: 'right' });


            doc.autoTable({
                startY: doc.lastAutoTable.finalY + 50, // Adjusting startY for the second table
                head: [
                    ['Method', 'Date', 'Total (USD)']
                ],
                body: [
                    ['Paid with Card', 'Aug 29, 2024', '6,890.67']
                ],
                margin: {
                    top: 50
                }
            });


            doc.text('Purchased on Fiverr.com through Fiverr Limited', 14, doc.lastAutoTable.finalY + 10);
            doc.text('India GST Identification Number (GSTIN): 9924JRS29010SH', 14, doc.lastAutoTable.finalY + 20);

            doc.text('Fiverr International Ltd.', 14, doc.lastAutoTable.finalY + 40);
            doc.text('8 Eliezer Kaplan St., Tel Aviv, Israel 6473409', 14, doc.lastAutoTable.finalY + 50);
            doc.text('Company no: 514048744; VAT ID: EU528375294', 14, doc.lastAutoTable.finalY + 60);

            doc.save('invoice.pdf');
        });
    </script>




    <?php require_once("includes/footer.php"); ?>
</body>

</html>