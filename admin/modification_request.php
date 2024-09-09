<?php

@session_start();

if (!isset($_SESSION['admin_email'])) {

    echo "<script>window.open('login','_self');</script>";
} else {

    $request_id = $input->get('modification_request');
    $page = (isset($_GET['page'])) ? "=" . $input->get('page') : "";
    $select_requests = $db->select("buyer_requests", array("request_id" => $request_id));
    $row_requests = $select_requests->fetch();

    $request_title = $row_requests->request_title;
    $seller_id = $row_requests->seller_id;

    $get_seller = $db->select("sellers", array("seller_id" => $seller_id));
    $row_seller = $get_seller->fetch();
    $seller_user_name = $row_seller->seller_user_name;
    $seller_email = $row_seller->seller_email;
    $seller_phone = $row_seller->seller_phone;

    $last_update_date = date("F d, Y");

    if (isset($_POST['submit'])) {

        $request_modification = $input->post('request_modification');

        $insert_modification = $db->insert("request_modifications ", array("admin_id" => $admin_id, "request_id" => $request_id, "modification_message" => $request_modification));

        $update_request = $db->update("buyer_requests", array("request_status" => 'modification'), array("request_id" => $request_id));

        if ($update_request) {

            $data = [];
            $data['template'] = "request_modification";
            $data['to'] = $seller_email;
            $data['subject'] = "$site_name: Admin Has Sent Modification To Your Job Request.";
            $data['user_name'] = $seller_user_name;
            send_mail($data);

            if ($notifierPlugin == 1) {
                $smsText = $lang['notifier_plugin']['request_modification'];
                sendSmsTwilio("", $smsText, $seller_phone);
            }

            $insert_notification = $db->insert("notifications", array("receiver_id" => $seller_id, "sender_id" => "admin_$admin_id", "order_id" => $request_id, "reason" => "modification_request", "date" => $last_update_date, "status" => "unread"));

            echo "<script>

        swal({
            type: 'success',
            text: 'Modification request sent!',
            timer: 3000,
            onOpen: function(){
                swal.showLoading()
            }
        }).then(function(){

            // Read more about handling dismissals
            window.open('index?buyer_requests&status=pending$page','_self');

        });

    </script>";
        }
    }

?>


    <div class="breadcrumbs">

        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1><i class="menu-icon fa fa-table"></i> Request / Modification Request</h1>
                </div>
            </div>
        </div>

        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Submit Request For Modification</li>
                    </ol>
                </div>
            </div>
        </div>

    </div>

    <div class="container-fluid">

        <div class="row pt-2">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-header">

                        <h4>Insert Reason For Modification Request</h4>

                    </div>

                    <div class="card-body">

                        <form action="" method="post">

                            <div class="form-group row">

                                <label class="col-md-3 control-label">Request Title</label>

                                <div class="col-md-6">

                                    <p class="mt-2"><?= $request_title; ?></p>

                                </div>

                            </div>


                            <div class="form-group row">

                                <label class="col-md-3 control-label">Describe Modification</label>

                                <div class="col-md-6">

                                    <textarea name="request_modification" class="form-control" required></textarea>

                                </div>

                            </div>


                            <div class="form-group row">

                                <label class="col-md-3 control-label"></label>

                                <div class="col-md-6">

                                    <input type="submit" name="submit" class="btn btn-success form-control" value="Send Modification Request">

                                </div>

                            </div>


                        </form>


                    </div>

                </div>

            </div>

        </div>

    </div>
<?php } ?>