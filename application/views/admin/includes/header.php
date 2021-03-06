<?php
ob_start();
?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
    <!-- <script src="http://khachhang9.com/CRM/assets/js/tongdaivoip.com.js"></script> -->
    <!-- <script type="text/javascript">
        host = "103.27.61.119";
        port = 8083;
        username = "inputusername";
        password = "inputpassword";
        topic1 = "test1_autocall";
        topic2 = "test2_autocall";
        topic3 = "calls/+"
        topics = [];
        topics.push(topic1);
        topics.push(topic2);
        topics.push(topic3);
        websocketclient.connect(host, port, username, password, topics, function(topic, msgObj){
           payload = msgObj.payload;
           if(topic== topic1){
                alert(topic1 + ": " + payload + "");
            }else if (topic==topic2){
               alert(topic2 + ": " + payload + "");
            }else{
               let item = JSON.parse(payload);
               let openLink = "<a href=\"#\" id=\"" + item["callerid"] + "\">";
               let closeLink = "</a><br />"
//               $("#popup").prepend(openLink + item["callerid"] + closeLink);
               var dataString={phone:item["callerid"]};
               jQuery.ajax({
                   type: "post",
                   url: "<?=admin_url()?>calllogs/information_phone_client",
                   data: dataString,
                   cache: false,
                   success: function (data) {
                       jQuery('.true_formation').hide();
                       var json = JSON.parse(data);
                       if(json!="")
                       {
                           $("#popup").html('Khách hàng: '+item["callerid"]+" Đang gọi....");
                           jQuery('.call_new').hide();
                           jQuery('.true_formation').show();
                           jQuery('.set_company').html(json.company);
                           jQuery('.set_name_owners').html(json.name_owners);
                           jQuery('.set_address').html(json.address);
                           jQuery('.set_website').html(json.website);

                       }
                       else
                       {
                           jQuery.ajax({
                               type: "post",
                               url: "<?=admin_url()?>calllogs/information_phone_leads",
                               data: dataString,
                               cache: false,
                               success: function (data) {
                                   var json = JSON.parse(data);
                                   if(json!="")
                                   {
                                       $("#popup").html('Khách hàng(Cuộc gọi): '+item["callerid"]+" Đang gọi....");
                                       jQuery('.call_new').hide();
                                       jQuery('.true_formation').show();
                                       jQuery('.set_company').html(json.company);
                                       jQuery('.set_name_owners').html(json.name_owners);
                                       jQuery('.set_address').html(json.address);
                                       jQuery('.set_website').html(json.website);

                                   }
                                   else
                                   {
                                       $('#popup').show();
                                       $('#popup').html('Khách hàng không có trong hệ thống: '+item['callerid']+' Đang gọi....');
                                       $('.call_new').show();
                                       $(".call_new").html('Khách hàng mới đang thực hiện cuộc gọi cho bạn');
                                   }

                               }
                           });
                       }

                   }
               });
               $('#myModal').modal({
                   show: 'false'

               });

            }
        });

        function clickCall(param){
            var extension = param.getAttribute("data-extension");
            var telephone = param.getAttribute("data-telephone");
            var dataString={phone:telephone}
            $("#popup").html("Bạn đang gọi cho số điện thoại: "+telephone);
            jQuery.ajax({
                type: "post",
                url: "<?=admin_url()?>calllogs/information_phone_client",
                data: dataString,
                cache: false,
                success: function (data) {
                    var json = JSON.parse(data);
                    if(json!="")
                    {
                        jQuery('.call_new').hide();
                        jQuery('.true_formation').show();
                        jQuery('.set_company').html(json.company);
                        jQuery('.set_name_owners').html(json.name_owners);
                        jQuery('.set_address').html(json.address);
                        jQuery('.set_website').html(json.website);

                    }
                    else
                    {
                        jQuery.ajax({
                            type: "post",
                            url: "<?=admin_url()?>calllogs/information_phone_leads",
                            data: dataString,
                            cache: false,
                            success: function (data) {
                                var json = JSON.parse(data);
                                if(json!="")
                                {
                                    jQuery('.call_new').hide();
                                    jQuery('.tab-content.true').show();
                                    jQuery('.set_company').html(json.company);
                                    jQuery('.set_name_owners').html(json.name_owners);
                                    jQuery('.set_address').html(json.address);
                                    jQuery('.set_website').html(json.website);

                                }
                                else
                                {
                                    $("#popup").show();
                                    jQuery('.call_new').hide();
                                    jQuery('.true_formation').hide();
                                }

                            }
                        });

                    }

                }
            });
            $('#myModal').modal({
                show: 'false'

            });


            $.ajax({
                url : "<?=admin_url()?>leads/callcenter",
                type : "post",
                data : {
                    exten : extension,
                    tel : telephone
                },
                async: true,
                success: function(result){
                }
            });
        };
    </script> -->
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><a id="popup" style="margin-bottom: 10px;"></a></h4>
        </div>
        <div class="modal-body" style="height:auto">
            <p class="call_new">Khách hàng mới đang thực hiện cuộc gọi cho bạn</p>
            <div class="tab-content true_formation">
                <div class="col-md-6 col-xs-12">
                    <div class="lead-info-heading">
                        <h4 class="no-margin font-medium-xs bold">Tên công ty</h4>
                    </div>
                    <p class="bold font-medium-xs set_company">

                    </p>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="lead-info-heading">
                        <h4 class="no-margin font-medium-xs bold">Người đại diện</h4>
                    </div>
                    <p class="bold font-medium-xs set_name_owners">

                    </p>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="lead-info-heading">
                        <h4 class="no-margin font-medium-xs bold">Địa chỉ</h4>
                    </div>
                    <p class="bold font-medium-xs set_address">

                    </p>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="lead-info-heading">
                        <h4 class="no-margin font-medium-xs bold">Website</h4>
                    </div>
                    <p class="bold font-medium-xs set_website">

                    </p>
                </div>
            </div>
            <div class="clearfix">  </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>




