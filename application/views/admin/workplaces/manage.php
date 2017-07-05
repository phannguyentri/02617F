<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body _buttons">
                        <a href="#" onclick="new_workplace(); return false;" class="btn btn-info pull-left display-block"><?php echo _l('Thêm nơi làm việc'); ?></a>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('id'),
                            _l('Tên'),
                            _l('Điện thoại'),
                            _l('Địa chỉ'),
                            _l('options')
                        ),'workplaces'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="type" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('workplaces/add_workplace'),array('id'=>'id_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('Sửa nơi làm việc'); ?></span>
                    <span class="add-title"><?php echo _l('Thêm nơi làm việc'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('workplace','Tên'); ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_input('phone','Điện thoại'); ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_input('address','Địa chỉ'); ?>
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
            url:admin_url+"workplaces/get_row_workplace/"+id,
            data: '',
            cache: false,
            success: function (data) {
                var json = JSON.parse(data);
//                if($data!="")
                {
                    $('#workplace').val(json.workplace);
                    $('#phone').val(json.phone);
                    $('#address').val(json.address);
                    jQuery('#id_type').prop('action',admin_url+'workplaces/update_workplace/'+id);
                }
            }
        });
    }

    $(function(){
        initDataTable('.table-workplaces', window.location.href, [1], [1]);
        _validate_form($('form'),{workplace:'required',phone:'required',address:'required'},manage_contract_types);
        $('#type').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#type input').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
    });
    function manage_contract_types(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if(response.success == true){
                alert_float('success',response.message);
            }
            $('.table-workplaces').DataTable().ajax.reload();
            $('#type').modal('hide');
        });
        return false;
    }

    function new_workplace(){
        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        jQuery('#name').val('');
        jQuery('#id_type').prop('action',admin_url+'workplaces/add_workplace');
    }
    function edit_type(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#type input[name="name"]').val(name);
        $('#type').modal('show');
        $('.add-title').addClass('hide');
    }

    

</script>
</body>
</html>
