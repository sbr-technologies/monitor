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
                    <a href="<?= base_url('price-chart-details') ?>">Price Chart Menu Details</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Add Price Chart Menu Details</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Add Price Chart Menu Details
            <small>please add price chart Menu details</small>
        </h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light portlet-fit portlet-form bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Please provide information for price chart menu details</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <?php
                        $attributes = array('class' => 'form-horizontal', 'id' => 'add-price-chart-details-form');
                        echo form_open('add-price-details', $attributes);
                        ?>
                        <div class="form-body">
                            <?php
                            echo validation_errors('<div class="alert alert-danger alert-dismissable">
										                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>', '</div>');
                            ?>
                            <div class="form-group category-input-area">
                                <label class="control-label col-md-3">Category
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <select name="category_id" class="form-control select2me" id="category_id" >
                                        <option value="" <?= (set_value('category_id') === "") ? "selected" : "" ?>>--Select Category--</option>
                                        <?php foreach ($category as $key => $value) { ?>
                                            <option value="<?= $value['id'] ?>" <?= (set_value('category_id') === $value['id']) ? "selected" : "" ?>><?= $value['cat_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group main-menu-input-area">
                                <label class="control-label col-md-3">Main Menu Name
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <select name="main_menu_id" class="form-control select2me" id="main_menu_id" onchange='javascript:menu_name_validate();'>
                                        <option value="" >--Select Main Menu--</option>
                                        <?php
                                        $current_menu_id = set_value('main_menu_id') !== "" ? set_value('main_menu_id') : "";

                                        foreach ($main_menu_detail as $key => $menu) {
                                            ?>  
                                            <option value="<?= $menu['id'] ?>" <?= $current_menu_id == $menu['id'] ? "selected" : "" ?>><?= $menu['menu_name'] ?></option>
                                        <?php } ?>


                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Menu Name
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="menu_name" id="menu_name" data-required="1" class="form-control" value="<?php echo set_value('menu_name') ?>"/> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Menu Description
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="menu_description" id="menu_description" data-required="1" class="form-control" value="<?php echo set_value('menu_description') ?>"/> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Lowest Price
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="lowest_price" id="lowest_price" data-required="1" class="form-control only-number" value="<?php echo set_value('lowest_price') ?>"/> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Highest Price
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="highest_price" id="highest_price" data-required="1" class="form-control only-number" value="<?php echo set_value('highest_price') ?>"/> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Status</label>
                                <div class="col-md-4">
                                    <input data-toggle="toggle" data-on="Enabled" data-off="Disabled"  checked="true" type="checkbox" name="status">
                                </div>
                            </div>

                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">Submit</button>
                                    <a class="btn grey-salsa btn-outline" href='<?= base_url('price-chart-details') ?>'>Cancel</a>
                                </div>
                            </div>
                        </div>
                        <?php
                        echo form_close();
                        ?>
                        <!-- END FORM-->
                    </div>
                </div>
                <!-- END VALIDATION STATES-->
            </div>
        </div>

    </div>
    <!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
<?php $this->load->view('layouts/footer'); ?>