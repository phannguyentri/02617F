<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body _buttons">
                    <!-- <h4 class="bold no-margin"><?=_l('quotes')?></h4>
                    <hr class="no-mbot no-border"> -->
                    <a href="#" onclick="init_qoute(); return false;" class="btn btn-info pull-left display-block"><?php echo _l('quote_add'); ?></a>
                    </div>
                </div>
                <div class="panel_s">
                   <div class="panel-body">
                       <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                           <?php
                               echo render_select('quote_client', $clients_iv, array('userid', 'company'), 'Khách hàng');
                           ?>
                       </div>
                       <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                          <?php
                               echo render_select('quote_userc', $user_iv, array('staffid', 'fullname'), 'Người tạo');
                           ?>
                       </div>


                       <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                         <label for="report-from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                         <div class="input-group date">
                            <input type="text" class="form-control datepicker" id="report-from" name="report-from">
                            <div class="input-group-addon">
                               <i class="fa fa-calendar calendar-icon"></i>
                            </div>
                         </div>
                      </div>
                      <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                         <label for="report-to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                         <div class="input-group date">
                            <input type="text" class="form-control datepicker" id="report-to" name="report-to">
                            <div class="input-group-addon">
                               <i class="fa fa-calendar calendar-icon"></i>
                            </div>
                         </div>
                      </div>

                   </div>
               </div>
                <div class="clearfix"></div>
                <div class="panel_s">
                    <div class="panel-body">
                    <input type="hidden" id="filterStatus" value="" />
                    <div data-toggle="btn" class="btn-group mbot15">
                        <button style=" font-size: 11px;" type="button" id="btnDatatableFilterAll" data-toggle="tab" class="btn btn-info active">Tất cả</button>
                       <!--  <button style=" font-size: 11px;" type="button" id="btnDatatableFilterNotApproval" data-toggle="tab" class="btn btn-info">Chưa duyệt</button>
                        <button style=" font-size: 11px;" type="button" id="btnDatatableFilterApproval" data-toggle="tab" class="btn btn-info">Đã duyệt</button>
                        <button style=" font-size: 11px;" type="button" id="btnDatatableFilterCancel" data-toggle="tab" class="btn btn-info">Không được duyệt</button> -->
                        <button style=" font-size: 11px;" type="button" id="btnDatatableFilterNotCreateContract" data-toggle="tab" class="btn btn-info">Chưa tạo hợp đồng</button>
                        <!-- <button style=" font-size: 11px;" type="button" id="btnDatatableFilterCreatingOrder" data-toggle="tab" class="btn btn-info">Đang tạo hợp đồng</button> -->
                        <button style=" font-size: 11px;" type="button" id="btnDatatableFilterCreateContract" data-toggle="tab" class="btn btn-info">Đã tạo hợp đồng</button>
                    </div>
                    <?php render_datatable(array(
                            _l('#'),
                            _l('Mã phiếu báo giá'),
                            _l('Khách hàng'),
                            _l('Người tạo'),
                            _l('Ngày tạo'),
                            _l('Tổng phát sinh'),
                            _l('Tổng tiền sản phẩm'),
                            _l('Tổng thuế sản phẩm'),
                            _l('Tổng giá trị sản phẩm'),
                            _l('options')
                        ),'quotes'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    var url222 = "<?php echo admin_url('quotes/delete_attachment/'); ?>";
        $(function(){
         $('[data-toggle="btn"] .btn').on('click', function(){
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
        $('#btnDatatableFilterCancel').click(() => {
            $('#filterStatus').val(5);
            $('#filterStatus').change();
        });
        $('#btnDatatableFilterNotCreateContract').click(() => {
            $('#filterStatus').val(3);
            $('#filterStatus').change();
        });
        $('#btnDatatableFilterCreateContract').click(() => {
            $('#filterStatus').val(4);
            $('#filterStatus').change();
        });
        var filterList = {
            'filterStatus' : '[id="filterStatus"]',
            "client_id" : "[name='quote_client']",
            "user_id" : "[name='quote_userc']",
            "report-from" : "[name='report-from']",
            "report-to" : "[name='report-to']",

        };

        initDataTable('.table-quotes', window.location.href, [0], [0], filterList,[0, 'DESC']);
        $.each(filterList, (filterIndex, filterItem) => {

            $('' + filterItem).on('change', () => {
                $('.table-quotes').DataTable().ajax.reload();
            });
        });

    });
    function var_status(status,id)
    {
        dataString={id:id,status:status};
        jQuery.ajax({
            type: "post",
            url:"<?=admin_url()?>quotes/update_status",
            data: dataString,
            cache: false,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    $('.table-quotes').DataTable().ajax.reload();
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
        var table='.table-quotes';
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

    $('body').on('click', '.cancel-remind', function() {
        var r = confirm(confirm_action_prompt);
        var table='.table-quotes';
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

