<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link name="favicon" type="image/x-icon" href="<?php echo base_url().'uploads/system/eduera-favicon.png' ?>" rel="shortcut icon" />
    <!-- <meta name="google-signin-client_id" content="1061299978547-kuijj4cpj5ea87rd2bflrau0rpvv2u1l.apps.googleusercontent.com"> -->

    <title>Admin panel | <?= $system_name ?></title>

    <!-- Bootstrap -->
    <link href="<?= base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?= base_url('assets/vendors/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?= base_url('assets/build/css/custom.min.css') ?>" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="<?= base_url('assets/vendors/bootstrap-daterangepicker/daterangepicker.css') ?>" rel="stylesheet">
    
    <?php if($page_name== 'user_chating'){?>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,300' rel='stylesheet' type='text/css'>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>
    <link href="<?= base_url('assets/build/css/chat.css')?>" rel="stylesheet">
    <?php } ?>
    <link href="<?= base_url('assets/vendors/switchery/dist/switchery.min.css') ?>" >
    <link href="<?= base_url('assets/build/css/main.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendors/iCheck/skins/flat/green.css') ?>" rel="stylesheet">
   
    <!-- jQuery -->
    <script src="<?= base_url('assets/vendors/jquery/dist/jquery.min.js') ?>"></script>
    <!-- bootstrap-progressbar -->


  </head>

  <body class="nav-md" id="body">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <?php include 'component/sidebar.php'; ?>
            </div>

        <!-- top navigation -->
        <div class="top_nav">
            <?php include 'component/top_navbar.php'; ?>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <!-- top tiles -->
            <?php include 'component/top_tiles.php'; ?>
            <!-- /top tiles -->
            
            <?php include $page_view . '.php'; ?>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            <a href="">Version - <?= constant('EDUERA_SOFTWARE_VERSION') ?></a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    
    <!-- Bootstrap -->
    <script src="<?= base_url('assets/vendors/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
    <!-- bootstrap-progressbar -->
    <script src="<?= base_url('assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') ?>"></script>

  

    <!-- Custom Theme Scripts -->
    <script src="<?= base_url('assets/build/js/custom.min.js') ?>"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?= base_url('assets/vendors/moment/min/moment.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendors/bootstrap-daterangepicker/daterangepicker.js') ?>"></script>
    <script src="<?= base_url('assets/vendors/iCheck/icheck.min.js') ?>"></script>
    
    <script src="https://js.pusher.com/6.0/pusher.min.js"></script>

   


<script>
    Pusher.logToConsole = true;

    var pusher = new Pusher('ef8c05dc5dc1f393d019', {
      cluster: 'ap2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        
        var user_id ='<?= ($this->session->userdata('user_id')) ? $this->session->userdata('user_id'): '' ?>';
   
        if(user_id == data.receiver_id){
            var aSound = document.createElement('audio');
            aSound.setAttribute('src', 'https://www.file.server.eduera.com.bd/admin_message.mp3');
            aSound.play();
        }
          $.ajax({
          url: '<?=base_url('/rest/api/get_admin_all_unseen_message/')?>'+data.sender_id +'/'+ data.receiver_id,
          type: 'GET',
          datatype: 'json',
          success:function(response){
            data = JSON.parse(response);
            $(".total_unseen_admin_message").text(data['total_unseen_messages']);
            
            

          }
      })
        
    });
</script>

  <script src="https://apis.google.com/js/platform.js" async defer></script>

<script>
(function(w,d,s,g,js,fs){
  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
  js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
  js.src='https://apis.google.com/js/platform.js';
  fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
}(window,document,'script'));
</script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

<script src="<?= base_url('assets/vendors/ga-dev-tools/src/javascript/embed-api/components/active-users.js') ?>"></script>
<script src="<?= base_url('assets/vendors/ga-dev-tools/src/javascript/embed-api/components/date-range-selector.js') ?>"></script>
 <script src="<?= base_url('assets/vendors/ga-dev-tools/src/javascript/embed-api/components/viewSelector2.js') ?>"></script> 
 <!-- <script src="<?= base_url('assets/vendors/ga-dev-tools/src/css/chartjs-visualization-custom.css') ?>"></script>  -->
<script>

