<section class="course-header-area">
<div class="container">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="course-header-wrap">
                <h1 class="title"><?php echo $course->title; ?></h1>
                <p class="subtitle"><?php echo $course->short_description; ?></p>
                <div class="rating-row">
                    <span class="course-badge best-seller"><?php echo $course->level; ?></span>
                    <!-- <span class="d-inline-block average-rating">4</span><span>(1 rating)</span> -->
                    <span class="enrolled-num"><?php echo $course->enrollment; ?> students enrolled</span>
                </div>
                <div class="created-row">
                    <!-- <span class="created-by">Created by <a href="">Global Skill</a></span> -->
                    <?php if ($course->last_modified == null) { ?>
                        <span class="last-updated-date">Last updated <?php echo date('D, d-M-Y', $course->date_added); ?></span>
                    <?php } else { ?>
                        <span class="last-updated-date">Last updated <?php echo date('D, d-M-Y', $course->last_modified); ?></span>
                    <?php } ?>
                    <span class="comment">
                        <i class="fas fa-comment"></i><?php echo $course->language; ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">

        </div>
    </div>
</div>
</section>

<section class="course-content-area">
    <div class="container">
        <div class="row">

            <div class="col-lg-8">

                <div class="what-you-get-box">
                    <div class="what-you-get-title">What will you learn?</div>
                    <ul class="what-you-get__items">
                        <?php foreach ($course->outcomes as $outcome) { 
                            if ($outcome != "") {
                            ?>
                            <li><?php echo $outcome; ?></li>
                        <?php }
                        }
                        ?>
                    </ul>
                </div>
                <br>


                <div class="course-curriculum-box">
                    <div class="course-curriculum-title clearfix">
                        <div class="title float-left">Curriculam for this course</div>
                        <div class="float-right">
                            <span class="total-lectures"><?php echo $course->lesson_count; ?> lessons</span>
                            <span class="total-time"><?php echo second_to_time_conversion($course->duration_in_second); ?> Hours</span>
                        </div>
                    </div>

                    <div class="course-curriculum-accordion">
                        <?php foreach ($course->section_list as $section) { ?>
                        <div class="lecture-group-wrapper">
                            <div class="lecture-group-title clearfix" data-toggle="collapse" data-target="#collapse-<?php echo $section->id; ?>" aria-expanded="false">
                                <div class="title float-left"><?php echo $section->title; ?></div>
                                <div class="float-right">
                                    <span class="total-lectures"><?php echo count($section->lesson_list); ?> lessons</span>
                                    <span class="total-time"><?php echo second_to_time_conversion($section->duration_in_second); ?> Hours</span>
                                </div>
                            </div>

                            <div id="collapse-<?php echo $section->id; ?>" class="lecture-list collapse">
                                <ul>
                                    <?php foreach ($section->lesson_list as $lesson) { ?>
                                    <li class="lecture has-preview">
                                        <span class="lecture-title"><?php echo $lesson->title; ?></span>
                                        <span class="lecture-preview center lespre"> Preview</span>
                                        <span class="lecture-time float-right"><?php echo second_to_time_conversion($lesson->duration_in_second); ?></span>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>                

                <div class="requirements-box">
                    <div class="requirements-title">Requirements</div>
                    <div class="requirements-content">
                        <ul class="requirements__list">
                            <?php foreach ($course->requirements as $requirement) { ?>
                                <li><?php echo $requirement; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="description-box view-more-parent">
                    <div class="view-more" onclick="viewMore(this,'hide')">+ View More</div>
                    <div class="description-title">Description</div>
                    <div class="description-content-wrap">
                        <div class="description-content">
                            <?php echo $course->description; ?>
                        </div>
                    </div>
                </div>

            </div>



            <!-- Right side preview part -->
            <div class="col-lg-4">
                <div class="course-sidebar natural">
                <?php if ($course->video_url != null || $course->video_url != ""): ?>
                    <div class="preview-video-box">
                        <a data-toggle="modal" data-target="#CoursePreviewModal">
                            <img src="<?php echo $this->course_model->get_course_thumbnail_url($course->id); ?>" alt="" class="img-fluid">
                            <span class="play-btn"></span>
                        </a>
                    </div>
                <?php endif; ?>
                <div class="course-sidebar-text-box">
                    <div class="includes">
                        <div class="title"><b>Includes:</b></div>
                        <ul>
                            <li><i class="far fa-file-video"></i><?php echo second_to_time_conversion($course->duration_in_second); ?> on demand videos</li>
                            <li><i class="far fa-file"></i><?php echo $course->lesson_count; ?> lessons</li>
                            <li><i class="far fa-compass"></i>Full lifetime access</li>
                            <li><i class="fas fa-mobile-alt"></i>Access on mobile and tv</li>
                        </ul>
                    </div>
                </div>
              </div>
            </div>


            
        </div>
    </div>
</section>


<script type="text/javascript">
    function viewMore(element,visibility){
        if(visibility=='hide'){
            $(element).parent('.view-more-parent').addClass('expanded');
            $(element).remove();
        }
        else if($(element).hasClass('view-more')){
            $(element).parent('.view-more-parent').addClass('expanded has-hide');
            $(element).removeClass('view-more').addClass('view-less').html('- View Less');
        }
        else if($(element).hasClass('view-less')){
            $(element).parent('.view-more-parent').removeClass('expanded has-hide');
            $(element).removeClass('view-less').addClass('view-more').html('+ View More');
        }
    }
</script>
