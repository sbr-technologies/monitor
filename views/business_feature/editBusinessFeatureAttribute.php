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
                    <a href="<?= base_url('business-feature-attribute') ?>">Business Feature Attribute</a>
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
                        $attributes = array('class' => 'form-horizontal', 'id' => 'edit-business-feature-attribute-form');
                        echo form_open('edit-business-feature-attribute' . '/' . $encrypted_feature_attribute_id, $attributes);
                        ?>
                        <div class="form-body">
                            <?php
                            echo validation_errors('<div class="alert alert-danger alert-dismissable">
										                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>', '</div>');
                            ?>
                            <input type="hidden" id="id" name="id" value="<?= $result['id'] ?>"> 
                            <div class="form-group  category-input-area">
                                <label class="control-label col-md-3">Category
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <select name="category_id" class="form-control select2me" id="category_id">
                                        <option value="" <?= (set_value('category_id') === "") ? "selected" : "" ?>>--Select Category--</option>
                                        <?php $current_category_id = set_value('category_id') !== "" ? set_value('category_id') : $result['main_category_id']; ?>
                                        <?php foreach ($categories as $key => $cat) { ?>
                                            <option value="<?php echo $cat['id']; ?>" <?= $current_category_id === $cat['id'] ? "selected" : "" ?>><?php echo $cat['cat_name']; ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group main-menu-input-area">
                                <label class="control-label col-md-3">Feature Name
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <select name="main_bfm_id" class="form-control select2me" id="main_bfm_id" onchange='javascript:feature_name_validate();'>
                                        <option value="" <?= (set_value('main_bfm_id') === "") ? "selected" : "" ?>>--Select Feature--</option>
                                        <?php
                                        $current_main_bfm_id = set_value('main_bfm_id') !== "" ? set_value('main_bfm_id') : $result['bfm_id'];
                                        ?>
                                        <?php
                                        foreach ($feature_id_details as $key => $menu) {
                                            ?>
                                            <option value="<?php echo $menu['id']; ?>" <?= $current_main_bfm_id === $menu['id'] ? "selected" : "" ?>>    <?php echo $menu['feature_name']; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Name
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="name" id="name" data-required="1" class="form-control" value="<?php echo htmlentities(set_value('name') !== "" ? set_value('name') : $result['name']); ?>"/> 
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
                                    <a class="btn grey-salsa btn-outline" href='<?= base_url('business-feature-attribute') ?>'>Cancel</a>
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