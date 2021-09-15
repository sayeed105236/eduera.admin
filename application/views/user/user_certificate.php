<style>
<!--
span.cls_003{font-family:"Monotype Corsiva",serif;font-size:46.1px;color:rgb(81,94,101);font-weight:normal;font-style:italic;text-decoration: none}
div.cls_003{font-family:"Monotype Corsiva",serif;font-size:46.1px;color:rgb(81,94,101);font-weight:normal;font-style:italic;text-decoration: none}
span.cls_002{font-family:"Monotype Corsiva",serif;font-size:24.7px;color:rgb(81,94,101);font-weight:normal;font-style:italic;text-decoration: none}
div.cls_002{font-family:"Monotype Corsiva",serif;font-size:24.7px;color:rgb(81,94,101);font-weight:normal;font-style:italic;text-decoration: none}
span.cls_004{font-family:"Monotype Corsiva",serif;font-size:10.0px;color:rgb(43,42,41);font-weight:normal;font-style:italic;text-decoration: none}
div.cls_004{font-family:"Monotype Corsiva",serif;font-size:10.0px;color:rgb(43,42,41);font-weight:normal;font-style:italic;text-decoration: none}
span.cls_005{font-family:"Monotype Corsiva",serif;font-size:6.9px;color:rgb(43,42,41);font-weight:normal;font-style:italic;text-decoration: none}
div.cls_005{font-family:"Monotype Corsiva",serif;font-size:6.9px;color:rgb(43,42,41);font-weight:normal;font-style:italic;text-decoration: none}
-->


</style>

