<?php
include_once 'functions.php';

if( isset( $_POST['lg_username'] ) ) {
	$frm_user = sanitize_string( $_POST['lg_username'] );
	$frm_pass = sanitize_string( $_POST['lg_password'] );

	$status = signin_user( $frm_user, $frm_pass );

	if( $status ) {
		header("Location: " . site_url() . '/chatroom.php');
	} else {
		$error = 'Invalid username or password.';
	}
}

include_once 'header.php';

//echo '<pre>'; print_r($_SESSION); echo '</pre>';
//unset( $_SESSION['current_user'] ); unset( $_SESSION['logged_in'] );

?>

<link rel="stylesheet" href="css/login.css">

<!-- LOGIN FORM -->
<div class="text-center" style="padding:50px 0">
	<div class="logo">login</div>
	<!-- Main Form -->
	<div class="login-form-1">

		<?php if( isset( $error ) ) : ?>
			<p class="text-danger"><?php echo $error; ?></p>
		<?php endif; ?>

		<form id="login-form" class="text-left" method="post" action="">
			<div class="login-form-main-message"></div>
			<div class="main-login-form">
				<div class="login-group">
					<div class="form-group">
						<label for="lg_username" class="sr-only">Username</label>
						<input type="text" class="form-control" id="lg_username" name="lg_username" placeholder="username">
					</div>
					<div class="form-group">
						<label for="lg_password" class="sr-only">Password</label>
						<input type="password" class="form-control" id="lg_password" name="lg_password" placeholder="password">
					</div>
					<div class="form-group login-group-checkbox">
						<input type="checkbox" id="lg_remember" name="lg_remember">
						<label for="lg_remember">remember</label>
					</div>
				</div>
				<button type="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>
			</div>
			<div class="etc-login-form">
				<p>forgot your password? <a href="#">click here</a></p>
				<p>new user? <a href="#">create new account</a></p>
			</div>
		</form>
	</div>
	<!-- end:Main Form -->
</div>

<?php
include_once 'footer.php';