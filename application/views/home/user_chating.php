

<div id="frame">
  <div id="sidepanel">
    <div id="profile">
      <div class="wrap">
        <img id="profile-img" src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" class="online" alt="" />
        <p><?= $this->session->userdata('name')?></p>
        <i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>
        <div id="status-options">
          <ul>
            <li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>
            <li id="status-away"><span class="status-circle"></span> <p>Away</p></li>
            <li id="status-busy"><span class="status-circle"></span> <p>Busy</p></li>
            <li id="status-offline"><span class="status-circle"></span> <p>Offline</p></li>
          </ul>
        </div>
        <div id="expanded">
          <label for="twitter"><i class="fa fa-facebook fa-fw" aria-hidden="true"></i></label>
          <input name="twitter" type="text" value="mikeross" />
          <label for="twitter"><i class="fa fa-twitter fa-fw" aria-hidden="true"></i></label>
          <input name="twitter" type="text" value="ross81" />
          <label for="twitter"><i class="fa fa-instagram fa-fw" aria-hidden="true"></i></label>
          <input name="twitter" type="text" value="mike.ross" />
        </div>
      </div>
    </div>
    <div id="search">
      <label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
      <input type="text" placeholder="Search contacts..." />
    </div>
    <div id="contacts">
      <ul id="user_chat_list">
        <?php 
        if(isset($user_list)){
            foreach($user_list as $users){
                 
          ?>
          <a href="<?= base_url('home/user_chating/'.$users[0]->id)?>">
            <li class="contact">
            
              <div class="wrap">
                <span class="contact-status online"></span>
                 <?php 
                    if($users[0]->profile_photo_name != '' || $users[0]->profile_photo_name != NULL){
                        $file =  'https://eduera.com.bd/uploads/user_image/' . $users[0]->profile_photo_name;
                ?>
                        <img src="<?= $file; ?>" alt="" >
                    <?php
                    }else{
                    ?>
                        <img src="<?php echo base_url() . 'uploads/user_image/placeholder.png'; ?>" alt="" />
                    <?php } ?>
                <!--<img src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" alt="" />-->
                <div class="meta" style="color: white">
                    <?php if($users[0]->user_id == 0){?>
                  <p class="name">Unknown   
                    <?php if($users[0]->message_count > 0){?>
                  <span class="badge"><?= $users[0]->message_count?></span></p>
                  <?php }?>
                  <!-- <p class="preview">You just got LITT up, Mike.</p> -->
              <?php }else{?>
                        <p class="name"><?= $users[0]->first_name?> <?= $users[0]->last_name?> 
                     <?php if($users[0]->message_count > 0){?>
                      <span class="badge">  <?= $users[0]->message_count?></span>
                    <?php }?>
                    </p>
              <?php }?>
                </div>
              </div>
        
            </li>
            </a>
        <?php
            }
        }
        ?>
     
      </ul>
    </div>
  
  </div>
   <?php if(isset($selected_user)){?>  
   
  <div class="content">
    <div class="contact-profile">
     
      <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />
      <?php if($selected_user[0]->user_id > 0){ ?>
        <p><?=$selected_user[0]->first_name ?> <?= $selected_user[0]->last_name?></p>
       <?php }else {?>
            <p>Unknown</p>
       <?php }?>        
    </div>
    <div class="messages" id="messages">
      <ul id="msg_card_body">
        <?php if(isset($user_messages)){

            foreach($user_messages as $message){
                if($message->chat_messages_status == 0){
            ?>
            <li class="replies">
                <?php 
                    if($selected_user[0]->profile_photo_name != '' || $selected_user[0]->profile_photo_name != NULL){
                ?>
                        <!--<img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />-->
                        <img src="<?php echo base_url() . 'uploads/user_image/' . $selected_user[0]->profile_photo_name; ?>" alt="" >
                    <?php
                    }else{
                    ?>
                        <img src="<?php echo base_url() . 'uploads/user_image/placeholder.png'; ?>" alt="" />
                    <?php } ?>
              <div>
                  <p><?= $message->chat_messages_text?></p><br>
                  <span><?= date('D M Y H:i', strtotime($message->chat_messages_datetime))?></span>
              </div>
            </li>
       
    <?php }else{?>
        <li class="sent">
          <img src="http://emilcarlsson.se/assets/mikeross.png" alt="" />
          <div>
              <p><?= $message->chat_messages_text?></p><br>
              <span><?= date('D M Y H:i', strtotime($message->chat_messages_datetime))?></span>
          </div>
        </li>
    <?php } ?>
     
    <?php } 
        }
        ?>
      </ul>
    </div>
    <form>
    <div class="message-input">
      <div class="wrap">
      <input type="text" id="chat_message_area" class="type_msg" placeholder="Write your message..." />
      <i class="fa fa-paperclip attachment" aria-hidden="true"></i>
      <button type="button" class="send_btn"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
      </div>
    </div>
</form>
  </div>
   <?php }else{?>
  <spna style="margin-top: 0; ">Select a user to continue your chat .</spna>
   <?php } ?>
