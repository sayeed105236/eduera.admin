<div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
        <a href="<?=base_url('dashboard')?>" class="site_title"> <span><img src="<?php echo base_url() . 'uploads/system/eduera-logo-small.png'; ?>" alt="" style="width: 120px;"></span></a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
        <div class="profile_pic">
            <img src="<?php echo base_url() . 'uploads/user_image/placeholder.png'; ?>" alt="" class="img-circle profile_img">
        </div>
        <div class="profile_info">
            <span>Welcome,</span>
            <h2><?=$this->session->userdata('name')?></h2>
        </div>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
            <h3>General</h3>
            <ul class="nav side-menu">
                <li><a href="<?=base_url('home')?>"><i class="fa fa-home"></i> Home <span class="label label-success pull-right"></span></a></li>
                <li><a href="<?=base_url('users')?>"><i class="fa fa-users"></i> Users <span class="label label-success pull-right"></span></a></li>
                <li><a href="<?=base_url('home/membership')?>"><i class="fa fa-user-plus"></i> Membership <span class="label label-success pull-right"></span></a></li>
                <?php if (has_role($this->session->userdata('user_id'), 'CATEGORY_READ')) {?>
                    <li><a href="<?=base_url('categories')?>"><i class="fa fa-cubes" aria-hidden="true"></i> Categories <span class="label label-success pull-right"></span></a></li>
                <?php }?>
                <li><a href="<?=base_url('courses')?>"><i class="fa fa-book"></i> Courses <span class="label label-success pull-right"></span></a></li>
                <li><a href="<?=base_url('home/faq')?>"><i class="fa fa-question"></i> FAQ <span class="label label-success pull-right"></span></a></li>
                <li><a href="<?=base_url('course/coupon')?>"><i class="fa fa-percent"></i> Coupon <span class="label label-success pull-right"></span></a></li>
                <?php if (has_role($this->session->userdata('user_id'), 'CHAT_READ')) {?>
                    <li><a class="chatting" href="<?=base_url('home/user_chating')?>"><i class="fa fa-inbox"></i> Chat 
                        <span class="badge total_unseen_admin_message" style="background:red"><?= ($total_unseen_message) ? $total_unseen_message : 0 ?></span>
                    </a>
                </li>
            <?php }?>
            <?php if (has_role($this->session->userdata('user_id'), 'MESSAGE_READ')) {?>
                <li><a href="<?=base_url('home/message_and_email')?>"><i class="fa fa-commenting-o" aria-hidden="true"></i> Message & Email 

                </a>
            </li>

        <?php } ?>
        <?php if (has_role($this->session->userdata('user_id'), 'CURRENCY_READ')) {?>
            <li><a href="<?=base_url('home/currency')?>"><i class="fa fa-money" aria-hidden="true"></i> Currency 

            </a>
        </li>

    <?php } ?>


    <?php if (has_role($this->session->userdata('user_id'), 'SYSTEM_BASIC')) {?>
        <li><a href="<?=base_url('eduera_system')?>"><i class="fa fa-cogs"></i> System settings <span class="label label-success pull-right"></span></a></li>
    <?php }?>

    <?php if (has_role($this->session->userdata('user_id'), 'ADMIN_LOG_READ')) {?>
        <li><a href="<?=base_url('home/admin_logger')?>"><i class="fa fa-history" aria-hidden="true"></i> Admin log <span class="label label-success pull-right"></span></a></li>
    <?php }?>
</ul>
</div>
</div>
<!-- /sidebar menu -->

<!-- /menu footer buttons -->
<div class="sidebar-footer hidden-small">
    <a data-toggle="tooltip" data-placement="top" title="Settings">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Lock">
        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?=base_url('login/logout/')?>">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
    </a>
</div>
<!-- /menu footer buttons -->
</div>