<?php init_head(); ?>
<style>
    .ui-widget.ui-widget-content {
        border: 1px solid #ccc!important;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <form enctype='multipart/form-data' role="form" method="post"  id="form-email"  name="form-email" action="<?php echo admin_url('email_marketing')?>">
                <div class="col-md-6 form-send">
                    <div class="panel_s">
                        <div class="panel-body">

                            <div style="text-align: right"><a onclick="full_col()"><i class="fa fa-expand"></i></a></div>
                            <h1><a href="#" title="CRM">Email Marketing</a></h1>
                            <h2></h2>

                            <?php
                            if(isset($message_display)){?>
                                <div class="alert alert-info">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>info!</strong> <?php echo $message_display; ?>
                                </div>
                            <?php     }
                            ?>
                            <?php
                            $tb="";
                            $class="";
                            $value_email="";
                            $value_pass_email="";
                            $get_email=get_table_where('tblstaff',array('staffid'=>get_staff_user_id()));?>
                            <?php if($get_email!=array()){
                                $value_email=$get_email[0]['email_marketing'];
                                $value_pass_email=$get_email[0]['password_email_marketing'];
                                $tb=_l('used_email_user').': '.$value_email;
                                $class='success';
                            }?>
                            <?php

                                if($value_email==""||$value_pass_email=="")
                                {

                                    $value_email=get_option('smtp_email');
                                    $value_pass_email=get_option('smtp_password');
                                    $tb=_l('used_email_setting').' '.$value_email;
                                    $class='success';
                                }
                                if($value_email==""||$value_pass_email=="")
                                {
                                    $tb=_l('null_email');
                                    $class='danger';
                                }
                                echo '
                                    <div class="alert alert-'.$class.'">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>'._l('tb').'</strong>: '.$tb.'
                                    </div>';
                            ?>
                            <div class="form-group" style="display: none;">
                                <label for="user_email">Email của bạn:</label>
                                <input type="email" class="form-control" id="user_email" name="user_email" value="<?=$value_email?>" placeholder="Nhập email của bạn..." required>
                            </div>
                            <div class="form-group" style="display: none;">
                                <label for="user_password">Password:</label>
                                <input type="password" class="form-control" id="user_password" name="user_password" value="<?=$value_pass_email?>" placeholder="Nhập mật khẩu của bạn..." required>
                            </div>
                            <div class="form-group">
                                <label for="">Email người nhận:</label>
                                <input type="text" id="email" name="email" class="tagemail form-control" placeholder="Email người nhận"  data-role="tagsinput">
                            </div>
                            <div class="form-group email_CC" style="display: none">
                                <label for="">Email người nhận CC:</label>
                                <input type="text" id="email_to_cc" name="email_to_cc" class="tagemail form-control" placeholder="Email người nhận CC" value="" data-role="tagsinput" onchange="review_null(2)">
                            </div>
                            <div class="form-group email_BC" style="display: none">
                                <label for="">Email người nhận BCC:</label>
                                <input type="text" id="email_to_bc" name="email_to_bc" class="tagemail form-control" placeholder="Email người nhận BCC" value="" data-role="tagsinput" onchange="review_null(3)">
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="button" class="btn  btn-lg btn-primary" data-toggle="modal" data-target="#email_list"><i class="glyphicon glyphicon-duplicate"></i> Khách hàng</button>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <div class="btn-group">
                                        <button type="button" class="btn  btn-lg btn-success"  onclick="change_data(2)">CC</button>
                                        <button type="button" class="btn btn-lg btn-info"  onclick="change_data(3)">BCC</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="button" class="btn  btn-lg btn-primary" onclick="excel_file()">
                                        <i class="glyphicon glyphicon-open-file"></i> Lấy danh sách email từ excel
                                    </button>
                                </div>
                                <div class="form-group">
                                    <div class="btn-group group_excel" style="display: none;">
                                        <button type="button" class="btn  btn-lg btn-success"  onclick="get_email_input(2)">CC</button>
                                        <button type="button" class="btn btn-lg btn-info"  onclick="get_email_input(3)">BCC</button>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="checkbox checkbox-success mbot20 no-mtop">
                                    <input type="checkbox" name="type_email" id="type_email" onchange="kiemtra_type_email(this.value)">
                                    <label for="type_email">Sử dụng template</label>
                                </div>
                            </div>
                            <div class="form-group">

                                <div id="email_list" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Khách hàng</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="panel_s">
                                                    <div class="panel-body">
                                                        <?php
                                                        $table_data = array();
                                                        $_table_data = array(
                                                            _l('customer_code'),
                                                            _l('customer_name'),
                                                            _l('company_primary_email'),
                                                            _l('customer_groups'),
                                                        );
                                                        foreach($_table_data as $_t){
                                                            array_push($table_data,$_t);
                                                        }
                                                        array_unshift($table_data,'<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="clients"><label></label></div>');

                                                        $custom_fields = get_custom_fields('customers',array('show_on_table'=>1));
                                                        foreach($custom_fields as $field){
                                                            array_push($table_data,$field['name']);
                                                        }

                                                        $table_data = do_action('customers_table_columns',$table_data);
                                                        render_datatable($table_data,'clients');
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" id="get_1" class="btn btn-primary" onclick="get_date(1)">lấy danh sách email</button>
                                                <button type="button" id="get_2" class="btn btn-primary" onclick="get_date(2)">lấy danh sách email gửi CC</button>
                                                <button type="button" id="get_3" class="btn btn-primary" onclick="get_date(3)">lấy danh sách email gửi BCC</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group select_template" style="display:none;">
                                <label for="view_template">Mẫu email:</label>
                                <?php echo render_select('view_template',$email_plate,array('id','name'),'','',array('onchange'=>'get_contentemail(this.value)','data-width'=>'100%','data-none-selected-text'=>_l('chọn Mẫu email'))); ?>
                            </div>
                            <div class="form-group">
                                <label for="subject">Chủ đề:</label>
                                <input type="text" class="form-control" id="subject" name="subject"  value="" placeholder="Nhập chủ đề của bạn..." required >
                            </div>
                            <div class="form-group">
                                    <p class="bold"><?php echo _l('email_content'); ?></p>
                                    <?php echo render_textarea('message','','',array('data-task-ae-editor'=>true),array(),'','tinymce-task'); ?>
                            </div>
                            <?php
                            if($value_email!=""||$value_pass_email!=""){
                            ?>
                                <div class="form-group" style="display: none;">
                                        <p class="bold"><?php echo _l('file'); ?></p>
                                        <?php echo render_textarea('file_send','','',array('data-task-ae-editor'=>true),array(),'',''); ?>
                                </div>
                            <?php }?>
                            <div class="checkbox checkbox-primary">
                                <input type="radio" name="type_send" onclick="not_send_next()" id="type_send_true" value="1" checked>
                                <label for="type_send_true">Gửi ngay</label>
                            </div>
                            <div class="checkbox checkbox-primary">
                                <input type="radio" name="type_send" onclick="not_send_next()" value="0" id="type_send_false">
                                <label for="type_send_false">Gửi sau</label>
                            </div>

                            <div id="send_next" class="collapse">
                              <?php echo render_datetime_input('date_send','Thời gian gửi',date('Y-m-d H:i:s'))?>
                            </div>

                    </form>
                            <div class="form-group file_dropzone"></div>
                        <div class="clearfix"></div>
                            <div class="form-group">
                                <?php echo form_open_multipart(admin_url('email_marketing/upload_file'),array('class'=>'dropzone','id'=>'email-upload','onchane'=>'get_delete(this)')); ?>
                                <input type="file" name="file" multiple />
                                <?php echo form_close(); ?>
                                <div class="text-right mtop15">
                                    <div id="dropbox-chooser"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo form_open_multipart(admin_url('email_marketing/get_email_to_excel'),array('id'=>'read-upload','onchane'=>'reading_upload(this)')); ?>
                                <input type="file" name="file_excel" style="display: none;" />
                                <?php echo form_close(); ?>
                            </div>
                            <div class="clearfix"></div>
                            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-envelope"></i> Gửi</button>
<!--                            <button type="button" class="btn btn-success" onclick="not_send_next()" data-toggle="collapse" data-target="#send_next">Gửi sau</button>-->
                            <button type="button" class="btn btn-info" onclick="view_template_email()"  data-toggle="modal" data-target="#view_email"><i class="glyphicon glyphicon-eye-open"></i> Xem trước</button>




                                <div id="view_email" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title view_title">Xem thử</h4>
                                            </div>
                                            <div class="modal-body">
                                                <center>
                                                    <div class="well view_well" style="width: 80%;">
                                                        <button type="button" class="btn btn-info" id="btn_left" style="float: left" onclick="war_email()"><i class="glyphicon glyphicon-arrow-left"></i></button>
                                                        <button type="button" class="btn btn-info" id="btn_right" style="float: right" onclick="war_email_next()"><i class="glyphicon glyphicon-arrow-right"></i></button>
                                                        <div class="clearfix"></div>
                                                        <center>

                                                        </center>
                                                    </div>
                                                </center>

                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                        </div>
                    </div>
                </div>
                <div class="col-md-6 form-code">
                    <div class="panel_s">
                        <div class="panel-body">
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

<?php init_tail(); ?>

<script>
    var excel_email=[];
    var this_email=0;
    function view_template_email()
    {

        var email =$('#email').val();
        var email_to_cc=$('#email_to_cc').val();
        var email_to_bc=$('#email_to_bc').val();
        var full_email=email+','+email_to_cc+','+email_to_bc;
        full_email.replace(',,',',');
        var res=full_email.split(",");
        res = $.grep(res, function(n, i){
            return (n !== "" && n != null);
        });
        if(this_email>=0)
        {
            email_view=res[this_email];
        }
        if(this_email==0){$('#btn_left').hide();}
        else {$('#btn_left').show();}

        if(this_email<res.length-1){$('#btn_right').show();}
        else {$('#btn_right').hide();}

        $('#view_email .view_title ').html("Xem thử Email:"+email_view);
        var content = tinymce.get("message").getContent();
        $.ajax({
            type: "post",
            url: "<?=admin_url()?>email_marketing/view_content",
            data: {email:email_view,content:content},
            dataType:"json",
            cache: false,
            success: function (data) {
                $('#view_email .modal-body .view_well center').html(data.content);
            }
        });
    }


    function not_send_next()
    {
        if($('#type_send_true').prop('checked'))
        {
            $('#date_send').val('');
            $('#send_next').collapse("hide");
            $('#date_send').removeAttr('required');

        }
        if($('#type_send_false').prop('checked'))
        {
            $('#date_send').prop("required", true);
            $('#send_next').collapse("show");
        }
    }
    function war_email()
    {
        if(this_email!=0)
        {
            this_email--;
        }
        view_template_email();
    }
    function war_email_next()
    {
        var email =$('#email').val();
        var email_to_cc=$('#email_to_cc').val();
        var email_to_bc=$('#email_to_bc').val();
        var full_email=email+','+email_to_cc+','+email_to_bc;
        full_email.replace(',,',',');
        var res=full_email.split(",");
        res = $.grep(res, function(n, i){
            return (n !== "" && n != null);
        });
        if(this_email<res.length-1)
        {
            this_email++;
        }
        console.log(res.length);
        console.log(this_email);
        view_template_email();
    }
</script>


<script>
    init_editor('.tinymce-task',{height:300});
    function kiemtra_type_email(id)
    {
        if($('#type_email').prop('checked')==true)
        {
            $('.select_template').show();
        }
        else
        {
            $('#view_template').val(0).selectpicker('refresh');
            $('.select_template').hide();
            var content = tinymce.get("message").setContent('');
        }
    }
    function get_contentemail(id)
    {
        jQuery.ajax({
            type: "post",
            url: "<?=admin_url()?>email_marketing/get_email/"+id,
            data: '',
            dataType:"json",
            cache: false,
            success: function (data) {
                var content = tinymce.get("message").setContent(data.content);
                $('#subject').val(data.subject);
            }
        });
    }

    var do_alert = setTimeout(function(){
        $('.alert.alert-info').hide();
    }, 3000);
    function clearAlert()
    {
        clearTimeout(do_alert);
    }

    var CustomersServerParams = {};
    var headers_clients = $('.table-clients').find('th');
    var not_sortable_clients = (headers_clients.length - 1);
    initDataTable('.table-clients', window.location.href, [not_sortable_clients,0], [not_sortable_clients,0], CustomersServerParams,<?php echo do_action('customers_table_default_order',json_encode(array(1,'ASC'))); ?>);

</script>
<script>

    $('.add_merge_field').on('click', function(e) {
        e.preventDefault();
        tinymce.activeEditor.execCommand('mceInsertContent', false, $(this).text());
    });
    $( document ).ready(function() {
        $('.ui-autocomplete-input').prop('placeholder','Email người nhận');
        $('.email_CC .ui-autocomplete-input').prop('placeholder','Email người nhận CC');
        $('.email_BC .ui-autocomplete-input').prop('placeholder','Email người nhận BCC');
        $('#file_send').val('');
    });

    function change_data(type)
    {
        if(type==1)
        {
            $('.modal-title').html('Khách hàng 1');
        }
        else
            if(type==2)
            {
                $('.email_CC').show();
            }
        else
            {
                if(type==3)
                {
                    $('.email_BC').show();

                }
            }
    }

    function review_null(type)
    {
    }
    function get_date(type)
    {
        var listid = [];
        $('input[name="listid[]"]:checked').each(function(a) {
            listid.push($(this).val());
        });
        jQuery.ajax({
            type: "post",
            url: "<?=admin_url()?>/email_marketing/get_email_client",
//            dataType: "json",
            data: {listid:listid},
            cache: false,
            success: function (data) {
                data = JSON.parse(data);
                $.each(data, function(key) {
                    if(type==1){
                            if(data[key].email!="")
                            {
                                $('#email').tagit('createTag',data[key].email);
                                $('#email_list').modal('hide');
                            }
                    }
                    else
                    {
                        if(type==2){
                            $('.email_CC').show();
                                if(data[key].email!="")
                                {
                                    $('#email_to_cc').tagit('createTag', data[key].email);
                                    $('#email_list').modal('hide');
                                }
                        }
                        else
                        {
                            $('.email_BC').show();
                                if(data[key].email!="")
                                {
                                    $('#email_to_bc').tagit('createTag',data[key].email);
                                    $('#email_list').modal('hide');
                                }
                        }

                    }
                })

            }
        });

    }
    Dropzone.options.clientAttachmentsUpload = false;
    if ($('#email-upload').length > 0) {
        new Dropzone('#email-upload', {
            paramName: "file",
            dictDefaultMessage:drop_files_here_to_upload,
            dictFallbackMessage:browser_not_support_drag_and_drop,
            dictRemoveFile:remove_file,
            dictFileTooBig: file_exceds_maxfile_size_in_form,
            dictMaxFilesExceeded:you_can_not_upload_any_more_files,
            maxFilesize: max_php_ini_upload_size.replace(/\D/g, ''),
            addRemoveLinks: false,
            accept: function(file, done) {
                done();
            },
            acceptedFiles: allowed_files,
            error: function(file, response) {
                alert_float('danger', response);
            },
            success: function(file, response) {

                var mang=$('#file_send').val();
                $('#file_send').val(mang+','+response);
                s_tring=$('#file_send').val()
                jQuery.ajax({
                    type: "post",
                    url: "<?=admin_url()?>email_marketing/tring_field",
                    data: {s_tring:s_tring},
                    cache: false,
                    success: function (data) {
                        debugger;
                        $('#file_send').val(data);
                    }
                });

                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    $('.dz-preview').remove();
                    $('.dz-default').show();
                    count=$('.c_file').length;
                    $('.file_dropzone').append('<div class="col-md-2 c_file" id="i_file-'+count+'" title='+response+'>'+response+' <a class="btn  btn-icon" onclick="delete_file('+count+')"><i class="glyphicon glyphicon-remove-circle"></i></a>' +
                        '<img src="<?=base_url()?>assets/images/document.png" style="height:100px">'
                        +'</div>')
                }
            }
        });
    }
    function delete_file(id)
    {
        name=$('#i_file-'+id).prop('title');
        jQuery('#i_file-'+id).remove();
        s_tring=$('#file_send').val()
        jQuery.ajax({
            type: "post",
            url: "<?=admin_url()?>email_marketing/tring_field",
            data: {s_tring:s_tring,name_remove:name},
            cache: false,
            success: function (data) {
                $('#file_send').val(data);
            }
        });
    }

    function full_col()
    {
        var form_send=$('.form-send').attr('class');
        if(form_send=='col-md-6 form-send')
        {
            $('.form-send').prop('class','col-md-12 form-send');
            $('.form-code').prop('class','col-md-6 form-code hide');
        }
        else
        {
            $('.form-send').prop('class','col-md-6 form-send');
            $('.form-code').prop('class','col-md-6 form-code');
        }

    }
    function excel_file()
    {
        $('input[name="file_excel"]').click();
    }
    $('input[name="file_excel"]').change(function() {
        form=$('#read-upload');
        att=form.attr("action") ;

        var file_data = $('input[name="file_excel"]').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file_excel', file_data);
        $.ajax({
            url: att,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                excel_email=data;
                $.each(excel_email, function( index, value ) {
                    $('#email').tagit('createTag',value.email);
                })
                email_not_null();

            }
        });
    });
    function get_email_input(type)
    {
        var reid="email";
        if(type==2)
        {reid="email_to_cc"}
        if(type==3)
        {reid="email_to_bc"}
        change_data(type);
        email_not_null();
        if(excel_email!=[])
        {
            $.each(excel_email, function( index, value ) {
                $('#'+reid).tagit('createTag',value.email);
            })
        }
        else
        {
            alert_float('warning','Không tìm thấy email từ excel');
        }
    }

    function email_not_null()
    {
        if(excel_email!=[])
        {
            $('.group_excel').show();
        }
        else
        {
            $('.group_excel').hide();
        }
    }


</script>
</body>
</html>
