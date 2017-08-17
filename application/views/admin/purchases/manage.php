<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <?php if(has_permission('items','','create')){ ?>
                <div class="panel_s">
                    <div class="panel-body _buttons">
                        <a href="<?=admin_url('purchases/purchase/'.$client->userid)?>"  class="btn btn-info pull-left display-block"><?php echo _l('Thêm kế hoạch mua'); ?></a>
                    </div>
                </div>
            <?php } ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        
                        <div class="row">
                            
                        </div>

                        <input type="hidden" id="filterStatus" value="" />
                        <div data-toggle="btns" class="btn-group">
                            <button type="button" id="btnDatatableFilterAll" data-toggle="tab" class="btn btn-info active">Tất cả</button>
                            <button type="button" id="btnDatatableFilterNotApproval" data-toggle="tab" class="btn btn-info">Chưa duyệt</button>
                            <button type="button" id="btnDatatableFilterApproval" data-toggle="tab" class="btn btn-info">Đã duyệt</button>
                            <button type="button" id="btnDatableFilterDidntConvert" data-toggle="tab" class="btn btn-info">Chưa chuyển đề xuất</button>
                            <button type="button" id="btnDatableFilterConverted" data-toggle="tab" class="btn btn-info">Đã chuyển đề xuất</button>
                        </div>
                        
                        <p></p>
                        <?php render_datatable(array(
                            _l('#'),
                            _l('Mã kế hoạch'),
                            _l('Ngày'),                            
                            _l('Người đề nghị'),
                            _l('Kế hoạch'),
                            _l('Lý do'),
                            _l('Trạng thái'),
                            _l('Được duyệt bởi'),
                            _l('options'),
                        ),'purchases'); ?>
                    </div>
                </div>
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
            url:"<?=admin_url()?>purchases/update_status",
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    $('.table-purchases').DataTable().ajax.reload();
                    alert_float('success', response.message);
                }
                return false;
            }
        });

    }

    function view_init_department(id)
    {
        $('#type').modal('show');
        $('.add-title').addClass('hide');
        jQuery.ajax({
            type: "post",
            url:admin_url+"units/get_row_unit/"+id,
            data: '',
            cache: false,
            success: function (data) {
                var json = JSON.parse(data);
                //if($data!="")
                {
                    $('#unit').val(json.unit);
                    jQuery('#id_type').prop('action',admin_url+'units/update_unit/'+id);
                }
            }
        });
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
        initDataTable('.table-purchases', window.location.href, [1], [1], filterList);
        $.each(filterList, (filterIndex, filterItem) => {
            $('input' + filterItem).on('change', () => {
                $('.table-purchases').DataTable().ajax.reload();
            });
        });
        _validate_form($('form'),{unit:'required'},manage_contract_types);
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
            $('.table-units').DataTable().ajax.reload();
            $('#type').modal('hide');
        });
        return false;
    }

    function new_unit(){
        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        jQuery('#unit').val('');
        jQuery('#id_type').prop('action',admin_url+'units/add_unit');
    }
    function edit_type(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#type input[name="unit"]').val(name);
        $('#type').modal('show');
        $('.add-title').addClass('hide');
    }

    

</script>
</body>
</html>
