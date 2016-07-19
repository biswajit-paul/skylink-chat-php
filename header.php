<?php
	require_once 'functions.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Chat room</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo date('his'); ?>">
		<script type="text/javascript">
			var ajaxurl = '<?php echo site_url() . '/ajax.php'; ?>';
		</script>
  </head>
  <body>
    <div class="container bootstrap snippet">
