<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body _buttons">                   
                    <h4 class="bold no-margin"><?=_l('sale_orders')?></h4>
                    <hr class="no-mbot no-border">
                    <a href="<?=admin_url('sales/sale_detail')?>" class="btn btn-info pull-left display-block"><?php echo _l('add_sale_order'); ?></a>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="panel_s">
                    <div class="panel-body">
                    <?php render_datatable(array(
                            _l('#'),
                            _l('Mã đơn hàng'),
                            _l('Khách hàng'),
                            _l('Người tạo'),
                            _l('Trạng thái'),
                            _l('Được duyệt bởi'),
                            _l('Ngày tạo'),
                            _l('options')
                        ),'sales'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    $(function(){
        initDataTable('.table-sales', window.location.href, [1], [1]);
    //     _validate_form($('form'),{unit:'required'},manage_contract_types);
    //     $('#adjustment_type').on('hidden.bs.modal', function(event) {
    //         $('#additional').html('');
    //         $('#adjustment_type input').val('');
    //         $('.add-title').removeClass('hide');
    //         $('.edit-title').removeClass('hide');
    //     });
    //     function manage_contract_types(form) {
    //     var data = $(form).serialize();
    //     var url = form.action;
    //     $.post(url, data).done(function(response) {
    //         response = JSON.parse(response);
    //         if(response.success == true){
    //             alert_float('success',response.message);
    //         }
    //         $('.table-units').DataTable().ajax.reload();
    //         $('#type').modal('hide');
    //     });
    //     return false;
    // }
    });
    function var_status(status,id)
    {
        // alert("<?=admin_url()?>sales/update_status")
        dataString={id:id,status:status};
        jQuery.ajax({
            type: "post",
            url:"<?=admin_url()?>sales/update_status",
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    $('.table-sales').DataTable().ajax.reload();
                    alert_float('success', response.message);
                }
                return false;
            }
        });

    }
//     function view_init_adjustment(id)
//     {
//         $('#adjustment_type').modal('show');
//         $('.add-title').addClass('hide');
//         jQuery.ajax({
//             type: "post",
//             url:admin_url+"units/get_row_unit/"+id,
//             data: '',
//             cache: false,
//             success: function (data) {
//                 var json = JSON.parse(data);
// //                if($data!="")
//                 {
//                     $('#unit').val(json.unit);
//                     jQuery('#id_type').prop('action',admin_url+'units/update_unit/'+id);
//                 }
//             }
//         });
//     }
    $('body').on('click', '.delete-remind', function() {
        var r = confirm(confirm_action_prompt);
        var table='.table-sales';
        if (r == false) {
            return false;
        } else {
            $.get($(this).attr('href'), function(response) {
                alert_float(response.alert_type, response.message);
                // Looop throug all availble reminders table to reload the data
                    if ($.fn.DataTable.isDataTable(table)) {
                        $('body').find(table).DataTable().ajax.reload();
                    }
            }, 'json');
        }
        return false;
    });
</script>

