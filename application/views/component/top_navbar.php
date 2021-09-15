<div class="nav_menu">
    <nav>
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" alt="">
                    <?= $this->session->userdata('name') ?>
                    <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                        <a href="javascript:;">
                            <span class="badge bg-red pull-right">50%</span>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>
                    <li><a href="<?= base_url('login/logout/') ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                </ul>
            </li>

            <li role="presentation" class="dropdown" >
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false" id="notify_result">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge bg-green " id="div1"><?= count($notification) ?></span>
                    </a>
                <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                
                    <?php
                    foreach($notification as $notify){
                    ?>
                   
                    <li >
                      
                    <?php 
                  if(isset($notify->user_data)){
                      foreach($notify->user_data as $user){ ?>

                        <a href="<?= base_url('users/'.$user->id.'/info?notify_id='.$notify->notify_id)?>">
                          
                            <span class="image"><img src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" alt="Profile Image" /></span>
                            <span>
                                <span><?= $user->first_name?> <?= $user->last_name?></span>
                                <span class="time"><?=  time_elapsed_string($user->created_at) ?></span>
                            </span>
                            <span class="message">
                <?php
                            if($notify->notification_type == 'registration'){
                            ?>
                                Successfully complete the registration..
                            <?php
                               
                              } 

                            ?>
                            </span>
                        </a>
                        <?php

                            }

                        }
                        ?>
                    </li>
                
                          <?php    
                    }
                    ?>
                
                     <div id="sound"></div>
                </ul>
            </li>


            <li role="presentation" class="dropdown" >
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false" id="course_notify_result">
                        <i class="fa fa-bell-o"></i>
                        <span class="badge bg-green " id="div2"><?= count($course_notification) ?></span>
                    </a>
                <ul id="menu2" class="dropdown-menu list-unstyled msg_list" role="menu">
                
                    <?php
                    foreach($course_notification as $course_notify){
                    ?>
                   
                    <li >
                      
                    <?php 
                  if(isset($course_notify->course_data)){
                      foreach($course_notify->course_data as $course){ ?>

                        <a href="<?= base_url('course/course_review/'.$course->id.'?notify_id='.$course_notify->notify_id)?>">
                          
                            <span class="image"><img src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" alt="Profile Image" /></span>
                            <span>
                                <span><?= $course->title?></span>
                                <span class="time"><?=  time_elapsed_string($course_notify->created_at) ?></span>
                            </span>
                            <span class="message">
                <?php
                            if($course_notify->notification_type == 'course_review'){
                            ?>
                                Successfully complete the course review..
                            <?php
                               
                              } 

                            ?>
                            </span>
                        </a>
                        <?php

                            }

                        }
                        ?>
                    </li>
                
                          <?php    
                    }
                    ?>
                
                     <div id="sound"></div>
                </ul>
            </li>
        </ul>
    </nav>
