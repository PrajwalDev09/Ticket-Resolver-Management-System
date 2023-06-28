<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['use_page_numbers']   = true;
$config['reuse_query_string'] = true;
$config['attributes']         = ['class' => 'page-link'];
$config['full_tag_open']      = '<ul class="pagination pagination-sm float-right m-3 mb-0">';
$config['full_tag_close']     = '</ul>';
$config['first_tag_open']     = '<li class="page-item">';
$config['first_tag_close']    = '</li>';
$config['last_tag_open']      = '<li class="page-item">';
$config['last_tag_close']     = '</li>';
$config['next_link']          = '&raquo;';
$config['next_tag_open']      = '<li class="page-item">';
$config['next_tag_close']     = '</li>';
$config['prev_link']          = '&laquo;';
$config['prev_tag_open']      = '<li class="page-item">';
$config['prev_tag_close']     = '</li>';
$config['cur_tag_open']       = '<li class="page-item active"><a href="javascript:void(0)" class="page-link">';
$config['cur_tag_close']      = '</a></li>';
$config['num_tag_open']       = '<li class="page-item">';
$config['num_tag_close']      = '</li>';
