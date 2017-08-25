<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
 <div class="content">
   <div class="row">

  <div class="col-md-12">
   <div class="panel_s">
     <div class="panel-body">
        <?php if (isset($item)) { ?>
        <?php echo form_hidden('isedit'); ?>
        <?php echo form_hidden('itemid', $item->id); ?>
      <div class="clearfix"></div>
        <?php 
    } ?>
        <!-- Product information -->
        

          <h4 class="bold no-margin"><?php echo _l('orders_create_heading') ?></h4>
  <hr class="no-mbot no-border" />
  <div class="row">
    <div class="additional"></div>
    <div class="col-md-12">
        <?php
            $type='warning';
            $status='Đơn hàng mới';
        ?>
        <div class="ribbon <?=$type?>"><span><?=$status?></span></div>
        <ul class="nav nav-tabs profile-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#item_detail" aria-controls="item_detail" role="tab" data-toggle="tab">
                    <?php echo _l('purchase_suggested_information'); ?>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="item_detail">
            <div class="row">
                
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    
                </div>
                
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 _buttons">
                    <div class="pull-right">
                        <?php if( isset($item) ) { ?>
                        <a href="<?php echo admin_url('purchase_suggested/detail_pdf/' . $item->id . '?print=true') ?>" target="_blank" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="In" aria-describedby="tooltip652034"><i class="fa fa-print"></i></a>
                        <a href="<?php echo admin_url('purchase_suggested/detail_pdf/' . $item->id  ) ?>" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Xem PDF"><i class="fa fa-file-pdf-o"></i></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'client-form', 'autocomplete' => 'off')); ?>
                <div class="row">
                  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">            
                    <?php
                      // config
                    $attrs_not_select = array('data-none-selected-text' => _l('system_default_string'));
                    ?>
                    <!-- prefix_purchase_order -->
                    <div class="form-group">
                        <label for="number"><?php echo _l('orders_code'); ?></label>  
                            <?php
                            if(!isset($item)) {
                            ?>
                            <div class="input-group">
                            <span class="input-group-addon">
                                <?php
                                echo get_option('prefix_purchase_order');
                                ?>
                            </span>
                            <?php
                            }
                            ?>
                            <?php 
                                // var_dump($purchase);
                                if($item)
                                {

                                    $number=$item->code;
                                }
                                else
                                {
                                    $number=sprintf('%05d',getMaxID('id','tblorders')+1);
                                }
                            ?>
                            <input type="text" name="code" class="form-control" value="<?=$number ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" readonly>
                        <?php if(!isset($item)) { ?>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="id_purchase_suggested"><?php echo _l('purchase_suggested_code') ?></label>
                        <input type="hidden" name="id_purchase_suggested" class="form-control" value="<?php echo $purchase_suggested->id ?>">
                        <input type="text" class="form-control" value="<?php echo $purchase_suggested->code ?>" readonly>
                    </div>

                    <?php 
                        $default_supplier = "";
                        echo render_select('id_supplier', $suppliers, array('userid', 'company'), 'suppliers', $default_supplier);
                    ?>
                    <?php 
                        $default_currency = "";
                        echo render_select('currency_id', $currencies, array('id', 'name'), 'currency', $default_currency);
                    ?>
                    
                    <?php
                        $default_date_create = ( isset($item) ? _d($item->date_create) : _d(date('Y-m-d')));
                        echo render_date_input( 'date_create', 'project_datecreated' , $default_date_create , 'date'); 
                    ?>
                    
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?php
                    $default_date_import = ( isset($item) ? _d($item->date_import) : _d(date('Y-m-d')));
                    echo render_date_input( 'date_import', 'orders_date_import' , $default_date_import , 'date'); 
                ?>
                <?php 
                $reason = (isset($item) ? $item->reason : "");
                echo render_textarea('explan', 'orders_explan', $reason, array(), array(), '', 'tinymce');
                ?>
            </div>
                <div class="row">

                    <!-- Edited -->
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h4 class="bold no-margin">Sản phẩm từ đề xuất có thể thêm vào đơn hàng</h4>
                        <!-- Cusstomize from invoice -->
                        <div class="panel-body mtop10">
                        <?php if(!empty($purchase_suggested->rel_id) || !empty($purchase_suggested->rel_code)){ $display='style="display: none;"';  }?>
                            <div class="row" <?=$display?> >
                                
                                <div class="col-md-5 text-right show_quantity_as_wrapper">
                                    
                                </div>
                            </div>
                            <div class="table-responsive s_table table_purchase_suggested">
                                <table class="table items item-export no-mtop">
                                    <thead>
                                        <tr>
                                            <th><input type="hidden" id="itemID" value="" /></th>
                                            <th width="" class="text-left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_name'); ?>"></i> <?php echo _l('item_name'); ?></th>
                                            <th width="" class="text-left"><?php echo _l('item_unit'); ?></th>
                                            <th width="" class="text-left"><?php echo _l('item_quantity'); ?></th>
                                            
                                            <th width="" class="text-left"><?php echo _l('warehouse_type'); ?></th>
                                            <th width="" class="text-left"><?php echo _l('warehouse_name'); ?></th>
                                            <th class="text-left">Tỷ giá</th>
                                            <th width="" class="text-left"><?php echo _l('Tiền tệ'); ?></th>
                                            <th width="" class="text-left"><?php echo _l('item_price_buy'); ?></th>
                                            <th width="" class="text-left"><?php echo _l('purchase_total_price'); ?></th>
                                            <th></th>
                                            
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php
                                        $i=0;
                                        $totalPrice=0;
                                        
                                        if(isset($purchase_suggested) && count($purchase_suggested->items) > 0) {
                                            
                                            foreach($purchase_suggested->items as $value) {
                                                $value = (array)$value;
                                                if($value['order_id']!=0) continue;
                                            ?>
                                        <tr class="sortable item">
                                            <td>
                                                <input type="hidden" name="items[<?php echo $i; ?>][product_id]" id="items[<?php echo $i; ?>][product_id]" value="<?php echo $value['product_id']; ?>">
                                            </td>
                                            <td class="dragger"><?php echo $value['name']; ?></td>
                                            <td><?php echo $value['unit_name']; ?></td>
                                            <?php
                                            $err='';
                                            $style='';
                                                if($value['quantity_required']>$value['warehouse_type']->maximum_quantity)
                                                {
                                                    $err='error';
                                                    $style='border: 1px solid red !important';
                                                }
                                            ?>
                                            <td>
                                            <input style="width: 100px; <?=$style?>" class="mainQuantity <?=$err?>" type="number" name="items[<?php echo $i; ?>][quantity]" id="items[<?php echo $i; ?>][quantity]" value="<?php echo $value['product_quantity']; ?>">
                                            </td>
                                                
                                            <td><?php echo $value['warehouse_type']->kindof_warehouse_name ?></td>
                                            <td><input type="hidden" data-store="<?=$value['warehouse_type']->maximum_quantity ?>" name="items[<?=$i?>][warehouse]" id="items[<?=$i?>][warehouse]" value="<?=$value['warehouse_id']?>"><?php echo $value['warehouse_type']->warehouse ?>(tối đa <?=$value['warehouse_type']->maximum_quantity?>)</td>
                                            <td>
                                            <?php
                                                echo render_input('items['.$i.'][exchange_rate]', '', 1);
                                            ?>
                                            </td>
                                            <td>
                                                <?php echo render_select('items['.$i.'][currency]', $currencies, array('id', 'name'), '', $value['currency_id'], array('disabled' => 'disabled')); ?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="items[<?php echo $i ?>][id]" id="items[<?php echo $i ?>][id]" value="<?=$value['id']?>" />
                                                <input style="width: 100px" class="mainPriceBuy" name="items[<?php echo $i ?>][price_buy]" id="items[<?php echo $i ?>][price_buy]" step="0,01" type="number" value="<?php echo $value['price_buy'] ?>"  class="form-control" placeholder="<?php echo _l('item_price_buy'); ?>">
                                            </td>
                                            <td>
                                                <?php echo number_format($value['price_buy']*$value['product_quantity']) ?>
                                            </td>
                                            <td><a href="#" class="btn btn-success pull-right" onclick="return addTrItem(this);"><i class="fa fa-plus"></i></a></td>
                                        </tr>
                                            <?php
                                                // $totalPrice += $value['price_buy']*$value['quantity_required'];
                                                $i++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-8 col-md-offset-4">
                                <table class="table text-right">
                                    <tbody>
                                        <tr>
                                            <td><span class="bold"><?php echo _l('purchase_total_items'); ?> :</span>
                                            </td>
                                            <td class="total">
                                                <?php echo $i ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End Customize from invoice -->
                    </div>
                    <!-- End edited -->
                    
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h4 class="bold">Sản phẩm trên đơn hàng</h4>
                        <!-- Cusstomize from invoice -->
                        <div class="panel-body mtop10">
                        <?php if(!empty($purchase_suggested->rel_id) || !empty($purchase_suggested->rel_code)){ $display='style="display: none;"';  }?>
                            <div class="row" <?=$display?> >
                                
                                <div class="col-md-5 text-right show_quantity_as_wrapper">
                                    
                                </div>
                            </div>
                            <div class="table-responsive s_table table_purchase_orders">
                                <table class="table items item-export no-mtop">
                                    <thead>
                                        <tr>
                                            <th><input type="hidden" id="itemID" value="" /></th>
                                            <th width="" class="text-left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_name'); ?>"></> <?php echo _l('item_name'); ?></th>
                                            <th width="" class="text-left"><?php echo _l('item_unit'); ?></th>
                                            <th width="" class="text-left"><?php echo _l('item_quantity'); ?></th>
                                            
                                            <th width="" class="text-left"><?php echo _l('warehouse_type'); ?></th>
                                            <th width="" class="text-left"><?php echo _l('warehouse_name'); ?></th>
                                            <th class="text-left">Tỷ giá</th>
                                            <th width="" class="text-left"><?php echo _l('Tiền tệ'); ?></th>
                                            <th width="" class="text-left"><?php echo _l('item_price_buy'); ?></th>
                                            <th width="" class="text-left"><?php echo _l('purchase_total_price'); ?></th>
                                            <th></th>
                                            
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php
                                        $i=0;
                                        $totalPrice=0;
                                        
                                        if(isset($purchase_order) && count($purchase_order->items) > 0) {
                                            foreach($purchase_order->items as $value) {
                                                $value = (array)$value;
                                            ?>
                                        <tr class="sortable item">
                                            <td>
                                                <input type="hidden" name="items[<?php echo $i; ?>][id]" value="<?php echo $value['product_id']; ?>">
                                            </td>
                                            <td class="dragger"><?php echo $value['name']; ?></td>
                                            <td><?php echo $value['unit_name']; ?></td>
                                            <?php
                                            $err='';
                                            $style='';
                                                if($value['quantity_required']>$value['warehouse_type']->maximum_quantity)
                                                {
                                                    $err='error';
                                                    $style='border: 1px solid red !important';
                                                }
                                            ?>
                                            <td>
                                            <input style="width: 100px; <?=$style?>" class="mainQuantity <?=$err?>" type="number" name="items[<?php echo $i; ?>][quantity]" value="<?php echo $value['product_quantity']; ?>">
                                            </td>
                                                
                                            <td><?php echo $value['warehouse_type']->kindof_warehouse_name ?></td>
                                            <td><input type="hidden" data-store="<?=$value['warehouse_type']->maximum_quantity ?>" name="items[<?=$i?>][warehouse]" value="<?=$value['warehouse_id']?>"><?php echo $value['warehouse_type']->warehouse ?>(tối đa <?=$value['warehouse_type']->maximum_quantity?>)</td>
                                            <td>
                                            <?php
                                                echo render_input('items['.$i.'][exchange_rate]', '');
                                            ?>
                                            </td>
                                            <td>
                                                <?php echo render_select('items['.$i.'][currency]', $currencies, array('id', 'name'), '', $value['currency_id'], array('disabled' => 'disabled')); ?>
                                            </td>
                                            <td>
                                                <input style="width: 100px" class="mainPriceBuy" name="items[<?php echo $i ?>][price_buy]" step="0.01" type="number" value="<?php echo $value['price_buy'] ?>"  class="form-control" placeholder="<?php echo _l('item_price_buy'); ?>">
                                            </td>
                                            <td>
                                                <?php echo number_format($value['price_buy']*$value['product_quantity']) ?>
                                            </td>
                                            <td><a href="#" class="btn btn-danger pull-right" onclick="removeTrItem(this); return false;"><i class="fa fa-times"></i></a></td>
                                        </tr>
                                            <?php
                                                // $totalPrice += $value['price_buy']*$value['quantity_required'];
                                                $i++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-8 col-md-offset-4">
                                <table class="table text-right">
                                    <tbody>
                                        <tr>
                                            <td><span class="bold"><?php echo _l('purchase_total_items'); ?> :</span>
                                            </td>
                                            <td class="total">
                                                <?php echo $i ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End Customize from invoice -->
                    </div>
                </div>
                <?php if(isset($item) && $item->status != 1 || !isset($item)) { ?>
                  <button class="btn btn-info mtop20 only-save customer-form-submiter" style="margin-left: 15px">
                    <?php echo _l('convert'); ?>
                </button>
                <?php } ?>
              </div>
            <?php echo form_close(); ?>
            </div>
        </div>
      </div>

        <!-- END PI -->
  </div>
</div>
</div>
</div>
</div>
</div>
<?php init_tail(); ?>
<script>
    var currentRate = {error: true};
    var loadCurrencyRate = () => {
        $.ajax({
            url: admin_url + '/purchase_orders/getExchangeRate/',
            dataType: 'json',
        }).done((data) => {
            currentRate = data;
            if(!currentRate.error) {
                
                $('select[id*="[currency]"]').toArray().forEach(v => {
                    console.log($(v).find('option:selected').text());
                    $(v).parents('td').prev().find('input').val( currentRate.currencies[$(v).find('option:selected').text()].toFixed(2).replace('.', ',') );
                });
            }
            $('select[id*="[currency]"]')
        });
    };
    loadCurrencyRate();
    $(function() {
        _validate_form($('.client-form'), {
            id_supplier: 'required',
            id_warehouse: 'required',
            date: 'required',
            date_import: 'required',
            explan: 'required',
            currency_id: 'required',
        });
        $('.table_purchase_suggested').find('input,select').attr('name', '');
    });
    //format currency
    function formatNumber(nStr, decSeperate=".", groupSeperate=",") {
        nStr += '';
        x = nStr.split(decSeperate);
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
        }
        return x1 + x2;
    }
    var getCurrencyExchangeRate = (id_currency) => {
        $.ajax({
            
        });
    };
    // get currency from supplier
    var getCurrencyFromSupplier = (idSupplier) => {
        $.ajax({
            url: '<?php echo admin_url('purchase_orders/getCurrencyIDFromSupplier/') ?>' + idSupplier,
            dataType: 'json',
        }).done((data) => {
            $('#currency_id').selectpicker('val', data.id);
            changeByCurrencyID(data.id);
        });
    };
    var changeByCurrencyID = (currency_id) => {
        $('.table_purchase_suggested').find('select[id*="currency"]').each((index, item) => {
            item = $(item).parents('tr');
            if(item.find('select').val() != currency_id) {
                item.hide();
            }
            else {
                item.show();
            }
        });
        changeStatics();
    };
    $('#currency_id').on('change', (e) => {
        let currentElement = $(e.currentTarget);
        changeByCurrencyID(currentElement.val());
    });
    $('#id_supplier').on('change', (e) => {
        let currentElement = $(e.currentTarget);
        if(typeof(currentElement.val()) != 'undefined' && currentElement.val() != '' && currentElement.val() != 0) {
            getCurrencyFromSupplier(currentElement.val());
        }
    });
    var backTrItem = (trItem) => {
        let currencyElement = $('#currency_id');
        $(trItem).parents('tr').find('select').attr('disabled', 'disabled');
        if($(trItem).parents('tr').hasClass('from-another')) return;
        $(trItem).parents('tr').find('a.btn').attr('onclick', 'return addTrItem(this);');
        $(trItem).parents('tr').find('a.btn').addClass('btn-success').removeClass('btn-danger');
        $(trItem).parents('tr').find('a.btn i').addClass('fa-plus').removeClass('fa-times');
        
        $('.table_purchase_suggested tbody').append($(trItem).parents('tr'));
        $('.table_purchase_suggested').find('input,select').attr('name', '');
        changeStatics();
        return false;
    }
    var addTrItem = (trItem) => {
        let currencyElement = $('#currency_id');
        if(typeof(currencyElement.val()) != 'undefined' && currencyElement.val() != '' && currencyElement.val() != 0) {
            $(trItem).parents('tr').find('select').removeAttr('disabled');
            $(trItem).parents('tr').find('select,input').each((index, item) => {
                $(item).attr('name', $(item).attr('id'));
            });
            $(trItem).parents('tr').find('a.btn').attr('onclick', 'return backTrItem(this)');
            $(trItem).parents('tr').find('a.btn').removeClass('btn-success').addClass('btn-danger');
            $(trItem).parents('tr').find('a.btn i').removeClass('fa-plus').addClass('fa-times');
            
            $('.table_purchase_orders tbody').append($(trItem).parents('tr'));
            currencyElement.prev().prev().attr('disabled', 'disabled');
        }
        else {
            alert_float('danger', 'Vui lòng chọn tiền tệ trước!');
        
        }
        
        changeStatics();
        return false;
    };
    var changeStatics = () => {
        let totalPurchaseSuggestedItem = $('.table_purchase_suggested tbody tr:visible').length;
        $('.table_purchase_suggested').next().find('.total').text(formatNumber(totalPurchaseSuggestedItem));
        
        let totalPurchaseOrderdItem = $('.table_purchase_orders tbody tr').length;
        $('.table_purchase_orders').next().find('.total').text(formatNumber(totalPurchaseOrderdItem));
    };
</script>
</body>
</html>