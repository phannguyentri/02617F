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
                            <li role="presentation" <?=(empty($order_id)? 'class="active"' : '')?>>
                                <a href="#sale_PO" aria-controls="sale_PO" role="tab" data-toggle="tab">
                                    <?php echo _l( 'sale_PO'); ?>
                                </a>
                            </li>
                            <li role="presentation" <?=(!empty($order_id)? 'class="active"' : '')?>>
                                <a href="#sale_SO" aria-controls="sale_SO" role="tab" data-toggle="tab">
                                    <?php echo _l( 'sale_SO'); ?>
                                </a>
                            </li>                            
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane <?=(empty($order_id)? 'active' : '')?>" id="sale_PO">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="<?=admin_url('sale_orders/sale_detail')?>" class="btn btn-info pull-left display-block mbot15"><?php echo _l('add_sale_porder'); ?></a>
                                        <div class="clearfix"></div>
                                        <div class="panel_s">
                                            <div class="panel-body">
                                            <input type="hidden" id="filterStatus" value="" />
                                            <div data-toggle="btns" class="btn-group mbot15">
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterAll" data-toggle="tab" class="btn btn-info active">Tất cả</button>
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterNotApproval" data-toggle="tab" class="btn btn-info">Chưa duyệt</button>
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterApproval" data-toggle="tab" class="btn btn-info">Đã duyệt</button>
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterNotCreateOrder" data-toggle="tab" class="btn btn-info">Chưa tạo đơn hàng</button>
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterCreatingOrder" data-toggle="tab" class="btn btn-info">Đang tạo đơn hàng</button>
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterCreateOrder" data-toggle="tab" class="btn btn-info">Đã tạo đơn hàng</button>
                                            </div>
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

                            <div role="tabpanel" class="tab-pane <?=(!empty($order_id)? 'active' : '')?>" id="sale_SO">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="<?=admin_url('sales/sale_detail')?>" class="btn btn-info pull-left display-block mbot15"><?php echo _l('add_sale_order_'); ?></a>
                                        <div class="clearfix"></div>
                                        <div class="panel_s">
                                            <div class="panel-body">
                                            <input type="hidden" id="filterStatusSale" value="" />
                                            <div data-toggle="btn" class="btn-group mbot15">
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterSaleAll" data-toggle="tab" class="btn btn-info active">Tất cả</button>
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterSaleNotApproval" data-toggle="tab" class="btn btn-info">Chưa duyệt</button>
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterSaleApproval" data-toggle="tab" class="btn btn-info">Đã duyệt</button>
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterSaleNotCreateExport" data-toggle="tab" class="btn btn-info">Chưa tạo phiếu xuất</button>
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterSaleCreatingExport" data-toggle="tab" class="btn btn-info">Đang tạo phiếu xuất</button>
                                                <button style=" font-size: 11px;" type="button" id="btnDatatableFilterSaleCreateExport" data-toggle="tab" class="btn btn-info">Đã tạo phiếu xuất</button>
                                            </div>
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
         $('[data-toggle="btn"] .btn').on('click', function(){
            var $this = $(this);
            $this.parent().find('.active').removeClass('active');
            $this.addClass('active');
        });
        $('#btnDatatableFilterSaleAll').click(() => {
            $('#filterStatusSale').val('');
            $('#filterStatusSale').change();
        });
        $('#btnDatatableFilterSaleNotApproval').click(() => {
            $('#filterStatusSale').val(1);
            $('#filterStatusSale').change();
        });
        $('#btnDatatableFilterSaleApproval').click(() => {
            $('#filterStatusSale').val(2);
            $('#filterStatusSale').change();
        });
        $('#btnDatatableFilterSaleNotCreateExport').click(() => {
            $('#filterStatusSale').val(3);
            $('#filterStatusSale').change();
        });
        $('#btnDatatableFilterSaleCreatingExport').click(() => {
            $('#filterStatusSale').val(4);
            $('#filterStatusSale').change();
        });
        $('#btnDatatableFilterSaleCreateExport').click(() => {
            $('#filterStatusSale').val(5);
            $('#filterStatusSale').change();
        });
        var filterList = {
            'filterStatus' : '[id="filterStatusSale"]',
        };
        initDataTable('.table-sales', window.location.href, [1], [1], filterList);
        $.each(filterList, (filterIndex, filterItem) => {
            $('input' + filterItem).on('change', () => {
                $('.table-sales').DataTable().ajax.reload();
            });
        });
    });

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
        $('#btnDatatableFilterNotCreateOrder').click(() => {
            $('#filterStatus').val(3);
            $('#filterStatus').change();
        });
        $('#btnDatatableFilterCreatingOrder').click(() => {
            $('#filterStatus').val(4);
            $('#filterStatus').change();
        });
        $('#btnDatatableFilterCreateOrder').click(() => {
            $('#filterStatus').val(5);
            $('#filterStatus').change();
        });
        var filterList = {
            'filterStatus' : '[id="filterStatus"]',
        };
        initDataTable('.table-sale_orders', admin_url+'sale_orders/list_sale_orders', [1], [1], filterList);
        $.each(filterList, (filterIndex, filterItem) => {
            $('input' + filterItem).on('change', () => {
                $('.table-sale_orders').DataTable().ajax.reload();
            });
        });
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
        
        if (r == false) {
            return false;
        } else {
            $.get($(this).attr('href'), function(response) {
                alert_float(response.alert_type, response.message);
                // Looop throug all availble reminders table to reload the data
                var table='.table-sales';
                    if ($.fn.DataTable.isDataTable(table)) {
                        $('body').find(table).DataTable().ajax.reload();
                    }
                var table='.table-sale_orders';
                    if ($.fn.DataTable.isDataTable(table)) {
                        $('body').find(table).DataTable().ajax.reload();
                    }
            }, 'json');
        }
        return false;
    });
</script>

