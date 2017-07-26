<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <!-- Cusstomize from invoice -->
    <div class="panel-body mtop10">
        <div class="row">
            <div class="col-lg-3 col-xs-12 col-md-12 total-column">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="text-muted _total">
                            <?php echo count($product_category) ?>         </h3>
                        <span class="text-warning">Số danh mục chứa sản phẩm</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-12 col-md-12 total-column">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="text-muted _total">
                            <?php echo count($products_in_warehouse) ?>         </h3>
                        <span class="text-danger">Tổng sản phẩm</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-12 col-md-12 total-column">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="text-muted _total">
                            <?php echo $product_outof_date ?>         </h3>
                        <span class="text-success">Sản phẩm tồn quá hạn</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-12 col-md-12 total-column">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3 class="text-muted _total">
                            <?php echo $product_low_quantity ?>         </h3>
                        <span class="text-primary">Sản phẩm dưới định mức</span>
                    </div>
                </div>
            </div
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?php 
                    echo render_select('detail_categories', $categories, array('id', 'category'), 'als_list');
                ?>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?php echo render_select('detail_products', $products_in_warehouse, array('id', 'name'), 'als_products'); ?>
            </div>
        </div>
        <?php render_datatable(array(
                _l('ID'),
                _l('item_code'),
                _l('item_name'),
                _l('item_unit'),
                _l('minimum_quantity'),
                _l('item_remaining_amount'),
                _l('item_remaining_value'),
                // _l('options')
            ),'warehouse-detail'); ?>
    </div>
    <!-- End Customize from invoice -->
</div>
<script>
     
</script>