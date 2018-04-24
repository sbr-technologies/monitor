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
                    <span>Price Chart Menu Details</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Price Chart Menu Details Info
            <small>list of all main price charts with details</small>
        </h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">

                <!-- starts price chart details list -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">List of price chart main menu with information</span>
                        </div>
                        <div class="actions">
                            <a type="button" class="btn btn-primary" href="<?= base_url('add-price-details') ?>">Add Price Chart Details</a>

                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">

                            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">

                                        <th width="15%"> Menu Name </th>
                                        <th width="15%"> Category Name </th>
                                        <th width="15%"> Main Menu Name</th>
                                        <th width="10%"> Highest Price </th>
                                        <th width="10%"> Lowest Price </th>
                                        <th width="15%"> Date </th>
                                        <th width="10%"> Status </th>
                                        <th width="10%"> Actions </th>
                                    </tr>
                                    <tr role="row" class="filter">

                                        <td class="dt-center">
                                            <input type="text" class="form-control form-filter input-sm" name="menu_name"> 
                                        </td>

                                        <td class="dt-center">
                                            <select id="category_id" name="category_id" class="form-control form-filter input-sm">
                                                <option value="">Select...</option>
                                                <?php foreach ($category as $value) { ?>
                                                    <option value="<?= $value['id'] ?>" <?= $value['id'] === $cookie_category_id ? "selected" : "" ?> ><?= $value['cat_name'] ?></option> 
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td class="dt-center">
                                            <select id="main_menu_id" name="main_menu_id" class="form-control form-filter input-sm">
                                                <option value="">Select...</option> 
                                                <?php echo $main_price_chart; ?>   

                                            </select>
                                        </td>
                                        <td class="dt-center">
                                            <div class="margin-bottom-5">
                                                <input type="text" class="form-control form-filter input-sm only-number" name="highest_price_from" placeholder="From" /> </div>
                                            <input type="text" class="form-control form-filter input-sm only-number" name="highest_price_to" placeholder="To" /> 

                                        </td>
                                        <td class="dt-center">
                                            <div class="margin-bottom-5">
                                                <input type="text" class="form-control form-filter input-sm" name="lowest_price_from" placeholder="From" /> </div>
                                            <input type="text" class="form-control form-filter input-sm" name="lowest_price_to" placeholder="To" /> 

                                        </td>
                                        <td class="dt-center">
                                            <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd" id = "startdate">
                                                <input type="text" class="form-control form-filter input-sm datepicker-entry-point" readonly name="updated_at_from" placeholder="From" id = "updated_at_from">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd" id = "enddate">
                                                <input type="text" class="form-control form-filter input-sm datepicker-entry-point" readonly name="updated_at_to" id = "updated_at_to" placeholder="To">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </td>  
                                        <td class="dt-center">
                                            <select name="status" class="form-control form-filter input-sm">
                                                <option value="">Select...</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </td>
                                        <td class="dt-center dt-button-collection">
                                            <div class="margin-bottom-5">
                                                <button class="btn btn-sm dt-button green  filter-submit margin-bottom" id="search-price-chart">
                                                    <i class="fa fa-search"></i> Search</button>
                                            </div>
                                            <button class="btn btn-sm dt-button red  filter-cancel" id="res">
                                                <i class="fa fa-times"></i> Reset</button>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody> </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End price chart details list -->

            </div>
        </div>
    </div>
    <!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->

<?php $this->load->view('layouts/footer'); ?>
