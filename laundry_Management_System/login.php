<?php
// session_start();
include('./admin_class.php');
$crud = new Action();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
 
        header("Location: index.p	hp?page=home"); 
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Admin | Washwiz</title>
    <?php include('./header.php'); ?>
    <?php include('../db.php'); ?>
    <style>
        body {
            width: 100%;
            height: calc(100%);
            /* background: #007bff; */
        }

        main#main {
            width: 100%;
            height: calc(100%);
            background: white;
        }

        #login-right {
            position: absolute;
            right: 0;
            width: 40%;
            height: calc(100%);
            background:  #72A0C1;
            display: flex;
            align-items: center;
        }

        #login-left {
            position: absolute;
            left: 0;
            width: 60%;
            height: calc(100%);
            background: #59b6ec61;
            display: flex;
            align-items: center;
        }

        #login-right .card {
            background: #F0F8FF;
			margin: auto
        }

        .logo-container {
            margin: auto;
            width: 600px; 
            height: 600px; 
            background: none;
            border-radius: 10px;
            overflow: hidden;
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
        }
    </style>


<body>
    <main id="main" class=" bg-dark">
	<div id="login-left">
            <div class="logo-container">
                <img src="./assets/img/wa.png" alt="Logo"> 
            </div>
        </div>
        <div id="login-right">
            <div class="card col-md-8">
                <div class="card-body">
                    <form id="login-form" action="" method="POST">
                        <?php if(isset($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
						<div class="form-group text-center">
							<label for="loginlabel" class="control-label" style="font-size: 2rem; font-weight: bold; display: block;">Login</label>
						</div>
                        <div class="form-group">
                            <label for="username" class="control-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <center><button type="submit" class="btn-sm btn-block btn-wave col-md-4 btn-primary">Login</button></center>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

    <script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>
</body>

</html>