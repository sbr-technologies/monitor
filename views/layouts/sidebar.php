<!-- BEGIN SIDEBAR --> 
<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <!-- END SIDEBAR TOGGLER BUTTON -->



            <li class="nav-item start <?= is_presented_in_url("dashboard") ? "active open" : "" ?>">
                <a href="<?= base_url('dashboard') ?>" class="nav-link nav-toggle">
                    <i class="fa fa-home"></i>
                    <span class="title">Dashboard</span>
                    <?php if (is_presented_in_url("dashboard")): ?>
                        <span class="selected"></span>
                    <?php endif; ?>
                </a>
            </li>

            <li class="heading">
                <h3 class="uppercase">Application Management</h3>
            </li>
            <li class="nav-item  <?php
            if (is_presented_in_url("category-manager") || is_presented_in_url("edit-category") || is_presented_in_url("add-category")): echo " active open";
            endif;
            ?>">
                <a href="<?= base_url("category-manager") ?>" class="nav-link nav-toggle">
                    <i class="fa fa-list"></i>
                    <span class="title">Category Management</span>
                    <?php if (is_presented_in_url("category-manager") || is_presented_in_url("edit-category") || is_presented_in_url("add-category")): ?>
                        <span class="selected"></span>
                    <?php endif; ?>
                </a>
            </li>

            <!--<li class="nav-item  <?php
            if (is_presented_in_url("business-feature-manager") || is_presented_in_url("add-business-feature") || is_presented_in_url("edit-business-feature") || is_presented_in_url("business-feature-attribute") || is_presented_in_url("add-business-feature-attribute") || is_presented_in_url("edit-business-feature-attribute")): echo " active open";
            endif;
            ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-diamond"></i>
                    <span class="title">Business Features</span>
                    <?php if (is_presented_in_url("business-feature-manager") || is_presented_in_url("add-business-feature") || is_presented_in_url("edit-business-feature") || is_presented_in_url("business-feature-attribute") || is_presented_in_url("add-business-feature-attribute") || is_presented_in_url("edit-business-feature-attribute")) : ?>
                        <span class="selected"></span>
                    <?php endif; ?>
                    <span class="arrow  <?php
                    if (is_presented_in_url('business-feature-manager') || is_presented_in_url("add-business-feature") || is_presented_in_url("edit-business-feature") || is_presented_in_url("business-feature-attribute") || is_presented_in_url("add-business-feature-attribute") || is_presented_in_url("edit-business-feature-attribute")): echo 'active open';
                    endif;
                    ?>"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  <?php
                    if (is_presented_in_url("business-feature-manager") || is_presented_in_url("add-business-feature") || is_presented_in_url("edit-business-feature")): echo " active open";
                    endif;
                    ?>">
                        <a href="<?= base_url("business-feature-manager") ?>" class="nav-link ">
                            <span class="title">Business Feature List</span>
                            <?php if (is_presented_in_url("business-feature-manager") || is_presented_in_url("add-business-feature") || is_presented_in_url("edit-business-feature")): ?>
                                <span class="selected"></span>
                            <?php endif; ?>

                        </a>
                    </li>
                    <li class="nav-item  <?php
                    if (is_presented_in_url("business-feature-attribute") || is_presented_in_url("add-business-feature-attribute") || is_presented_in_url("edit-business-feature-attribute")): echo " active open";
                    endif;
                    ?>">
                        <a href="<?= base_url("business-feature-attribute") ?>" class="nav-link ">
                            <span class="title">Business Feature Attribute</span>
                            <?php if (is_presented_in_url("business-feature-attribute") || is_presented_in_url("add-business-feature-attribute") || is_presented_in_url("edit-business-feature-attribute")): ?>
                                <span class="selected"></span>
                            <?php endif; ?>
                        </a>
                    </li>

                </ul>
            </li>-->




            <li class="nav-item  <?php
            if (is_presented_in_url('all-business') || is_presented_in_url('add-business') || is_presented_in_url('edit-business') || is_presented_in_url("business-information")): echo " active open";
            endif;
            ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                    <span class="title">Business Management</span>
                    <?php if (is_presented_in_url('all-business') || is_presented_in_url('add-business') || is_presented_in_url('edit-business') || is_presented_in_url("business-information")) : ?>
                        <span class="selected"></span>
                    <?php endif; ?>
                    <span class="arrow  <?php
                    if (is_presented_in_url('all-business') || is_presented_in_url('add-business') || is_presented_in_url('edit-business') || is_presented_in_url("business-information")): echo 'active open';
                    endif;
                    ?>"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  <?php
                    if (is_presented_in_url("all-business") || is_presented_in_url("add-business") || is_presented_in_url('edit-business') || is_presented_in_url("business-information")): echo " active open";
                    endif;
                    ?>">
                        <a href="<?= base_url("all-business") ?>" class="nav-link ">
                            <span class="title">All Business</span>
                            <?php if (is_presented_in_url("business-information") || is_presented_in_url("all-business") || is_presented_in_url('edit-business') || is_presented_in_url("add-business")): ?>
                                <span class="selected"></span>
                            <?php endif; ?>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="nav-item  <?php
            if (is_presented_in_url("price-chart-manager") || is_presented_in_url("add-price-chart") || is_presented_in_url("edit-price-chart") || is_presented_in_url("price-chart-details") || is_presented_in_url("add-price-details") || is_presented_in_url("edit-price-details")): echo " active open";
            endif;
            ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-usd" aria-hidden="true"></i>
                    <span class="title">Price Chart Management</span>
                    <?php if (is_presented_in_url("price-chart-manager") || is_presented_in_url("add-price-chart") || is_presented_in_url("edit-price-chart") || is_presented_in_url("price-chart-details") || is_presented_in_url("add-price-details") || is_presented_in_url("edit-price-details")) : ?>
                        <span class="selected"></span>
                    <?php endif; ?>
                    <span class="arrow  <?php
                    if (is_presented_in_url("price-chart-manager") || is_presented_in_url("add-price-chart") || is_presented_in_url("edit-price-chart") || is_presented_in_url("price-chart-details") || is_presented_in_url("add-price-details") || is_presented_in_url("edit-price-details")): echo 'active open';
                    endif;
                    ?>"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  <?php
                    if (is_presented_in_url("price-chart-manager") || is_presented_in_url("add-price-chart") || is_presented_in_url("edit-price-chart")): echo " active open";
                    endif;
                    ?>">
                        <a href="<?= base_url("price-chart-manager") ?>" class="nav-link ">
                            <span class="title">Price chart manager </span>
                            <?php if (is_presented_in_url("price-chart-manager") || is_presented_in_url("add-price-chart") || is_presented_in_url("edit-price-chart")): ?>
                                <span class="selected"></span>
                            <?php endif; ?>

                        </a>
                    </li>
                    <li class="nav-item  <?php
                    if (is_presented_in_url("price-chart-details") || is_presented_in_url("add-price-details") || is_presented_in_url("edit-price-details")): echo " active open";
                    endif;
                    ?>">
                        <a href="<?= base_url("price-chart-details") ?>" class="nav-link ">
                            <span class="title">Price chart details</span>
                            <?php if (is_presented_in_url("price-chart-details") || is_presented_in_url("add-price-details") || is_presented_in_url("edit-price-details")): ?>
                                <span class="selected"></span>
                            <?php endif; ?>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item  <?php
            if (is_presented_in_url("business-characteristics") || is_presented_in_url("add-business-characteristics") || is_presented_in_url("edit-business-characteristics")): echo " active open";
            endif;
            ?>">
                <a href="<?= base_url("business-characteristics") ?>" class="nav-link nav-toggle">
                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                    <span class="title">Business Characteristics</span>
                    <?php if (is_presented_in_url("business-characteristics") || is_presented_in_url("add-business-characteristics") || is_presented_in_url("edit-business-characteristics")): ?>
                        <span class="selected"></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item  <?php
            if (is_presented_in_url("expertise-area") || is_presented_in_url("add-expertise-area") || is_presented_in_url("edit-expertise-area")): echo " active open";
            endif;
            ?>">
                <a href="<?= base_url("expertise-area") ?>" class="nav-link nav-toggle">
                   <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                    <span class="title">Area of Expertise</span>
                    <?php if (is_presented_in_url("expertise-area") || is_presented_in_url("add-expertise-area") || is_presented_in_url("edit-expertise-area")): ?>
                        <span class="selected"></span>
                    <?php endif; ?>
                </a>
            </li>
           <!-- <li class="nav-item  <?php
            if (is_presented_in_url("service-area") || is_presented_in_url("add-service-area") || is_presented_in_url("edit-service-area")): echo " active open";
            endif;
            ?>">
                <a href="<?= base_url("service-area") ?>" class="nav-link nav-toggle">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <span class="title">Service Area</span>
                    <?php if (is_presented_in_url("service-area") || is_presented_in_url("add-service-area") || is_presented_in_url("edit-service-area")): ?>
                        <span class="selected"></span>
                    <?php endif; ?>
                </a>
            </li>-->
            <li class="nav-item  <?php
            if (is_presented_in_url("availability-manager") || is_presented_in_url("add-availability") || is_presented_in_url("edit-availability-manager")): echo " active open";
            endif;
            ?>">
                <a href="<?= base_url("availability-manager") ?>" class="nav-link nav-toggle">
                    <i class="fa fa-tachometer" aria-hidden="true"></i>
                    <span class="title">Availability Manager</span>
                    <?php if (is_presented_in_url("availability-manager") || is_presented_in_url("add-availability") || is_presented_in_url("edit-availability-manager")): ?>
                        <span class="selected"></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item  <?php
            if (is_presented_in_url("additional-language") || is_presented_in_url("add-additional-language") || is_presented_in_url("edit-additional-language")): echo " active open";
            endif;
            ?>">
                <a href="<?= base_url("additional-language") ?>" class="nav-link nav-toggle">
                    <i class="fa fa-arrows-alt" aria-hidden="true"></i>
                    <span class="title">Additional Language</span>
                    <?php if (is_presented_in_url("additional-language") || is_presented_in_url("add-additional-language") || is_presented_in_url("edit-additional-language")): ?>
                        <span class="selected"></span>
                    <?php endif; ?>
                </a>
            </li>

            <li class="nav-item  <?php
            if (is_presented_in_url('customers-list')): echo " active open";
            endif;
            ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-users"></i>
                    <span class="title">User management</span>
                    <?php if (is_presented_in_url('customers-list')) : ?>
                        <span class="selected"></span>
                    <?php endif; ?>
                    <span class="arrow  <?php
                    if (is_presented_in_url('customers-list')): echo 'active open';
                    endif;
                    ?>"></span>
                </a>
                <ul class="sub-menu">

                    <li class="nav-item  <?php
                    if (is_presented_in_url("customers-list")): echo " active open";
                    endif;
                    ?>">
                        <a href="<?= base_url("customers-list") ?>" class="nav-link ">
                            <span class="title">Users List</span>
                            <?php if (is_presented_in_url("customers-list")): ?>
                                <span class="selected"></span>
                            <?php endif; ?>
                        </a>
                    </li>

                </ul>
            </li>


            <li class="nav-item <?php
            if (is_presented_in_url('blogcategory-list') || is_presented_in_url('add-blog-category') || is_presented_in_url('edit-blog-category') || is_presented_in_url('blogmaster-list') || is_presented_in_url('add-blogmaster') || is_presented_in_url('edit-blogmaster')): echo " active open";
            endif;
            ?>">
                <a href="javascript:void(0);" class="nav-link nav-toggle">
                    <i class="fa fa-edit"></i>
                    <span class="title">Blog Management</span>
                    <?php if (is_presented_in_url('blogcategory-list') || is_presented_in_url('add-blog-category') || is_presented_in_url('edit-blog-category') || is_presented_in_url('blogmaster-list') || is_presented_in_url('add-blogmaster') || is_presented_in_url('edit-blogmaster')) : ?>
                        <span class="selected"></span>
                    <?php endif; ?>

                    <span class="arrow <?php
                    if (is_presented_in_url('blogcategory-list') || is_presented_in_url('add-blog-category') || is_presented_in_url('edit-blog-category') || is_presented_in_url('blogmaster-list') || is_presented_in_url('add-blogmaster') || is_presented_in_url('edit-blogmaster')): echo " active open";
                    endif;
                    ?>"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item <?php
                    if (is_presented_in_url('blogcategory-list') || is_presented_in_url('add-blog-category') || is_presented_in_url('edit-blog-category')): echo " active open";
                    endif;
                    ?>">
                        <a href="<?= base_url('blogcategory-list') ?>" class="nav-link ">
                            <span class="title">Blog Category List</span>
                            <?php if (is_presented_in_url('blogcategory-list') || is_presented_in_url('add-blog-category') || is_presented_in_url('edit-blog-category')) : ?>
                                <span class="selected"></span>
                            <?php endif; ?>



                        </a>
                    </li>
                    <li class="nav-item <?php
                    if (is_presented_in_url('blogmaster-list') || is_presented_in_url('add-blogmaster') || is_presented_in_url('edit-blogmaster')): echo " active open";
                    endif;
                    ?>">
                        <a href="<?= base_url('blogmaster-list') ?>" class="nav-link ">
                            <span class="title">Blog List</span>
                            <?php if (is_presented_in_url('blogmaster-list') || is_presented_in_url('add-blogmaster') || is_presented_in_url('edit-blogmaster')) : ?>
                                <span class="selected"></span>
                            <?php endif; ?>

                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item <?php
            if (is_presented_in_url('advertisement-type') || is_presented_in_url("add-advertisement-type") || is_presented_in_url("edit-advertisement-type")): echo " active open";
            endif;
            ?>">
                <a href="javascript:void(0);" class="nav-link nav-toggle">
                    <i class="icon-puzzle"></i>
                    <span class="title">Advertise Management</span>
                    <?php if (is_presented_in_url('advertisement-type') || is_presented_in_url("add-advertisement-type") || is_presented_in_url("edit-advertisement-type")) : ?>
                        <span class="selected"></span>
                    <?php endif; ?>

                    <span class="arrow <?php
                    if (is_presented_in_url('advertisement-type') || is_presented_in_url("add-advertisement-type") || is_presented_in_url("edit-advertisement-type") || is_presented_in_url('advertisement-list') || is_presented_in_url("add-new-advertisement") || is_presented_in_url("edit-advertisement")): echo " active open";
                    endif;
                    ?>"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item <?php
                    if (is_presented_in_url('advertisement-type') || is_presented_in_url("add-advertisement-type") || is_presented_in_url("edit-advertisement-type")): echo " active open";
                    endif;
                    ?>">
                        <a href="<?= base_url('advertisement-type') ?>" class="nav-link ">
                            <span class="title">Advertisement Type</span>
                            <?php if (is_presented_in_url('advertisement-type') || is_presented_in_url("add-advertisement-type") || is_presented_in_url("edit-advertisement-type")) : ?>
                                <span class="selected"></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item <?php
                    if (is_presented_in_url('advertisement-list') || is_presented_in_url("add-new-advertisement") || is_presented_in_url("edit-advertisement")): echo " active open";
                    endif;
                    ?>">
                        <a href="<?= base_url('advertisement-list') ?>" class="nav-link ">
                            <span class="title">Advertisement List</span>
                            <?php if (is_presented_in_url('advertisement-list') || is_presented_in_url("add-new-advertisement") || is_presented_in_url("edit-advertisement")) : ?>
                                <span class="selected"></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item <?php
            if (is_presented_in_url("edit-content") || is_presented_in_url("language-setting") || is_presented_in_url("add-new-content") || is_presented_in_url("page-content") || is_presented_in_url("seo-setting") || is_presented_in_url('static-image-list') || is_presented_in_url('edit-static-image') || is_presented_in_url("page-list")): echo " active open";
            endif;
            ?>">
                <a href="javascript:void(0);" class="nav-link nav-toggle">
                    <i class="fa fa-th-large"></i>
                    <span class="title">CMS Management</span>
                    <?php if (is_presented_in_url("edit-content") || is_presented_in_url('static-image-list') || is_presented_in_url('edit-static-image') || is_presented_in_url("page-list") || is_presented_in_url("language-setting") || is_presented_in_url("add-new-content")) : ?>
                        <span class="selected"></span>
                    <?php endif; ?>

                    <span class="arrow <?php
                    if (is_presented_in_url("edit-content") || is_presented_in_url("page-content") || is_presented_in_url("seo-setting") || is_presented_in_url('static-image-list') || is_presented_in_url("language-setting") || is_presented_in_url("add-new-content") || is_presented_in_url("add-new-content") || is_presented_in_url('edit-static-image') || is_presented_in_url("page-list")): echo " active open";
                    endif;
                    ?>"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item <?php
                    if (is_presented_in_url('static-image-list') || is_presented_in_url('edit-static-image')): echo " active open";
                    endif;
                    ?>">
                        <a href="<?= base_url('static-image-list') ?>" class="nav-link ">
                            <span class="title">Image List</span>
                            <?php if (is_presented_in_url('static-image-list') || is_presented_in_url('edit-static-image')) : ?>
                                <span class="selected"></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item  <?php
                    if (is_presented_in_url("page-content") || is_presented_in_url("seo-setting") || is_presented_in_url("page-list")): echo " active open";
                    endif;
                    ?>">
                        <a  href="<?= base_url('page-list') ?>" class="nav-link ">
                            <span class="title">SEO Setting</span>
                            <?php if (is_presented_in_url('page-list') || is_presented_in_url("page-content") || is_presented_in_url("seo-setting")) : ?>
                                <span class="selected"></span>
                            <?php endif; ?>

                        </a>
                    </li>
                    <li class="nav-item  <?php
                    if (is_presented_in_url("language-setting") || is_presented_in_url("add-new-content") || is_presented_in_url("edit-content")): echo " active open";
                    endif;
                    ?>">
                        <a  href="<?= base_url('language-setting') ?>" class="nav-link ">
                            <span class="title">Language Setting</span>
                            <?php if (is_presented_in_url("language-setting") || is_presented_in_url("add-new-content") || is_presented_in_url("edit-content")) : ?>
                                <span class="selected"></span>
                            <?php endif; ?>

                        </a>
                    </li>
                </ul>
            </li>

        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR-->



