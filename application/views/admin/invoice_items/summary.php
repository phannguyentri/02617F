<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <?php if(has_permission('items','','create')){ ?>
        <div class="panel_s">
          <div class="panel-body _buttons">
            <!--<a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#sales_item_modal"><?php echo _l('new_invoice_item'); ?></a>-->
            <a href="<?php echo admin_url() . "warehouses" ?>" class="btn mright5 btn-info pull-left display-block"><?php echo _l('Danh sách kho'); ?></a>
            <a href="<?php echo admin_url() . "kind_of_warehouse" ?>" class="btn mright5 btn-info pull-left display-block"><?php echo _l('Loại kho'); ?></a>
            <!-- <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#landtype">Loại nhà đất</a> -->
          </div>

        </div>
        <?php } ?>
        
        <div class="panel_s">
          <div class="panel-body">
            <div class="clearfix"></div>
            <?php
            $summary_columns = array(
              "ID",
              _l('item_code'),
              _l('item_name'),
              );
            foreach($warehouses as $warehouse) {
              $summary_columns[] = $warehouse['warehouse'];
            }
            $summary_columns[] = _l('Hàng có thể bán');
            render_datatable($summary_columns,
              'invoice-items'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php $this->load->view('admin/invoice_items/item'); ?>
  <!-- landtype -->
  <!-- <div class="modal fade" id="landtype" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">
            Danh mục loại nhà đất
          </h4>
        </div>
        <div class="modal-body">
          <?php if(has_permission('items','','create')){ ?>
          <div class="form-group">
            <label>Mô tả</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Loại nhà đất">
            
          </div>
          <div class="form-group"  >
                <label class="control-label" for="landtype">Loại nhà đất</label>
                <select class="selectpicker display-block" data-width="100%" name="parentid" id="parentid" title='Chọn danh mục' data-none-selected-text="Chưa chọn loại nhà đất">
                    <option value="0" selected>Chọn danh mục</option>
                    <?php 
                    foreach($landtype as $lt){ 
                        if($lt['parentid']==0){
                    ?>
                        <option value="<?php echo $lt['id']; ?>"><?php echo $lt['name']; ?></option>
                        
                    <?php } }   ?>
                        
                </select>
                <hr />
            <span class="input-group-btn">
            <button class="btn btn-info p7" type="button" id="new-item-landtype-insert">Thêm mới</button>
            </span>
            </div>
          <hr />
          <?php } ?>
          <div class="row">
           <div class="container-fluid">
            <table class="table table-striped dt-table table-items-groups" data-order-col="0" data-order-type="asc">
              <thead>
                <tr>
                  <th>Loại nhà đất</th>
                  <th><?php echo _l('options'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($landtype as $lt){ ?>
                <tr data-group-row-id="<?php echo $lt['id']; ?>">
                  <td data-order="<?php echo $lt['name']; ?>">
                    <span class="group_name_plain_text"><?php echo $lt['name']; ?></span>
                    <div class="group_edit hide">
                     <div class="input-group">
                      <input type="text" class="form-control">
                      <span class="input-group-btn">
                        <button class="btn btn-info p7 update-item-landtype" type="button"><?php echo _l('submit'); ?></button>
                      </span>
                    </div>
                  </div>
                </td>
                <td align="right">
                  <?php if(has_permission('items','','edit')){ ?><button type="button" class="btn btn-default btn-icon edit-item-group"><i class="fa fa-pencil-square-o"></i></button><?php } ?>
                  <?php if(has_permission('items','','delete')){ ?><a href="<?php echo admin_url('invoice_items/delete_landtype/'.$lt['id']); ?>" class="btn btn-danger btn-icon delete-item-group _delete"><i class="fa fa-remove"></i></a><?php } ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      </div>
    </div>
  </div>
</div> -->
<!-- endlandtype -->
  <div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">
            <?php echo _l('item_groups'); ?>
          </h4>
        </div>
        <div class="modal-body">
          <?php if(has_permission('items','','create')){ ?>
          <div class="input-group">
            <input type="text" name="item_group_name" id="item_group_name" class="form-control" placeholder="<?php echo _l('item_group_name'); ?>">
            <span class="input-group-btn">
              <button class="btn btn-info p7" type="button" id="new-item-group-insert"><?php echo _l('new_item_group'); ?></button>
            </span>
          </div>
          <hr />
          <?php } ?>
          <div class="row">
           <div class="container-fluid">
            <table class="table table-striped dt-table table-items-groups" data-order-col="0" data-order-type="asc">
              <thead>
                <tr>
                  <th><?php echo _l('item_group_name'); ?></th>
                  <th><?php echo _l('options'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($items_groups as $group){ ?>
                <tr data-group-row-id="<?php echo $group['id']; ?>">
                  <td data-order="<?php echo $group['name']; ?>">
                    <span class="group_name_plain_text"><?php echo $group['name']; ?></span>
                    <div class="group_edit hide">
                     <div class="input-group">
                      <input type="text" class="form-control">
                      <span class="input-group-btn">
                        <button class="btn btn-info p7 update-item-group" type="button"><?php echo _l('submit'); ?></button>
                      </span>
                    </div>
                  </div>
                </td>
                <td align="right">
                  <?php if(has_permission('items','','edit')){ ?><button type="button" class="btn btn-default btn-icon edit-item-group"><i class="fa fa-pencil-square-o"></i></button><?php } ?>
                  <?php if(has_permission('items','','delete')){ ?><a href="<?php echo admin_url('invoice_items/delete_group/'.$group['id']); ?>" class="btn btn-danger btn-icon delete-item-group _delete"><i class="fa fa-remove"></i></a><?php } ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
  $(function(){
    var filterList = {
    };
    initDataTable('.table-invoice-items', window.location.href, [3,4,5,6,7,8,9], [3,4,5,6,7,8,9], filterList,[0,'DESC']);
    $(document).ready(()=>{
        $('#category_1,#category_2,#category_3,#category_4').on('change', (e) => {
            var id = $(e.currentTarget).val();
            $(e.currentTarget).parents('.col-xs-3').nextAll().find('select[name^="category_"] option:gt(0)').remove();
            $(e.currentTarget).parents('.col-xs-3').nextAll().find('select[name^="category_"]').selectpicker('refresh');
            if(typeof(id) == 'undefined' || id == 0) return;
            jQuery.ajax({
                type: "post",
                url:admin_url+"categories/get_childs/"+id,
                data: '',
                cache: false,
                success: function (data) {
                    data = JSON.parse(data);
                    data.map(o => 
                            $(e.currentTarget).parents('.col-xs-3').next().find('select[name^="category_"]').append('<option value='+o.id+'>'+o.category+'</option>')
                    );
                    $(e.currentTarget).parents('.col-xs-3').next().find('select[name^="category_"]').selectpicker('refresh');
                },
            });
        });
    $.each(filterList, (key,value)=>{
        $('select' + value).on('change', () => {
            $('.table-invoice-items').DataTable().ajax.reload();
        });
    });
    

    });
    if(get_url_param('groups_modal')){
      // Set time out user to see the message
      setTimeout(function(){
       $('#groups').modal('show');
     },1000);
    }
    if(get_url_param('landtype_modal')){
      // Set time out user to see the message
      setTimeout(function(){
       $('#landtype').modal('show');
     },1000);
    }
    $('#new-item-landtype-insert').on('click',function(){
      var name = $('#name').val();
      var parentid = $('#parentid').val();
      // alert(name+'-'+parentid);
      // var data=array[name,parentid];
      if(name != ''){
        $.post(admin_url+'invoice_items/add_landtype',{name:name,parentid:parentid}).done(function(){
         window.location.href = admin_url+'invoice_items?landtype_modal=true';
       });
      }
    });

    $('#new-item-group-insert').on('click',function(){
      var group_name = $('#item_group_name').val();
      if(group_name != ''){
        $.post(admin_url+'invoice_items/add_group',{name:group_name}).done(function(){
         window.location.href = admin_url+'invoice_items?groups_modal=true';
       });
      }
    });

    $('body').on('click','.edit-item-group',function(){
      var tr = $(this).parents('tr'),
      group_id = tr.attr('data-group-row-id');
      tr.find('.group_name_plain_text').toggleClass('hide');
      tr.find('.group_edit').toggleClass('hide');
      tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
    });

    $('body').on('click','.update-item-group',function(){
      var tr = $(this).parents('tr');
      var group_id = tr.attr('data-group-row-id');
      name = tr.find('.group_edit input').val();
      if(name != ''){
        $.post(admin_url+'invoice_items/update_group/'+group_id,{name:name}).done(function(){
         window.location.href = admin_url+'invoice_items';
       });
      }
    });
    $('body').on('click','.update-item-landtype',function(){
      var tr = $(this).parents('tr');
      var id = tr.attr('data-group-row-id');
      namee = tr.find('.group_edit input').val();
      if(namee != ''){
        $.post(admin_url+'invoice_items/update_landtype/'+id,{name:namee}).done(function(){
         window.location.href = admin_url+'invoice_items';
         // alert(window.location.href);
       });
      }
    });
  });
</script>
</body>
</html>
