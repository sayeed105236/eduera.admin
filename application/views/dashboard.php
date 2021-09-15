<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      	<div class="dashboard_graph">

            <div class="row x_title">
              	<div class="col-md-6">
                	<h3>General report</h3>
              	</div>
              	<div class="col-md-6">
                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                      	<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                      	<span>January 01, 2020 - January 31, 2020</span> <b class="caret"></b>
                    </div>
              	</div>
            </div>

            <div class="clearfix"></div>
      	</div>
    </div>

</div>
<br />


<style>

 
.ActiveUsers{background:greenyellow;border:1px solid #d4d2d0;border-radius:4px;font-weight:300;padding:.5em 1.5em;white-space:nowrap}.ActiveUsers-value{display:inline-block;font-weight:600;margin-right:-.25em}.ActiveUsers.is-increasing{-webkit-animation:a 3s;animation:a 3s}.ActiveUsers.is-decreasing{-webkit-animation:b 3s;animation:b 3s}@-webkit-keyframes a{10%{background-color:#ebffeb;border-color:rgba(0,128,0,.5);color:green}}@keyframes a{10%{background-color:#ebffeb;border-color:rgba(0,128,0,.5);color:green}}@-webkit-keyframes b{10%{background-color:#ffebeb;border-color:rgba(255,0,0,.5);color:red}}@keyframes b{10%{background-color:#ffebeb;border-color:rgba(255,0,0,.5);color:red}}.Chartjs{font-size:.85em}.Chartjs-figure{height:250px}.Chartjs-legend{list-style:none;margin:0;padding:1em 0 0;text-align:center}.Chartjs-legend>li{display:inline-block;padding:.25em .5em}.Chartjs-legend>li>i{display:inline-block;height:1em;margin-right:.5em;vertical-align:-.1em;width:1em}@media (min-width:570px){.Chartjs-figure{margin-right:1.5em}}

</style>

<div class="row">


<header>
  <div id="embed-api-auth-container"></div>
  <br>
  <div style="display: none" id="view-selector-container"></div>
  <div id="view-name"></div>
  <br>
  <div class="row">
    <div class="col-md-2">
        <div id="active-users-container"></div>
    </div>
</div>
<br>
</header>
    <div class="col-md-6">
        <div class="Chartjs">
          <h3>This Week vs Last Week (by sessions)</h3>
        
          <figure class="Chartjs-figure" id="chart-1-container"></figure>
          <ol class="Chartjs-legend" id="legend-1-container"></ol>
        </div>
</div>
    <div class="col-md-6">
        <div class="Chartjs">
          <h3>This Year vs Last Year (by users)</h3>
          <figure class="Chartjs-figure" id="chart-2-container"></figure>
          <ol class="Chartjs-legend" id="legend-2-container"></ol>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="Chartjs">
          <h3>Top Browsers (by pageview)</h3>
          <figure class="Chartjs-figure" id="chart-3-container"></figure>
          <ol class="Chartjs-legend" id="legend-3-container"></ol>
        </div>
    </div>
    <div class="col-md-6">
        <div class="Chartjs">
          <h3>Top Countries (by sessions)</h3>
          <figure class="Chartjs-figure" id="chart-4-container"></figure>
          <ol class="Chartjs-legend" id="legend-4-container"></ol>
        </div>
    </div>
</div>


<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>

    // Enable pusher logging - don't include this in production
   
  </script>
<!-- 

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
          html  = '<i class="fa fa-envelope-o"></i><span class="badge bg-green " id="div1">'+response.notification.length+'</span>';
          nexthtml = '';
          for(var j = 0; j < response.notification.length; j++){

          for(var i = 0; i< response.notification[j].user_data.length; i++){
              regi = '';
              if(response.notification[j].notification_type == 'registration'){
                  regi = 'Successfully complete the registration..';
              }
              url = '<?=base_url('users/')?>'+ response.notification[j].user_data[i].id + '/info?notify_id='+response.notification[j].notify_id ;
            
              nexthtml += '<li><a href="'+url+'"><span class="image"><img src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" alt="Profile Image" /></span>';
              nexthtml += ' <span> <span>'+response.notification[j].user_data[i].first_name+'  ' + response.notification[j].user_data[i].last_name+' </span><span class="time">'+time_ago(response.notification[j].user_data[i].created_at)+'</span> </span>';
              nexthtml += '<span class="message">'+regi+'</span></a></li>';
           }
          
          
          
            
           $("#notify_result").html(html);
           $("#menu1").html(nexthtml);
           
          }
          }
       });
      });
      
      
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
   
 -->