<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <!-- <h4 class="modal-title">--><?php //echo $call_logs->name; ?><!--</h4>-->
    <h4 class="modal-title"><?php echo $call_logs->name; ?></h4>
</div>
<?php echo form_open('admin/calllogs/update_or_add_call_logs/'.$id.'?idlead='.$idlead,array('id'=>'form-call-logs','novalidate'=>'novalidate')); ?>
<div class="modal-body">
    <div class="row">

        <!--Right-->
        <div class="col-md-5 task-single-col-left">
            <div class="pull-right mbot10 task-single-menu task-menu-options">
                <div class="content-menu hide">
                </div>
            </div>
            <h4 class="task-info-heading"><i class="fa fa-info-circle" aria-hidden="true"></i> <?php echo "Thông tin nhật Ký cuộc gọi" ?></h4>
            <div class="clearfix"></div>
            <div class="task-info task-status">
                <!--status-->
                <h5>
                    <i class="fa fa-<?php if($call_logs->checkout == 0){echo 'star';} else if($call_logs->checkout == 1){echo 'star-o';} else {echo 'star-half-o';} ?> pull-left task-info-icon"></i>
                    <?php echo _l('task_status');?> :
                    <?php if($call_logs->checkout == 1){echo 'Đồng ý hẹn';} else if($call_logs->checkout == 0){echo 'Không Đồng ý hẹn';} else echo ""?>
                </h5>
                <!-- end status-->
            </div>
            <!-- ngay-->
            <div class="text-muted task-info">
                <h5><i class="fa task-info-icon fa-calendar-plus-o pull-left fa-margin"></i>
                    <?php echo _l('lead_people_date_call'); ?>: <?php echo _d($call_logs->date_call); ?>
                </h5>
            </div>
            <div class="text-danger task-info">
                <h5><i class="fa task-info-icon fa-calendar-plus-o pull-left fa-margin"></i>
                    <?php echo _l('lead_time_width'); ?>: <?php echo $call_logs->time_width; ?>
                </h5>
            </div>
            <?php if($call_logs->checkout==0){?>
                <h4 class="bold th font-medium mbot15 pull-left">Lý do</h4>
                <div class="clearfix"></div>
                <div class="text-muted no-margin tc-content" id="task_view_description" spellcheck="false" style="position: relative;">
                    <p><?=$call_logs->reason?></p>
                </div>
            <?php } ?>
            <?php if($call_logs->checkout==1){?>
                <h4 class="bold th font-medium mbot15 pull-left">Địa điểm hẹn gặp</h4>
                <div class="clearfix"></div>
                <div class="text-muted no-margin tc-content" id="task_view_description" spellcheck="false" style="position: relative;">
                    <p><?=$call_logs->address?></p>
                </div>
                <h4 class="bold th font-medium mbot15 pull-left">Tài liệu cần chuẩn bị</h4>
                <div class="clearfix"></div>
                <div class="text-muted no-margin tc-content" id="task_view_description" spellcheck="false" style="position: relative;">
                    <p><?=$call_logs->document?></p>
                </div>
            <?php } ?>
            <h4 class="bold th font-medium mbot15 pull-left">Ghi chú nhật ký</h4>
            <div class="clearfix"></div>
            <div class="text-muted no-margin tc-content" id="task_view_description" spellcheck="false" style="position: relative;">
                <p><?=$call_logs->note?></p>
            </div>
            <!--End Right-->
        </div>
        <div class="col-md-7 task-single-col-right">
            <div class="clearfix"></div>
            <label for="name" class="control-label">Đồng ý hẹn gặp</label>
            <div class="checkbox">
                <input type="checkbox" name="checkout" id="checkout" data-toggle="collapse" data-target="#call_logs_true" value="<?php echo $value = (isset($call_logs) ? $call_logs->checkout : '0');?>" <?php if($call_logs->checkout==1)echo  "checked aria-expanded='true'"?> onchange="kiemtra(this.value)">
                <label></label>
            </div>
            <script>
                function kiemtra(status)
                {
                    if(status==0){
                        jQuery('#checkout').val(1);
                    }
                    else {
                        jQuery('#checkout').val(0);
                    }
                }
            </script>
            <div class="clearfix"></div>
            <div class="task_users_wrapper">
                <?php $selected= explode(",", $call_logs->assigned);?>
                <h4 class="task-info-heading mbot15"><i class="fa fa-users" aria-hidden="true"></i> <?php echo _l('task_single_followers'); ?></h4>
                <?php echo render_select('assigned[]',$call_assignees,array('staffid',array('firstname','lastname')),'',$selected,array('multiple'=>true),array(),'','',false); ?>
            </div>
            <hr class="task-info-separator" />
            <div class="clearfix"></div>

            <?php $value = (isset($call_logs) ? $call_logs->time_width : ''); ?>
            <?php echo  render_datetime_input('time_width','lead_time_width',$call_logs->time_width,array('data-date-start-date'=>date('Y-m-d')));?>
            <div class="clearfix"></div>
            <div id="call_logs_true" <?php  if($call_logs->checkout==1) echo 'class="collapse in" aria-expanded="true"'; else echo 'class="collapse" aria-expanded="false" style="height:0px;"'; ?> >
                <?php $value = (isset($call_logs) ? $call_logs->time_width : ''); ?>
                <?php echo  render_datetime_input('time_report','time_report',$call_logs->time_width,array('data-date-start-date'=>date('Y-m-d')));?>
                <div class="clearfix"></div>
                <?php $value = (isset($call_logs) ? $call_logs->address : ''); ?>
                <?php echo render_input('address','call_logs_address',$value); ?>
                <div class="clearfix"></div>
                <?php $value = (isset($call_logs) ? $call_logs->document : ''); ?>
                <?php echo render_textarea('document','lead_document',$value); ?>
            </div>
            <div id="call_logs_flase" <?php  if($call_logs->checkout==0) echo 'class="collapse" aria-expanded="false"  style="height:0px;"'; else echo 'class="collapse in" aria-expanded="true"'; ?> >
                <?php $value = (isset($call_logs) ? $call_logs->reason : ''); ?>
                <?php echo render_textarea('reason','lead_reason',$value); ?>
            </div>
        </div>
        <div class="col-md-12">
            <?php $value = (isset($call_logs) ? $call_logs->note : ''); ?>
            <?php echo render_textarea('note','call_logs_note',$value); ?>
        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default close-call-logs-modal" data-dismiss="modal" aria-label="Close"><?php echo _l('close'); ?></button>
    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
