<?php init_head(); ?>
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
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <?php 
                                echo render_select('category_3', array(), array('id', 'category'), 'Danh mục cấp 3');
                            ?>
                        </div>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <?php render_datatable(array(
                            _l('id'),
                            _l('Tên danh mục'),
                            _l('Danh mục cha'),
                            _l('options')
                        ),'categories'); ?>
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
                        <?php echo render_input('category','Tên'); ?>
                        <?php echo render_select('category_parent', $categories, array('id', 'category'), 'Danh mục cha'); ?>
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
        initDataTable('.table-categories', window.location.href, [1], [1], filterList);
        $.each(filterList, (key,value)=>{
            $('select' + value).on('change', () => {
                $('.table-categories').DataTable().ajax.reload();
            });
        });
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
        alert('reload000');
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if(response.success == true){
                alert_float('success',response.message);
            }
            alert('reload');
            $('.table-categories').DataTable().ajax.reload();
            $('#type').modal('hide');
        });
        return false;
    }

    function new_category(){
        $('#type').modal('show');
        $('.edit-title').addClass('hide');
        jQuery('#category').val('');
        jQuery('#id_type').prop('action',admin_url+'categories/add_category');
    }
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
