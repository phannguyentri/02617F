<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                    <?php echo form_open($this->uri->uri_string()); ?>
                    <h4 class="bold no-margin">Danh sách sản phẩm trong kho</h4>
                    <hr class="no-mbot no-border">
                    <?php echo form_close(); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="panel_s">
                    <div class="panel-body">
                    <?php render_datatable(array(
                            _l('id'),
                            _l('Đơn vị'),
                            _l('options')
                        ),'units'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>