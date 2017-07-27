<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php $this->load->view('admin/suppliers/profile'); ?>
                    </div>
                </div>
                        
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
$(document).ready(()=>{
    var default_city  = '<?php echo isset($supplier) ? $supplier->city : 0 ?>';
    var default_state = '<?php echo isset($supplier) ? $supplier->state : 0 ?>';
    var default_ward  = '<?php echo isset($supplier) ? $supplier->address_ward : 0?>';

    var loadFromCity = (city_id, currentTarget, default_value_state, default_value_ward) => {
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
    loadFromCity(default_city, $('#city'), default_state, default_ward);
    $('#city').change((e)=>{
      var city_id = $(e.currentTarget).val();
      loadFromCity(city_id, e.currentTarget, default_state, default_ward);
    });
    $('#state').change((e)=>{
      var state_id = $(e.currentTarget).val();
      loadFromState(state_id, e.currentTarget, default_ward);
    });
});
</script>
</body>
</html>
