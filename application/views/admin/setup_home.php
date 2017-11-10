<?php init_head(); ?>
<style type="text/css">
   .admin #side-menu, .admin #setup-menu {
   background: #e9ebef;
   }
   body.hide-sidebar #wrapper #side-menu li:hover {
     margin-left: 0px; 
    }
   #side-menu li > a {
   border: 2px dashed #000;
   color: #000;
   text-transform: uppercase;
   padding: 12px 20px 12px 16px;
   font-size: 13px;
   }
   #side-menu.nav>li>a:focus, #side-menu.nav>li>a:hover {
   border-bottom: 2px dashed #000 !important;
   }
   #side-menu li .nav-second-level li a:hover{
    padding: 8px 10px 8px 45px;
   }
   #side-menu li .nav-second-level li a {
   padding: 7px 10px 7px 45px;
   color: #0181BB;
   text-transform: none;
   font-size: 14px;
   border: 0px;
   }
   #side-menu li{
   margin: 15px 0px;
   }
   .drop-title{
   padding: 0px;
   font-size: 25px;
   /* padding: 60px 0px 70px 0px; */
   /* margin-bottom: 25px; */
   border-radius: 50%;
   width: 200px;
   background: red;
   height: 200px;
   margin: auto;
   position: relative;
   }
   .drop-title a{
   /* background: red; */
   /* border-radius: 50%; */   
   /* height: 50px; */
   color: #fff;
   /* width: 50px; */
   /* padding-top: 53px; */
   top: 69px;
   left: 10px;
   }
</style>
<div id="wrapper" style="min-height: auto !important">
   <div class="content" style="position: relative;">
      <h2 class="text-center tc padding">QUẢN LÝ DOANH NGHIỆP</h2>
      
      <div style="display: inline-block;
    position: absolute;
    top: :0px;
    right: 58px;
    top: 27px;"><?php if($total_qa_removed != count($_quick_actions)){ ?>
<li class="quick-links" style="list-style: none;">
  <div class="dropdown dropdown-quick-links" >
    <a href="#" class="dropdown-toggle btn btn-primary" id="dropdownQuickLinks" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
     <i class="fa fa-table" aria-hidden="true"></i><span style="margin-left: 10px;">DANH MỤC</span>
   </a>
   <ul class="dropdown-menu" aria-labelledby="dropdownQuickLinks">
     <?php
      

     foreach($_quick_actions as $key => $item){
      $url = '';
      if(isset($item['permission'])){
       if(!has_permission($item['permission'],'','create')){
        continue;
      }
    }
    if(isset($item['custom_url'])){
     $url = $item['url'];
   } else {
     $url = admin_url(''.$item['url']);
   }
   $href_attributes = '';
   if(isset($item['href_attributes'])){
     foreach ($item['href_attributes'] as $key => $val) {
      $href_attributes .= $key . '=' . '"' . $val . '"';
    }
  }
  ?>
  <li>
    <a href="<?php echo $url; ?>" <?php echo $href_attributes; ?>><?php echo $item['name']; ?></a>
  </li>
  <?php } ?>
</ul>
</div>

