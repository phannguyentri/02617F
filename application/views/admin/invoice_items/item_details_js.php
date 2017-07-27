<?php
/**
 * Included in application/views/admin/invoice_items/item_details.php
 */
?>
<script>
    
$(document).ready(()=>{
    var createSelect = (id_category = 0, select) => {
        if(id_category == 0 || typeof(id_category) == 'undefined' || typeof(select) == 'undefined') {
            return;
        }
        $.ajax({
            url: '<?php echo admin_url('invoice_items/get_categories/') ?>' + id_category,
            dataType: 'json',
        }).done((data)=>{
            select.parent().parent().show();
            select.find('option').remove();
            select.append('<option value></option>');
            $.each(data, (index,value) => {
                select.append('<option value="' + value.id + '">' + value.category + '</option>');
            });
            select.selectpicker('refresh');
        });
    };
    $('select[name=category_id\\[\\]]').on('change', (e)=>{  
        // 
        $(e.currentTarget).parents('div.form-group').nextAll().find('select[name=category_id\\[\\]] option:gt(0)').remove();
        $(e.currentTarget).parents('div.form-group').nextAll().find('select[name=category_id\\[\\]]').selectpicker('refresh');
        // Hide
        if($(e.currentTarget).val() != '' && $(e.currentTarget).val() != 0)
            $(e.currentTarget).parents('div.form-group').next().nextAll().find('select[name=category_id\\[\\]]').parents('div.form-group').hide();
        else
            $(e.currentTarget).parents('div.form-group').nextAll().find('select[name=category_id\\[\\]]').parents('div.form-group').hide();
        
        createSelect($(e.currentTarget).val(), $(e.currentTarget).parents('div.form-group').next().find('select[name=category_id\\[\\]]'));
    });
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