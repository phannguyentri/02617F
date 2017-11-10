<?php init_head(); ?>
<div id="wrapper">
    <div class="content">

        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="bold no-margin font-medium">
                            <?php echo $title; ?>
                        </h4>
                        <hr />
                        <?php echo form_open($this->uri->uri_string()); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_input('name','Tên Mẫu',$template->name,'text'); ?>
                                <?php echo render_input('subject','template_subject',$template->subject); ?>
                                <hr />
                                <p class="bold"><?php echo _l('email_template_email_message'); ?></p>
                                <?php echo render_textarea('content','',$template->content,array('data-url-converter-callback'=>'myCustomURLConverter'),array(),'','tinymce tinymce-manual'); ?>
                                <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel_s">
                        <div class="panel-body">
                            <h4 class="bold no-margin font-medium">
                                <?php echo _l('available_merge_fields'); ?>
                            </h4>
                            <hr />
                            <div class="row">
                                    <div class=" col-md-12">
                                            <div class="alert alert-warning">
                                                <?php if($template->type == 'ticket'){
                                                    echo _l('email_template_ticket_warning');
                                                } else {
                                                    echo _l('email_template_contact_warning');
                                                } ?>
                                            </div>
                                    </div>
                                <div class="col-md-12">
                                    <div class="row available_merge_fields_container">

                                        <div class="col-md-6 merge_fields_col">
                                            <hr>
                                                <h5>Thông tin chung</h5>
                                            <hr>
                                            <?php foreach($field as $row=> $fi){?>
                                                 <p><?=_l('tblclients.'.$fi)?><span class="pull-right"><a href="#" class="add_merge_field">{tblclients.<?=$fi?>}</a></span></p>
                                            <?php }?>
                                        </div>

                                        <div class="col-md-6 merge_fields_col">
                                            <hr>
                                                <h5>Doanh nghiệp</h5>
                                            <hr>
                                            <?php foreach($field2 as $row2=> $fi2){?>
                                                <p><?=_l('tblclients.'.$fi2)?><span class="pull-right"><a href="#" class="add_merge_field">{tblclients.<?=$fi2?>}</a></span></p>
                                            <?php }?>
                                            <hr>
                                                <h5>Nhân viên</h5>
                                            <hr>
                                            <?php foreach($fieldstaff as $num=>$fis){?>
                                                  <p><?=_l('tblstaff.'.$fis)?><span class="pull-right"><a href="#" class="add_merge_field">{tblstaff.<?=$fis?>}</a></span></p>
                                            <?php }?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        init_editor('textarea[name="content"]',{height:200});
<!--        --><?php //foreach($editors as $id){ ?>
//        init_editor('textarea[name="content"]',{urlconverter_callback:'merge_field_format_url'});
//        <?php //} ?>
        var merge_fields_col = $('.merge_fields_col');
        // If not fields available
//        $.each(merge_fields_col, function() {
//            var total_available_fields = $(this).find('p');
//            if (total_available_fields.length == 0) {
//                $(this).remove();
//            }
//        });
        // Add merge field to tinymce
        $('.add_merge_field').on('click', function(e) {
            e.preventDefault();
            tinymce.activeEditor.execCommand('mceInsertContent', false, $(this).text());
        });
        _validate_form($('form'), {
            name: 'required',
        });
    });
</script>
</body>
</html>
