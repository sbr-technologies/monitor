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
                    <a href="<?= base_url('language-setting') ?>">Language Setting</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Edit Content</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Edit Content
            <small>please provide new content</small>
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
                            <span class="caption-subject font-dark sbold uppercase">Please provide new content in hebrew and english</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <?php
                        $attributes = array('class' => 'form-horizontal', 'id' => 'edit-content');
                        echo form_open("edit-content/$encrypted_id", $attributes);
                        ?>
                        <div class="form-body">
                            <?php
                            echo validation_errors('<div class="alert alert-danger alert-dismissable">
										                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>', '</div>');
                            ?>
                            <div class="form-group">
                                <label class="control-label col-md-3">Content English
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <textarea class="form-control" name="word_en"><?php echo set_value('word_en') !== "" ? set_value('word_en') : $result['word_en']; ?></textarea>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="control-label col-md-3">Content Hebrew
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <textarea class="form-control" name="word_iw"><?php echo set_value('word_iw') !== "" ? set_value('word_iw') : $result['word_iw']; ?></textarea> 
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">Submit</button>
                                    
                                    <a class="btn grey-salsa btn-outline" href="<?= base_url("language-setting"); ?>">Cancel</a>

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
