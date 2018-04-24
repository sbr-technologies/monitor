<?php $this->load->view('layouts/header'); ?>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->

        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url('dashboard') ?>">Dashboard</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Page List</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> List of Front Pages
            <small>Please configure your pages properly</small>
        </h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
                <!-- Begin: Category Listing -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">List of all pages</span>
                        </div>

                    </div>
                    <div class="portlet-body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th> <h5 class="sbold">#</h5> </th>
                                        <th> <h5 class="sbold">Page</h5> </th>
                                        <th> <h5 class="sbold">Seo Configuration</h5> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="30%"> <h5>1</h5> </td>
                                        <td width="30%"> <h5>Home</h5> </td>
                                        <td width="40%"> <a href="<?= base_url('seo-setting/home') ?>" class="btn default green">
                                                <i class="fa fa-cogs"></i> Click Me </a></td>

                                    </tr>
                                     <tr>
                                        <td width="30%"> <h5>2</h5> </td>
                                        <td width="30%"> <h5>Login Page</h5> </td>
                                        <td width="40%"> <a href="<?= base_url('seo-setting/login') ?>" class="btn default green">
                                                <i class="fa fa-cogs"></i> Click Me </a></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!-- End: Category Listing -->

            </div>
        </div>
    </div>
    <!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
<?php $this->load->view('layouts/footer'); ?>
