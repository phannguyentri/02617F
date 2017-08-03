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
$info_right_column .= '<p><b><font size="13px">' . get_option('invoice_company_name') . '</font></b>';
$info_right_column .= '<br /><br /><font size="8px">' . get_option('invoice_company_address') . '<br />';
if (get_option('invoice_company_city') != '') {
    $info_right_column .= '' . get_option('invoice_company_city') . '';
}
if (trim(get_option('invoice_company_country_code')) != '') {
    $info_right_column .= '' . get_option('invoice_company_country_code') . ' | ';
}
if (trim(get_option('invoice_company_postal_code')) != '') {
    $info_right_column .= '' . get_option('invoice_company_postal_code') . ' | ';
}
if (trim(get_option('invoice_company_phonenumber')) != '') {
    $info_right_column .= '' . get_option('invoice_company_phonenumber') . ' | ';
}
if (trim(get_option('company_vat')) != '') {
    $info_right_column .= '' . get_option('company_vat') . ' | ';
}
$info_right_column .= "</font></p>";

// write the first column
$info_left_column .= pdf_logo_url();
$pdf->MultiCell(($dimensions['wk'] / 2) - $dimensions['lm'], 0, $info_left_column, 0, 'J', 0, 0, '', '', true, 0, true, true, 0);
// write the second column
$pdf->MultiCell(($dimensions['wk'] / 2) - $dimensions['rm'], 0, $info_right_column, 0, 'R', 0, 1, '', '', true, 0, true, false, 0);
// $pdf->ln(10);

// Get Y position for the separation
$y            = $pdf->getY();

$title = _l('purchase_contracts');
$title = mb_strtoupper($title, "UTF-8");

$pdf->writeHTMLCell($dimensions['wk'] - $dimensions['lm'], '', '', $y, "<hr />", 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);
$pdf->ln(10);

$y            = $pdf->getY();
$info_center_column = '<span style="font-weight:bold;font-size:30px;">' . $title . '</span>
<p style="text-align: center;">'._l('purchase_contracts_number_code').': .......................................... </p>
<p style="text-align: center;">
    <ul>
        <li style="text-align: center;">
            <i>
                Căn cứ Bộ luật dân sự số 33/2005/QH11 ngày 14/06/2005 của Quốc hội nước Cộng hòa <br/>xã hội chủ nghĩa Việt Nam;
            </i>
        </li>
        <li style="text-align: center;">
            <i>
                Căn cứ Nghị định số 163/2006/NĐ-CP ngày 29/12/2006 của Chính phủ về giao dịch bảo <br />đảm;
            </i>        
        </li>
    </ul>
</p>
';
$pdf->writeHTMLCell(( ($dimensions['wk']) - $dimensions['lm']) , '', '', $y, $info_center_column, 0, 0, false, true, 'C', true);
$pdf->ln(40);

// $y            = $pdf->getY();

// $detail .= '<p><b>' . _l('als_suppliers') . '</b>: '. $purchase_order->suppliers_company.'</p>';
// $detail .= '<p><b>' . _l('address') . '</b>: '. $purchase_order->suppliers_address.'</p>';
// $detail .= '<p><b>' . _l('company_vat_number') . '</b>: '. $purchase_order->suppliers_vat.'</p>';

// $pdf->writeHTMLCell($dimensions['wk'] - $dimensions['lm'], '', '', $y, $detail, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);
$y            = $pdf->getY();

$date_left = '<p style="text-align: right"><b><i>Ngày '.date('d').' tháng '.date('m').'  năm  '.date('Y').',</i></b>  </p>';

$pdf->writeHTMLCell($dimensions['wk'] - $dimensions['lm'], '', '', $y, $date_left, 0, 0, false, true, ($swap == '1' ? 'R' : 'J'), true);



// BÊN MUA
$tblTable = '
<font size="11px">
<b>
    <u>BÊN MUA:</u> (BÊN A)
</b>
<br />

<table width="100%" cellspacing="0" cellpadding="5">
    <tr>
        <td width="20%">
            <b>Đơn vị: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
    <tr>
        <td width="20%">
            <b>Mã số thuế: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
    <tr>
        <td width="20%">
            <b>Người đại diện: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
    <tr>
        <td width="20%">
            <b>Chức vụ: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
    <tr>
        <td width="20%">
            <b>Địa chỉ: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
    <tr>
        <td width="20%">
            <b>Điện thoại: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
</table>

<br />
<br />

<b>
    <u>BÊN BÁN:</u> (BÊN B)
</b>
<br />

