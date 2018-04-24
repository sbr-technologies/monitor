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
                    <a href="<?= base_url("page-list"); ?>">Page List</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>SEO Setting</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Admin Dashboard 2
            <small>statistics, charts, recent events and reports</small>
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
                        $attributes = array('class' => 'form-horizontal', 'id' => 'seo-setting-form');
                        echo form_open("seo-setting/$slug", $attributes);
                        ?>
                        <div class="form-body">
                            <?php
                            echo validation_errors('<div class="alert alert-danger alert-dismissable">
										                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>', '</div>');
                            ?>
                            <div class="form-group">
                                <label class="control-label col-md-3">Title
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="title"  class="form-control" value="<?php echo htmlentities(set_value('title') !== "" ? set_value('title') : $result['title']); ?>"/>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="control-label col-md-3">Meta Title
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="meta_title"  class="form-control" value="<?php echo htmlentities(set_value('meta_title') !== "" ? set_value('meta_title') : $result['meta_title']); ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Meta Description
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="meta_description"  class="form-control" value="<?php echo htmlentities(set_value('meta_description') !== "" ? set_value('meta_description') : $result['meta_description']); ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Meta Keywords
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="meta_keywords"  class="form-control" value="<?php echo htmlentities(set_value('meta_keywords') !== "" ? set_value('meta_keywords') : $result['meta_keywords']); ?>"/>
                                </div>
                            </div>

                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">Submit</button>
                                    <a class="btn grey-salsa btn-outline" href='<?= base_url('page-list') ?>'>Cancel</a>
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