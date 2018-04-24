<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
<link href="admin_ui/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="admin_ui/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
<link href="admin_ui/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<!-- END GLOBAL MANDATORY STYLES -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<?php if (is_presented_in_url("admin-profile") || is_presented_in_url("edit-static-image")): ?>
    <link href="admin_ui/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
    <link href="admin_ui/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="admin_ui/global/plugins/sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

<?php if (is_presented_in_url("add-price-chart") || is_presented_in_url("edit-price-chart") || is_presented_in_url("add-category") || is_presented_in_url("edit-category") || is_presented_in_url("add-business") || is_presented_in_url("edit-business") || is_presented_in_url("add-blogmaster") || is_presented_in_url("edit-blogmaster")): ?>
    <link href="admin_ui/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

<?php if (is_presented_in_url("add-blogmaster") || is_presented_in_url("edit-blogmaster")): ?>
    <link href="admin_ui/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />   
<?php endif; ?>

<?php if (is_presented_in_url("webadmin/dashboard")): ?>

<?php endif; ?>
<?php if (is_presented_in_url("seo-setting")): ?>
    <link href="admin_ui/global/plugins/sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css" />
<?php endif; ?>
<?php if (is_presented_in_url("language-setting") || is_presented_in_url("advertisement-type") || is_presented_in_url("all-business")||is_presented_in_url("business-characteristics") || is_presented_in_url("additional-language") || is_presented_in_url("service-area") || is_presented_in_url("category-manager") || is_presented_in_url("business-feature-attribute") || is_presented_in_url("business-feature-manager") || is_presented_in_url('customers-list') || is_presented_in_url("price-chart-manager") || is_presented_in_url("price-chart-details") || is_presented_in_url("availability-manager") || is_presented_in_url("blogcategory-list") || is_presented_in_url("static-image-list")): ?>
    <link href="admin_ui/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="admin_ui/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="admin_ui/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="admin_ui/global/plugins/sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css" />
<?php endif; ?>
<?php if (is_presented_in_url("edit-content") || is_presented_in_url("add-new-content") || is_presented_in_url("edit-advertisement-type") || is_presented_in_url("add-advertisement-type") || is_presented_in_url("add-business") || is_presented_in_url("edit-business") || is_presented_in_url("edit-additional-language")||is_presented_in_url('edit-expertise-area')|| is_presented_in_url('add-expertise-area')||is_presented_in_url("add-business-characteristics")||is_presented_in_url("edit-business-characteristics") || is_presented_in_url("add-additional-language") || is_presented_in_url("edit-service-area") || is_presented_in_url("add-service-area") || is_presented_in_url('edit-business-feature-attribute') || is_presented_in_url('add-business-feature-attribute') || is_presented_in_url("add-category") || is_presented_in_url("edit-category") || is_presented_in_url("add-business-feature") || is_presented_in_url("edit-business-feature") || is_presented_in_url("add-price-chart") || is_presented_in_url("edit-price-chart") || is_presented_in_url("add-price-details") || is_presented_in_url("edit-price-details") || is_presented_in_url("add-availability") || is_presented_in_url("edit-availability-manager") || is_presented_in_url("add-blog-category") || is_presented_in_url("edit-blog-category") || is_presented_in_url("add-blogmaster") || is_presented_in_url("edit-blogmaster")): ?>
    <?php if (is_presented_in_url("add-business") || is_presented_in_url("edit-business") || is_presented_in_url('edit-business-feature-attribute') || is_presented_in_url('add-business-feature-attribute') || is_presented_in_url("add-business-feature") || is_presented_in_url("edit-business-feature") || is_presented_in_url("add-price-chart") || is_presented_in_url("edit-price-chart") || is_presented_in_url("add-price-details") || is_presented_in_url("edit-price-details") || is_presented_in_url("add-blogmaster") || is_presented_in_url("edit-blogmaster")): ?>
        <link href="admin_ui/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="admin_ui/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <?php endif; ?>
    <link href="admin_ui/global/plugins/sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="admin_ui/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
<?php endif; ?>
<?php if (is_presented_in_url("business-information")): ?>
    <link href="admin_ui/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />  
    <link href="admin_ui/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <link href="admin_ui/global/plugins/sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="admin_ui/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="admin_ui/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<?php endif; ?>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME GLOBAL STYLES -->

<link href="admin_ui/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
<link href="admin_ui/global/css/plugins.min.css" rel="stylesheet" type="text/css" />

<!-- END THEME GLOBAL STYLES -->

<!-- BEGIN THEME LAYOUT STYLES -->
<link href="admin_ui/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />

<link href="admin_ui/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />

<link href="admin_ui/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
<!-- END THEME LAYOUT STYLES -->