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
        

          <h4 class="bold no-margin"><?php echo _l('purchase_constract_detail') ?></h4>
  <hr class="no-mbot no-border" />
  <div class="row">
    <div class="additional"></div>
    <div class="col-md-12">
       
        <ul class="nav nav-tabs profile-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#item_detail" aria-controls="item_detail" role="tab" data-toggle="tab">
                    <?php echo _l('purchase_constract_detail'); ?>
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
                        <a href="<?php echo admin_url('purchase_contracts/detail_pdf/' . $item->id . '?print=true') ?>" target="_blank" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="In" aria-describedby="tooltip652034"><i class="fa fa-print"></i></a>
                        <a href="<?php echo admin_url('purchase_contracts/detail_pdf/' . $item->id . '?pdf=true') ?>" target="_blank" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Xem PDF"><i class="fa fa-file-pdf-o"></i></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
                <div class="row">
                  <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">            
                    <?php
                    $lock = true;
                      // config
                    $attrs_not_select = array('data-none-selected-text' => _l('system_default_string'));
                    ?>
                    <!-- prefix_purchase_order -->
                    <div class="form-group">
                        <label for="number"><?php echo _l('purchase_constract_code'); ?></label>  
                                    
                        <input type="text" name="code" class="form-control" value="<?=$item->code ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" readonly>      
                    </div>
                    
                    <div class="form-group">
                        <label for="id_order"><?php echo _l('orders_code') ?></label>
                        <input type="text" class="form-control" value="<?php echo $item->code_order ?>" readonly>
                    </div>

                    <?php
                        $default_date_create = date("Y-m-d", strtotime($item->date_create));
                        echo render_date_input( 'date_create', 'project_datecreated' , $default_date_create , array('readonly'=>'readonly')); 
                    ?>

                    <?php
                        $shipping_terms = (isset($item) ? $item->shipping_terms : "");
                        echo render_textarea('shipping_terms', 'Điều khoản vận chuyển', $shipping_terms, array(), array(), '', 'tinymce');
                    ?>
                    <?php 
                        $default_supplier = $item->id_supplier;
                        echo render_select('id_supplier', $suppliers, array('userid', 'company'), 'suppliers', $default_supplier, array('disabled'=>'disabled'));
                    ?>
                    <?php 
                        $default_currency = $item->currency_id;
                        echo render_select('currency_id', $currencies, array('id', 'name'), 'currency', $default_currency, array('disabled'=>'disabled'));
                    ?>

                    <?php
                        $terms_of_sale = (isset($item) ? $item->terms_of_sale : "");
                        echo render_textarea('terms_of_sale', 'Điều khoản thanh toán', $terms_of_sale, array(), array(), '', 'tinymce');
                    ?>
                </div>

                
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <?php if (isset($contract_merge_fields)) { ?>
                        <p class="bold mtop10"><a href="#" onclick="slideToggle('.avilable_merge_fields'); return false;"><?php echo _l('available_merge_fields'); ?></a></p>
                        <div class=" avilable_merge_fields mtop15 hide">
                        <ul class="list-group">
                            <?php
                            foreach ($contract_merge_fields as $field) {
                                foreach ($field as $f) {
                                    echo '<li class="list-group-item"><b>' . $f['name'] . '</b>  <a href="#" class="pull-right" onclick="insert_merge_field(this); return false">' . $f['key'] . '</a></li>';
                                }
                            } ?>
                        </ul>
                        </div>
                        <?php 
                    } ?>
                    <div class="form-group" >
                        <label for="template" class="control-label">Mẫu hợp đồng</label>
                        <textarea id="template" name="template" class="form-control" rows="50"><?php echo (isset($item) ? $item->template : "")?></textarea>
                    </div>
                </div>
                
                
                
                <!-- Edited -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <!-- Cusstomize from invoice -->
                    <div class="panel-body mtop10">
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
                                            <?php echo render_select('items['.$i.'][currency]', $currencies, array('id', 'name'), '', $value['currency_id'], array('disabled'=>'disabled')); ?>
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
                
              </div>
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
    $(()=>{
        tinymce.activeEditor.setMode('readonly');
        var contract_id = '<?php echo $item->id; ?>';
        var activeSave = true;
        tinymce.init({
            selector: 'textarea#template',
            theme: 'modern',
            skin: 'perfex',
            relative_urls: false,
            remove_script_host: false,
            inline_styles : false,
            verify_html : false,
            cleanup : false,
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
                        let buttonSave = this;
                        data.contract_id = contract_id;
                        data.template = editor.getContent();
                        buttonSave.disabled(true);
                        $.post(admin_url + 'purchase_contracts/save_contract_data', data).done(function(response) {
                            response = JSON.parse(response);
                            if (response.success == true) {
                                alert_float('success', response.message);
                                // setTimeout(() => {
                                //     location.reload();
                                // }, 2000);
                            }
                            else
                                alert_float('danger', 'Cập nhật thất bại');
                            buttonSave.disabled(false);
                        }).fail(function(error){
                            var response = JSON.parse(error.responseText);
                            alert_float('danger', response.message);
                            buttonSave.disabled(false);
                        });
                    },
                });
            },
        });
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
    
</script>
</body>
</html>
