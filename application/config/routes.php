<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller']                        = 'home';
$route['login']                                     = 'account/login';
$route['login/banned']                              = 'account/login/banned';
$route['login/facebook']                            = 'account/login_facebook';
$route['login/twitter']                             = 'account/login_twitter';
$route['login/google']                              = 'account/login_google';
$route['logout']                                    = 'account/logout';
$route['everify/(:num)/(:any)']                     = 'account/everify/$1/$2';
$route['change_email/(:any)']                       = 'account/change_email/$1';
$route['register']                                  = 'account/register';
$route['register/invitation/(:any)']                = 'account/register/$1';
$route['forgot_password']                           = 'account/forgot_password';
$route['change_password/(:any)']                    = 'account/change_password/$1';

$route['knowledge-base/article/(:any)']             = 'support/article/$1';
$route['knowledge-base/(:any)']                     = 'support/kb_category/$1';
$route['knowledge-base/(:any)/(:num)']              = 'support/kb_category/$1';
$route['knowledge-base/(:any)/(:any)']              = 'support/kb_category/$1/$2';
$route['knowledge-base/(:any)/(:any)/(:num)']       = 'support/kb_category/$1/$2';
$route['search']                                    = 'support/search';
$route['search/(:num)']                             = 'support/search';
$route['faqs']                                      = 'support/faqs';

// @version 1.6
$route['create_ticket']                             = 'support/create_ticket';
$route['tverify/(:num)/(:any)']                     = 'home/tverify/$1/$2';

// @version 1.4
$route['ticket/guest/(:any)/(:num)']                = 'support/guest_ticket/$1/$2';

$route['dashboard']                                 = 'user/dashboard';
$route['admin/dashboard']                           = 'user/dashboard/admin';
$route['admin/notifications']                       = 'user/account/notifications/admin';
$route['admin/notifications/(:num)']                = 'user/account/notifications/admin/$1';
$route['admin/read_notification/(:num)']            = 'user/account/read_notification/admin/$1';
$route['admin/account/profile_settings']            = 'user/account/profile_settings/admin';
$route['actions/admin/account/change_password']     = 'actions/user/account/change_password';
$route['user/notifications']                        = 'user/account/notifications/user';
$route['user/notifications/(:num)']                 = 'user/account/notifications/user/$1';
$route['user/read_notification/(:num)']             = 'user/account/read_notification/user/$1';
$route['user/sessions/(:num)']                      = 'user/tools/sessions/$1';
$route['user/sessions']                             = 'user/tools/sessions';

$route['admin/tickets/all']                         = 'admin/support/all_tickets';
$route['admin/tickets/all/(:num)']                  = 'admin/support/all_tickets/$1';
$route['admin/tickets/opened']                      = 'admin/support/opened_tickets';
$route['admin/tickets/opened/(:num)']               = 'admin/support/opened_tickets/$1';
$route['admin/tickets/closed']                      = 'admin/support/closed_tickets';
$route['admin/tickets/closed/(:num)']               = 'admin/support/closed_tickets/$1';
$route['admin/tickets/assigned']                    = 'admin/support/assigned_tickets';
$route['admin/tickets/assigned/(:num)']             = 'admin/support/assigned_tickets/$1';
$route['admin/tickets/ticket/(:num)']               = 'admin/support/ticket/$1';
$route['admin/tickets/history/(:num)']              = 'admin/support/ticket_history/$1';
$route['admin/tickets/history/(:num)/page/(:num)']  = 'admin/support/ticket_history/$1/page/$2';
$route['admin/tickets/history/(:num)/page']         = 'admin/support/ticket_history/$1/page/1';

// @version 1.1
$route['admin/tickets/create_ticket']               = 'admin/support/create_ticket';

// @version 1.4
$route['admin/chats/all']                           = 'admin/support/chats/all';
$route['admin/chats/all/(:num)']                    = 'admin/support/chats/all/$1';
$route['admin/chats/assigned']                      = 'admin/support/chats/assigned';
$route['admin/chats/assigned/(:num)']               = 'admin/support/chats/assigned/$1';
$route['admin/chats/active']                        = 'admin/support/chats/active';
$route['admin/chats/active/(:num)']                 = 'admin/support/chats/active/$1';
$route['admin/chats/ended']                         = 'admin/support/chats/ended';
$route['admin/chats/ended/(:num)']                  = 'admin/support/chats/ended/$1';
$route['admin/chats/chat/(:num)']                   = 'admin/support/chat/$1';
$route['admin/report/(:num)']                       = 'admin/reports/report/$1';

$route['admin/knowledge_base/categories']           = 'admin/support/articles_categories';
$route['admin/knowledge_base/subcategories']        = 'admin/support/articles_categories/sub';
$route['admin/knowledge_base/articles']             = 'admin/support/articles';
$route['admin/knowledge_base/articles/list/(:num)'] = 'admin/support/articles/list/$1';
$route['admin/knowledge_base/articles/list']        = 'admin/support/articles/list/1';
$route['admin/knowledge_base/new_article']          = 'admin/support/articles/new';
$route['admin/knowledge_base/edit_article/(:num)']  = 'admin/support/articles/edit/$1';
$route['terms']                                     = 'home/page/1';
$route['privacy-policy']                            = 'home/page/2';

// @version 1.8:
$route['page/(:any)']                               = 'home/custom_page/$1';

$route['404_override']                              = '';
$route['translate_uri_dashes']                      = FALSE;
