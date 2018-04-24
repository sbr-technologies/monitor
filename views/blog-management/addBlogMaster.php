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
                    <a href="<?= base_url('blogmaster-list') ?>">Blog </a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Add Blog</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Add Blog 
            <small>please add Blog details</small>
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
                            <span class="caption-subject font-dark sbold uppercase">Please provide information for Blog </span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <?php
                        $attributes = array('class' => 'form-horizontal', 'id' => 'add-blog-master-form');
                        echo form_open_multipart('add-blogmaster', $attributes);
                        ?>
                        <div class="form-body">
                            <?php
                            echo validation_errors('<div class="alert alert-danger alert-dismissable">
										                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>', '</div>');
                            ?>
                            <?php
                            $arr = array();
                            $arr = set_value('blog_category');
                            ?>
                            <div class="form-group ">
                                <label class="control-label col-md-3">Blog Image </label>
                                <div class="col-md-9">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">


                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new"> Select image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                <input type="file" name="blog_image" id = "blog_image" > 

                                            </span>
                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                        </div>
                                    </div>
                                    <div class="clearfix margin-top-10">
                                        <span class="label label-danger">NOTE!</span> &nbsp;&nbsp;&nbsp;Image size 1000 X 800 
                                    </div>
                                </div>
                            </div> 

                            <div class="form-group category-input-area">
                                <label class="control-label col-md-3">Blog Category
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <select  name="blog_category[]" class="form-control select2-multiple " id="blog_category" multiple onchange='javascript:block_name_validate();'>
                                        <?php
                                        $current_category_id = ($arr !== "") ? $arr : array() ;
                                        foreach ($blog_category as $row) {
                                            $selectedd = (in_array($row['id'], $current_category_id)) ? 'selected' : '';
                                            ?>  
                                            <option value = "<?= $row['id'] ?>" <?= $selectedd ?> ><?= $row['category_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>    

                            <div class="form-group">
                                <label class="control-label col-md-3">Tags 
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text"  id="tags" name="tags"  class="form-control" value="<?php echo set_value('tags') ?>" data-role="tagsinput" >
                                    <span class="help-block text-danger">Press enter or coma to make it a tag.*</span>

                                </div>
                            </div>    
                            <div class="form-group">
                                <label class="control-label col-md-3">Author
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="author" id = "author"  class="form-control" value="<?php echo set_value('author') ?>"/> 
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Head Line
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="head_line" id = "head_line" class="form-control" value="<?php echo set_value('head_line') ?>"/> 
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Meta Tag
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="meta_tag" id = "meta_tag"  class="form-control" value="<?php echo set_value('meta_tag') ?>"/> 
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Meta Description
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="meta_description" id = "meta_description"  class="form-control" value="<?php echo set_value('meta_description') ?>"/> 
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Meta Keywords
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input_element">
                                    <input type="text" name="meta_keywords" id = "meta_keywords"  class="form-control" value="<?php echo set_value('meta_keywords') ?>"/> 
                                </div>
                            </div>

                            <div class="form-body ">
                                <div class="form-group last ">
                                    <label class="control-label col-md-3">Description
                                        <span class="required"> * </span>        
                                    </label>
                                    <div class="col-md-9 description_container">
                                        <textarea class="form-control" name="description" id = "description" rows="6"><?php echo set_value('description') ?></textarea>

                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Is Featured</label>
                                <div class="col-md-4">
                                    <input data-toggle="toggle" data-on="Enabled" data-off="Disabled"  type="checkbox" name="is_featured">
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

                                    <a type="button" class="btn grey-salsa btn-outline" href="<?= base_url("blogmaster-list"); ?>">Cancel</a>

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