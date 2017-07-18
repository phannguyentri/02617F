<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
 <div class="content">
   <div class="row">

  <div class="col-md-12">
   <div class="panel_s">
     <div class="panel-body">
        <?php if (isset($item)) { ?>
        <?php echo form_hidden('isedit'); ?>
      <div class="clearfix"></div>
        <?php 
    } ?>
        <!-- Product information -->
        

          <h4 class="bold no-margin"><?php echo _l('purchase_suggested_add_heading'); ?></h4>
  <hr class="no-mbot no-border" />
  <div class="row">
    <div class="additional"></div>
    <div class="col-md-12">
        <?php
         if(isset($item))
            {
                $type='warning';
                $status='Đề xuất mới';
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
                        <a href="<?php echo admin_url('purchase_suggested/detail_pdf/' . $item->id . '?print=true') ?>" target="_blank" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="In" aria-describedby="tooltip652034"><i class="fa fa-print"></i></a>
                        <a href="<?php echo admin_url('purchase_suggested/detail_pdf/' . $item->id  ) ?>" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Xem PDF"><i class="fa fa-file-pdf-o"></i></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <?php echo form_open_multipart(admin_url('purchase_suggested/detail'), array('class' => 'client-form', 'autocomplete' => 'off')); ?>
                <div class="row">
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">            
                    <?php
                      // config
                    $attrs_not_select = array('data-none-selected-text' => _l('system_default_string'));
                    ?>
                    
                    <!-- <?php
                    $default_code = (isset($item) ? $item->code : "");
                    echo render_input('code', _l('purchase_suggested_code'), $default_code);
                    ?> -->
                    <div class="form-group">
                         <label for="number"><?php echo _l('Mã đề xuất'); ?></label>
                         <div class="input-group">
                          <span class="input-group-addon">
                            <?php echo get_option('prefix_purchase_suggested') ?></span>
                            <?php 
                                // var_dump($purchase);
                                if($item)
                                {

                                    $number=$item->code;
                                }
                                else
                                {
                                    $number=sprintf('%05d',getMaxID('id','tblpurchase_suggested')+1);
                                }
                            ?>
                            <input type="text" name="code" class="form-control" value="<?=$number ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" readonly>
                          </div>
                    </div>
                    <?php echo form_hidden('purchase_plan_id', $item->id); ?>
                    <?php
                    $default_name = (isset($item) ? $item->name : "");
                    echo render_input('name', _l('purchase_suggested_name'), $default_name);
                    ?>

                    <?php 
                    $reason = (isset($item) ? $item->reason : "");
                    echo render_textarea('reason', 'purchase_suggested_reason', $reason, array(), array(), '', 'tinymce');
                    ?>
                </div>

                
                
                
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <!-- Cusstomize from invoice -->
                    <div class="panel-body mtop10">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mbot25">
                                    <select class="selectpicker no-margin" data-width="100%" id="custom_item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                                        <option value=""></option>

                                        <?php foreach ($items as $product) { ?>
                                        <option value="<?php echo $product['id']; ?>" data-subtext="">(<?php echo $product['code']; ?>) <?php echo $product['name']; ?></option>
                                        <?php 
                                        } ?>

                                    <?php if (has_permission('items', '', 'create')) { ?>
                                    <option data-divider="true"></option>
                                    <option value="newitem" data-content="<span class='text-info'><?php echo _l('new_invoice_item'); ?></span>"></option>
                                    <?php 
                                } ?>
                                    </select>
                                </div>
                            </div>
                        
                            <div class="col-md-5 text-right show_quantity_as_wrapper">
                                
                            </div>
                        </div>
                        

                        <div class="table-responsive s_table">
                            <table class="table items item-purchase no-mtop">
                                <thead>
                                    <tr>
                                        <th><input type="hidden" id="itemID" value="" /></th>
                                        <th width="20%" class="text-left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_name'); ?>"></i> <?php echo _l('item_code'); ?></th>
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
                                            <?php echo _l('item_code'); ?>
                                        </td>
                                        <td>
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
                                            <button style="display:none" id="btnAdd" type="button" onclick="createTrItem(); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
                                        </td>
                                    </tr>
                                    <?php
                                    $i=0;
                                    $totalPrice=0;
                                    if(isset($item) && count($item->items) > 0) {
                                        
                                        foreach($item->items as $value) {
                                        ?>
                                    <tr class="sortable item">
                                        <td>
                                            <input type="hidden" name="items[<?php echo $i; ?>][id]" value="<?php echo $value->product_id; ?>">
                                        </td>
                                        <td class="dragger"><?php echo $value['name']; ?></td>
                                        <td><?php echo $value['unit_name']; ?></td>
                                        <td><input class="mainQuantity" type="number" name="items[<?php echo $i; ?>][quantity]" value="<?php echo $value['quantity_required']; ?>"></td>
                                            
                                        <td><?php echo number_format($value['unit_cost']); ?></td>
                                        <td><?php echo number_format($value['unit_cost']*$value['quantity_required']); ?></td>
                                        <td><?php echo $value->description	; ?></td>
                                        <td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this); return false;"><i class="fa fa-times"></i></a></td>
                                    </tr>
                                        <?php
                                            $totalPrice += $value['unit_cost']*$value['quantity_required'];
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
                                    <tr>
                                        <td><span class="bold"><?php echo _l('purchase_total_price'); ?> :</span>
                                        </td>
                                        <td class="totalPrice">
                                            <?php echo number_format($totalPrice) ?> VND
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!-- End Customize from invoice -->
                </div>
                
                <?php if(isset($item) && $item->status != 1 || !isset($item)) { ?>
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
    var itemList = <?php
        echo json_encode($items);
?>;

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
    var createTrItem = () => {
        if(!isNew) return;
        if( $('table.item-purchase tbody tr:gt(0)').find('input[value=' + $('tr.main').find('td:nth-child(1) > input').val() + ']').length ) {
            $('table.item-purchase tbody tr:gt(0)').find('input[value=' + $('tr.main').find('td:nth-child(1) > input').val() + ']').parent().find('td:nth-child(2) > input').focus();
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

        td1.find('input').val($('tr.main').find('td:nth-child(1) > input').val());
        td2.text($('tr.main').find('td:nth-child(2)').text());
        td3.text($('tr.main').find('td:nth-child(3)').text());
        td4.find('input').val($('tr.main').find('td:nth-child(4) > input').val());
        
        td5.text( $('tr.main').find('td:nth-child(5)').text() );
        td6.text( $('tr.main').find('td:nth-child(6)').text() );
        td7.text( $('tr.main').find('td:nth-child(7)').text() );
        
        newTr.append(td1);
        newTr.append(td2);
        newTr.append(td3);
        newTr.append(td4);
        newTr.append(td5);
        newTr.append(td6);
        newTr.append(td7);

        newTr.append('<td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this); return false;"><i class="fa fa-times"></i></a></td');
        $('table.item-purchase tbody').append(newTr);
        total++;
        totalPrice += $('tr.main').find('td:nth-child(4) > input').val() * $('tr.main').find('td:nth-child(5)').text().replace(/\+/g, ' ');
        uniqueArray++;
        refreshTotal();
        refreshAll();
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
    var deleteTrItem = (trItem) => {
        var current = $(trItem).parent().parent();
        totalPrice -= current.find('td:nth-child(4) > input').val() * current.find('td:nth-child(5)').text().replace(/\,/g, '');
        $(trItem).parent().parent().remove();
        total--;
        refreshTotal();
    };
    var refreshTotal = () => {
        $('.total').text(formatNumber(total));
        var items = $('table.item-purchase tbody tr:gt(0)');
        totalPrice = 0;
        $.each(items, (index,value)=>{
            totalPrice += $(value).find('td:nth-child(4) > input').val() * $(value).find('td:nth-child(5)').text().replace(/\,/g, '');
        });
        $('.totalPrice').text(formatNumber(totalPrice));
    };
    $('#custom_item_select').change((e)=>{
        var id = $(e.currentTarget).val();
        var itemFound = findItem(id);
        if(typeof(itemFound) != 'undefined') {
            var trBar = $('tr.main');
            //console.log(trBar.find('td:nth-child(2) > input'));
            
            trBar.find('td:first > input').val(itemFound.id);
            trBar.find('td:nth-child(2)').text(itemFound.name);
            trBar.find('td:nth-child(3)').text(itemFound.unit);
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
        
        var currentQuantityInput = $(e.currentTarget);
        var Gia = currentQuantityInput.parent().find(' + td');
        var Tong = Gia.find(' + td');
        Tong.text( formatNumber(Gia.text().replace(/\,/g, '') * currentQuantityInput.val()) );
        refreshTotal();
    });
</script>
</body>
</html>
