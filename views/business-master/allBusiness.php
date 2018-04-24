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
                    <span>Business Manager</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> All Business Information
            <small>all the available business list</small>
        </h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN Portlet PORTLET-->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-search"></i>
                            <span class="caption-subject bold uppercase"> Search Data</span>
                            <span class="caption-helper">please fill out the form for searching</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" id="business_name_url" Placeholder="Business name or url" name="business_name_url"  class="form-control input-sm search-fields " >
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <select  class="form-control input-sm search-fields " id="user_id" name="user_id">
                                        <option value="">---Select Vendor...</option>
                                        <?php foreach ($vendorList as $value) { ?>
                                            <option value="<?= $value['id'] ?>" <?= ($value['id'] == $cookie_vendor_name) ? "selected" : "" ?> ><?= $value['first_name'] . " " . $value['last_name'] ?></option> 
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <select id="category_id" name="category_id"  class="form-control input-sm search-fields ">
                                        <option value="">------Select Category------</option>
                                        <?php foreach ($category as $value) { ?>
                                            <option value="<?= $value['id'] ?>" <?= ($value['id'] == $cookie_category_id) ? "selected" : "" ?> ><?= $value['cat_name'] ?></option> 
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" class="form-control input-sm search-fields " id="vendor_number" name="vendor_number" Placeholder="Business phone number">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3 margin-bottom-5">
                                    <div class="input-group date date-picker" data-date-format="yyyy-mm-dd" id = "startdate">
                                        <input type="text" class="form-control input-sm search-fields " readonly name="updated_at_from" placeholder="Added date from">
                                        <span class="input-group-btn">
                                            <button class="btn default btn-sm" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <div class="input-group date date-picker" data-date-format="yyyy-mm-dd" id = "enddate">
                                        <input type="text" class="form-control input-sm search-fields " readonly name="updated_at_to" placeholder="Added date to">
                                        <span class="input-group-btn">
                                            <button class="btn default btn-sm" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" class="form-control  input-sm search-fields " id="from_tot_rater" name="from_tot_rater" Placeholder="From total rater">
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" class="form-control  input-sm search-fields " id="to_tot_rater" name="to_tot_rater" Placeholder="To total rater">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" class="form-control only-number input-sm search-fields " id="from_tot_viewer" name="from_tot_viewer" Placeholder="From total viewer">
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" class="form-control only-number input-sm search-fields " id="to_tot_viewer" name="to_tot_viewer" Placeholder="To total viewer">
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" class="form-control only-number input-sm search-fields " id="from_avg_rate" name="from_avg_rate" Placeholder="From average rate">
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" class="form-control only-number input-sm search-fields " id="to_avg_rate" name="to_avg_rate" Placeholder="To average rate">
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" class="form-control input-sm search-fields " id="vendor_email" name="vendor_email" Placeholder="Business email">
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" class="form-control input-sm search-fields " id="vendor_street" name="vendor_street" Placeholder="Street">
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" class="form-control input-sm search-fields " id="vendor_city" name="vendor_city" Placeholder="City">
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <input type="text" class="form-control input-sm search-fields " id="vendor_location" name="vendor_location" Placeholder="Location">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3 margin-bottom-5">
                                    <select id="status" class="form-control input-sm search-fields " name="status">
                                        <option value="">---Select Status---</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-3 margin-bottom-5">
                                    <button class="btn btn-primary filter-submit margin-bottom" id = "search-business-list" type="button">
                                        <i class="fa fa-search"></i> Search</button>
                                    <button class="btn purple filter-cancel" id = "reset-business-search" type="button">
                                        <i class="fa fa-times"></i> Reset</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- END Portlet PORTLET-->
                <!-- Begin: Business Listing -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">List of all the business</span>
                        </div>
                        <div class="actions">
                            <a type="button" class="btn btn-primary" href="<?= base_url('add-business') ?>">Add New Business</a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">

                            <table  class="table table-bordered" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th> Business </th>
                                        <th> Vendor </th>
                                        <th> Category </th>
                                        <th> Phone number </th>
                                        <th> Email </th>
                                        <th> Date </th>
                                        <th> Total Rater </th>
                                        <th> Average Rate </th>
                                        <th> Total Viewer </th>
                                        <th> Address </th>
                                        <th> Status </th>
                                        <th> Actions </th>
                                    </tr>
                                </thead>
                                <tbody> </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End: Business Listing -->

            </div>
        </div>
    </div>
    <!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->

<!--Banner preview modal starts here-->
<div id="featured_business" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase">Featured Business (Maximum three is allowed)</span>
                </div>
            </div>
            <div class="modal-body">
                <div id="featured_business_content" class="row">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--Banner preview modal ends here-->

<?php $this->load->view('layouts/footer'); ?>