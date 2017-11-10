<?php init_head(); ?>
<div id="wrapper">
    <div class="content email-templates">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">

                                    <h4 class="bold well email-template-heading"><?php echo _l('Lịch sử gửi email'); ?></h4>
                                    <hr />
                                    <div class="clearfix"></div>
                                    <div class="row mbot15">
                                        <div class="col-md-12">
                                            <h3 class="text-success no-margin"><?=_l('information_log_email')?></h3>
                                        </div>
                                        <hr />
                                        <div class="col-lg-4 col-xs-12 col-md-12 total-column">
                                            <div class="panel_s">
                                                <div class="panel-body">
                                                    <h3 class="text-muted"><?=$count_group_email?></h3>
                                                    <span class="text-warning"><?=_l('count_group_email')?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-xs-12 col-md-12 total-column">
                                            <div class="panel_s">
                                                <div class="panel-body">
                                                    <h3 class="text-muted"><?=$count_email?></h3>
                                                    <span class="text-warning"><?=_l('count_email')?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-xs-12 col-md-12 total-column">
                                            <div class="panel_s">
                                                <div class="panel-body">
                                                    <h3 class="text-muted"><?=$count_email_view?></h3>
                                                    <span class="text-warning"><?=_l('count_email_view')?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />

                                    <div class="panel_s">
                                       <!--  <div class="col-md-6 mbot30">
                                            <?=render_select('filterCampaign',$campaign,array('id','name'),'campaign')?>
                                        </div> -->
                                        <div class="clearfix"></div>
                                        <div class="panel-body">
                                            <div class="clearfix"></div>
                                            <?php render_datatable(array(
                                                _l('#'),
                                                _l('Tiêu đề '),
                                                _l('Người gửi '),
                                                _l('Thời gian gửi email'),
                                                _l('Tình trạng')
                                                // _l('Chiến dịch')
                                            ),'log-email'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="modal fade" id="view-email" role="dialog">
                                    <div class="modal-dialog modal-lg">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a data-toggle="tab" id="view_table_to" href="#email-to">Email to </a></li>
                                                    <li><a data-toggle="tab" href="#email-cc" id="view_table_cc">Email CC </a></li>
                                                    <li><a data-toggle="tab" href="#email-bcc" id="view_table_bcc">Email BCC </a></li>
                                                </ul>

                                                <div class="tab-content">

                                                    <div id="email-to" class="modal-title email-to tab-pane fade in active">
                                                        <div class = "col-md-12">
                                                            <table class="table table-striped table-log-email-to dataTable no-footer dtr-inline">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Email</th>
                                                                        <th>Template</th>
                                                                        <th>Tình trạng</th>
                                                                        <th>Thời gian xem gần nhất</th>
                                                                        <th>Số lần xem</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div id="email-cc" class="modal-title email-cc tab-pane fade">
                                                        <div class = "col-md-12">
                                                            <table class="table table-striped table-log-email-cc dataTable no-footer dtr-inline">
                                                                <thead>
                                                                <tr>
                                                                    <th>Email</th>
                                                                    <th>Template</th>
                                                                    <th>Tình trạng</th>
                                                                    <th>Thời gian xem gần nhất</th>
                                                                    <th>Số lần xem</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div id="email-bcc" class="modal-title email-bcc tab-pane fade">
                                                        <div class = "col-md-12">
                                                            <table class="table table-striped table-log-email-bcc dataTable no-footer dtr-inline">
                                                                <thead>
                                                                <tr>
                                                                    <th>Email</th>
                                                                    <th>Template</th>
                                                                    <th>Tình trạng</th>
                                                                    <th>Thời gian xem gần nhất</th>
                                                                    <th>Số lần xem</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>



                                            </div>
                                            <div class="modal-body">
                                                <div class = "panel panel-success">
                                                    <div class = "panel-heading">
                                                        <h3 class = "panel-title subject-email"></h3>
                                                    </div>

                                                    <div class = "panel-body">
                                                        <div class="content_email"></div>
                                                    </div>
                                                </div>
                                                <div class = "col-md-4">
                                                    <div class=" file_email">

                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
</div>
<?php init_tail(); ?>
<script>
    var full_data="";
    function load_lish(id)
    {
        $.ajax({
            type: "post",
            url: "<?=admin_url()?>email_marketing/load_log_id",
            data: {id:id},
            dataType: "json",
            cache: false,
            success: function (data) {
                full_data =data;
                jQuery('.content_email').html(data.message);
                jQuery('.subject-email').html(data.subject);
                initDataTable('.table-log-email-to', admin_url + 'email_marketing/init_email_marketing/'+id, false, false, "", [0, 'DESC']);
                initDataTable('.table-log-email-cc', admin_url + 'email_marketing/init_email_marketing/'+id+'/1', false, false, "", [0, 'DESC']);
                initDataTable('.table-log-email-bcc', admin_url + 'email_marketing/init_email_marketing/'+id+'/2', false, false, "", [0, 'DESC']);
                if(data.file!="")
                {
                    $('.well').show();
                   name_file="";
                   var name= data.file.split(",");
                    for(i=0;i<name.length;i++)
                    {
                        name_file=name_file+'<i class="fa fa-paperclip" aria-hidden="true"></i> <a href="<?=base_url()?>uploads/email/'+name[i]+'" target="_blank">'+name[i]+'</a>';
                    }
                    jQuery('.file_email').html(name_file);
                }
                else
                {
                    $('.file_email').hide();
                }
            }
        });

    }
    var filterList = {
        'filterCampaign' : '[id="filterCampaign"]'
    };
    initDataTable('.table-log-email', window.location.href, [1], [1],filterList,[0,'DESC']);


    $.each(filterList, (filterIndex, filterItem) => {
        $('select' + filterItem).on('change', () => {
            console.log(filterItem);
            $('.table-log-email').DataTable().ajax.reload();
        });
    });
</script>

</body>
</html>