<script type="text/javascript" src="5fddaf08-7982-11ea-8b25-0cc47a792c0a_id_5fddaf08-7982-11ea-8b25-0cc47a792c0a_files/wz_jsgraphics.js"></script>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">

            <?php echo validation_errors(); ?>

            <?php if ($this->session->flashdata('send_email_success')) { ?>

                <div class="alert alert-success alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('send_email_success') ?>
                </div>

            <?php } ?>

            <?php if ($this->session->flashdata('send_email_failed')) { ?>

                <div class="alert alert-danger alert-dismissible  show" role="alert">
                    <strong></strong> 
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $this->session->flashdata('send_email_failed') ?>
                </div>

            <?php } ?>
            <div class="x_content">
                    <form method="post" action="<?=base_url('users/' . $user_data->id . '/user_certificate')?>">
                <div class="form-group row"  >
                    <label class="control-label col-md-2 col-sm-2 col-xs-6" for="title">Choose option :
                    </label>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <select class="form-control"  name="course_id" id="course_id">
                            <option value="">Choose your course</option>
                            <?php foreach ($enroll_history as $course) {?>
                                <option value="<?=$course->id?>" <?=isset($_POST['course_id']) && $_POST['course_id'] == $course->id ? 'selected' : ''?>><?=$course->title?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-4">
                        <button class="btn btn-info">Search</button>
                    </div>
                </div>
            </form>
                    <br>
   <div class="container">
                    <div class="row">
                        <div class="col">

                        <?php echo validation_errors(); ?>
                          
                                       
                            <?php

                            if ($_POST['course_id'] != null) {
                                if($course_info->certificate != NULL || $course_info->certificate != ''){
                              if ($_POST['course_id'] == 1) {
                                $certificate_info->course_name = 'IT Service Management Foundation';
                              } elseif ($_POST['course_id'] == 4) {
                                $certificate_info->course_name = 'Project Management Foundation';
                              } elseif ($_POST['course_id'] == 8) {
                                $certificate_info->course_name = 'Project Management Practitioner';
                              }elseif($_POST['course_id'] == 56){
                                $certificate_info->course_name = 'ITIL4 Foundation Exam Preparation Training';
                              }elseif($_POST['course_id'] == 60){
                                $certificate_info->course_name = 'ITIL4 Foundation Training';
                              } else {
                                $certificate_info->course_name;
                              }

                           
                              $get_date = date('d', strtotime($certificate_info->created_at));
                              $get_month = date('m', strtotime($certificate_info->created_at));
                              $get_year = date('Y', strtotime($certificate_info->created_at));
                              // Create date object to store the DateTime format
                                    $dateObj = DateTime::createFromFormat('!m', $get_month);

                              // Store the month name to variable
                                    $monthName = $dateObj->format('F');
                                    ?>
                                    <div style="position:relative;left:50%;margin-left:-420px;top:0px;width:841px;height:595px;border-style:outset;overflow:hidden">
                                        <div style="position:absolute;left:0px;top:0px">

                                            <img src="http://file.server.eduera.com.bd/certificate/<?=$course_info->certificate ?>" width=841 height=595>
                                        </div>
                                     
                                        <div class="row" style="position:absolute;left:170.59px;top:152.23px; text-align: center;">
                                            <div class="col-md-12">
                                                <div  class="cls_003"><span class="cls_003">Certification of completion</span></div>
                                            </div>
                                        </div>

                                        <div class="row" style=" text-align: center; position:absolute; left:130.62px;top:280.02px; padding-right: 30px;">
                                            <div class="col-md-12">
                                                <span class="cls_002 cls_003" style="font-weight:italic;"> <?=$certificate_info->first_name ?> <?= $certificate_info->last_name ?>  has successfully completed <br><span style="color:#e33667;"><?= $certificate_info->course_name ?></span> online training on <?= $get_date ?> <?=$monthName ?>, <?= $get_year ?> </span>
                                            </div>
                                        </div>

                                        <div style="position:absolute;left:16.06px;top:567.77px;" class="cls_004"><span class="cls_004">Certificate no:  <?=$certificate_info->certificate_no?></span></div>
                                        <div style="position:absolute;right:16.06px;top:567.77px;font-weight:bold" class="cls_004"><span class="cls_004">Verification URL:  www.eduera.com.bd/home/certificate</span></div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col">
                                           <!--  <a href="<?=base_url('users/download_certificate/' . $certificate_info->certificate_key)?>" target="_blank"  class="btn btn-primary " ><i class="fa fa-download"></i> Download</a> -->

                                           <a href="https://eduera.com.bd/home/get_certificate/<?=$certificate_info->certificate_key?>" target="_blank"  class="btn btn-primary " ><i class="fa fa-download"></i> Download</a>
                                        </div>
                                        <div class="col">
                                            <a href="<?=base_url('users/send_certificate_mail/' . $certificate_info->certificate_key)?>" class="btn btn-primary " ><i class="fa fa-envelope-square"></i> Send Mail with Certificate</a>
                                        </div>
                                    </div>
                                   

                                <?php 
                            }
                            } else {
                    
                                    ?>
                                <div style="position:relative;left:50%;margin-left:-420px;top:0px;width:841px;height:595px;border-style:outset;overflow:hidden">
                                    <div style="position:absolute;left:0px;top:0px">

                                        <img src="<?=base_url('assets/frontend/certificate/1.jpg')?>" width=841 height=595>
                                    </div>
                                   

                                    <div class="row" style="position:absolute;left:140.59px;top:152.23px; text-align: center;">
                                        <div class="col-md-12">
                                            <div  class="cls_003"><span class="cls_003">Certification of completion</span></div>
                                        </div>
                                    </div>

                                    <div class="row" style=" text-align: center; position:absolute; left:120.62px;top:280.02px; padding-right: 30px;">
                                        <div class="col-md-12">
                                            <span class="cls_002 cls_003" style="font-weight:italic;"> ``Your name will be here``  has successfully completed <br><span style="color:#e33667;">``Course name`` </span> online training on 21 april, 2020 </span>
                                        </div>
                                    </div>

                                    <div style="position:absolute;left:16.06px;top:567.77px;" class="cls_004"><span class="cls_004">Certificate no:  20200421004000001</span></div>
                                    <div style="position:absolute;right:16.06px;top:567.77px;font-weight:bold" class="cls_004"><span class="cls_004">Verification URL:  www.eduera.com.bd/home/certificate</span></div>
                                </div>
                            <?php
                                }
                             
                            ?>
                                         


                        </div>

                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>


