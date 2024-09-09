<div align="center" style="vertical-align:top" style="background-color: #fafafa;">
   <table cellpadding="0" cellspacing="0" border="0" width="480" class="table">
      <tbody>
         <tr>
            <td width="100%" align="center" style="vertical-align: top; background-color: #FAFAFA">
               <a href="<?= $site_url; ?>" target="_blank">
                  <img src="<?= $site_logo; ?>" border="0" alt="hiremyprofile" class="logo">
               </a>
            </td>
         </tr>
         <tr>
            <td width="100%" align="left" style="vertical-align:top">
               <a href="<?= $site_url; ?>" target="_blank">
                  <img src="<?= img_url("banner.jpg"); ?>" class='banner' alt="<?= $site_name; ?>">
               </a>
            </td>
         </tr>
         <tr>
            <td height="30" bgcolor="#ffffff" width="100%"></td>
         </tr>
         <tr>
            <td bgcolor="#ffffff" width="100%" align="center" style="vertical-align:top">
               <table width="390" cellpadding="0" cellspacing="0" border="0">
                  <tbody>
                     <tr>
                        <td width="100%" align="left" style="vertical-align:top">
                           <h2> Dear <?=$seller_user_name?>, </h2>
				               <p class='lead'> Are You Ready To Change Your Password. </p>
                        </td>
                     </tr>
                     <tr>
                        <td width="100%" align="center" style="vertical-align:top">
                           <a href="<?= $data['reset_link']; ?>" target="_blank">
                              <img src="<?= img_url("confirm.jpg"); ?>" border="0" alt="Confirm Now" width="180" style="display:block;padding-bottom:20px">
                           </a>
                           <p style="font-family:'Roboto',Arial,Helvetica,sans-serif;font-size:18px;font-weight:400;color:#333333;margin:0px!important;padding:0px!important;line-height:21px">
                           Click Here To Change Your Password<br>
                              <font style="color:#8b8b8b;font-size:13px;text-decoration:underline">
                                 <?= $data['reset_link']; ?>
                              </font><br>
                              <font style="color:#8b8b8b;font-size:13px">* If the button doesn't work copy to the URL and paste it in your browser.</font>
                           </p>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
         <tr>
            <td height="30" bgcolor="#ffffff" width="100%"></td>
         </tr>
         <tr>
            <td width="100%" align="left" style="vertical-align:top" bgcolor="#fafafa">
               <p class="footer-p">© <?= date("Y"); ?> <?= $site_name; ?> Inc. All rights reserved.<br></p>
            </td>
         </tr>
         <tr>
            <td height="40" bgcolor="#fafafa" width="100%"></td>
         </tr>
      </tbody>
   </table>
</div>