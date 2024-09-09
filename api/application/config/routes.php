<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
$route['default_controller'] = '';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* API */
$route['v1/register'] = 'api/Auth/register';
$route['v1/password/reset-request'] = 'api/Auth/passwordResetRequest';
// $route['v1/password'] = 'api/Auth/register';
$route['v1/login'] = 'api/Auth/login';
$route['v1/logout'] = 'api/Auth/logout';
// $route['reGenToken'] = 'api/Token/reGenToken';

// AUTH USER
// Order
$route['v1/orders/(:num)/(:num)'] = 'api/Order/orderDetails/$1/$2';
$route['v1/orders/(:num)/buyers/([a-z]+)'] = 'api/Order/orderBuyer/$1/$2';
$route['v1/orders/(:num)/sellers/([a-z]+)'] = 'api/Order/orderSeller/$1/$2';

// Requests
$route['v1/requests/(:num)/sellers/categories'] = 'api/Request/categories/$1';
$route['v1/requests/(:num)/sellers/offers-sent'] = 'api/Request/offersSent/$1';
$route['v1/requests/(:num)/sellers/([a-z]+)'] = 'api/Request/sellerBuyerRequest/$1/$2';
$route['v1/requests/(:num)/buyers/(:num)/([a-z]+)']['PUT'] = 'api/Request/updateRequestStatus/$1/$2/$3';
$route['v1/requests/(:num)/buyers/([a-z]+)']['GET'] = 'api/Request/sellerManageRequest/$1/$2';
$route['v1/requests/(:num)/buyers/(:num)']['DELETE'] = 'api/Request/$1/$2';
$route['v1/requests/(:num)/buyers/(:num)/offers']['GET'] = 'api/Request/requestOffers/$1/$2';
$route['v1/requests/(:num)/my-offers']['GET'] = 'api/Request/myOffersInRequest/$1';
$route['v1/requests/(:num)/my-offers']['POST'] = 'api/Request/addMyOffersInRequest/$1';
$route['v1/requests/(:num)/my-offers']['PUT'] = 'api/Request/updateMyOffersInRequest/$1';
$route['v1/requests/(:num)/my-offers']['DELETE'] = 'api/Request/deleteMyOffersInRequest/$1';
$route['v1/requests']['POST'] = 'api/Request/addRequest';

// Membership
$route['v1/memberships/(:num)'] = 'api/Membership/membershipData/$1';

// Reviews
$route['v1/proposals/(:num)/reviews'] = 'api/Review/$1';

// Proposals
$route['v1/proposals/(:num)'] = 'api/Proposal/getDetail/$1';
$route['v1/proposals/(:num)/([a-z]+)'] = 'api/Proposal/$1/$2';
$route['v1/proposals/(:num)/(:num)/([a-z]+)']['PUT'] = 'api/Proposal/updateStatus/$1/$2/$3';
$route['v1/proposals']['POST'] = 'api/Proposal';
$route['v1/proposals/edit/(:num)']['GET'] = 'api/Proposal/getEditProposalDetail/$1';
$route['v1/proposals/edit/(:num)']['POST'] = 'api/Proposal/editProposal/$1';
$route['v1/proposals/(:num)'] = 'api/Proposal/$1';

// Coupons
$route['v1/proposals/(:num)/(:num)/coupons'] = 'api/Coupon/$1/$2';
$route['v1/proposals/(:num)/(:num)/coupons'] = 'api/Coupon/$1/$2';
$route['v1/proposals/(:num)/(:num)/coupons/(:num)']['PUT'] = 'api/Coupon/$1/$2/$3';
$route['v1/proposals/(:num)/(:num)/coupons/(:num)']['DELETE'] = 'api/Coupon/$1/$2/$3';
// Referrals
$route['v1/proposals/(:num)/(:num)/referrals'] = 'api/Referral/$1/$2';


// Purchases
$route['v1/purchases/(:num)'] = 'api/Purchase/$1';

// Manage Contacts
$route['v1/contacts/(:num)/([a-z]+)'] = 'api/Contact/$1/$2';
$route['v1/contacts/(:num)/([a-z]+)/(:num)/buying-histories'] = 'api/Contact/getHistories/$1/$2/$3';
$route['v1/contacts/(:num)/([a-z]+)/(:num)/selling-histories'] = 'api/Contact/getHistories/$1/$2/$3';

$route['v1/referrals/(:num)/my-referrals'] = 'api/Referral/getMyRefferals/$1';
$route['v1/referrals/(:num)/my-proposals'] = 'api/Referral/getMyProposalRefferals/$1';

$route['v1/categories/get-categories'] = 'api/Search/getPluckCategories';
$route['v1/categories/search-properties'] = 'api/Search/getSearchProperties';
$route['v1/categories/search'] = 'api/Search/getSearch';

$route['v1/search'] = 'api/Search';

$route['v1/freelancers/search-properties'] = 'api/Freelancer/getSearchProperties';
$route['v1/freelancers'] = 'api/Freelancer';

$route['v1/revenue/(:num)'] = 'api/Revenue/$1';

// Customer Support
$route['v1/customer-supports/(:num)'] = 'api/CustomerSupport/index/$1';

// User
$route['v1/users/profile'] = 'api/User/index';
$route['v1/users/change-password'] = 'api/User/changePassword';
$route['v1/users/profile'] = 'api/User/changeProfile';

// Professionl occupation category & skills
$route['v1/occupations/categories'] = 'api/Helper/occupationsCategory';
$route['v1/occupations/categories/(:num)'] = 'api/Helper/occupationsCategoryOption/$1';
$route['v1/occupations/skills'] = 'api/Helper/occupationsSkill';

// User professional
$route['v1/users/professionals/([a-zA-Z]+)'] = 'api/User/changeProfessional/$1';

// Favorites
$route['v1/users/favorites'] = 'api/User/favorite';
$route['v1/users/favorites/(:num)']['DELETE'] = 'api/User/favorite/$1';

// Notifications
$route['v1/users/notifications'] = 'api/User/notification';
$route['v1/users/notifications/(:num)']['DELETE'] = 'api/User/notification/$1';
