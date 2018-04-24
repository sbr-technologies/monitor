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
  | When you set this option to TRUE, it will replace ALL dashes with
  | underscores in the controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* --------ADDED FOR ADMIN EDIT-------- */
$route['admin-profile'] = "Auth/editProfile";
$route['admin-profile-update'] = "Auth/updateProfile";
$route['admin-profile-picture'] = "Auth/updateProfilePicture";
$route['admin-check-password'] = "Auth/checkProfilePassword";

/* ------CUSTOM URL CONFIGURATION----- */
$route['dashbaord'] = "dashboard/index";
$route['logout'] = "auth/logout";

/* ---------CATEGORY SEGMENTATION ---------- */
$route['category-manager'] = "CategoryManagement/index";
$route['ajaxMainCategory'] = "CategoryManagement/ajaxMainCategory";
$route['add-category'] = "CategoryManagement/addCategoryManager";
$route['edit-category/([a-zA-Z0-9]+)'] = "CategoryManagement/editCategoryManager";

/* ---------BUSINESS FEATURE ----------------- */
$route['business-feature-manager'] = "BusinessFeatureMaster/index";
$route['add-business-feature'] = "BusinessFeatureMaster/addBusinessFeatureMaster";
$route['ajaxBusinessFeatureMaster'] = "BusinessFeatureMaster/ajaxBusinessFeatureMaster";
$route['edit-business-feature/([a-zA-Z0-9]+)'] = "BusinessFeatureMaster/editBusinessFeatureMaster";

/* ---------BUSINESS FEATURE ATTRIBUTE----------------- */
$route['business-feature-attribute'] = "BusinessFeatureMaster/businessFeatureAttribute";
$route['add-business-feature-attribute'] = "BusinessFeatureMaster/addBusinessFeatureAttribute";
$route['ajaxBusinessFeatureAttribute'] = "BusinessFeatureMaster/ajaxBusinessFeatureAttribute";
$route['edit-business-feature-attribute/([a-zA-Z0-9]+)'] = "BusinessFeatureMaster/editBusinessFeatureAttribute";

/* ---------PRICE CHART MANAGER ----------------- */
$route['price-chart-manager'] = "PriceChartManagement/index";
$route['ajaxPriceChartManager'] = "PriceChartManagement/ajaxPriceChartManager";
$route['add-price-chart'] = "PriceChartManagement/addPriceChart";
$route['edit-price-chart/([a-zA-Z0-9]+)'] = "PriceChartManagement/editPriceChart";

/* ---------PRICE CHART DETAILS ----------------- */
$route['price-chart-details'] = "PriceChartManagement/priceChartDetails";
$route['ajaxPriceChartDetails'] = "PriceChartManagement/ajaxPriceChartDetails";
$route['add-price-details'] = "PriceChartManagement/addPriceDetails";
$route['edit-price-details/([a-zA-Z0-9]+)'] = "PriceChartManagement/editPriceChartDetails";

/* ---------SERVICE AREA----------- */
$route['service-area'] = "ServiceAreaManagement/index";
$route['add-service-area'] = "ServiceAreaManagement/addServiceArea";
$route['edit-service-area/([a-zA-Z0-9]+)'] = "ServiceAreaManagement/editServiceArea";
$route['ajaxServiceArea'] = "ServiceAreaManagement/ajaxServiceArea";

/* ---------ADDITIONAL LANGUAGE----------- */
$route['additional-language'] = "AdditionalLanguageController/index";
$route['add-additional-language'] = "AdditionalLanguageController/addAdditionalLanguage";
$route['edit-additional-language/([a-zA-Z0-9]+)'] = "AdditionalLanguageController/editAdditionalLanguage";
$route['ajaxAdditionalLanguage'] = "AdditionalLanguageController/ajaxAdditionalLanguage";

/*--------BUSINESS CHARACTERISTICS---------*/
$route['business-characteristics'] = "BusinessCharacteristicsController/index";
$route['add-business-characteristics'] = "BusinessCharacteristicsController/addBusinessCharacteristics";
$route['edit-business-characteristics/([a-zA-Z0-9]+)'] = "BusinessCharacteristicsController/editBusinessCharacteristics";
$route['ajax-business-characteristics'] = "BusinessCharacteristicsController/ajaxBusinessCharacteristics";

