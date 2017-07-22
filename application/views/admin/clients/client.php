<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
 <div class="content">
   <div class="row">
    <div class="col-md-12">
    <?php if(isset($client) && $client->active == 0){ ?>
    <div class="alert alert-warning">
        <?php echo _l('
        '); ?>
        <br />
        <a href="<?php echo admin_url('clients/mark_as_active/'.$client->userid); ?>"><?php echo _l('mark_as_active'); ?></a>
    </div>
    <?php } ?>
    <?php if(isset($client) && $client->leadid != NULL){ ?>
    <div class="alert alert-info">
     <a href="#" onclick="init_lead(<?php echo $client->leadid; ?>); return false;"><?php echo _l('customer_from_lead',_l('lead')); ?></a>
   </div>
   <?php } ?>
   <?php if(isset($client) && (!has_permission('customers','','view') && is_customer_admin($client->userid))){?>
   <div class="alert alert-info">
     <?php echo _l('customer_admin_login_as_client_message',get_staff_full_name(get_staff_user_id())); ?>
   </div>
   <?php } ?>
   </div>
   <?php if(isset($client)){ ?>
   <div class="col-md-3">
     <div class="panel_s">
       <div class="panel-body">
        <?php if(has_permission('customers','','delete') || is_admin()){ ?>
        <div class="btn-group pull-left mright10">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-left">
            <?php if(is_admin()){ ?>
            <li>
              <a href="<?php echo admin_url('clients/login_as_client/'.$client->userid); ?>" target="_blank">
                <i class="fa fa-share-square-o"></i> <?php echo _l('login_as_client'); ?>
              </a>
            </li>
            <?php } ?>
            <?php if(has_permission('customers','','delete')){ ?>
            <li>
              <a href="<?php echo admin_url('clients/delete/'.$client->userid); ?>" class="text-danger delete-text _delete" data-toggle="tooltip" data-title="<?php echo _l('client_delete_tooltip'); ?>" data-placement="bottom"><i class="fa fa-remove"></i> <?php echo _l('delete'); ?>
              </a>
            </li>
            <?php } ?>
          </ul>
        </div>
        <?php } ?>
        <h4 class="customer-heading-profile bold"><?php echo $title; ?></h4>
        <?php $this->load->view('admin/clients/tabs'); ?>
      </div>
    </div>
  </div>
  <?php } ?>
  <div class="col-md-<?php if(isset($client)){echo 9;} else {echo 12;} ?>">
   <div class="panel_s">
     <div class="panel-body">
      <?php if(isset($client)){ ?>
      <?php echo form_hidden( 'isedit'); ?>
      <?php echo form_hidden( 'userid',$client->userid); ?>
      <div class="clearfix"></div>
      <?php } ?>
      <div>
       <div class="tab-content">
        <?php $this->load->view('admin/clients/groups/'.$group); ?>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
  // others script write here
  var client_type = $('#client_type option:selected').val();
  console.log(client_type);
  var switchMode = () => {
    var name_title                    = $('#name_title');
    var cooperative_day               = $('#cooperative_day');
    var legal_representative          = $('#legal_representative');
    var bussiness_registration_number = $('#bussiness_registration_number');
    var id_card                       = $('#id_card');
    var birthday                      = $('label[for="birthday"]');
    var mobilephone_number            = $('#mobilephone_number,#phonenumber');
    var type_of_organization          = $('#type_of_organization');
    var vat                           = $('#vat');
    var short_name                    = $('#short_name');
    var billing_col                   = $('li:has(a[aria-controls="billing_and_shipping"])');
    if(client_type == 1) {
      $('label[for="company"]').html('<small class="req text-danger">* </small> <?=_l('client-name')?> <?=_l('client-personal')?>');
      
      // Company
      birthday.html('<?=_l('date_birth')?>');
      type_of_organization.parent().parent().hide();
      vat.parent().hide();
      bussiness_registration_number.parent().hide();
      legal_representative.parent().hide();
      cooperative_day.parent().parent().hide();
      billing_col.hide();

      // Personal
      id_card.parent().show();
      mobilephone_number.parent().show();
      short_name.parent().show();
    }
    else {
      $('label[for="company"]').html('<small class="req text-danger">* </small> <?=_l('client-name')?> <?=_l('client-company')?>');
      // Company
      birthday.html('<?=_l('client-company-birthday')?>');
      type_of_organization.parent().parent().show();
      vat.parent().show();
      bussiness_registration_number.parent().show();
      legal_representative.parent().show();
      cooperative_day.parent().parent().show();
      billing_col.show();

      // Personal
      id_card.parent().hide();
      mobilephone_number.parent().hide();
      short_name.parent().hide();
    }
  };
  // switchMode();
  $(document).ready(()=>{
    var default_city  = '<?php echo isset($client) ? $client->city : 0 ?>';
    var default_state = '<?php echo isset($client) ? $client->state : 0 ?>';
    var default_ward  = '<?php echo isset($client) ? $client->address_ward : 0?>';

    var default_city_billing  = '<?php echo isset($client) ? $client->billing_city : 0 ?>';
    var default_state_billing = '<?php echo isset($client) ? $client->billing_state : 0 ?>';
    var default_ward_billing  = '<?php echo isset($client) ? $client->billing_ward : 0?>';

    var default_city_shipping  = '<?php echo isset($client) ? $client->shipping_city : 0 ?>';
    var default_state_shipping = '<?php echo isset($client) ? $client->shipping_state : 0 ?>';
    var default_ward_shipping  = '<?php echo isset($client) ? $client->shipping_ward : 0?>';

    var default_city_dkkd  = '<?php echo isset($client) ? $client->dkkd_city : 0 ?>';
    var default_state_dkkd = '<?php echo isset($client) ? $client->dkkd_state : 0 ?>';
    var default_ward_dkkd  = '<?php echo isset($client) ? $client->dkkd_ward : 0?>';

    var loadFromCity = (city_id, currentTarget, default_value_state, default_value_ward) => {
      console.log();
      var objState = $(currentTarget).parent().parent().next().find('select');
      var objWard = $(currentTarget).parent().parent().next().next().find('select');
      objState.find('option').remove();
      objState.append('<option value=""></option>');
      objWard.find('option').remove();
      objWard.append('<option value=""></option>');
      
      objState.selectpicker("refresh");
      objWard.selectpicker("refresh");

      if(city_id != 0 && city_id != '') {
        $.ajax({
          url : admin_url + 'clients/get_districts/' + city_id,
          dataType : 'json',
        })
        .done((data) => {          
          objState.find('option').remove();
          objState.append('<option value=""></option>');
          var foundSelected = false;
          $.each(data, (key,value) => {
            var stringSelected = "";
            if(!foundSelected && value.districtid == default_value_state) {
              stringSelected = ' selected="selected"';
              foundSelected = true;
            }
            objState.append('<option value="' + value.districtid + '"'+stringSelected+'>' + value.name + '</option>');
          });
          objState.selectpicker('refresh');
          if(foundSelected) {
            loadFromState(default_value_state, objState, default_value_ward);
          }
        });
      }
    };
    var loadFromState = (state_id, currentTarget, default_value_ward) => {
      var objWard = $(currentTarget).parent().parent().next().find('select');

      objWard.find('option').remove();
      objWard.append('<option value=""></option>');
      objWard.selectpicker("refresh");
      if(state_id != 0 && state_id != '') {
        $.ajax({
          url : admin_url + 'clients/get_wards/' + state_id,
          dataType : 'json',
        })
        .done((data) => {
          $.each(data, (key,value) => {
            var stringSelected = "";
            if(value.wardid == default_value_ward) {
              stringSelected = 'selected="selected"';
            }
            objWard.append('<option value="' + value.wardid + '"' + stringSelected + '>' + value.name + '</option>');
          });
          objWard.selectpicker('refresh');
        });
      }
    };
    
    $('#city').change((e)=>{
      var city_id = $(e.currentTarget).val();
      loadFromCity(city_id, e.currentTarget, default_state, default_ward);
    });
    $('#billing_city').change((e)=>{
      var city_id = $(e.currentTarget).val();
      loadFromCity(city_id, e.currentTarget, default_state_billing, default_ward_billing);
    });
    $('#shipping_city').change((e)=>{
      var city_id = $(e.currentTarget).val();
      loadFromCity(city_id, e.currentTarget, default_state_shipping, default_ward_shipping);
    });
    $('#dkkd_city').change((e)=>{
      var city_id = $(e.currentTarget).val();
      loadFromCity(city_id, e.currentTarget, default_state_dkkd, default_ward_dkkd);
    });

    $('#state').change((e)=>{
      var state_id = $(e.currentTarget).val();
      loadFromState(state_id, e.currentTarget, default_ward);
    });
    $('#billing_state').change((e)=>{
      var state_id = $(e.currentTarget).val();
      loadFromState(state_id, e.currentTarget, default_ward_billing);
    });
    $('#shipping_state').change((e)=>{
      var state_id = $(e.currentTarget).val();
      loadFromState(state_id, e.currentTarget, default_ward_shipping);
    });
    $('#dkkd_state').change((e)=>{
      var state_id = $(e.currentTarget).val();
      loadFromState(state_id, e.currentTarget, default_ward_dkkd);
    });

    loadFromCity(default_city, $('#city'), default_state, default_ward);
    loadFromCity(default_city_billing, $('#billing_city'), default_state_billing, default_ward_billing);
    loadFromCity(default_city_shipping, $('#shipping_city'), default_state_shipping, default_ward_shipping);
    loadFromCity(default_city_dkkd, $('#dkkd_city'), default_state_dkkd, default_ward_dkkd);
    
    $('#client_type').change((e)=>{
      client_type = $(e.currentTarget).find('option:selected').val();
      console.log(client_type);
      switchMode();
    });

    $('.billing-same-as-customer').on('click', function(e) {
      e.preventDefault();
      $('select[name="billing_country"]').selectpicker('val', $('select[name="country"]').selectpicker('val'));
      $('select[name="billing_city"]').selectpicker('val', $('select[name="city"]').selectpicker('val'));
      loadFromCity($('select[name="city"]').selectpicker('val'), $('select[name="billing_city"]'), $('select[name="state"]').selectpicker('val'), $('select[name="address_ward"]').selectpicker('val'));

      $('input[name="billing_room_number"]').val($('input[name="address_room_number"]').val());
      $('input[name="billing_building"]').val($('input[name="address_building"]').val());
      $('input[name="billing_home_number"]').val($('input[name="address_home_number"]').val());
      $('input[name="billing_street"]').val($('input[name="address"]').val());
      $('input[name="billing_town"]').val($('input[name="address_town"]').val());
      $('input[name="billing_zip"]').val($('input[name="zip"]').val());
      
    });
    $('.customer-copy-billing-address').on('click', function(e) {
      e.preventDefault();
      $('select[name="shipping_country"]').selectpicker('val', $('select[name="billing_country"]').selectpicker('val'));
      
      $('select[name="shipping_city"]').selectpicker('val', $('select[name="billing_city"]').selectpicker('val'));
      loadFromCity($('select[name="shipping_city"]').selectpicker('val'), $('select[name="shipping_city"]'), $('select[name="billing_state"]').selectpicker('val'), $('select[name="billing_ward"]').selectpicker('val'));


      $('input[name="shipping_room_number"]').val($('input[name="billing_room_number"]').val());
      $('input[name="shipping_building"]').val($('input[name="billing_building"]').val());
      $('input[name="shipping_home_number"]').val($('input[name="billing_home_number"]').val());
      $('input[name="shipping_street"]').val($('input[name="billing_street"]').val());
      $('input[name="shipping_town"]').val($('input[name="billing_town"]').val());
      $('input[name="shipping_zip"]').val($('input[name="billing_zip"]').val());
    });
    $('.customer-copy-billing-address-dkkd').on('click', function(e) {
      e.preventDefault();
      $('select[name="dkkd_country"]').selectpicker('val', $('select[name="billing_country"]').selectpicker('val'));
      
      $('select[name="dkkd_city"]').selectpicker('val', $('select[name="billing_city"]').selectpicker('val'));
      loadFromCity($('select[name="dkkd_city"]').selectpicker('val'), $('select[name="dkkd_city"]'), $('select[name="billing_state"]').selectpicker('val'), $('select[name="billing_ward"]').selectpicker('val'));


      $('input[name="dkkd_room_number"]').val($('input[name="billing_room_number"]').val());
      $('input[name="dkkd_building"]').val($('input[name="billing_building"]').val());
      $('input[name="dkkd_home_number"]').val($('input[name="billing_home_number"]').val());
      $('input[name="dkkd_street"]').val($('input[name="billing_street"]').val());
      $('input[name="dkkd_town"]').val($('input[name="billing_town"]').val());
      $('input[name="dkkd_zip"]').val($('input[name="billing_zip"]').val());
    });

  });
  
  
  

  

</script>
<?php if(isset($client)){ ?>
<script>
 init_rel_tasks_table(<?php echo $client->userid; ?>,'customer');
 
    

</script>
<?php } ?>
<?php if(!empty($google_api_key) && !empty($client->latitude) && !empty($client->longitude)){ ?>
<script>

 var latitude = '<?php echo $client->latitude; ?>';
 var longitude = '<?php echo $client->longitude; ?>';
 var marker = '<?php echo $client->company; ?>';
</script>
<?php echo app_script('assets/js','map.js'); ?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&callback=initMap"></script>
<?php } ?>
<?php $this->load->view('admin/clients/client_js'); ?>
</body>
</html>
