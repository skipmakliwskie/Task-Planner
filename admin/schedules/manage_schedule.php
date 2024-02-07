<?php

require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `schedule_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
	<form action="" id="schedule-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="category_id" class="control-label">Category</label>
			<select name="category_id" id="category_id" class="form-control form-control-sm rounded-0" required>
				<option value="" disabled <?= !isset($category_id) ? 'selected' : "" ?>></option>
				<?php 
				$where = "";
				if($_settings->userdata('type') != 1){
					$where = " and user_id = '{$_settings->userdata('id')}' ";
				}
				$category_qry = $conn->query("SELECT *,(SELECT concat(firstname, coalesce(concat(' ', middlename), ''), ' ', lastname) FROM `users` where id = category_list.id ) as uname FROM `category_list` where `status` = 1 and delete_flag = 0 {$where} ".(isset($category_id) && $category_id > 0? " or id = '{$category_id}'" : "")." order by `name` asc");
				while($row = $category_qry->fetch_assoc()):
				?>
				<?php if($_settings->userdata('type') == 1): ?>
				<option value="<?= $row['id'] ?>" <?= isset($dorm_id) && $dorm_id == $row['id'] ? "selected" : '' ?>><?= $row['uname']. ' - ' . $row['name'] ?></option>
				<?php else: ?>
				<option value="<?= $row['id'] ?>" <?= isset($dorm_id) && $dorm_id == $row['id'] ? "selected" : '' ?>><?= $row['name'] ?></option>
				<?php endif; ?>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="title" class="control-label">Task Title</label>
			<input type="text" name="title" id="title" class="form-control form-control-sm rounded-0" value="<?php echo isset($title) ? $title : ''; ?>"  required/>
		</div>
		<div class="form-group">
			<label for="description" class="control-label">Description</label>
			<textarea type="4" name="description" id="description" class="form-control form-control-sm rounded-0" required><?php echo isset($description) ? $description : ''; ?></textarea>
		</div>
		<div class="form-group">
			<label for="schedule_from" class="control-label">Schedule Start</label>
			<input type="datetime-local" min="<?= date("Y-m-d\TH:i") ?>" name="schedule_from" id="schedule_from" class="form-control form-control-sm rounded-0" value="<?php echo isset($schedule_from) ? date("Y-m-d\TH:i", strtotime($schedule_from)) : 0; ?>"  required/>
		</div>
		<div class="form-group">
			<label for="schedule_to" class="control-label">Schedule End</label>
			<input type="datetime-local" min="<?= date("Y-m-d\TH:i") ?>" name="schedule_to" id="schedule_to" class="form-control form-control-sm rounded-0" value="<?php echo isset($schedule_to) ? date("Y-m-d\TH:i", strtotime($schedule_to)) : 0; ?>"  required/>
		</div>
	</form>
</div>
<script>
	$(document).ready(function(){
		$('#uni_modal').on('shown.modal.bs', function(){
			$('#category_id').select2({
				placeholder:'Please Select Category Here',
				width:"100%",
				containerCssClass:"form-control form-control-sm rounded-0"
			})
		})
		$('#schedule-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_schedule",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.reload()
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body, .modal").scrollTop(0)
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})

	})
</script>