</li>
<?php } ?></div>
      <div class="row">
         <?php  if(has_permission('customers', '', 'view') || has_permission('quote_items', '', 'view') || has_permission('contracts', '', 'view') || has_permission('tasks', '', 'view') || has_permission('customers', '', 'view_own') || has_permission('quote_items', '', 'view_own') || has_permission('contracts', '', 'view_own') || has_permission('tasks', '', 'view_own') ) { ?>
         <div class="col-md-3">
            <?php
               $total_qa_removed = 0;
               foreach($_quick_actions as $key => $item){
               if(isset($item['permission'])){
               if(!has_permission($item['permission'],'','create')){
               $total_qa_removed++;
               }
               }
               }
               
               ?>
            <?php
               do_action('before_render_aside_menu');
               // $menu_active = get_option('aside_menu_active');
               if(isset($_SESSION['type_role']))
               {
                 $menu_active = get_option($_SESSION['type_role']);
               }
               else
               {
                 $menu_active = get_option('aside_menu_active');
               }
               ?>
            <div class="drop-title text-center" style=" background: #f43737;">
               <a href="<?=admin_url('clients')?>">
                  <div style="height: 100%">
                     <span style="position: relative; top: 29%;">QUẢN LÝ<br> KINH DOANH</span>
                  </div>
               </a>
            </div>
            <aside class="sidebar">
               <ul class="nav metis-menu" id="side-menu">

                  <?php
                     $menu_active = json_decode($menu_active);
                     $m = 0;
                     
                     foreach($menu_active->aside_menu_active as $item){
                     // als_sale_contracts
                      // als_quotations
                     if($item->id == 'customers'  || $item->id == 'tasks'  || $item->id == 'quote_items' || $item->id == 'contracts' || $item->id == '_email_marketing'){
                     $submenu = false;
                     $remove_main_menu = false;
                     $url = '';
                     if(isset($item->children)){
                       $submenu = true;
                       $total_sub_items_removed = 0;
                       foreach($item->children as $_sub_menu_check){
                        if(isset($_sub_menu_check->permission) && !empty($_sub_menu_check->permission) && $_sub_menu_check->permission != 'payments'){
                         if(!has_permission($_sub_menu_check->permission,'','view') && !has_permission($_sub_menu_check->permission, '', 'view_own')){
                          $total_sub_items_removed++;
                        }
                      } else if($_sub_menu_check->permission == 'payments'){
                        if(!has_permission('payments','','view') && !has_permission('invoices','','view_own')){
                          $total_sub_items_removed++;
                        }
                      }
                     }
                     if($total_sub_items_removed == count($item->children)){
                       $submenu = false;
                       $remove_main_menu = true;
                     }
                     } else {
                       if($item->url == '#'){continue;}
                       $url = $item->url;
                     }
                     if($remove_main_menu == true){
                       continue;
                     }
                     $url = $item->url;
                     if(!_startsWith($url,'http://') && $url != '#'){
                      $url = admin_url($url);
                     }
                     
                     ?>
                     <?php if(has_permission($item->id,'','view') || has_permission($item->id,'','view_own') ){ ?>
                    <li class="menu-item-<?php echo $item->id; ?> <?php echo ($item->children) ? 'drop' : '' ?> ">
                     <a href="<?php echo ($item->children) ? 'javascript:void(0)' : $url; ?>" aria-expanded="false"><i class="<?php echo $item->icon; ?> menu-icon"></i>
                     <?php echo _l($item->name); ?>
                     <?php if($submenu == true){ ?>
                     <span class="fa arrow"></span>
                     <?php } ?>
                     </a>
                     <?php if(isset($item->children)){ ?>
                     <ul class="nav nav-second-level collapse" aria-expanded="false">
                        <?php foreach($item->children as $submenu){
                           if(isset($submenu->permission) && !empty($submenu->permission) && $submenu->permission != 'payments'){
                            if(!has_permission($submenu->permission,'','view') && !has_permission($submenu->permission, '', 'view_own')){
                              continue;
                            }
                           } else if($submenu->permission == 'payments'){
                           if(!has_permission('payments','','view') && !has_permission('invoices','','view_own')){
                             continue;
                           }
                           }
                           $url = $submenu->url;
                           if(!_startsWith($url,'http://')){
                           $url = admin_url($url);
                           }
                           ?>
                        <li class="sub-menu-item-<?php echo $submenu->id; ?>"><a href="<?php echo $url; ?>">
                           <?php if(!empty($submenu->icon)){ ?>
                           <i class="<?php echo $submenu->icon; ?> menu-icon"></i>
                           <?php } ?>
                           <?php echo _l($submenu->name); ?></a>
                        </li>
                        <?php } ?>
                     </ul>
                     <?php } ?>
                  </li>
                  <?php } ?>
                  <?php
                     $m++;
                     do_action('after_render_single_aside_menu',$m); ?>
                  <?php } ?>
                  <?php do_action('after_render_aside_menu'); ?>
                  <?php if(count($_pinned_projects) > 0){ ?>
                  <li class="pinned-separator"></li>
                  <?php foreach($_pinned_projects as $_pinned_project){ ?>
                  <li class="pinned_project">
                     <a href="<?php echo admin_url('projects/view/'.$_pinned_project['id']); ?>" data-toggle="tooltip" data-title="<?php echo _l('pinned_project'); ?>"><?php echo $_pinned_project['name']; ?></a>
                     <div class="col-md-12">
                        <div class="progress progress-bar-mini">
                           <div class="progress-bar no-percent-text not-dynamic" role="progressbar" data-percent="<?php echo $_pinned_project['progress']; ?>" style="width: <?php echo $_pinned_project['progress']; ?>%;">
                           </div>
                        </div>
                     </div>
                  </li>
                  <?php } } ?>
                  <?php } ?>
                  
                  <li><a href="#"><i class="fa fa-calendar menu-icon"></i>QUẢN LÝ SMS</a></li>
                  
               </ul>
            </aside>
         </div>
         <?php } ?>
         <div class="col-md-3">
            <?php
               $total_qa_removed = 0;
               foreach($_quick_actions as $key => $item){
               if(isset($item['permission'])){
               if(!has_permission($item['permission'],'','create')){
               $total_qa_removed++;
               }
               }
               }
               
               ?>
            <?php
               do_action('before_render_aside_menu');
               // $menu_active = get_option('aside_menu_active');
               if(isset($_SESSION['type_role']))
               {
                 $menu_active = get_option($_SESSION['type_role']);
               }
               else
               {
                 $menu_active = get_option('aside_menu_active');
               }
               ?>
            <div class="drop-title text-center" style=" background: #f43737;">
               <a href="<?=admin_url('staff')?>">
                  <div style="height: 100%">
                     <span style="position: relative; top: 29%;">QUẢN LÝ<br> KẾ TOÁN</span>
                  </div>
               </a>
            </div>
            <aside class="sidebar">
               <ul class="nav metis-menu" id="side-menu">
                  <?php
                     $menu_active = json_decode($menu_active);
                     $m = 0;
                     
                     foreach($menu_active->aside_menu_active as $item){
                     
                     
                     if($item->id == 'products' || $item->id == 'reports' || $item->id == 'staff' || $item->id == 'import_goods' || $item->id == 'export_warehouses'   || $item->id == 'sales' || $item->id == 'als_purchase'){
                     $submenu = false;
                     $remove_main_menu = false;
                     $url = '';
                     if(isset($item->children)){
                       $submenu = true;
                       $total_sub_items_removed = 0;
                       foreach($item->children as $_sub_menu_check){
                        if(isset($_sub_menu_check->permission) && !empty($_sub_menu_check->permission) && $_sub_menu_check->permission != 'payments'){
                         if(!has_permission($_sub_menu_check->permission,'','view') && !has_permission($_sub_menu_check->permission, '', 'view_own')){
                          $total_sub_items_removed++;
                        }
                      } else if($_sub_menu_check->permission == 'payments'){
                        if(!has_permission('payments','','view') && !has_permission('invoices','','view_own')){
                          $total_sub_items_removed++;
                        }
                      }
                     }
                     if($total_sub_items_removed == count($item->children)){
                       $submenu = false;
                       $remove_main_menu = true;
                     }
                     } else {
                       if($item->url == '#'){continue;}
                       $url = $item->url;
                     }
                     if($remove_main_menu == true){
                       continue;
                     }
                     $url = $item->url;
                     if(!_startsWith($url,'http://') && $url != '#'){
                      $url = admin_url($url);
                     }
                     
                     ?>
                  <li class="menu-item-<?php echo $item->id; ?> <?php echo ($item->children) ? 'drop' : '' ?> ">
                     <a href="<?php echo ($item->children) ? 'javascript:void(0)' : $url; ?>" aria-expanded="false"><i class="<?php echo $item->icon; ?> menu-icon"></i>
                     <?php echo _l($item->name); ?>
                     <?php if($submenu == true){ ?>
                     <span class="fa arrow"></span>
                     <?php } ?>
                     </a>
                     <?php if(isset($item->children)){ ?>
                     <ul class="nav nav-second-level collapse" aria-expanded="false">
                        <?php foreach($item->children as $submenu){
                           if(isset($submenu->permission) && !empty($submenu->permission) && $submenu->permission != 'payments'){
                            if(!has_permission($submenu->permission,'','view') && !has_permission($submenu->permission, '', 'view_own')){
                              continue;
                            }
                           } else if($submenu->permission == 'payments'){
                           if(!has_permission('payments','','view') && !has_permission('invoices','','view_own')){
                             continue;
                           }
                           }
                           $url = $submenu->url;
                           if(!_startsWith($url,'http://')){
                           $url = admin_url($url);
                           }
                           ?>
                        <li class="sub-menu-item-<?php echo $submenu->id; ?>"><a href="<?php echo $url; ?>">
                           <?php if(!empty($submenu->icon)){ ?>
                           <i class="<?php echo $submenu->icon; ?> menu-icon"></i>
                           <?php } ?>
                           <?php echo _l($submenu->name); ?></a>
                        </li>
                        <?php } ?>
                     </ul>
                     <?php } ?>
                  </li>
                  <?php
                     $m++;
                     do_action('after_render_single_aside_menu',$m); ?>
                  <?php } ?>
                  <?php do_action('after_render_aside_menu'); ?>
                  <?php if(count($_pinned_projects) > 0){ ?>
                  <li class="pinned-separator"></li>
                  <?php foreach($_pinned_projects as $_pinned_project){ ?>
                  <li class="pinned_project">
                     <a href="<?php echo admin_url('projects/view/'.$_pinned_project['id']); ?>" data-toggle="tooltip" data-title="<?php echo _l('pinned_project'); ?>"><?php echo $_pinned_project['name']; ?></a>
                     <div class="col-md-12">
                        <div class="progress progress-bar-mini">
                           <div class="progress-bar no-percent-text not-dynamic" role="progressbar" data-percent="<?php echo $_pinned_project['progress']; ?>" style="width: <?php echo $_pinned_project['progress']; ?>%;">
                           </div>
                        </div>
                     </div>
                  </li>
                  <?php } } ?>
                  <?php } ?>
               </ul>
            </aside>
         </div>
         <div class="col-md-3">
            <div class="drop-title text-center" style=" background:#f58632;">
               <a   href="<?=admin_url('clients')?>">
                  <div style="height: 100%">
                     <span style="position: relative; top: 24%;">QUẢN LÝ<br>KỸ THUẬT<br>VÀ LẮP ĐẶT</span>
                  </div>
               </a>
            </div>
           <aside class="sidebar">
               <ul class="nav metis-menu" id="side-menu">

                  <?php
                     $total_qa_removed = 0;
                     foreach($_quick_actions as $key => $item){
                      if(isset($item['permission'])){
                       if(!has_permission($item['permission'],'','create')){
                        $total_qa_removed++;
                      }
                     }
                     }
                     
                     ?>
                  <?php
                     do_action('before_render_aside_menu');
                     // $menu_active = get_option('aside_menu_active');
                     if(isset($_SESSION['type_role']))
                     {
                       $menu_active = get_option($_SESSION['type_role']);
                     }
                     else
                     {
                       $menu_active = get_option('aside_menu_active');
                     }
                     
                     
                     $menu_active = json_decode($menu_active);
                     $m = 0;
                     
                     foreach($menu_active->aside_menu_active as $item){
                     
                     
                     if($item->id == 'fg'){
                     $submenu = false;
                     $remove_main_menu = false;
                     $url = '';
                     if(isset($item->children)){
                       $submenu = true;
                       $total_sub_items_removed = 0;
                       foreach($item->children as $_sub_menu_check){
                        if(isset($_sub_menu_check->permission) && !empty($_sub_menu_check->permission) && $_sub_menu_check->permission != 'payments'){
                         if(!has_permission($_sub_menu_check->permission,'','view') && !has_permission($_sub_menu_check->permission, '', 'view_own')){
                          $total_sub_items_removed++;
                        }
                      } else if($_sub_menu_check->permission == 'payments'){
                        if(!has_permission('payments','','view') && !has_permission('invoices','','view_own')){
                          $total_sub_items_removed++;
                        }
                      }
                     }
                     if($total_sub_items_removed == count($item->children)){
                       $submenu = false;
                       $remove_main_menu = true;
                     }
                     } else {
                       if($item->url == '#'){continue;}
                       $url = $item->url;
                     }
                     if($remove_main_menu == true){
                       continue;
                     }
                     $url = $item->url;
                     if(!_startsWith($url,'http://') && $url != '#'){
                      $url = admin_url($url);
                     }
                     
                     ?>
                  <li class="menu-item-<?php echo $item->id; ?> <?php echo ($item->children) ? 'drop' : '' ?> ">
                     <a href="<?php echo ($item->children) ? 'javascript:void(0)' : $url; ?>" aria-expanded="false"><i class="<?php echo $item->icon; ?> menu-icon"></i>
                     <?php echo _l($item->name); ?>
                     <?php if($submenu == true){ ?>
                     <span class="fa arrow"></span>
                     <?php } ?>
                     </a>
                     <?php if(isset($item->children)){ ?>
                     <ul class="nav nav-second-level collapse" aria-expanded="false">
                        <?php foreach($item->children as $submenu){
                           if(isset($submenu->permission) && !empty($submenu->permission) && $submenu->permission != 'payments'){
                            if(!has_permission($submenu->permission,'','view') && !has_permission($submenu->permission, '', 'view_own')){
                              continue;
                            }
                           } else if($submenu->permission == 'payments'){
                           if(!has_permission('payments','','view') && !has_permission('invoices','','view_own')){
                             continue;
                           }
                           }
                           $url = $submenu->url;
                           if(!_startsWith($url,'http://')){
                           $url = admin_url($url);
                           }
                           ?>
                        <li class="sub-menu-item-<?php echo $submenu->id; ?>"><a href="<?php echo $url; ?>">
                           <?php if(!empty($submenu->icon)){ ?>
                           <i class="<?php echo $submenu->icon; ?> menu-icon"></i>
                           <?php } ?>
                           <?php echo _l($submenu->name); ?></a>
                        </li>
                        <?php } ?>
                        
                     </ul>
                     <?php } ?>
                  </li>
                  <?php
                     $m++;
                     do_action('after_render_single_aside_menu',$m); ?>
                  <?php } ?>
                  <?php do_action('after_render_aside_menu'); ?>
                  <?php if(count($_pinned_projects) > 0){ ?>
                  <li class="pinned-separator"></li>
                  <?php foreach($_pinned_projects as $_pinned_project){ ?>
                  <li class="pinned_project">
                     <a href="<?php echo admin_url('projects/view/'.$_pinned_project['id']); ?>" data-toggle="tooltip" data-title="<?php echo _l('pinned_project'); ?>"><?php echo $_pinned_project['name']; ?></a>
                     <div class="col-md-12">
                        <div class="progress progress-bar-mini">
                           <div class="progress-bar no-percent-text not-dynamic" role="progressbar" data-percent="<?php echo $_pinned_project['progress']; ?>" style="width: <?php echo $_pinned_project['progress']; ?>%;">
                           </div>
                        </div>
                     </div>
                  </li>
                  <?php } } ?>
                  <?php } ?>
                  <li>
                    <a href="#"><i class="fa fa-plus-square menu-icon"></i>THÊM YÊU CẦU LẮP ĐẶT</a>

                  </li>
                   <li>
                    <a href="#"><i class="fa fa-list-alt menu-icon"></i>DANH SÁCH LẮP ĐẶT</a>
                   
                  </li>
               </ul>
            </aside> 
         </div>
         <div class="col-md-3">
            <div class="drop-title text-center" style=" background:#175f96;">
               <a   href="<?=admin_url('clients')?>">
                  <div style="height: 100%">
                     <span style="position: relative; top: 29%;">QUẢN LÝ<br>BẢO HÀNH</span>
                  </div>
               </a>
            </div>
           <aside class="sidebar">
               <ul class="nav metis-menu" id="side-menu">
                  <?php
                     $total_qa_removed = 0;
                     foreach($_quick_actions as $key => $item){
                      if(isset($item['permission'])){
                       if(!has_permission($item['permission'],'','create')){
                        $total_qa_removed++;
                      }
                     }
                     }
                     
                     ?>
                  <?php
                     do_action('before_render_aside_menu');
                     // $menu_active = get_option('aside_menu_active');
                     if(isset($_SESSION['type_role']))
                     {
                       $menu_active = get_option($_SESSION['type_role']);
                     }
                     else
                     {
                       $menu_active = get_option('aside_menu_active');
                     }
                     
                     
                     $menu_active = json_decode($menu_active);
                     $m = 0;
                     
                     foreach($menu_active->aside_menu_active as $item){
                     
                     
                     if($item->id == 'ff'){
                     $submenu = false;
                     $remove_main_menu = false;
                     $url = '';
                     if(isset($item->children)){
                       $submenu = true;
                       $total_sub_items_removed = 0;
                       foreach($item->children as $_sub_menu_check){
                        if(isset($_sub_menu_check->permission) && !empty($_sub_menu_check->permission) && $_sub_menu_check->permission != 'payments'){
                         if(!has_permission($_sub_menu_check->permission,'','view') && !has_permission($_sub_menu_check->permission, '', 'view_own')){
                          $total_sub_items_removed++;
                        }
                      } else if($_sub_menu_check->permission == 'payments'){
                        if(!has_permission('payments','','view') && !has_permission('invoices','','view_own')){
                          $total_sub_items_removed++;
                        }
                      }
                     }
                     if($total_sub_items_removed == count($item->children)){
                       $submenu = false;
                       $remove_main_menu = true;
                     }
                     } else {
                       if($item->url == '#'){continue;}
                       $url = $item->url;
                     }
                     if($remove_main_menu == true){
                       continue;
                     }
                     $url = $item->url;
                     if(!_startsWith($url,'http://') && $url != '#'){
                      $url = admin_url($url);
                     }
                     
                     ?>
                  <li class="menu-item-<?php echo $item->id; ?> <?php echo ($item->children) ? 'drop' : '' ?> ">
                     <a href="<?php echo ($item->children) ? 'javascript:void(0)' : $url; ?>" aria-expanded="false"><i class="<?php echo $item->icon; ?> menu-icon"></i>
                     <?php echo _l($item->name); ?>
                     <?php if($submenu == true){ ?>
                     <span class="fa arrow"></span>
                     <?php } ?>
                     </a>
                     <?php if(isset($item->children)){ ?>
                     <ul class="nav nav-second-level collapse" aria-expanded="false">
                        <?php foreach($item->children as $submenu){
                           if(isset($submenu->permission) && !empty($submenu->permission) && $submenu->permission != 'payments'){
                            if(!has_permission($submenu->permission,'','view') && !has_permission($submenu->permission, '', 'view_own')){
                              continue;
                            }
                           } else if($submenu->permission == 'payments'){
                           if(!has_permission('payments','','view') && !has_permission('invoices','','view_own')){
                             continue;
                           }
                           }
                           $url = $submenu->url;
                           if(!_startsWith($url,'http://')){
                           $url = admin_url($url);
                           }
                           ?>
                        <li class="sub-menu-item-<?php echo $submenu->id; ?>"><a href="<?php echo $url; ?>">
                           <?php if(!empty($submenu->icon)){ ?>
                           <i class="<?php echo $submenu->icon; ?> menu-icon"></i>
                           <?php } ?>
                           <?php echo _l($submenu->name); ?></a>
                        </li>
                        <?php } ?>
                     </ul>
                     <?php } ?>
                  </li>
                  <?php
                     $m++;
                     do_action('after_render_single_aside_menu',$m); ?>
                  <?php } ?>
                  <?php do_action('after_render_aside_menu'); ?>
                  <?php if(count($_pinned_projects) > 0){ ?>
                  <li class="pinned-separator"></li>
                  <?php foreach($_pinned_projects as $_pinned_project){ ?>
                  <li class="pinned_project">
                     <a href="<?php echo admin_url('projects/view/'.$_pinned_project['id']); ?>" data-toggle="tooltip" data-title="<?php echo _l('pinned_project'); ?>"><?php echo $_pinned_project['name']; ?></a>
                     <div class="col-md-12">
                        <div class="progress progress-bar-mini">
                           <div class="progress-bar no-percent-text not-dynamic" role="progressbar" data-percent="<?php echo $_pinned_project['progress']; ?>" style="width: <?php echo $_pinned_project['progress']; ?>%;">
                           </div>
                        </div>
                     </div>
                  </li>
                  <?php } } ?>
                  <?php } ?>
                  <li>
                    <a href="#"><i class="fa fa-plus-square menu-icon"></i>THÊM YÊU CẦU BẢO HÀNH</a>

                  </li>
                   <li>
                    <a href="#"><i class="fa fa-list-alt menu-icon"></i>DANH SÁCH BẢO HÀNH</a>
                   
                  </li>
               </ul>
            </aside>
         </div>
      </div>
   </div>
</div>
</div>
<script>
   google_api = '<?php echo $google_api_key; ?>';
   calendarIDs = '<?php echo json_encode($google_ids_calendars); ?>';
</script>
<?php init_tail(); ?>
<script type="text/javascript">
   $('.drop').click(function() {
       $(this).find('.nav-second-level').toggleClass('collapse');
   })
</script>
</body>
</html>