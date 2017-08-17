<?php init_head(); ?>
<div id="wrapper">
<div class="content contract-templates">
    <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="row">
                    <h4 class="bold mleft15 font-medium"><?php echo _l('contract_templates'); ?></h4>
                    <?php foreach ($templates as $key => $template) { ?>
                        <div class="col-md-12">                        
                        <hr />
                            <h4 class="bold well contract-template-heading"><?=$template->name?></h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('contract_templates_table_heading_name'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($template->items as $item){  ?>
                                        <tr>
                                        <!-- <?php if($item->active == 0){echo 'text-throught';} ?> -->
                                            <td class="">
                                                <a href="<?php echo admin_url('contract_templates/contract_template/'.$item->id); ?>"><?php echo $item->name ?></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php } ?>  
                        <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
