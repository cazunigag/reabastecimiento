<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Alertas WMS</title>
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
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.common.min.css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.material.min.css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.material.mobile.min.css" />
  <script src="<?php echo base_url();?>assets/telerik/js/jquery.min.js"></script>
  <script src="<?php echo base_url();?>assets/telerik/js/jszip.min.js"></script>
  <script src="<?php echo base_url();?>assets/telerik/js/kendo.all.min.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

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
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
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
  <!-- Left side column. contains the logo and sidebar -->
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
            <img src="<?php echo base_url();?>assets/img/logo.png" height="60" width="170"/>
          </center>
        </li>
        <div style="margin-top: 20px; margin-bottom: 20px"></div>
        <li class="header"><b>MENU</b></li>
        <li>
          <a href="<?php echo site_url('articuloLocacion');?>">
            <i class="fa fa-table"></i><span>Ingreso Articulo-Locacion</span>
          </a>
        </li>
        <li>
          <a href="<?php echo site_url('asignacionPedido');?>">
            <i class="fa fa-table"></i><span>Asignacion de Pedidos</span>
          </a>
        </li>
        <li>
          <a href="<?php echo site_url('redex');?>">
            <i class="fa fa-map"></i></i><span>Centro de Distribucion</span>
          </a>
        </li>
        <li>
          <a href="<?php echo site_url('centroAlertas');?>">
            <i class="fa fa-warning"></i></i><span>Centro de Alertas</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-warning"/>  Alertas INPT</i>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green" id="pktBox">
            <div class="inner">
              <a id="PKTBajados"><h3 id="npkt">0</h3></a>

              <p>PICK TICKET BAJADOS</p>
            </div>
            <div class="icon">
              <i id="iconPKT" class="ion ion-clipboard"></i>
            </div>
            <a id="pktDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green" id="POBox">
            <div class="inner">
              <h3 id="nPO">0</h3>

              <p>OC BAJADAS</p>
            </div>
            <div class="icon">
              <i id="iconPO" class="ion ion-clipboard"></i>
            </div>
            <a id="PODetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green" id="BRCDBox">
            <div class="inner">
              <h3 id="nBRCD">0</h3>

              <p>CODIGOS DE BARRA (XREF)</p>
            </div>
            <div class="icon">
              <i id="iconBRCD" class="ion ion-clipboard"></i>
            </div>
            <a id="BRCDDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green" id="ARTBox">
            <div class="inner">
              <h3 id="nART">0</h3>

              <p>ERRORES ARTICULOS</p>
            </div>
            <div class="icon">
              <i id="iconART" class="ion ion-clipboard"></i>
            </div>
            <a id="ARTDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>

      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green" id="CITABox">
            <div class="inner">
              <a id="CITASBajadas"><h3 id="nCITA">0</h3></a>

              <p>CITAS BAJADAS</p>
            </div>
            <div class="icon">
              <i id="iconCITA" class="ion ion-clipboard"></i>
            </div>
            <a id="CITADetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green" id="ASNBox">
            <div class="inner">
              <a id="ASNBajados"><h3 id="nASN">0</h3></a>

              <p>ASN BAJADOS</p>
            </div>
            <div class="icon">
              <i id="iconASN" class="ion ion-clipboard"></i>
            </div>
            <a id="ASNDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div> 
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green" id="LPNBox">
            <div class="inner">
              <a id="LPNBajados"><h3 id="nLPN">0</h3></a>

              <p>LPN BAJADOS</p>
            </div>
            <div class="icon">
              <i id="iconLPN" class="ion ion-clipboard"></i>
            </div>
            <a id="LPNDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green" id="DISTROBox">
            <div class="inner">
              <h3 id="nDISTRO">0</h3>

              <p>ERRORES DISTRO</p>
            </div>
            <div class="icon">
              <i id="iconDISTRO" class="ion ion-clipboard"></i>
            </div>
            <a id="DISTROSDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>  
      </div>
       <div class="row">
       <section class="content-header">
        <h1>
          <i class="fa fa-warning"/>  Alertas OUTPT</i>
        </h1>
      </section>
      </div>
      <br>
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green" id="CARGABox">
            <div class="inner">
              <a id="CARGASEjecutadas"><h3 id="nCARGA">0</h3></a>

              <p>CARGAS ENVIADAS</p>
            </div>
            <div class="icon">
              <i id="iconCARGA" class="ion ion-clipboard"></i>
            </div>
            <a id="CARGADetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
      <div class="row">
       <section class="content-header">
        <h1>
          <i class="fa fa-warning"/>  Alertas OLA</i>
        </h1>
      </section>
      </div>
      <br>
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green" id="OLABox">
            <div class="inner">
              <a id="OLASEjecutadas"><h3 id="nOLA">0</h3></a>

              <p>OLAS EJECUTADAS</p>
            </div>
            <div class="icon">
              <i id="iconOLA" class="ion ion-clipboard"></i>
            </div>
            <a id="OLADetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
      <!-- /.row -->
      <!-- Main row -->
          
          </div>
          <!-- /.box -->

        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark" style="display: none;">
    <!-- Create the tabs -->
    
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<div id="POPUP_Detalle_PKT" class="grid">
  <div id="toolbarPKT"></div>
  <div id="gridDetPKT"></div>
