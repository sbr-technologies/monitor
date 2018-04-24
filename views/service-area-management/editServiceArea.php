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
                    <a href="<?= base_url('service-area') ?>">Service Area</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Edit Service Area</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Edit Service Area
            <small>please edit service area details</small>
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
                            <span class="caption-subject font-dark sbold uppercase">Please edit information for service area</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <?php
                        $attributes = array('class' => 'form-horizontal', 'id' => 'edit-service-area-form');
                        echo form_open('edit-service-area/' . $encrypted_id, $attributes);
                        ?>
                        <input type="hidden" name="service_area_id" id="service_area_id" value="<?= $result['id'] ?>">
                        <div class="form-body">
                            <?php
                            echo validation_errors('<div class="alert alert-danger alert-dismissable">
                                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>', '</div>');
                            ?>
                            <div class="form-group">
                                <label class="control-label col-md-3">Service Area Name
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="name"  class="form-control" value="<?php echo htmlentities(set_value('name') !== "" ? set_value('name') : $result['name']); ?>"/>
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <label class="control-label col-md-3">Status</label>
                                <div class="col-md-4">
                                    <?php
                                    if (set_value('status') === "on") {
                                        $current_staus = TRUE;
                                    } else {
                                        if ($result['status'] === "0") {
                                            $current_staus = FALSE;
                                        } else {
                                            $current_staus = TRUE;
                                        }
                                    }
                                    ?>
                                    <input data-toggle="toggle" data-on="Enabled" data-off="Disabled"  <?php if ($current_staus) { ?> checked="true" <?php } ?> type="checkbox" name="status">
                                </div>
                            </div>

                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">Submit</button>
                                    <a class="btn grey-salsa btn-outline" href="<?= base_url("service-area"); ?>">Cancel</a>
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
