<style type="text/css">
    .fix_clear:nth-child(3n+1){
        clear:both;
    }
</style>
<div class="client-wrapper" <?php if(isset($client) && ($lead->junk == 1 || $lead->lost == 1)){ echo 'client-is-junk-or-lost';} ?>>
    <?php if(isset($client)){ ?>
        <div class="btn-group pull-left mbot25 mtop5">
            <a href="#" client-edit  class="mright10 font-medium-xs pull-left<?php if($client_locked == true){echo ' hide';} ?>"><?php echo _l('edit'); ?> <i class="fa fa-pencil-square-o"></i></a>
            <a href="#" class="font-medium-xs hidden dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo _l('more'); ?> <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-left ">
                <?php if($lead->junk == 0){ ?>
                    <?php if($lead->lost == 0 && (total_rows('tblclients',array('leadid'=>$lead->id)) == 0)){ ?>
                        <li>
                            <a href="#" onclick="lead_mark_as_lost(<?php echo $lead->id; ?>); return false;"><i class="fa fa-mars"></i> <?php echo _l('lead_mark_as_lost'); ?></a>
                        </li>
                    <?php } else if($lead->lost == 1){ ?>
                        <li>
                            <a href="#" onclick="lead_unmark_as_lost(<?php echo $lead->id; ?>); return false;"><i class="fa fa-smile-o"></i> <?php echo _l('lead_unmark_as_lost'); ?></a>
                        </li>
                    <?php } ?>
                <?php } ?>
                <!-- mark as junk -->
                <?php if($lead->lost == 0){ ?>
                    <?php if($lead->junk == 0 && (total_rows('tblclients',array('leadid'=>$lead->id)) == 0)){ ?>
                        <li>
                            <a href="#" onclick="lead_mark_as_junk(<?php echo $lead->id; ?>); return false;"><i class="fa fa fa-times"></i> <?php echo _l('lead_mark_as_junk'); ?></a>
                        </li>
                    <?php } else if($lead->junk == 1){ ?>
                        <li>
                            <a href="#" onclick="lead_unmark_as_junk(<?php echo $lead->id; ?>); return false;"><i class="fa fa-smile-o"></i> <?php echo _l('lead_unmark_as_junk'); ?></a>
                        </li>
                    <?php } ?>
                <?php } ?>
                <?php if((is_lead_creator($lead->id) && $lead_locked == false) || is_admin()){ ?>
                    <li>
                        <a href="<?php echo admin_url('leads/delete/'.$lead->id); ?>" class="text-danger delete-text _delete" data-toggle="tooltip" title=""><i class="fa fa-remove"></i> <?php echo _l('lead_edit_delete_tooltip'); ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>


        <?php if(total_rows('tblclients',array('leadid'=>$lead->id)) == 0){ ?>
            <a href="#" data-toggle="tooltip" data-title="<?php echo $convert_to_client_tooltip_email_exists; ?>" class="btn btn-success pull-right" onclick="convert_lead_to_customer(<?php echo $lead->id; ?>); return false;">
                <?php echo $text; ?>
            </a>
        <?php } ?>
    <?php } ?>
    <div class="clearfix no-margin"></div>
    <?php
    $form_url = admin_url('clients/client');
    if(isset($client)){
        $form_url = admin_url('clients/client/'.$client->userid);
    }
    ?>
    <?php if(isset($client)){ ?>
        <div class="alert alert-warning hide" role="alert" id="lead_proposal_warning">
            <?php echo _l('proposal_warning_email_change',array(_l('lead_lowercase'),_l('lead_lowercase'),_l('lead_lowercase'))); ?>
            <hr />
            <a href="#" onclick="update_all_proposal_emails_linked_to_lead(<?php echo $client->id; ?>); return false;"><?php echo _l('update_proposal_email_yes'); ?></a>
            <br />
            <a href="#" onclick="init_lead_modal_data(<?php echo $client->id; ?>); return false;"><?php echo _l('update_proposal_email_no'); ?></a>
        </div>
    <?php } ?>
    <?php echo form_open($form_url,array('id'=>'client_form')); ?>
    
    <!-- begin bang cuoc goi nho-->
    <div class="row">
        <div class="client-view<?php if(!isset($client)){echo ' hide';} ?>">
            <div class="col-md-12 col-xs-12 mtop15">
                <div class="lead-info-heading">
                    <h4 class="no-margin font-medium-xs bold">
                        <?php echo _l('customer_profile_details'); ?>
                    </h4>
                </div>

                <div>
                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading no-mtop"><?php echo _l('client_type'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->client_type1 != '' ? ($client->client_type1==1 ? 'Cá nhân' : 'Đại lý' ) : '-') ?></p>
                    </div>
                    <?php
                       
                        foreach ($customer_groups_name as $key => $value) {
                            $cus[] = $value['name'];
                        }
                       
                     ?>
                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading no-mtop "><?php echo _l('Phân loại theo ngành nghề'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $cus != '' ? implode(", ", $cus) : '-') ?></p>
                    </div>

                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading no-mtop"><?php echo _l('Phân loại doanh số'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->client_type_value1 != '' ? _l($client->client_type_value1) : '-') ?></p>
                    </div>

                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Mã khách hàng'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->code_company != '' ? $client->code_company : '-') ?></p>
                    </div>

                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Tên khách hàng/ công ty'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->company != '' ? $client->company : '-') ?></p>
                    </div>
                    
                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Số điện thoại cố định'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->phonenumber != '' ? $client->phonenumber : '-') ?></p>
                    </div>

                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Địa chỉ văn phòng'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->address != '' ? $client->address : '-') ?></p>
                    </div>
                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Thành phố'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->city != '' ? $city->name : '-') ?></p>
                    </div>

                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Quận/huyện'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->state != '' ? $state->name : '-') ?></p>
                    </div>


                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Địa chỉ viết hóa đơn'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->shipping_street != '' ? $client->shipping_street : '-') ?></p>
                    </div>
                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Thành phố'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->shipping_city != '' ? $city1->name : '-') ?></p>
                    </div>

                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Quận/huyện'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->shipping_state != '' ? $state1->name : '-') ?></p>
                    </div>

                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Mã số thuế'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->code_vat != '' ? $client->code_vat : '-') ?></p>
                    </div>

                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Số tài khoản'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->number_user != '' ? $client->number_user : '-') ?></p>
                    </div>
                    
                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Người đại diện'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->user_def != '' ? $client->user_def : '-') ?></p>
                    </div>

                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Chức danh'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->user_def_name != '' ? $client->user_def_name : '-') ?></p>
                    </div>

                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Tên người liên hệ'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->name_contact != '' ? $client->name_contact : '-') ?></p>
                    </div>

                    <div class="col-md-4 fix_clear">
                        <p class="text-muted lead-field-heading"><?php echo _l('Số di động'); ?></p>
                        <p class="bold font-medium-xs"><?php echo (isset($client) && $client->mobilephone_number != '' ? $client->mobilephone_number : '-') ?></p>
                    </div>
                    
                   

                </div>
            </div>
            <!-- <div class="col-md-4 col-xs-12 mtop15">
                <div class="lead-info-heading">
                    <h4 class="no-margin font-medium-xs bold">
                        <?php echo _l('lead_general_info'); ?>
                    </h4>
                </div>
                <p class="text-muted lead-field-heading no-mtop"><?php echo _l('lead_add_edit_status'); ?></p>
                <p class="bold font-medium-xs mbot15"><?php echo (isset($lead) && $lead->status_name != '' ? $lead->status_name : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('lead_add_edit_source'); ?></p>
                <p class="bold font-medium-xs mbot15"><?php echo (isset($lead) && $lead->source_name != '' ? $lead->source_name : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('lead_add_edit_assigned'); ?></p>
                <p class="bold font-medium-xs mbot15"><?php echo (isset($lead) && $lead->assigned != 0 ? get_staff_full_name($lead->assigned) : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('tags'); ?></p>
                <p class="bold font-medium-xs mbot10">
                    <?php
                    if(isset($lead)){
                        $tags = get_tags_in($lead->id,'lead');
                        if(count($tags) > 0){
                            echo render_tags($tags);
                            echo '<div class="clearfix"></div>';
                        } else {
                            echo '-';
                        }
                    }
                    ?>
                </p>
                <p class="text-muted lead-field-heading"><?php echo _l('leads_dt_datecreated'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($lead) && $lead->dateadded != '' ? time_ago($lead->dateadded) : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('leads_dt_last_contact'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($lead) && $lead->lastcontact != '' ? time_ago($lead->lastcontact) : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('lead_public'); ?></p>
                <p class="bold font-medium-xs mbot15">
                    <?php if(isset($lead)){
                        if($lead->is_public == 1){
                            echo _l('lead_is_public_yes');
                        } else {
                            echo _l('lead_is_public_no');
                        }
                    } else {
                        echo '-';
                    }
                    ?>
                </p>
                <?php if(isset($lead) && $lead->from_form_id != 0){ ?>
                    <p class="text-muted lead-field-heading"><?php echo _l('web_to_lead_form'); ?></p>
                    <p class="bold font-medium-xs mbot15"><?php echo $lead->form_data->name; ?></p>
                <?php } ?>
            </div> -->
            <!-- <div class="col-md-4 col-xs-12 mtop15">
                <?php if(total_rows('tblcustomfields',array('fieldto'=>'leads','active'=>1)) > 0 && isset($lead)){ ?>
                    <div class="lead-info-heading">
                        <h4 class="no-margin font-medium-xs bold">
                            <?php echo _l('custom_fields'); ?>
                        </h4>
                    </div>
                    <?php
                    $custom_fields = get_custom_fields('leads');
                    foreach ($custom_fields as $field) {
                        $value = get_custom_field_value($lead->id, $field['id'], 'leads'); ?>
                        <p class="text-muted lead-field-heading no-mtop"><?php echo $field['name']; ?></p>
                        <p class="bold font-medium-xs"><?php echo ($value != '' ? $value : '-') ?></p>
                        <?php
                    }
                } ?>
            </div>
            <div class="col-md-12">
                <p class="text-muted lead-field-heading"><?php echo _l('lead_description'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($lead) && $lead->description != '' ? $lead->description : '-') ?></p>
            </div> -->
        </div>
        <div class="clearfix">  </div>
        <div class="client-edit<?php if(isset($client)){echo ' hide';} ?>">
               <div class="col-md-12" >
        
                    <div class="col-md-4" style="padding-left: 0px;">
                        <?php
                        $s_attrs = array('data-none-selected-text'=>_l('system_default_string'));
                        $client_type_value = array(
                            array(
                                'id' => 1,
                                'name' => 'Cá nhân',
                            ),
                            array(
                                'id' => 2,
                                'name' => 'Đại lý',
                            ),
                        );

                        echo render_select('client_type1', $client_type_value, array('id','name'),'client_type', (isset($client) ? $client->client_type : 1), array(), array(), '', '', false);
                        ?>
                    </div>
                    <div class="col-md-4">
                        <?php
                        $selected = array();
                        if(isset($customer_groups)){
                            foreach($customer_groups as $group){
                                array_push($selected,$group['groupid']);
                            }
                        }
                        echo render_select('groups_in[]',$groups,array('id','name'),'Phân loại theo ngành nghề',$selected,array('multiple'=>true),array(),'','',false);
                        ?>
                    </div>
                    <div class="col-md-4" style="padding-right: 0px;">
                        <?php
                        $s_attrs = array('data-none-selected-text'=>_l('system_default_string'));
                        $client_type_value1 = array(

                            array(
                                'id' => 'highV',
                                'name' => 'Cao',
                            ),
                            array(
                                'id' => 'mediumV',
                                'name' => 'Vừa',
                            ),
                            array(
                                'id' => 'lowV',
                                'name' => 'Thấp',
                            ),
                        );
                        echo render_select('client_type_value1', $client_type_value1, array('id','name'),'Phân loại danh số', (isset($client) ? $client->client_sales : 1), array(), array(), '', '', false);
                        ?>
                    </div>
               </div>
                <div class="clearfix"></div>





                <div class="col-md-12">
                    <div class="col-md-4" style="padding-left: 0px;">
                        <?php
                        
                        $code_company = (isset($item) ? (($client->code_company)? $client->code_company : get_option('prefix_customer').sprintf("%05d",$client->id)) : get_option('prefix_customer').sprintf("%05d",(getMaxID('userid','tblclients')+1)));
                        echo render_input( 'code_company', 'Mã khách hàng' , $code_company, 'text', array(),''); ?>

                    </div>
                    <div class="col-md-4">
                        <?php
                        $value= ( isset($client) ? $client->company : '');
                        $attrs = (isset($client) ? array() : array('autofocus'=>true));

                        $name_type_client = ( isset($client) ? ($client->client_type == 2 ? "client-company" : "client-personal") : 'client-personal' ); ?>


                        <?php echo render_input( 'company', _l("Tên khách hàng/ công ty",$name_type_client),$value,'text',$attrs); ?>
                    </div>
                    <div class="col-md-4" style="padding-right: 0px;">
                        <?php

                    $phonenumber = ( isset($client) ? $client->phonenumber : "" );
                    echo render_input( 'phonenumber', 'Số điện thoại cố định',$phonenumber, 'text', array()); ?>

                    </div>
                </div>

                <div class="col-md-12" >

                    <div class="col-md-4" style="padding-left:0px">
                        <?php $value=( isset($client) ? $client->address : ''); ?>
                        <?php echo render_input( 'address', 'Địa chỉ văn phòng',$value, 'text', array()); ?>
                    </div>
                    <div class="col-md-4">
                        <?php $value=( isset($client) ? $client->city : ''); ?>
                        <?php echo render_select( 'city', get_all_province(), array('provinceid','name') , 'client_city',$value,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                    </div>
                    <div class="col-md-4"  style="padding-right:0px">
                        <?php $value=( isset($client) ? $client->state : ''); ?>
                        <?php $stateid = ( isset($client) ? $stateid : array()); ?>
                        <?php echo render_select('state', $stateid, array('districtid', 'name'),'client_district',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                    </div>
                </div>
                <div class="col-md-12"><div class="col-md-4" style="padding-left:0px"> <?php echo render_input( 'shipping_street', 'Địa chỉ viết hóa đơn',$client->shipping_street, 'text', array()); ?></div>
                <div class="col-md-4">
                    <?php $value=( isset($client) ? $client->shipping_city : ''); ?>
                    <?php echo render_select( 'shipping_city', get_all_province(), array('provinceid','name') , 'client_city',$value,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                </div>
                <div class="col-md-4" style="padding-right: 0px;">
                    <?php $value=( isset($client) ? $client->shipping_state : ''); ?>
                    <?php $stateid1 = ( isset($client) ? $stateid1 : array()); ?>
                    <?php echo render_select( 'shipping_state', $stateid1, array('districtid', 'name'),'client_district',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                </div></div>
                <div class="col-md-12" >
                    <div class="col-md-3" style="padding-left: 0px;">
                        <?php 

                        $code_vat = (isset($client) ? $client->code_vat : "" );
                        echo render_input( 'code_vat', 'Mã số thuế',$code_vat, 'text', array()); ?>
                    </div>
                    

                    <div class="col-md-3" >
                        <?php 

                        $number_user = (isset($client) ? $client->number_user : "" );
                        echo render_input('number_user', 'Số tài khoản',$number_user, 'text', array()); ?>
                    </div>

                    <div class="col-md-3" >
                        <?php 

                        $user_def = (isset($client) ? $client->user_def : "" );
                        echo render_input('user_def', 'Người đại diện',$user_def, 'text', array()); ?>
                    </div>

                    <div class="col-md-3" style="padding-right: 0px;">
                        <?php 

                        $user_def_name = ( isset($client) ? $client->user_def_name : "");

                        echo render_input('user_def_name', 'Chức danh',$user_def_name, 'text', array()); ?>
                        

                       
                    </div>
                </div>
                <div class="col-md-12" >
                    <div class="col-md-4" style="padding-left: 0px;">
                        <?php 

                        $name_contact = (isset($client) ? $client->name_contact : "" );
                        echo render_input( 'name_contact', 'Tên liên hệ',$name_contact, 'text', array()); ?>
                    </div>
                    

                    <div class="col-md-4" >
                        <?php 

                        $email = (isset($client) ? $client->email : "" );
                        echo render_input('email', 'Email',$email, 'email', array()); ?>
                    </div>

                    <div class="col-md-4" style="padding-right: 0px;">
                        <?php 

                        $mobilephone_text = ( isset($client) ? $client->mobilephone_number : "");
                        ?>

                        <div class="form-group" >
                            <label for="mobilephone_number" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i>
                                <?php echo _l('client-mobilephone'); ?></label>
                            <input type="text" class="tagsinput_phone" value="<?=$mobilephone_text?>" id="mobilephone_number" name="mobilephone_number" data-role="tagsinput">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <?php $rel_id = (isset($lead) ? $lead->id : false); ?>
                    <?php echo render_custom_fields('leads',$rel_id); ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

    <!-- end bang cuoc goi nho-->

    <?php if(!isset($lead)){ ?>
       
    <?php } ?>
    <!-- <?php if(isset($client)){ ?>
        <div class="lead-latest-activity lead-view">
            <div class="lead-info-heading">
                <h4 class="no-margin bold font-medium-xs"><?php echo _l('lead_latest_activity'); ?></h4>
            </div>
            <div id="lead-latest-activity" class="pleft5"></div>
        </div>
    <?php } ?> -->
    
    <div class="client-edit<?php if(isset($client)){echo ' hide';} ?>">
        <hr />
        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
        <button type="button" class="btn btn-default pull-right mright5" data-dismiss="modal"><?php echo _l('close'); ?></button>
    </div>
    <input type="hidden" id="clientid" value="<?php echo $client->userid ?>">
    <div class="clearfix"></div>
    <?php echo form_close(); ?>
</div>

<script>
    $(function(){
        custom_fields_hyperlink();
        init_tags_inputs();
    });
</script>
<?php if(isset($lead) && $lead_locked == true){ ?>
    <script>
        $(function(){
            // Set all fields to disabled if lead is locked
            var lead_fields = $('.lead-wrapper').find('input,select,textarea');
            $.each(lead_fields,function(){
                $(this).attr('disabled',true);
            });
        });


    </script>
<?php } ?>
<script>
    $(function(){
        $("body").on("click", '[client-edit]', function(a) {
            a.preventDefault(), 
            $("body .client-view").toggleClass("hide"),
            $("body .client-edit").toggleClass("show");
        });
//            $("body").on("click", "[client-edit]", function(a) {
//                alert('123');
//                a.preventDefault(), $("body .lead-view").toggleClass("show"),
//                    $("body .lead-edit").toggleClass("hide");
//            });

    });
    

    

</script>
</script>
