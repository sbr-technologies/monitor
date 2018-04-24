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
                    <a href="<?= base_url('price-chart-manager') ?>">Price Chart Menu List</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Add Price Chart</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Add Price Chart
            <small>please add price chart details</small>
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
                            <span class="caption-subject font-dark sbold uppercase">Please provide information for price chart</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <?php
                        $attributes = array('class' => 'form-horizontal', 'id' => 'add-price-chart-form');
                        echo form_open_multipart('add-price-chart', $attributes);
                        ?>
                        <div class="form-body">
                            <?php
                            echo validation_errors('<div class="alert alert-danger alert-dismissable">
										                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>', '</div>');
                            ?>
                            <div class="form-group">
                                <label class="control-label col-md-3"> Price Chart Picture</label>
                                <div  class="col-md-4 input_element">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            <img src="<?= ASSET_URL . 'uploads/price-chart-manager/preview/No-Image-Placeholder.jpg' ?>" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;" id="fileinput-preview-container"> 
                                        </div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new"> Select image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                <input type="file" name="picture" id="picture" accept="image/*"> </span>
                                            <a href="javascript:vaoid(0);" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                        </div>
                                    </div>
                                    <div class="clearfix margin-top-10">
                                        <span class="label label-danger"> NOTE! </span>
                                        <span>For better preview on your home page please upload image with width 386 and height 437</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group category-input-area">
                                <label class="control-label col-md-3">Category
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <select name="category_id" class="form-control select2me" id="category_id" onchange='javascript:cat_name_validate()'>
                                        <option value="" <?= (set_value('category_id') === "") ? "selected" : "" ?>>--Select Category--</option>
                                        <?php foreach ($category as $key => $value) { ?>
                                            <option value="<?= $value['id'] ?>" <?= (set_value('category_id') === $value['id']) ? "selected" : "" ?>><?= $value['cat_name'] ?></option>
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
                                <label class="control-label col-md-3">Is Featured</label>
                                <div class="col-md-4">
                                    <input data-toggle="toggle" data-on="Enabled" data-off="Disabled"  checked="true" type="checkbox" name="is_featured">
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
                                    <a class="btn grey-salsa btn-outline" href='<?= base_url('price-chart-manager') ?>'>Cancel</a>
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