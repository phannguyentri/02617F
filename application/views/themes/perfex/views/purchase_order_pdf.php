<?php
function mb_ucfirst($string, $encoding)
{
    return mb_convert_case($string, MB_CASE_TITLE, $encoding);
}
$dimensions = $pdf->getPageDimensions();
if($tag != ''){
    $pdf->SetFillColor(240,240,240);
    $pdf->SetDrawColor(245,245,245);
    $pdf->SetXY(0,0);
    $pdf->SetFont($font_name,'B',15);
    $pdf->SetTextColor(0);
    $pdf->SetLineWidth(0.75);
    $pdf->StartTransform();
    $pdf->Rotate(-35,109,235);
    $pdf->Cell(100,1,mb_strtoupper($tag,'UTF-8'),'TB',0,'C','1');
    $pdf->StopTransform();
    $pdf->SetFont($font_name,'',$font_size);
    $pdf->setX(10);
    $pdf->setY(10);
}

$pdf_text_color_array = hex2rgb(get_option('pdf_text_color'));
if (is_array($pdf_text_color_array) && count($pdf_text_color_array) == 3) {
    $pdf->SetTextColor($pdf_text_color_array[0], $pdf_text_color_array[1], $pdf_text_color_array[2]);
}

// Get Y position for the separation
$y            = $pdf->getY();





$info_right_column = '';
$info_left_column  = '';


// $info_right_column .= '<a href="' . admin_url('purchase_order/view/' . $purchase_suggested->id) . '" style="color:#4e4e4e;text-decoration:none;"><b># ' . $purchase_suggested->date . '</b></a>';
$info_right_column .= '<p><b>' . _l('orders_code') . '</b>: '. $purchase_order->code.'</p>';
$info_right_column .= '<p><b>' . _l('pdf_date_print') . '</b>: '. date('d-m-Y').'</p>';

// write the first column
$info_left_column .= pdf_logo_url();
$pdf->MultiCell(($dimensions['wk'] / 2) - $dimensions['lm'], 0, $info_left_column, 0, 'J', 0, 0, '', '', true, 0, true, true, 0);
// write the second column
$pdf->MultiCell(($dimensions['wk'] / 2) - $dimensions['rm'], 0, $info_right_column, 0, 'R', 0, 1, '', '', true, 0, true, false, 0);
$pdf->ln(10);

// Get Y position for the separation
$y            = $pdf->getY();
$invoice_info = '<b>' . get_option('invoice_company_name') . '</b><br />';
$invoice_info .= get_option('invoice_company_address') . '<br/>';
if (get_option('invoice_company_city') != '') {
    $invoice_info .= get_option('invoice_company_city') . ', ';
}
$invoice_info .= get_option('invoice_company_country_code') . ' ';
$invoice_info .= get_option('invoice_company_postal_code') . ' ';

if (get_option('invoice_company_phonenumber') != '') {
    $invoice_info .= '<br />' . get_option('invoice_company_phonenumber');
}
if(get_option('company_vat') != ''){
    $invoice_info .= '<br />'.get_option('company_vat');
}
// check for company custom fields
$custom_company_fields = get_company_custom_fields();
if (count($custom_company_fields) > 0) {
    $invoice_info .= '<br />';
}
foreach ($custom_company_fields as $field) {
    $invoice_info .= $field['label'] . ': ' . $field['value'] . '<br />';
}
$pdf->writeHTMLCell(($swap == '1' ? ($dimensions['wk']) - ($dimensions['lm'] * 2) : ($dimensions['wk'] / 2) - $dimensions['lm']), '', '', $y, $invoice_info, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);
$pdf->ln(20);


$title = _l('orders_ticket');
$title = mb_strtoupper($title, "UTF-8");


$info_center_column = '<span style="font-weight:bold;font-size:30px;">' . $title . '</span><p style="text-align: center;">'._l('orders_date_create').': ' . date("d/m/Y", strtotime($purchase_order->date_create)) . '</p>';
$date_create .= '';

$pdf->writeHTMLCell(($dimensions['wk']) - $dimensions['lm'], '', '', $y+20, $info_center_column, 0, 0, false, true, 'C', true);
$pdf->ln(30);

$y            = $pdf->getY();

$detail .= '<p><b>' . _l('als_suppliers') . '</b>: '. $purchase_order->suppliers_company.'</p>';
$detail .= '<p><b>' . _l('address') . '</b>: '. $purchase_order->suppliers_address.'</p>';
$detail .= '<p><b>' . _l('company_vat_number') . '</b>: '. $purchase_order->suppliers_vat.'</p>';

