 <style type="text/css">
   @media (min-width: 992px){

.modal-lg {
    width: 1350px;
}
}
</style>
<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="bold no-margin"><?php echo (isset($item) ? _l('edit_export_order') : _l('add_export_order')); ?></h4>
</div>
<div class="modal-body">
   <div class="row">
    <div class="additional"></div>

         <?php if (isset($item)) { ?>
        <?php echo form_hidden('isedit'); ?>
        <?php echo form_hidden('itemid', $item->id); ?>
      <div class="clearfix"></div>
        <?php 
        } ?>
  
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
                    <?php echo _l('export_detail'); ?>
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
                        <a href="<?php echo admin_url('exports/pdf/' . $item->id . '?print=true') ?>" target="_blank" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="In" aria-describedby="tooltip652034"><i class="fa fa-print"></i></a>
                        <a href="<?php echo admin_url('exports/pdf/' . $item->id  ) ?>" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Xem PDF"><i class="fa fa-file-pdf-o"></i></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'sales-form', 'autocomplete' => 'off')); ?>
                <div class="row">
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">            
                    <?php
                    $attrs_not_select = array('data-none-selected-text' => _l('system_default_string'));
                    ?>
                    <div class="form-group">
                         <label for="number"><?php echo _l('export_code'); ?></label>
                         <div class="input-group">
                          <span class="input-group-addon">
                          <?php $prefix =($item) ? $item->prefix : get_option('prefix_export'); ?>
                            <?=$prefix?>
                            <?php echo form_hidden('rel_type', (($item->rel_type)? $item->rel_type :'export_warehouse_transfer')); ?>
                            <?=form_hidden('prefix',$prefix)?>    
                            </span>
                            <?php 
                                if($item)
                                {
                                    $number=$item->code;
                                }
                                else
                                {
                                    $number=$code;
                                }
                            ?>
                            <input type="text" name="code" class="form-control" id="code" value="<?=$number ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" readonly>
                          </div>
                    </div>

                    <?php
                    $value= ( isset($item) ? $item->rel_code : ''); 
                    $attrs = array('readonly'=>true);
                    if(!empty('rel_id') || !empty('rel_code'))
                    {
                        $frmattrs['style']="display: none;";
                        

                    }
                    if($item){
                        $frmattrs1['style']="pointer-events: none;";
                    }
                    ?>
                    <?php echo render_input( 'rel_code', _l("sale_code"),$value,'text',$attrs,$frmattrs); ?>

                    <?php $value = (isset($item) ? _d($item->date) : _d(date('Y-m-d')));?>
                    <?php echo render_date_input('date','view_date',$value); ?>
                    
                    <?php
                    $default_name = (isset($item) ? $item->name : _l('export_name'));
                    echo form_hidden('name', _l('export_name'), $default_name);
                    ?>

                    <?php
                    $selected1 = (isset($contract_id) ? $contract_id :'');
                    if($selected1 == '')
                    {
                      $selected1 = (isset($item->rel_id) ? $item->rel_id: '');
                    }
                    ?>

                    <?php 
                    echo render_select('rel_id',$contract,array('id','contract_name'),'Mã hợp đồng',$selected1,'',$frmattrs1);
                    ?>

                    <?php
                    $selected=(isset($item) ? $item->customer_id : '');
                    echo render_select('customer_id',$customers,array('userid','company'),'client',$selected,$frmattrs);
                    ?>
                   
                    <?php
                    $selected=(isset($item) ? $item->receiver_id : '');
                    echo render_select('receiver_id',$receivers,array('staffid','fullname'),'staffs',$selected); 
                    ?>

                    <!-- <?php
                    $selected=(isset($item) ? $warehouse_id : '');
                    echo render_select('warehouse_id',$warehouses,array('warehouseid','warehouse'),'warehouse_name',$selected); 
                    ?> -->

                    <?php 
                    $reason = (isset($item) ? $item->reason : "");
                    echo render_textarea('reason', 'note', $reason, array(), array(), '', 'tinymce');
                    ?>
                </div>

                
                
                
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <!-- Cusstomize from invoice -->
                    <div class="panel-body mtop10" style="border: 1px solid #ccc;">
                    <?php if(!empty($item->rel_id) || !empty($item->rel_code)){ $display='style="display: none;"';  }?>
                        <h4 class="text-center" style="background: #ccc;padding:10px;">SẢN PHẨM</h4>
                        <div class="row"  >
                        <div class="col-md-4" style="display: none;">
                            <?php 
                                if($item->rel_id)
                                    {
                                        $arr=array('style'=>"pointer-events:none;");
                                        echo form_hidden('warehouse_type',$warehouse_type_id);
                                        echo form_hidden('warehouse_name',$warehouse_id);
                                    }
                            ?>
                        </div>
                            <div class="col-md-4">
                                <?php 
                                   echo render_select('categories_name', $categories_a, array('id', 'category'),'Hãng sản phẩm');
                                   ?>
                             </div>
                            <div class="col-md-4" <?=$display?>>
                                <div class="form-group mbot25">
                                    <label for="custom_item_select" class="control-label"><?=_l('item_name')?></label>
                                    <select class="selectpicker no-margin" data-width="100%" id="custom_item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                                        <option value=""></option>

                                        <!-- <?php foreach ($items as $product) { ?>
                                        <option value="<?php echo $product['id']; ?>" data-subtext="">(<?php echo $product['code']; ?>) <?php echo $product['name']; ?></option>
                                        <?php 
                                        } ?> -->

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
                        <div class="table-responsive s_table" style="overflow-x: auto;overflow-y: hidden;">
                            <table class="table items item-export no-mtop">
                                <thead>
                                    <tr>
                                        <th><input type="hidden" id="itemID" value="" /></th>
                                        <th style="min-width: 200px" class="text-left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_name'); ?>"></i> <?php echo _l('item_name'); ?></th>
                                        <th width="" class="text-left"><?php echo _l('item_unit'); ?></th>
                                        <th width="" class="text-left"><?php echo _l('item_quantity'); ?></th>
                                        
                                        <th width="" class="text-left"><?php echo _l('item_price'); ?></th>
                                        <th width="" class="text-left"><?php echo _l('amount'); ?></th>
                                        <th width="" class="text-left"><?php echo _l('tax'); ?></th>

                                        <th width="" class="text-left"><?php echo _l('sub_amount'); ?></th>
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
                                            <?php echo _l('item_price'); ?>
                                        </td>
                                        
                                        <td>
                                            0
                                        </td>
                                        <td>
                                            <?php echo _l('tax'); ?>
                                            <input type="hidden" id="tax" data-taxid="" data-taxrate="" value="" />
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
                                    if(isset($item) && count($item->items) > 0) {
                                        foreach($item->items as $value) {
                                          
                                        ?>
                                    <tr class="sortable item">
                                        <td>
                                            <input type="hidden" name="items[<?php echo $i; ?>][id]" value="<?php echo $value->product_id; ?>">
                                        </td>
                                        <td class="dragger"><?php echo $value->product_name; ?></td>
                                        <td><?php echo $value->unit_name; ?></td>
                                        <?php
                                        $err='';
                                            if($value->quantity>$value->warehouse_type->product_quantity)
                                            {
                                                $err='error';
                                                $style='border: 1px solid red !important';
                                            }
                                        ?>
                                        <td>
                                            
                                        <input style="width: 100px;" data-store="<?php echo $value->product_quantity ?>" class="mainQuantity" type="number" name="items[<?php echo $i; ?>][quantity]" value="<?php echo $value->quantity; ?>">
                                        </td>
                                            
                                        <td><?php echo number_format($value->unit_cost); ?></td>
                                        <td><?php echo number_format($value->sub_total); ?></td>
                                        <td><?php echo number_format($value->tax) ?>
                                            <input type="hidden" id="tax" data-taxrate="<?=$value->tax_rate?>" value="<?=$value->tax_id?>">
                                        </td>
                                        <td><?php echo number_format($value->amount) ?></td>
                                        <td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this); return false;"><i class="fa fa-times"></i></a></td>
                                    </tr>
                                        <?php
                                            $totalPrice += $value->amount;
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
                    <div class="panel-body mtop10" style="border: 1px solid #ccc;">
                           <h4 class="text-center" style="background: #ccc;padding:10px;">LINH KIỆN</h4>
                           <div class="row">
                            <div class="col-md-4" style="display: none;">
                            <?php 
                                if($item->rel_id)
                                    {
                                        $arr=array('style'=>"pointer-events:none;");
                                        echo form_hidden('warehouse_name',$warehouse_id1);
                                    }
                            ?>
                            </div>
                              <div class="col-md-4">
                                 <?php 
                                    echo render_select('categories_name1', $categories_b, array('id', 'category'),'Hãng linh kiện');
                                    ?>
                              </div>
                              <div class="col-md-4" <?=$display?>>
                                 <div class="form-group mbot25">
                                    <label for="custom_item_select" class="control-label"><?=_l('item_name')?></label>
                                    <select class="selectpicker no-margin" data-width="100%" id="custom_item_select1" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                                       <option value=""></option>
                                       <?php foreach ($items as $product) { ?>
                                       <!--  <option value="<?php echo $product['id']; ?>" data-subtext="">(<?php echo $product['code']; ?>) <?php echo $product['name']; ?></option> -->
                                       <?php 
                                          } ?>
                                       <!-- <?php if (has_permission('items', '', 'create')) { ?>
                                          <option data-divider="true"></option>
                                          <option value="newitem" data-content="<span class='text-info'><?php echo _l('new_invoice_item'); ?></span>"></option>
                                          <?php } ?> -->
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="table-responsive s_table" style="overflow-x: auto;overflow-y: hidden;">
                              <table class="table items1 item-export1 no-mtop" border="">
                                 <thead>
                                    <tr>
                                       <th><input type="hidden" id="itemID" value="" /></th>
                                       <th style="min-width: 150px" class="text-left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_name'); ?>"></i> <?php echo _l('item_name'); ?></th>
                                       <th width="" class="text-left"><?php echo _l('item_unit'); ?></th>
                                       <th width="" class="text-left"><?php echo _l('item_quantity'); ?></th>
                                       <th width="" class="text-left"><?php echo _l('item_price'); ?></th>
                                       <th width="" class="text-left"><?php echo _l('amount'); ?></th>
                                       <th width="" class="text-left"><?php echo _l('tax'); ?></th>
                                       <th width="" class="text-left"><?php echo _l('Giá trị'); ?></th>
                                       <th></th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr class="main1" <?=$display?> >
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
                                          <?php echo _l('item_price'); ?>
                                       </td>
                                       <td>
                                          0
                                       </td>
                                       <td>
                                          <?php echo _l('tax'); ?>
                                          <input type="hidden" id="tax" data-taxid="" data-taxrate="" value="" />
                                       </td>
                                       <td>
                                          0
                                       </td>
                                       <td>
                                          <button style="display:none" id="btnAdd1" type="button" onclick="createTrItem1(); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
                                       </td>
                                    </tr>
                                    <?php
                                       $i1=0;
                                       $totalPrice1=0;
                                       
                                       if(isset($item) && count($item->items1) > 0) {
                                           
                                           foreach($item->items1 as $value) {
                                           ?>
                                    <tr class="sortable item1">
                                       <td>
                                          <input type="hidden" name="items1[<?php echo $i1; ?>][id]" value="<?php echo $value->product_id; ?>">
                                       </td>
                                       <td class="dragger"><?php echo $value->product_name; ?></td>
                                       <td><?php echo $value->unit_name; ?></td>
                                       <?php
                                          $err='';
                                              if($value->quantity>$value->warehouse_type->product_quantity)
                                              {
                                                  $err='error';
                                                  $style='border: 1px solid red !important';
                                              }
                                          ?>
                                       <td>
                                          <input style="width: 100px;" data-store="<?php echo $value->product_quantity ?>" class="mainQuantity" type="number" name="items1[<?php echo $i1; ?>][quantity]" value="<?php echo $value->quantity; ?>">
                                       </td>
                                       <td><?php echo number_format($value->unit_cost); ?></td>
                                       <!-- <td><?=form_input('items['.$i.'][taxrate]',$value->taxrate)?><?php echo number_format($value->tax); ?></td> -->
                                       <td><?php echo number_format($value->sub_total); ?></td>
                                       <td><?php echo number_format($value->tax) ?>
                                          <input type="hidden" id="tax" data-taxrate="<?=$value->tax_rate?>" value="<?=$value->tax_id?>">
                                       </td>
                                       <td><?php echo number_format($value->amount) ?></td>
                                       <td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem1(this); return false;"><i class="fa fa-times"></i></a></td>
                                    </tr>
                                    <?php
                                       $totalPrice1 += $value->amount;
                                       $i1++;
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
                                       <td class="total1">
                                          <?php echo $i1 ?>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><span class="bold"><?php echo _l('purchase_total_price'); ?> :</span>
                                       </td>
                                       <td class="totalPrice1">
                                          <?php echo number_format($totalPrice1) ?>
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

<script>

    _validate_form($('.sales-form'),{code:'required',date:'required',customer_id:'required',receiver_id:'required'});
    
    var itemList = <?php echo json_encode($items);?>;

    $('#warehouse_name').change(function(e){
        $('table tr.sortable.item').remove();
        total=0;
        var warehouse_id=$(this).val();
        loadProductsInWarehouse(warehouse_id)
        refreshAll();
        refreshTotal();
    });

     $('#warehouse_name1').change(function(e){
        $('table tr.sortable.item1').remove();
        total=0;
        var warehouse_id=$(this).val();
        loadProductsInWarehouse1(warehouse_id)
        refreshAll1();
        refreshTotal1();
    });

    function loadProductsInWarehouse(warehouse_id){
        var product_id=$('#custom_item_select');
        product_id.find('option:gt(0)').remove();
        product_id.selectpicker('refresh');
        if(product_id.length) {
            $.ajax({
                url : admin_url + 'warehouses/getProductsInWH/' + warehouse_id,
                dataType : 'json',
            })
            .done(function(data){         
                $.each(data, function(key,value){

                    product_id.append('<option data-store="'+value.product_quantity+'" value="' + value.product_id + '">'+'('+ value.code +') '  + value.name + ' ('+ value.product_quantity +')</option>');
                });
                product_id.selectpicker('refresh');
            });
        }
    }

    function loadProductsInWarehouse1(warehouse_id){
        var product_id=$('#custom_item_select1');
        product_id.find('option:gt(0)').remove();
        product_id.selectpicker('refresh');
        if(product_id.length) {
            $.ajax({
                url : admin_url + 'warehouses/getProductsInWH/' + warehouse_id,
                dataType : 'json',
            })
            .done(function(data){          
                $.each(data, function(key,value){
                    
                    product_id.append('<option data-store="'+value.product_quantity+'" value="' + value.product_id + '">'+'('+ value.code +') '  + value.name + '</option>');
                });
                product_id.selectpicker('refresh');
            });
        }
    }


    $('#warehouse_type').change(function(e){
        var warehouse_type = $(e.currentTarget).val();
        if(warehouse_type != '') {
            getWarehouses(warehouse_type); 
        }
    });
    function getWarehouses(warehouse_type){
        var warehouse_id=$('#warehouse_name');
        warehouse_id.find('option:gt(0)').remove();
        warehouse_id.selectpicker('refresh');
        if(warehouse_id.length) {
            $.ajax({
                url : admin_url + 'warehouses/getWarehouses/' + warehouse_type ,
                dataType : 'json',
            })
            .done(function(data){  
                $.each(data, function(key,value){
                    warehouse_id.append('<option value="' + value.warehouseid +'">' + value.warehouse + '</option>');
                });

                warehouse_id.selectpicker('refresh');
            });
        }
    }

    $('#rel_id').change(function(){
        var v = $(this).val();
        var this1  = $(this);
        $('table tr.sortable.item').remove(); 
        $('table tr.sortable.item1').remove();  
        $('#rel_code').val($('#rel_id option:selected').html())   
   
     var total=0;     
      
     refreshAll();  
    refreshAll1();  
   
     refreshTotal();
      refreshTotal1();
    
   
     if(v){
       $.ajax({
                url : admin_url + 'exports/getIteamContact/',
                data: {
                 'id' : v,
                },
                type:'POST',
                dataType : 'json',
            })
            .done(function(data){
             $('#customer_id').selectpicker('val', data.client);
             if(data.items[0]){
                var total = 0;
               addProductQuote('',data.items);
               $.each(data.items, (index,itemFound) => { 
   
                 total += parseFloat(itemFound.tax)+parseFloat(itemFound.price);
   
               });
               $('.totalPrice').text(formatNumber(total))
             
           }
   
   
             if(data.items1[0]){
                var total = 0;
               addProductQuote(1,data.items1);
               $.each(data.items1, (index,itemFound) => { 
                 total += parseFloat(itemFound.tax)+parseFloat(itemFound.price);
               });
               $('.totalPrice1').text(formatNumber(total))
             }
   
             $('input[name="contract_value"]').val(($('.totalPrice1').text().replace(/\,/g,'')*1) + ($('.totalPrice').text().replace(/\,/g,'')*1))
            });
     }
    })

    

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
    var total1 = <?php echo $i1 ?>;
    var totalPrice = <?php echo $totalPrice ?>;
    var uniqueArray = <?php echo $i ?>;
    var uniqueArray1 = <?php echo $i1 ?>;
    var isNew = false;
    var createTrItem = () => {
        if(!isNew) return;
        
        if($('tr.main').find('.error').length > 0){
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
        uniqueArray++;
        var newTr = $('<tr class="sortable item"></tr>');
        
        var td1 = $('<td><input type="hidden" name="items[' + uniqueArray + '][id]" value="" /></td>');
        var td2 = $('<td class="dragger"></td>');
        var td3 = $('<td></td>');
        var td4 = $('<td><input style="width: 100px" class="mainQuantity" type="number" name="items[' + uniqueArray + '][quantity]" value="" /></td>');
        var td5 = $('<td></td>');
        var td6 = $('<td></td>');
        var td7 = $('<td></td>');
        var td8 = $('<td></td>');

        td1.find('input').val($('tr.main').find('td:nth-child(1) > input').val());
        td2.text($('tr.main').find('td:nth-child(2)').text());
        td3.text($('tr.main').find('td:nth-child(3)').text());
        td4.find('input').val($('tr.main').find('td:nth-child(4) > input').val());
        td4.find('input').attr('data-store',$('tr.main').find('td:nth-child(4) > input').attr('data-store'));
        td5.text( $('tr.main').find('td:nth-child(5)').text());
        td6.text( $('tr.main').find('td:nth-child(6)').text());
        var inputTax=$('tr.main').find('td:nth-child(7) > input');
        td7.text( $('tr.main').find('td:nth-child(7)').text());
        td7.append(inputTax);
        td8.text($('tr.main').find('td:nth-child(8)').text());
        newTr.append(td1);
        newTr.append(td2);
        newTr.append(td3);
        newTr.append(td4);
        newTr.append(td5);
        newTr.append(td6);
        newTr.append(td7);
        newTr.append(td8);

        newTr.append('<td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this); return false;"><i class="fa fa-times"></i></a></td');
        $('table.item-export tbody').append(newTr);
        total++;
        totalPrice += $('tr.main').find('td:nth-child(4) > input').val() * $('tr.main').find('td:nth-child(5)').text().replace(/\+/g, ' ');
        

        
        refreshTotal();
        // refreshAll();
    };

    var createTrItem1 = () => {
        if(!isNew) return;
        
        if($('tr.main').find('.error').length > 0){
            return;
        }
        if( $('table.item-export1 tbody tr:gt(0)').find('input[value=' + $('tr.main1').find('td:nth-child(1) > input').val() + ']').length ) {
            $('table.item-export1 tbody tr:gt(0)').find('input[value=' + $('tr.main1').find('td:nth-child(1) > input').val() + ']').parent().find('td:nth-child(2) > input').focus();
            alert_float('danger', "Sản phẩm này đã được thêm, vui lòng lòng kiểm tra lại!");
            return;
        }
        if($('tr.main1').find('td:nth-child(4) > input').val() > $('tr.main1 #select_warehouse option:selected').data('store')) {
            alert_float('danger', 'Kho ' + $('tr.main1 #select_warehouse option:selected').text() + '. Bạn đã nhập ' + $('tr.main1').find('td:nth-child(4) > input').val() + ' là quá số lượng cho phép.');
            return;
        }
        var newTr = $('<tr class="sortable item1"></tr>');
         uniqueArray1++;
        var td1 = $('<td><input type="hidden" name="items1[' + uniqueArray1 + '][id]" value="" /></td>');
        var td2 = $('<td class="dragger"></td>');
        var td3 = $('<td></td>');
        var td4 = $('<td><input style="width: 100px" class="mainQuantity" type="number" name="items1[' + uniqueArray1 + '][quantity]" value="" /></td>');
        var td5 = $('<td></td>');
        var td6 = $('<td></td>');
        var td7 = $('<td></td>');
        var td8 = $('<td></td>');

        td1.find('input').val($('tr.main1').find('td:nth-child(1) > input').val());
        td2.text($('tr.main1').find('td:nth-child(2)').text());
        td3.text($('tr.main1').find('td:nth-child(3)').text());
        td4.find('input').val($('tr.main1').find('td:nth-child(4) > input').val());
        td4.find('input').attr('data-store',$('tr.main1').find('td:nth-child(4) > input').attr('data-store'));
        td5.text( $('tr.main1').find('td:nth-child(5)').text());
        td6.text( $('tr.main1').find('td:nth-child(6)').text());
        var inputTax=$('tr.main1').find('td:nth-child(7) > input');
        td7.text( $('tr.main1').find('td:nth-child(7)').text());
        td7.append(inputTax);
        td8.text($('tr.main1').find('td:nth-child(8)').text());
        newTr.append(td1);
        newTr.append(td2);
        newTr.append(td3);
        newTr.append(td4);
        newTr.append(td5);
        newTr.append(td6);
        newTr.append(td7);
        newTr.append(td8);

        newTr.append('<td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem1(this); return false;"><i class="fa fa-times"></i></a></td');
        $('table.item-export1 tbody').append(newTr);
        total++;
        totalPrice += $('tr.main1').find('td:nth-child(4) > input').val() * $('tr.main1').find('td:nth-child(5)').text().replace(/\+/g, ' ');
       

        
        refreshTotal1();
        // refreshAll();
    };

    $('#categories_name').change(function(e){
       
       
       var category_id=$(this).val();
       loadProductsInCategory(category_id);
     
   });
   
   $('#categories_name1').change(function(e){
       var category_id=$(this).val();
       loadProductsInCategory1(category_id);
   });

   function loadProductsInCategory(category_id){
       var product_id=$('#custom_item_select');
       product_id.find('option:gt(0)').remove();
       product_id.selectpicker('refresh');
       if(product_id.length) {
           $.ajax({
               url : admin_url + 'invoice_items/getProductsInCate/' + category_id,
               dataType : 'json',
           })
           .done(function(data){          
               $.each(data, function(key,value){
                   
                   product_id.append('<option value="' + value.id + '">'+'('+ value.code +') '  + value.name + '</option>');
               });
               product_id.selectpicker('refresh');
           });
       }
   }
   
   function loadProductsInCategory1(category_id){
       var product_id=$('#custom_item_select1');
       product_id.find('option:gt(0)').remove();
       product_id.selectpicker('refresh');
       if(product_id.length) {
           $.ajax({
               url : admin_url + 'invoice_items/getProductsInCate/' + category_id,
               dataType : 'json',
           })
           .done(function(data){   
   
               $.each(data, function(key,value){
                   product_id.append('<option value="' + value.id + '">'+'('+ value.code +') '  + value.name + '</option>');
               });
               product_id.selectpicker('refresh');
           });
       }
   }


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

    var refreshAll1 = () => {
        isNew = false;
        $('#btnAdd1').hide();
        $('#custom_item_select1').val('');
        $('#custom_item_select1').selectpicker('refresh');
        var trBar = $('tr.main1');
        
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

    var deleteTrItem1 = (trItem) => {
        var current = $(trItem).parent().parent();
        totalPrice -= current.find('td:nth-child(4) > input').val() * current.find('td:nth-child(5)').text().replace(/\,/g, '');
        $(trItem).parent().parent().remove();
        total1--;
        refreshTotal1();
    };


    var refreshTotal = () => {
         $('.total').text(formatNumber(total));
        var items = $('table.item-export tbody tr:gt(0)');
        totalPrice = 0;
        $.each(items, (index,value)=>{
            totalPrice += parseFloat($(value).find('td:nth-child(6)').text().replace(/\,/g, ''))+parseFloat($(value).find('td:nth-child(7)').text().replace(/\,/g, ''));
            // * 
        });
        $('.totalPrice').text(formatNumber(totalPrice));
    };

    var refreshTotal1 = () => {
         $('.total1').text(formatNumber(total));
        var items = $('table.item-export1 tbody tr:gt(0)');
        totalPrice = 0;
        $.each(items, (index,value)=>{
            totalPrice += parseFloat($(value).find('td:nth-child(6)').text().replace(/\,/g, ''))+parseFloat($(value).find('td:nth-child(7)').text().replace(/\,/g, ''));
            // * 
        });
        $('.totalPrice1').text(formatNumber(totalPrice));
    };

    $('#custom_item_select').change((e)=>{
        var id = $(e.currentTarget).val();
        var itemFound = findItem(id);
        $('#select_kindof_warehouse').val('');
        $('#select_kindof_warehouse').selectpicker('refresh');
        $('#custom_item_select1').val('');
       $('#custom_item_select1').selectpicker('refresh');
        var warehouse_id=$('#select_warehouse');
        warehouse_id.find('option:gt(0)').remove();
        warehouse_id.selectpicker('refresh');
        if(typeof(itemFound) != 'undefined') {
            var trBar = $('tr.main');
            
            trBar.find('td:first > input').val(itemFound.id);
            trBar.find('td:nth-child(2)').text(itemFound.name+' ('+itemFound.prefix+itemFound.code+')('+ itemFound.category +')');
            trBar.find('td:nth-child(3)').text(itemFound.unit_name);
            trBar.find('td:nth-child(3) > input').val(itemFound.unit);
            trBar.find('td:nth-child(4) > input').val(1);
            trBar.find('td:nth-child(4) > input').attr('data-store',itemFound.product_quantity);
            
            trBar.find('td:nth-child(5)').text(formatNumber(itemFound.price));
            trBar.find('td:nth-child(6)').text(formatNumber(itemFound.price * 1) );
            var taxValue = (parseFloat(itemFound.taxrate)*parseFloat(itemFound.price)/100);
            var inputTax = $('<input type="hidden" id="tax" data-taxrate="'+itemFound.taxrate+'" value="'+itemFound.tax+'" />');
            trBar.find('td:nth-child(7)').text(formatNumber(taxValue));
            trBar.find('td:nth-child(7)').append(inputTax);
            trBar.find('td:nth-child(8)').text(formatNumber(parseFloat(taxValue)+parseFloat(itemFound.price)));
            isNew = true;
            $('#btnAdd').show();
        }
        else {
            isNew = false;
            $('#btnAdd').hide();
        }
    });

    $('#custom_item_select1').change((e)=>{
        var id = $(e.currentTarget).val();
        var itemFound = findItem(id);
        $('#custom_item_select1').val('');
       $('#custom_item_select1').selectpicker('refresh');
        $('#select_kindof_warehouse1').val('');
        $('#select_kindof_warehouse1').selectpicker('refresh');
        var warehouse_id=$('#select_warehouse1');
        warehouse_id.find('option:gt(0)').remove();
        warehouse_id.selectpicker('refresh');
        if(typeof(itemFound) != 'undefined') {
            var trBar = $('tr.main1');
            if(itemFound.product_quantity > 0){
                trBar.find('td:first > input').val(itemFound.id);

                trBar.find('td:nth-child(2)').text(itemFound.name+' ('+itemFound.prefix+itemFound.code+')('+ itemFound.category +')');
                trBar.find('td:nth-child(3)').text(itemFound.unit_name);
                trBar.find('td:nth-child(3) > input').val(itemFound.unit);
                trBar.find('td:nth-child(4) > input').val(1);
                trBar.find('td:nth-child(4) > input').attr('data-store',itemFound.product_quantity);
                
                trBar.find('td:nth-child(5)').text(formatNumber(itemFound.price));
                trBar.find('td:nth-child(6)').text(formatNumber(itemFound.price * 1) );
                var taxValue = (parseFloat(itemFound.taxrate)*parseFloat(itemFound.price)/100);
                var inputTax = $('<input type="hidden" id="tax" data-taxrate="'+itemFound.taxrate+'" value="'+itemFound.tax+'" />');
                trBar.find('td:nth-child(7)').text(formatNumber(taxValue));
                trBar.find('td:nth-child(7)').append(inputTax);
                trBar.find('td:nth-child(8)').text(formatNumber(parseFloat(taxValue)+parseFloat(itemFound.price)));
                isNew = true;
                $('#btnAdd1').show();
            }else{
                alert_float('danger', "Số lượng trong kho không đủ!");
            }
        }
        else {
            isNew = false;
            $('#btnAdd1').hide();
        }
    });
    $('select[id^="select_warehouse"]').on('change', (e)=>{
        if($(e.currentTarget).val() != '') {
            $(e.currentTarget).parents('tr').find('input.mainQuantity').attr('data-store', $(e.currentTarget).find('option:selected').data('store'));
        }
    });
    $(document).on('change', '.mainQuantity', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        let elementToCompare;
          

        if(typeof(currentQuantityInput.attr('data-store')) == 'undefined' )
            elementToCompare = currentQuantityInput.parents('tr').find('input[aria-label!="Search"]:last');
        else
            elementToCompare = currentQuantityInput;
        if(parseInt(currentQuantityInput.val()) > parseInt(elementToCompare.attr('data-store'))) {
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
        
        var Gia = currentQuantityInput.parent().find(' + td');
        var GiaTri = Gia.find(' + td');
        var Thue = GiaTri.find(' + td');
        var Tong = Thue.find(' + td');
        var inputTax=Thue.find('input');        
        GiaTri.text(formatNumber(Gia.text().replace(/\,/g, '') * currentQuantityInput.val()) );
        Thue.text(formatNumber(parseFloat(inputTax.data('taxrate'))/100*parseFloat(GiaTri.text().replace(/\,/g,''))));
        Thue.append(inputTax);
        Tong.text(formatNumber(parseFloat(Thue.text().replace(/\,/g,''))+parseFloat(GiaTri.text().replace(/\,/g,''))));
        if($('.sortable.item').find('.error').length > 0){
            $('.customer-form-submiter').attr('disabled','disabled');
        }else{
            $('.customer-form-submiter').removeAttr('disabled');
        }
        refreshTotal();
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
                url : admin_url + 'warehouses/getWarehouses/' + warehouse_type + '/' + filter_by_product,
                dataType : 'json',
            })
            .done(function(data){          
                $.each(data, function(key,value){
                    var stringSelected = "";
                    if(value.warehouseid == default_value) {
                        stringSelected = ' selected="selected"';
                    }
                    warehouse_id.append('<option data-store="'+value.items[0].product_quantity+'" value="' + value.warehouseid + '"'+stringSelected+'>' + value.warehouse + '(có '+value.items[0].product_quantity+')</option>');
                });
                warehouse_id.selectpicker('refresh');
            });
        }
    }

    function loadWarehouseByID(warehouse_type, filter_by_product,id){
        var warehouse_id=$('#select_warehouse'+id);
        warehouse_id.find('option:gt(0)').remove();
        warehouse_id.selectpicker('refresh');
        if(warehouse_id.length) {
            $.ajax({
                url : admin_url + 'warehouses/getWarehouses/' + warehouse_type + '/' + filter_by_product,
                dataType : 'json',
            }).done(function(data){          
                $.each(data, function(key,value){
                    var stringSelected = "";
                    warehouse_id.append('<option data-store="'+value.items[0].product_quantity+'" value="' + value.warehouseid + '"'+stringSelected+'>' + value.warehouse + '(có '+value.items[0].product_quantity+')</option>');
                });
                warehouse_id.selectpicker('refresh');
            });
        }
    }

    $('.customer-form-submiter').on('click', (e)=>{
        if($('input.error').length) {
            e.preventDefault();
            alert_float('warning','Giá trị không hợp lệ!');    
        }
        // alert($('select[id^="select_warehouse"]').selectpicker('val'));
        // $.each($('select[id^="select_warehouse"]'), function(key,value){
        //     // alert($(value).selectpicker('val'))
        //     if($(value).selectpicker('val')=='')
        //     {
        //         e.preventDefault();
        //         alert_float('warning','Vui lòng chọn kho sản phẩm');  
        //     }
        // });
    });

    function addProductQuote(a,data){
     

        let variablename = 'uniqueArray'+a;

   
        var total = 0;
    
        $.each(data, (index,itemFound) => { 
        var newTr = $('<tr class="sortable item'+a+'"></tr>');
        var td1 = $('<td><input type="hidden" name="items'+a+'[' + window[variablename]+ '][id]" value="" /></td>');
        var td2 = $('<td class="dragger"></td>');
        var td3 = $('<td></td>');
        var td4 = $('<td><input style="width: 100px" class="mainQuantity" type="number" name="items'+a+'[' + window[variablename] + '][quantity]" data-store="'+itemFound.product_quantity+'" value="" /></td>');
        var td5 = $('<td></td>');
        var td6 = $('<td></td>');
        var td7 = $('<td><input type="hidden" id="tax" data-taxid="" data-taxrate="" value="" /></td>');
        var td8 = $('<td></td>');
          td1.find('input').val(itemFound.product_id);
          td2.text(itemFound.product_name+' ('+itemFound.prefix+itemFound.code+')('+ itemFound.category +')');
          td3.text(itemFound.unit_name);

          td4.find('input').val(itemFound.quantity);
          currentQuantityInput = td4.find('input');
          if(typeof(currentQuantityInput.attr('data-store')) == 'undefined' )
            elementToCompare = currentQuantityInput.parents('tr').find('input[aria-label!="Search"]:last');
            else
                elementToCompare = currentQuantityInput;
            if(parseInt(currentQuantityInput.val()) > parseInt(elementToCompare.attr('data-store'))) {
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
          td5.text(formatNumber(itemFound.price * 1));
          td6.text(formatNumber(itemFound.price * 1));
   
          var taxValue = itemFound.tax;
          var inputTax = $('<input type="hidden" id="tax" data-taxrate="'+itemFound.tax_rate+'" value="'+itemFound.tax+'" />');
   
   
           td7.text(formatNumber(itemFound.tax));
           td7.append(inputTax);
           td8.text(formatNumber(parseFloat(taxValue)+parseFloat(itemFound.price)));
   
          newTr.append(td1);
          newTr.append(td2);
          newTr.append(td3);
          newTr.append(td4);
          newTr.append(td5);
          newTr.append(td6);
          newTr.append(td7);
          newTr.append(td8);
         
        newTr.append('<td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem'+a+'(this); return false;"><i class="fa fa-times"></i></a></td');
        
         $('table.item-export'+a+' tbody').append(newTr);
        });
        
        $('.total'+a).text($('<tr class="sortable item'+a+'"></tr>').length)
        
        
        
    }
    
</script>


