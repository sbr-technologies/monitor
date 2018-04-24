<?php //print_r($category) ; die();  ?>
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
                    <a href="<?= base_url('business-feature-manager') ?>">Business Feature</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Edit Business Feature</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Edit Business Feature
            <small>please edit business feature details</small>
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
                            <span class="caption-subject font-dark sbold uppercase">Please provide information for business feature</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <?php
                        $attributes = array('class' => 'form-horizontal', 'id' => 'edit-business-feature-form');
                        echo form_open('edit-business-feature/' . $encrypted_business_feature_id, $attributes);
                        ?>
                        <div class="form-body">
                            <?php
                            echo validation_errors('<div class="alert alert-danger alert-dismissable">
                                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>', '</div>');
                            ?>
                            <input type="hidden" id="id" name ="id" value ="<?= $result['id'] ?>"> 
                            <div class="form-group  category-input-area">
                                <label class="control-label col-md-3">Category
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <select name="parent_id" class="form-control select2me" id="parent_id">
                                        <option value="" <?= (set_value('parent_id') === "") ? "selected" : "" ?>>--Select Category--</option>


                                        <?php $current_category_id = set_value('parent_id') !== "" ? set_value('parent_id') : $result['category_id']; ?>
                                        <?php foreach ($categories as $key => $cat) { ?>
                                            <option value="<?php echo $cat['id']; ?>" <?= $current_category_id === $cat['id'] ? "selected" : "" ?>><?php echo $cat['cat_name']; ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Feature Name
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="feature_name" onblur='javascript:feature_name_validate()'  id="feature_name" data-required="1" class="form-control" value="<?php echo htmlentities(set_value('feature_name') !== "" ? set_value('feature_name') : $result['feature_name']); ?>"/> 
                                </div>
                            </div>



                            <div class="form-group ">
                                <label for="status" class="control-label col-md-3">Status</label>
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
                                    <input data-toggle="toggle" data-on="Enabled" data-off="Disabled"  type="checkbox" name="status" <?php if ($current_staus) { ?> checked="true" <?php } ?> >
                                </div>
                            </div>

                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">Submit</button>
                                    <a class="btn grey-salsa btn-outline" href='<?= base_url('business-feature-manager') ?>'>Cancel</a>
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