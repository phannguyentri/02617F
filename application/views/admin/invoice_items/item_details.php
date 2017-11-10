<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="bold no-margin"><?php  echo (isset($item) ? _l('invoice_item_edit_heading') : _l('invoice_item_add_heading')); ?></h4>
</div>
<div class="modal-body">
   <div class="row">
      <div class="col-md-12">
         
            
               <?php if (isset($item)) { ?>
               <?php echo form_hidden( 'isedit'); ?>
               <?php echo form_hidden( 'itemid', $item->id); ?>
               <div class="clearfix"></div>
               <?php } ?>
               <!-- Product information -->
               
               
               <div class="row">
                  <div class="additional"></div>
                  <div class="col-md-12">
                     <ul class="nav nav-tabs profile-tabs" role="tablist" style="border-top: 0px;">
                        <li role="presentation" class="active">
                           <a href="#item_detail" aria-controls="item_detail" role="tab" data-toggle="tab">
                           <?php echo _l( 'item_detail'); ?>
                           </a>
                        </li>
                        <?php if(isset($item)) { ?>
                        <!--<li role="presentation">
                           <a href="#item_date" aria-controls="item_date" role="tab" data-toggle="tab">
                               <?php echo _l( 'item_date'); ?>
                           </a>
                           </li>-->
                        <li role="presentation">
                           <a href="#item_price_history" aria-controls="item_price_history" role="tab" data-toggle="tab">
                           <?php echo _l( 'item_price_history'); ?>
                           </a>
                        </li>
                        <li role="presentation">
                           <a href="#item_files" aria-controls="item_files" role="tab" data-toggle="tab">
                           <?php echo _l( 'item_files'); ?>
                           </a>
                        </li>
                        <!--  <li role="presentation">
                           <a href="#item_duty" aria-controls="item_duty" role="tab" data-toggle="tab">
                               <?php echo _l( 'item_duty'); ?>
                           </a>
                           </li>
                           <li role="presentation">
                           <a href="#item_campaign" aria-controls="item_campaign" role="tab" data-toggle="tab">
                               <?php echo _l( 'item_campaign'); ?>
                           </a>
                           </li>
                           <li role="presentation">
                           <a href="#item_opportunity" aria-controls="item_opportunity" role="tab" data-toggle="tab">
                               <?php echo _l( 'item_opportunity'); ?>
                           </a>
                           </li> -->
                        <?php } ?>
                     </ul>
                     <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="item_detail">
                           <?php echo form_open_multipart($this->uri->uri_string(), array('class'=>'client-form','autocomplete'=>'off')); ?>
                           <div class="row">
                              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                 <?php
                                    // config
                                    $attrs_not_select = array('data-none-selected-text'=>_l('system_default_string'));
                                    ?>
                                 <div class="form-group text-center hidden">
                                    <label for="avatar" class="profile-image" style="text-align: left;"><?php echo _l('item_avatar'); ?></label>
                                    <input type="file" onchange="readURL(this, '#avatar_view');"  name="item_avatar" class="form-control" id="avatar"> <br />
                                    <img id="avatar_view" src="<?php echo (isset($item) && file_exists($item->avatar) ? base_url($item->avatar) : base_url('assets/images/preview_no_available.jpg')) ?>" width="150px" />
                                 </div>
                                 <script type="text/javascript">
                                    function readURL(input, output_img) {
                                        if (input.files && input.files[0]) {
                                            var reader = new FileReader();
                                            reader.onload = function (e) {
                                                $(output_img)
                                                    .attr('src', e.target.result)
                                                    .width(100);
                                            };
                                    
                                            reader.readAsDataURL(input.files[0]);
                                        }
                                    }
                                 </script>
                                 <?php $value = (isset($item) ? (($item->code)? $item->code : get_option('prefix_product').sprintf("%05d",$item->id)) : get_option('prefix_product').sprintf("%05d",(getMaxID('id','tblitems')+1))); ?>
                                 <?php $attrs = array('readonly'=>true); ?>
                                 <?php echo render_input('code','Mã sản phẩm',$value,'text',$attrs); ?>
                                 <!-- <?php
                                    $default_code = (isset($item) ? $item->code : "");
                                    echo render_input('code', _l('item_code'), $default_code);
                                    ?> -->
                                 <?php
                                    $default_name = (isset($item) ? $item->name : "");
                                    echo render_input('name', _l('item_name'), $default_name);
                                    ?>
                                 <!-- <?php
                                    $default_short_name = (isset($item) ? $item->short_name : "");
                                    echo render_input('short_name', _l('item_short_name'), $default_short_name);
                                    ?> -->
                                 <?php
                                    $default_price = (isset($item) ? $item->price : 0);
                                    echo render_input('price', _l('item_price'), number_format($default_price,0,".","."),'',array('onkeyup'=>"formart_num('price')"));
                                    ?>
                                 <?php
                                    $default_price_buy = (isset($item) ? $item->price_buy : 0);
                                    echo render_input('price_buy', _l('item_price_buy'), number_format($default_price_buy,0,".","."),'',array('onkeyup'=>"formart_num('price_buy')"));
                                    ?>
                                 <?php
                                    $default_tax = (isset($item) ? $item->tax : 0);
                                    echo render_select('tax', get_all_taxes(), array('id','name'), 'taxes', $default_tax, array(), array(), '', '', false);
                                    ?>
                                 <?php
                                    $default_rate = (isset($item) ? $item->rate : 0);
                                    echo render_input('rate', _l('tax_add_edit_rate'), $default_rate,'',array(),array('style' => 'display:none;'));
                                    ?>
                                 <!--  <?php 
                                    $contents_description = (isset($item) ? $item->description : "");
                                    echo render_textarea('description','item_description',$contents_description,array(),array(),'','tinymce '); 
                                    ?>
                                    <?php 
                                       $contents_long_description = (isset($item) ? $item->long_description : "");
                                       echo render_textarea('long_description','item_long_description',$contents_long_description,array(),array(),'','tinymce'); 
                                       ?> -->
                                 <?php
                                    $units = get_units();
                                    $default_unit = (isset($item) ? $item->unit : "");
                                    echo render_select('unit', $units, array('unitid','unit'), 'item_unit', $default_unit, array(), array(), '', '', false);
                                    ?>
                                 <?php
                                    $groups = get_item_groups();
                                    $default_group = (isset($item) ? $item->group_id : "");
                                    echo render_select('group_id', $groups, array('id','name'), 'item_group_id', $default_group);
                                    ?>
                                 <?php
                                    $countries = get_all_countries();
                                    $default_contry = (isset($item) ? $item->country_id : "");
                                    
                                    echo render_select('country_id', $countries, array('country_id','short_name'), 'Nước sản xuất', $default_contry, array(), array(), '', '', false);
                                    ?>
                              </div>
                              <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                 <?php
                                    $i=0;
                                    if(count($array_categories) > 0) {
                                        foreach($array_categories as $value)
                                        {
                                            $i++;
                                            echo render_select('category_id[]', $value[1], array('id','category'), _l('item_category_level') . $i, $value[0], array(), array(), '', '');
                                        }
                                    }
                                    else {
                                        echo render_select('category_id[]', "", array('id','category'), 'item_category', "", array(), array(), '', '');
                                    }
                                    ?>
                                 <!-- <?php
                                    $default_minimum = (isset($item) ? $item->minimum_quantity : 10);
                                    echo render_input('minimum_quantity', _l('minimum_quantity'), number_format($default_minimum,10,".","."),'',array('onkeyup'=>"formart_num('minimum_quantity')"));
                                    ?>
                                    <?php
                                       $default_maximum = (isset($item) ? $item->maximum_quantity : 99);
                                       echo render_input('maximum_quantity', _l('maximum_quantity'), number_format($default_maximum,99,".","."),'',array('onkeyup'=>"formart_num('maximum_quantity')"));
                                       ?> -->
                                 <!--      <div class="form-group">
                                    <label for="date"><?php echo _l('item_date'); ?></label>
                                    <div class="input-group">
                                     
                                       <input type="number" min="0" name="date" class="form-control" value="<?=$item->date ?>" id="date">
                                       <span class="input-group-addon">
                                       <?php echo _l('Tháng') ?></span>
                                     </div>
                                    </div>
                                    <div class="form-group">
                                    <label for="warranty"><?php echo _l('item_warranty'); ?></label>
                                    <div class="input-group">
                                     
                                       <input type="number" min="0" name="warranty" class="form-control" value="<?=$item->warranty ?>" id=" warranty">
                                       <span class="input-group-addon">
                                       <?php echo _l('Tháng') ?></span>
                                     </div>
                                    </div> -->
                                 <!-- <?php
                                    $release_date = ( isset($item) ? _d($item->release_date) : _d(date('Y-m-d')));
                                    echo render_date_input( 'release_date', 'item_release_date' , $release_date, 'date'); 
                                    ?>
                                    <?php
                                       $date_of_removal_of_sample = ( isset($item) ? _d($item->date_of_removal_of_sample) : _d(date('Y-m-d')));
                                       echo render_date_input( 'date_of_removal_of_sample', 'item_date_of_removal_of_sample' , $date_of_removal_of_sample, 'date'); 
                                       ?> -->
                                 <!-- <?php
                                    $default_producer = (isset($item) ? $item->producer : "");
                                    echo render_input('producer', _l('Nhà sản xuất'), $default_producer);
                                    ?>
                                    -->
                                 <!-- <?php
                                    $default_specification = (isset($item) ? $item->specification : "");
                                    echo render_input('specification', _l('item_specification'), $default_specification);
                                    ?>
                                    <?php
                                       $default_size = (isset($item) ? $item->size : "");
                                       echo render_input('size', _l('item_size'), $default_size);
                                       ?>
                                    
                                    <?php
                                       $default_weight = (isset($item) ? $item->weight : "");
                                       echo render_input('weight', _l('item_weight'), $default_weight);
                                       ?> -->
                                 <?php
                                    $default_product_long_description = (isset($item) ? $item->long_description : "");
                                    echo render_textarea('long_description','Chi tiết sản phẩm',$default_product_long_description,array('data-task-ae-editor'=>true),array(),'','tinymce'); 
                                    ?>
                                 <?php

                                    $default_product_features = (isset($item) ? $item->product_features : "");
                                    echo render_textarea('product_features','Thông số kỹ thuật',$default_product_features,array('data-task-ae-editor'=>true),array(),'','tinymce');

                                    ?>

                              </div>
                           </div>
                           <div class="row">
                              <button class="btn btn-info mtop20 only-save customer-form-submiter" style="margin-left: 15px">
                              <?php echo _l( 'submit'); ?>
                              </button>
                           </div>
                           <?php echo form_close(); ?>
                        </div>
                        <?php if(isset($item)) { ?>
                        <div role="tabpanel" class="tab-pane" id="item_date">
                        </div>
                        <div role="tabpanel" class="tab-pane" id="item_price_history">
                           <div class="row">
                              <div class="col-md-12">
                                 <h3><?php echo _l('item_price') ?></h3>
                                 <?php render_datatable(array(
                                    _l('item_price_date'),
                                    _l('item_old_price'),
                                    _l('item_new_price'),
                                    ),
                                    'invoice-item-price-history'); ?>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <h3><?php echo _l('item_price_buy') ?></h3>
                                 <?php render_datatable(array(
                                    _l('item_price_date'),
                                    _l('item_old_price'),
                                    _l('item_new_price'),
                                    ),
                                    'invoice-item-price-buy-history'); ?>
                              </div>
                           </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="item_files">
                           <?php echo form_open('admin/invoice_items/add_item_attachment',array('class'=>'dropzone mtop30','id'=>'invoice-item-attachment-upload')); ?>
                           <?php echo form_close(); ?>
                           <?php if(get_option('dropbox_app_key') != ''){ ?>
                           <hr />
                           <div class="text-center">
                              <div id="dropbox-chooser-lead"></div>
                           </div>
                           <?php } ?>
                           <hr />
                           <div class="mtop30" id="invoice_item_attachments">
                           </div>
                        </div>
                        <?php } ?>
                     </div>
                  </div>
                  <!-- END PI -->        
               </div>
            
         
      </div>
   </div>
