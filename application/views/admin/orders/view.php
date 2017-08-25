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
        

          <h4 class="bold no-margin"><?php echo _l('orders_view_heading') ?></h4>
  <hr class="no-mbot no-border" />
  <div class="row">
    <div class="additional"></div>
    <div class="col-md-12">
        <?php
        $lock = false;
        $array_attribute_lock = array();
         if(isset($item))
            {
                $lock = $item->isLock;
                if($lock) {
                    $array_attribute_lock = array('disabled' => 'disabled', 'readonly' => 'readonly');
                }
                if($item->user_head_id==0)
                {
                    $type='warning';
                    $status='Chưa duyệt';
                }
                else
                {
                    $type='success';
                    $status='Đã duyệt';
                }
            }
            else
            {
                $type='warning';
                $status='Đơn hàng mới';
            }

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
                        <a href="<?php echo admin_url('purchase_orders/detail_pdf/' . $item->id . '?print=true') ?>" target="_blank" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="In" aria-describedby="tooltip652034"><i class="fa fa-print"></i></a>
                        <a href="<?php echo admin_url('purchase_orders/detail_pdf/' . $item->id  ) ?>?pdf=true" target="_blank" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Xem PDF"><i class="fa fa-file-pdf-o"></i></a>
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
                        <label for="number"><?php echo _l('purchase_constract_code'); ?></label>  
                                    
                        <input type="text" name="code" class="form-control" value="<?=$item->code ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" readonly>
                                  
                    </div>
                    
                    <div class="form-group">
                        <label for="id_purchase_suggested"><?php echo _l('purchase_suggested_code') ?></label>
                        <input type="text" class="form-control" value="<?php echo $item->code_purchase_suggested ?>" readonly>
                    </div>

                    
                    <?php 
                        $default_supplier = $item->id_supplier;
                        echo render_select('id_supplier', $suppliers, array('userid', 'company'), 'suppliers', $default_supplier, array('disabled'=>'disabled'));
                    ?>

                    <?php 
                        $default_currency = $item->currency_id;
                        echo render_select('currency_id', $currencies, array('id', 'name'), 'currency', $default_currency, array('disabled'=>'disabled'));
                    ?>

                    <?php
                        $default_date_create = date("Y-m-d", strtotime($item->date_create));
                        echo render_date_input( 'date_create', 'project_datecreated' , $default_date_create, $array_attribute_lock); 
                    ?>
                    
                </div>
                
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <?php
                        $default_date_import = date("Y-m-d", strtotime($item->date_import));
                        echo render_date_input( 'date_import', 'orders_date_import' , $default_date_import, $array_attribute_lock); 
                    ?>
                    <?php 
                    $reason = $item->explan;
                    echo render_textarea('explan', 'orders_explan', $reason, $array_attribute_lock, array(), '', 'tinymce');
                    ?>
                </div>
                
                
                
                
                <!-- Edited -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <!-- Cusstomize from invoice -->
                    <div class="panel-body mtop10">
                    <?php if($lock){ $display='style="display: none;"';  }?>
                        <div class="row" <?=$display?> >
                            <div class="col-md-4">
                                <div class="form-group mbot25">
                                    <select class="selectpicker no-margin" data-width="100%" id="custom_item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                                        <option value=""></option>

                                        <?php foreach ($products as $product) { ?>
                                        <option value="<?php echo $product['id']; ?>" data-subtext="">(<?php echo $product['code']; ?>) <?php echo $product['name']; ?></option>
                                        <?php 
                                        } ?>

                                    <!-- <?php if (has_permission('items', '', 'create')) { ?>
                                    <option data-divider="true"></option>
                                    <option value="newitem" data-content="<span class='text-info'><?php echo _l('new_invoice_item'); ?></span>"></option>
                                    <?php } ?> -->
                                    </select>
                                </div>
                            </div>
                        
                            <div class="col-md-5 text-right show_quantity_as_wrapper">
                                
                            </div>
                        </div>
                        <div class="table-responsive s_table">
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
                                    <tr class="main" <?=$display?> >
                                        <td><input type="hidden" id="itemID" value="" /></td>
                                        <td>
                                            <?php echo _l('item_name'); ?>
                                        </td>
                                        <td>
                                            <input type="hidden" id="item_unit" value="" />
                                            <?php echo _l('item_unit'); ?>
                                        </td>

                                        <td>
                                            <input style="width: 100px" class="mainQuantity" type="number" min="1" value="1"  class="form-control" placeholder="<?php echo _l('item_quantity'); ?>">
                                        </td>
                                        <td>
                                        <?php 
                                            echo render_select('select_kindof_warehouse', $warehouse_types, array('id', 'name'));
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                            echo render_select('select_warehouse', array(), array('id', 'name'));
                                        ?>
                                        </td>
                                        <td>
                                        <?php
                                            echo render_input('items['.$i.'][exchange_rate]', '', 1);
                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo render_select('select_currency', $currencies, array('id', 'name'), '', $default_currency, array('disabled'=>'disabled'), array(), '', '', false);
                                        ?>
                                        </td>
                                        <td>
                                            <input style="width: 100px" step="0.01" class="mainPriceBuy" type="number" value=""  class="form-control" placeholder="<?php echo _l('item_price_buy'); ?>">
                                        </td>
                                        <td>
                                            0
                                        </td>
                                        <td>
                                            <button style="display:none" id="btnAdd" type="button" onclick="createTrItem(); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
                                        </td>
                                    </tr>
                                    <?php
                                    $i=0;
                                    $totalPrice=0;
                                    
                                    if(isset($item) && count($item->products) > 0) {
                                        
                                        foreach($item->products as $value) {
                                            $value = (array)$value;
                                        ?>
                                    <tr class="sortable item">
                                        <td>
                                            <input type="hidden" name="items[<?php echo $i; ?>][product_id]" value="<?php echo $value['product_id']; ?>">
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
                                        <input <?=($lock ? "disabled=\"disabled\"":"")?> style="width: 100px; <?=$style?>" class="mainQuantity <?=$err?>" type="number" name="items[<?php echo $i; ?>][quantity]" value="<?php echo $value['product_quantity']; ?>">
                                        </td>
                                            
                                        <td><?php echo $value['warehouse_type']->kindof_warehouse_name ?></td>
                                        <td><input type="hidden" data-store="<?=$value['warehouse_type']->maximum_quantity ?>" name="items[<?=$i?>][warehouse]" value="<?=$value['warehouse_id']?>"><?php echo $value['warehouse_type']->warehouse ?>(tối đa <?=$value['warehouse_type']->maximum_quantity?>)</td>
                                        <td>
                                        <?php
                                            $array_disabled = array();
                                            if($lock) {
                                                $array_disabled = array('disabled'=>'disabled');
                                            }
                                            echo render_input('items['.$i.'][exchange_rate]', '', $value['exchange_rate'], 'text', array(), $array_disabled, '', "mainExchange_Rate");
                                        ?>
                                        </td>
                                        <td>
                                            <?php echo render_select('items['.$i.'][currency]', $currencies, array('id', 'name'), '', $value['currency_id'], array(), array('disabled'=>'disabled')); ?>
                                        </td>
                                        <td>
                                            <input <?=($lock ? "disabled=\"disabled\"":"")?> style="width: 100px" class="mainPriceBuy" name="items[<?php echo $i ?>][price_buy]" step="0.01" type="number" value="<?php echo $value['price_buy'] ?>"  class="form-control" placeholder="<?php echo _l('item_price_buy'); ?>">
                                        </td>
                                        <td>
                                            <?php echo number_format($value['price_buy']*$value['product_quantity']) ?>
                                        </td>
                                        <td></td>
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

                <?php if(isset($item) && !$item->isLock || !isset($item)) { ?>
                <button class="btn btn-info mtop20 only-save customer-form-submiter" style="margin-left: 15px">
                    <?php echo _l('submit'); ?>
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
    var itemList = <?php echo json_encode($products);?>;
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

    var findItem = (id) => {
        var itemResult;
        $.each(itemList, (index,value) => {
            if(value.id == id) {
                itemResult = value;
                return false;
            }
        });
        return itemResult;
    };
    var total = <?php echo $i ?>;
    var totalPrice = <?php echo $totalPrice ?>;
    var uniqueArray = <?php echo $i ?>;
    var isNew = false;
	// Remove select name
	$('#select_kindof_warehouse').removeAttr('name');
	$('#select_warehouse').removeAttr('name');
    $('#select_currency').removeAttr('name');
    var createTrItem = () => {
        if(!isNew) return;
        if(!$('tr.main #select_warehouse option:selected').length || $('tr.main #select_warehouse option:selected').val() == '') {
            alert_float('danger', "Vui lòng chọn kho chứa sản phẩm!");
            return;
        }
		if(!$('tr.main #select_currency option:selected').length || $('tr.main #select_currency option:selected').val() == '') {
            alert_float('danger', "Vui lòng chọn tiền tệ cho sản phẩm!");
            return;
        }
		if($.trim($('tr.main .mainPriceBuy').val()) == '') {
            alert_float('danger', "Vui lòng chọn giá nhập cho sản phẩm!");
            return;
        }
        if( $('table.item-export tbody tr:gt(0)').find('input[value=' + $('tr.main').find('td:nth-child(1) > input').val() + ']').length ) {
            $('table.item-export tbody tr:gt(0)').find('input[value=' + $('tr.main').find('td:nth-child(1) > input').val() + ']').parent().find('td:nth-child(2) > input').focus();
            alert_float('danger', "Sản phẩm này đã được thêm, vui lòng lòng kiểm tra lại!");
            return;
        }
        if($('tr.main').find('td:nth-child(4) > input').val() > $('tr.main #select_warehouse option:selected').data('store')) {
            alert_float('danger', 'Kho ' + $('tr.main #select_warehouse option:selected').text() + '. Bạn đã nhập ' + $('tr.main').find('td:nth-child(4) > input').val() + ' là quá số lượng cho phép.');
            return;
        }
        var newTr = $('<tr class="sortable item"></tr>');
        
        var td1 = $('<td><input type="hidden" name="items[' + uniqueArray + '][product_id]" value="" /></td>');
        var td2 = $('<td class="dragger"></td>');
        var td3 = $('<td></td>');
        var td4 = $('<td><input style="width: 100px" class="mainQuantity" type="number" name="items[' + uniqueArray + '][quantity]" value="" /></td>');
        var td5 = $('<td></td>');
        var td6 = $('<td></td>');
        var td7 = $('<td><input class="mainExchange_Rate" type="number" name="items[' + uniqueArray + '][exchange_rate]" value="" /></td>');
        var td8 = $('<td></td>');
		var td9 = $('<td></td>');
        var td10 = $('<td></td>')

        td1.find('input').val($('tr.main').find('td:nth-child(1) > input').val());
        td2.text($('tr.main').find('td:nth-child(2)').text());
        td3.text($('tr.main').find('td:nth-child(3)').text());
		td4.find('input').val($('tr.main').find('td:nth-child(4) > input').val());
        
		//td5.text( $('tr.main').find('td:nth-child(5)').text());
        
		td5.text( $('tr.main').find('td:nth-child(5) select option:selected').text());
        td6.append('<input type="hidden" data-store="'+$('tr.main').find('td:nth-child(6) select option:selected').data('store')+'" name="items[' + uniqueArray + '][warehouse]" value="'+$('tr.main').find('td:nth-child(6) select option:selected').val()+'" />');
        td6.append($('tr.main').find('td:nth-child(6) select option:selected').text());
        
        console.log($('tr.main').find('td:nth-child(7) input'));
        td7.find('input').val($('tr.main').find('td:nth-child(7) input').val());

        let objCurrency = $('tr.main').find('td:nth-child(8)').find('select').clone(); 
		objCurrency.attr('name', 'items[' + uniqueArray + '][currency]');
		objCurrency.removeAttr('id').val($('tr.main').find('td:nth-child(8)').find('select').selectpicker('val'));

		td8.append(objCurrency);
		let objPriceBuy = $('tr.main').find('td:nth-child(9)').find('input').clone(); 
		objPriceBuy.attr('name', 'items[' + uniqueArray + '][price_buy]');
		objPriceBuy.removeAttr('id');
		td9.append(objPriceBuy);
        td10.append($('tr.main').find('td:nth-child(10)').text());

		newTr.append(td1);
        newTr.append(td2);
        newTr.append(td3);
        newTr.append(td4);
        newTr.append(td5);
        newTr.append(td6);
        newTr.append(td7);
        newTr.append(td8);
		newTr.append(td9);
        newTr.append(td10);

        newTr.append('<td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this); return false;"><i class="fa fa-times"></i></a></td');
        $('table.item-export tbody').append(newTr);
        total++;
        totalPrice += $('tr.main').find('td:nth-child(4) > input').val() * $('tr.main').find('td:nth-child(5)').text().replace(/\+/g, ' ');
        uniqueArray++;
        refreshTotal();
        // refreshAll();
    };
    var refreshAll = () => {
        isNew = false;
        $('#btnAdd').hide();
        $('#custom_item_select').val('');
        $('#custom_item_select').selectpicker('refresh');
        var trBar = $('tr.main');
        
        trBar.find('td:first > input').val("");
        trBar.find('td:nth-child(2) > input').val('');
        trBar.find('td:nth-child(3) > input').val(1);
        trBar.find('td:nth-child(4) > input').val('');
        trBar.find('td:nth-child(5) > textarea').text('');


    };
    var deleteTrItem = (trItem) => {
        var current = $(trItem).parent().parent();
        totalPrice -= current.find('td:nth-child(4) > input').val() * current.find('td:nth-child(5)').text().replace(/\,/g, '');
        $(trItem).parent().parent().remove();
        total--;
        refreshTotal();
    };
    var refreshTotal = () => {
        $('.total').text(formatNumber(total));
        var items = $('table.item-export tbody tr:gt(0)');
        
		$('.selectpicker').selectpicker('refresh');
	};
    $('#custom_item_select').change((e)=>{
        var id = $(e.currentTarget).val();
        var itemFound = findItem(id);

        $('#select_kindof_warehouse').val('');
        $('#select_kindof_warehouse').selectpicker('refresh');
		// $('#select_currency').find('option:first').attr('selected', 'selected');
        // $('#select_currency').selectpicker('refresh');
        var warehouse_id=$('#select_warehouse');
        warehouse_id.find('option:gt(0)').remove();
        warehouse_id.selectpicker('refresh');

        if(typeof(itemFound) != 'undefined') {
            var trBar = $('tr.main');
            
            trBar.find('td:first > input').val(itemFound.id);
            trBar.find('td:nth-child(2)').text(itemFound.name+' ('+itemFound.prefix+itemFound.code+')');
            trBar.find('td:nth-child(3)').text(itemFound.unit_name);
            trBar.find('td:nth-child(3) > input').val(itemFound.unit);
            trBar.find('td:nth-child(4) > input').val(1);

            trBar.find('td:nth-child(5)');
            trBar.find('td:nth-child(6)');
            trBar.find('td:nth-child(7)');
            trBar.find('td:nth-child(8)');
            isNew = true;
            $('#btnAdd').show();
        }
        else {
            isNew = false;
            $('#btnAdd').hide();
        }
    });
    $('#select_warehouse').on('change', (e)=>{
        if($(e.currentTarget).val() != '') {
            $(e.currentTarget).parents('tr').find('input.mainQuantity').attr('data-store', $(e.currentTarget).find('option:selected').data('store'));
        }
    });
	var calculateTotal = (currentInput) => {
		currentInput = $(currentInput);		
		let soLuong = currentInput.parents('tr').find('.mainQuantity'); 
		let gia = currentInput.parents('tr').find('.mainPriceBuy'); 
		let tdTong = gia.parent().find(' + td');
		tdTong.text( formatNumber( String(soLuong.val()).replace(/\,/g, '') * String(gia.val()).replace(/\,/g, '')) );
        refreshTotal();
	};
	$(document).on('keyup', '.mainPriceBuy', (e)=>{
		var currentPriceBuyInput = $(e.currentTarget);
		calculateTotal(e.currentTarget);
	});
    $(document).on('keyup', '.mainQuantity', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        let elementToCompare;
        if(typeof(currentQuantityInput.attr('data-store')) == 'undefined' )
            elementToCompare = currentQuantityInput.parents('tr').find('input[data-store]');
        else
            elementToCompare = currentQuantityInput;
		
        if(parseInt(currentQuantityInput.val()) > parseInt(elementToCompare.attr('data-store'))){
            currentQuantityInput.attr("style", "width: 100px;border: 1px solid red !important");
            currentQuantityInput.attr('data-toggle', 'tooltip');
            currentQuantityInput.attr('data-trigger', 'manual');
            currentQuantityInput.attr('title', 'Số lượng vượt mức cho phép!');
            // $('[data-toggle="tooltip"]').tooltip();
            currentQuantityInput.off('focus', '**').off('hover', '**');
            currentQuantityInput.tooltip('fixTitle').focus(()=>$(this).tooltip('show')).hover(()=>$(this).tooltip('show'));
            // error flag
            currentQuantityInput.addClass('error');
            currentQuantityInput.focus();
        }
        else {
            currentQuantityInput.attr('title', 'OK!').tooltip('fixTitle').tooltip('show');
            currentQuantityInput.attr("style", "width: 100px;");
            // remove flag
            currentQuantityInput.removeClass('error');
            currentQuantityInput.focus();
        }
        calculateTotal(e.currentTarget);
    });
    $('#select_kindof_warehouse').change(function(e){
        var warehouse_type = $(e.currentTarget).val();
        var product = $(e.currentTarget).parents('tr').find('td:first input');
        if(warehouse_type != '' && product.val() != '') {
            loadWarehouses(warehouse_type,product.val()); 
        }
    });
    function loadWarehouses(warehouse_type, filter_by_product,default_value=''){
        var warehouse_id=$('#select_warehouse');
        warehouse_id.find('option:gt(0)').remove();
        warehouse_id.selectpicker('refresh');
        if(warehouse_id.length) {
            $.ajax({
                url : admin_url + 'warehouses/getWarehouses/' + warehouse_type + '/' + filter_by_product + '/true',
                dataType : 'json',
            })
            .done(function(data){
				console.log(data);
                $.each(data, function(key,value){
                    var stringSelected = "";
                    if(value.warehouseid == default_value) {
                        stringSelected = ' selected="selected"';
                    }
					warehouse_id.append('<option data-store="'+(value.items[0].maximum_quantity - value.items[0].product_quantity)+'" value="' + value.warehouseid + '"'+stringSelected+'>' + value.warehouse + '(nhập tối đa '+(value.items[0].maximum_quantity - value.items[0].product_quantity)+')</option>');
                });
                warehouse_id.selectpicker('refresh');
            });
        }
    }
    $('.client-form').on('submit', (e)=>{
        if($('input.error').length > 0) {
            e.preventDefault();
            alert_float('danger', 'Giá trị không hợp lệ!');    
        }
        
    });
</script>
</body>
</html>
