<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="colorlib.com">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Sign Up Form</title>

	<!-- Font Icon -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/mainpage/'; ?>fonts/material-icon/css/material-design-iconic-font.min.css">

	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/mainpage/'; ?>bootstrap1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/mainpage/'; ?>bootstrap1/dist/css/bootstrap.css">

	<!-- Main css -->
	<link rel="stylesheet" href="<?php echo base_url() . 'assets/mainpage/'; ?>css/style.css">
	<script type="text/javascript">
		function base_url() {
			return "<?php echo base_url() . 'index.php/'; ?>";
		}

		function site_url() {
			return "<?php echo site_url('/'); ?>";
		}

		function ConfirmDelete(url) {
			var agree = confirm("Are you sure you want to delete this item?");
			if (agree)
				return location.href = url;
			else
				return false;
		};
	</script>
</head>

<body>

	<div class="main">