<table width="100%" cellspacing="0" cellpadding="5">
    <tr>
        <td width="20%">
            <b>Đơn vị: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
    <tr>
        <td width="20%">
            <b>Mã số thuế: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
    <tr>
        <td width="20%">
            <b>Người đại diện: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
    <tr>
        <td width="20%">
            <b>Chức vụ: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
    <tr>
        <td width="20%">
            <b>Địa chỉ: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
    <tr>
        <td width="20%">
            <b>Điện thoại: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
    <tr>
        <td width="20%">
            <b>Tài khoản: </b>
        </td>
        <td width="80%">
            : sadasdasdsadsa
        </td>
    </tr>
</table>

</font>
<br />
<b><i>Hai bên thống nhất ký kết Hợp đồng với các điều khoản sau:</i></b>
';
$pdf->Ln(30);
$pdf->writeHTML($tblTable, true, false, false, false, '');

// // The Table
// $pdf->Ln(3);
// // $item_width = 38;
// // // If show item taxes is disabled in PDF we should increase the item width table heading
// // if (get_option('show_tax_per_item') == 0) {
// //     $item_width = $item_width + 15;
// // }
// // // Header
// // $qty_heading = _l('invoice_table_quantity_heading');

// $tblHtml = '
// <table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="1">
//     <tr height="30" bgcolor="' . get_option('pdf_table_heading_color') . '" style="color:' . get_option('pdf_table_heading_text_color') . ';">
//         <th width="5%;" valign="middle" align="center">#</th>
//         <th width="10%" valign="middle" align="center">
//             <i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip"></i>'. _l('item_code').'</th>
//         <th width="25%" valign="middle" align="center">'. _l('item_name') .'</th>
//         <th width="10%" valign="middle" align="center">'. _l('item_unit') .'</th>
//         <th width="10%" valign="middle" align="center">'. _l('item_quantity') .'</th>
//         <th width="10%" valign="middle" align="center">'. _l('item_price_buy') .'</th>
//         <th width="10%" valign="middle" align="center">'. _l('tax') . '</th>
//         <th width="20%" valign="middle" align="center">'. _l('purchase_total_price') . '</th>
//     </tr>
//         ';
// // Items

// $i=0;
// $totalPrice = 0;

// foreach($purchase_order->products as $value) {
//     // print_r($value);
//     // exit();
//     $i++;
//     $tblHtml .= '
//         <tr>
//             <td>'.$i.'</td>
//             <td>'.$value->product_code.'</td>
//             <td>'.$value->product_code.'</td>
//             <td>'.$value->unit.'</td>
//             <td style="text-align:center">'.number_format($value->product_quantity).'</td>
//             <td style="text-align:right">'.number_format($value->product_price_buy).'</td>
//             <td>'.$value->rate.'</td>
//             <td style="text-align:right">'.number_format($value->product_quantity*$value->product_price_buy + ($value->product_quantity*$value->product_price_buy)* ($value->rate)/100).'</td>
//         </tr>
//     ';
//     $totalPrice += ($value->product_quantity*$value->product_price_buy + ($value->product_quantity*$value->product_price_buy)* $value->rate/100);
// }

// $tblHtml .= '
//         <tr>
//             <td colspan="5" style="text-align: right">'._l('purchase_total_price').'</td>
//             <td colspan="3" style="text-align: right">' . number_format($totalPrice). '</td>
//         </tr>
//         <tr>
//             <td colspan="5" style="text-align: right">'._l('purchase_total_items').'</td>
//             <td colspan="3" style="text-align: right">' . number_format($i). '</td>
//         </tr>
// ';
// $tblHtml .= '</table>';
// $pdf->writeHTML($tblHtml, true, false, false, false, '');

// // print_r($tblHtml);
// // exit();

// // $detail = _l('user_head').': <b>' . $purchase_suggested->user_head_name . '</b> <br /> <br />';
// // $detail .= _l('user_admin').': <b>' . $purchase_suggested->user_admin_name . '</b> <br /> <br />';
// $pdf->Ln(20);
// $table = "<table style=\"width: 100%;text-align: center\" border=\"0\">
//         <tr>
//             <td><b>" . mb_ucfirst(_l('orders_user_create'), "UTF-8") . "</b>
//             <br />(ký, ghi rõ họ tên)</td>
//             <td><b>" . mb_ucfirst(_l('user_head'), "UTF-8") . "</b>
//             <br />(ký, ghi rõ họ tên)</td>
//         </tr>
//         <tr>
//             <td style=\"height: 100px\" colspan=\"3\"></td>
//         </tr>
        
// </table>";
// $pdf->writeHTML($table, true, false, false, false, '');