</div>
<div id="POPUP_Resumen_PKT" class="grid">
  <div id="gridResPKT"></div>
</div>
<div id="POPUP_Detalle_PO" class="grid">
  <div id="toolbarPO"></div>
  <div id="gridDetPO"></div>
</div>
<div id="POPUP_Detalle_BRCD" class="grid">
  <div id="toolbarBRCD"></div>
  <div id="gridDEtBRCD"></div>
</div>
<div id="POPUP_Detalle_ART" class="grid">
  <div id="toolbarART"></div>
  <div id="gridDEtART"></div>
</div>
<div id="POPUP_Detalle_OLA" class="grid">
  <div id="gridDetOLA"></div>
</div>
<div id="POPUP_Resumen_OLA" class="grid">
  <div id="gridResOLA"></div>
</div>
<div id="POPUP_Resumen_CITA" class="grid">
  <div id="gridResCITA"></div>
</div>
<div id="POPUP_Resumen_codCITA" class="grid">
  <div id="gridCodCITA"></div>
</div>
<div id="POPUP_Detalle_CITA" class="grid">
  <div id="toolbarCITA"></div>
  <div id="gridDetCITA"></div>
</div>
<div id="POPUP_Resumen_ASN" class="grid">
  <div id="gridResASN"></div>
</div>
<div id="POPUP_Resumen_codASN" class="grid">
  <div id="gridRescodASN"></div>
</div>
<div id="POPUP_Detalle_ASN" class="grid">
  <div id="toolbarASN"></div>
  <div id="gridDetASN"></div>
</div>
<div id="POPUP_Resumen_LPN" class="grid">
  <div id="gridResLPN"></div>
</div>
<div id="POPUP_Detalle_LPN" class="grid">
  <div id="toolbarLPN"></div>
  <div id="gridDetLPN"></div>
</div>
<div id="POPUP_Detalle_DISTRO" class="grid">
  <div id="toolbarDISTRO"></div>
  <div id="gridDetDISTRO"></div>
</div>
<div id="POPUP_Resumen_CARGA" class="grid">
  <div id="gridResCARGA"></div>
</div>
<div id="POPUP_Detalle_CARGA" class="grid">
  <div id="toolbarCARGA"></div>
  <div id="gridDetCARGA"></div>
</div>

<div class="modal modal-success fade" id="modal-success">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-info"></i> Informacion</h4>
      </div>
      <div class="modal-body">
        <p id="success-modal"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal" id="closemodal">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal modal-danger fade" id="modal-danger">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-warning"></i> Error</h4>
      </div>
      <div class="modal-body">
        <p id="error-modal"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal modal-info fade" id="modal-info">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-info"></i> Informacion</h4>
      </div>
      <div class="modal-body">
        <p>Operacion Cancelada</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->

<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url();?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<!-- Sparkline -->
<script src="<?php echo base_url();?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url();?>assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url();?>assets/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url();?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url();?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url();?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url();?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>assets/dist/js/demo.js"></script>
<script type="text/javascript">
  var baseURL= "<?php echo base_url();?>";
</script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/centroalertas.js" async>
</script>
<style type="text/css">
  a{
    color: white;
  }
  a:link{
    color: white;
  }
  a:visited{
    color: white;
  }
  a:hover{
    color: white;
    cursor: pointer;
  }
  a:active{
    color: white;
    cursor: pointer;
  }
  .k-toolbar .k-button{
    color: black;
  }
</style>
</body>
</html>
