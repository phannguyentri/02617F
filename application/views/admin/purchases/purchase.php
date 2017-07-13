<?php init_head(); ?>
<style type="text/css">
  .item-purchase .ui-sortable tr td input {
    width: 110px;
  }
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			echo form_open($this->uri->uri_string(),array('id'=>'invoice-form','class'=>'_transaction_form invoice-form'));
			if(isset($invoice)){
				echo form_hidden('isedit');
			}
			?>
			<div class="col-md-12">
				<div class="panel_s">
				 	<div class="additional"></div>
				 	<div class="panel-body">
				 		<div class="col-md-4">
				 			<h4 class="bold no-margin font-medium">
						     <?php echo _l('Thông tin kế hoạch'); ?>
						   </h4>
						   <hr />
						   <div class="form-group">
				                 <label for="number"><?php echo _l('Số kế hoạch'); ?></label>
				                 <div class="input-group">
				                  <span class="input-group-addon">
				                    <?php echo get_option('prefix_purchase_plan') ?></span>
				                    <?php 
				                    	// var_dump($purchase);
				                    	if($purchase)
				                    	{

				                    		$number=substr($purchase->code, strlen(get_option('prefix_purchase_plan')));
				                    	}
				                    	else
				                    	{
				                    		$number=sprintf('%05d',getMaxID('id','tblpurchase_plan')+1);
				                    	}
				                    ?>
				                    <input type="text" name="number" class="form-control" value="<?=$number ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" readonly>
				                  </div>
			                </div>

			                <?php $value = (isset($purchase) ? _d($purchase->date) : _d(date('Y-m-d')));?>
                  			<?php echo render_date_input('date','Ngày kế hoạch',$value); ?>

                  			<?php
		                      $value = (isset($purchase) ? $purchase->name : "");
		                      echo render_input('name', _l('Tên kế hoạch'), $value);
		                    ?>

		                    <?php 
		                        $value = (isset($purchase) ? $purchase->reason : "");
		                        echo render_textarea('reason','Lý do',$value,array(),array(),'','tinymce'); 
		                    ?>

		                    <?php
		                    	$status=array(array('id'=>'0','text'=>'Chưa duyệt'),array('id'=>'1','text'=>'Đã duyệt'));
		                        $value = (isset($purchase) ? $purchase->status : "0");
		                        echo render_select('status', $status, array('id','text'), 'Trạng thái', $value, array(), array(), '', '', false);
		                    ?>
		                    <button class="btn-tr btn btn-info mleft10 text-right pull-right invoice-form-submit">
						      <?php echo _l('submit'); ?>
						    </button>
				 		</div>
				 		<div class="col-md-8">
				 			<div class="row">
						   <div class="col-md-4">
						     <div class="form-group mbot25">
						     <label for="item_select">Sản phẩm</label>
						      <select class="selectpicker no-margin" data-width="100%" id="custom_item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                                    <option value=""></option>

                                    <?php foreach ($items as $item) { ?>
                                    <option value="<?php echo $item['id']; ?>" data-subtext="">(<?php echo $item['code']; ?>) <?php echo $item['name']; ?></option>
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

						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
								 <table class="table item-purchase items table-main-invoice-edit no-mtop">
								  <thead>
								   <tr>
								    <th></th>
								    <th  class="text-left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_description_new_lines_notice'); ?>"></i> <?php echo _l('invoice_table_item_heading'); ?></th>
								    <th  class="text-left"><?php echo _l('invoice_table_item_description'); ?></th>
								   <th class="text-left qty"><?php echo _l('Số lượng yêu cầu'); ?></th>
								   <th class="text-left"><?php echo _l('Số lượng hiện tại'); ?></th>
								   <th class="text-left"><?php echo _l('Số lượng an toàn'); ?></th>
								   <th class="text-left"><?php echo _l('Mức mua tối thiểu'); ?></th>
								   <th></th>
								 </tr>
								</thead>
								<tbody>
								 <tr class="main">
								  <td><input type="hidden" id="itemID" /></td>
								  <td>
								   <textarea class="form-control" placeholder="<?php echo _l('Sản phẩm'); ?>" readonly></textarea>
								 </td>
								 <td>
								   <textarea name="description" class="form-control" placeholder="<?php echo _l('Mô tả'); ?>" readonly></textarea>
								 </td>
								 <td>
								   <input type="number"  name="quantity" min="0" value="1" class="form-control" placeholder="<?php echo _l('Số lượng yêu cầu'); ?>">
								 </td>
								 <td>
								   <input type="number"  name="rate" class="form-control" placeholder="<?php echo _l('Số lượng hiện tại'); ?>" readonly>
								 </td>
								 <td>
								   <input type="number"  name="rate" class="form-control" placeholder="<?php echo _l('Số lượng an toàn'); ?>" readonly>
								 </td>
								 <td>
								   <input type="number"  name="rate" class="form-control" placeholder="<?php echo _l('Mức mua tối thiểu'); ?>" readonly>
								 </td>
								<td>
								<button type="button" id="btnAdd" onclick="createTrItem(); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
								</td>
								</tr>
								<?php if (isset($purchase) || isset($purchase->items)) {
								  foreach ($purchase->items as $item) {
								    $manual    = false;
								    $table_row = '<tr class="sortable item">';
								    $table_row .= '<td class="dragger">';
								    $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);
								    $amount = $item['rate'] * $item['qty'];
								    $amount = _format_number($amount);
								                                      // order input
								    $table_row .= '<input type="hidden" class="order" name="' . $items_indicator . '[' . $i . '][order]">';
								    $table_row .= '</td>';
								    $table_row .= '<td class="bold description"><textarea name="' . $items_indicator . '[' . $i . '][description]" class="form-control" >' . clear_textarea_breaks($item['description']) . '</textarea></td>';
								    $table_row .= '<td><textarea name="' . $items_indicator . '[' . $i . '][long_description]" class="form-control" >' . clear_textarea_breaks($item['long_description']) . '</textarea></td>';
								    $table_row .= '<td><input type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" data-quantity name="' . $items_indicator . '[' . $i . '][qty]" value="' . $item['qty'] . '" class="form-control">';
								    $unit_placeholder = '';
								    if(!$item['unit']){
								      $unit_placeholder = _l('unit');
								      $item['unit'] = '';
								    }
								    $table_row .= '<input type="text" placeholder="'.$unit_placeholder.'" name="'.$items_indicator.'['.$i.'][unit]" class="form-control input-transparent text-right" value="'.$item['unit'].'">';
								    $table_row .= '</td>';
								    $table_row .= '<td class="rate"><input type="text" data-toggle="tooltip" title="' . _l('numbers_not_formated_while_editing') . '" onblur="calculate_total();" onchange="calculate_total();" name="' . $items_indicator . '[' . $i . '][rate]" value="' . $item['rate'] . '" class="form-control"></td>';
								    $table_row .= '<td class="taxrate">' . $this->misc_model->get_taxes_dropdown_template('' . $items_indicator . '[' . $i . '][taxname][]', $invoice_item_taxes, 'invoice', $item['id'], true, $manual) . '</td>';
								    $table_row .= '<td class="amount">' . $amount . '</td>';
								    $table_row .= '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';
								    $table_row .= '</tr>';
								    if (isset($item['task_id'])) {
								      if (!is_array($item['task_id'])) {
								        $table_row .= form_hidden('billed_tasks['.$i.'][]', $item['task_id']);
								      } else {
								        foreach ($item['task_id'] as $task_id) {
								          $table_row .= form_hidden('billed_tasks['.$i.'][]', $task_id);
								        }
								      }
								    } else if (isset($item['expense_id'])) {
								      $table_row .= form_hidden('billed_expenses['.$i.'][]', $item['expense_id']);
								    }
								    echo $table_row;
								    $i++;
								  }
								}
								?>
								</tbody>
								</table>
								</div>
							</div>

						</div>

						<div class="col-md-8 col-md-offset-4">
                        <table class="table text-right">
                            <tbody>
                                <tr>
                                    <td><span class="bold"><?php echo _l('Số sản phẩm'); ?> :</span>
                                    </td>
                                    <td class="total">
                                        0
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

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
	console.log(itemList);

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
    var total = 0;
    var uniqueArray = 0;
    var isNew = false;
    var createTrItem = () => {

        if(!isNew) return;
        if( $('table.item-purchase tbody tr:gt(0)').find('input[value=' + $('tr.main').find('td:nth-child(1) > input').val() + ']').length ) {
            $('table.item-purchase tbody tr:gt(0)').find('input[value=' + $('tr.main').find('td:nth-child(1) > input').val() + ']').parent().find('td:nth-child(2) > input').focus();
            alert('Sản phẩm này đã được thêm, vui lòng lòng kiểm tra lại!');
            return;
        }
        var newTr = $('<tr class="sortable item"></tr>');
        
        var td1 = $('<td><input type="hidden" name="item[id][]" value="" /></td>');
        var td2 = $('<td class="dragger"></td>');
        var td3 = $('<td class="dragger"></td>');
        var td4 = $('<td><input type="number" name="item[quantity_required][]" value="" /></td>');
        var td5 = $('<td><input type="number" name="item[quantity_current][]" value="" readonly/></td>');
        var td6 = $('<td><input type="number" name="item[minimum_quantity][]" value="" readonly/></td>');
        var td7 = $('<td><input type="number" name="item[quantity_min][]" value="" readonly/></td>');

        td1.find('input').val($('tr.main').find('td:nth-child(1) > input').val());
        td2.text($('tr.main').find('td:nth-child(2) > textarea').val());
        td3.text($('tr.main').find('td:nth-child(3) > textarea').val());
        td4.find('input').val($('tr.main').find('td:nth-child(4) > input').val());
        td5.find('input').val($('tr.main').find('td:nth-child(5) > input').val());
        td6.find('input').val($('tr.main').find('td:nth-child(6) > input').val());
        td7.find('input').val($('tr.main').find('td:nth-child(7) > input').val());
        
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
        $(trItem).parent().parent().remove();
        total--;
        refreshTotal();
    };
    var refreshTotal = () => {
        $('.total').text(total);
    };
    $('#custom_item_select').change((e)=>{
        var id = $(e.currentTarget).val();

        var itemFound = findItem(id);
        if(typeof(itemFound) != 'undefined') {
            var trBar = $('tr.main');
            //console.log(trBar.find('td:nth-child(2) > input'));
            trBar.find('td:first > input').val(itemFound.id);
            trBar.find('td:nth-child(2) > textarea').text(itemFound.name);
            trBar.find('td:nth-child(3) > textarea').text(itemFound.description);
            trBar.find('td:nth-child(4) > input').val(1);
            trBar.find('td:nth-child(5) > input').val(0);
            trBar.find('td:nth-child(6) > input').val(0);
            trBar.find('td:nth-child(7) > input').val(0);

            isNew = true;
            $('#btnAdd').show();
        }
        else {
            isNew = false;
            $('#btnAdd').hide();
        }
    });

</script>

