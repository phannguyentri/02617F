<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- <div class="panel_s">
                    <div class="panel-body">                   
                    <h4 class="bold no-margin"><?=_l('sale_orders')?></h4>
                    </div>
                </div> -->
                <div class="clearfix"></div>
                <div class="panel_s">
                    <div class="panel-body">
                        <ul class="nav nav-tabs profile-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#sale_PO" aria-controls="sale_PO" role="tab" data-toggle="tab">
                                    <?php echo _l( 'sale_PO'); ?>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#sale_SO" aria-controls="sale_SO" role="tab" data-toggle="tab">
                                    <?php echo _l( 'sale_SO'); ?>
                                </a>
                            </li>                            
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="sale_PO">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="<?=admin_url('sales/sale_po_detail')?>" class="btn btn-info pull-left display-block mbot15"><?php echo _l('add_sale_porder'); ?></a>
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
                                                ),'sale_orders'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="sale_SO">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="<?=admin_url('sales/sale_detail')?>" class="btn btn-info pull-left display-block mbot15"><?php echo _l('add_sale_order'); ?></a>
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    $(function(){
        initDataTable('.table-sales', window.location.href, [1], [1]);
        initDataTable('.table-sale_orders', admin_url+'sale_orders/list_sale_orders', [1], [1]);
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
    function var_status_order(status,id)
    {
        // alert("<?=admin_url()?>sales/update_status")
        dataString={id:id,status:status};
        jQuery.ajax({
            type: "post",
            url:"<?=admin_url()?>sale_orders/update_status",
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    $('.table-sale_orders').DataTable().ajax.reload();
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

