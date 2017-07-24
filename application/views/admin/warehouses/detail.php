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
        

          <h4 class="bold no-margin"><?php echo (isset($item) ? _l('purchase_suggested_edit_heading') : _l('purchase_suggested_add_heading')); ?></h4>
  <hr class="no-mbot no-border" />
  <div class="row">
    <div class="additional"></div>
    <div class="col-md-12">
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
            
            <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'client-form', 'autocomplete' => 'off')); ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">            
                        <?php
                        // config
                        $attrs_not_select = array('data-none-selected-text' => _l('system_default_string'));
                        ?>
                        
                        <div class="form-group">
                            <label for="code" class="control-label"><?php echo _l('warehouse_code')?></label>
                            <p class="form-control-static"><?php echo $warehouse->code?></p>
                        </div>

                        <div class="form-group">
                            <label for="code" class="control-label"><?php echo _l('warehouse_name')?></label>
                            <p class="form-control-static"><?php echo $warehouse->warehouse?></p>
                        </div>
                        
                        <div class="form-group" >
                            <label for="address" class="control-label"><?php echo _l('lead_address') ?></label>
                            <textarea readonly="readonly" id="address" name="address" class="form-control" rows="4"><?php echo $warehouse->address?></textarea>
                        </div>

                    </div>
                </div>
                <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <!-- Cusstomize from invoice -->
                    <div class="panel-body mtop10">
                        <div class="table-responsive s_table">
                            <table class="table items item-purchase no-mtop">
                                <thead>
                                    <tr>
                                        <th width="" class="text-left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_name'); ?>"></i> <?php echo _l('item_code'); ?></th>
                                        <th width="" class="text-left"><?php echo _l('item_name'); ?></th>
                                        <th width="" class="text-left"><?php echo _l('item_unit'); ?></th>
                                        <th width="" class="text-left"><?php echo _l('item_remaining_amount'); ?></th>
                                        <th width="" class="text-left"><?php echo _l('item_remaining_value'); ?></th>
                                        <th width="" class="text-left"><?php echo _l('minimum_quantity'); ?></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr class="main">

                                    </tr>
                                    <?php
                                    $i=0;
                                    $totalPrice=0;
                                    foreach($warehouse->detail as $value) {
                                        ?>
                                    <tr class="sortable item">
                                        <td><?php echo $value->id ?></td>
                                        <td><?php echo $value->name?></td>
                                        <td><?php echo $value->unit?></td>
                                        <td><?php echo $value->product_quantity?></td>
                                        <td><?php echo number_format($value->product_quantity * $value->price_buy) ?> VNĐ</td>
                                        <td><?php echo $value->minimum_quantity?></td>
                                    </tr>
                                    <?php
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
                                        <td class="">
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
</script>
</body>
</html>