</div>

<script >
var messageBody = document.querySelector('#messages');
messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;



/*Send message to user*/


$('.send_btn').click(function(){
    message = $(".type_msg").val();
    time = '<?=date('D M Y H:i', strtotime(date('Y-m-d H:i:s'))) ?>';
    if(message == ''){
        alert('Please type message first.')
    }else{
        
         user_id = '<?= isset($selected_user) ? $selected_user[0]->id : ''?>';
        $("#chat_message_area").val('');
        $.ajax({
            url: '<?=base_url('/rest/api/insertUserMessage')?>',
            type: 'POST',
            data: {message:message, user_id: user_id},
            success:function(response){
                // console.log(response);
                
                var html = '<li class="sent"><img src="http://emilcarlsson.se/assets/mikeross.png" alt="" />';
                html += '<div><p>'+message+'</p><br>';
               
                html +='<span class="msg_time">'+time+'</span>';
                html += '</div><li>';

                $("#msg_card_body").append(html);
                var messageBody = document.querySelector('#messages');
                messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;


            }
        })
    }
});



</script>

<script src="https://js.pusher.com/6.0/pusher.min.js"></script>

<script type="text/javascript">
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('ef8c05dc5dc1f393d019', {
      cluster: 'ap2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      // alert(JSON.stringify(data));

//   var aSound = document.createElement('audio');
//     aSound.setAttribute('src', 'https://www.file.server.eduera.com.bd/admin_message.mp3');
//     aSound.play();

    
    
    $.ajax({
          url: '<?=base_url('/rest/api/get_admin_all_unseen_message/')?>'+data.sender_id +'/'+ data.receiver_id,
          type: 'GET',
          datatype: 'json',
          success:function(response){
               json_data = JSON.parse(response);
              
               var html = '<ul id="user_chat_list">';
              for(var i = 0; i < json_data.user_unseen_message.length; i++){
                
                html += ' <a href="<?= base_url('home/user_chating/')?>'+json_data.user_unseen_message[i][0].id+'">';
                html += '<li class="contact">  <div class="wrap"> <span class="contact-status online"></span>';
                
                if(json_data.user_unseen_message[i][0].user_id != ''){
                    if(json_data.user_unseen_message[i][0].profile_photo_name != ''){
                    html += '<img src="<?php echo 'https://eduera.com.bd/uploads/user_image/'?>'+json_data.user_unseen_message[i][0].profile_photo_name+'" alt="" />';
                    }else{
                        html += '<img src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" alt="" />';
                    }
                }else{
                    html += '<img src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" alt="" />';
                }
                
                
                html += ' <div class="meta" style="color: white">';
                if(json_data.user_unseen_message[i][0].user_id == 0){
                    html += '<p class="name">Unknown   <span class="badge">'+json_data.user_unseen_message[i][0].message_count+'</span></p>';
                }else{
                    html += '<p class="name"> '+json_data.user_unseen_message[i][0].first_name +' '+ json_data.user_unseen_message[i][0].last_name +'<span class="badge">'+json_data.user_unseen_message[i][0].message_count+'</span></p>';
                }
                html += '</div> </div> <li> </a>';
              }
              
              html += '</ul>';
                $("#user_chat_list").remove();
              $("#contacts").append(html);
              
              
              
              
               $(".total_unseen_admin_message").text(json_data.total_unseen_messages);
          }
    });



  $.ajax({
            url: '<?=base_url('/rest/api/get_user_chat_messages/')?>'+data.sender_id +'/'+ data.last_chat_message_id,
            type: 'GET',
            datatype: 'json',
            success:function(response){
                responseData = JSON.parse(response);
                user_id = '<?= isset($selected_user) ? $selected_user[0]->id : ''?>';
                // $("#chat_message_area").val('');
               
                if(responseData.user_message[0].sender_id == user_id){
                    var html = '<li class="replies"><img src="http://emilcarlsson.se/assets/mikeross.png" alt="" />';
                     html += '<div><p>'+responseData.user_message[0].chat_messages_text+'</p><br>';
                   
                     html +='<span class="msg_time">'+responseData.user_message[0].chat_messages_datetime+'</span>';
                     html += '</div><li>';
      
                     $("#msg_card_body").append(html);
                     
                     if(user_id != ''){
                          var messageBody = document.querySelector('#messages');
                          messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
                     }
                }
                 
                 

            }
        });
      
      
    });
</script>