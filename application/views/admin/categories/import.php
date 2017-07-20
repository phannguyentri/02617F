
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                    <?php echo form_open($this->uri->uri_string()); ?>
                    <?php echo form_hidden('download_sample','true'); ?>
                    <button type="submit" class="btn btn-success">Download Sample</button>
                    <hr />
                    <?php echo form_close(); ?>
                       <?php $max_input = ini_get('max_input_vars');
                       if(($max_input>0 && isset($total_rows_post) && $total_rows_post >= $max_input)){ ?>
                        <div class="alert alert-warning">
                            Your hosting provider has PHP setting <b>max_input_vars</b> at <?php echo $max_input;?>.<br/>
                            Ask your hosting provider to increase the <b>max_input_vars</b> setting to <?php echo $total_rows_post;?> or higher or import less rows.
                        </div>
                        <?php } ?>
                        <?php

                            if(!isset($simulate) > 0) { ?>
                        <p>Khi nhập sẽ bỏ qua hàng đầu tiên(vì có thể là tiêu đề). <br />
                        Danh mục nhập bắt buộc không được trùng với danh mục đã có.
                        </p>
                        
                        </div>
                        <?php } ?>

                        <?php if($this->session->userdata('query_array')) { ?>
                        
                        <div class="panel-body" style="margin-bottom: 20px">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h3>Xem trước cây danh mục chuẩn bị nhập</h3> <br />

                                <?php
                                    if(count($this->session->userdata('query_duplicate')) > 0) {
                                ?>
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <strong>Có <?php echo count($this->session->userdata('query_duplicate')) ?> danh mục đã có:</strong> <br /> 
                                    <?php
                                        echo implode(", ", $this->session->userdata('query_duplicate'));
                                    ?>
                                </div>
                                
                                <?php
                                }
                                ?>
                                <?php foreach($this->session->userdata('query_array') as $value) : ?>
                                    <?php echo ($value['duplicate'] == true ? "<b>" : "") ?> <?php echo $value['sub'].$value['name'];?> <?php echo ($value['duplicate'] == true ? "(Đã có) </b>" : "") ?> <br />
                                <?php endforeach; ?>
                                <br />

                                <?php
                                    if(count($this->session->userdata('query_duplicate')) == 0) {
                                ?>
                                <br />
                                <br />
                                <?php echo form_open($this->uri->uri_string(),array('id'=>'')) ;?>
                                <div class="form-group">
                                    <?php echo form_hidden("confirm", "1"); ?>
                                    <button type="submit" class="btn btn-info import btn-import-confirm"><?php echo _l('copy_task_confirm'); ?></button>
                                </div>
                                <?php echo form_close();
                                } else {
                                    $this->session->unset_userdata('query_array');
                                    $this->session->unset_userdata('query_duplicate');
                                }
                                 // end form ?>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php if(isset($row_imported)) : ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <strong><?php echo _l('category_import_success') . $row_imported; ?></strong>
                                </div>
                                <?php endif; ?>
                                <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'import_form')) ;?>
                                <?php echo form_hidden('leads_import','true'); ?>
                                <?php echo render_input('file_import','import_choose_file','','file'); ?>
                                <div class="form-group">
                                    <button type="button" class="btn btn-info import btn-import-submit"><?php echo _l('import'); ?></button>
                                    <button type="button" class="btn btn-info simulate btn-import-submit"><?php echo _l('simulate_import'); ?></button>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url('assets/plugins/jquery-validation/additional-methods.min.js'); ?>"></script>
<script>
    _validate_form($('#import_form'),{file_csv:{required:true,extension: "csv"},source:'required',status:'required'});
    $(function(){
     $('.btn-import-submit').on('click',function(){
       if($(this).hasClass('simulate')){
         $('#import_form').append(hidden_input('simulate',true));
       }
       $('#import_form').submit();
     });
    })
</script>
</body>
</html>