<?php
/**
 * Included in application/views/admin/invoice_items/item_details.php
 */
?>
<script>
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
        unit: 'required',
    });
 });
</script>