<?php init_head(); ?>
<div id="wrapper">
  <div class="content">

    <div class="row">
      <div class="col-md-8">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="bold no-margin font-medium">
              <?php echo $title; ?>
            </h4>
            <hr />
            <?php echo form_open($this->uri->uri_string()); ?>
            <div class="row">
              <div class="col-md-12">
                 <?php echo render_input('name','template_name',$template->name,'text',array('readonly'=>true)); ?>                 
                <hr />
                <?php
                $editors = array();
                  array_push($editors,'content');
                ?>
                <p class="bold"><?php echo _l('contract_template_content'); ?></p>
                <?php echo render_textarea('content','',$template->content,array('data-url-converter-callback'=>'myCustomURLConverter'),array(),'','tinymce tinymce-manual'); ?>
                
                <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
              </div>
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel_s">
            <div class="panel-body">
              <!-- <h4 class="bold no-margin font-medium">
                <?php echo _l('available_merge_fields'); ?>
              </h4>
              <hr /> -->
              <div class="row">                 
          <!--  -->
          <div class="col-md-12">
                      <?php if(isset($contract_merge_fields)){ ?>
                      <p class="bold mtop10"><a href="#" onclick="slideToggle('.avilable_merge_fields'); return false;"><?php echo _l('available_merge_fields'); ?></a></p>
                      <div class=" avilable_merge_fields mtop15 hide">
                        <ul class="list-group">
                          <?php
                          foreach($contract_merge_fields as $field){
                           foreach($field as $f){
                            echo '<li class="list-group-item"><b>'.$f['name'].'</b><span class="pull-right"><a href="#" class="add_merge_field">'.$f['key'].'</a></span></li>';
                          }
                        } ?>
                      </ul>
                    </div>
                    <?php } ?>
                  </div>
          <!--  -->
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
    <?php foreach($editors as $id){ ?>
      init_editor('textarea[name="<?php echo $id; ?>"]',{urlconverter_callback:'merge_field_format_url'});
      <?php } ?>
      var merge_fields_col = $('.merge_fields_col');
        // If not fields available
        $.each(merge_fields_col, function() {
          var total_available_fields = $(this).find('p');
          if (total_available_fields.length == 0) {
            $(this).remove();
          }
        });
    // Add merge field to tinymce
    $('.add_merge_field').on('click', function(e) {
     e.preventDefault();
     tinymce.activeEditor.execCommand('mceInsertContent', false, $(this).text());
   });
    _validate_form($('form'), {
      name: 'required',
      fromname: 'required',
    });
  });
</script>
</body>
</html>
