
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
                        <p>
                            Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is UTF-8 to avoid unnecessary encoding problems.
                        </p>
                          <p>If the column you are trying to import is date make sure that is formated in format Y-m-d (<?php echo date('Y-m-d'); ?>)</p>
                        
                        </div>
                        <?php } ?>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'import_form')) ;?>
                                <?php echo form_hidden('leads_import','true'); ?>
                                <?php echo render_input('file_csv','choose_csv_file','','file'); ?>
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