</div>
<script src="https://js.pusher.com/6.0/pusher.min.js"></script>
  <script>
  

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('769aa47391ce06086fa0', {
      cluster: 'ap2'
    });
    
    
    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(pusher_data) {
    /*Notification sound here*/
    
    var aSound = document.createElement('audio');
    aSound.setAttribute('src', 'https://www.file.server.eduera.com.bd/notification.mp3');
    aSound.play();
    /*notification sound end*/

  
     
       $.ajax({
          url: "<?php echo base_url('rest/api/get_notification_data')?>",
          type: 'GET',
          dataType: 'json',
          success: function(response){
          html  = '<i class="fa fa-envelope-o"></i><span class="badge bg-green " id="div1">'+response.notification.length+'</span>';
          nexthtml = '';
          regi = '';
          for(var j = 0; j < response.notification.length; j++){

          for(var i = 0; i< response.notification[j].user_data.length; i++){

            if(response.notification[j].notification_type == 'registration'){
                regi = 'Successfully complete the registration..';
            
             
             
              url = '<?=base_url('users/')?>'+ response.notification[j].user_data[i].id + '/info?notify_id='+response.notification[j].notify_id ;
            
              nexthtml += '<li><a href="'+url+'"><span class="image"><img src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" alt="Profile Image" /></span>';
              nexthtml += ' <span> <span>'+response.notification[j].user_data[i].first_name+'  ' + response.notification[j].user_data[i].last_name+' </span><span class="time">'+time_ago(response.notification[j].user_data[i].created_at)+'</span> </span>';
              nexthtml += '<span class="message">'+regi+'</span></a></li>';
           }
          
          }
          
            
           $("#notify_result").html(html);
           $("#menu1").html(nexthtml);
           
          }
          }
       });
    });
    
    
  </script>
 

     <script>
    

      // Enable pusher logging - don't include this in production
      Pusher.logToConsole = true;

      var pusher = new Pusher('c203510a7bc26284f91c', {
        cluster: 'ap1'
      });
      
      
      var channel = pusher.subscribe('my-channel');
      channel.bind('my-event', function(pusher_data) {
      
      /*Notification sound here*/
      
      var aSound = document.createElement('audio');
      aSound.setAttribute('src', 'https://www.file.server.eduera.com.bd/notification.mp3');
      aSound.play();
      /*notification sound end*/

    
       
       $.ajax({
          url: "<?php echo base_url('rest/api/get_notification_data')?>",
          type: 'GET',
          dataType: 'json',
          success: function(response){
            console.log(response.course_notification.length);
          html  = '<i class="fa fa-bell-o"></i><span class="badge bg-green " id="div2">'+response.course_notification.length+'</span>';
          nexthtml = '';
          regi = '';
          for(var j = 0; j < response.course_notification.length; j++){

          for(var i = 0; i< response.course_notification[j].course_data.length; i++){
              
              if(response.course_notification[j].notification_type == 'course_review'){
                  regi = 'Successfully complete the course review..';
              
              url = '<?=base_url('course/course_review/')?>'+ pusher_data['notification_id'] ;
            
              nexthtml += '<li><a href="'+url+'"><span class="image"><img src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" alt="Profile Image" /></span>';
              nexthtml += ' <span> <span>'+response.course_notification[j].course_data[i].title+' </span><span class="time">'+time_ago(response.course_notification[j].created_at)+'</span> </span>';
              nexthtml += '<span class="message">'+regi+'</span></a></li>';
           }
          
          
          
            
           $("#course_notify_result").html(html);
           $("#menu2").html(nexthtml);
           }
          }
          }
       });
      });
      


   //    Time format

          
           function time_ago(time) {

            switch (typeof time) {
              case 'number':
                break;
              case 'string':
                time = +new Date(time);
                break;
              case 'object':
                if (time.constructor === Date) time = time.getTime();
                break;
              default:
                time = +new Date();
            }
            var time_formats = [
              [60, 'seconds', 1], // 60
              [120, '1 minute ago', '1 minute ago'], // 60*2
              [3600, 'minutes', 60], // 60*60, 60
              [7200, '1 hour ago', '1 hour ago'], // 60*60*2
              [86400, 'hours', 3600], // 60*60*24, 60*60
              [172800, 'Yesterday', 'Tomorrow'], // 60*60*24*2
              [604800, 'days', 86400], // 60*60*24*7, 60*60*24
              [1209600, 'Last week', 'Next week'], // 60*60*24*7*4*2
              [2419200, 'weeks', 604800], // 60*60*24*7*4, 60*60*24*7
              [4838400, 'Last month', 'Next month'], // 60*60*24*7*4*2
              [29030400, 'months', 2419200], // 60*60*24*7*4*12, 60*60*24*7*4
              [58060800, 'Last year', 'Next year'], // 60*60*24*7*4*12*2
              [2903040000, 'years', 29030400], // 60*60*24*7*4*12*100, 60*60*24*7*4*12
              [5806080000, 'Last century', 'Next century'], // 60*60*24*7*4*12*100*2
              [58060800000, 'centuries', 2903040000] // 60*60*24*7*4*12*100*20, 60*60*24*7*4*12*100
            ];
            var seconds = (+new Date() - time) / 1000,
              token = 'ago',
              list_choice = 1;

            if (seconds == 0) {
              return 'Just now'
            }
            if (seconds < 0) {
              seconds = Math.abs(seconds);
              token = 'ago';
              list_choice = 2;
            }
            var i = 0,
              format;
            while (format = time_formats[i++])
              if (seconds < format[0]) {
                if (typeof format[2] == 'string')
                  return format[list_choice];
                else
                  return Math.floor(seconds / format[2]) + ' ' + format[1] + ' ' + token;
              }
            return time;
      }


  

    </script>
   