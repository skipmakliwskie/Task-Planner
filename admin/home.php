<h1>Welcome, <?php echo $_settings->userdata('username') ?>!</h1>
<hr>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-gradient-secondary elevation-1"><i class="fas fa-th-list"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Categories</span>
          <span class="info-box-number text-right">
            <?php 
              $category = $conn->query("SELECT * FROM category_list where `delete_flag` = 0 and `status` = 1". ($_settings->userdata('type') != 1 ? " and user_id = '{$_settings->userdata('id')}'" : ""))->num_rows;
              echo format_num($category);
            ?>
            <?php ?>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fa-calendar-day"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Today's Scheduled Tasks</span>
          <span class="info-box-number text-right">
            <?php 
              $schedule = $conn->query("SELECT * FROM schedule_list where '".date('Y-m-d')."' BETWEEN date(schedule_from) and date(schedule_to) ". ($_settings->userdata('type') != 1 ? " and user_id = '{$_settings->userdata('id')}'" : ""))->num_rows;
              echo format_num($schedule);
            ?>
            <?php ?>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-gradient-warning elevation-1"><i class="fas fa-calendar"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Upcoming Scheduled Tasks</span>
          <span class="info-box-number text-right">
            <?php 
              $schedule = $conn->query("SELECT * FROM schedule_list where unix_timestamp(date(schedule_from)) > '".strtotime(date('Y-m-d'))."' ". ($_settings->userdata('type') != 1 ? " and user_id = '{$_settings->userdata('id')}'" : ""))->num_rows;
              echo format_num($schedule);
            ?>
            <?php ?>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    
</div>
