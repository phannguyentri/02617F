<style type="text/css">
  .panel-primary>.panel-heading{
    color: #fff;
    background-color: #2595d4;
    border-color: #2595d4;
  }
  .fix-phone-margin{
    margin: 3px;
    display: inline-block !important;
  }
</style>
<!-- Modal -->
<div class="modal fade" id="smsDetailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="myModalLabel">Chi tiết SMS</h4>
                </div>
            <div class="modal-body">
                <center>
                  <p class="text-left">
                    <span class="text-muted bold">Người gửi:</span><?php echo staff_profile_image($sms->staff_id, array('img-circle')) ?> <a href="<?php echo admin_url('profile/'.$sms->staff_id) ?>"><strong><?php echo get_staff_full_name($sms->staff_id) ?></strong></a>
                  </p>
                  <p class="text-left">
                    <span class="text-muted bold">Chủ đề:</span> <strong><?php echo $sms->subject ?></strong>
                  </p>
                  <p class="text-left">
                    <span class="text-muted bold">Mẫu template:</span> <strong><?php echo $sms->name ?></strong>
                  </p>
                  <p class="text-left">
                    <span class="text-muted bold">Thời gian:</span> <strong><?php echo $sms->date_send ?></strong>
                  </p>
                  <p class="text-left">
                    <span class="text-muted bold" style="display: block;">Số điện thoại:</span>
                    <?php
                      $arrphone = explode(',', $sms->phone_number);
                      foreach ($arrphone as $phone) {
                        echo '<span class="label label-warning fix-phone-margin"><strong>'.$phone.'</strong></span>';
                      }
                     ?>
                  </p>
                  <div class="panel panel-primary"  >
                    <div class="panel-heading">Nội dung SMS:</div>
                    <div class="panel-body"><?php echo $sms->message ?></div>
                  </div>
                  <br>
                </center>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ĐÓNG</button>
            </div>
        </div>
    </div>
</div>
