<?php init_head(); ?>
<link rel="stylesheet" href="<?=base_url('assets/treegrid/')?>css/jquery.treegrid.css">
<style type="text/css">
    .poitv{
        pointer-events: none;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body _buttons">
                        <a href="#" onclick="new_category(); return false;" class="btn mright5 btn-info pull-left display-block"><?php echo _l('Thêm danh mục mới'); ?></a>
                        
                        <?php if(is_admin()){ ?>
                        <a href="<?php echo admin_url('categories/import'); ?>" class="btn btn-info pull-left display-block">
                        <?php echo _l('import_categories'); ?>
                        </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <?php 
                                echo render_select('category_1', $category_1, array('id', 'category'), 'Danh mục cấp 1');
                            ?>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <?php 
                                echo render_select('category_2', array(), array('id', 'category'), 'Danh mục cấp 2');
                            ?>
                        </div>
                       <!--  <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <?php 
                                echo render_select('category_3', array(), array('id', 'category'), 'Danh mục cấp 3');
                            ?>
                        </div> -->
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <table class="table tree">
                            <thead>
                                <th>Tên danh mục</th>
                                <th>Cấp danh mục</th>
                                <th>Mô tả</th>
                                <th>Tác vụ</th>
                            </thead>
                            <tbody>
                            <?php
                                foreach($full_categories as $categories_1) {
                                ?>
                            <tr class="treegrid-<?=$categories_1->id?>">
                                <td><h5 style="display: inline-block;"><?=$categories_1->category?></h5></td>
                                <td>Cấp 1</td>
                                <td><?=$categories_1->description?></td>
                                <td>
                                   <!--  <?=icon_btn('categories/delete_category/'. $categories_1->id , 'remove', 'btn-danger delete-reminder')?> -->
                                        
                                </td>
                            </tr>
                                <?php
                                    if(isset($categories_1->items) && count($categories_1->items) > 0) {
                                        foreach($categories_1->items as $categories_2) {
                                            ?>
                            <tr class="treegrid-<?=$categories_2->id?> treegrid-parent-<?=$categories_1->id?>">
                                <td><?=$categories_2->category?></td>
                                <td>Cấp 2</td>
                                <td><?=$categories_2->description?></td>
                                <td>
                                    <a href="#" data-id="<?php echo $categories_2->id ?>" onclick="edit_category(<?php echo $categories_2->id ?>); return false;" class="btn edit_cate btn-info  btn-icon"><i class="fa fa-edit "></i></a>
                                  
                                    <?=icon_btn('categories/delete_category/'. $categories_2->id , 'remove', 'btn-danger delete-reminder')?>
                                    
                                </td>
                            </tr>
                                            <?php
                                            if(isset($categories_2->items) && count($categories_2->items) > 0) {
                                                foreach($categories_2->items as $categories_3) {
                                                ?>
                            <tr class="treegrid-<?=$categories_3->id?> treegrid-parent-<?=$categories_2->id?>">
                                <td><h5 style="display: inline-block;"><?=$categories_3->category?></h5></td>
                                <td>Cấp 3</td>
                                <td><?=icon_btn('categories/delete_category/'. $categories_3->id , 'remove', 'btn-danger delete-reminder')?></td>
                            </tr>
                                            <?php
                                                    if(isset($categories_3->items) && count($categories_3->items) > 0) {
                                                        foreach($categories_3->items as $categories_4) {
                                                            ?>
                            <tr class="treegrid-<?=$categories_4->id?> treegrid-parent-<?=$categories_3->id?>">
                                <td><?=$categories_4->category?></td>
                                <td>Cấp 4</td>
                                <td><?=icon_btn('categories/delete_category/'. $categories_4->id , 'remove', 'btn-danger delete-reminder')?></td>
                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        
                                    }
                                }
                            ?>
                            </tbody>
                        
                        
                        </table>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="type" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('categories/add_category'),array('id'=>'id_type')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('Sửa danh mục sản phẩm'); ?></span>
                    <span class="add-title"><?php echo _l('Thêm danh mục sản phẩm mới'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <?php $selected = $categories[0]['id'] ?>
                        <?php echo render_input('category','Tên'); ?>
                        <?php echo render_select('category_parent', $categories, array('id', 'category'), 'Danh mục cha',$selected); ?>
                        <?php 
                        
                        echo render_textarea('description', 'Mô tả', '', array(), array(), '', 'tinymce');
                        ?> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
<script type="text/javascript" src="<?=base_url('assets/treegrid/')?>js/jquery.treegrid.js"></script>

<script type="text/javascript">
  $('.tree').treegrid({
    initialState: 'collapsed',
  });
</script>

<script>
    function view_init_department(id)
    {
        $('#type').modal('show');
        $('.add-title').addClass('hide');
        jQuery.ajax({
            type: "post",
            url:admin_url+"categories/get_row_category/"+id,
            data: '',
            cache: false,
            success: function (data) {
                var json = JSON.parse(data);
//                if($data!="")
                {
                    $('#category').val(json.category);
                    $('#category_parent').selectpicker("val", json.category_parent);

                    jQuery('#id_type').prop('action',admin_url+'categories/update_category/'+id);
                }
            }
        });
    }


    

    $(function(){
        var filterList = {
            "category_1" : "[name='category_1']",
            "category_2" : "[name='category_2']",
            "category_3" : "[name='category_3']",
        };
        // initDataTable('.table-categories', window.location.href, [1], [1], filterList);
        // $.each(filterList, (key,value)=>{
        //     $('select' + value).on('change', () => {
        //         $('.table-categories').DataTable().ajax.reload();
        //     });
        // });
        _validate_form($('form'),{category:'required'},manage_contract_types);
        $('#type').on('hidden.bs.modal', function(event) {
            $('#additional').html('');
            $('#type input').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
    });
    
    function manage_contract_types(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if(response.success == true){
                alert_float('success',response.message);
                location.reload();
            }
            
            // $('.table-categories').DataTable().ajax.reload();
            $('#type').modal('hide');
        });
        return false;
    }

    function new_category(){
        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        jQuery('#category').val('');
        jQuery('#id_type').prop('action',admin_url+'categories/update_category');
        $('#category').removeAttr('readonly');
        $('#category_parent').parents('.form-group').removeClass('poitv');
        tinyMCE.activeEditor.setContent('');
    }


     function edit_category(a){
        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        jQuery('#category').val('');
        jQuery('#id_type').prop('action',admin_url+'categories/update_category/'+a);
    }



    $('.edit_cate').click(function(){
        var v = $(this).attr('data-id');
        var this1 = $(this);
        tinyMCE.activeEditor.setContent('');
        if(v){
            $.ajax({
               type: "POST",
               dataType:'JSON',
               url: admin_url+"categories/getCateByID/"+v,
               success: function(data)
               {
                    
                    $('#category').val(data.category);
                    $('#category').attr('readonly','readonly');
                    $('#category_parent').parents('.form-group').addClass('poitv');
                    $('#category_parent').selectpicker('val', data.category_parent);
                    $('#description').html(data.description);
                    var b = data.description;
                    if(b){
                        tinyMCE.activeEditor.setContent(b);
                    }
               }
             });
        }
    })

    function edit_type(invoker,id){
        var name = $(invoker).data('name');
        $('#additional').append(hidden_input('id',id));
        $('#type input[name="category"]').val(name);
        $('#type').modal('show');
        $('.add-title').addClass('hide');
    }
    
    $(document).ready(()=>{
        $('#category_1,#category_2,#category_3').on('change', (e) => {
            var id = $(e.currentTarget).val();
            $(e.currentTarget).parents('.col-xs-4').nextAll().find('select[name^="category_"] option:gt(0)').remove();
            $(e.currentTarget).parents('.col-xs-4').nextAll().find('select[name^="category_"]').selectpicker('refresh');
            if(typeof(id) == 'undefined' || id == 0) return;
            jQuery.ajax({
                type: "post",
                url:admin_url+"categories/get_childs/"+id,
                data: '',
                cache: false,
                success: function (data) {
                    data = JSON.parse(data);
                    data.map(o => 
                            $(e.currentTarget).parents('.col-xs-4').next().find('select[name^="category_"]').append('<option value='+o.id+'>'+o.category+'</option>')
                    );
                    $(e.currentTarget).parents('.col-xs-4').next().find('select[name^="category_"]').selectpicker('refresh');
                },
            });
        });
        
    });

</script>
</body>
</html>
