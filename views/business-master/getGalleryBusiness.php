<?php
if (count($banner_images) > 0) {
    ?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th> Image </th>
                    <th> Content </th>
                    <th> Date </th>
                    <th> Action </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($banner_images as $key => $value) { ?>
                    <tr>
                        <td>
                            <?= $key + 1; ?>
                        </td>
                        <td>
                            <img src="<?= ASSET_URL . "uploads/business-banner-images/thumb/" . $value["image_name"] ?>">
                        </td>
                        <td>
                            <?= $value["image_caption"] !== "" ? $value["image_caption"] : "" ?>
                        </td>
                        <td>
                            <?= date("Y-m-d", strtotime($value["updated_at"])) ?>
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="btn btn-icon-only green" onclick="javascript:edit_banner_business(<?= $value["id"] ?>)">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-icon-only red" onclick="javascript:delete_banner_business(<?= $value["id"] ?>, '<?= ASSET_URL . "uploads/business-banner-images/thumb/" . $value["image_name"]; ?>')">
                                <i class="fa fa-trash"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-icon-only blue" onclick="javascript:preview_banner_business(<?= $value["id"] ?>,)">
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