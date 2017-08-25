<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <?php if(has_permission('items','','create')){ ?>
        <div class="panel_s">
          <div class="panel-body _buttons">
            <a href="<?php echo admin_url('purchase_suggested/detail') ?>" class="btn btn-info pull-left"><?php echo _l('purchase_suggested_add_heading'); ?></a>
          </div>

        </div>
        <?php } ?>
        <div class="panel_s">
          <div class="panel-body">
            <div class="clearfix"></div>
            <input type="hidden" id="filterStatus" value="" />
            <div data-toggle="btns" class="btn-group">
                <button type="button" id="btnDatatableFilterAll" data-toggle="tab" class="btn btn-info active">Tất cả</button>
                <button type="button" id="btnDatatableFilterNotApproval" data-toggle="tab" class="btn btn-info">Chưa duyệt</button>
                <button type="button" id="btnDatatableFilterApproval" data-toggle="tab" class="btn btn-info">Đã duyệt</button>
                <button type="button" id="btnDatableFilterDidntConvert" data-toggle="tab" class="btn btn-info">Chưa chuyển hết</button>
                <button type="button" id="btnDatableFilterConverted" data-toggle="tab" class="btn btn-info">Đã chuyển hết</button>
            </div>
            
            <p></p>
            <?php render_datatable(array(
              "ID",
              _l('purchase_suggested_code'),
              _l('purchase_suggested_name'),
              _l('Người đề nghị'),
              _l('purchase_suggested_status'),
              _l('purchase_total_items'),
              _l('Được duyệt bởi'),              
              _l('purchase_suggested_date'),
              _l('actions'),              
              ),
              'purchase-suggested'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php $this->load->view('admin/invoice_items/item'); ?>
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
            <table class="table table-striped dt-table table-purchase" data-order-col="0" data-order-type="asc">
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
  function var_status(status,id)
    {
        dataString={id:id,status:status};
        jQuery.ajax({
            type: "post",
            url:"<?=admin_url()?>purchase_suggested/update_status",
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    $('.table-purchase-suggested').DataTable().ajax.reload();
                    alert_float('success', response.message);
                }
            }
        });
        return false;
    }


  $(function(){
    $('[data-toggle="btns"] .btn').on('click', function(){
        var $this = $(this);
        $this.parent().find('.active').removeClass('active');
        $this.addClass('active');
    });
    $('#btnDatatableFilterAll').click(() => {
        $('#filterStatus').val('');
        $('#filterStatus').change();
    });
    $('#btnDatatableFilterNotApproval').click(() => {
        $('#filterStatus').val(1);
        $('#filterStatus').change();
    });
    $('#btnDatatableFilterApproval').click(() => {
        $('#filterStatus').val(2);
        $('#filterStatus').change();
    });
    $('#btnDatableFilterDidntConvert').click(() => {
        $('#filterStatus').val(3);
        $('#filterStatus').change();
    });
    $('#btnDatableFilterConverted').click(() => {
        $('#filterStatus').val(4);
        $('#filterStatus').change();
    });
    
    var filterList = {
        'filterStatus' : '[id="filterStatus"]',
    };
    //initDataTable('.table-purchase-suggested', '<?=admin_url('purchase_suggested')?>', [1], [1], filterList,[0,'DESC']);
    initDataTable('.table-purchase-suggested', window.location.href, [0], [0], filterList, [0,'DESC']);
    $.each(filterList, (filterIndex, filterItem) => {
      $('input' + filterItem).on('change', () => {
          $('.table-purchase-suggested').DataTable().ajax.reload();
      });
    });
  });
</script>
</body>
</html>