<!-- END QUICK NAV -->
<!--[if lt IE 9]>
<script src="admin_ui/global/plugins/respond.min.js"></script>
<script src="admin_ui/global/plugins/excanvas.min.js"></script> 
<script src="admin_ui/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="admin_ui/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="admin_ui/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="admin_ui/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="admin_ui/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="admin_ui/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<?php if (is_presented_in_url("admin-profile") || is_presented_in_url("add-blogmaster") || is_presented_in_url("edit-blogmaster") || is_presented_in_url("edit-static-image")): ?>
    <script src="admin_ui/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/sweetalert-master/dist/sweetalert-dev.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-price-chart") || is_presented_in_url("edit-price-chart") || is_presented_in_url("add-category") || is_presented_in_url("edit-category") || is_presented_in_url("add-business") || is_presented_in_url("edit-business") || is_presented_in_url("add-blogmaster") || is_presented_in_url("edit-blogmaster")): ?>
    <script src="admin_ui/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-blogmaster") || is_presented_in_url("edit-blogmaster")): ?>
    <script src="admin_ui/global/plugins/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("webadmin/dashboard")): ?>
<?php endif; ?>

<?php if (is_presented_in_url("language-setting") || is_presented_in_url("advertisement-type") || is_presented_in_url("all-business") || is_presented_in_url("business-characteristics") || is_presented_in_url('expertise-area') || is_presented_in_url("additional-language") || is_presented_in_url("service-area") || is_presented_in_url("category-manager") || is_presented_in_url("business-feature-manager") || is_presented_in_url("business-feature-attribute") || is_presented_in_url('customers-list') || is_presented_in_url("price-chart-manager") || is_presented_in_url("price-chart-details") || is_presented_in_url("availability-manager") || is_presented_in_url("blogcategory-list") || is_presented_in_url("blogmaster-list") || is_presented_in_url("static-image-list")): ?>
    <?php if (is_presented_in_url("all-business")) { ?>
        <script src="admin_ui/global/scripts/datatable_customized.js" type="text/javascript"></script>
    <?php } else { ?>
        <script src="admin_ui/global/scripts/datatable.js" type="text/javascript"></script>
    <?php } ?>
    <script src="admin_ui/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/sweetalert-master/dist/sweetalert-dev.js" type="text/javascript"></script>
<?php endif; ?>

<?php
if (is_presented_in_url("add-new-content") || is_presented_in_url("edit-content") || is_presented_in_url("edit-advertisement-type") || is_presented_in_url("add-advertisement-type") || is_presented_in_url("edit-business") || is_presented_in_url("add-business") || is_presented_in_url("add-availability") || is_presented_in_url("edit-availability-manager") || is_presented_in_url("edit-additional-language") || is_presented_in_url("add-expertise-area") || is_presented_in_url('edit-expertise-area') || is_presented_in_url('edit-business-characteristics') || is_presented_in_url("add-business-characteristics") || is_presented_in_url("add-additional-language") || is_presented_in_url("edit-service-area") || is_presented_in_url("add-service-area") || is_presented_in_url("add-category") || is_presented_in_url('edit-business-feature-attribute') || is_presented_in_url('add-business-feature-attribute') || is_presented_in_url("edit-category") ||
        is_presented_in_url("add-business-feature") || is_presented_in_url("edit-business-feature") ||
        is_presented_in_url("add-price-chart") || is_presented_in_url('edit-price-chart') || is_presented_in_url('add-price-details') || is_presented_in_url("edit-price-details") || is_presented_in_url("add-blog-category") || is_presented_in_url("edit-blog-category") || is_presented_in_url("add-blogmaster") || is_presented_in_url("edit-blogmaster")):
    ?>
    <script src="admin_ui/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <?php
    if (is_presented_in_url("edit-business") || is_presented_in_url("add-business") || is_presented_in_url("add-business-feature") || is_presented_in_url('add-business-feature-attribute') || is_presented_in_url('edit-business-feature-attribute') || is_presented_in_url("edit-business-feature") ||
            is_presented_in_url("add-price-chart") || is_presented_in_url('edit-price-chart') || is_presented_in_url('add-price-details') || is_presented_in_url("edit-price-details") || is_presented_in_url("add-blogmaster") || is_presented_in_url("edit-blogmaster")):
        ?>
        <script src="admin_ui/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <?php endif; ?>
    <script src="admin_ui/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/sweetalert-master/dist/sweetalert-dev.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("seo-setting")): ?>
    <script src="admin_ui/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/sweetalert-master/dist/sweetalert-dev.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("business-information")): ?>
    <script src="admin_ui/global/plugins/sweetalert-master/dist/sweetalert-dev.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js" type="text/javascript"></script>
    <script src="admin_ui/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<?php endif; ?>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="admin_ui/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<?php if (is_presented_in_url("add-business-feature-attribute")): ?>
    <script src="admin_ui/pages/scripts/main/add-business-feature-attribute.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-business-feature-attribute")): ?>
    <script src="admin_ui/pages/scripts/main/edit-business-feature-attribute.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("admin-profile")): ?>
    <script src="admin_ui/pages/scripts/main/profile.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("webadmin/dashboard")): ?>
    <script src="admin_ui/pages/scripts/main/dashboard.min.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("category-manager")): ?>
    <script src="admin_ui/pages/scripts/main/category-manager.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-category")): ?>
    <script src="admin_ui/pages/scripts/main/add-category.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-category")): ?>
    <script src="admin_ui/pages/scripts/main/edit-category.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-business-feature")): ?>
    <script src="admin_ui/pages/scripts/main/add-business-feature.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-business-feature")): ?>
    <script src="admin_ui/pages/scripts/main/edit-feature.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("business-feature-manager")): ?>
    <script src="admin_ui/pages/scripts/main/business-feature-manager.js" type="text/javascript"></script>
