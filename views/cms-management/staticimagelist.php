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
                    <span>Static Images List</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Static Image List
            <small>list of images </small>
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
                            <span class="caption-subject font-dark sbold uppercase">List of all images</span>
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="table-container">

                            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">

                                        <th width="30%"> Image </th>                                                        
                                        <th width="35%"> Name </th>
                                        <th width="25%"> Date </th>
                                        
                                        <th width="10%"> Actions </th>
                                    </tr>
                                    <tr role="row" class="filter">

                                        <td class="dt-center"></td>
                                         <td class="dt-center"> <input type="text" class="form-control form-filter input-sm"  name="name" > </td>
                                        
                                        <td class="dt-center">
                                            <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd" id = "startdate1">
                                                <input type="text" class="form-control form-filter input-sm" readonly name="updated_at_from" placeholder="From">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd" id = "enddate1">
                                                <input type="text" class="form-control form-filter input-sm" readonly name="updated_at_to" placeholder="To">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </td>
                                        
                                        <td  class="dt-center  dt-button-collection">
                                            <div class="margin-bottom-5">
                                                <button class="btn btn-sm green filter-submit margin-bottom dt-button">
                                                    <i class="fa fa-search"></i> Search</button>
                                            </div>
                                            <button class="btn btn-sm red filter-cancel dt-button" id = "res">
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
