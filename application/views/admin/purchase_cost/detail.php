<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
 <div class="content">
   <div class="row">

  <div class="col-md-12">
   <div class="panel_s">
     <div class="panel-body">
        <?php if (isset($purchase_cost)) { ?>
        <?php echo form_hidden('isedit'); ?>
        <?php echo form_hidden('itemid', $purchase_cost->id); ?>
      <div class="clearfix"></div>
        <?php 
    } ?>
        <!-- Product information -->
        

        <h4 class="bold no-margin"><?php echo (isset($purchase_cost) ? _l('cost_edit_heading') : _l('cost_add_heading')); ?></h4>
  <hr class="no-mbot no-border" />
  <div class="row">
    <div class="additional"></div>
    <div class="col-md-12">
        <?php
         if(isset($purchase_cost))
            {
                if($purchase_cost->status==0)
                {
                    $type='warning';
                    $status='Chưa duyệt';
                }
                elseif($purchase_cost->status==1)
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
                $status='Phiếu chi mới';
            }

        ?>
        <div class="ribbon <?=$type?>"><span><?=$status?></span></div>
        <ul class="nav nav-tabs profile-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#item_detail" aria-controls="item_detail" role="tab" data-toggle="tab">
                    <?php echo _l('purchase_cost_information'); ?>
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
                        <?php if( isset($purchase_cost) ) { ?>
                        <a href="<?php echo admin_url('purchase_cost/detail_pdf/' . $purchase_cost->id . '?print=true') ?>" target="_blank" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="In" aria-describedby="tooltip652034"><i class="fa fa-print"></i></a>
                        <a href="<?php echo admin_url('purchase_cost/detail_pdf/' . $purchase_cost->id  ) ?>" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Xem PDF"><i class="fa fa-file-pdf-o"></i></a>
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
                    
                    <div class="form-group">
                        <label for="number"><?php echo _l('cost_code'); ?></label>
                        <?php
                        if(!isset($purchase_cost)) {
                        ?>
                        <div class="input-group">
                        <span class="input-group-addon">
                            <?php
                            echo get_option('prefix_purchase_cost');
                            ?>
                        </span>
                        <?php
                        }
                        ?>
                        <?php 
                            if($purchase_cost)
                            {

                                $number=$purchase_cost->code;
                            }
                            else
                            {
                                $number=sprintf('%05d',getMaxID('id','tblpurchase_costs')+1);
                            }
                        ?>
                        <input type="text" name="code" class="form-control" value="<?=$number ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" readonly>
                        <?php if(!isset($purchase_cost)) { ?>
                        </div>
                        <?php } ?>
                    </div>
                <?php
                    $default_date = ( isset($purchase_cost) ? _d($purchase_cost->date_created) : _d(date('Y-m-d')));
                    echo render_date_input( 'date', 'project_datecreated' , $default_date , 'date'); 
                ?>
                <?php
                    echo render_input("unit_shipping_name", "Tên đơn vị vận chuyển", 0, 'number');
                ?>
                <?php
                    echo render_input("unit_shipping_address", "Địa chỉ đơn vị vận chuyển", 0, 'number');
                ?>
                <?php
                    echo render_input("unit_shipping_unit", "Đối tác", 0, 'number');
                ?>
                <?php 
                $note = (isset($purchase_cost) ? $purchase_cost->note : "");
                echo render_textarea('note', 'sumary_note', $note, array(), array(), '', 'tinymce');
                ?>
                </div>
                
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?php
                    echo render_input("costValue", "Chi phí", 0, 'number');
                ?>
                <?php
                $costType = array(
                    array(
                        'id' => 1,
                        'value' => 'Giá trị'
                    ),
                    array(
                        'id' => 2,
                        'value' => 'Số lượng'
                    ),
                );
                echo render_select("costType", $costType, array('id', 'value'), 'Phân bổ theo:');
                ?>
                <?php 
                $note = ("");
                echo render_textarea('costNote', str_replace(':', "",_l('invoice_note'))." chi phí:", $note, array(), array(), '', '');
                ?>
                
                <button type="button" id="btnAdd" class="btn btn-primary">Thêm</button>
                
                </div>
                

                <!-- Edited -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <!-- Cusstomize from invoice -->
                    <div class="panel-body mtop10">
                        <div class="table-responsive s_table">
                            <table class="table items item-export no-mtop">
                                <thead>
                                    <tr>
                                        <th width="20%" class="text-left"><?php echo _l('Phân bố theo'); ?></th>
                                        <th width="30%" class="text-left"><?php echo _l('Chi phí'); ?></th>
                                        <th width="40%" class="text-left"><?php echo _l('Ghi chú'); ?></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-8 col-md-offset-4">
                            <table class="table text-right">
                                <tbody>
                                    <tr>
                                        <td><span class="bold"><?php echo _l('Số chi phí'); ?> :</span>
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
                    <?php if(isset($purchase_cost) && $purchase_cost->status != 1 || !isset($purchase_cost)) { ?>
                    <button class="btn btn-info mtop20 only-save customer-form-submiter" style="margin-left: 15px">
                        <?php echo _l('submit'); ?>
                    </button>
                    <?php } ?>
                </div>
                <!-- End edited -->
                
                
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
    var stt = 0;
    $(()=>{
        /**
         * costValue
         * costType
         * note
         */
        var costValue = $('#costValue');
        var costType = $('#costType');
        var costNote = $('#costNote');

        $('#costValue, #costType, #note').removeAttr('name');
        $('#btnAdd').click(() => {
            if(costValue.val() == '' || costValue.val() <= 0) {
                alert_float('danger', 'Chi phí không hợp lệ!');
                return;
            }
            if(costType.val() == '' || costType.val() <= 0) {
                alert_float('danger', 'Vui lòng chọn phân bố!');
                return;
            }
            stt++;
            var newTr = $('<tr></tr>');
            var inputCostValue = $('<input type="hidden" name="items['+stt+'][cost_value]" value="'+costValue.val()+'" />')
            var tdCostValue = $('<td></td>');
            var inputCostType = $('<input type="hidden" name="items['+stt+'][cost_type]" value="'+costType.val()+'" />')
            var tdCostType = $('<td></td>');
            var inputCostNote = $('<input type="hidden" name="items['+stt+'][cost_note]" value="'+costNote.val()+'" />')
            var tdCostNote = $('<td style="text-align: left"></td>');
            
            // 
            console.log(costNote);
            tdCostValue.text(costValue.val() + inputCostValue.html());
            tdCostType.text(costType.find('option:selected').text() + inputCostType.html());
            tdCostNote.text(costNote.val() + inputCostNote.html());

            // Add td items to Tr
            newTr.append(tdCostType);
            newTr.append(tdCostValue);
            
            newTr.append(tdCostNote);
            stt++;

            $('table.item-export tbody').append(newTr);
        });
    });
</script>
</body>
</html>
