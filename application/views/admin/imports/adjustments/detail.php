<?php init_head(); ?>
<div id="wrapper">
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


  <h4 class="bold no-margin"><?php echo (isset($item) ? (($item->status==2)?_l('Xem phiếu nhập kho'):_l('Sửa phiếu nhập kho')) : _l('Tạo phiếu nhập kho')); ?></h4>
  <hr class="no-mbot no-border" />
  <div class="row">
    <div class="additional"></div>
    <div class="col-md-12">
        <?php
         if(isset($item))
            {
                if($item->status==0)
                {
                    $type='warning';
                    $status='Chưa duyệt';
                }
                elseif($item->status==1)
                {
                    $type='info';
                    $status='Đã xác nhận';
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
                $status='Phiếu mới';
            }

        ?>
        <div class="ribbon <?=$type?>"><span><?=$status?></span></div>
        <ul class="nav nav-tabs profile-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#item_detail" aria-controls="item_detail" role="tab" data-toggle="tab">
                    <?php echo _l('Chi tiết phiếu điều chỉnh kho'); ?>
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
                        <a href="<?php echo admin_url('imports/detail_pdf/' . $item->id . '?print=true') ?>" target="_blank" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="In" aria-describedby="tooltip652034"><i class="fa fa-print"></i></a>
                        <a href="<?php echo admin_url('imports/detail_pdf/' . $item->id  ) ?>" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Xem PDF"><i class="fa fa-file-pdf-o"></i></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'client-form', 'autocomplete' => 'off')); ?>
                <div class="row">
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <?php
                    $attrs_not_select = array('data-none-selected-text' => _l('system_default_string'));
                    ?>
                    <div class="form-group">
                         <label for="number"><?php echo _l('adjustment_code'); ?></label>
                         <div class="input-group">
                          <span class="input-group-addon">
                          <?php $prefix =($item) ? $item->prefix : get_option('prefix_adjustment'); ?>
                            <?=$prefix?>
                            <?php echo form_hidden('rel_type', 'adjustment'); ?>
                            <?=form_hidden('prefix',$prefix)?>
                            </span>
                            <?php
                                if($item)
                                {
                                    $number=$item->code;
                                }
                                else
                                {
                                    $number=sprintf('%05d',getMaxID('id','tblimports')+1);
                                }
                            ?>
                            <input type="text" name="code" class="form-control" value="<?=$number ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" readonly>
                          </div>
                    </div>

                    <?php $value = (isset($item) ? _d($item->date) : _d(date('Y-m-d')));?>
                    <?php echo render_date_input('date','Ngày',$value); ?>

                    <?php
                    $default_name = (isset($item) ? $item->name : "");
                    echo render_input('name', _l('import_name'), $default_name);
                    ?>

                   <!--  <?php
                    $selected=(isset($item) ? $warehouse_type : '');
                    echo render_select('warehouse_type',$warehouse_types,array('id','name'),'warehouse_type',$selected);
                    ?> -->

                    <?php
                    $selected=(isset($item) ? $warehouse_id : '1');
                    echo render_select('warehouse_id',$warehouses,array('warehouseid','warehouse'),'warehouse_name',$selected);
                    ?>

                    <?php
                    $reason = (isset($item) ? $item->reason : "");
                    echo render_textarea('reason', 'note', $reason, array(), array(), '', 'tinymce');
                    ?>
                </div>




                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">

                    <!-- Cusstomize from invoice -->
                    <div class="panel-body mtop10">
                        <div class="row">
                            <h4 class="text-center" style="background: #ccc;padding:10px; margin: 10px 15px;">SẢN PHẨM</h4>
                            <!-- <div class="col-md-4">
                                <div class="form-group mbot25">
                                    <select class="selectpicker no-margin" data-width="100%" id="custom_item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                                        <option value=""></option> -->

                                        <!-- <?php foreach ($items as $product) { ?>
                                        <option value="<?php echo $product['id']; ?>" data-subtext="">(<?php echo $product['code']; ?>) <?php echo $product['name']; ?></option>
                                        <?php
                                        } ?> -->
<!--
                                    </select>
                                </div>
                            </div> -->

                            <div class="col-md-5 text-right show_quantity_as_wrapper">

                            </div>
                        </div>

                        <div class="col-md-4">
                            <?php
                                echo render_select('categories_name', $categories_a, array('id', 'category'), 'Hãng sản phẩm');
                            ?>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mbot25">
                                <label for="productItems" class="control-label"><?=_l('item_name')?></label>
                                <select class="selectItem selectpicker no-margin" data-width="100%" id="productItems" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive s_table">
                            <table class="table items item-purchase no-mtop">
                                <thead>
                                    <tr>
                                        <th><input type="hidden" id="itemID" value="" /></th>
                                        <th width="20%" class="text-left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_name'); ?>"></i> <?php echo _l('item_name'); ?></th>
                                        <th width="10%" class="text-left"><?php echo _l('item_unit'); ?></th>
                                        <th width="10%" class="text-left"><?php echo _l('item_quantity'); ?></th>

                                        <th width="10%" class="text-left"><?php echo _l('item_price_buy'); ?></th>
                                        <th width="10%" class="text-left"><?php echo _l('purchase_total_price'); ?></th>
                                        <th width="15%" class="text-left"><?php echo _l('item_specification'); ?></th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr class="main">
                                        <td><input type="hidden" id="itemID" value="" /></td>
                                        <td>
                                            <?php echo _l('item_name'); ?>
                                        </td>
                                        <td>
                                            <input type="hidden" id="item_unit" value="" />
                                            <?php echo _l('item_unit'); ?>
                                        </td>

                                        <td>
                                            <input class="mainQuantity" type="number" min="1" value="1"  class="form-control" placeholder="<?php echo _l('item_quantity'); ?>">
                                        </td>

                                        <td>
                                            <?php echo _l('item_price_buy'); ?>
                                        </td>
                                        <td>
                                            0
                                        </td>
                                        <td>
                                            <?php echo _l('item_specification'); ?>
                                        </td>
                                        <td>
                                            <button style="display:none" id="btnAdd" type="button" onclick="createTrItem('product'); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
                                        </td>
                                    </tr>
                                    <?php
                                    $i=0;
                                    $totalProductPrice = 0;
                                    $numberTotalProduct = 0;
                                    if(isset($productItems) && count($productItems->items) > 0) {

                                        foreach($productItems->items as $value) {
                                        ?>
                                    <tr class="sortable item">
                                        <td>
                                            <input type="hidden" name="items[<?php echo $i; ?>][id]" value="<?php echo $value->product_id; ?>">
                                        </td>
                                        <td class="dragger"><?php echo $value->product_name; ?></td>
                                        <td><?php echo $value->unit_name; ?></td>
                                        <td><input class="mainQuantity" type="number" name="items[<?php echo $i; ?>][quantity]" value="<?php echo $value->quantity; ?>"></td>

                                        <td><?php echo number_format($value->unit_cost); ?></td>
                                        <td><?php echo number_format($value->sub_total); ?></td>
                                        <td><?php echo $value->product_specifications	; ?></td>
                                        <td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this, 'product'); return false;"><i class="fa fa-times"></i></a></td>
                                    </tr>

                                        <?php

                                            $numberTotalProduct += $value->quantity;
                                            $totalProductPrice  += $value->sub_total;
                                            $i++;
                                        }
                                    }

                                    // echo('<pre>');
                                    // print_r($productItems->items);
                                    // echo('</pre>');
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
                                            <?php echo $numberTotalProduct ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="bold"><?php echo _l('purchase_total_price'); ?> :</span>
                                        </td>
                                        <td class="totalPrice">
                                            <?php echo number_format($totalProductPrice) ?> VND
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!-- End Customize from invoice -->
                </div>



                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">

                    <!-- Cusstomize from invoice -->
                    <div class="panel-body mtop10">
                        <div class="row">
                            <h4 class="text-center" style="background: #ccc;padding:10px; margin: 10px 15px;">LINH KIỆN</h4>

                            <div class="col-md-5 text-right show_quantity_as_wrapper">

                            </div>
                        </div>

                        <div class="col-md-4">
                            <?php
                                echo render_select('categories_name_accessories', $categories_b, array('id', 'category'), 'Hãng linh kiện');
                            ?>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mbot25">
                                <label for="accessoryItems" class="control-label"><?=_l('item_name')?></label>
                                <select class="selectItem selectpicker no-margin" data-width="100%" id="accessoryItems" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive s_table">
                            <table class="table items item-purchase-accessory no-mtop">
                                <thead>
                                    <tr>
                                        <th><input type="hidden" id="itemID" value="" /></th>
                                        <th width="20%" class="text-left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_name'); ?>"></i> <?php echo _l('item_name'); ?></th>
                                        <th width="10%" class="text-left"><?php echo _l('item_unit'); ?></th>
                                        <th width="10%" class="text-left"><?php echo _l('item_quantity'); ?></th>

                                        <th width="10%" class="text-left"><?php echo _l('item_price_buy'); ?></th>
                                        <th width="10%" class="text-left"><?php echo _l('purchase_total_price'); ?></th>
                                        <th width="15%" class="text-left"><?php echo _l('item_specification'); ?></th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr class="main2">
                                        <td><input type="hidden" id="itemID" value="" /></td>
                                        <td>
                                            <?php echo _l('item_name'); ?>
                                        </td>
                                        <td>
                                            <input type="hidden" id="item_unit" value="" />
                                            <?php echo _l('item_unit'); ?>
                                        </td>

                                        <td>
                                            <input class="mainQuantity"  type="number" min="1" value="1"  class="form-control" placeholder="<?php echo _l('item_quantity'); ?>">
                                        </td>

                                        <td>
                                            <?php echo _l('item_price_buy'); ?>
                                        </td>
                                        <td>
                                            0
                                        </td>
                                        <td>
                                            <?php echo _l('item_specification'); ?>
                                        </td>
                                        <td>
                                            <button style="display:none" id="btnAddAccessory" type="button" onclick="createTrItem('accessory'); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
                                        </td>
                                    </tr>
                                    <?php
                                    $countAccessory       = 0;
                                    $totalAccessoryPrice  = 0;
                                    $numberTotalAccessory = 0;
                                    if(isset($accessoryItems) && count($accessoryItems->items) > 0) {

                                        foreach($accessoryItems->items as $value) {
                                        ?>
                                    <tr class="sortable item">
                                        <td>
                                            <input type="hidden" name="items[<?php echo $i; ?>][id]" value="<?php echo $value->product_id; ?>">
                                        </td>
                                        <td class="dragger"><?php echo $value->product_name; ?></td>
                                        <td><?php echo $value->unit_name; ?></td>
                                        <td><input class="mainQuantity" type="number" name="items[<?php echo $i; ?>][quantity]" value="<?php echo $value->quantity; ?>"></td>

                                        <td><?php echo number_format($value->unit_cost); ?></td>
                                        <td><?php echo number_format($value->sub_total); ?></td>
                                        <td><?php echo $value->product_specifications   ; ?></td>
                                        <td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this, 'accessory'); return false;"><i class="fa fa-times"></i></a></td>
                                    </tr>
                                        <?php
                                            $totalAccessoryPrice  += $value->sub_total;
                                            $numberTotalAccessory += $value->quantity;
                                            $countAccessory++;
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
                                        <td class="count-accessory">
                                            <?php echo $numberTotalAccessory ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="bold"><?php echo _l('purchase_total_price'); ?> :</span>
                                        </td>
                                        <td class="totalPriceAccessory">
                                            <?php echo number_format($totalAccessoryPrice) ?> VND
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!-- End Customize from invoice -->
                </div>




                <?php if(isset($item) && $item->status != 2 || !isset($item)) { ?>
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
    _validate_form($('.client-form'),{code:'required',warehouse_type:'required',warehouse_id:'required'});

    var itemList = <?php echo json_encode($items);?>;
    var productList = null;
    var accessoryList = null;

    $('#categories_name_accessories').change(function(e){
       let category_id = $(this).val();
       loadProductsInCategory(category_id, 'accessoryItems');
    });


    $('#categories_name').change(function(e){
       let category_id = $(this).val();
       loadProductsInCategory(category_id, 'productItems');
    });

    function loadProductsInCategory(category_id, selectId){
        var productSelect = $('#'+selectId);

        productSelect.find('option:gt(0)').remove();
        productSelect.selectpicker('refresh');

        $.ajax({
            url      : admin_url + 'invoice_items/getProductsInCate/' + category_id,
            dataType : 'json',
            async    : false,
        })
        .done(function(data){

            if (selectId == 'productItems') {
                productList = data;
            }else{
                accessoryList = data;
            }


            $.each(data, function(key, value){
               productSelect.append('<option value="' + value.id + '">'+'('+ value.code +') '  + value.name + '</option>');
            });
            productSelect.selectpicker('refresh');
        });

    }

    $('.selectItem').change(function(e) {
        let productId = $(this).val();

        let classMain = ($(this).attr('id') === 'productItems') ? 'main' : 'main2';
        let idBtnAdd  = ($(this).attr('id') === 'productItems') ? 'btnAdd' : 'btnAddAccessory';
        let listItem  = ($(this).attr('id') === 'productItems') ? productList : accessoryList;


        let productItem = findItem(productId, listItem);

        if(typeof(productItem) != 'undefined'){
            let trBar = $('tr.'+classMain);
            trBar.find('td:first > input').val(productItem.id);
            trBar.find('td:nth-child(2)').text(productItem.name);
            trBar.find('td:nth-child(3)').text(productItem.unit_name);
            trBar.find('td:nth-child(3) > input').val(productItem.unit);
            trBar.find('td:nth-child(4) > input').val(1);
            trBar.find('td:nth-child(5)').text(formatNumber(productItem.price_buy));
            trBar.find('td:nth-child(6)').text(formatNumber(productItem.price_buy * 1) );
            trBar.find('td:nth-child(7)').text(productItem.specification);
            isNew = true;
            $('#'+idBtnAdd).show();
        }else {
            isNew = false;
            $('#'+idBtnAdd).hide();
        }
    });

    var findProductItem = (id) => {
        var itemResult;

        $.each(itemList, (index, value) => {
            if(value.id == id) {
                itemResult = value;
                return false;
            }
        });
        return itemResult;
    };

    var findItem = (id, listItem) => {
        var itemResult;

        $.each(listItem, (index, value) => {
            if(value.id == id) {
                itemResult = value;
                return false;
            }
        });
        return itemResult;
    };


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



    var total                = <?php echo $i ?>;
    var countAccessory       = <?php echo $countAccessory ?>;
    var totalPrice           = <?php echo $totalProductPrice ?>;
    var totalAccessoryPrice  = <?php echo $totalAccessoryPrice ?>;
    var uniqueArray          = <?php echo $i ?>;
    var isNew = false;



    var createTrItem = (kindCreate) => {


        let classTable  = (kindCreate == 'product') ? 'item-purchase' : 'item-purchase-accessory';
        let classMain   = (kindCreate == 'product') ? 'main' : 'main2';
        let delArgument = (kindCreate == 'product') ? 'product' : 'accessory';

        if(!isNew) return;
        if( $('table.'+classTable+' tbody tr:gt(0)').find('input[value=' + $('tr.'+classMain).find('td:nth-child(1) > input').val() + ']').length ) {
            $('table.'+classTable+' tbody tr:gt(0)').find('input[value=' + $('tr.'+classMain).find('td:nth-child(1) > input').val() + ']').parent().find('td:nth-child(2) > input').focus();
            alert('Sản phẩm này đã được thêm, vui lòng lòng kiểm tra lại!');
            return;
        }
        var newTr = $('<tr class="sortable item"></tr>');

        var td1 = $('<td><input type="hidden" name="items[' + uniqueArray + '][id]" value="" /></td>');
        var td2 = $('<td class="dragger"></td>');
        var td3 = $('<td></td>');
        var td4 = $('<td><input class="mainQuantity" type="number" name="items[' + uniqueArray + '][quantity]" value="" /></td>');
        var td5 = $('<td></td>');
        var td6 = $('<td></td>');
        var td7 = $('<td></td>');

        td1.find('input').val($('tr.'+classMain).find('td:nth-child(1) > input').val());
        td2.text($('tr.'+classMain).find('td:nth-child(2)').text());
        td3.text($('tr.'+classMain).find('td:nth-child(3)').text());
        td4.find('input').val($('tr.'+classMain).find('td:nth-child(4) > input').val());

        td5.text( $('tr.'+classMain).find('td:nth-child(5)').text() );
        td6.text( $('tr.'+classMain).find('td:nth-child(6)').text() );
        td7.text( $('tr.'+classMain).find('td:nth-child(7)').text() );

        newTr.append(td1);
        newTr.append(td2);
        newTr.append(td3);
        newTr.append(td4);
        newTr.append(td5);
        newTr.append(td6);
        newTr.append(td7);

        newTr.append('<td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this,\''+delArgument+'\'); return false;"><i class="fa fa-times"></i></a></td');
        $('table.'+classTable+' tbody').append(newTr);

        (kindCreate == 'product') ? total++ : countAccessory++;
        totalPrice += $('tr.'+classMain).find('td:nth-child(4) > input').val() * $('tr.'+classMain).find('td:nth-child(5)').text().replace(/\+/g, ' ');
        uniqueArray++;
        refreshTotal(classTable);
        // refreshAll();
    };
    var refreshAll = () => {
        isNew = false;
        $('#btnAdd').hide();
        $('#custom_item_select').val('');
        $('#custom_item_select').selectpicker('refresh');
        var trBar = $('tr.main');
        //console.log(trBar.find('td:nth-child(2) > input'));

        trBar.find('td:first > input').val("");
        trBar.find('td:nth-child(2) > input').val('');
        trBar.find('td:nth-child(3) > input').val(1);
        trBar.find('td:nth-child(4) > input').val('');
        trBar.find('td:nth-child(5) > textarea').text('');


    };
    var deleteTrItem = (trItem, kindCreate = 'product') => {
        var current = $(trItem).parent().parent();
        totalPrice -= current.find('td:nth-child(4) > input').val() * current.find('td:nth-child(5)').text().replace(/\,/g, '');
        $(trItem).parent().parent().remove();
        if (kindCreate == 'product') {
            total--;
            classTable = 'item-purchase';
        }else{
            countAccessory--;
            classTable = 'item-purchase-accessory';
        }

        refreshTotal(classTable);
    };
    var refreshTotal = (classTable) => {
        let clasTotalPrice  = (classTable == 'item-purchase') ? 'totalPrice' : 'totalPriceAccessory';
        let classCount      = (classTable == 'item-purchase') ? 'total' : 'count-accessory';
        let count           = (classTable == 'item-purchase') ? total : countAccessory;

        var items = $('table.'+classTable+' tbody tr:gt(0)');
        totalPrice = 0;

        let numberTotalItems = 0;

        $.each(items, (index, value)=>{
            totalPrice += $(value).find('td:nth-child(4) > input').val() * $(value).find('td:nth-child(5)').text().replace(/\,/g, '');
            numberTotalItems += parseInt($(value).find('td:nth-child(4) > input').val());
        });
        $('.'+clasTotalPrice).text(formatNumber(totalPrice));
        $('.'+classCount).text(formatNumber(numberTotalItems));
    };

    $('#custom_item_select').change((e)=>{
        var id = $(e.currentTarget).val();
        var itemFound = findItem(id);
        if(typeof(itemFound) != 'undefined') {
            var trBar = $('tr.main');
            //console.log(trBar.find('td:nth-child(2) > input'));

            trBar.find('td:first > input').val(itemFound.id);
            trBar.find('td:nth-child(2)').text(itemFound.name);
            trBar.find('td:nth-child(3)').text(itemFound.unit_name);
            trBar.find('td:nth-child(3) > input').val(itemFound.unit);
            trBar.find('td:nth-child(4) > input').val(1);
            trBar.find('td:nth-child(5)').text(formatNumber(itemFound.price_buy));
            trBar.find('td:nth-child(6)').text(  formatNumber(itemFound.price_buy * 1) );
            trBar.find('td:nth-child(7)').text(itemFound.specification);
            isNew = true;
            $('#btnAdd').show();
        }
        else {
            isNew = false;
            $('#btnAdd').hide();
        }
    });

    $(document).on('keyup', '.mainQuantity',(e)=>{
        if ($(e.currentTarget).parents('table').hasClass('item-purchase')) {
            classTable = 'item-purchase';
        }else{
            classTable = 'item-purchase-accessory';
        }

        var currentQuantityInput = $(e.currentTarget);
        var Gia = currentQuantityInput.parent().find(' + td');
        var Tong = Gia.find(' + td');
        Tong.text( formatNumber(Gia.text().replace(/\,/g, '') * currentQuantityInput.val()) );
        refreshTotal(classTable);
    });
    $('#warehouse_type').change(function(e){
      var warehouse_type = $(e.currentTarget).val();
      loadWarehouses(warehouse_type,'');
    });
    function loadWarehouses(warehouse_type,default_value=''){
        var warehouse_id=$('#warehouse_id');
        warehouse_id.find('option').remove()
        warehouse_id.selectpicker("refresh");
        if(warehouse_id != 0 && warehouse_id != '') {
        $.ajax({
          url : admin_url + 'warehouses/getWarehouses/' + warehouse_type,
          dataType : 'json',
        })
        .done(function(data){
          warehouse_id.find('option').remove();
          warehouse_id.append('<option value=""></option>');
          $.each(data, function(key,value){
            var stringSelected = "";
            if(value.warehouseid == default_value) {
              stringSelected = ' selected="selected"';
            }
            warehouse_id.append('<option value="' + value.warehouseid + '"'+stringSelected+'>' + value.warehouse + '</option>');
          });
          warehouse_id.selectpicker('refresh');
        });
      }
    }

</script>
</body>
</html>
