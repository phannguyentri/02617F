<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                    <?php echo form_open($this->uri->uri_string()); ?>
                    <h4 class="bold no-margin">Điều chỉnh số lượng kho</h4>
                    <hr class="no-mbot no-border">
                    <?php echo form_close(); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="panel_s">
                    <div class="panel-body">
                    <?php render_datatable(array(
                            _l('#'),
                            _l('Kho'),
                            _l('Loại kho'),
                            _l('Sản phẩm'),
                            _l('Số lượng'),
                            _l('options')
                        ),'warehouses_products'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    $(function(){
        initDataTable('.table-warehouses_products', window.location.href, [1], [1]);
    });
</script>