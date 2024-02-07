<?php

require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT *,COALESCE((SELECT `name` from category_list where id = schedule_list.category_id),'N/A') as `category`, COALESCE((SELECT concat(firstname, coalesce(concat(' ',middlename), ''), ' ', lastname) from `users` where id = schedule_list.user_id),'N/A') as `uname` FROM `schedule_list`  where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>
<div class="container-fluid">
	<dl>
        <?php if($_settings->userdata('type') == 1): ?>
        <dt class="text-muted">User</dt>
        <dd class="pl-4"><?= isset($uname) ? $uname : "" ?></dd>
        <?php endif; ?>
        <dt class="text-muted">Category</dt>
        <dd class="pl-4"><?= isset($category) ? $category : "" ?></dd>
        <dt class="text-muted">Schedule Start</dt>
        <dd class="pl-4"><?= isset($schedule_from) ? date("F d, Y h:i A", strtotime($schedule_from)) : "" ?></dd>
        <dt class="text-muted">Schedule End</dt>
        <dd class="pl-4"><?= isset($schedule_to) ? date("F d, Y h:i A", strtotime($schedule_to)) : "" ?></dd>
        <dt class="text-muted">Title</dt>
        <dd class="pl-4"><?= isset($title) ? $title : "" ?></dd>
        <dt class="text-muted">Description</dt>
        <dd class="pl-4"><?= isset($description) ? $description : "" ?></dd>
    </dl>
    <div class="clear-fix my-3"></div>
    <div class="text-right">
        <button class="btn btn-sm btn-primary bg-gradient-primary btn-flat edit-schedule" type="button" ><i class="fa fa-edit"></i> Edit</button>
        <button class="btn btn-sm btn-danger bg-gradient-danger btn-flat delete-schedule" type="button" ><i class="fa fa-trash"></i> Delete</button>
        <button class="btn btn-sm btn-dark bg-gradient-dark btn-flat" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
</div>
<script>
    $(function(){
        $('#uni_modal').on('shown.bs.modal', function(){
            $('.edit-schedule').click(function(){
                uni_modal("<i class='fa fa-edit'></i> Edit Schedule Details", "schedules/manage_schedule.php?id=<?= isset($id) ? $id : '' ?>");
            })
            $('.delete-schedule').click(function(){
                _conf("Are you sure to delete this Scheduled Task?", 'delete_schedule', ['<?= isset($id) ? $id : '' ?>'])
            })
        })
        $('#uni_modal').on('hidden.bs.modal', function(){
            if($('form#schedule-form').length > 0 && $('#schedule-form [name="id"]').val() != ''){
			    uni_modal("<i class='fa fa-calendar-day'></i> Scheduled Task Details", "schedules/view_schedule.php?id=<?= isset($id) ? $id : '' ?>")
            }
        })
        
    })
    function delete_schedule($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_schedule",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>