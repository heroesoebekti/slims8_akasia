<?php
/**
* Copyright (C) 2019 Heru Subekti (heroe.soebekti@gmail.com)
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
*
*/

// be sure that this file not accessed directly
if (!defined('INDEX_AUTH')) {
  die("can not access this file directly");
} elseif (INDEX_AUTH != 1) {
  die("can not access this file directly");
}

if($sysconf['index']['type'] != 'elastic_search') {
  exit();
} else {
    if(isset($_SESSION['aggs'])){
      foreach ($_SESSION['aggs'] as $key => $val) {
        if($val['sum_other_doc_count']>=0){
        echo '<h4 class="cluster-title">'.__(ucwords(str_replace('_', ' ',$key))).'</h4>'."\n";
        $field = $key;
        echo '<ul class="cluster-list">'."\n"; 
            foreach ($val['buckets'] as $key => $value) {
            if($value['key'] !='' && $value['key'] !="-" ){
              echo '<li class="cluster-item" style="list-style:none;background-image:none;background-repeat:none;background-position:0;">';
              echo '<a href="'.$_SERVER['HTTP_REFERER'].'&field['.$field.']='.urlencode(stripslashes(stripslashes($value['key']))).'">'. stripslashes(stripslashes($value['key']));
              echo '<span class="cluster-item-count pull-right">'.$value['doc_count'].'</span></a></li>';
            }
            }
            echo '</ul>';
          }
      }  
    unset($_SESSION['aggs']);   
    }   
  }

