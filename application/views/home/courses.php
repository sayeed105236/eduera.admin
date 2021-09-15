<section class="category-header-area">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('home') ?>"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item">
                            <a href="#">Courses</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="category-course-list-area">
    <div class="container">
        <div class="category-filter-box filter-box clearfix">
            <span>Showing on this page : <?php echo $number_of_courses_for_this_page . ' of ' . $number_of_total_courses ?></span>
        </div>
        <div class="row">
            <div class="col-lg-3 filter-area">
                <div class="card">
                    <a href="javascript::"  style="color: unset;">
                        <div class="card-header filter-card-header" id="headingOne" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="true" aria-controls="collapseFilter">
                            <h6 class="mb-0">
                                Filter
                                <i class="fas fa-sliders-h" style="float: right;"></i>
                            </h6>
                        </div>
                    </a>
                    <div id="collapseFilter" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body pt-0">
                            <div class="filter_type">
                                <h6>Categories</h6>
                                <ul>
                                    <?php foreach ($category_list as $category) { ?>
                                    <li>
                                        <a href="<?php echo base_url('home/courses?category='.$category->id); ?>"><i class="fas fa-angle-double-right"></i> <?php echo $category->name; ?></a>
                                        <?php if (count($category->sub_category_list) > 0 ) { ?>
                                        <ul class="sidebar-subcategory-collpased-area">
                                            <?php foreach ($category->sub_category_list as $sub_category) { ?>
                                            <li class="ml-2"><a href="<?php echo base_url('home/courses?category='.$sub_category->id); ?>"><i class="fas fa-angle-right"></i> <?php echo $sub_category->name; ?></a></li>
                                            <?php } ?>
                                        </ul>
                                        <?php } ?>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="category-course-list">
                    <div class="row">
                        <?php foreach ($course_list as $course) { ?>
                        <div class="col-md-4 col-lg-4">
                            <div class="course-box-wrap">
                                <a href="<?= base_url('course/'. $course->id) ?>">
                                    <div class="course-box">
                                        <div class="course-image">
                                            <img src="<?php echo $this->course_model->get_course_thumbnail_url($course->id); ?>" alt="" class="img-fluid">
                                        </div>
                                        <div class="course-details">
                                            <h5 class="title"><?php echo $course->title; ?></h5>
                                            <p class="instructors"><?php echo $course->short_description; ?></p>
                                            <?php if ($course->is_free_course == 1): ?>
                                                <p class="price text-right">Free</p>
                                            <?php else: ?>
                                                <?php if ($course->discount_flag == 1): ?>
                                                    <p class="price text-right">
                                                        <small><?php echo $course->price; ?></small>
                                                        <?php echo $course->discounted_price; ?>
                                                    </p>
                                                <?php else: ?>
                                                    <p class="price text-right"><?php echo $course->price; ?></p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <?php if (count($course_list) == 0): ?>
                        <p>No result found</p>
                    <?php endif; ?>
                </div>
                <nav>
                    <?php //if (count($course_list) > $page_limit){
                        echo $this->pagination->create_links();
                    //}?>
                </nav>
            </div>
        </div>
    </div>
</section>