<?php endif; ?>  
<?php if (is_presented_in_url("service-area")): ?>
    <script src="admin_ui/pages/scripts/main/service-area.js" type="text/javascript"></script>   
<?php endif; ?> 
<?php if (is_presented_in_url("business-feature-attribute")): ?>
    <script src="admin_ui/pages/scripts/main/business-feature-attribute.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("price-chart-manager")): ?>
    <script src="admin_ui/pages/scripts/main/price-chart-manager.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-price-chart")): ?>
    <script src="admin_ui/pages/scripts/main/add-price-chart.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url('edit-price-chart')): ?>
    <script src="admin_ui/pages/scripts/main/edit-price-chart.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url('add-business')): ?> 
    <script src="admin_ui/pages/scripts/main/add-business.js" type="text/javascript"></script>
<?php endif; ?> 
<?php if (is_presented_in_url('customers-list')): ?>
    <script src="admin_ui/pages/scripts/main/customer-manager.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("price-chart-details")): ?>
    <script src="admin_ui/pages/scripts/main/price-chart-details.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-price-details")): ?>
    <script src="admin_ui/pages/scripts/main/add-price-details.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-price-details")): ?>
    <script src="admin_ui/pages/scripts/main/edit-price-details.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-service-area")): ?>
    <script src="admin_ui/pages/scripts/main/add-service-area.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-service-area")): ?>
    <script src="admin_ui/pages/scripts/main/edit-service-area.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("additional-language")): ?>
    <script src="admin_ui/pages/scripts/main/additional-language.js" type="text/javascript"></script>
<?php endif; ?>  
<?php if (is_presented_in_url("business-characteristics")): ?>
    <script src="admin_ui/pages/scripts/main/business-characteristics.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("availability-manager")): ?>
    <script src="admin_ui/pages/scripts/main/availability-manager.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-availability")): ?>
    <script src="admin_ui/pages/scripts/main/add-availability-manager.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-availability-manager")): ?>
    <script src="admin_ui/pages/scripts/main/edit-availability-manager.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-additional-language")): ?>
    <script src="admin_ui/pages/scripts/main/add-additional-language.js" type="text/javascript"></script>
<?php endif; ?> 
<?php if (is_presented_in_url("add-business-characteristics")): ?>
    <script src="admin_ui/pages/scripts/main/add-business-characteristics.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-business-characteristics")): ?>
    <script src="admin_ui/pages/scripts/main/edit-business-characteristics.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-additional-language")): ?>
    <script src="admin_ui/pages/scripts/main/edit-additional-language.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("all-business")): ?>
    <script src="admin_ui/pages/scripts/main/all-business.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("blogcategory-list")): ?>
    <script src="admin_ui/pages/scripts/main/blogcategory-list.js" type="text/javascript"></script>   
<?php endif; ?> 
<?php if (is_presented_in_url("add-blog-category")): ?>
    <script src="admin_ui/pages/scripts/main/add-blog-category.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-blog-category")): ?>
    <script src="admin_ui/pages/scripts/main/edit-blog-category.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("blogmaster-list")): ?>
    <script src="admin_ui/pages/scripts/main/blogmaster-list.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-blogmaster")): ?>
    <script src="admin_ui/pages/scripts/main/add-blogmaster.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url('add-expertise-area')): ?>
    <script src="admin_ui/pages/scripts/main/add-expertise-area.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url('edit-expertise-area')): ?>
    <script src="admin_ui/pages/scripts/main/edit-expertise-area.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-business")): ?>
    <script src="admin_ui/pages/scripts/main/edit-business.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("advertisement-type")): ?>
    <script src="admin_ui/pages/scripts/main/advertisement-type.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-blogmaster")): ?>
    <script src="admin_ui/pages/scripts/main/edit-blogmaster.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-advertisement-type")): ?>
    <script src="admin_ui/pages/scripts/main/add-advertisement-type.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-advertisement-type")): ?>
    <script src="admin_ui/pages/scripts/main/edit-advertisement-type.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("business-information")): ?>
    <script src="admin_ui/pages/scripts/main/business-information.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("static-image-list")): ?>
    <script src="admin_ui/pages/scripts/main/static-image-list.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-static-image")): ?>
    <script src="admin_ui/pages/scripts/main/edit-static-image.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("seo-setting")): ?>
    <script src="admin_ui/pages/scripts/main/seo-setting.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("language-setting")): ?>
    <script src="admin_ui/pages/scripts/main/language-setting.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("add-new-content")): ?>
    <script src="admin_ui/pages/scripts/main/add-new-content.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url("edit-content")): ?>
    <script src="admin_ui/pages/scripts/main/edit-content.js" type="text/javascript"></script>
<?php endif; ?>
<?php if (is_presented_in_url('expertise-area')): ?>
    <script src="admin_ui/pages/scripts/main/expertise-area.js" type="text/javascript"></script>
<?php endif; ?>
<!-- END PAGE LEVEL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="admin_ui/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->