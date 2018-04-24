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
                    <span>Edit Admin Profile</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"> Admin Profile | Edit
            <small>Admin account page</small>
        </h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PROFILE CONTENT -->
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light ">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1_1" data-toggle="tab">Personal Info</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_2" data-toggle="tab">Change Avatar</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_3" data-toggle="tab">Change Password</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <!-- PERSONAL INFO TAB -->
                                        <div class="tab-pane active" id="tab_1_1">
                                            <form role="form" action="#">
                                                <div class="form-group">
                                                    <label class="control-label">Name</label>
                                                    <input type="text" class="form-control" value="<?php echo htmlentities(set_value('name') !== "" ? set_value('name') : $result['name']); ?>" id="name"/> 
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Email</label>
                                                    <input type="email" id="email" class="form-control" value="<?php echo htmlentities(set_value('email') !== "" ? set_value('email') : $result['email']); ?>"/> 
                                                </div>

                                                <div class="margiv-top-10">
                                                    <button onclick="javascript:saveAdminInfo()" class="btn green" type="button"> Save Changes </button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- END PERSONAL INFO TAB -->
                                        <!-- CHANGE AVATAR TAB -->
                                        <div class="tab-pane" id="tab_1_2">

                                            <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                            <?php if ($result['profile_picture'] === NULL || $result['profile_picture'] === "") { ?>
                                                                <img src="<?= ASSET_URL . 'global/img/DummyUser.png' ?>" alt="" class="target_image">
                                                            <?php } else { ?>
                                                                <img src="<?= ASSET_URL . "uploads/admin-images/preview/" . $result['profile_picture'] ?>" alt="" class="target_image">
                                                            <?php } ?>
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                        <div>
                                                            <span class="btn default btn-file">
                                                                <span class="fileinput-new"> Select image </span>

                                                                <span class="fileinput-exists"> Change </span> 

                                                                <input type="file" name="profile_picture" id="profile_picture" accept="image/*"> 
                                                            </span>
                                                            <a href="javascript:;" class="btn default fileinput-exists"  data-dismiss="fileinput"> Remove </a>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix margin-top-10" id="message">
                                                        <div class="clearfix margin-top-10">
                                                            <span class="label label-danger">NOTE! </span>
                                                            <span>
                                                                &nbsp;&nbsp;&nbsp;Max 2MB image is allowed
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="margin-top-10">
                                                    <button type="button" class="btn green" onclick="javascript:submit_image();"> Submit </button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- END CHANGE AVATAR TAB -->
                                        <!-- CHANGE PASSWORD TAB -->
                                        <div class="tab-pane" id="tab_1_3">
                                            <form action="#">
                                                <div class="form-group">
                                                    <label class="control-label">Current Password</label>
                                                    <input type="password" class="form-control" name="password" id="password" /> </div>
                                                <div class="form-group">
                                                    <label class="control-label">New Password</label>
                                                    <input type="password" class="form-control" name="new-password" id="new-password"/> </div>
                                                <div class="form-group">
                                                    <label class="control-label">Re-type New Password</label>
                                                    <input type="password" class="form-control" name="con-password" id="con-password"/> </div>
                                                <div class="margin-top-10">
                                                    <button type="button" class="btn green" onclick="javascript:updatePassword()"> Change Password </button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- END CHANGE PASSWORD TAB -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PROFILE CONTENT -->
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->
<?php $this->load->view('layouts/footer'); ?>