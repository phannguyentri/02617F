   <?php include_once(APPPATH . 'views/admin/includes/modals/post_likes.php'); ?>
   <?php include_once(APPPATH . 'views/admin/includes/modals/post_comment_likes.php'); ?>

   <div id="newsfeed" class="animated fadeIn hide" <?php if($this->session->flashdata('newsfeed_auto')){echo 'data-newsfeed-auto';} ?>>
   </div>

   <div class="modal fade call_log-modal-single" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
       <div class="modal-dialog modal-lg">
           <div class="modal-content data">

           </div>
       </div>
   </div>
   <!-- Task modal view START -->
<div class="modal fade task-modal-single" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content data">

        </div>
    </div>
</div>
<!--Task modal view END-->



<!-- Lead Data Add/Edit  START-->
<div class="modal fade lead-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>



<!--Client Data Add/Edit END-->

<!-- Lead Data Add/Edit  START-->
<style type="text/css">


@media (min-width: 992px){
  .client-modal .modal-lg{      
      width: 950px;    
      }
  }
</style>
<div class="modal fade client-modal" style="min-height: 500px;" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>

<style type="text/css">
.quotes-modal .modal-lg{      
      width: 1350px;   
      }

@media (max-width: 1350px){
  .quotes-modal .modal-lg{      
      width: 100% !important;    
      }
  }
</style>
<!-- Lead Data Add/Edit  START-->
<div class="modal fade quotes-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>
<!--Lead Data Add/Edit END-->
<!--call_log-->
<div class="modal fade call_log-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
   <div class="modal-dialog modal-lg">
       <div class="modal-content">

       </div>
   </div>
</div>
<!--end call_log-->
<div class="modal fade timers-modal-logout" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h4 class="bold"><?php echo _l('timers_started_confirm_logout'); ?></h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="<?php echo site_url('authentication/logout'); ?>" class="btn btn-danger"><?php echo _l('confirm_logout'); ?></a>
            </div>
        </div>
    </div>
</div>
<!--Add/edit task modal start-->
<div id="client_reminder_modal"></div>
<!--Tracking stats chart for task END-->
<div id="tacking-stats"></div>
<!--Tracking stats chart for task END-->
<!--Add/edit task modal start-->
<div id="_task"></div>
<div id="_task1"></div>
<div id="_reminder"></div>
<!--Add/edit task modal end-->
<!--Lead convert to customer modal start-->
<div id="lead_convert_to_customer"></div>
<!--Lead convert to customer modal end-->
<!--Lead convert to customer modal start-->
<div id="lead_reminder_modal"></div>
<!--Lead convert to customer modal end-->
<div id="lead_call-logs_modal"></div>
