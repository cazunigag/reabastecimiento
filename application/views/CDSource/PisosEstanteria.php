<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reabastecimiento | Estanteria</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
  
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
      
</head>
<body class="hold-transition skin-purple sidebar-mini fixed"> 
<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo site_url('home');?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>RDX</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>REDEX</b></span>
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
	<center>
    <div class="containerdiv">
   <a href="<?php echo site_url('piso/5')?>">
    <table id="tercerpiso">
     <tr>
        <?php for($i=0; $i<=10; $i++){?>
          <td width="60" height="">
           <img src="<?php echo base_url();?>assets/img/estante.png" height="100" width="40"/>
          </td>
        <?php }?>
      </tr>  
      <tr style="background-image: '<?php echo base_url();?>assets/img/plataforma.jpg'">
        <?php if($i != 0){ ?>
         <td  colspan="11" style="text-align: center; " ><b><h3>QUINTO PISO (SENSIBLES)</h3></b></td>
        <?php }?>
      </tr>
    </table>
  </a>
    <a href="<?php echo site_url('piso/4')?>">
    <table id="tercerpiso">
     <tr>
        <?php for($i=0; $i<=10; $i++){?>
          <td width="60" height="">
           <img src="<?php echo base_url();?>assets/img/estante.png" height="100" width="40"/>
          </td>
        <?php }?>
      </tr>  
      <tr style="background-image: '<?php echo base_url();?>assets/img/plataforma.jpg'">
        <?php if($i != 0){ ?>
         <td  colspan="11" style="text-align: center; " ><b><h3>CUARTO PISO (COLGADOS)</h3></b></td>
        <?php }?>
      </tr>
    </table>
  </a>
    <a href="<?php echo site_url('piso/3')?>">
    <table id="tercerpiso">
     <tr>
        <?php for($i=0; $i<=10; $i++){?>
          <td width="60" height="">
           <img src="<?php echo base_url();?>assets/img/estante.png" height="100" width="40"/>
          </td>
        <?php }?>
      </tr>  
      <tr style="background-image: '<?php echo base_url();?>assets/img/plataforma.jpg'">
        <?php if($i != 0){ ?>
         <td  colspan="11" style="text-align: center; " ><b><h3>TERCER PISO (SHELVING)</h3></b></td>
        <?php }?>
      </tr>
    </table>
  </a>
   <a href="<?php echo site_url('piso/2')?>">
  <table id="primerpiso">
     <tr>
        <?php for($i=0; $i<=10; $i++){?>
          <td width="60" height="">
           <img src="<?php echo base_url();?>assets/img/estante.png" height="100" width="40"/>
          </td>
        <?php }?>
      </tr>  
      <tr>
        <?php if($i != 0){ ?>
         <td colspan="11" style="text-align: center;"><b><h3>SEGUNDO PISO (SHELVING)</h3></b></td>
        <?php }?>
      </tr>
    </table>
    </a>
  <a href="<?php echo site_url('piso/1')?>">
  <table id="primerpiso" >
     <tr>
        <?php for($i=0; $i<=10; $i++){?>
          <td width="60" height="">
           <img src="<?php echo base_url();?>assets/img/estante.png" height="100" width="40"/>
          </td>
        <?php }?>
      </tr>  
      <tr>
        <?php if($i != 0){ ?>
         <td colspan="11" style="text-align: center;"><b><h3>PRIMER PISO (SHELVING)</h3></b></td>
        <?php }?>
      </tr>
    </table>
    </a>
    </div>
  </center>
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

<script type="text/javascript">
</script>
</body>
<style type="text/css">
  table:hover{
    cursor: pointer;
    

  }
  .containerdiv{
    position: absolute;
    top: 90px;
    width: 100%;
  }
  a{
    color: black;
  }
  
</style>
</html>