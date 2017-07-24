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
                        <th width="" class="text-left"><?php echo _l('minimum_quantity'); ?></th>
                        <th width="" class="text-left"><?php echo _l('item_remaining_amount'); ?></th>
                        <th width="" class="text-left"><?php echo _l('item_remaining_value'); ?></th>
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
                        <td><?php echo $value->minimum_quantity?></td>
                        <td><?php echo $value->product_quantity?></td>
                        <td><?php echo number_format($value->product_quantity * $value->price_buy) ?> VNĐ</td>
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
                    <!-- <tr>
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
                    </tr> -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- End Customize from invoice -->
</div>