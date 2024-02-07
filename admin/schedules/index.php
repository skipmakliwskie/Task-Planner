<link rel="stylesheet" href="<?= base_url ?>plugins/fullcalendar/main.css">
<script src="<?= base_url ?>plugins/fullcalendar/main.js"></script>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<style>
	.cursor-pointer{
		cursor: pointer!important;
	}
	.fc-toolbar-chunk button{
		text-transform: capitalize !important;
	}
</style>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title">List of Rooms</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<?php 
			$schedule_arr = [];
			$where = "";
			if($_settings->userdata('type') != 1){
				$where = " where user_id = '{$_settings->userdata('id')}' ";
			}
			$schedule_qry = $conn->query("SELECT * from `schedule_list` {$where}");
			while($row = $schedule_qry->fetch_assoc()){
				$schedule_arr[] = $row;
			}
			?>
			<div id="calendar"></div>
		</div>
	</div>
</div>
<script>
	var scheds = $.parseJSON('<?= isset($schedule_arr) ? json_encode($schedule_arr) : '{}' ?>')
	var events = []
	$(document).ready(function(){
		$('#create_new').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Add New Task Schedule", "schedules/manage_schedule.php")
		})
		if(Object.keys(scheds).length > 0){
			Object.keys(scheds).map(k=>{
				var data = scheds[k]
				var event_item = {
					id			   : data.id,
					title          : data.title,
					start          : data.schedule_from,
					end          : data.schedule_to,
					backgroundColor: '#3788d8',
					borderColor    : '#3788d8',
					allDay         : data.is_whole == 1,
					className      :'cursor-pointer'
				}
				events.push(event_item)
			})
		}
		var date = new Date()
    	var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
		var Calendar = FullCalendar.Calendar;
		var calendar = new Calendar(document.querySelector('#calendar'), {
		headerToolbar: {
			left  : 'prev,next today',
			center: 'title',
			right : 'dayGridMonth,timeGridWeek,timeGridDay'
		},
		themeSystem: 'bootstrap',
		//Random default events
		events: events,
		editable  : false,
		droppable : false, // this allows things to be dropped onto the calendar !!!
		drop      : false,
		eventClick:function(info){
			var id= info.event.id
			uni_modal("<i class='fa fa-calendar-day'></i> Scheduled Task Details", "schedules/view_schedule.php?id=" + id)
		}
		});

		calendar.render();
	})
</script>