$pdf->writeHTMLCell($dimensions['wk'] - $dimensions['lm'], '', '', $y, $detail, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);





// Thông tin Kho hàng và nhân viên
$tblTable = '
<table width="100%" cellspacing="0" cellpadding="5">
    <tr>
        <td>
            <b>'._l('orders_user_create').':</b>
        </td>
        <td>
            '.$purchase_order->user_fullname.'
        </td>
        <td>
            <b>'._l('Ngày nhập dự kiến').':</b>
        </td>
        <td>
            '.date('d/m/Y',strtotime($purchase_order->date_import)).'
        </td>
    </tr>
    <tr>
        <td>
            <b>'._l('orders_explan').':</b>
        </td>
        <td>
            '.$purchase_order->explan.'
        </td>
        
    </tr>
</table>    
';
$pdf->Ln(30);
$pdf->writeHTML($tblTable, true, false, false, false, '');
// The Table
$pdf->Ln(3);
// $item_width = 38;
// // If show item taxes is disabled in PDF we should increase the item width table heading
// if (get_option('show_tax_per_item') == 0) {
//     $item_width = $item_width + 15;
// }
// // Header
// $qty_heading = _l('invoice_table_quantity_heading');

$tblHtml = '
<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="1">
    <tr height="30" bgcolor="' . get_option('pdf_table_heading_color') . '" style="color:' . get_option('pdf_table_heading_text_color') . ';">
        <th width="5%;" valign="middle" align="center">#</th>
        <th width="10%" valign="middle" align="center">
            <i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip"></i>'. _l('item_code').'</th>
        <th width="25%" valign="middle" align="center">'. _l('item_name') .'</th>
        <th width="10%" valign="middle" align="center">'. _l('item_unit') .'</th>
        <th width="10%" valign="middle" align="center">'. _l('item_quantity') .'</th>
        <th width="10%" valign="middle" align="center">'. _l('item_price_buy') .'</th>
        <th width="10%" valign="middle" align="center">'. _l('tax') . '</th>
        <th width="20%" valign="middle" align="center">'. _l('purchase_total_price') . '</th>
    </tr>
        ';
// Items

$i=0;
$totalPrice = 0;

foreach($purchase_order->products as $value) {
    // print_r($value);
    // exit();
    $i++;
    $tblHtml .= '
        <tr>
            <td>'.$i.'</td>
            <td>'.$value->product_code.'</td>
            <td>'.$value->product_code.'</td>
            <td>'.$value->unit.'</td>
            <td style="text-align:center">'.number_format($value->product_quantity).'</td>
            <td style="text-align:right">'.number_format($value->product_price_buy).'</td>
            <td>'.$value->rate.'</td>
            <td style="text-align:right">'.number_format($value->product_quantity*$value->product_price_buy + ($value->product_quantity*$value->product_price_buy)* $value->rate).'</td>
        </tr>
    ';
    $totalPrice += ($value->product_quantity*$value->product_price_buy + ($value->product_quantity*$value->product_price_buy)* $value->rate);
}

$tblHtml .= '
        <tr>
            <td colspan="5" style="text-align: right">'._l('purchase_total_price').'</td>
            <td colspan="3" style="text-align: right">' . number_format($totalPrice). '</td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: right">'._l('purchase_total_items').'</td>
            <td colspan="3" style="text-align: right">' . number_format($i). '</td>
        </tr>
';
$tblHtml .= '</table>';
$pdf->writeHTML($tblHtml, true, false, false, false, '');

// print_r($tblHtml);
// exit();

// $detail = _l('user_head').': <b>' . $purchase_suggested->user_head_name . '</b> <br /> <br />';
// $detail .= _l('user_admin').': <b>' . $purchase_suggested->user_admin_name . '</b> <br /> <br />';
$pdf->Ln(20);
$table = "<table style=\"width: 100%;text-align: center\" border=\"0\">
        <tr>
            <td><b>" . mb_ucfirst(_l('orders_user_create'), "UTF-8") . "</b></td>
            <td><b>" . mb_ucfirst(_l('user_head'), "UTF-8") . "</b></td>
        </tr>
        <tr>
            <td style=\"height: 100px\" colspan=\"3\"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>(ký, ghi rõ họ tên)</td>
            <td>(ký, ghi rõ họ tên)</td>
        </tr>
</table>";
$pdf->writeHTML($table, true, false, false, false, '');