</div>

<script>

   <?php if(isset($item)) { ?>
   init_invoice_item_data(<?php echo $item->id; ?>, '<?php echo admin_url('invoice_items/get_invoice_item_attachment/'); ?>');
   $(function(){
   initDataTable('.table-invoice-item-price-history', '<?=admin_url('invoice_items/price_history/' . $item->id)?>', [], [],'undefined',[0,'DESC']);
   
   initDataTable('.table-invoice-item-price-buy-history', '<?=admin_url('invoice_items/price_buy_history/' . $item->id)?>', [], [],'undefined',[0,'DESC']);
   });
   <?php } ?>
</script>
<?php $this->load->view('admin/invoice_items/item_details_js'); ?>
<script>
  
   $('document').ready(()=>{
       <?php
      if(!isset($item)) {
      ?>
           getRate($('#tax').val());
       <?php
      }
      ?>
   });
   var getRate = (tax_id) => {
       $.get(admin_url + 'invoice_items/get_tax/' + tax_id, (data) => {
           $('#rate').val(data.taxrate);
       }, 'json');
   };
   $('#tax').on('change', ()=>{
       getRate($('#tax').val());
   });
   function formatNumber(nStr, decSeperate, groupSeperate) {
       //decSeperate= ki tu cach,groupSeperate= ki tu noi
       nStr += '';
       x = nStr.split(decSeperate);
       x1 = x[0];
       x2 = x.length > 1 ? ',' + x[1] : '';
       var rgx = /(\d+)(\d{3})/;
       while (rgx.test(x1)) {
           x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
       }
       return x1 + x2;
   }

  

   function formart_num(id_input)
   {
      key="";
      money=$("#"+id_input).val().replace(/[^\d\.]/g, '');
      a=money.split(".");
      $.each(a , function (index, value){
          key=key+value;
      });
      $("#"+id_input).val(formatNumber(key, '.', '.'));
   }
  
   
</script>
