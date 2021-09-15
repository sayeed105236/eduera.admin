<div class="row tile_count">
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total Users</span>
        <div class="count"><?=$total_users?></div>
        <span class="count_bottom"><i class="green">4% </i> Since last Week</span>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      	<span class="count_top"><i class="fa fa-clock-o"></i> Total sell</span>
      	<div class="count"><?=$total_enrollment?></div>
      	<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      	<span class="count_top"><i class="fa fa-user"></i> Total courses</span>
      	<div class="count green"><?=$total_courses?></div>
      	<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">

        <span class="count_top"><i class="fa fa-user"></i> Today Total sell</span>
        <div class="count green"><?=($total_day_amount->amount) > 0 ? $total_day_amount->amount : 0?></div>
        <span class="count_bottom">Total <i class="green"><i class="fa fa-sort-asc"></i><?=($total_day_amount->total_times) > 0 ? $total_day_amount->total_times : 0?> </i> times</span>
    </div>
    <!-- <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      	<span class="count_top"><i class="fa fa-user"></i> Total trainer</span>
      	<div class="count">4,567</div>
      	<span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      	<span class="count_top"><i class="fa fa-user"></i> Total Collections</span>
      	<div class="count">2,315</div>
      	<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      	<span class="count_top"><i class="fa fa-user"></i> Total Connections</span>
      	<div class="count">7,325</div>
      	<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
    </div> -->
</div>