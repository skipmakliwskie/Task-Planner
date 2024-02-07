<nav class="navbar navbar-expand-lg navbar-dark bg-gradient-navy">
            <div class="container px-4 px-lg-5 ">
                <button class="navbar-toggler btn btn-sm" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <a class="navbar-brand" href="./">
                <img src="<?php echo validate_image($_settings->info('logo')) ?>" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
                <?php echo $_settings->info('short_name') ?>
                </a>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link text-white" aria-current="page" href="./">Home</a></li>
                        <li class="nav-item"><a class="nav-link text-white" aria-current="page" href="./?page=search_pass">Verify Pass</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="./?page=about">About</a></li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <a class="font-weight-bolder text-light mx-2 text-decoration-none" href="./admin">Admin Login</a> | 
                        <a class="font-weight-bolder text-light mx-2 text-decoration-none" href="./user">User Login</a>
                    </div>
                </div>
            </div>
        </nav>
<script>
  $(function(){
    $('#login-btn').click(function(){
      uni_modal("","login.php")
    })
    $('#navbarResponsive').on('show.bs.collapse', function () {
        $('#mainNav').addClass('navbar-shrink')
    })
    $('#navbarResponsive').on('hidden.bs.collapse', function () {
        if($('body').offset.top == 0)
          $('#mainNav').removeClass('navbar-shrink')
    })
  })

</script>