<li id="top_search" class="dropdown" data-toggle="tooltip" data-placement="bottom" data-title="<?php echo _l('search_by_tags'); ?>">
    <input type="search" id="search_input" class="form-control" placeholder="<?php echo _l('top_search_placeholder'); ?>">
    <div id="search_results">
    </div>
</li>
<li id="top_search_button">
    <button class="btn"><i class="fa fa-search"></i></button>
</li>
<?php
$top_search_area = ob_get_contents();
ob_end_clean();
?>
<div id="header">
    <?php if(rtrim(admin_url(), "/") != current_url()){ ?>
      <div class="hide-menu"><i class="fa fa-bars"></i></div>
       <?php } ?>
    <div id="logo">
        <?php get_company_logo('admin') ?>
    </div>

    <nav>
        <div class="small-logo hidden-xs hidden-sm">
            <span class="text-primary">
                <?php get_company_logo('admin') ?>
            </span>

        </div>
        <div class="mobile-menu">
            <button type="button" class="navbar-toggle visible-md visible-sm visible-xs mobile-menu-toggle collapsed" data-toggle="collapse" data-target="#mobile-collapse" aria-expanded="false">
                <i class="fa fa-chevron-down"></i>
            </button>
            <ul class="mobile-icon-menu">
                <?php
                // To prevent not loading the timers twice
                if(is_mobile()){ ?>
                <li class="dropdown notifications-wrapper">
                    <?php $this->load->view('admin/includes/notifications'); ?>
                </li>
                <?php if(is_staff_member()){ ?>
                <li>
                    <a href="#" class="open_newsfeed"><i class="fa fa-commenting" aria-hidden="true"></i></a>
                </li>
                <?php } ?>
                <li>
                    <a href="#" class="dropdown-toggle top-timers<?php if(count($_started_timers) > 0){echo ' text-warning';} ?>" data-toggle="dropdown"><i class="fa fa-clock-o"></i></a>
                    <ul class="dropdown-menu animated fadeIn started-timers-top width300" id="started-timers-top">
                     <?php $this->load->view('admin/tasks/started_timers'); ?>
                 </ul>
             </li>
             <?php } ?>
         </ul>
         <div class="mobile-navbar collapse" id="mobile-collapse" aria-expanded="false" style="height: 0px;" role="navigation" >
            <ul class="nav navbar-nav">
                <li><a href="<?php echo admin_url('profile'); ?>"><?php echo _l('nav_my_profile'); ?></a></li>
                <li><a href="<?php echo admin_url('staff/timesheets'); ?>"><?php echo _l('my_timesheets'); ?></a></li>
                <li><a href="<?php echo admin_url('staff/edit_profile'); ?>"><?php echo _l('nav_edit_profile'); ?></a></li>
                <li><a href="#" onclick="logout(); return false;"><?php echo _l('nav_logout'); ?></a></li>
            </ul>
        </div>
    </div>
    <ul class="nav navbar-nav navbar-right">
        <?php
        if(!is_mobile()){
            echo $top_search_area;
        } ?>
        <?php do_action('after_render_top_search'); ?>
        <li>
            <a href="#" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="false">
                <?php echo staff_profile_image($_staff->staffid,array('img','img-responsive','staff-profile-image-small','pull-left')); ?>
                <?php echo $_staff->firstname . ' ' . $_staff->lastname; ?>
                <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu animated fadeIn">
                <li><a href="<?php echo admin_url('profile'); ?>"><?php echo _l('nav_my_profile'); ?></a></li>
                <li><a href="<?php echo admin_url('staff/timesheets'); ?>"><?php echo _l('my_timesheets'); ?></a></li>
                <li><a href="<?php echo admin_url('staff/edit_profile'); ?>"><?php echo _l('nav_edit_profile'); ?></a></li>
                <li class="dropdown-submenu pull-left">
                   <a href="#" tabindex="-1"><?php echo _l('language'); ?></a>
                   <ul class="dropdown-menu dropdown-menu-left">
                      <li class="<?php if($_staff->default_language == ""){echo 'active';} ?>"><a href="<?php echo admin_url('staff/change_language'); ?>"><?php echo _l('system_default_string'); ?></a></li>
                      <?php foreach($this->perfex_base->get_available_languages() as $user_lang) { ?>
                      <li <?php if($_staff->default_language == $user_lang){echo 'class="active"';} ?>>
                       <a href="<?php echo admin_url('staff/change_language/'.$user_lang); ?>"><?php echo ucfirst($user_lang); ?></a>
                   </li>
                   <?php } ?>
               </ul>
           </li>
           <li><a href="#" onclick="logout(); return false;"><?php echo _l('nav_logout'); ?></a></li>
       </ul>
   </li>
   <li class="icon">
    <a href="<?php echo admin_url('business_news'); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo _l('business_news'); ?>"><i class="fa fa-newspaper-o"></i></a>
