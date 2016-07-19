<?php
include_once 'functions.php';

unset( $_SESSION['current_user'] ); unset( $_SESSION['logged_in'] );

header("Location: " . site_url() . '/login.php' );
