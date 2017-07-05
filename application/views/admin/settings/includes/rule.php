<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('id'),
                            _l('rule_name'),
                            _l('rule_content'),
                        ),'rule'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="type" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('rule/update_add_rule'),array('id'=>'id_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('contract_type_edit'); ?></span>
                    <span class="add-title"><?php echo _l('new_contract_type'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_input('name','rule_name'); ?>
                    </div>
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?=render_select('pater_id',$rule,array('id','name'),'quyen_quanly_content')?>
                    </div>
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php echo render_textarea('content','rule_content'); ?>
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

    function view_init_rule(id)
    {
        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        jQuery.ajax({
            type: "post",
            url:admin_url+"/rule/get_row_rule/"+id,
            data: '',
            cache: false,
            success: function (data) {
                var json = JSON.parse(data);
//                if($data!="")
                {
                    jQuery('#name').val(json.name);
                    $('#pater_id').selectpicker();
                    $('#pater_id').selectpicker('val',json.pater_id);
                    jQuery('#content').val(json.content);
                    jQuery('#id_type').prop('action',admin_url+'rule/update_add_rule/'+id);
                }
            }
        });
    }
    $(function(){
        initDataTable('.table-rule', window.location.href, [1], [1]);
        _validate_form($('form'),{name:'required'},manage_contract_types);
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
            $('.table-rule').DataTable().ajax.reload();
            $('#type').modal('hide');
        });
        return false;
    }
    function new_rule(){
        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        jQuery('#name').val('');
        $('#pater_id').selectpicker();
        $('#pater_id').selectpicker('val','');
        jQuery('#content').val('');
        jQuery('#id_type').prop('action',admin_url+'rule/update_add_rule');
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
