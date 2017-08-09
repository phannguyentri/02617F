<div class="lead-wrapper" <?php if(isset($lead) && ($lead->junk == 1 || $lead->lost == 1)){ echo 'lead-is-junk-or-lost';} ?>>

<div class="clearfix no-margin"></div>
    <hr class="no-margin" />
    <!-- begin bang cuoc goi nho-->
    <div class="row">
        <div class="lead-view">
            <div class="col-md-4 col-xs-12 mtop15">
                <!-- <div class="lead-info-heading">
                    <h4 class="no-margin font-medium-xs bold">
                        <?php echo _l('client_string_contracts_table_heading'); ?>
                    </h4>
                </div> -->
                <p class="text-muted lead-field-heading no-mtop"><?php echo _l('client_type'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->client_type != '' ? ($client->client_type == 1 ? "Cá nhân" : "Tổ chức") : '-') ?></p>
                <p class="text-muted lead-field-heading no-mtop "><?php echo _l("client-name") . " " . _l(( isset($client) ? ($client->client_type == 2 ? "client-company" : "client-personal") : 'client-personal' )); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->company != '' ? $client->company : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('client_shortname'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->short_name != '' ? $client->short_name : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('client_phonenumber'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->phonenumber != '' ? $client->phonenumber : '-') ?></p>

                <?php 
                $mobilephone_text = ( isset($client) ? $client->mobilephone_number : ""); 
                $mobilephone_text = implode(", " ,explode(",", $mobilephone_text));
                ?>
                <p class="text-muted lead-field-heading"><?php echo _l('client-mobilephone'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->mobilephone_number != '' ? $mobilephone_text : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('client_address_room_number'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->address_room_number != '' ? $client->address_room_number : '-') ?></p>

                <p class="text-muted lead-field-heading"><?php echo _l('client_address_building'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->address_building != '' ? $client->address_building : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('client_address_home_number'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->address_home_number != '' ? $client->address_home_number : '-') ?></p>

                <p class="text-muted lead-field-heading"><?php echo _l('client_street'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->address != '' ? $client->address : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('client_address_town'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->address_town != '' ? $client->address_town : '-') ?></p>
                
                

                
                

            </div>
            <div class="col-md-4 col-xs-12 mtop15">

                <?php 
                    $countries= get_all_countries();
                    $customer_default_country = get_option('customer_default_country');
                    foreach($countries as $country_item) {
                        if($country_item['country_id'] == $client->country) {
                            $country = $country_item['short_name'];
                            break;
                        }
                    }
                    $country =( isset($client) ? $country : $customer_default_country);
                ?>
                <p class="text-muted lead-field-heading"><?php echo _l('clients_country'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->country != '' ? $country : '-') ?></p>
                
                <?php 
                    $area = "-";
                    // print_r($areas);
                    foreach($areas as $area_item) {
                        if($area_item['id'] == $client->address_area) {
                            $area = $area_item['name'];
                            break;
                        }
                    }
                ?>
                <p class="text-muted lead-field-heading"><?php echo _l('client_area'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->address_area != '' ? $area : '-') ?></p>

                <p class="text-muted lead-field-heading"><?php echo _l('client_postal_code'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->zip != '' ? $client->zip : '-') ?></p>
                <?php
                    $provinces = get_all_province();
                    $province = "";
                    // print_r($areas);
                    foreach($provinces as $province_item) {
                        if($province_item['provinceid'] == $client->city) {
                            $province = $province_item['name'];
                            break;
                        }
                    }
                ?>
                <p class="text-muted lead-field-heading"><?php echo _l('client_city'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $province != '' ? $province : '-') ?></p>
                <?php
                    $districts = get_all_district();
                    $district = "";
                    // print_r($areas);
                    foreach($districts as $district_item) {
                        if($district_item['districtid'] == $client->city) {
                            $district = $district_item['name'];
                            break;
                        }
                    }
                ?>
                <p class="text-muted lead-field-heading"><?php echo _l('client_district'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $district != '' ? $district : '-') ?></p>
                <?php
                    $wards = get_all_wards();
                    $ward = "";
                    // print_r($areas);
                    foreach($wards as $ward_item) {
                        if($ward_item['wardid'] == $client->address_ward) {
                            $ward = $ward_item['name'];
                            break;
                        }
                    }
                ?>
                <p class="text-muted lead-field-heading"><?php echo _l('client_ward'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $ward != '' ? $ward : '-') ?></p>


                <p class="text-muted lead-field-heading"><?php echo _l('type_of_organization'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->type_of_organization != '' ? $client->type_of_organization : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('bussiness_registration_number'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->bussiness_registration_number != '' ? $client->bussiness_registration_number : '-') ?></p>


                <p class="text-muted lead-field-heading"><?php echo _l('legal_representative'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->legal_representative != '' ? $client->legal_representative : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('email'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->email != '' ? '<a href="mailto:'.$client->email.'">' . $client->email.'</a>' : '-') ?></p>

                <p class="text-muted lead-field-heading"><?php echo _l('id_card'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->id_card != '' ? $client->id_card : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('client_vat_number'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->vat != '' ? $client->vat : '-') ?></p>
            </div>
            <div class="col-md-4 col-xs-12 mtop15">
                
                
                <p class="text-muted lead-field-heading"><?php echo (isset($client) ? ($client->client_type == 2 ? _l('client-company-birthday') : _l('date_birth')) : _l('date_birth')); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->birthday != '0000-00-00' ? date('d/m/Y', strtotime($client->birthday)) : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('client_website'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->website != '' ? '<a href="'.$client->website.'">' . $client->website.'</a>' : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('cooperative_day'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $client->cooperative_day != '0000-00-00' ? date('d/m/Y', strtotime($client->cooperative_day)) : '-') ?></p>


                <?php
                    $source = "";
                    // print_r($areas);
                    foreach($sources as $source_item) {
                        if($source_item['id'] == $client->source_approach) {
                            $source = $source_item['name'];
                            break;
                        }
                    }
                    $user_referrer = "";
                    foreach($users as $user_item) {
                        if($user_item['userid'] == $client->user_referrer) {
                            $user_referrer = $user_item['company'];
                            break;
                        }
                    }
                    $group = array();
                    
                    foreach($customer_groups as $customer_group_item) {
                        foreach($groups as $group_item) {
                            if(trim($group_item['id']) == trim($customer_group_item['groupid'])){
                                $group[] = $group_item['name'];
                                break;
                            }
                        }
                    }
                    $currency = "";
                    foreach($currencies as $currency_item){
                        if($currency_item['id'] == $client->default_currency){
                            $currency = $currency_item['name'];
                        }
                    }
                ?>
                <p class="text-muted lead-field-heading"><?php echo _l('source_from'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $source != '' ? $source : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('client_user_referrer'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $user_referrer != '' ? $user_referrer : '-') ?></p>

                <p class="text-muted lead-field-heading"><?php echo _l('client_relationship'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && count($group) != 0 ? implode(", ", $group) : '-') ?></p>
                <p class="text-muted lead-field-heading"><?php echo _l('invoice_add_edit_currency'); ?></p>
                <p class="bold font-medium-xs"><?php echo (isset($client) && $currency != '' ? $currency : '-') ?></p>
                
            </div>
            
        </div>
        <div class="clearfix">  </div>
    </div>

    <!-- end bang cuoc goi nho-->
    <?php if($lead_locked == false){ ?>
        <div class="lead-edit<?php if(isset($lead)){echo ' hide';} ?>">
            <hr />
            <button type="button" class="btn btn-default pull-right mright5" data-dismiss="modal"><?php echo _l('close'); ?></button>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
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
