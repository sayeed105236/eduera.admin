<section class="home-banner-area">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <div class="home-banner-wrap">
                    <h2>Learn on your schedule</h2>
                    <p>Study any topic, anytime. Explore thousands of courses for the lowest price ever!</p>
                    <form class="" action="<?php echo base_url('home/courses'); ?>" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" name = "query" placeholder="What are you looking for?">
                            <div class="input-group-append">
                                <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="home-fact-area">
    <div class="container-lg">
        <div class="row">
            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto ml-auto mr-auto">
                    <i class="fas fa-bullseye float-left"></i>
                    <div class="text-box">
                        <h4>19 online courses</h4>
                        <p>Explore a variety of topics</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto ml-auto mr-auto">
                    <i class="fa fa-check float-left"></i>
                    <div class="text-box">
                        <h4>Expert instruction</h4>
                        <p>Find the right courses for you</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto ml-auto mr-auto">
                    <i class="fa fa-clock float-left"></i>
                    <div class="text-box">
                        <h4>Lifetime access</h4>
                        <p>Learn on your schedule</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
	<div class="row course-container">
        <?php foreach ($course_list as $course) { ?>
		<div class="course-box-wrap col-md-3">
            <a href="<?= base_url('course/' . $course->id) ?>" class="course-block">
                <div class="course-box">
                    <!-- <div class="course-badge position best-seller">Best seller</div> -->
                    <div class="course-image">
                        <img src="<?= $this->course_model->get_course_thumbnail_url($course->id) ?>" alt="" class="img-fluid">
                    </div>
                    <div class="course-details">
                        <h5 class="title"><?= $course->title ?></h5>
                        <p class="instructors"><?= $course->short_description ?></p>
	                    <?php if ($course->is_free_course == 1): ?>
	                        <p class="price text-right"><?= get_phrase('free') ?></p>
	                    <?php else: ?>
	                        <?php if ($course->discount_flag == 1): ?>
	                            <p class="price text-right">
                                    <small><?= $course->price ?></small>
                                    <?= $course->discounted_price ?>
                                </p>
	                        <?php else: ?>
	                            <p class="price text-right"><?= $course->price ?></p>
	                        <?php endif; ?>
	                    <?php endif; ?>
                	</div>
            	</div>
        	</a>
        </div>
        <?php } ?>
	</div>
</section>
