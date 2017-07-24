  <h4 class="bold no-margin"><?php echo _l('supplier_add_edit_profile'); ?></h4>
  <hr class="no-mbot no-border" />
  <div class="row">
    <?php echo form_open($this->uri->uri_string(),array('class'=>'supplier-form','autocomplete'=>'off')); ?>
    <div class="additional"></div>
    <div class="col-md-12">
        <ul class="nav nav-tabs profile-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#contact_info" aria-controls="contact_info" role="tab" data-toggle="tab">
                    <?php echo _l( 'supplier_profile_details'); ?>
                </a>
            </li>
            <?php if(isset($supplier)){ ?>
            <li role="presentation">
                <a href="#contacts" aria-controls="contacts" role="tab" data-toggle="tab">
                    <?php echo _l( 'customer_contacts'); ?>
                </a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="contact_info">
                <div class="row">
                <?php if(!isset($supplier) || isset($supplier) && !is_empty_customer_company($supplier->userid)) { ?>
                    <div class="col-md-12">
                       <div class="checkbox checkbox-success mbot20 no-mtop">
                           <input type="checkbox" name="show_primary_contact"<?php if(isset($supplier) && $supplier->show_primary_contact == 1){echo ' checked';}?> value="1" id="show_primary_contact">
                           <label for="show_primary_contact"><?php echo _l('show_primary_contact',_l('invoices').', '._l('estimates').', '._l('payments')); ?></label>
                       </div>
                   </div>
                   
                   <?php } ?>
                   <div class="col-md-6">
                      <div class="form-group">
                           <label for="number"><?php echo _l('Mã nhà cung cấp'); ?></label>
                           <div class="input-group">
                            <span class="input-group-addon">
                              <?php echo get_option('prefix_supplier') ?></span>
                              <?php 
                                // var_dump($purchase);
                                if($supplier)
                                {

                                  $number=$supplier->supplier_code;
                                }
                                else
                                {
                                  $number=sprintf('%05d',getMaxID('userid','tblsuppliers')+1);
                                }
                              ?>
                              <input type="text" name="supplier_code" class="form-control" value="<?=$number ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" readonly>
                            </div>
                        </div>                  
                    <?php
                    $value= ( isset($supplier) ? $supplier->company : ''); 
                    $attrs = (isset($supplier) ? array() : array('autofocus'=>true));
                    $value = ( isset($supplier) ? $supplier->company : ''); ?>
                    <?php echo render_input( 'company', _l("supplier_name"),$value,'text',$attrs); ?>
                    
                    <?php
                    $c_attrs_personal = (isset($supplier) ? ($supplier->client_type == 1 ? array() : array('style' => 'display:none')) : array());
                    $short_name = ( isset($supplier) ? $supplier->short_name : "" );
                    echo render_input( 'short_name', 'client_shortname' , $short_name, 'text', array(), $c_attrs_personal); ?>                    
                    
                    <?php $value = (isset($supplier) ? _d($supplier->birthday) : _d(date('Y-m-d')));
                        $label = _l('client-company-birthday');
                    echo render_date_input('birthday', $label, $value); ?>

                    <?php
                    
                        $value_type_of_organization = ( isset($supplier) ? $supplier->type_of_organization : "" );
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
                    $group_attrs_company = array();

                    echo render_select('type_of_organization',$type_of_organization,array('id','name'),'type_of_organization',$value_type_of_organization,$s_attrs, $group_attrs_company); ?>

                    <?php
                    $c_attrs_personal = array();
                    $bussiness_registration_number = ( isset($supplier) ? $supplier->bussiness_registration_number : "" );
                    echo render_input( 'bussiness_registration_number', 'bussiness_registration_number' , $bussiness_registration_number, 'text', array(), $c_attrs_personal ); ?>
                    
                    <!-- <?php
                    $legal_representative = ( isset($supplier) ? $supplier->legal_representative : "" );
                    echo render_input( 'legal_representative', 'legal_representative' , $legal_representative, 'text', array(), $c_attrs_personal ); ?> -->

                    <?php
                    $email = ( isset($supplier) ? $supplier->email : "" );
                    echo render_input( 'email', 'email' , $email ,'email',array('autocomplete'=>'off')); ?>

                    <?php                    
                    $cooperative_day = ( isset($supplier) ? _d($supplier->cooperative_day) : _d(date('Y-m-d')));
                    echo render_date_input( 'cooperative_day', 'cooperative_day' , $cooperative_day, array(), $c_attrs_personal ); ?>

                    <?php                    
                    if(get_option('company_requires_vat_number_field') == 1){
                        $value=( isset($supplier) ? $supplier->vat : '');
                        echo render_input( 'vat', 'client_vat_number',$value, 'text', array(), $c_attrs_personal);
                    }
                    $s_attrs = array('data-none-selected-text'=>_l('system_default_string'));
                    $selected = '';
                    if(isset($supplier) && client_have_transactions($supplier->userid)){
                      $s_attrs['disabled'] = true;
                  }
                  foreach($currencies as $currency){
                    if(isset($supplier)){
                      if($currency['id'] == $supplier->default_currency){
                        $selected = $currency['id'];
                    }
                }
            }
            ?>
            <?php if(!isset($supplier)){ ?>
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
                        if(isset($supplier)){
                           if($supplier->default_language == $language){
                              $selected = 'selected';
                          }
                      }
                      ?>
                      <option value="<?php echo $language; ?>" <?php echo $selected; ?>><?php echo ucfirst($language); ?></option>
                      <?php } ?>
                  </select>
              </div>

                <?php
                $default_source_id = 0;
                if(!isset($supplier) && count($sources) > 0) {
                    $default_source_id = $sources[0]['id'];
                }
                echo render_select('source_approach', $sources, array('id','name'),'source_from', !isset($supplier) ? $default_source_id : $supplier->source_approach, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); 
                ?>

          </div>
          <div class="col-md-6">
              
            <!-- <?php
                $name_title = ( isset($supplier) ? $supplier->name_title : "" );
                echo render_input( 'name_title', 'client_name_title' , $name_title ); ?> -->
                
            <?php
            $c_attrs_personal = array();

            $phonenumber = ( isset($supplier) ? $supplier->phonenumber : "" );
            echo render_input( 'phonenumber', 'client_phonenumber',$phonenumber, 'text', array(), $c_attrs_personal);

            ?>

            <?php
                $default_source_id = 0;
                if(!isset($supplier) && count($areas) > 0) {
                    $default_source_id = $areas[0]['id'];
                }
                echo render_select('address_area', $areas, array('id','name'),'client_area', !isset($supplier) ? $default_source_id : $supplier->address_area, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); 
            ?>

            <?php $countries= get_all_countries();
            $customer_default_country = get_option('customer_default_country');
            $selected =( isset($supplier) ? $supplier->country : $customer_default_country);
            echo render_select( 'country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
            ?>
            
            <?php $value=( isset($supplier) ? $supplier->city : ''); ?>
            <?php echo render_select( 'city', get_all_province(), array('provinceid','name') , 'client_city',$value,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
            
            <?php $value=( isset($supplier) ? $supplier->state : ''); ?>
            <?php echo render_select( 'state', array(), array('districtid', 'name'),'client_district',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
            
            <?php $value=( isset($supplier) ? $supplier->address_ward : ''); ?>
            <?php echo render_select( 'address_ward', array(), array('districtid', 'name'),'client_ward',$value, array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
            
            <?php $value=( isset($supplier) ? $supplier->address_room_number : ''); ?>
            <?php echo render_input( 'address_room_number', 'client_address_room_number',$value, 'text', array()); ?>

            <?php $value=( isset($supplier) ? $supplier->address_building : ''); ?>
            <?php echo render_input( 'address_building', 'client_address_building',$value, 'text', array()); ?>

            <?php $value=( isset($supplier) ? $supplier->address_home_number : ''); ?>
            <?php echo render_input( 'address_home_number', 'client_address_home_number',$value, 'text', array()); ?>

            <?php $value=( isset($supplier) ? $supplier->address : ''); ?>
            <?php echo render_input( 'address', 'client_address',$value, 'text', array()); ?>

            <?php $value=( isset($supplier) ? $supplier->address_town : ''); ?>
            <?php echo render_input( 'address_town', 'client_address_town',$value, 'text', array()); ?>

            <?php $value=( isset($supplier) ? $supplier->zip : ''); ?>
            <?php echo render_input( 'zip', 'client_postal_code',$value, 'text', array()); ?>
            

            <?php $value=( isset($supplier) ? $supplier->website : ''); ?>
            <?php echo render_input( 'website', 'client_website',$value); ?>
            
            
       </div>
       
</div>
</div>
<?php if(isset($supplier)){ ?>
<div role="tabpanel" class="tab-pane" id="contacts">
    <?php if(has_permission('customers','','create') || is_customer_admin($supplier->userid)){
        $disable_new_contacts = false;
        if(is_empty_customer_company($supplier->userid) && total_rows('tblcontacts',array('userid'=>$supplier->userid)) == 1){
           $disable_new_contacts = true;
       }
       ?>
       <div class="inline-block"<?php if($disable_new_contacts){ ?> data-toggle="tooltip" data-title="<?php echo _l('customer_contact_person_only_one_allowed'); ?>"<?php } ?>>
        <a href="#" onclick="contact(<?php echo $supplier->userid; ?>); return false;" class="btn btn-info mbot25<?php if($disable_new_contacts){echo ' disabled';} ?>"><?php echo _l('new_contact'); ?></a>
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

    <?php } ?>
    
        
        <button class="btn btn-info mtop20 only-save customer-form-submiter">
            <?php echo _l( 'submit'); ?>
        </button>
        <?php if(!isset($supplier)){ ?>
        <button class="btn btn-info mtop20 save-and-add-contact customer-form-submiter">
            <?php echo _l( 'save_customer_and_add_contact'); ?>
        </button>
        <?php } ?>
    </div>
</div>
<?php echo form_close(); ?>
</div>
<div id="contact_data"></div>