gapi.analytics.ready(function() {

  gapi.analytics.auth.authorize({
    container: 'embed-api-auth-container',
    clientid: '1061299978547-kuijj4cpj5ea87rd2bflrau0rpvv2u1l.apps.googleusercontent.com',
  });
  var query = {
              ids: 'ga:212992644',
              metrics: 'ga:sessions,ga:pageviews,ga:avgTimeOnPage,ga:bounceRate',
              dimensions: 'ga:date,ga:pagePath',
              filters: 'ga:pagePath=@page1,ga:pagePath=@page2' //<-- page name to search for
          }
          var report = new gapi.analytics.report.Data({ query });
             report.on('success', function(response) {
             });

             report.execute();

 var activeUsers = new gapi.analytics.ext.ActiveUsers({
   ids: 'ga:212992644',
   container: 'active-users-container',
   pollingInterval: 5
 });

  activeUsers.once('success', function() {
    var element = this.container.firstChild;
    var timeout;

    this.on('change', function(data) {
      var element = this.container.firstChild;
      var animationClass = data.delta > 0 ? 'is-increasing' : 'is-decreasing';
      element.className += (' ' + animationClass);

      clearTimeout(timeout);
      timeout = setTimeout(function() {
        element.className =
        element.className.replace(/ is-(increasing|decreasing)/g, '');
      }, 3000);
    });
  });



   var viewSelector = new gapi.analytics.ext.ViewSelector2({
    container: 'view-selector-container',
  })
  .execute();

  /**
   * Update the activeUsers component, the Chartjs charts, and the dashboard
   * title whenever the user changes the view.
   */
  viewSelector.on('viewChange', function(data) {
    var title = document.getElementById('view-name');
    title.textContent = data.property.name + ' (' + data.view.name + ')';

    // Start tracking active users for this view.
    activeUsers.set(data).execute();

    // Render all the of charts for this view.
    renderWeekOverWeekChart(data.ids);
    renderYearOverYearChart(data.ids);
    renderTopBrowsersChart(data.ids);
    renderTopCountriesChart(data.ids);
  });


  


  function renderWeekOverWeekChart(ids) {

    // Adjust `now` to experiment with different days, for testing only...
    var now = moment(); // .subtract(3, 'day');

    var thisWeek = query({
      'ids': ids,
      'dimensions': 'ga:date,ga:nthDay',
      'metrics': 'ga:sessions',
      'start-date': moment(now).subtract(1, 'day').day(0).format('YYYY-MM-DD'),
      'end-date': moment(now).format('YYYY-MM-DD')
    });

    var lastWeek = query({
      'ids': ids,
      'dimensions': 'ga:date,ga:nthDay',
      'metrics': 'ga:sessions',
      'start-date': moment(now).subtract(1, 'day').day(0).subtract(1, 'week')
          .format('YYYY-MM-DD'),
      'end-date': moment(now).subtract(1, 'day').day(6).subtract(1, 'week')
          .format('YYYY-MM-DD')
    });

    Promise.all([thisWeek, lastWeek]).then(function(results) {

      var data1 = results[0].rows.map(function(row) { return +row[2]; });
      var data2 = results[1].rows.map(function(row) { return +row[2]; });
      var labels = results[1].rows.map(function(row) { return +row[0]; });

      labels = labels.map(function(label) {
        return moment(label, 'YYYYMMDD').format('ddd');
      });

      var data = {
        labels : labels,
        datasets : [
          {
            label: 'Last Week',
            fillColor : "rgba(250,92,108,0.5)",
            strokeColor : "rgba(250,92,108,1)",
            pointColor : "rgba(250,92,108,1)",
            pointStrokeColor : "#FA5C6C",
            data : data2
          },
          {
            label: 'This Week',
            fillColor : "rgba(92,250,171,0.5)",
            strokeColor : "rgba(92,250,171,1)",
            pointColor : "rgba(92,250,171,1)",
            pointStrokeColor : "#5CFAAB",
            data : data1
          }
        ]
      };

      new Chart(makeCanvas('chart-1-container')).Line(data);
      generateLegend('legend-1-container', data.datasets);
    });
  }


  function renderYearOverYearChart(ids) {

    // Adjust `now` to experiment with different days, for testing only...
    var now = moment(); // .subtract(3, 'day');

    var thisYear = query({
      'ids': ids,
      'dimensions': 'ga:month,ga:nthMonth',
      'metrics': 'ga:users',
      'start-date': moment(now).date(1).month(0).format('YYYY-MM-DD'),
      'end-date': moment(now).format('YYYY-MM-DD')
    });

    var lastYear = query({
      'ids': ids,
      'dimensions': 'ga:month,ga:nthMonth',
      'metrics': 'ga:users',
      'start-date': moment(now).subtract(1, 'year').date(1).month(0)
          .format('YYYY-MM-DD'),
      'end-date': moment(now).date(1).month(0).subtract(1, 'day')
          .format('YYYY-MM-DD')
    });

    Promise.all([thisYear, lastYear]).then(function(results) {
      var data1 = results[0].rows.map(function(row) { return +row[2]; });
      var data2 = results[1].rows.map(function(row) { return +row[2]; });
      var labels = ['Jan','Feb','Mar','Apr','May','Jun',
                'Jul','Aug','Sep','Oct','Nov','Dec'];

      for (var i = 0, len = labels.length; i < len; i++) {
        if (data1[i] === undefined) data1[i] = null;
        if (data2[i] === undefined) data2[i] = null;
      }

      var data = {
        labels : labels,
        datasets : [
          {
            label: 'Last Year',
            fillColor : "rgba(141,141,141,0.5)",
            strokeColor : "rgba(141,141,141,1)",
            data : data2
          },
          {
            label: 'This Year',
            fillColor : "rgba(79,240,181,0.5)",
            strokeColor : "rgba(79,240,181,1)",
            data : data1
          }
        ]
      };

      new Chart(makeCanvas('chart-2-container')).Bar(data);
      generateLegend('legend-2-container', data.datasets);
    })
    .catch(function(err) {
      console.error(err.stack);
    })
  }


  function renderTopBrowsersChart(ids) {

    query({
      'ids': ids,
      'dimensions': 'ga:browser',
      'metrics': 'ga:pageviews',
      'sort': '-ga:pageviews',
      'start-date': '14daysAgo',
      'end-date': 'yesterday',
      'max-results': 5
    })
    .then(function(response) {

      var data = [];
      var colors = ['#4D5360','#949FB1','#D4CCC5','#E2EAE9','#F7464A'];

      response.rows.forEach(function(row, i) {
        data.push({ value: +row[1], color: colors[i], label: row[0] });
      });

      new Chart(makeCanvas('chart-3-container')).Doughnut(data);
      generateLegend('legend-3-container', data);
    });
  }


  function renderTopCountriesChart(ids) {
    query({
      'ids': ids,
      'dimensions': 'ga:country',
      'metrics': 'ga:sessions',
      'sort': '-ga:sessions',
      'start-date': '14daysAgo',
      'end-date': 'yesterday',
      'max-results': 5
    })
    .then(function(response) {

      var data = [];
      var colors = ['#4D5360','#949FB1','#D4CCC5','#E2EAE9','#F7464A'];

      response.rows.forEach(function(row, i) {
        data.push({
          label: row[0],
          value: +row[1],
          color: colors[i]
        });
      });

      new Chart(makeCanvas('chart-4-container')).Doughnut(data);
      generateLegend('legend-4-container', data);
    });
  }

  function query(params) {
    return new Promise(function(resolve, reject) {
      var data = new gapi.analytics.report.Data({query: params});
      data.once('success', function(response) { resolve(response); })
          .once('error', function(response) { reject(response); })
          .execute();
    });
  }

  function makeCanvas(id) {
    var container = document.getElementById(id);
    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');

    container.innerHTML = '';
    canvas.width = container.offsetWidth;
    canvas.height = container.offsetHeight;
    container.appendChild(canvas);

    return ctx;
  }

  function generateLegend(id, items) {
    var legend = document.getElementById(id);
    legend.innerHTML = items.map(function(item) {
      var color = item.color || item.fillColor;
      var label = item.label;
      return '<li><i style="background:' + color + '"></i>' + label + '</li>';
    }).join('');
  }

  Chart.defaults.global.animationSteps = 60;
  Chart.defaults.global.animationEasing = 'easeInOutQuart';
  Chart.defaults.global.responsive = true;
  Chart.defaults.global.maintainAspectRatio = false;

});
</script>

  </body>
</html>
