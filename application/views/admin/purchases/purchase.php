<?php init_head(); ?>
<style type="text/css">
  .item-purchase .ui-sortable tr td input {
    width: 80px;
  }
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			echo form_open($this->uri->uri_string(), array('id' => 'purchase-form', 'class' => '_transaction_form invoice-form'));
			if (isset($invoice)) {
				echo form_hidden('isedit');
			}
			?>
			<div class="col-md-12">
				<div class="panel_s">
				 	<div class="additional"></div>
				 	<div class="panel-body">
				 	<?php 
						$type = '';
						if (!isset($purchase))
							$type = 'warning';
						elseif ($purchase->status == 0)
							$type = 'warning';
						elseif ($purchase->status == 1)
							$type = 'info';
						elseif ($purchase->status == 2)
							$type = 'success';

						?>
				 		<div class="ribbon <?= $type ?>" project-status-ribbon-2="">
				 			<?php 
								if (isset($purchase))
									{
									$status = format_purchase_status($purchase->status, '', false);
								}
								else
									{
									$status = format_purchase_status(-1, '', false);
								}
								?>
				 			<span><?= $status ?></span>
						 </div>
						 <?php 
							if (isset($purchase))
							{ ?>
						<div class="form-group" style="margin-top: 25px">
						    <a href="<?php echo admin_url('purchases/pdf/' . $purchase->id . '?print=true'); ?>" target = '_blank' class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('print'); ?>" data-placement="bottom"><i class="fa fa-print"></i></a>
						    <a href="<?php echo admin_url('purchases/pdf/' . $purchase->id); ?>" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="<?php echo _l('view_pdf'); ?>" data-placement="bottom"><i class="fa fa-file-pdf-o"></i></a>
						    <!-- <a href="#" class="invoice-send-to-client btn-with-tooltip btn btn-default" data-toggle="tooltip" title="<?php echo $_tooltip; ?>" data-placement="bottom"><span data-toggle="tooltip" data-title="<?php echo $_tooltip_already_send; ?>"><i class="fa fa-envelope"></i></span></a> -->
						</div>
						<?php 
							}
						?>
				 		<div class="col-md-4">
				 			<h4 class="bold no-margin font-medium">
						     <?php echo _l('Thông tin kế hoạch'); ?>
						   </h4>
						   <hr />
						   <div class="form-group">
				                 <label for="number"><?php echo _l('Số kế hoạch'); ?></label>
				                 <div class="input-group">
									<?php
									if (!$purchase)
									{
									?>
				                  <span class="input-group-addon">
				                    <?php echo get_option('prefix_purchase_plan') ?></span>
									<?php } ?>
									<?php 
				                    	// var_dump($purchase);
																								if ($purchase)
																									{

																									$number = $purchase->code;
																								}
																								else
																									{
																									$number = sprintf('%05d', getMaxID('id', 'tblpurchase_plan') + 1);
																								}
																								?>
				                    <input type="text" name="number" class="form-control" value="<?= $number ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" readonly>
				                  </div>
			                </div>

			                <?php $value = (isset($purchase) ? _d($purchase->date) : _d(date('Y-m-d'))); ?>
                  			<?php echo render_date_input('date', 'Ngày kế hoạch', $value); ?>

                  			<?php
																					$value = (isset($purchase) ? $purchase->name : "");
																					echo render_input('name', _l('Tên kế hoạch'), $value);
																					?>

		                    <?php 
																						$value = (isset($purchase) ? $purchase->reason : "");
																						echo render_textarea('reason', 'Lý do', $value, array(), array(), '', 'tinymce');
																						?>

		                    <!-- <?php
																											$status = array(array('id' => 0, 'text' => 'Chưa duyệt'), array('id' => 1, 'text' => 'Đã duyệt'));
																											$value = (isset($purchase) ? $purchase->status : "0");
																											echo render_select('status', $status, array('id', 'text'), 'Trạng thái', $value, array(), array(), '', '', false);
																											?> -->
		                    <button class="btn-tr btn btn-info mleft10 text-right pull-right purchase-form-submit">
						      <?php echo _l('submit'); ?>
						    </button>
				 		</div>
						

						
						<!-- Edited -->
						<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
							<!-- Cusstomize from invoice -->
							<div class="panel-body mtop10">
							<?php if(!empty($item->rel_id) || !empty($item->rel_code)){ $display='style="display: none;"';  }?>
								<div class="row" <?=$display?> >
									<div class="col-md-4">
										<div class="form-group mbot25">
											<select class="selectpicker no-margin" data-width="100%" id="custom_item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
												<option value=""></option>

												<?php foreach ($items as $product) { ?>
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
												
												<th width="" class="text-left"><?php echo _l('item_price_buy'); ?></th>
												<th width="" class="text-left"><?php echo _l('purchase_total_price'); ?></th>
												<th width="" class="text-left"><?php echo _l('warehouse_type'); ?></th>
												<th width="" class="text-left"><?php echo _l('warehouse_name'); ?></th>
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
													<button style="display:none" id="btnAdd" type="button" onclick="createTrItem(); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
												</td>
											</tr>
											<?php
											$i=0;
											$totalPrice=0;
											
											if(isset($purchase) && count($purchase->items) > 0) {
												
												foreach($purchase->items as $value) {
													
												?>
											<tr class="sortable item">
												<td>
													<input type="hidden" name="item[<?php echo $i; ?>][id]" value="<?php echo $value['product_id']; ?>">
												</td>
												<td class="dragger"><?php echo $value['name']; ?></td>
												<td><?php echo $value['unit_name']; ?></td>
												<?php
												$err='';
													if($value['quantity_required']>$value['warehouse_type']->maximum_quantity)
													{
														$err='error';
														$style='border: 1px solid red !important';
													}
												?>
												<td>
												<input style="width: 100px; <?=$style?>" class="mainQuantity <?=$err?>" type="number" name="item[<?php echo $i; ?>][quantity]" value="<?php echo $value['quantity_required']; ?>">
												</td>
													
												<td><?php echo number_format($value['price_buy']); ?></td>
												<td><?php echo number_format($value['price_buy']*$value['quantity_required']); ?></td>
												<td><?php echo $value['warehouse_type']->kindof_warehouse_name ?></td>
												<td><input type="hidden" data-store="<?=$value['warehouse_type']->maximum_quantity ?>" name="item[<?=$i?>][warehouse]" value="<?=$value['warehouse_id']?>"><?php echo $value['warehouse_type']->warehouse ?>(tối đa <?=$value['warehouse_type']->maximum_quantity?>)</td>
												<td><a href="#" class="btn btn-danger pull-right" onclick="deleteTrItem(this); return false;"><i class="fa fa-times"></i></a></td>
											</tr>
												<?php
													$totalPrice += $value['price_buy']*$value['quantity_required'];
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
						<!-- End edited -->
				 		</div>

				 		

				 	</div>

				 	

			 	</div>
				
			</div>
			
			<?php echo form_close(); ?>
			<?php $this->load->view('admin/invoice_items/item'); ?>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	$(function(){
		validate_invoice_form();
	    // Init accountacy currency symbol
	    init_currency_symbol();
	});
</script>
</body>
</html>
<script type="text/javascript">
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
    var createTrItem = () => {
        if(!isNew) return;
        if(!$('tr.main #select_warehouse option:selected').length || $('tr.main #select_warehouse option:selected').val() == '') {
            alert_float('danger', "Vui lòng chọn kho chứa sản phẩm!");
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
        
        var td1 = $('<td><input type="hidden" name="item[' + uniqueArray + '][id]" value="" /></td>');
        var td2 = $('<td class="dragger"></td>');
        var td3 = $('<td></td>');
        var td4 = $('<td><input style="width: 100px" class="mainQuantity" type="number" name="item[' + uniqueArray + '][quantity]" value="" /></td>');
        var td5 = $('<td></td>');
        var td6 = $('<td></td>');
        var td7 = $('<td></td>');
        var td8 = $('<td></td>');

        td1.find('input').val($('tr.main').find('td:nth-child(1) > input').val());
        td2.text($('tr.main').find('td:nth-child(2)').text());
        td3.text($('tr.main').find('td:nth-child(3)').text());
        td4.find('input').val($('tr.main').find('td:nth-child(4) > input').val());
        
        td5.text( $('tr.main').find('td:nth-child(5)').text());
        td6.text( $('tr.main').find('td:nth-child(6)').text());
        td7.text( $('tr.main').find('td:nth-child(7) select option:selected').text());
        td8.append( '<input type="hidden" data-store="'+$('tr.main').find('td:nth-child(8) select option:selected').data('store')+'" name="item[' + uniqueArray + '][warehouse]" value="'+$('tr.main').find('td:nth-child(8) select option:selected').val()+'" />');
        td8.append($('tr.main').find('td:nth-child(8) select option:selected').text());
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
        totalPrice = 0;
        $.each(items, (index,value)=>{
            totalPrice += $(value).find('td:nth-child(4) > input').val() * $(value).find('td:nth-child(5)').text().replace(/\,/g, '');
        });
        $('.totalPrice').text(formatNumber(totalPrice));
    };
    $('#custom_item_select').change((e)=>{
        var id = $(e.currentTarget).val();
        var itemFound = findItem(id);

        $('#select_kindof_warehouse').val('');
        $('#select_kindof_warehouse').selectpicker('refresh');
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
            
            trBar.find('td:nth-child(5)').text(formatNumber(itemFound.price));
            trBar.find('td:nth-child(6)').text(formatNumber(itemFound.price * 1) );
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
    $(document).on('keyup', '.mainQuantity', (e)=>{
        var currentQuantityInput = $(e.currentTarget);
        let elementToCompare;
        if(typeof(currentQuantityInput.attr('data-store')) == 'undefined' )
            elementToCompare = currentQuantityInput.parents('tr').find('input:last');
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
        
        var Gia = currentQuantityInput.parent().find(' + td');
        var Tong = Gia.find(' + td');
        Tong.text( formatNumber(Gia.text().replace(/\,/g, '') * currentQuantityInput.val()) );
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
    $('.customer-form-submiter').on('click', (e)=>{
        if($('input.error').length) {
            e.preventDefault();
            alert('Giá trị không hợp lệ!');    
        }
        
    });

</script>

