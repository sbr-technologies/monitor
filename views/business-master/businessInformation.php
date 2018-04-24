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
                    <a href="<?= base_url('all-business') ?>">Business Manager</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Business Information</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Business Information
            <small>Please provide all the informations regarding this business</small>
        </h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->

        <div class="row">
            <div class="col-md-6 ">
                <!-- START AVAILABILITY INFORMATION -->
                <?php if (count($availability_info) > 0) : ?>
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">AVAILABILITY INFORMATION</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <?php
                            $attributes = array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'edit-business-availability-info');
                            echo form_open('business-information/' . $encrypted_id, $attributes);
                            echo form_hidden('is_availability_info', TRUE);
                            ?>
                            <div class="form-body">
                                <?php
                                $i = 0;
                                foreach ($availability_info as $value):
                                    ?>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?= $value['name'] ?></label>
                                        <div class="col-md-9">
                                            <?php
                                            if (in_array($value['id'], array_column($availability_info_business, 'availability_master_id'))) {
                                                $cheched_status = 'checked="true"';
                                            } else {
                                                $cheched_status = "";
                                            }
                                            ?>
                                            <input data-toggle="toggle" data-on="Enabled" data-off="Disabled" <?= $cheched_status ?> type="checkbox" name="business_availability_info_<?= $i ?>" class="form-control switch_element" >
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                endforeach;
                                ?>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="button" class="btn green" onclick="javascript:submit_basic_info('<?= $encrypted_id ?>', 'edit-business-availability-info')">Submit</button>
                                        <a type="button" class="btn grey-salsa btn-outline" href="<?= base_url("all-business"); ?>">Cancel</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            echo form_close();
                            ?>
                        </div>
                    </div>
                    <!-- END SAMPLE FORM PORTLET-->
                <?php endif; ?>
                <!-- END AVAILABILITY INFORMATION -->

                <!-- START ADDITIONAL LANGUAGE INFORMATION -->
                <?php if (count($additional_language_info) > 0) : ?>
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">ADDITIONAL LANGUAGE INFORMATION</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <?php
                            $attributes = array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'edit-additional-language-info');
                            echo form_open('business-information/' . $encrypted_id, $attributes);
                            echo form_hidden('is_additional_language_info', TRUE);
                            ?>
                            <div class="form-body">
                                <?php
                                $i = 0;
                                foreach ($additional_language_info as $value):
                                    ?>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?= $value['name'] ?></label>
                                        <div class="col-md-9">
                                            <?php
                                            if (in_array($value['id'], array_column($additional_language_business, 'additional_language_id'))) {
                                                $cheched_status = 'checked="true"';
                                            } else {
                                                $cheched_status = "";
                                            }
                                            ?>
                                            <input data-toggle="toggle" data-on="Enabled" data-off="Disabled"  <?= $cheched_status ?>  type="checkbox" name="additional_language_info_<?= $i ?>" class="form-control switch_element" >
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                endforeach;
                                ?>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="button" class="btn green" onclick="javascript:submit_basic_info('<?= $encrypted_id ?>', 'edit-additional-language-info')">Submit</button>
                                        <a type="button" class="btn grey-salsa btn-outline" href="<?= base_url("all-business"); ?>">Cancel</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            echo form_close();
                            ?>
                        </div>
                    </div>
                    <!-- END SAMPLE FORM PORTLET-->
                <?php endif; ?>
                <!-- END ADDITIONAL LANGUAGE INFORMATION -->
            </div>
            <!-- END SERVICE AREA INFORMATION -->
            <?php if (count($service_area_info) > 0) : ?>
                <div class="col-md-6">
                    <!-- BEGIN SAMPLE FORM PORTLET-->
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">SERVICE AREA INFORMATION</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <?php
                            $attributes = array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'edit-business-service-area');
                            echo form_open('business-information/' . $encrypted_id, $attributes);
                            echo form_hidden('is_service_area_info', TRUE);
                            ?>
                            <div class="form-body">
                                <?php
                                $i = 0;
                                foreach ($service_area_info as $value):
                                    ?>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><?= $value['name'] ?></label>
                                        <div class="col-md-9">
                                            <?php
                                            if (in_array($value['id'], array_column($service_area_business, 'service_area_id'))) {
                                                $cheched_status = 'checked="true"';
                                            } else {
                                                $cheched_status = "";
                                            }
                                            ?>
                                            <input data-toggle="toggle" data-on="Enabled" data-off="Disabled"  <?= $cheched_status ?> type="checkbox" name="business_service_area_<?= $i ?>" class="form-control switch_element" >
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                endforeach;
                                ?>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="button" class="btn green" onclick="javascript:submit_basic_info('<?= $encrypted_id ?>', 'edit-business-service-area')">Submit</button>
                                        <a type="button" class="btn grey-salsa btn-outline" href="<?= base_url("all-business"); ?>">Cancel</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            echo form_close();
                            ?>
                        </div>
                    </div>
                    <!-- END SAMPLE FORM PORTLET-->
                </div>
            <?php endif; ?>
            <!-- END SERVICE AREA INFORMATION -->
        </div>

        <!-- START BUSINESS FEATURE INFORMATION -->
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">BUSINESS FEATURE INFORMATION</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <?php if (count($business_feature_info) === 0) { ?>
                            <h4>Oops !!! <?= $business_info["vendor_name"] ?> comes under <?= $business_info["cat_name"] ?> and this category has no feature</h4>
                            <?php
                        } else {
                            $attributes = array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'business-dynamic-feature-info');
                            echo form_open('business-information/' . $encrypted_id, $attributes);
                            ?>
                            <div class="form-body">
                                <?php
                                $stored_array = array();
                                foreach ($business_feature_info as $business_feature):
                                    ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">
                                            <?= $business_feature["feature_name"] ?>
                                        </label>
                                        <div class="col-md-4 input_element">
                                            <select class="form-control select2-multiple dynamic_feature_attribute" multiple>
                                                <?php
                                                foreach ($business_feature["feature_content"] as $row) {
                                                    if (in_array($row['feature_attribute_id'], $dynamic_feature_info)) {
                                                        $selected_string = "selected='true'";
                                                    } else {
                                                        $selected_string = "";
                                                    }
                                                    ?>  
                                                    <option value = "<?= $row['feature_attribute_id'] ?>"  <?= $selected_string ?>><?= $row['feature_attribute_name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div> 
                                    <?php
                                endforeach;
                                ?>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="button" class="btn green" onclick="javascript:submit_dynamic_feature_info('<?= $encrypted_id ?>')">Submit</button>
                                        <a type="button" class="btn grey-salsa btn-outline" href="<?= base_url("all-business"); ?>">Cancel</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            echo form_close();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- END BUSINESS FEATURE INFORMATION -->

    </div>
    <!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->


<?php $this->load->view('layouts/footer'); ?>