<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved
| routes must come before any wildcard or regular expression routes.
|
*/

//$route['default_controller'] = "core";
$route['default_controller'] = "articler";
$route[':num'] = "articler";
$route['scaffolding_trigger'] = "";
$route['migration/(.*)'] = "migration/$1";
$route['admin/(.*)'] = "admin/$1";
$route['admin'] = "admin/admin";
$route['admin/logout'] = "admin/logout";
$route['admin/delete_cache'] = "admin/delete_cache";
$route['avtory'] = "articler/list_authors";
$route['avtory/:num'] = "articler/list_authors";
$route['avtory/(.*)'] = "articler/profile/$1";
$route['avtory/(.*)/:num'] = "articler/profile/$1";
$route['authors'] = "articler/list_authors";
$route['authors/:num'] = "articler/list_authors";
$route['authors/(.*)'] = "articler/profile/$1";
$route['authors/(.*)/:num'] = "articler/profile/$1";
$route['transit'] = "articler/transit";
$route['publish'] = "articler/publish";
$route['publish/update'] = "articler/publish";
$route['publish/update/:num'] = "articler/publish/update";
$route['publish/delete/:num'] = "articler/delete";
$route['publish/check/:num'] = "articler/check";
$route['publish/add/:num'] = "articler/add";
$route['publish/cancel/:num'] = "articler/cancel";
$route['list_articles'] = "articler/list_articles";
$route['list_articles/:num'] = "articler/list_articles";
$route['list_comments'] = "articler/list_comments";
$route['list_comments/:num'] = "articler/list_comments";
$route['moderator/public_articles'] = "articler/moderator";
$route['moderator/public_articles/:num'] = "articler/moderator";
$route['moderator/moderate_articles'] = "articler/moderator";
$route['moderator/pleas'] = "articler/list_pleas";
$route['moderator/make_payout/:num'] = "articler/make_payout";
$route['moderator/refers'] = "articler/list_refers";
$route['moderator/refers/:num'] = "articler/list_refers";
$route['moderator/refers/branch/:num'] = "articler/refer";
$route['moderator/refer_settings'] = 'articler/refer_settings';
$route['moderator/refer_settings/add_refer'] = 'articler/refer_settings';
$route['moderator/refer_exceptions/add'] = 'articler/refer_exception';
$route['moderator/refer_exceptions/delete/:num'] = 'articler/refer_exception';
$route['moderator/payouts'] = "articler/list_payouts";
$route['moderator/payouts/open'] = "articler/list_payouts";
$route['moderator/payouts/open/:num'] = "articler/list_payouts";
$route['moderator/payouts/closed'] = "articler/list_payouts";
$route['moderator/payouts/closed/:num'] = "articler/list_payouts";
$route['moderator/moderate_articles/:num'] = "articler/moderator";
$route['moderator/resolution/:num'] = "articler/moderator";
$route['moderator/article/:num'] = "articler/moderator";
$route['moderator/rubricator'] = "articler/rubricator";
$route['moderator/rubric_add'] = "articler/rubric_add";
$route['moderator/rubric_update/:num'] = "articler/rubric_update";
$route['moderator/adverts'] = "articler/adverts";
$route['moderator/advert_article/:num'] = "articler/advert_article";
$route['moderator/advert_article/save/:num'] = "articler/save_advert_article";
$route['moderator/advert_article/remove/:num/:num'] = "articler/remove_advert_article";
$route['moderator/advert/articles/:num'] = "articler/advert_articles";
$route['moderator/advert/articles/:num/:num'] = "articler/advert_articles";
$route['moderator/advert/articles/:num/heading/:num'] = "articler/advert_articles";
$route['moderator/advert/articles/:num/heading/:num/:num'] = "articler/advert_articles";
$route['moderator/advert/articles/:num/heading/:num/(all|setted|unsetted)'] = "articler/advert_articles";
$route['moderator/advert/articles/:num/heading/:num/(all|setted|unsetted)/:num'] = "articler/advert_articles";
$route['moderator/advert/headings/:num'] = "articler/advert_headings";
$route['moderator/advert_add'] = "articler/advert_add";
$route['moderator/default_advert_update/:num'] = "articler/default_advert_update";
$route['moderator/advert_update/:num'] = "articler/advert_update";
$route['moderator/advert_delete/:num'] = "articler/advert_delete";
$route['raw/article/:num'] = "articler/article";
$route['moderate/article/:num'] = "articler/article";
$route['moderator/edit/:num'] = "articler/moderator_edit";
$route['moderator/change_rating/:any'] = "articler/change_rating";
$route['moderator/change_article_rating/:num'] = "articler/change_article_rating";
$route['all_comments/:num'] = 'articler/all_comments';
$route['comment/add_plus/:num'] = 'articler/add_plus';
$route['comment/add_minus/:num'] = 'articler/add_minus';
$route['comment/add_reply/:num'] = 'articler/add_reply';
$route['comment/add_plea/:num'] = 'articler/add_plea';
$route['comment/delete/:num'] = 'articler/delete_comment';
$route['comment/edit/:num'] = 'articler/edit_comment';
$route['comment/delete_plus/:num'] = 'articler/delete_plus';
$route['check_refer'] = 'articler/check_refer_exception';
$route['pesochnica'] = 'articler/sandbox';
$route['pesochnica/:any'] = 'articler/sandbox';
$route['sandbox'] = 'articler/sandbox';
$route['sandbox/:any'] = 'articler/sandbox';
$route['private/list_articles'] = 'articler/private_list_articles';
$route['private/add'] = 'articler/private_add';
$route['private/update/:num'] = 'articler/private_update';
$route['private/delete/:num'] = 'articler/private_delete';
$route['private/:any'] = 'articler/private_article';
$route['moderator/giventopics'] = 'articler/giventopics_list';
$route['moderator/giventopic/add'] = 'articler/giventopic_add';
$route['moderator/giventopic/update/:num'] = 'articler/giventopic_update';
$route['moderator/giventopic/delete/:num'] = 'articler/giventopic_delete';
$route['booking'] = 'articler/booking';
$route['search'] = 'articler/search';
$route['search/:num'] = 'articler/search';
//$route['pravila-servisa.html'] = 'articler/rules';
$route['pravila-servisa.html'] = 'articler/rules';
$route['rules.html'] = 'articler/rules';
$route['contact'] = 'articler/contact';
$route['contact.html'] = 'articler/contact';
$route['enter'] = 'articler/enter';
$route['avtor/finance'] = "articler/author_finance";
$route['avtor/profile'] = "articler/author_profile";
$route['avtor/refers'] = "articler/author_refers";
$route['avtor/refers/:num'] = "articler/author_refers";
$route['avtor/profile/set_name'] = "articler/author_profile";
$route['author/finance'] = "articler/author_finance";
$route['author/profile'] = "articler/author_profile";
$route['author/refers'] = "articler/author_refers";
$route['author/refers/:num'] = "articler/author_refers";
$route['author/profile/set_name'] = "articler/author_profile";

include_once('heading_routes.php');

$route[':any/:any\.html'] = 'articler/article';
//$route[':any'] = "articler/articles";

// You can remove next lines after instalation.
//----------------------------------------------
//$route['install/(.*)'] = "install/$1";
//$route['install'] = "install"; 
//----------------------------------------------

$route['sitemap.xml']    = 'sitemap/build_xml_map';
$route['sitemap.xml.gz'] = 'sitemap/gzip';
$route[':any/:num'] = 'articler/articles';
$route[':any'] = "core";

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */