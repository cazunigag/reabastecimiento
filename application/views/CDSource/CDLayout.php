<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reabastecimiento | REDEX</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Google Font -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/skins/_all-skins.min.css">
  <!-- Pace style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/pace/pace.min.css">

  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/planoREDEX.css">  
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    
</head>
<body class="hold-transition skin-purple sidebar-mini fixed">
<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo site_url('home/home');?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>R</b>eabastecimiento</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" role="button"><i class="fa_"></i>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
      
          
        </ul>
      </div>
    </nav>
  </header>
 <div class="contentdiv">
	<a href="<?php echo site_url('estanteria')?>"><div id="estanteria" class="estanteria"><center><b>ESTANTERIA</b></center></div></a>
	<div id="palletrackA" class="palletrackA"><center><b>PALLET RACK TIP.A</b></center></div>
	<div id="recepcion" class="recepcion"><center><b>RECEPCION</b></center></div>
	<div id="embarques" class="embarques"><center><b>EMBARQUES</b></center></div>
	<div id="dadomicilio" class="dadomicilio"><center><b>DESPACHO</b></center></div>
	<div id="rackextanchC" class="rackextanchC"><center><b>RACK EXTRA ANCHO TIP.C</b></center></div>
	<div id="rackextanchB" class="rackextanchB"><center><b>RACK EXTRA ANCHO TIP.B</b></center></div>
	<div id="recepcolgados" class="recepcolgados"><center><b>RECEPCION COLGADOS</b></center></div>
</div>

<script src="<?php echo base_url();?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- PACE -->
<script src="<?php echo base_url();?>assets/bower_components/PACE/pace.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url();?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>assets/dist/js/demo.js"></script> 
<style type="text/css">
  a{
    color: black;
  }
 body{
  overflow: auto;
  width: 1700px;
 }
</style>
</body>
</html>