</div>
<?php echo form_close(); ?>
<script>
    var inner_popover_template = '<div class="popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>';
    init_tags_inputs();
    $('.task-menu-options .trigger').popover({
        html: true,
        placement: "bottom",
        trigger: 'click',
        title:'<?php echo _l('actions'); ?>',
        content: function() {
            return $('body').find('.task-menu-options .content-menu').html();
        },
        template: inner_popover_template
    });

    $('.task-menu-status .trigger').popover({
        html: true,
        placement: "bottom",
        trigger: 'click',
        title:'<?php echo _l('task_status'); ?>',
        content: function() {
            return $('body').find('.task-menu-status .content-menu').html();
        },
        template: inner_popover_template
    });

    tinyMCE.remove('#task_view_description');

    if(typeof(Dropbox) != 'undefined'){
        document.getElementById("dropbox-chooser-task").appendChild(Dropbox.createChooseButton({
            success: function(files) {
                $.post(admin_url+'tasks/add_external_attachment',{files:files,task_id:'<?php echo $task->id; ?>',external:'dropbox'}).done(function(){
                    init_task_modal('<?php echo $task->id; ?>');
                });
            },
            linkType: "preview",
            extensions: allowed_files.split(','),
        }));
    }
    init_selectpicker();
    init_datepicker();
    include_lightbox();
    init_lightbox({positionFromTop:120});
    include_chart_js();

    if (typeof(taskAttachmentDropzone) != 'undefined') {
        taskAttachmentDropzone.destroy();
    }

    taskAttachmentDropzone = new Dropzone("#task-attachment", {
        autoProcessQueue: true,
        createImageThumbnails: false,
        dictDefaultMessage:drop_files_here_to_upload,
        dictRemoveFile:remove_file,
        dictFileTooBig: file_exceds_maxfile_size_in_form,
        dictMaxFilesExceeded:you_can_not_upload_any_more_files,
        maxFilesize: max_php_ini_upload_size.replace(/\D/g, ''),
        dictFallbackMessage: browser_not_support_drag_and_drop,
        addRemoveLinks: false,
        previewTemplate: '<div style="display:none"></div>',
        maxFiles: 10,
        acceptedFiles: allowed_files,
        error: function(file, response) {
            alert_float('danger', response);
        },
        sending: function(file, xhr, formData) {
            formData.append("taskid", '<?php echo $task->id; ?>');
        },
        success: function(files, response) {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                init_task_modal('<?php echo $task->id; ?>');
            }
        }
    });
</script>
