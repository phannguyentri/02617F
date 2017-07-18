<?php
/**
 * Included in application/views/admin/invoice_items/item_details.php
 */
?>
<script>
    
$(document).ready(()=>{
    var createSelect = (id_category = 0, select) => {
        $.ajax({
            url: '<?php echo admin_url('invoice_items/get_categories/') ?>' + id_category,
            dataType: 'json',
        }).done((data)=>{
            console.log(select);
            select.find('option').remove();
            select.append('<option value></option>');
            $.each(data, (index,value) => {
                select.append('<option value="' + value.id + '">' + value.category + '</option>');
            });
            select.selectpicker('refresh');
        });
    };
    $('select[name=category_id\\[\\]]:first').on('change', (e)=>{  
        // 
        $('select[name=category_id\\[\\]]:last').find('option:gt(0)').remove();
        $('select[name=category_id\\[\\]]:last').selectpicker('refresh');
        // Hide
        $('select[name=category_id\\[\\]]:last').parent().parent().hide();
        if($(e.currentTarget).val() == 0 || $(e.currentTarget).val() == ''){
            $('select[name=category_id\\[\\]]:odd').parent().parent().hide(); 
        } else {
            $('select[name=category_id\\[\\]]:odd').parent().parent().show();
        }
        createSelect($('select[name=category_id\\[\\]]:first').val(), $('select[name=category_id\\[\\]]:odd'));
    });
    $('select[name=category_id\\[\\]]:odd').on('change', (e)=>{  
        createSelect($('select[name=category_id\\[\\]]:odd').val(), $('select[name=category_id\\[\\]]:last'));
        if($(e.currentTarget).val() == 0 || $(e.currentTarget).val() == '')
            $('select[name=category_id\\[\\]]:last').parent().parent().hide();
        else
            $('select[name=category_id\\[\\]]:last').parent().parent().show();
    });
    createSelect($('select[name=category_id\\[\\]]:first').val(), $('select[name=category_id\\[\\]]:odd'));
    if($('select[name=category_id\\[\\]]:odd').val() == 0 || $('select[name=category_id\\[\\]]:odd').val() == '')
        $('select[name=category_id\\[\\]]:last').parent().parent().hide();
    if($('select[name=category_id\\[\\]]:first').val() == 0 || $('select[name=category_id\\[\\]]:first').val() == '')
        $('select[name=category_id\\[\\]]:odd').parent().parent().hide();
});
 $(function() {
    _validate_form($('.client-form'), {
        unit: 'required',
        minimum_quantity: 'required',
        maximum_quantity: 'required',
        group_id: 'required',
        country_id: 'required',
        code: 'required',
        name: 'required',
        short_name: 'required',
        price: 'required',
        price_buy: 'required',
        unit: 'required',
    });
 });
</script>