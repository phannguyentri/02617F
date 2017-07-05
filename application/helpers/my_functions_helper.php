<?php
defined('BASEPATH') OR exit('No direct script access allowed');

add_action('after_render_single_aside_menu','my_custom_menu_items');

// function my_custom_menu_items($order){
//     if($order == 1){
//         echo '<li><a href="#">Send mail</a></li>';
//     }

// }
// function my_custom_menu_items($order){
//     if($order == 2){
//         echo '<li><a href="#"><i class="fa fa-area-chart menu-icon" aria-hidden="true"></i>Test</a></li>';
//     }
// }
// function my_custom_menu_items($order){
//     if($order == 2){
//         echo '<li><a href="#"><i class="fa fa-area-chart menu-icon" aria-hidden="true"></i>My First Custom Item</a></li>';
//         echo '<li><a href="#"><i class="fa fa-area-chart menu-icon" aria-hidden="true"></i>My Second Custom Item</a></li>';
//     }
// }
// function my_custom_menu_items($order){
//     if($order == 1){
//         echo '<li>';
//         echo '<a href="#" aria-expanded="false"><i class="fa fa-balance-scale menu-icon"></i>
//             My Custom Menu<span class="fa arrow"></span>
//             </a>';

//         echo '<ul class="nav nav-second-level collapse" aria-expanded="false">
//                <li><a href="http://mydomain.com/url">Sub item</a></li>
//              </ul>';
//         echo '</li>';
//     }
// }
// function my_custom_menu_items($order){
//     if($order == 1){
//         if(has_permission('invoices','','view')){
//             echo '<li><a href="#"><i class="fa fa-area-chart menu-icon" aria-hidden="true"></i>Custom Item</a></li>';
//         }
//     }
// }

?>