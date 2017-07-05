<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body _buttons">
                        <a href="#" onclick="new_warehouse(); return false;" class="btn btn-info pull-left display-block"><?php echo _l('Thêm sản phẩm mới'); ?></a>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('id'),
                            _l('Mã kho'),
                            _l('Tên kho'),
                            _l('Địa chỉ'),
                            _l('Điện thoại'),
                            _l('options')
                        ),'warehouses'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="type" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('warehouses/add_warehouse'),array('id'=>'id_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('Sửa kho sản phẩm'); ?></span>
                    <span class="add-title"><?php echo _l('Thêm kho sản phẩm mới'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('code','Mã kho'); ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_input('warehouse','Tên kho'); ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_input('address','Địa chỉ'); ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_input('phone','Điện thoại'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
<script>

    function view_init_department(id)
    {
        $('#type').modal('show');
        $('.add-title').addClass('hide');
        jQuery.ajax({
            type: "post",
            url:admin_url+"warehouses/get_row_warehouse/"+id,
            data: '',
            cache: false,
            success: function (data) {
                var json = JSON.parse(data);
//                if($data!="")
                {
                    $('#code').val(json.code);
                    $('#warehouse').val(json.warehouse);
                    $('#address').val(json.address);
                    $('#phone').val(json.phone);
                    jQuery('#id_type').prop('action',admin_url+'warehouses/update_warehouse/'+id);
                }
            }
        });
    }

    $(function(){
        initDataTable('.table-warehouses', window.location.href, [1], [1]);
        _validate_form($('form'),{code:'required',warehouse:'required',address:'required',phone:'required'},manage_contract_types);
        $('#type').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#type input').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
    });

    // $('body').on('click', '.delete-reminder', function() {
    //     var r = confirm(confirm_action_prompt);
    //     alert($.fn.DataTable.isDataTable('.table-warehouses'));
    //     if (r == false) {
    //         return false;
    //     } else {
    //         $.get($(this).attr('href'), function(response) {
    //             alert_float(response.alert_type, response.message);
    //             // Looop throug all availble reminders table to reload the data
    //             $.each(available_reminders_table, function(i, table) {
    //                 alert(table);
    //                 if ($.fn.DataTable.isDataTable(table)) {
    //                     $('body').find(table).DataTable().ajax.reload();
    //                 }
    //             });
    //         }, 'json');
    //     }
    //     return false;
    // });
    
    function manage_contract_types(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if(response.success == true){
                alert_float('success',response.message);
            }
            $('.table-warehouses').DataTable().ajax.reload();
            $('#type').modal('hide');
        });
        return false;
    }

    function new_warehouse(){
        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        jQuery('#category').val('');
        jQuery('#id_type').prop('action',admin_url+'warehouses/add_warehouse');
    }
    function edit_type(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#type input[name="category"]').val(name);
        $('#type').modal('show');
        $('.add-title').addClass('hide');
    }

    

</script>
</body>
</html>
