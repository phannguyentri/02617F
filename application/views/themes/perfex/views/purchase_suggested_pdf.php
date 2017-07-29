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

$info_right_column = '';
$info_left_column  = '';

// if (get_option('show_status_on_pdf_ei') == 1) {
//     $status = $purchase_suggested->status;
//     if ($status == 0) {
//         $bg_status = '252, 45, 66';
//     } else if ($status == 1) {
//         $bg_status = '0, 191, 54';
//     } else if ($status == 2) {
//         $bg_status = '255, 111, 0';
//     }

//     $info_right_column .= '
//     <table style="text-align:center;border-spacing:3px 3px;padding:3px 4px 3px 4px;">
//     <tbody>
//         <tr>
//             <td></td>
//             <td></td>
//             <td style="background-color:rgb(' . $bg_status . ');color:#fff;">' . mb_strtoupper($status == 1 ? "Đã duyệt" : "Chưa duyệt", 'UTF-8') . '</td>
//         </tr>
//     </tbody>
//     </table>';
// }


$info_right_column .= '<a href="' . admin_url('purchase_suggested/detail/' . $purchase_suggested->name) . '" style="color:#4e4e4e;text-decoration:none;"><b># ' . $purchase_suggested->date . '</b></a>';

// write the first column
$info_left_column .= pdf_logo_url();
$pdf->MultiCell(($dimensions['wk'] / 2) - $dimensions['lm'], 0, $info_left_column, 0, 'J', 0, 0, '', '', true, 0, true, true, 0);
// write the second column
$pdf->MultiCell(($dimensions['wk'] / 2) - $dimensions['rm'], 0, $info_right_column, 0, 'R', 0, 1, '', '', true, 0, true, false, 0);


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
$pdf->writeHTMLCell(($swap == '1' ? ($dimensions['wk']) - ($dimensions['lm'] * 2) : ($dimensions['wk'] / 2) - $dimensions['lm']), '', '', $y+40, $invoice_info, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);



$pdf->ln(10);
$title = _l('purchase_suggested');
$title = mb_strtoupper($title, "UTF-8");
$info_center_column = '<span style="font-weight:bold;font-size:30px;">' . $title . '</span><br />';

$pdf->writeHTMLCell(($dimensions['wk']) - $dimensions['lm'], '', '', $y, $info_center_column, 0, 0, false, true, 'C', true);
$pdf->ln(20);
// Get Y position for the separation
$y            = $pdf->getY();

$detail  = _l('purchase_suggested_code').': ' . $purchase_suggested->code . '</b> <br /> <br />';
$detail .= _l('purchase_suggested_name').': ' . $purchase_suggested->name . '<br /> <br />';
$detail .= _l('purchase_suggested_date').': ' . $purchase_suggested->date . '<br /> <br />';
$detail .= _l('purchase_suggested_reason').': ' . $purchase_suggested->reason . '<br /> <br />';
// $detail .= _l('purchase_suggested_status').': <b>' . ($purchase_suggested->status == 1 ? "Đã duyệt" : "Chưa duyệt") . '</b> <br /> <br /> <br />';

$pdf->writeHTMLCell($dimensions['wk'] - $dimensions['lm'] - 20, '', '', $y, $detail, 0, 0, false, true, ($swap == '1' ? 'R' : 'L'), true);

// The Table
$pdf->Ln(80);
$item_width = 38;
// If show item taxes is disabled in PDF we should increase the item width table heading
if (get_option('show_tax_per_item') == 0) {
    $item_width = $item_width + 15;
}
// Header
$qty_heading = _l('invoice_table_quantity_heading');

$tblhtml = '
<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="1">
    <tr height="30" bgcolor="' . get_option('pdf_table_heading_color') . '" style="color:' . get_option('pdf_table_heading_text_color') . ';font-size: 20px;">
        <th width="5%;" valign="middle" align="center">#</th>
        <th width="20%" valign="middle" align="center">
            <i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip"></i>'. _l('item_name').'</th>
        <th width="10%" valign="middle" align="center">'. _l('item_unit') .'</th>
        <th width="10%" valign="middle" align="center">'. _l('item_quantity') .'</th>
        
        <th width="15%" valign="middle" align="center">'. _l('item_price_buy') .'</th>
        <th width="15%" valign="middle" align="center">'. _l('purchase_total_price') . '</th>
        <th width="25%" valign="middle" align="center">'. _l('item_specification') . '</th>
        
        ';
$tblhtml .= '
</tr>';
// Items
$tblhtml .= '<tbody>';

$i=0;
$totalPrice = 0;
foreach($purchase_suggested->items as $value) {
    $i++;
    $tblhtml .= '
        <tr>
            <td>'.$i.'</td>
            <td>'.$value->product_name.'<br /><span style="color:#777;">'.$value->product_code.'</span></td>
            <td>'.$value->product_unit.'</td>
            <td style="text-align:center">'.number_format($value->product_quantity).'</td>
            <td style="text-align:right">'.number_format($value->product_price_buy).'</td>
            <td style="text-align:right">'.number_format($value->product_quantity*$value->product_price_buy).'</td>
            <td>'.$value->product_specifications.'</td>
        </tr>
    ';
    $totalPrice += ($value->product_quantity*$value->product_price_buy);
}
$tblhtml .= '
        <tr>
            <td colspan="5" style="text-align: right">'._l('purchase_total_price').'</td>
            <td colspan="2" style="text-align: right">' . number_format($totalPrice). '</td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: right">'._l('purchase_total_items').'</td>
            <td colspan="2" style="text-align: right">' . number_format($i). '</td>
        </tr>
';
$tblhtml .= '</tbody>';
$tblhtml .= '</table>';
$pdf->writeHTMLCell($dimensions['wk'] - $dimensions['lm'] - 20, '', '', $y+100, $tblhtml, false, true, 'L', true);


// $detail = _l('user_head').': <b>' . $purchase_suggested->user_head_name . '</b> <br /> <br />';
// $detail .= _l('user_admin').': <b>' . $purchase_suggested->user_admin_name . '</b> <br /> <br />';
$pdf->Ln(20);
$table = "<table style=\"width: 100%;text-align: center\" border=\"0\">
        <tr>
            <td>" . mb_ucfirst(_l('purchase_user'), "UTF-8") . "</td>
            <td>" . mb_ucfirst(_l('user_head'), "UTF-8") . "</td>
            <td>" . mb_ucfirst(_l('user_admin'), "UTF-8") . "</td>
        </tr>
        <tr>
            <td style=\"height: 100px\" colspan=\"3\"></td>
        </tr>
        <tr>
            <td>" . $purchase_suggested->user_name . "</td>
            <td>" . $purchase_suggested->user_head_name . "</td>
            <td>" . $purchase_suggested->user_admin_name . "</td>
        </tr>
        <tr>
            <td>(ký, ghi rõ họ tên)</td>
            <td>(ký, ghi rõ họ tên)</td>
            <td>(ký, ghi rõ họ tên)</td>
        </tr>
</table>";
$pdf->writeHTML($table, true, false, false, false, '');