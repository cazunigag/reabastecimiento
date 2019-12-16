<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Redex | Home</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
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

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
      
</head>
<body class="hold-transition skin-purple sidebar-mini fixed">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo site_url('home/home');?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>RDX</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>REDEX</b></span>
    </a>
   
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" id="togglenavigation" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li>           
          <div style="margin-top: 20px; margin-bottom: 20px"></div>
          <center>
            <a href="<?php echo site_url('home/home');?>" class="logo">
              <!-- mini logo for sidebar mini 50x50 pixels -->
              <span class="logo-mini"></span>
              <!-- logo for regular state and mobile devices -->
              <span class="logo-lg"><img src="<?php echo base_url();?>assets/img/logo.png" height="60" width="170"/></span>
            </a>  
          </center>
        </li>
        <div style="margin-top: 20px; margin-bottom: 20px"></div>
        <li class="header"><b>MENU</b></li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-refresh"></i>
            <span>Reabastecimiento</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo site_url('articuloLocacion');?>"><i class="fa fa-table"></i> Ingreso Articulo-Locacion</a></li>
            <li><a href="<?php echo site_url('asignacionPedido');?>"><i class="fa fa-table"></i> Asignacion de Pedidos</a></li>
            <li><a href="<?php echo site_url('seteo308');?>"><i class="fa fa-arrow-down"></i> Seteo Depto</a></li>
            <li><a href="<?php echo site_url('almacenamientolocn');?>"><i class="fa fa-cube"></i> Almacenamiento Locacion</a></li>
            <li><a href="<?php echo site_url('sublineas');?>"><i class="fa fa-table"></i> Mantenedor Min-Max Sublinea</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-map"></i>
            <span>Centro de Distribucion</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo site_url('redex');?>"><i class="fa fa-map"></i> Centro de Distribucion</a></li>
            <li class="treeview">
              <a href="#"><i class="fa fa-cube"></i> Carton Type
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo site_url('cartontype');?>"><i class="fa fa-cube"></i> Carton Type Pasillo</a></li>
                <li><a href="<?php echo site_url('cartontypearticulo');?>"><i class="fa fa-cube"></i> Carton Type Articulo</a></li>
              </ul>
          </ul>
        </li>
        <li>
          <a href="<?php echo site_url('centroAlertas');?>">
            <i class="fa fa-warning"></i></i><span>Centro de Alertas</span>
          </a>
        </li>
        <!--<li>
          <a href="<?php echo site_url('seteoAttr');?>">
            <i class="fa fa-check-square-o"></i></i><span>Seteo Atributos Logisticos</span>
          </a>
        </li>-->
        <li>
          <a href="<?php echo site_url('LPNDemora');?>">
            <i class="fa fa-calendar"></i></i><span>LPNs Con Demora</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">    
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>


<!-- ./wrapper -->

<!-- jQuery 3 -->
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
<!-- page script -->
<script src="<?php echo base_url();?>assets/artyom-source/artyom.window.min.js"></script>
<!--<script type="text/javascript">
  const artyom = new Artyom();
  var commandPasillos = {
      smart: true,
      indexes:["pasillo *","KD02"], // These spoken words will trigger the execution of the command
      action:function(i,wildcard){ // Action to be executed when a index match with spoken word
          switch(i){
            default: 
              //window.location.href= "<?php echo site_url('pasillo/KD01')?>";
              console.log(wildcard);
          }
      }
  };
  artyom.addCommands(commandPasillos);
$(document).ready(function(){
  alert('ready');
  

      setTimeout(function(){// if you use artyom.fatality , wait 250 ms to initialize again.
           artyom.initialize({
              soundex: true,
              lang:"es_ES",// A lot of languages are supported. Read the docs !
              continuous:false,// recognize 1 command and stop listening !
              listen:true, // Start recognizing
              debug:true, // Show everything in the console
              speed:1 // talk normally
          }).then(function(){
              console.log("Ready to work !");
          });
      },250);
  
});  
  
</script>-->
<script type="text/javascript">
  $(document).ready(function(){
    checkChanges();
      function checkChanges(){
        if($("body").hasClass('sidebar-collapse')){
          $(".logo-lg").hide();
        }else{
          $(".logo-lg").fadeIn();
        }
        setTimeout(checkChanges, 200);
      }
  });
</script>
</body>
</html>