</li>
<li class="icon">
    <a href="<?php echo admin_url('todo'); ?>" data-toggle="tooltip" title="<?php echo _l('nav_todo_items'); ?>" data-placement="bottom"><i class="fa fa-list"></i>
        <?php $_unfinished_todos = total_rows('tbltodoitems',array('finished'=>0,'staffid'=>get_staff_user_id())); ?>
        <span class="label label-warning icon-total-indicator nav-total-todos<?php if($_unfinished_todos == 0){echo ' hide';} ?>"><?php echo $_unfinished_todos; ?></span>
    </a>
</li>
<li class="icon">
    <a href="#" class="dropdown-toggle top-timers<?php if(count($_started_timers) > 0){echo ' text-warning';} ?>" data-toggle="dropdown"><span data-placement="bottom" data-toggle="tooltip" data-title="<?php echo _l('project_timesheets'); ?>"><i class="fa fa-clock-o"></i></span></a>
    <ul class="dropdown-menu animated fadeIn started-timers-top width350" id="started-timers-top">
        <?php $this->load->view('admin/tasks/started_timers'); ?>
    </ul>
</li>
<?php if(is_staff_member()){ ?>
<li class="icon">
    <a href="#" class="open_newsfeed"><i class="fa fa-commenting" aria-hidden="true"></i></a>
</li>
<?php } ?>
<li class="dropdown notifications-wrapper">
    <?php $this->load->view('admin/includes/notifications'); ?>
</li>
</ul>
</nav>
</div>
<div id="mobile-search" class="<?php if(!is_mobile()){echo 'hide';} ?>">
    <ul>
        <?php
        if(is_mobile()){
            echo $top_search_area;
        } ?>
    </ul>
</div>
