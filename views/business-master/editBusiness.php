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
                    <span>Edit Business</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Edit Business
            <small>Please provide information for the business</small>
        </h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
                <div class="tabbable-line boxless tabbable-reversed">
                    <ul class="nav nav-tabs">
                        <li class="active" >
                            <a href="#tab_0" data-toggle="tab"> Basic Information </a>
                        </li>
                        <li>
                            <a href="#tab_1" data-toggle="tab" class="additional_information_tab"> Additional Information </a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab" class="gallery_information_tab">Gallery</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_0">
                            <!-- ENTRY FOR BASIC BUSINESS INFORMATION STARTED-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light portlet-fit portlet-form bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="icon-settings font-dark"></i>
                                                <span class="caption-subject font-dark sbold uppercase">Please provide basic information for business</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body">

                                            <?php
                                            $attributes = array('class' => 'form-horizontal', 'id' => 'edit-business-form');
                                            echo form_open('edit-business', $attributes);
                                            ?>
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Vendor
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4 input_element">
                                                        <select name="user_id" class="form-control select2me" id="user_id" >
                                                            <option value="">--Select Vendor Name--</option>
                                                            <?php foreach ($vendorList as $key => $value) { ?>
                                                                <option value="<?= $value['id'] ?>" <?= $result["user_id"] === $value['id'] ? "selected" : "" ?>><?= $value['first_name'] . " " . $value['last_name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group category-input-area">
                                                    <label class="control-label col-md-3">Category
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4 input_element">
                                                        <select name="category_id" class="form-control select2me" id="category_id">
                                                            <option value="" >--Select Category--</option>
                                                            <?php foreach ($category as $key => $value) { ?>
                                                                <option value="<?= $value['id'] ?>" <?= $result["category_id"] === $value['id'] ? "selected" : "" ?>><?= $value['cat_name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> Name
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4 input_element">
                                                        <input type="text" name="vendor_name" id="vendor_name"  class="form-control" value="<?= $result["vendor_name"] ?>"/> 
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> Number
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4 input_element">
                                                        <input type="text" name="vendor_number" id="vendor_number" class="form-control" value="<?= $result["vendor_number"] ?>"/> 
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> Tax Id
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4 input_element">
                                                        <input type="text" name="tax_id" id="tax_id" class="form-control" value="<?= $result["tax_id"] ?>"/> 
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> Email
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4 input_element">
                                                        <input type="text" name="vendor_email" id="vendor_email" class="form-control" value="<?= $result["vendor_email"] ?>"/> 
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> Description
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4 input_element">
                                                        <textarea class="form-control" rows="5" id="vendor_description" name="vendor_description"><?= $result["vendor_description"] ?></textarea> 
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> Street
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4 input_element">
                                                        <input type="text" name="vendor_street"  class="form-control" id="vendor_street" value="<?= $result["vendor_street"] ?>"/> 
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> City
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4 input_element">
                                                        <input type="text" name="vendor_city"  class="form-control" id="vendor_city" value="<?= $result["vendor_city"] ?>"/> 
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"> Location
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-4 input_element">
                                                        <input type="text" name="vendor_location"  class="form-control" id="vendor_location" value="<?= $result["vendor_location"] ?>"/> 
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Status</label>
                                                    <div class="col-md-4">
                                                        <?php
                                                        if ($result['status'] === "0") {
                                                            $current_staus = FALSE;
                                                        } else {
                                                            $current_staus = TRUE;
                                                        }
                                                        ?>
                                                        <input data-toggle="toggle" data-on="Enabled" data-off="Disabled"  <?php if ($current_staus) { ?> checked="true" <?php } ?> type="checkbox" name="status" id="status">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" class="btn green" name="basic_info_business">Update</button>
                                                        <a type="button" class="btn grey-salsa btn-outline" href="<?= base_url("all-business"); ?>">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            echo form_close();
                                            ?>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- ENTRY FOR BASIC BUSINESS INFORMATION ENDED-->
                        </div>
                        <div class="tab-pane" id="tab_1">
                            <!-- ENTRY FOR ADDITIONAL INFORMATION STARTED-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light portlet-fit portlet-form bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="icon-settings font-dark"></i>
                                                <span class="caption-subject font-dark sbold uppercase">Please edit additional information business</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <form class="form-horizontal" id="edit-additional-information" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                                <input type="hidden" id="additional_information_id" name="additional_information_id" value="<?= $encrypted_code ?>"/>
                                                <div class="form-body">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"> Profile Picture</label>
                                                        <div  class="col-md-4 input_element">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <?php
                                                                if ($result['profile_image'] !== NULL) {
                                                                    $image_url = ASSET_URL . 'uploads/vendor-images/admin-preview/' . $result['profile_image'];
                                                                } else {
                                                                    $image_url = ASSET_URL . 'uploads/vendor-images/admin-preview/No-Image-Placeholder.jpg';
                                                                }
                                                                ?>
                                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                                    <img src="<?= $image_url ?>" alt="" /> 
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;" id="fileinput-preview-container"> </div>
                                                                <div>
                                                                    <span class="btn default btn-file">
                                                                        <span class="fileinput-new"> Select image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        <input type="file" name="profile_image" id="profile_image"> </span>
                                                                    <a href="javascript:vaoid(0);" class="btn default fileinput-exists" data-dismiss="fileinput" onclick="javascript:remove_profile_picture()"> Remove </a>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix margin-top-10">
                                                                <span class="label label-danger"> NOTE! </span>
                                                                <span> This will be your profile picture and will be displayed in the list view. For better result use width 148 pixels and height 120 pixels </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"> Vendor Url</label>
                                                        <div class="col-md-4 input_element">
                                                            <input type="text" name="vendor_site_url"  class="form-control" id="vendor_site_url" value="<?= $result["vendor_site_url"] ?>"/> 
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"> Vendor Facebook Page</label>
                                                        <div class="col-md-4 input_element">
                                                            <input type="text" name="vendor_facebook_page"  class="form-control" id="vendor_facebook_page" value="<?= $result["vendor_facebook_page"] ?>"/> 
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"> Meta Tag</label>
                                                        <div class="col-md-4 input_element">
                                                            <input type="text" name="meta_tag"  class="form-control" id="meta_tag" value="<?= $result["meta_tag"] ?>"/> 
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"> Meta Description</label>
                                                        <div class="col-md-4 input_element">
                                                            <input type="text" name="meta_description"  class="form-control" id="meta_description" value="<?= $result["meta_description"] ?>"/> 
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"> Meta Keywords</label>
                                                        <div class="col-md-4 input_element">
                                                            <input type="text" name="meta_keywords"  class="form-control" id="meta_keywords" value="<?= $result["meta_keywords"] ?>"/> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions">
                                                    <div class="row">
                                                        <div class="col-md-offset-3 col-md-9">
                                                            <button type="button" class="btn green" name="additional_info_business" id="additional_info_business">Update</button>
                                                            <a type="button" class="btn grey-salsa btn-outline" href="<?= base_url("all-business"); ?>">Cancel</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- ENTRY FOR ADDITIONAL INFORMATION ENDED-->
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <!--ENTRY FOR GALLERY STARTED-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light portlet-fit portlet-form bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="icon-settings font-dark"></i>
                                                <span class="caption-subject font-dark sbold uppercase">Banner Images List</span>
                                            </div>
                                            <div class="actions">
                                                <a href="javascript:void(0);" class="btn blue"  data-toggle="modal" data-target="#uploadModal">
                                                    <i class="fa fa-plus"></i> Add Banner Image </a>
                                            </div>
                                        </div>
                                        <div class="portlet-body" id="banner_images_set">
                                            <?php
                                            if (count($banner_images) > 0) {
                                                ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th  class="text-center">#</th>
                                                                <th  class="text-center"> Image </th>
                                                                <th  class="text-center"> Content </th>
                                                                <th  class="text-center"> Date </th>
                                                                <th  class="text-center"> Action </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($banner_images as $key => $value) { ?>
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <?= $key + 1; ?>
                                                                    </td>
                                                                    <td  class="text-center">
                                                                        <img src="<?= ASSET_URL . "uploads/business-banner-images/thumb/" . $value["image_name"] ?>">
                                                                    </td>
                                                                    <td  class="text-center">
                                                                        <?= $value["image_caption"] !== "" ? $value["image_caption"] : "" ?>
                                                                    </td>
                                                                    <td  class="text-center">
                                                                        <?= date("Y-m-d", strtotime($value["updated_at"])) ?>
                                                                    </td>
                                                                    <td  class="text-center">
                                                                        <a href="javascript:void(0);" class="btn btn-icon-only green" onclick="javascript:edit_banner_business(<?= $value["id"] ?>)">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                                                                        <a href="javascript:void(0);" class="btn btn-icon-only red" onclick="javascript:delete_banner_business(<?= $value["id"] ?>, '<?= ASSET_URL . "uploads/business-banner-images/thumb/" . $value["image_name"]; ?>')">
                                                                            <i class="fa fa-trash"></i>
                                                                        </a>
                                                                        <a href="javascript:void(0);" class="btn btn-icon-only blue" onclick="javascript:preview_banner_business(<?= $value["id"] ?>, )">
                                                                            <i class="fa fa-file-image-o"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="alert alert-block alert-danger fade in">
                                                    <h4 class="alert-heading">OOPS !!!</h4>
                                                    <p>Sorry!!! No images uploaded. Please add some image for the banner. Let all know about the speciality of the business.Please choose some good images.<br/>
                                                        Please upload image with width 800 pixels and height 400 pixels</p>
                                                    <p>
                                                        <a class="btn red" data-toggle="modal" data-target="#uploadModal"> Do this </a>
                                                        <a class="btn blue" href="javascript:;"> Cancel </a>
                                                    </p>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--ENTRY FOR GALLERY ENDED-->
                        </div>
                        <div class="tab-pane" id="tab_3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
<div style="display:none">
    <input type="hiden" id="encrypted_code" value="<?= $encrypted_code ?>"/>
</div>



<!-- Add banner gallery modal starts here-->
<div class="modal fade" id="uploadModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase">Add Banner Images</span>
                </div>
            </div>
            <div class="modal-body">
                <form id="add-image-banner">
                    <input type="hidden" id="banner_business_id" name="banner_business_id" value="<?= $encrypted_code ?>">
                    <div class="form-group">
                        <label for="image_caption">Image Caption</label>
                        <input type="text" class="form-control" id="image_caption" name="image_caption" placeholder="Enter caption for the image">
                        <small id="emailHelp" class="form-text text-muted">This caption will be shown over the banner image</small>
                    </div>

                    <div class="form-group">
                        <label for="banner_image">Banner Image</label>
                        <input type="file" class="form-control-file" id="banner_image" aria-describedby="fileHelp" name="banner_image">
                        <small id="fileHelp" class="form-text text-muted">Please upload image with width 800 pixels and height 400 pixels</small>
                    </div>
                    <button type="button" class="btn btn-primary" id="add-banner-image">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default blue" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Add banner gallery modal ends here-->

<!--Banner preview modal starts here-->
<div id="preview_banner_business" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase">Preview of the banner</span>
                </div>
                
            </div>
            <div class="modal-body" id="banner-image-url-business">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default blue" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--Banner preview modal ends here-->

<!--Edit banner gallery modal starts here-->
<div class="modal fade" id="edit-banner-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase">Edit Banner Images</span>
                </div>
            </div>
            <div class="modal-body">
                <form id="edit-image-banner">
                    <input type="hidden" id="edit_banner_business_id" name="edit_banner_business_id" value="<?= $encrypted_code ?>">
                    <div class="form-group">
                        <label for="edit_image_caption">Image Caption</label>
                        <input type="text" class="form-control" id="edit_image_caption" name="edit_image_caption" placeholder="Enter caption for the image">
                        <small id="emailHelp" class="form-text text-muted">This caption will be shown over the banner image</small>
                    </div>

                    <div class="form-group">
                        <label for="edit_banner_image">Banner Image</label>
                        <input type="file" class="form-control-file" id="edit_banner_image" aria-describedby="fileHelp" name="edit_banner_image">
                        <small id="fileHelp" class="form-text text-muted">Please upload image with width 800 pixels and height 400 pixels</small>
                    </div>
                    <button type="button" class="btn btn-primary" id="edit-banner-image">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default blue" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Edit banner gallery modal ends here-->


<?php $this->load->view('layouts/footer'); ?>

