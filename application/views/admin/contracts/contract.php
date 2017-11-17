<?php init_head(); ?>
<style type="text/css">
   .tab-pane{
   display: none;
   }
   .tab-pane.active{
   display: block;
   }
   .panel_s .panel-body{
   padding: 10px;
   }
   .point-ev{
      pointer-events: none;
   }
</style>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <?php echo form_open($this->uri->uri_string(),array('id'=>'contract-form')); ?>
         <div class="col-md-12 left-column">
            <div class="panel_s">
               <div class="panel-body">
                  <h4 class="bold no-margin font-medium"><?php echo $title; ?></h4>
                  <hr />
                  <ul class="nav nav-tabs" role="tablist">
                     <li role="presentation" class="active">
                        <a href="#tab_info" aria-controls="tab_info" role="tab" data-toggle="tab">
                        <?php echo _l('Thông tin'); ?>
                        </a>
                     </li>
                     <li role="presentation">
                        <a href="#tab_pro" aria-controls="tab_pro" role="tab" data-toggle="tab">
                        <?php echo _l('Sản phẩm'); ?>
                        </a>
                     </li>
                     <li role="presentation">
                        <a href="#tab_detail" aria-controls="tab_detail" role="tab" data-toggle="tab">
                        <?php echo _l('Chi tiết hợp đồng'); ?>
                        </a>
                     </li>
                  </ul>
                  <div role="tabpanel" class="tab-pane active" id="tab_info">
                     <div class="form-group hidden">
                        <div class="checkbox checkbox-primary no-mtop checkbox-inline">
                           <input type="checkbox" id="trash" name="trash" data-toggle="tooltip" title="<?php echo _l('contract_trash_tooltip'); ?>" <?php if(isset($contract)){if($contract->trash == 1){echo 'checked';}}; ?>>
                           <label for="trash"><?php echo _l('contract_trash'); ?></label>
                        </div>
                        <div class="checkbox checkbox-primary checkbox-inline">
                           <input type="checkbox" name="not_visible_to_client" id="not_visible_to_client" <?php if(isset($contract)){if($contract->not_visible_to_client == 1){echo 'checked';}}; ?>>
                           <label for="not_visible_to_client"><?php echo _l('contract_not_visible_to_client'); ?></label>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="number"><?php echo _l('contract_code'); ?></label>
                        <div class="input-group">
                           <span class="input-group-addon">
                              <?php $prefix =($contract) ? $contract->prefix : get_option('prefix_contract'); ?>
                              <?=$prefix?>
                              <?=form_hidden('prefix',$prefix)?>
                              <!-- <?=form_hidden('rel_id',$contract->rel_id)?>    -->
                           </span>
                           <?php
                              if($contract)
                              {
                                $number=$contract->code;
                              }
                              else
                              {
                                $number=$code;
                              }
                              ?>
                           <input type="text" name="code" class="form-control" id="code" value="<?=$number ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" <?php echo ($item)? 'readonly' : '' ?>>
                        </div>
                     </div>
                     <?php
                        $selected = (isset($contract) ? $contract->client :'');
                        if($selected == '')
                        {
                          $selected = (isset($customer_id) ? $customer_id: '');
                        }
                        ?>
                     <?php $auto_toggle_class = (isset($contract) || isset($do_not_auto_toggle) ? '' : 'auto-toggle'); ?>
                     <?php echo render_select('client',$clients,array('userid','company'),'contract_client_string',$selected,array(),array(),'',$auto_toggle_class); ?>
                     <?php $value = (isset($contract) ? $contract->subject : ''); ?>
                     <?php echo render_input('subject','contract_subject',$value,'text',array('data-toggle'=>'tooltip','title'=>'contract_subject_tooltip')); ?>
                     <?php
                        $selected1 = (isset($contract) ? $contract->rel_id :'');
                        if($selected1 == '')
                        {
                          $selected1 = (isset($quotes->id) ? $quotes->id: '');
                        }
                        ?>
                     <?php echo render_select('rel_id',$quotes,array('id','quote_name'),'Mã báo giá',$selected1,array(),array(),($contract)?'point-ev':''); ?>
                     <div class="form-group">
                        <label for="contract_value"><?php echo _l('contract_value'); ?></label>
                        <div class="input-group" data-toggle="tooltip" title="<?php echo _l('contract_value_tooltip'); ?>">
                           <input type="number" class="form-control" readonly name="contract_value" value="<?php if(isset($contract)){echo $contract->contract_value; }?>">
                           <div class="input-group-addon">
                              <?php echo $base_currency->symbol; ?>
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="incurred"><?php echo _l('Phí phát sinh'); ?></label>
                        <div class="input-group" data-toggle="tooltip" title="<?php echo _l('contract_value_tooltip'); ?>">
                           <!-- <?php if(isset($quote)){echo $quote->total; }?> -->
                           <input type="number" class="form-control" id="contract_incurred" name="contract_incurred" value="0" readonly>
                           <div class="input-group-addon">
                              <?php echo $base_currency->symbol; ?>
                           </div>
                        </div>
                     </div>
                     <?php $selected = $types[0]['id']; ?>
                     <?php if(!$selected){$selected = (isset($contract) ? $contract->contract_type : '');} ?>
                     <?php echo render_select('contract_type',$types,array('id','name'),'contract_type',$selected); ?>
                     <div class="row">
                        <div class="col-md-6">
                           <?php $value = (isset($contract) ? _d($contract->datestart) : _d(date('Y-m-d'))); ?>
                           <?php echo render_date_input('datestart','contract_start_date',$value); ?>
                        </div>
                        <div class="col-md-6">
                           <?php $value = (isset($contract) ? _d($contract->dateend) : ''); ?>
                           <?php echo render_date_input('dateend','contract_end_date',$value); ?>
                        </div>
                     </div>
                     <?php $value = (isset($contract) ? $contract->description : ''); ?>
                     <?php echo render_textarea('description','contract_description',$value,array('rows'=>10)); ?>
                     <?php $rel_id = (isset($contract) ? $contract->id : false); ?>
                     <?php echo render_custom_fields('contracts',$rel_id); ?>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="tab_pro">
                     <div class="panel_s">
                        <div class="panel-body" style="border: 1px solid #ccc;">
                           <h4 class="text-center" style="background: #ccc;padding:10px;">SẢN PHẨM</h4>
                           <div class="row">
                              <div class="col-md-4">
                                 <?php
                                    echo render_select('categories_name', $categories_a, array('id', 'category'),'Hãng sản phẩm');
                                    ?>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group mbot25">
                                    <label for="custom_item_select" class="control-label"><?=_l('item_name')?></label>
                                    <select class="selectpicker no-margin" data-width="100%" id="custom_item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
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
                              <table class="table items item-export no-mtop" border="">
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
                                          <input style="width: 100px" class="mainQuantity" type="number" min="1" value="1"  class="form-control" placeholder="<?php echo _l('item_quantity'); ?>">
                                       </td>
                                       <td>
                                          <input style="width: 100px" class="mainUnitCost" type="text" value="1"  class="form-control" id="unit_cost" onkeyup="formart_num('unit_cost')" placeholder="<?php echo _l('item_price'); ?>">
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
                                    <tr class="sortable item" quantity-warehouse="<?=$value->warehouse_type->product_quantity; ?>" old-quantity="<?=$value->quantity ?>">
                                       <td>
                                          <input type="hidden" name="items[<?php echo $i; ?>][id]" value="<?php echo $value->product_id; ?>">
                                       </td>
                                       <td class="dragger"><?php echo $value->product_name; ?> (<?php echo $value->prefix; ?><?php echo $value->code; ?>)(<?php echo $value->category; ?>)</td>
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
                                          <input style="width: 100px;" class="mainQuantity" type="number" name="items[<?php echo $i; ?>][quantity]" value="<?php echo $value->quantity; ?>">
                                       </td>
                                       <td>
                                          <input style="width: 100px" class="mainUnitCost" type="text" value="<?php echo str_replace(',','.',number_format($value->unit_cost)); ?>" onkeyup="formart_num('unit_cost<?php echo $i; ?>')" id="unit_cost<?php echo $i; ?>"  class="form-control" placeholder="<?php echo _l('item_price');  ?>" name="items[<?php echo $i; ?>][unit_cost] ">
                                       </td>
                                       <!-- <td><?=form_input('items['.$i.'][taxrate]',$value->taxrate)?><?php echo number_format($value->tax); ?></td> -->
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
                                          <?php echo number_format($totalPrice) ?>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                        <div class="panel-body mtop10" style="border: 1px solid #ccc;">
                           <h4 class="text-center" style="background: #ccc;padding:10px;">LINH KIỆN</h4>
                           <div class="row">
                              <div class="col-md-4">
                                 <?php
                                    echo render_select('categories_name1', $categories_b, array('id', 'category'),'Hãng linh kiện');
                                    ?>
                              </div>
                              <div class="col-md-4">
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
                                    <tr class="main1">
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
                                          <input style="width: 100px" class="mainUnitCost" type="text" value="1"  class="form-control" id="unit_costl1" onkeyup="formart_num('unit_costl1')" placeholder="<?php echo _l('item_price'); ?>">
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
                                    <tr class="sortable item1" quantity-warehouse="<?=$value->warehouse_type->product_quantity; ?>" old-quantity="<?=$value->quantity ?>">
                                       <td>
                                          <input type="hidden" name="items1[<?php echo $i1; ?>][id]" value="<?php echo $value->product_id; ?>">
                                       </td>
                                       <td class="dragger"><?php echo $value->product_name; ?> (<?php echo $value->prefix; ?><?php echo $value->code; ?>)(<?php echo $value->category; ?>)</td>
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
                                          <input style="width: 100px;" class="mainQuantity" type="number" name="items1[<?php echo $i1; ?>][quantity]" value="<?php echo $value->quantity; ?>">
                                       </td>
                                       <td>
                                          <input style="width: 100px" name="items1[<?php echo $i1; ?>][unit_cost] " class="mainUnitCost" type="text" value="<?php echo str_replace(',','.',number_format($value->unit_cost)); ?>" onkeyup="formart_num('unit_cost1<?php echo $i1; ?>')" id="unit_cost1<?php echo $i1; ?>"  class="form-control" placeholder="<?php echo _l('item_price'); ?>">
                                       </td>
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
                        <div class="col-xs-12" style="padding:0px;">
                        <div class="form-group all" style="display: inline-block; width: 100%">
                           <div class="title-hf" style="margin: 10px 0px;">Chi phí phát sinh thêm
                              <button type="button" class="btn btn-primary btn-xs" id="addGift"><i class="fa fa-plus"></i>
                              </button>
                           </div>
                           <div id="ex-gift" class="col-md-12" style="padding:0px;">
                              <?php $k = 0; ?>
                              <?php if(!$item){ ?>
                              <div class="col-md-12" style="    margin-bottom: 10px; padding: 0px;">
                                 <div class="col-xs-12 col-md-6" style="padding-left: 0px;">
                                    <label>Tên phát sinh </label>
                                    <input type="text" name="incurred[<?php echo $k ?>][name_incurred]" style="width: 100%; ">
                                 </div>
                                 <div class="col-xs-12 col-md-6">
                                    <label>Phí phát sinh </label>
                                    <input type="text" class="pay_incurred_class" onkeyup="formart_num('pay_incurred<?php echo $k ?>')" value="0" id="pay_incurred<?php echo $k ?>" name="incurred[<?php echo $k ?>][pay_incurred]" style="width: 100%">
                                 </div>
                              </div>

                              <?php }else{ ?>
                              <?php foreach ($item->items2 as $key => $value) { ?>
                              <div class="col-md-12" style="    margin-bottom: 10px; padding: 0px;">
                                 <div class="col-xs-12 col-md-6" style="padding-left: 0px;">
                                    <label>Tên phát sinh </label>
                                    <input type="hidden" name="incurred[<?php echo $k ?>][id]" value="<?php echo $value->tblincurred_id ?>">
                                    <input type="text" value="<?php echo $value->tblincurred_contract_name ?>" name="incurred[<?php echo $k ?>][name_incurred]" style="width: 100%; ">
                                 </div>
                                 <div class="col-xs-11 col-md-5">
                                    <label>Phí phát sinh </label>
                                    <input type="text" class="pay_incurred_class" onkeyup="formart_num('pay_incurred<?php echo $k ?>')" id="pay_incurred<?php echo $k ?>" value="<?php echo str_replace(',','.',number_format($value->tblincurred_contract_price)) ?>" name="incurred[<?php echo $k ?>][pay_incurred]" style="width: 100%">
                                 </div>
                                 <div class="col-xs-1 col-md-1">
                                    <div>&nbsp</div>
                                    <a href="#" class="btn btn-danger pull-right delete_in_item" style="margin-top: 7px;"><i class="fa fa-times"></i></a>
                                 </div>
                              </div>
                              <?php $k++; } ?>
                              <?php  } ?>
                           </div>
                        </div>
                     </div>
                     <div class="clearfix"></div>
                        <!-- End Customize from invoice -->
                     </div>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="tab_detail">
                     <?php if(isset($contract)) { ?>
                     <div class="col-md-12 right-column" >
                        <div class="panel_s">
                           <div class="panel-body">
                              <h4 class="bold no-margin font-medium"><?php echo _l('contract_edit_overview'); ?></h4>
                              <hr />
                              <?php if($contract->trash > 0){
                                 echo '<div class="ribbon default"><span>'._l('contract_trash').'</span></div>';
                                 } ?>
                              <ul class="nav nav-tabs" role="tablist">
                                 <li role="presentation" class="active">
                                    <a href="#tab_content" aria-controls="tab_content" role="tab" data-toggle="tab">
                                    <?php echo _l('contract_content'); ?>
                                    </a>
                                 </li>
                                 <li role="presentation">
                                    <a href="#tab_attachments" aria-controls="tab_attachments" role="tab" data-toggle="tab">
                                    <?php echo _l('contract_attachments'); ?>
                                    </a>
                                 </li>
                                 <li role="presentation">
                                    <a href="#tab_renewals" aria-controls="tab_renewals" role="tab" data-toggle="tab">
                                    <?php echo _l('no_contract_renewals_history_heading'); ?>
                                    </a>
                                 </li>
                                 <!-- <li role="presentation">
                                    <a href="#tab_tasks" aria-controls="tab_tasks" role="tab" data-toggle="tab">
                                      <?php echo _l('tasks'); ?>
                                    </a>
                                    </li> -->
                                 <li role="presentation">
                                    <a href="#" onclick="contract_full_view(); return false;" data-toggle="tooltip" data-title="<?php echo _l('toggle_full_view'); ?>" class="toggle_view">
                                    <i class="fa fa-expand"></i></a>
                                 </li>
                              </ul>
                              <div class="tab-content">
                                 <div role="tabpanel" class="tab-pane active" id="tab_content">
                                    <div class="row">
                                       <div class="col-md-12 text-right " style="padding-bottom: 10px;">
                                          <div style="display: inline-block;">
                                             <label>In có thuế</label>
                                             <input type="radio" checked name="typecontract" value="yes" style="position: relative; top:3px;">

                                             &nbsp|&nbsp

                                             <label>In không thuế</label>
                                             <input type="radio" name="typecontract" value="no" style="position: relative; top:3px;">

                                          </div>
                                       </div>
                                       <div class="col-md-12 text-right _buttons">

                                          <a href="<?php echo admin_url('contracts/pdf/'.$contract->id.'?print=true&'); ?>" target="_blank" class="btn btn-default mright5 btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('print'); ?>" data-placement="bottom"><i class="fa fa-print"></i></a>
                                          <a href="<?php echo admin_url('contracts/word/'.$item->id); ?>" class="btn btn-default mright5 btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('Xem Word'); ?>" data-placement="bottom"><i class="fa fa-file-word-o"></i></a>
                                          <a href="<?php echo admin_url('contracts/pdf/'.$contract->id); ?>" class="btn btn-default mright5 btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('view_pdf'); ?>" data-placement="bottom"><i class="fa fa-file-pdf-o"></i></a>
                                          <a href="#" class="btn btn-default mright5" data-target="#contract_send_to_client_modal" data-toggle="modal"><span class="btn-with-tooltip" data-toggle="tooltip" data-title="<?php echo _l('contract_send_to_email'); ?>" data-placement="bottom"><i class="fa fa-envelope"></i></span></a>
                                       </div>
                                       <div class="col-md-12">
                                          <?php if(isset($contract_merge_fields)){ ?>
                                          <p class="bold mtop10"><a href="#" onclick="slideToggle('.avilable_merge_fields'); return false;"><?php echo _l('available_merge_fields'); ?></a></p>
                                          <div class=" avilable_merge_fields mtop15 hide">
                                             <ul class="list-group">
                                                <?php
                                                   foreach($contract_merge_fields as $field){
                                                    foreach($field as $f){
                                                     echo '<li class="list-group-item"><b>'.$f['name'].'</b>  <a href="#" class="pull-right" onclick="insert_merge_field(this); return false">'.$f['key'].'</a></li>';
                                                   }
                                                   } ?>
                                             </ul>
                                          </div>
                                          <?php } ?>
                                       </div>
                                    </div>
                                    <hr />
                                    <div class="editable tc-content" style="border:1px solid #f0f0f0;">
                                       <?php if(empty($contract->content)){
                                          echo '<span class="text-danger text-uppercase mtop15 editor-add-content-notice"> ' . _l('click_to_add_content') . '</span>';
                                          } else {
                                          echo $contract->content;
                                          }
                                          ?>
                                    </div>
                                 </div>

                                 <div role="tabpanel" class="tab-pane" id="tab_attachments">
                                    <?php echo form_open(admin_url('contracts/add_contract_attachment/'.$contract->id),array('id'=>'contract-attachments-form','class'=>'dropzone')); ?>
                                    <?php echo form_close(); ?>
                                    <div class="text-right mtop15">
                                       <div id="dropbox-chooser"></div>
                                    </div>
                                    <div id="contract_attachments" class="mtop30">
                                       <?php
                                          $data = '<div class="row">';
                                          foreach($contract->attachments as $attachment) {
                                            $href_url = site_url('download/file/contract/'.$attachment['id']);
                                            if(!empty($attachment['external'])){
                                              $href_url = $attachment['external_link'];
                                            }
                                            $data .= '<div class="display-block contract-attachment-wrapper" style="padding:0px;">';
                                            $data .= '<div class="col-md-10">';
                                            $data .= '<div class="pull-left"><i class="'.get_mime_class($attachment['filetype']).'"></i></div>';
                                            $data .= '<a href="'.$href_url.'">'.$attachment['file_name'].'</a>';
                                            $data .= '<p class="text-muted">'.$attachment["filetype"].'</p>';
                                            $data .= '</div>';
                                            $data .= '<div class="col-md-2 text-right">';
                                            if($attachment['staffid'] == get_staff_user_id() || is_admin()){
                                             $data .= '<a href="#" class="text-danger" onclick="delete_contract_attachment(this,'.$attachment['id'].'); return false;"><i class="fa fa fa-times"></i></a>';
                                           }
                                           $data .= '</div>';
                                           $data .= '<div class="clearfix"></div><hr/>';
                                           $data .= '</div>';
                                          }
                                          $data .= '</div>';
                                          echo $data;
                                          ?>
                                    </div>
                                 </div>
                                 <div role="tabpanel" class="tab-pane" id="tab_renewals">
                                    <?php if(has_permission('contracts', '', 'create') || has_permission('contracts', '', 'edit')){ ?>
                                    <div class="_buttons">
                                       <a href="#" class="btn btn-default" data-toggle="modal" data-target="#renew_contract_modal">
                                       <i class="fa fa-refresh"></i> <?php echo _l('contract_renew_heading'); ?>
                                       </a>
                                    </div>
                                    <hr />
                                    <?php } ?>
                                    <div class="clearfix"></div>
                                    <?php
                                       if(count($contract_renewal_history) == 0){
                                        echo _l('no_contract_renewals_found');
                                       }
                                       foreach($contract_renewal_history as $renewal){ ?>
                                    <div class="display-block">
                                       <div class="media-body">
                                          <div class="display-block">
                                             <?php
                                                echo _l('contract_renewed_by',$renewal['renewed_by']);
                                                ?>
                                             <?php if($renewal['renewed_by_staff_id'] == get_staff_user_id() || is_admin()){ ?>
                                             <a href="<?php echo admin_url('contracts/delete_renewal/'.$renewal['id'] . '/'.$renewal['contractid']); ?>" class="pull-right _delete text-danger"><i class="fa fa-remove"></i></a>
                                             <br />
                                             <?php } ?>
                                             <small class="text-muted"><?php echo _dt($renewal['date_renewed']); ?></small>
                                             <hr />
                                             <span class="text-success bold" data-toggle="tooltip" title="<?php echo _l('contract_renewal_old_start_date',_d($renewal['old_start_date'])); ?>">
                                             <?php echo _l('contract_renewal_new_start_date',_d($renewal['new_start_date'])); ?>
                                             </span>
                                             <br />
                                             <?php if(is_date($renewal['new_end_date'])){
                                                $tooltip = '';
                                                if(is_date($renewal['old_end_date'])){
                                                 $tooltip = _l('contract_renewal_old_end_date',_d($renewal['old_end_date']));
                                                }
                                                ?>
                                             <span class="text-success bold" data-toggle="tooltip" title="<?php echo $tooltip; ?>">
                                             <?php echo _l('contract_renewal_new_end_date',_d($renewal['new_end_date'])); ?>
                                             </span>
                                             <br/>
                                             <?php } ?>
                                             <?php if($renewal['new_value'] > 0){
                                                $contract_renewal_value_tooltip = '';
                                                if($renewal['old_value'] > 0){
                                                 $contract_renewal_value_tooltip = ' data-toggle="tooltip" data-title="'._l('contract_renewal_old_value',_format_number($renewal['old_value'])).'"';
                                                } ?>
                                             <span class="text-success bold"<?php echo $contract_renewal_value_tooltip; ?>>
                                             <?php echo _l('contract_renewal_new_value',_format_number($renewal['new_value'])); ?>
                                             </span>
                                             <br />
                                             <?php } ?>
                                          </div>
                                       </div>
                                       <hr />
                                    </div>
                                    <?php } ?>
                                 </div>
                                 <!-- <div role="tabpanel" class="tab-pane" id="tab_tasks">
                                    <?php //init_relation_tasks_table(array('data-new-rel-id'=>$contract->id,'data-new-rel-type'=>'contract')); ?>
                                    </div> -->
                              </div>
                           </div>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
                  <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
               </div>
            </div>
         </div>
         <?php echo form_close(); ?>

      </div>
   </div>
</div>
<?php init_tail(); ?>
<?php if(isset($contract)){ ?>
<!-- init table tasks -->
<script>
   init_rel_tasks_table(<?php echo $contract->id; ?>,'contract');
   var contract_id = '<?php echo $contract->id; ?>';

</script>
<?php $this->load->view('admin/contracts/send_to_client'); ?>
<?php $this->load->view('admin/contracts/renew_contract'); ?>
<?php } ?>
<script>
   var itemList = <?php echo json_encode($items);?>;
      var total_inclu = <?php echo ($item->incurred) ? $item->incurred : 0 ; ?>;
   Dropzone.autoDiscover = false;
   if($('#contract-attachments-form').length > 0){
     var contractAttachmentsForm = new Dropzone("#contract-attachments-form", {
      addRemoveLinks: true,
      dictDefaultMessage:drop_files_here_to_upload,
      dictFallbackMessage:browser_not_support_drag_and_drop,
      dictRemoveFile:remove_file,
      dictFileTooBig: file_exceds_maxfile_size_in_form,
      dictMaxFilesExceeded:you_can_not_upload_any_more_files,
      maxFilesize: max_php_ini_upload_size.replace(/\D/g, ''),
      success:function(file){
       if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
        var location = window.location.href;
        window.location.href= location.split('?')[0]+'?tab=tab_attachments';
      }
    },
    acceptedFiles:allowed_files,
    error:function(file,response){
     alert_float('danger',response);
   },
   });
   }
   $(function(){

    if(typeof(Dropbox) != 'undefined' && $('#dropbox-chooser').length > 0 ){
     document.getElementById("dropbox-chooser").appendChild(Dropbox.createChooseButton({
       success: function(files) {
        $.post(admin_url+'contracts/add_external_attachment',{files:files,contract_id:contract_id,external:'dropbox'}).done(function(){
         var location = window.location.href;
         window.location.href= location.split('?')[0]+'?tab=tab_attachments';
       });
      },
      linkType: "preview",
      extensions: allowed_files.split(','),
    }));
   }
   $('#contract_incurred').val(total_inclu);
   _validate_form($('#contract-form'),{client:'required',datestart:'required',subject:'required'});
   _validate_form($('#renew-contract-form'),{new_start_date:'required'});

   tinymce.init({
     selector: 'div.editable',
     inline: true,
     theme: 'modern',
     skin: 'perfex',
     relative_urls: false,
     remove_script_host: false,
     inline_styles : true,
     verify_html : false,
     cleanup : false,
     valid_elements : '+*[*]',
     valid_children : "+body[style], +style[type]",
     apply_source_formatting : false,
     file_browser_callback: elFinderBrowser,
     table_class_list: [{
      title: 'Flat',
      value: 'table'
    }, {
      title: 'Table Bordered',
      value: 'table table-bordered'
    }],
    table_default_styles: {
      width: '100%'
    },
    setup: function(ed) {
      ed.on('init', function() {
       this.getDoc().body.style.fontSize = '14px';
     });
    },
    removed_menuitems: 'newdocument',
    fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
    plugins: [
    'advlist pagebreak autolink autoresize lists link image charmap hr anchor',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'media nonbreaking save table contextmenu directionality',
    'paste textcolor colorpicker textpattern'
    ],
    autoresize_bottom_margin: 50,
    pagebreak_separator: '<p pagebreak="true"></p>',
    toolbar1: 'save_button fontselect fontsizeselect insertfile | styleselect',
    toolbar2:'bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
    toolbar3: 'media image | forecolor backcolor link ',
    setup: function(editor) {
      editor.addButton('save_button', {
       text: contract_save,
       icon: false,
       id: 'inline-editor-save-btn',
       onclick: function() {
        var data = {};
        data.contract_id = contract_id;
        data.content = editor.getContent();
        $.post(admin_url + 'contracts/save_contract_data', data).done(function(response) {
         response = JSON.parse(response);
         if (response.success == true) {
          alert_float('success', response.message);
        }
      }).fail(function(error){
       var response = JSON.parse(error.responseText);
       alert_float('danger', response.message);
     });
    }
   });
    },
   });
   });

   $('input[name="typecontract"]').change(function(){
      var type = $(this).val();
      if($(this).val() == 'yes'){
          $.each($('._buttons a'), function (key, val) {
            if($(val).attr('href') != '#'){
               $(val).attr('href',$(val).attr('href'));
            }
         });
      }else{
         $.each($('._buttons a'), function (key, val) {
            if($(val).attr('href') != '#'){
               $(val).attr('href',$(val).attr('href')+'?type='+type);
            }
         });
      }
   })

   function delete_contract_attachment(wrapper,id){
    var r = confirm(confirm_action_prompt);
    if (r == false) {
     return false;
   } else {
     $.get(admin_url + 'contracts/delete_contract_attachment/'+id,function(response){
      if(response.success == true){
       $(wrapper).parents('.contract-attachment-wrapper').remove();
     } else {
       alert_float('danger',response.message);
     }
   },'json');
   }
   }
   function insert_merge_field(field){
   var key = $(field).text();
   tinymce.activeEditor.execCommand('mceInsertContent', false, key);
   }
   function contract_full_view(){
   $('.left-column').toggleClass('hide');
   $('.right-column').toggleClass('col-md-6');
   $('.right-column').toggleClass('col-md-12');
   }

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
    var total2 = <?php echo $k ?>;
    var uniqueArray1 = <?php echo $i1 ?>;
    var isNew = false;
    var createTrItem = () => {
      alert('a');
        if(!isNew) return;
        // if(!$('div #warehouse_name option:selected').length || $('div #warehouse_name option:selected').val() == '') {
        //     alert_float('danger', "Vui lòng chọn kho chứa sản phẩm!");
        //     return;
        // }
        if( $('table.item-export tbody tr:gt(0)').find('input[value=' + $('tr.main').find('td:nth-child(1) > input').val() + ']').length ) {
            $('table.item-export tbody tr:gt(0)').find('input[value=' + $('tr.main').find('td:nth-child(1) > input').val() + ']').parent().find('td:nth-child(2) > input').focus();
            alert_float('danger', "Sản phẩm này đã được thêm, vui lòng lòng kiểm tra lại!");
            return;
        }
       let quantityWarehouse  = parseInt($('#btnAdd').attr('quantity-warehouse'));
       let currentQuantity    = parseInt($('tr.main').find('td:nth-child(4) > input').val());
       if (currentQuantity > quantityWarehouse ) {
          alert_float('danger', "Số lượng sản phẩm này lớn hơn số lượng trong kho, vui lòng nhập lại!");
          return;
       }

        // if($('tr.main').find('td:nth-child(4) > input').val() > $('tr.main #select_warehouse option:selected').data('store')) {
        //     alert_float('danger', 'Kho ' + $('tr.main #select_warehouse option:selected').text() + '. Bạn đã nhập ' + $('tr.main').find('td:nth-child(4) > input').val() + ' là quá số lượng cho phép.');
        //     return;
        // }
        uniqueArray++;
        var newTr = $('<tr class="sortable item" quantity-warehouse="'+quantityWarehouse+'" old-quantity="'+$('tr.main').find('td:nth-child(4) > input').val()+'"></tr>');

        var td1 = $('<td><input type="hidden" name="items[' + uniqueArray + '][id]" value="" /></td>');
        var td2 = $('<td class="dragger"></td>');
        var td3 = $('<td></td>');
        var td4 = $('<td><input style="width: 100px" class="mainQuantity" type="number" name="items[' + uniqueArray + '][quantity]" value="" /></td>');
        var td5 = $('<td><input style="width: 100px;" class="mainUnitCost" onkeyup="formart_num(\'unit_cost' + uniqueArray + '\')" type="text" id="unit_cost' + uniqueArray + '" name="items[' + uniqueArray + '][unit_cost]" value=""></td>');
        var td6 = $('<td></td>');
        var td7 = $('<td><input type="hidden" id="tax" data-taxid="" data-taxrate="" value="" /></td>');
        var td8 = $('<td></td>');

        td1.find('input').val($('tr.main').find('td:nth-child(1) > input').val());
        td2.text($('tr.main').find('td:nth-child(2)').text());
        td3.text($('tr.main').find('td:nth-child(3)').text());
        td4.find('input').val($('tr.main').find('td:nth-child(4) > input').val());

        td5.find('input').val($('tr.main').find('td:nth-child(5) > input').val().replace(/\,/g,'.'));
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
        // if(!$('div #warehouse_name1 option:selected').length || $('div #warehouse_name1 option:selected').val() == '') {
        //     alert_float('danger', "Vui lòng chọn kho chứa sản phẩm!");
        //     return;
        // }
        if( $('table.item-export1 tbody tr:gt(0)').find('input[value=' + $('tr.main1').find('td:nth-child(1) > input').val() + ']').length ) {
            $('table.item-export1 tbody tr:gt(0)').find('input[value=' + $('tr.main1').find('td:nth-child(1) > input').val() + ']').parent().find('td:nth-child(2) > input').focus();
            alert_float('danger', "Sản phẩm này đã được thêm, vui lòng lòng kiểm tra lại!");
            return;
        }
        let quantityWarehouse  = parseInt($('#btnAdd1').attr('quantity-warehouse'));
        let currentQuantity    = parseInt($('tr.main1').find('td:nth-child(4) > input').val());
        if (currentQuantity > quantityWarehouse ) {
           alert_float('danger', "Số lượng sản phẩm này lớn hơn số lượng trong kho, vui lòng nhập lại!");
           return;
        }

        // if($('tr.main1').find('td:nth-child(4) > input').val() > $('tr.main #select_warehouse option:selected').data('store')) {
        //     alert_float('danger', 'Kho ' + $('tr.main #select_warehouse1 option:selected').text() + '. Bạn đã nhập ' + $('tr.main1').find('td:nth-child(4) > input').val() + ' là quá số lượng cho phép.');
        //     return;
        // }
       uniqueArray1++;
        var newTr = $('<tr class="sortable item1" quantity-warehouse="'+quantityWarehouse+'" old-quantity="'+$('tr.main1').find('td:nth-child(4) > input').val()+'"></tr>');

        var td1 = $('<td><input type="hidden" name="items1[' + uniqueArray1 + '][id]" value="" /></td>');
        var td2 = $('<td class="dragger"></td>');
        var td3 = $('<td></td>');
        var td4 = $('<td><input style="width: 100px" class="mainQuantity" type="number" name="items1[' + uniqueArray1 + '][quantity]" value="" /></td>');
        var td5 = $('<td><input style="width: 100px;" class="mainUnitCost" onkeyup="formart_num(\'unit_costl1' + uniqueArray1 + '\')" type="text" id="unit_costl1' + uniqueArray1 + '" name="items1[' + uniqueArray1 + '][unit_cost]" value=""></td>');
        var td6 = $('<td></td>');
        var td7 = $('<td><input type="hidden" id="tax" data-taxid="" data-taxrate="" value="" /></td>');
        var td8 = $('<td></td>');

        td1.find('input').val($('tr.main1').find('td:nth-child(1) > input').val());
        td2.text($('tr.main1').find('td:nth-child(2)').text());
        td3.text($('tr.main1').find('td:nth-child(3)').text());
        td4.find('input').val($('tr.main1').find('td:nth-child(4) > input').val());

        td5.find('input').val($('tr.main1').find('td:nth-child(5) > input').val().replace(/\,/g,'.'));
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
        total1++;
        totalPrice += $('tr.main1').find('td:nth-child(4) > input').val() * $('tr.main1').find('td:nth-child(5)').text().replace(/\+/g, ' ');

        refreshTotal1();
        // refreshAll();

    };
    var refreshAll = () => {
        isNew = false;
        $('#btnAdd').hide();
        $('#custom_item_select').val('');
        $('#custom_item_select').selectpicker('refresh');
        var trBar = $('tr.main');

        trBar.find('td:first > input').val("");
        // trBar.find('td:nth-child(1) > input').val('');
        trBar.find('td:nth-child(2)').text("<?=_l('item_name')?>");
        trBar.find('td:nth-child(3)').text("<?=_l('item_unit')?>");
        trBar.find('td:nth-child(4) > input').val('1');
        trBar.find('td:nth-child(5)').text("<?=_l('item_price')?>");
        trBar.find('td:nth-child(6)').text(0);
        trBar.find('td:nth-child(7)').text("<?=_l('tax')?>");
        trBar.find('td:nth-child(8)').text(0);

        $('input[name="contract_value"]').val(($('.totalPrice1').html().replace(/\,/g, '')*1) + ($('.totalPrice').html().replace(/\,/g, '')*1));
    };

     var refreshAll1 = () => {
        isNew = false;
        $('#btnAdd1').hide();
        $('#custom_item_select1').val('');
        $('#custom_item_select1').selectpicker('refresh');
        var trBar = $('tr.main1');

        trBar.find('td:first > input').val("");
        // trBar.find('td:nth-child(1) > input').val('');
        trBar.find('td:nth-child(2)').text("<?=_l('item_name')?>");
        trBar.find('td:nth-child(3)').text("<?=_l('item_unit')?>");
        trBar.find('td:nth-child(4) > input').val('1');
        trBar.find('td:nth-child(5)').text("<?=_l('item_price')?>");
        trBar.find('td:nth-child(6)').text(0);
        trBar.find('td:nth-child(7)').text("<?=_l('tax')?>");
        trBar.find('td:nth-child(8)').text(0);
         $('input[name="contract_value"]').val(($('.totalPrice1').html().replace(/\,/g, '')*1) + ($('.totalPrice').html().replace(/\,/g, '')*1));
    };

     var refreshAll = () => {
       isNew = false;
       $('#btnAdd').hide();
       $('#custom_item_select').val('');
       $('#custom_item_select').selectpicker('refresh');
       var trBar = $('tr.main');

       trBar.find('td:first > input').val("");
       // trBar.find('td:nth-child(1) > input').val('');
       trBar.find('td:nth-child(2)').text("<?=_l('item_name')?>");
       trBar.find('td:nth-child(3)').text("<?=_l('item_unit')?>");
       trBar.find('td:nth-child(4) > input').val('1');
       trBar.find('td:nth-child(5) > input').val("1");
       trBar.find('td:nth-child(6)').text(0);
       trBar.find('td:nth-child(7)').text("<?=_l('tax')?>");
       trBar.find('td:nth-child(8)').text(0);

       $('input[name="contract_value"]').val(($('.totalPrice1').html().replace(/\,/g, '')*1) + ($('.totalPrice').html().replace(/\,/g, '')*1));
   };

    var refreshAll1 = () => {
       isNew = false;
       $('#btnAdd1').hide();
       $('#custom_item_select1').val('');
       $('#custom_item_select1').selectpicker('refresh');
       var trBar = $('tr.main1');

       trBar.find('td:first > input').val("");
       // trBar.find('td:nth-child(1) > input').val('');
       trBar.find('td:nth-child(2)').text("<?=_l('item_name')?>");
       trBar.find('td:nth-child(3)').text("<?=_l('item_unit')?>");
       trBar.find('td:nth-child(4) > input').val('1');
       trBar.find('td:nth-child(5) > input').val("1");
       trBar.find('td:nth-child(6)').text(0);
       trBar.find('td:nth-child(7)').text("<?=_l('tax')?>");
       trBar.find('td:nth-child(8)').text(0);
        $('input[name="contract_value"]').val(($('.totalPrice1').html().replace(/\,/g, '')*1) + ($('.totalPrice').html().replace(/\,/g, '')*1));
   };

   var deleteTrItem = (trItem) => {
       var current = $(trItem).parent().parent();
       totalPrice -= current.find('td:nth-child(4) > input').val() * current.find('td:nth-child(5) > input').val().replace(/\./g, '');
       $(trItem).parent().parent().remove();
       total--;

       refreshTotal();
   };

   var deleteTrItem1 = (trItem) => {
       var current = $(trItem).parent().parent();
       totalPrice -= current.find('td:nth-child(4) > input').val() * current.find('td:nth-child(5) > input').val().replace(/\./g, '');
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
      $('input[name="contract_value"]').val(($('.totalPrice1').html().replace(/\,/g, '')*1) + ($('.totalPrice').html().replace(/\,/g, '')*1));
   };

   var refreshTotal1 = () => {
       $('.total1').text(formatNumber(total1));
       var items = $('table.item-export1 tbody tr:gt(0)');
       totalPrice = 0;
       $.each(items, (index,value)=>{
           totalPrice += parseFloat($(value).find('td:nth-child(6)').text().replace(/\,/g, ''))+parseFloat($(value).find('td:nth-child(7)').text().replace(/\,/g, ''));
           // *
       });
       $('.totalPrice1').text(formatNumber(totalPrice));
      $('input[name="contract_value"]').val(($('.totalPrice1').html().replace(/\,/g, '')*1) + ($('.totalPrice').html().replace(/\,/g, '')*1));
   };


    $('#categories_name').change(function(e){


       var category_id=$(this).val();
       loadProductsInCategory(category_id);

   });

   $('#categories_name1').change(function(e){
       var category_id=$(this).val();
       loadProductsInCategory1(category_id);
   });

   $('#custom_item_select').change((e)=>{
       var id = $(e.currentTarget).val();
       var itemFound = findItem(id);
       $('#custom_item_select').val('');
       $('#custom_item_select').selectpicker('refresh');
       // $('#select_kindof_warehouse').val('');
       // $('#select_kindof_warehouse').selectpicker('refresh');
       // var warehouse_id=$('#select_warehouse');
       // warehouse_id.find('option:gt(0)').remove();
       // warehouse_id.selectpicker('refresh');

       if(typeof(itemFound) != 'undefined') {
           var trBar = $('tr.main');
           trBar.find('td:first > input').val(itemFound.id);
           trBar.find('td:nth-child(2)').text(itemFound.name+' ('+itemFound.prefix+itemFound.code+')('+ itemFound.category +')');
           trBar.find('td:nth-child(3)').text(itemFound.unit_name);
           trBar.find('td:nth-child(3) > input').val(itemFound.unit);
           trBar.find('td:nth-child(4) > input').val(1);
           trBar.find('td:nth-child(5) > input').val(formatNumber(itemFound.price).replace(/\,/g,'.'));
           trBar.find('td:nth-child(6)').text(formatNumber(itemFound.price * 1) );
           var taxValue = (parseFloat(itemFound.rate)*parseFloat(itemFound.price)/100);
           var inputTax = $('<input type="hidden" id="tax" data-taxrate="'+itemFound.rate+'" value="'+itemFound.tax+'" />');
           trBar.find('td:nth-child(7)').text(formatNumber(taxValue));
           trBar.find('td:nth-child(7)').append(inputTax);
           trBar.find('td:nth-child(8)').text(formatNumber(parseFloat(taxValue)+parseFloat(itemFound.price)));
           isNew = true;
           $('#btnAdd').attr('quantity-warehouse', itemFound.product_quantity);
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
       // $('#select_kindof_warehouse1').val('');
       // $('#select_kindof_warehouse1').selectpicker('refresh');
       // var warehouse_id=$('#select_warehouse1');
       // warehouse_id.find('option:gt(0)').remove();
       // warehouse_id.selectpicker('refresh');

       if(typeof(itemFound) != 'undefined') {
           var trBar = $('tr.main1');

           trBar.find('td:first > input').val(itemFound.id);
            trBar.find('td:nth-child(2)').text(itemFound.name+' ('+itemFound.prefix+itemFound.code+')('+ itemFound.category +')');
           trBar.find('td:nth-child(3)').text(itemFound.unit_name);
           trBar.find('td:nth-child(3) > input').val(itemFound.unit);
           trBar.find('td:nth-child(4) > input').val(1);
           trBar.find('td:nth-child(5) > input').val(formatNumber(itemFound.price).replace(/\,/g,'.'));
           trBar.find('td:nth-child(6)').text(formatNumber(itemFound.price * 1) );
           var taxValue = (parseFloat(itemFound.rate)*parseFloat(itemFound.price)/100);
           var inputTax = $('<input type="hidden" id="tax" data-taxrate="'+itemFound.rate+'" value="'+itemFound.tax+'" />');
           trBar.find('td:nth-child(7)').text(formatNumber(taxValue));
           trBar.find('td:nth-child(7)').append(inputTax);
           trBar.find('td:nth-child(8)').text(formatNumber(parseFloat(taxValue)+parseFloat(itemFound.price)));
           isNew = true;
           $('#btnAdd1').attr('quantity-warehouse', itemFound.product_quantity);
           $('#btnAdd1').show();
       }
       else {
           isNew = false;
           $('#btnAdd1').hide();
       }
   });


    // $('#select_warehouse').on('change', (e)=>{
    //     if($(e.currentTarget).val() != '') {
    //         $(e.currentTarget).parents('tr').find('input.mainQuantity').attr('data-store', $(e.currentTarget).find('option:selected').data('store'));
    //     }
    // });


    $(document).on('keyup', '.mainQuantity', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        let currentQuantity    = parseInt(currentQuantityInput.val());
        let quantityWarehouse  = parseInt(currentQuantityInput.parent().parent().attr('quantity-warehouse'));

        if (currentQuantity > quantityWarehouse) {
           alert_float('danger', "Số lượng sản phẩm này lớn hơn số lượng trong kho, vui lòng nhập lại!");
           $(e.currentTarget).val(currentQuantityInput.parent().parent().attr('old-quantity'));
           return;
        }

        let elementToCompare;
        if(typeof(currentQuantityInput.attr('data-store')) == 'undefined' )
            elementToCompare = currentQuantityInput.parents('tr').find('input:last');
        else
            elementToCompare = currentQuantityInput;
        // console.log(elementToCompare)
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

       var Gia = currentQuantityInput.parent().find(' + td');
       var GiaTri = Gia.find(' + td');

       var Thue = GiaTri.find(' + td');
       var Tong = Thue.find(' + td');
       var inputTax=Thue.find('input');
       GiaTri.text(formatNumber(replaceObjMoney($(Gia).find('input').val()) * (currentQuantityInput.val()*1)) );
       Thue.text(formatNumber(parseFloat(inputTax.data('taxrate'))/100*parseFloat(GiaTri.text().replace(/\,/g,''))));

       Thue.append(inputTax);
       Tong.text(formatNumber(parseFloat(Thue.text().replace(/\,/g,''))+parseFloat(GiaTri.text().replace(/\,/g,''))));
       refreshTotal();
       refreshTotal1();
    });

    $(document).on('change', '.mainUnitCost', (e)=>{
       var currentQuantityInput = $(e.currentTarget);
       var SoLuong = currentQuantityInput.parent().prev();
       var GiaTri = $(currentQuantityInput).parent().find(' + td');

       var Thue = GiaTri.find(' + td');
       var Tong = Thue.find(' + td');
       var inputTax=Thue.find('input');



       GiaTri.text(formatNumber($(SoLuong).find('input').val() * replaceObjMoney(currentQuantityInput.val()) ));
       Thue.text(formatNumber(parseFloat(inputTax.data('taxrate'))/100*parseFloat(GiaTri.text().replace(/\,/g,''))));

       Thue.append(inputTax);
       Tong.text(formatNumber(parseFloat(Thue.text().replace(/\,/g,''))+parseFloat(GiaTri.text().replace(/\,/g,''))));
       refreshTotal();
       refreshTotal1();
   });


    // $('#select_kindof_warehouse').change(function(e){
    //     var warehouse_type = $(e.currentTarget).val();
    //     var product = $(e.currentTarget).parents('tr').find('td:first input');
    //     if(warehouse_type != '' && product.val() != '') {
    //         loadWarehouses(warehouse_type,product.val());
    //     }
    // });
    // $('#warehouse_type').change(function(e){
    //     var warehouse_type = $(e.currentTarget).val();
    //     if(warehouse_type != '') {
    //         getWarehouses(warehouse_type);
    //     }
    // });

    $('#rel_id').change(function(){
     var v = $('#rel_id').val();
     $('table tr.sortable.item').remove();
     $('table tr.sortable.item1').remove();

     var total=0;

     refreshAll();
     refreshAll1();


     refreshTotal();
     refreshTotal1();

     if(v){
       $.ajax({
          url : admin_url + 'contracts/getIteamQuote/',
          data: {
           'id' : v,
          },
          type:'POST',
          dataType : 'json',
      })
            .done(function(data){

            $('#contract_incurred').val(data.incurred*1);

             if(data.items[0]){
                var total = 0;
               addProductQuote('',data.items);
               $.each(data.items, (index,itemFound) => {

                 total += parseFloat(itemFound.tax)+parseFloat(itemFound.price);

               });
               $('.totalPrice').text(formatNumber(total))

           }
           var html = "";
           var total2= 0;
           if(data.items2[0]){
               $.each(data.items2, (index,value) => {
               html += '<div class="col-md-12" style="margin-bottom: 10px; padding: 0px;"><div class="col-xs-12 col-md-6" style="padding-left: 0px;"><label>Tên phát sinh </label><input type="text" name="incurred['+ total2 +'][name_incurred]" value="'+ value.tblincurred_name +'" style="width: 100%; "></div><div class="col-xs-11 col-md-5"><label>Phí phát sinh </label><input type="text" class="pay_incurred_class"  onkeyup="formart_num(\'pay_incurred' + total2 + '\')"  id="pay_incurred'+total2+'" value="'+ (formatNumber(value.tblincurred_price *1)).replace(/\,/g,'.') +'" name="incurred['+ total2 +'][pay_incurred]" style="width: 100%"></div> <div class="col-xs-1 col-md-1"><div>&nbsp</div><a href="#" class="btn btn-danger pull-right delete_in_item" style="margin-top: 7px;"><i class="fa fa-times"></i></a></div></div>'
               total2++;
               });
           }

            $('#ex-gift').empty().html(html);

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
    });

    $('#addGift').click(function(){
     total2++;
     $('#ex-gift').append('<div class="col-md-12" style="margin-bottom: 10px; padding: 0px;"><div class="col-xs-12 col-md-6" style="padding-left: 0px;"><label>Tên phát sinh </label><input type="text" name="incurred['+ total2 +'][name_incurred]" style="width: 100%; "></div><div class="col-xs-11 col-md-5"><label>Phí phát sinh </label><input type="text" class="pay_incurred_class" value="0" onkeyup="formart_num(\'pay_incurred' + total2 + '\')"  id="pay_incurred'+total2+'" name="incurred['+ total2 +'][pay_incurred]" style="width: 100%"></div> <div class="col-xs-1 col-md-1"><div>&nbsp</div><a href="#" class="btn btn-danger pull-right delete_in_item" style="margin-top: 7px;"><i class="fa fa-times"></i></a></div></div>')
   });

   $('#ex-gift').on('click','.delete_in_item',function(){
     $(this).parent().parent().remove();
     total2--;
   });


    function addProductQuote(a,data){
        let variablename = 'uniqueArray'+a;
        if(a == 1){
         var b = "l";
        }else{
         var b = "";
        }

        var total = 0;

        $.each(data, (index,itemFound) => {

        var newTr = $('<tr class="sortable item'+a+'" quantity-warehouse="'+itemFound.product_quantity+'" old-quantity="'+itemFound.quantity+'"></tr>');
        var td1 = $('<td><input type="hidden" name="items'+a+'[' + window[variablename] + '][id]" value="" /></td>');
        var td2 = $('<td class="dragger"></td>');
        var td3 = $('<td></td>');
        var td4 = $('<td><input style="width: 100px" class="mainQuantity" type="number" name="items'+a+'[' + window[variablename] + '][quantity]" value="" /></td>');
        var td5 = $('<td><input style="width: 100px;" class="mainUnitCost" onkeyup="formart_num(\'unit_cost'+b + window[variablename] + '\')" type="text" id="unit_cost'+b+ + window[variablename] + '" name="items'+a+'[' + window[variablename] + '][unit_cost]" value=""></td>');
        var td6 = $('<td></td>');
        var td7 = $('<td><input type="hidden" id="tax" data-taxid="" data-taxrate="" value="" /></td>');
        var td8 = $('<td></td>');
         td1.find('input').val(itemFound.product_id);
          td2.text(itemFound.product_name+' ('+itemFound.prefix+itemFound.code+')');
          td3.text(itemFound.unit_name);
          td4.find('input').val(itemFound.quantity);

          td5.find('input').val(formatNumber(itemFound.unit_cost * 1));
          td6.text(formatNumber( (itemFound.unit_cost * 1) * (itemFound.quantity*1) ) );

          var taxValue = itemFound.tax;
          var inputTax = $('<input type="hidden" id="tax" data-taxrate="'+itemFound.tax+'" value="'+itemFound.tax+'" />');


         td7.text(formatNumber(itemFound.tax));
         td7.append(inputTax);
         td8.text(formatNumber(parseFloat(taxValue)+parseFloat(itemFound.unit_cost * itemFound.quantity)));

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

    function formart_num(id_input)
   {
     key="";
     money=$("#"+id_input).val().replace(/[^\d\.]/g, '');
     a=money.split(".");
     $.each(a , function (index, value){
         key=key+value;
     });
     $("#"+id_input).val(formatNumber(key, '.', '.'));
   }


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
</script>
</body>
</html>
