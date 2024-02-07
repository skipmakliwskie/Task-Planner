<?php

require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT c.*, concat(u.firstname, coalesce(concat(' ',u.middlename), ''), ' ', u.lastname) as uname from `category_list` c inner join `users` u on c.user_id = u.id where c.id = '{$_GET['id']}' ");
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
        <dt class="text-muted">Name</dt>
        <dd class="pl-4"><?= isset($name) ? $name : "" ?></dd>
        <dt class="text-muted">Status</dt>
        <dd class="pl-4">
            <?php if($status == 1): ?>
                <span class="badge badge-navy bg-gradient-navy px-3 rounded-pill">Active</span>
            <?php else: ?>
                <span class="badge badge-light bg-gradient-light border text-dark px-3 rounded-pill">Inactive</span>
            <?php endif; ?>
        </dd>
    </dl>
    <div class="clear-fix my-3"></div>
    <div class="text-right">
        <button class="btn btn-sm btn-dark bg-gradient-dark btn-flat" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
</div>