/*--------EXPERTISE AREA---------*/
$route['expertise-area'] = "ExpertiseAreaController/index";
$route['add-expertise-area'] = "ExpertiseAreaController/addExpertiseArea";
$route['edit-expertise-area/([a-zA-Z0-9]+)'] = "ExpertiseAreaController/editExpertiseArea";  
$route['ajax-expertise-area'] = "ExpertiseAreaController/ajaxExpertiseArea";

/* ----------ADD AVAILABILITY MANAGER-------------------- */
$route['availability-manager'] = "AvailabilityManagement/index";
$route['ajaxavailabilitymanager'] = "AvailabilityManagement/ajaxAvailabilityManager";
$route['add-availability'] = "AvailabilityManagement/addAvailabilityManager";
$route['edit-availability-manager/([a-zA-Z0-9]+)'] = "AvailabilityManagement/editAvailabilityManager";

/* ---------BUSINESS MANAGEMENT----------------- */
$route['all-business'] = "BusinessManagement/allBusiness";
$route['ajax-business-list'] = "BusinessManagement/ajaxbusinessList";
$route['add-business'] = "BusinessManagement/addBusiness";
$route['edit-business/([a-zA-Z0-9]+)'] = "BusinessManagement/editBusiness";
$route['business-information/([a-zA-Z0-9]+)'] = "BusinessManagement/businessInformation";
$route['add-additional-information-business'] = "BusinessManagement/addAdditionalInformationBusiness";

/* -------------BLOG MANAGEMENT-------------- */
$route['blogcategory-list'] = "BlogManagement/index";
$route['add-blog-category'] = "BlogManagement/addBlogCategory";
$route['edit-blog-category/([a-zA-Z0-9]+)'] = "BlogManagement/editBlogCategory";
$route['ajaxBlogCategory'] = "BlogManagement/ajaxBlogCategory";

/* ----------ADVERTISE MANAGEMENT---------- */
$route['advertisement-type'] = "AdvertisementManagement/advertisementType";
$route['advertisement-type-list-ajax'] = "AdvertisementManagement/ajaxAdvertisementTypeList";
$route['add-advertisement-type'] = "AdvertisementManagement/addAdvertisementType";
$route['edit-advertisement-type/([a-zA-Z0-9]+)'] = "AdvertisementManagement/editAdvertisementType";
$route['delete-advertisement-type'] = "AdvertisementManagement/deleteAdvertisementType";
$route['add-new-advertisement'] = "AdvertisementManagement/addNewAdvertisement";
$route['advertisement-list'] = "AdvertisementManagement/advertisementList";
$route['edit-advertisement/([a-zA-Z0-9]+)'] = "AdvertisementManagement/editAdvertisement";

/*-------------------CMS/PAGE MANAGEMENT------------------*/
$route['static-image-list'] = "CmsManagement/index";
$route['ajaxImageList'] = "CmsManagement/ajaxImageList";
$route['edit-static-image/([a-zA-Z0-9]+)'] = "CmsManagement/editStaticImage";
$route['page-list'] = "CmsManagement/pageList";
$route['seo-setting/([a-z]+)'] = "CmsManagement/seoSetting";
$route['language-setting'] = "CmsManagement/languageSetting";
$route['ajaxLanguageContent'] = "CmsManagement/ajaxLanguageContent";
$route['add-new-content'] = "CmsManagement/addNewContent";
$route['edit-content/([a-zA-Z0-9]+)'] = "CmsManagement/editContent";
$route['generate-cache'] = "CmsManagement/generateCache";

/*-------------------BLOG MANAGEMENT------------------*/
$route['blogmaster-list'] = "BlogManagement/blogmasterList";
$route['add-blogmaster'] = "BlogManagement/addBlogMaster";
$route['ajaxBlogMaster'] = "BlogManagement/ajaxBlogMaster";
$route['edit-blogmaster/([a-zA-Z0-9]+)'] = "BlogManagement/editBlogMaster";

/* ---------USER MANAGEMENT FEATURE ----------------- */
$route['customers-list'] = "UserManagement/index";
$route['ajaxCustomerList'] = "UserManagement/ajaxCustomerList";
/* ------New Code for User management feature (change password)------- */
$route['customers-change-password/([a-zA-Z0-9]+)'] = "UserManagement/customersChangePassword";
$route['customers-edit-password/([a-zA-Z0-9]+)'] = "UserManagement/customersEditPassword";
/* ================================================== */
$route['vendors/list'] = "UserManagement/vendorList";
$route['ajaxVendorList'] = "UserManagement/ajaxVendorList";










