<?php init_head(); ?>
<div id="wrapper">
    <div class="content email-templates">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="<?=admin_url()?>sms/template_sms_detail" class="btn btn-info mright5 test pull-left display-block">Thêm mẫu SMS mới </a>
                            <div class="col-md-12">
                                <hr />
                                <h4 class="bold well email-template-heading"><?php echo _l('Mẫu email'); ?></h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <?php foreach($template_sms as $rom){?>
                                                <tr>
                                                    <td style="width: 90%" class="">
                                                        <a href="<?=admin_url()?>sms/template_sms_detail/<?=$rom['id']?>"><?=$rom['name']?></a>
                                                    </td>
                                                    <td><?=icon_btn('sms/delete_sms_template/' . $rom['id'], 'remove', 'btn-danger _delete');?></td>
                                                </tr>
                                            <?php }?>
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
</div>
<?php init_tail(); ?>
</body>
</html>
