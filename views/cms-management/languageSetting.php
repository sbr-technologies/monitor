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
                    <span>Language Setting</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Language Setting
            <small>list of all the multilingual content</small>
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
                            <span class="caption-subject font-dark sbold uppercase">List of all the content</span>
                        </div>
                        <div class="actions">
                            <button type="button" class="btn btn-primary green" onclick="javascript:generate_cache()">Generate Cache</button>
                            <a class="btn btn-primary" href="<?= base_url('add-new-content') ?>">Add New Content</a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">

                            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">

                                        <th width="35%"> Content English </th>
                                        <th width="35%"> Content Hebrew </th>
                                        <th width="15%"> Date </th>
                                        <th width="15%"> Actions </th>
                                    </tr>
                                    <tr role="row" class="filter">

                                        <td class="dt-center">
                                            <input type="text" class="form-control form-filter input-sm" name="word_en">
                                        </td>
                                        <td class="dt-right">
                                            <input type="text" class="form-control form-filter input-sm" name="word_iw">
                                        </td>

                                        <td class="dt-center">
                                            <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd" id = "startdate">
                                                <input type="text" class="form-control form-filter input-sm" readonly name="updated_at_from" placeholder="From">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd" id = "enddate">
                                                <input type="text" class="form-control form-filter input-sm" readonly name="updated_at_to" placeholder="To">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="dt-center">
                                            <div class="margin-bottom-5">
                                                <button class="btn btn-default green filter-submit margin-bottom">
                                                    <i class="fa fa-search"></i> Search</button>
                                            </div>
                                            <button class="btn btn-default red filter-cancel" id = "res">
                                                <i class="fa fa-times"></i> Reset</button>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody> </tbody>
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

