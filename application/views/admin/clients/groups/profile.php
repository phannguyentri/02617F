  <h4 class="bold no-margin"><?php echo _l('client_add_edit_profile'); ?></h4>
  <hr class="no-mbot no-border" />
  <div class="row">
    <?php echo form_open($this->uri->uri_string(),array('class'=>'client-form','autocomplete'=>'off')); ?>
    <div class="additional"></div>
    <div class="col-md-12">
        <ul class="nav nav-tabs profile-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#contact_info" aria-controls="contact_info" role="tab" data-toggle="tab">
                    <?php echo _l( 'customer_profile_details'); ?>
                </a>
            </li>
            <li style="<?=(isset($client) && $client->client_type == 2 || !isset($client) ? "" : "display: none")?>" role="presentation">
                <a href="#billing_and_shipping" aria-controls="billing_and_shipping" role="tab" data-toggle="tab">
                    <?php echo _l( 'billing_shipping'); ?>
                </a>
            </li>
            <?php if(isset($client)){ ?>
            <li role="presentation">
                <a href="#contacts" aria-controls="contacts" role="tab" data-toggle="tab">
                    <?php echo _l( 'customer_contacts'); ?>
                </a>
            </li>
            <li role="presentation">
                <a href="#customer_admins" aria-controls="customer_admins" role="tab" data-toggle="tab">
                    <?php echo _l( 'customer_admins'); ?>
                </a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="contact_info">
                <div class="row">
                <?php if(!isset($client) || isset($client) && !is_empty_customer_company($client->userid)) { ?>
                    <div class="col-md-12">
                       <div class="checkbox checkbox-success mbot20 no-mtop">
                           <input type="checkbox" name="show_primary_contact"<?php if(isset($client) && $client->show_primary_contact == 1){echo ' checked';}?> value="1" id="show_primary_contact">
                           <label for="show_primary_contact"><?php echo _l('show_primary_contact',_l('invoices').', '._l('estimates').', '._l('payments')); ?></label>
                       </div>
                   </div>
                   
                   <?php } ?>
                   <div class="col-md-6">
                       <?php 
                       $s_attrs = array('data-none-selected-text'=>_l('system_default_string'));
                       $client_type_value = array(
                           array(
                               'id' => 1,
                               'name' => 'Cá nhân',
                           ),
                           array(
                               'id' => 2,
                               'name' => 'Tổ chức',
                           ),
                       );
                       
                     echo render_select('client_type', $client_type_value, array('id','name'),'client_type', (isset($client) ? $client->client_type : 1), array(), array(), '', '', false); ?>
                      
                    <?php
                    $value= ( isset($client) ? $client->company : ''); 
                    $attrs = (isset($client) ? array() : array('autofocus'=>true));
                    $name_type_client = ( isset($client) ? ($client->client_type == 2 ? "client-company" : "client-personal") : 'client-personal' ); ?>
                    <?php echo render_input( 'company', _l("client-name",$name_type_client),$value,'text',$attrs); ?>
                    
                    <?php
                    $c_attrs_personal = (isset($client) ? ($client->client_type == 1 ? array() : array('style' => 'display:none')) : array());
                    $short_name = ( isset($client) ? $client->short_name : "" );
                    echo render_input( 'short_name', 'client_shortname' , $short_name, 'text', array(), $c_attrs_personal); ?>                    
                    
                    <?php $value = (isset($client) ? _d($client->birthday) : _d(date('Y-m-d')));
                        $label = (isset($client) ? ($client->client_type == 2 ? _l('client-company-birthday') : _l('date_birth')) : _l('date_birth'));
                    echo render_date_input('birthday', $label, $value); ?>

                    <?php
                    
                        $value_type_of_organization = ( isset($client) ? $client->type_of_organization : "" );
                        $type_of_organization = array(
                            array(
                                'id' => 'Doanh nghiệp tư nhân',
                                'name' => 'Doanh nghiệp tư nhân',
                            ),
                            array(
                                'id' => 'Công ty cổ phần',
                                'name' => 'Công ty cổ phần',
                            ),
                            array(
                                'id' => 'Công ty trách nhiệm hữu hạn một thành viên',
                                'name' => 'Công ty trách nhiệm hữu hạn một thành viên',
                            ),
                            array(
                                'id' => 'Công ty trách nhiệm hữu hạn hai thành viên trở lên',
                                'name' => 'Công ty trách nhiệm hữu hạn hai thành viên trở lên',
                            ),
                            array(
                                'id' => 'Công ty hợp danh',
                                'name' => 'Công ty hợp danh',
                            ),
                        );
                    ?>
                    <?php 
                    $group_attrs_company = array('style' => ( isset($client) ? ($client->client_type == 2 ? "" : "display: none") : "display: none"));

                    echo render_select('type_of_organization',$type_of_organization,array('id','name'),'type_of_organization',$value_type_of_organization,$s_attrs, $group_attrs_company); ?>

                    <?php
                    $c_attrs_personal = (isset($client) ? ($client->client_type == 2 ? array() : array('style' => 'display:none')) : array('style' => 'display:none'));
                    $bussiness_registration_number = ( isset($client) ? $client->bussiness_registration_number : "" );
                    echo render_input( 'bussiness_registration_number', 'bussiness_registration_number' , $bussiness_registration_number, 'text', array(), $c_attrs_personal ); ?>
                    
                    <?php
                    $c_attrs_personal = (isset($client) ? ($client->client_type == 2 ? array() : array('style' => 'display:none')) : array('style' => 'display:none'));
                    $legal_representative = ( isset($client) ? $client->legal_representative : "" );
                    echo render_input( 'legal_representative', 'legal_representative' , $legal_representative, 'text', array(), $c_attrs_personal ); ?>

                    <?php
                    $email = ( isset($client) ? $client->email : "" );
                    echo render_input( 'email', 'email' , $email ,'email',array('autocomplete'=>'off')); ?>

                    <?php
                    $c_attrs_personal = (isset($client) ? ($client->client_type == 2 ? array() : array('style' => 'display:none')) : array('style' => 'display:none'));
                    $cooperative_day = ( isset($client) ? _d($client->cooperative_day) : _d(date('Y-m-d')));
                    echo render_date_input( 'cooperative_day', 'cooperative_day' , $cooperative_day, array(), $c_attrs_personal ); ?>

                    <?php
                    $c_attrs_personal = (isset($client) ? ($client->client_type == 2 ? array() : array('style' => 'display:none')) : array('style' => 'display:none'));
                    if(get_option('company_requires_vat_number_field') == 1){
                        $value=( isset($client) ? $client->vat : '');
                        echo render_input( 'vat', 'client_vat_number',$value, 'text', array(), $c_attrs_personal);
                    }
                    $s_attrs = array('data-none-selected-text'=>_l('system_default_string'));
                    $selected = '';
                    if(isset($client) && client_have_transactions($client->userid)){
                      $s_attrs['disabled'] = true;
                  }
                  foreach($currencies as $currency){
                    if(isset($client)){
                      if($currency['id'] == $client->default_currency){
                        $selected = $currency['id'];
                    }
                }
            }
            ?>
            <?php if(!isset($client)){ ?>
            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('customer_currency_change_notice'); ?>"></i>
            <?php } ?>
            <?php echo render_select('default_currency',$currencies,array('id','name','symbol'),'invoice_add_edit_currency',$selected,$s_attrs); ?>
            <div class="form-group">
                <label for="default_language" class="control-label"><?php echo _l('localization_default_language'); ?>
                </label>
                <select name="default_language" id="default_language" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                    <option value=""><?php echo _l('system_default_string'); ?></option>
                    <?php foreach(list_folders(APPPATH .'language') as $language){
                        $selected = '';
                        if(isset($client)){
                           if($client->default_language == $language){
                              $selected = 'selected';
                          }
                      }
                      ?>
                      <option value="<?php echo $language; ?>" <?php echo $selected; ?>><?php echo ucfirst($language); ?></option>
                      <?php } ?>
                  </select>
              </div>
              
              <!-- <a href="#" class="pull-left mright5" onclick="fetch_lat_long_from_google_cprofile(); return false;" data-toggle="tooltip" data-title="<?php echo _l('fetch_from_google') . ' - ' . _l('customer_fetch_lat_lng_usage'); ?>"><i id="gmaps-search-icon" class="fa fa-google" aria-hidden="true"></i></a> -->
              <!-- <?php $value=( isset($client) ? $client->latitude : ''); ?>
              <?php echo render_input( 'latitude', 'customer_latitude',$value); ?>
              <?php $value=( isset($client) ? $client->longitude : ''); ?>
              <?php echo render_input( 'longitude', 'customer_longitude',$value); ?> -->

              <?php
                    $selected = array();
                    if(isset($customer_groups)){
                        foreach($customer_groups as $group){
                        array_push($selected,$group['groupid']);
                    }
                }
                echo render_select('groups_in[]',$groups,array('id','name'),'customer_groups',$selected,array('multiple'=>true),array(),'','',false);
                ?>
                <?php
                $default_source_id = 0;
                if(!isset($client) && count($sources) > 0) {
                    $default_source_id = $sources[0]['id'];
                }
                echo render_select('source_approach', $sources, array('id','name'),'source_from', !isset($client) ? $default_source_id : $client->source_approach, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); 
                ?>

          </div>
          <div class="col-md-6">
              
            <?php
                $name_title = ( isset($client) ? $client->name_title : "" );
                echo render_input( 'name_title', 'client_name_title' , $name_title ); ?>
                
            <?php
            $c_attrs_personal = (isset($client) ? ($client->client_type == 1 ? array() : array('style' => 'display:none')) : array());

            $phonenumber = ( isset($client) ? $client->phonenumber : "" );
            echo render_input( 'phonenumber', 'client_phonenumber',$phonenumber, 'text', array(), $c_attrs_personal);

            $mobilephone_text = ( isset($client) ? $client->mobilephone_number : "");

            ?>
            
            <div class="form-group" <?=(isset($client) ? ($client->client_type == 1 ? "" : 'style="display:none"') : "")?> >
                <label for="mobilephone_number" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i> 
                <?php echo _l('client-mobilephone'); ?></label>
                <input type="text" class="tagsinput_phone" value="<?=$mobilephone_text?>" id="mobilephone_number" name="mobilephone_number" data-role="tagsinput">
            </div>

            <?php
                $default_source_id = 0;
                if(!isset($client) && count($areas) > 0) {
                    $default_source_id = $areas[0]['id'];
                }
                echo render_select('address_area', $areas, array('id','name'),'client_area', !isset($client) ? $default_source_id : $client->address_area, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); 
            ?>

            <?php $countries= get_all_countries();
            $customer_default_country = get_option('customer_default_country');
            $selected =( isset($client) ? $client->country : $customer_default_country);
            echo render_select( 'country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
            ?>
            
            <?php $value=( isset($client) ? $client->city : ''); ?>
            <?php echo render_select( 'city', get_all_province(), array('provinceid','name') , 'client_city',$value,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
            
            <?php $value=( isset($client) ? $client->state : ''); ?>
            <?php echo render_select( 'state', array(), array('districtid', 'name'),'client_district',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
            
            <?php $value=( isset($client) ? $client->address_ward : ''); ?>
            <?php echo render_select( 'address_ward', array(), array('districtid', 'name'),'client_ward',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
            
            <?php $value=( isset($client) ? $client->address_room_number : ''); ?>
            <?php echo render_input( 'address_room_number', 'client_address_room_number',$value, 'text', array()); ?>

            <?php $value=( isset($client) ? $client->address_building : ''); ?>
            <?php echo render_input( 'address_building', 'client_address_building',$value, 'text', array()); ?>

            <?php $value=( isset($client) ? $client->address_home_number : ''); ?>
            <?php echo render_input( 'address_home_number', 'client_address_home_number',$value, 'text', array()); ?>

            <?php $value=( isset($client) ? $client->address : ''); ?>
            <?php echo render_input( 'address', 'client_address',$value, 'text', array()); ?>

            <?php $value=( isset($client) ? $client->address_town : ''); ?>
            <?php echo render_input( 'address_town', 'client_address_town',$value, 'text', array()); ?>

            <?php $value=( isset($client) ? $client->zip : ''); ?>
            <?php echo render_input( 'zip', 'client_postal_code',$value, 'text', array()); ?>
            

            <?php $value=( isset($client) ? $client->website : ''); ?>
            <?php echo render_input( 'website', 'client_website',$value); ?>
            
            
       </div>
       <div class="col-md-12">
        <?php $rel_id=( isset($client) ? $client->userid : false); ?>
        <?php echo render_custom_fields( 'customers',$rel_id); ?>
    </div>
</div>
</div>
<?php if(isset($client)){ ?>
<div role="tabpanel" class="tab-pane" id="contacts">
    <?php if(has_permission('customers','','create') || is_customer_admin($client->userid)){
        $disable_new_contacts = false;
        if(is_empty_customer_company($client->userid) && total_rows('tblcontacts',array('userid'=>$client->userid)) == 1){
           $disable_new_contacts = true;
       }
       ?>
       <div class="inline-block"<?php if($disable_new_contacts){ ?> data-toggle="tooltip" data-title="<?php echo _l('customer_contact_person_only_one_allowed'); ?>"<?php } ?>>
        <a href="#" onclick="contact(<?php echo $client->userid; ?>); return false;" class="btn btn-info mbot25<?php if($disable_new_contacts){echo ' disabled';} ?>"><?php echo _l('new_contact'); ?></a>
    </div>
    <?php } ?>
    <?php
    $table_data = array(_l('client_firstname'),_l('client_lastname'),_l('client_email'),_l('contact_position'),_l('client_phonenumber'),_l('contact_active'),_l('clients_list_last_login'));
    $custom_fields = get_custom_fields('contacts',array('show_on_table'=>1));
    foreach($custom_fields as $field){
       array_push($table_data,$field['name']);
   }
   array_push($table_data,_l('options'));
   echo render_datatable($table_data,'contacts'); ?>
</div>
<div role="tabpanel" class="tab-pane" id="customer_admins">
    <?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
    <a href="#" data-toggle="modal" data-target="#customer_admins_assign" class="btn btn-info mbot30"><?php echo _l('assign_admin'); ?></a>
    <?php } ?>
    <table class="table dt-table">
        <thead>
            <tr>
                <th><?php echo _l('staff_member'); ?></th>
                <th><?php echo _l('customer_admin_date_assigned'); ?></th>
                <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                <th><?php echo _l('options'); ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($customer_admins as $c_admin){ ?>
            <tr>
                <td><a href="<?php echo admin_url('profile/'.$c_admin['staff_id']); ?>">
                    <?php echo staff_profile_image($c_admin['staff_id'], array(
                        'staff-profile-image-small',
                        'mright5'
                        ));
                        echo get_staff_full_name($c_admin['staff_id']); ?></a>
                    </td>
                    <td data-order="<?php echo $c_admin['date_assigned']; ?>"><?php echo _dt($c_admin['date_assigned']); ?></td>
                    <?php if(has_permission('customers','','create') || has_permission('customers','','edit')){ ?>
                    <td>
                        <a href="<?php echo admin_url('clients/delete_customer_admin/'.$client->userid.'/'.$c_admin['staff_id']); ?>" class="btn btn-danger _delete btn-icon"><i class="fa fa-remove"></i></a>
                    </td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <div role="tabpanel" class="tab-pane" id="billing_and_shipping">
        <div class="row">
            <div class="col-md-6">
                <h4><?php echo _l('billing_address'); ?> <a href="#" class="pull-right billing-same-as-customer"><small class="text-info font-medium-xs"><?php echo _l('customer_billing_same_as_profile'); ?></small></a></h4>
                <hr />
                <?php
                    $default_source_id = 0;
                    if(!isset($client) && count($areas) > 0) {
                        $default_source_id = $areas[0]['id'];
                    }
                    echo render_select('billing_area', $areas, array('id','name'),'client_area', !isset($client) ? $default_source_id : $client->billing_area, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); 
                ?>
                <?php $countries= get_all_countries();
                $customer_default_country = get_option('customer_default_country');
                $selected =( isset($client) ? $client->billing_country : $customer_default_country);
                echo render_select( 'billing_country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                ?>
                
                <?php $value=( isset($client) ? $client->billing_city : ''); ?>
                <?php echo render_select( 'billing_city', get_all_province(), array('provinceid','name') , 'client_city',$value,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                
                <?php $value=( isset($client) ? $client->billing_state : ''); ?>
                <?php echo render_select( 'billing_state', array(), array('districtid', 'name'),'client_district',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                
                <?php $value=( isset($client) ? $client->billing_ward : ''); ?>
                <?php echo render_select( 'billing_ward', array(), array('districtid', 'name'),'client_ward',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                
                <?php $value=( isset($client) ? $client->billing_room_number : ''); ?>
                <?php echo render_input( 'billing_room_number', 'client_address_room_number',$value, 'text', array()); ?>

                <?php $value=( isset($client) ? $client->billing_building : ''); ?>
                <?php echo render_input( 'billing_building', 'client_address_building',$value, 'text', array()); ?>

                <?php $value=( isset($client) ? $client->billing_home_number : ''); ?>
                <?php echo render_input( 'billing_home_number', 'client_address_home_number',$value, 'text', array()); ?>

                <?php $value=( isset($client) ? $client->billing_street : ''); ?>
                <?php echo render_input( 'billing_street', 'client_address',$value, 'text', array()); ?>

                <?php $value=( isset($client) ? $client->billing_town : ''); ?>
                <?php echo render_input( 'billing_town', 'client_address_town',$value, 'text', array()); ?>

                <?php $value=( isset($client) ? $client->billing_zip : ''); ?>
                <?php echo render_input( 'billing_zip', 'client_postal_code',$value, 'text', array()); ?>
            </div>
            <div class="col-md-6">
                <h4>
                    <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('customer_shipping_address_notice'); ?>"></i>
                    <?php echo _l('shipping_address'); ?> <a href="#" class="pull-right customer-copy-billing-address"><small class="text-info font-medium-xs"><?php echo _l('customer_billing_copy'); ?></small></a></h4>
                    <hr />
                    <?php
                        $default_source_id = 0;
                        if(!isset($client) && count($areas) > 0) {
                            $default_source_id = $areas[0]['id'];
                        }
                        echo render_select('shipping_area', $areas, array('id','name'),'client_area', !isset($client) ? $default_source_id : $client->shipping_area, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); 
                    ?>
                    <?php $countries= get_all_countries();
                    $customer_default_country = get_option('customer_default_country');
                    $selected =( isset($client) ? $client->shipping_country : $customer_default_country);
                    echo render_select( 'shipping_country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                    ?>
                    
                    <?php $value=( isset($client) ? $client->shipping_city : ''); ?>
                    <?php echo render_select( 'shipping_city', get_all_province(), array('provinceid','name') , 'client_city',$value,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                    
                    <?php $value=( isset($client) ? $client->shipping_state : ''); ?>
                    <?php echo render_select( 'shipping_state', array(), array('districtid', 'name'),'client_district',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                    
                    <?php $value=( isset($client) ? $client->shipping_ward : ''); ?>
                    <?php echo render_select( 'shipping_ward', array(), array('districtid', 'name'),'client_ward',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                    
                    <?php $value=( isset($client) ? $client->shipping_room_number : ''); ?>
                    <?php echo render_input( 'shipping_room_number', 'client_address_room_number',$value, 'text', array()); ?>

                    <?php $value=( isset($client) ? $client->shipping_building : ''); ?>
                    <?php echo render_input( 'shipping_building', 'client_address_building',$value, 'text', array()); ?>

                    <?php $value=( isset($client) ? $client->shipping_home_number : ''); ?>
                    <?php echo render_input( 'shipping_home_number', 'client_address_home_number',$value, 'text', array()); ?>

                    <?php $value=( isset($client) ? $client->shipping_street : ''); ?>
                    <?php echo render_input( 'shipping_street', 'client_address',$value, 'text', array()); ?>

                    <?php $value=( isset($client) ? $client->shipping_town : ''); ?>
                    <?php echo render_input( 'shipping_town', 'client_address_town',$value, 'text', array()); ?>

                    <?php $value=( isset($client) ? $client->shipping_zip : ''); ?>
                    <?php echo render_input( 'shipping_zip', 'client_postal_code',$value, 'text', array()); ?>
                    
                </div>
                <?php if(isset($client) &&
                (total_rows('tblinvoices',array('clientid'=>$client->userid)) > 0 || total_rows('tblestimates',array('clientid'=>$client->userid)) > 0)){ ?>
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <div class="checkbox checkbox-default">
                            <input type="checkbox" name="update_all_other_transactions" id="update_all_other_transactions">
                            <label for="update_all_other_transactions">
                                <?php echo _l('customer_update_address_info_on_invoices'); ?><br />
                            </label>
                        </div>
                        <b><?php echo _l('customer_update_address_info_on_invoices_help'); ?></b>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="col-md-6">
                <h4><?php echo _l('dkkd_address'); ?> <a href="#" class="pull-right customer-copy-billing-address-dkkd"><small class="text-info font-medium-xs"><?php echo _l('customer_billing_same_as_profile'); ?></small></a></h4>
                <hr />
                
                <?php
                    $default_source_id = 0;
                    if(!isset($client) && count($areas) > 0) {
                        $default_source_id = $areas[0]['id'];
                    }
                    echo render_select('dkkd_area', $areas, array('id','name'),'client_area', !isset($client) ? $default_source_id : $client->dkkd_area, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); 
                ?>

                <?php $countries= get_all_countries();
                $customer_default_country = get_option('customer_default_country');
                $selected =( isset($client) ? $client->dkkd_country : $customer_default_country);
                echo render_select( 'dkkd_country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                ?>
                
                <?php $value=( isset($client) ? $client->dkkd_city : ''); ?>
                <?php echo render_select( 'dkkd_city', get_all_province(), array('provinceid','name') , 'client_city',$value,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                
                <?php $value=( isset($client) ? $client->dkkd_state : ''); ?>
                <?php echo render_select( 'dkkd_state', array(), array('districtid', 'name'),'client_district',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                
                <?php $value=( isset($client) ? $client->dkkd_ward : ''); ?>
                <?php echo render_select( 'dkkd_ward', array(), array('districtid', 'name'),'client_ward',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                
                <?php $value=( isset($client) ? $client->dkkd_room_number : ''); ?>
                <?php echo render_input( 'dkkd_room_number', 'client_address_room_number',$value, 'text', array()); ?>

                <?php $value=( isset($client) ? $client->dkkd_building : ''); ?>
                <?php echo render_input( 'dkkd_building', 'client_address_building',$value, 'text', array()); ?>

                <?php $value=( isset($client) ? $client->dkkd_home_number : ''); ?>
                <?php echo render_input( 'dkkd_home_number', 'client_address_home_number',$value, 'text', array()); ?>

                <?php $value=( isset($client) ? $client->dkkd_street : ''); ?>
                <?php echo render_input( 'dkkd_street', 'client_address',$value, 'text', array()); ?>

                <?php $value=( isset($client) ? $client->dkkd_town : ''); ?>
                <?php echo render_input( 'dkkd_town', 'client_address_town',$value, 'text', array()); ?>

                <?php $value=( isset($client) ? $client->dkkd_zip : ''); ?>
                <?php echo render_input( 'dkkd_zip', 'client_postal_code',$value, 'text', array()); ?>
            </div>
        </div>
        
        <button class="btn btn-info mtop20 only-save customer-form-submiter">
            <?php echo _l( 'submit'); ?>
        </button>
        <?php if(!isset($client)){ ?>
        <button class="btn btn-info mtop20 save-and-add-contact customer-form-submiter">
            <?php echo _l( 'save_customer_and_add_contact'); ?>
        </button>
        <?php } ?>
    </div>
</div>
<?php echo form_close(); ?>
</div>
<div id="contact_data"></div>
<?php if(isset($client)){ ?>
<?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
<div class="modal fade" id="customer_admins_assign" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('clients/assign_admins/'.$client->userid)); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('assign_admin'); ?></h4>
            </div>
            <div class="modal-body">
                <?php
                $selected = array();
                foreach($customer_admins as $c_admin){
                   array_push($selected,$c_admin['staff_id']);
               }
               echo render_select('customer_admins[]',$staff,array('staffid',array('firstname','lastname')),'',$selected,array('multiple'=>true),array(),'','',false); ?>
           </div>
           <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
        </div>
    </div>
    <!-- /.modal-content -->
    <?php echo form_close(); ?>
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php } ?>
<?php } ?>