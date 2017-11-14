<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="bold no-margin"><?php echo (isset($item) ? _l('quote_edit') : _l('quote_add')); ?></h4>
</div>
<div class="modal-body">
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
                   elseif($item->status==3)
                   {
                       $type='danger';
                       $status='Không duyệt';
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
         <div class="ribbon <?=$type?> hidden"><span><?=$status?></span></div>
         <ul class="nav nav-tabs profile-tabs" role="tablist">
            <li role="presentation" class="active">
               <a href="#item_detail" aria-controls="item_detail" role="tab" data-toggle="tab">Thông tin
               </a>
            </li>
            <?php if($item) { ?>
            <li role="presentation" class="">
               <a href="#item_pdf" aria-controls="item_pdf" role="tab" data-toggle="tab">Xem PDF
               </a>
            </li>
            <li role="presentation" class="">
               <a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab"><?php echo _l('Các tập tin') ?>
               </a>
            </li>
            <?php } ?>
         </ul>
         <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="item_detail">
              <?php
                $quotesAction = (isset($item)) ? 'quote_detail/'.$item->id : 'quote_add_ajax';
                $classForm    = (isset($item)) ? '-update' : '';
               ?>

               <?php echo form_open_multipart(base_url().'admin/quotes/'.$quotesAction, array('id'=>'quotes_form','class' => 'sales-form'.$classForm, 'autocomplete' => 'off')); ?>
               <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                     <?php
                        $attrs_not_select = array('data-none-selected-text' => _l('system_default_string'));
                        ?>
                     <div class="form-group">
                        <label for="number"><?php echo _l('quote_code'); ?></label>
                        <div class="input-group">
                           <span class="input-group-addon">
                           <?php $prefix =($item) ? $item->prefix : get_option('prefix_quote'); ?>
                           <?=$prefix?>
                           <?=form_hidden('prefix',$prefix)?>
                           </span>
                           <?php
                              if($item)
                              {
                                  $number=$item->code;
                              }
                              else
                              {
                                  $number= $code;

                              }
                              ?>
                           <input type="text" name="code" class="form-control" id="code" value="<?=$number ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" <?php echo ($item)? 'readonly' : '' ?>>
                        </div>
                     </div>
                     <?php $value = (isset($item) ? _d($item->date) : _d(date('Y-m-d')));?>
                     <?php echo render_date_input('date','view_date',$value); ?>
                     <?php
                        $default_name = (isset($item) ? $item->name : _l('quote_name'));
                        echo form_hidden('name', _l('quote_name'), $default_name);
                        ?>
                     <?php
                        $selected = $client_id;
                        if($selected == NULL){
                          $selected=(isset($item) ? $item->customer_id : '');
                        }
                        echo render_select('customer_id',$customers,array('userid','company'),'client',$selected,$frmattrs);
                        ?>
                     <?php
                        $selected1=(isset($item) ? $item->cate_delegate_id : '');
                        echo render_select('cate_delegate_id',$categories_a,array('id','category'),'Chọn hãng mô tả',$selected1);
                        ?>
                     <?php $value = (isset($item) ? $item->subject : _l('Báo giá - ')); ?>
                     <?php echo render_input('subject','contract_subject',$value,'text',array('data-toggle'=>'tooltip','title'=>'contract_subject_tooltip')); ?>
                     <?php
                        $note = (isset($item) ? $item->note : "");
                        echo render_textarea('note', 'note', $note, array(), array(), '', 'tinymce');
                        ?>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                     <!-- Cusstomize from invoice -->
                     <div class="panel-body mtop10" style="border: 1px solid #ccc;">
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
                                    <th style="min-width: 200px" class="text-left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_name'); ?>"></i> <?php echo _l('item_name'); ?></th>
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
                                 <tr class="sortable item">
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
                                       <?php echo number_format($totalPrice) ?> VND
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <!-- End Customize from invoice -->
                     <!-- Cusstomize from invoice -->
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
                                    <th style="min-width: 200px" class="text-left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_name'); ?>"></i> <?php echo _l('item_name'); ?></th>
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
                                       <input style="width: 100px" class="mainUnitCost" type="text" value="1"  class="form-control" id="unit_cost1" onkeyup="formart_num('unit_cost1')" placeholder="<?php echo _l('item_price'); ?>">
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
                                       <?php echo number_format($totalPrice1) ?> VND
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
                                    <input type="text" onkeyup="formart_num('pay_incurred<?php echo $k ?>')" id="pay_incurred<?php echo $k ?>" value="0" name="incurred[<?php echo $k ?>][pay_incurred]" style="width: 100%">
                                 </div>
                              </div>
                              <?php }else{ ?>
                              <?php foreach ($item->items2 as $key => $value) { ?>
                              <div class="col-md-12" style="    margin-bottom: 10px; padding: 0px;">
                                 <div class="col-xs-12 col-md-6" style="padding-left: 0px;">
                                    <label>Tên phát sinh </label>
                                    <input type="hidden" name="incurred[<?php echo $k ?>][id]" value="<?php echo $value->tblincurred_id ?>">
                                    <input type="text" value="<?php echo $value->tblincurred_name ?>" name="incurred[<?php echo $k ?>][name_incurred]" style="width: 100%; ">
                                 </div>
                                 <div class="col-xs-11 col-md-5">
                                    <label>Phí phát sinh </label>
                                    <input type="text" onkeyup="formart_num('pay_incurred<?php echo $k ?>')" id="pay_incurred<?php echo $k ?>" value="<?php echo str_replace(',','.',number_format($value->tblincurred_price)) ?>" name="incurred[<?php echo $k ?>][pay_incurred]" style="width: 100%">
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
                     <!-- End Customize from invoice -->
                  </div>
                  <div class="text-right col-xs-12">
                     <button class="btn btn-info mtop20 only-save customer-form-submiter " style="margin-right: 5px">
                     <?php echo _l('submit'); ?>
                     </button>
                  </div>
               </div>
               <?php echo form_close(); ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="item_pdf">
               <div class="row">
                  <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                  </div>
                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 _buttons">
                     <div class="pull-right">
                        <?php if( isset($item) ) { ?>
                        <a href="<?php echo admin_url('quotes/pdf/'.$item->id.'?print=true'); ?>" target="_blank" class="btn btn-default mright5 btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('print'); ?>" data-placement="bottom"><i class="fa fa-print"></i></a>
                        <a href="<?php echo admin_url('quotes/pdf/'.$item->id); ?>" class="btn btn-default mright5 btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('view_pdf'); ?>" data-placement="bottom"><i class="fa fa-file-pdf-o"></i></a>
                        <a href="<?php echo admin_url('quotes/word/'.$item->id); ?>" class="btn btn-default mright5 btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('Xem Word'); ?>" data-placement="bottom"><i class="fa fa-file-word-o"></i></a>
                        <a href="#" class="btn btn-default mright5" data-target="#contract_send_to_client_modal" data-toggle="modal"><span class="btn-with-tooltip" data-toggle="tooltip" data-title="<?php echo _l('contract_send_to_email'); ?>" data-placement="bottom"><i class="fa fa-envelope"></i></span></a>
                        <?php } ?>
                     </div>
                  </div>
                  <div class="editable tc-content col-md-12" style="border:1px solid #f0f0f0; padding: 10px; margin-top: 15px;">
                     <?php if(empty($item->content)){  ?>
                     <table>
                        <tr>
                           <td style="width: 20%;">
                              <img src="<?php echo site_url() ?>uploads/company/logo.png" class="img-responsive" alt="" data-mce-src="<?php echo site_url() ?>uploads/company/logo.png">
                           </td>
                           <td style="width: 80%;">
                              <p class="bold" style="color: red; font-size: 12pt;" data-mce-style="color: red; font-size: 12pt;">CÔNG TY CỔ PHẦN TỰ ĐỘNG SƠN HÀ</p>
                              <p>Địa chỉ: 271 Phổ Vọng - Quận Hai Bà Trưng - Hà Nội</p>
                              <p>Điện thoại: 043-8688981 * Fax: 043-8688981</p>
                              <p>Email: autodoor@sonha.com</p>
                              <p>Website: www.sonha.com - www.cuatudong.com</p>
                           </td>
                        </tr>
                     </table>
                     <p style="text-align: center;" data-mce-style="text-align: center;"><span style="font-size: 18pt;" data-mce-style="font-size: 18pt;"><strong>BÁO GIÁ CỔNG THIẾT BỊ SỐ</strong></span></p>
                     <p style="text-align: center;" data-mce-style="text-align: center;"><span style="font-size: 12pt;" data-mce-style="font-size: 12pt;"><strong>Số: ..../022016/SH</strong></span></p>
                     <table class="table mce-item-table" border="1" width="100%" data-mce-selected="1">
                        <tbody>
                           <tr>
                              <td style="width: 20%">Tên khách hàng</td>
                              <td style="width: 40%"><?php echo $item->customer_name ?></td>
                              <td style="width: 20%">Ngày báo giá</td>
                              <td style="width: 20%"><?php echo _d($item->date) ?></td>
                           </tr>
                           <tr>
                              <td>Địa chỉ</td>
                              <td><?php echo $item->address ?></td>
                              <td>Tên dự án</td>
                              <td></td>
                           </tr>
                           <tr>
                              <td>Số điện thoại / email</td>
                              <td><?php echo $item->phonenumber  ?>  <?php echo ($item->email) ? '/'.$item->email : '' ?></td>
                              <td>Ngày báo giá / Tel</td>
                              <td></td>
                           </tr>
                        </tbody>
                     </table>
                     <p style="font-size: 12pt;">Công ty hệ thống tự động Sơn Hà xin trân trọng gửi tới quý khách hàng báo giá cung cấp, và bảo hành hệ thống điều khiển cổng tự động như sau:</p>
                     <?php echo $item->cdescription ?>
                     <br>
                     <table class="table" border="1">
                        <tr style="text-align: center;font-size: 12pt;">
                           <td style="width: 5%;"><span style="font-size: 12pt;" data-mce-style="font-size: 12pt;"><strong>TT</strong></span></td>
                           <td colspan="1" style=" width: 40%"><span style="font-size: 12pt;" data-mce-style="font-size: 12pt;"><strong>Tên thiết bị - Đặc tính kỹ thuật</strong></span></td>
                           <td style="width: 10%;"><strong>ĐVT</strong></td>
                           <td style="width: 10%;"><strong>Số lượng</strong></td>
                           <td style=" width: 15%;"><span style="" data-mce-style="font-size: 12pt;"><strong>Đơn giá(VNĐ)</strong></span></td>
                           <td style=" width: 15%;"><strong>Thành tiền(VNĐ)</strong></td>
                           <!--  <td>VAT(VNĐ)</td>
                              <td>Tổng thành tiền(VNĐ)</td> -->
                        </tr>
                        <?php if($item->items){ ?>
                        <tr>
                           <td colspan="6" style="background-color: red" data-mce-style="background-color: red;"><span style="font-size: 14pt; color: #fff" data-mce-style=" font-size: 14pt; color:#fff;">SẢN PHẨM</span></td>
                        </tr>
                        <?php $z=1;$totalPriceItem = 0; foreach ($item->items as $key => $value) {?>
                        <tr>
                           <td style="text-align: center; width: 5%"><?php echo $z ?></td>
                           <td style="width: 40%">
                              <span style="text-transform: uppercase;" style="font-weight: bold;">MODEL:<?php echo $value->product_name ?></span><br>
                              <span style="font-weight: bold;">Thông số kỹ thuật:</span><br>
                              <span><?php echo $value->product_features ?></span><br>
                              <span style="font-weight: bold;">Chi tiết thiết bị: </span><br>
                              <span><?php echo $value->long_description?></span>
                           </td>
                           <td style="text-align: center;"><?php echo $value->unit_name ?></td>
                           <td style="text-align: center;"><?php echo $value->quantity ?></td>
                           <td  style="text-align: center;font-weight: bold;width: 15%;"><?php echo number_format($value->unit_cost)  ?> VNĐ</td>
                           <td  style="text-align: center;font-weight: bold;width: 15%;"><?php echo number_format($value->quantity*$value->unit_cost)  ?> VNĐ</td>
                           <?php  $totalPriceItem += $value->quantity*$value->unit_cost; ?>
                        </tr>
                        <?php $z++; } ?>
                        <?php } ?>
                        <?php if($item->items1){ ?>
                        <tr>
                           <td colspan="6" style="background-color: red" data-mce-style="background-color: red;"><span style="font-size: 14pt; color: #fff" data-mce-style=" font-size: 14pt; color:#fff;">PHỤ KIỆN KÈM THEO</span></td>
                        </tr>
                        <?php foreach ($item->items1 as $key => $value) {?>
                        <tr>
                           <td style="text-align: center; width: 5%"><?php echo $z ?></td>
                           <td style="width:40%">
                              <span style="text-transform: uppercase"><?php echo $value->product_name ?></span>
                           </td>
                           <td style="text-align: center;"><?php echo $value->unit_name ?></td>
                           <td style="text-align: center;"><?php echo $value->quantity ?></td>
                           <td  style="text-align: center;font-weight: bold;width: 15%;"><?php echo number_format($value->unit_cost)  ?> VNĐ</td>
                           <td  style="text-align: center;font-weight: bold;width: 15%;"><?php echo number_format($value->quantity*$value->unit_cost)  ?> VNĐ</td>
                           <?php  $totalPriceItem += $value->quantity*$value->unit_cost; ?>
                        </tr>
                        <?php $z++; }} ?>
                        <tr  style="font-size: 11pt;" data-mce-style="font-size: 11pt;">
                           <td style="text-align: center;"><?php echo $z; ?></td>
                           <td style="text-align: right;" data-mce-style="text-align: right;"><strong>Tổng giá trị sản phẩm</strong></td>
                           <td></td>
                           <td></td>
                           <td colspan="2" style="text-align: right;" data-mce-style="text-align: right;"><strong><?php echo number_format($totalPriceItem) ?> VNĐ</strong></td>
                           <?php $z++; ?>
                        </tr>
                        <?php $totaInc = 0; foreach ($item->items2 as $key => $value) { ?>
                        <tr  style="font-size: 11pt;" data-mce-style="font-size: 11pt;">
                           <td style="text-align: center;"><?php echo $z; ?></td>
                           <td style="text-align: right;" data-mce-style="text-align: right;"><strong><?php echo $value->tblincurred_name?></strong></td>
                           <td></td>
                           <td></td>
                           <td colspan="2" style="text-align: right;" data-mce-style="text-align: right;"><strong><?php echo number_format($value->tblincurred_price) ?> VNĐ</strong></td>
                           <?php $z++; ?>
                        </tr>
                        <?php $totaInc += $value->tblincurred_price; } ?>
                        <tr  style="font-size: 11pt;" data-mce-style="font-size: 11pt;">
                           <td  style="text-align: center;" data-mce-style="text-align: center;"><?php echo $z; ?></td>
                           <td style="text-align: right;" data-mce-style="text-align: right;"><strong>Tổng giá trị</strong></td>
                           <td></td>
                           <td></td>
                           <td colspan="2" style="text-align: right;" data-mce-style="text-align: right;"><strong><?php echo number_format($totaInc + $totalPriceItem) ?> VNĐ</strong></td>
                        </tr>
                     </table>
                     <p><span style="text-decoration: underline; font-size: 10pt;" data-mce-style="text-decoration: underline; font-size: 10pt;"><em><strong>Ghi chú:</strong></em></span> Giá trên chưa bao gồm 10% thuế VAT<br></p>
                     <p>-&nbsp;<em>Công lắp đặt 1,500,000 VND/ Bộ tại Hà nội</em></p>
                     <p><em><span>-&nbsp;Người liên hệ: Phan Quý Giang</span></em></p>
                     <ul>
                        <li><strong><em>Chú giải ý nghĩa các biểu tượng công nghệ của BFT</em></strong></li>
                     </ul>
                     <?php  } else {
                        echo $item->content;
                        }
                        ?>
                  </div>
               </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="attachments">
               <h4 class="no-mtop bold"><?php echo _l('customer_attachments'); ?></h4>
               <hr />
               <?php if(isset($item)){ ?>
               <?php echo form_open_multipart(admin_url('quotes/upload_attachment/'.$item->id),array('class'=>'dropzone','id'=>'client-attachments-upload')); ?>
               <input type="file" name="file" multiple />
               <?php echo form_close(); ?>
               <div class="text-right mtop15">
                  <div id="dropbox-chooser"></div>
               </div>
               <div class="attachments">
                  <div class="table-responsive mtop25">
                     <table class="table dt-table" data-order-col="2" data-order-type="desc">
                        <thead>
                           <tr>
                              <th width="30%"><?php echo _l('customer_attachments_file'); ?></th>
                              <th><?php echo _l('file_date_uploaded'); ?></th>
                              <th><?php echo _l('options'); ?></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach($attachments as $type => $attachment){
                              $download_indicator = 'id';
                              $key_indicator = 'rel_id';
                              $upload_path = get_upload_path_by_type($type);
                              if($type == 'invoice'){
                                  $url = site_url() .'download/file/sales_attachment/';
                                  $download_indicator = 'attachment_key';
                              } else if($type == 'proposal'){
                                  $url = site_url() .'download/file/sales_attachment/';
                                  $download_indicator = 'attachment_key';
                              } else if($type == 'estimate'){
                                  $url = site_url() .'download/file/sales_attachment/';
                                  $download_indicator = 'attachment_key';
                              } else if($type == 'contract'){
                                  $url = site_url() .'download/file/contract/';
                              } else if($type == 'lead'){
                                  $url = site_url() .'download/file/lead_attachment/';
                              } else if($type == 'task'){
                                  $url = site_url() .'download/file/taskattachment/';
                              } else if($type == 'ticket'){
                                  $url = site_url() .'download/file/ticket/';
                                  $key_indicator = 'ticketid';
                              }else if($type == 'quotes'){
                                  $url = site_url() .'download/file/quote/';
                              } else if($type == 'customer'){
                                  $url = site_url() .'download/file/client/';
                              } else if($type == 'expense'){
                                  $url = site_url() .'download/file/expense/';
                                  $download_indicator = 'rel_id';
                              }
                              ?>
                           <?php foreach($attachment as $_att){
                              ?>
                           <tr id="tr_file_<?php echo $_att['id']; ?>">
                              <td>
                                 <?php
                                    $path = $upload_path . $_att[$key_indicator] . '/' . $_att['file_name'];
                                    $is_image = false;
                                    if(!isset($_att['external'])) {
                                       $attachment_url = $url . $_att[$download_indicator];
                                       $is_image = is_image($path);
                                       $img_url = site_url('download/preview_image?path='.protected_file_url_by_path($path).'&type='.$_att['filetype']);
                                    } else if(isset($_att['external']) && !empty($_att['external'])){

                                       if(!empty($_att['thumbnail_link'])){
                                           $is_image = true;
                                           $img_url = optimize_dropbox_thumbnail($_att['thumbnail_link']);
                                       }

                                       $attachment_url = $_att['external_link'];
                                    }
                                    if($is_image){
                                       echo '<div class="preview_image">';
                                    }
                                    ?>
                                 <a href="<?php if($is_image){ echo $img_url; } else {echo $attachment_url; } ?>"<?php if($is_image){ ?> data-lightbox="customer-profile" <?php } ?> class="display-block mbot5">
                                    <?php if($is_image){ ?>
                                    <div class="table-image">
                                       <img src="<?php echo $img_url; ?>">
                                    </div>
                                    <?php } else { ?>
                                    <i class="<?php echo get_mime_class($_att['filetype']); ?>"></i> <?php echo $_att['file_name']; ?>
                                    <?php } ?>
                                 </a>
                                 <?php if($is_image){
                                    echo '</div>';
                                    }
                                    ?>
                              </td>
                              <td data-order="<?php echo $_att['dateadded']; ?>"><?php echo _dt($_att['dateadded']); ?></td>
                              <td>
                                 <?php if($type == 'quotes'){ ?>
                                 <a data-id="<?php echo $_att['id'] ?>" data-customer="<?php echo $_att['rel_id']  ?>" href="javascript:void(0)"  class="btn btn-danger btn-icon delete_attachment"><i class="fa fa-remove"></i></a>
                                 <?php } ?>
                              </td>
                              <?php } ?>
                           </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
               <?php
                  include_once(APPPATH . 'views/admin/clients/modals/send_file_modal.php');
                  } ?>
            </div>
         </div>
      </div>
      <!-- END PI -->
   </div>
</div>
<?php if(isset($item)){ ?>
<!-- init table tasks -->
<?php $this->load->view('admin/quotes/send_to_client'); ?>
<?php $this->load->view('admin/quotes/renew_contract'); ?>
<?php } ?>
<script>
    _validate_form($('.sales-form'),{code:'required',date:'required',customer_id:'required'}, addQuotes);

    function addQuotes(form) {
        var data = $(form).serialize();
        var url = form.action;

        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if(response.status == true){
              alert_float('success', response.message);
              $('.quotes-modal').modal('hide');
              $('.table-quote_clients').DataTable().ajax.reload()
            }else{
              alert_float('danger', response.message);
            }
            // console.log('response', response);
        });
        return false;
    }


   var contract_id = '<?php echo $item->id; ?>';
   var itemList = <?php echo json_encode($items);?>;
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



   function insert_merge_field(field){
    var key = $(field).text();
    tinymce.activeEditor.execCommand('mceInsertContent', false, key);
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
   var total2 = <?php echo $k ?>;
   var isNew = false;
   var createTrItem = () => {
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
       // if($('tr.main').find('td:nth-child(4) > input').val() > $('tr.main #select_warehouse option:selected').data('store')) {
       //     alert_float('danger', 'Kho ' + $('tr.main #select_warehouse option:selected').text() + '. Bạn đã nhập ' + $('tr.main').find('td:nth-child(4) > input').val() + ' là quá số lượng cho phép.');
       //     return;
       // }
       var newTr = $('<tr class="sortable item"></tr>');

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
       uniqueArray++;
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
       // if($('tr.main1').find('td:nth-child(4) > input').val() > $('tr.main #select_warehouse option:selected').data('store')) {
       //     alert_float('danger', 'Kho ' + $('tr.main #select_warehouse1 option:selected').text() + '. Bạn đã nhập ' + $('tr.main1').find('td:nth-child(4) > input').val() + ' là quá số lượng cho phép.');
       //     return;
       // }
       var newTr = $('<tr class="sortable item1"></tr>');

       var td1 = $('<td><input type="hidden" name="items1[' + uniqueArray1 + '][id]" value="" /></td>');
       var td2 = $('<td class="dragger"></td>');
       var td3 = $('<td></td>');
       var td4 = $('<td><input style="width: 100px" class="mainQuantity" type="number" name="items1[' + uniqueArray1 + '][quantity]" value="" /></td>');
       var td5 = $('<td><input style="width: 100px;" class="mainUnitCost" onkeyup="formart_num(\'unit_cost1' + uniqueArray1 + '\')" type="text" id="unit_cost1' + uniqueArray1 + '" name="items1[' + uniqueArray1 + '][unit_cost]" value=""></td>');
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
       uniqueArray1++;
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

   $('#addGift').click(function(){
     total2++;
     $('#ex-gift').append('<div class="col-md-12" style="margin-bottom: 10px; padding: 0px;"><div class="col-xs-12 col-md-6" style="padding-left: 0px;"><label>Tên phát sinh </label><input type="text" name="incurred['+ total2 +'][name_incurred]" style="width: 100%; "></div><div class="col-xs-11 col-md-5"><label>Phí phát sinh </label><input type="text" onkeyup="formart_num(\'pay_incurred' + total2 + '\')"  id="pay_incurred'+total2+'" name="incurred['+ total2 +'][pay_incurred]" style="width: 100%"></div> <div class="col-xs-1 col-md-1"><div>&nbsp</div><a href="#" class="btn btn-danger pull-right delete_in_item" style="margin-top: 7px;"><i class="fa fa-times"></i></a></div></div>')
   });
   <?php if($item){ ?>
     $(".sales-form-update").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var url1 = $(this)[0].action; // the script where you handle the form input.

        $.ajax({
               type: "POST",
               url: url1,
               dataType:'JSON',
               data: $(".sales-form-update").serialize(), // serializes the form's elements.
               success: function(data)
               {
                  alert_float(data.alert_type,data.message);
               }
        });

    });

   <?php } ?>

   $('#ex-gift').on('click','.delete_in_item',function(){
     $(this).parent().parent().remove();
     total2--;
   })

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


   $(document).on('change', '.mainQuantity', (e)=>{
       var currentQuantityInput = $(e.currentTarget);
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

   // function getWarehouses(warehouse_type){
   //     var warehouse_id=$('#warehouse_name');
   //     warehouse_id.find('option:gt(0)').remove();
   //     warehouse_id.selectpicker('refresh');
   //     if(warehouse_id.length) {
   //         $.ajax({
   //             url : admin_url + 'warehouses/getWarehouses/' + warehouse_type ,
   //             dataType : 'json',
   //         })
   //         .done(function(data){
   //             $.each(data, function(key,value){
   //                 warehouse_id.append('<option value="' + value.warehouseid +'">' + value.warehouse + '</option>');
   //             });

   //             warehouse_id.selectpicker('refresh');
   //         });
   //     }
   // }
   // function loadWarehouses(warehouse_type, filter_by_product,default_value=''){
   //     var warehouse_id=$('#select_warehouse');
   //     warehouse_id.find('option:gt(0)').remove();
   //     warehouse_id.selectpicker('refresh');
   //     if(warehouse_id.length) {
   //         $.ajax({
   //             url : admin_url + 'warehouses/getWarehouses/' + warehouse_type + '/' + filter_by_product,
   //             dataType : 'json',
   //         })
   //         .done(function(data){
   //             $.each(data, function(key,value){
   //                 var stringSelected = "";
   //                 if(value.warehouseid == default_value) {
   //                     stringSelected = ' selected="selected"';
   //                 }
   //                 warehouse_id.append('<option data-store="'+value.items[0].product_quantity+'" value="' + value.warehouseid + '"'+stringSelected+'>' + value.warehouse + '(có '+value.items[0].product_quantity+')</option>');
   //             });
   //             warehouse_id.selectpicker('refresh');
   //         });
   //     }
   // }
   $('.customer-form-submiter').on('click', (e)=>{
       if($('input.error').length) {
           e.preventDefault();
           alert('Giá trị không hợp lệ!');
       }

   });



</script>
</body>
</html>
