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
                    <a href="<?= base_url("static-image-list"); ?>">Static Images List</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Edit Image</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Add image
            <small>please add image</small>
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
                            <span class="caption-subject font-dark sbold uppercase">Please provide information for image</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <?php
                        $attributes = array('class' => 'form-horizontal', 'id' => 'edit-static-image-form');
                        echo form_open_multipart('edit-static-image/' . $encrypted_id, $attributes);
                        echo form_hidden('is_post', 'TRUE');
                        ?>
                        <div class="form-body">
                            <?php
                            echo validation_errors('<div class="alert alert-danger alert-dismissable">
										                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>', '</div>');
                            ?>

                            <div class="form-group">
                                <?php
                                if ((int) $result["id"] === 1) {
                                    $label_string = "Home Banner Image";
                                    $image_string = "   For better preview on your home page please upload image with width 1920 and height 900";
                                }
                                if ((int) $result["id"] === 2) {
                                    $label_string = "Login Page Image";
                                    $image_string = "   For better preview on your home page please upload image with width 526 and height 523";
                                }
                                if ((int) $result["id"] === 3) {
                                    $label_string = "Logo Image";
                                    $image_string = "   For better preview on your home page please upload image with width 180 and height 70";
                                }
                                ?>
                                <label class="control-label col-md-3"><?= $label_string ?></label>
                                <div  class="col-md-4 input_element">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            <img src="<?= ASSET_URL . "uploads/static-images/preview/" . $result['image_name'] ?>" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;" id="fileinput-preview-container"> 
                                        </div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new"> Select image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                <input type="file" name="static_image" id="static_image" accept="image/*"> </span>
                                            <a href="javascript:vaoid(0);" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                        </div>
                                    </div>
                                    <div class="clearfix margin-top-10">
                                        <span class="label label-danger"> NOTE! </span>
                                        <span><?= $image_string ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green" id = "submit-form">Submit</button>

                                    <a type="button" class="btn grey-salsa btn-outline" href="<?= base_url("static-image-list"); ?>">Cancel</a>

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
