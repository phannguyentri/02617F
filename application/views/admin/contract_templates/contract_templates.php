<?php init_head(); ?>
<div id="wrapper">
<div class="content contract-templates">
    <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                        <h4 class="bold no-margin font-medium"><?php echo _l('contract_templates'); ?></h3>
                        <hr />
                            <h4 class="bold well contract-template-heading"><?php echo _l('contract_template_sale'); ?></h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('contract_templates_table_heading_name'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($tickets as $ticket_template){ ?>
                                        <tr>
                                            <td class="<?php if($ticket_template['active'] == 0){echo 'text-throught';} ?>">
                                                <a href="<?php echo admin_url('contracts/contract_template/'.$ticket_template['contracttemplateid']); ?>"><?php echo $ticket_template['name']; ?></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            <div class="col-md-12">
                            <hr />
                            <h4 class="bold well contract-template-heading"><?php echo _l('contract_template_purchase'); ?></h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('contract_templates_table_heading_name'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($estimate as $estimate_template){ ?>
                                        <tr>
                                            <td class="<?php if($estimate_template['active'] == 0){echo 'text-throught';} ?>">
                                                <a href="<?php echo admin_url('contracts/contract_template/'.$estimate_template['contracttemplateid']); ?>"><?php echo $estimate_template['name']; ?></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            
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
