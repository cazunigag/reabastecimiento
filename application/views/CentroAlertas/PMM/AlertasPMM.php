<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Alertas PMM</title>
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
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo site_url('home');?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>PMM</b></span>
      <span class="logo-mini"><b>PMM</b></span>
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
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-user"></i>
              <span class="hidden-xs"><?php echo $this->session->userdata('nombre'); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url();?>assets/img/user.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $this->session->userdata('nombre'); ?>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="<?php echo site_url('logout');?>" class="btn btn-default btn-flat">Cerrar Sesion</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
 <?php $this->load->view("sidebar_menu"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-warning"/>  Tablas Finales</i></h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="info-box bg-green" id="DPWBox">
                 <span class="info-box-icon" ><i id="iconDPW" class="glyphicon glyphicon-ok"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">DOC NO RECIBIDOS</span>
                    <span class="info-box-number" id="nDPW">0</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                        <span class="progress-description">
                          <a id="DPWDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                        </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
              </div>
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="info-box bg-green" id="DCPWBox">
                 <span class="info-box-icon" ><i id="iconDCPW" class="glyphicon glyphicon-ok"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">CARGAS NO RECIBIDAS</span>
                    <span class="info-box-number" id="nDCPW">0</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                        <span class="progress-description">
                          <a id="DCPWDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                        </span>
                  </div>
                 
                  <!-- /.info-box-content -->
                </div>
              </div>
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="info-box bg-aqua" id="ELPNDBox">
                 <a id="UpdELPND"><span class="info-box-icon" ><i id="iconELPND" class="fa fa-download"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text">ERRORES LPN DISPOSICION</span>
                    <span class="info-box-number" id="nELPND">0</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                        <span class="progress-description">
                          <a id="ELPNDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                        </span>
                  </div>
                 
                  <!-- /.info-box-content -->
                </div>
              </div>
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="info-box bg-aqua" id="EALMBox">
                 <a id="UpdEALM"><span class="info-box-icon" ><i id="iconEALM" class="fa fa-download"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text">ERRORES ALMACENAJE</span>
                    <span class="info-box-number" id="nEALM">0</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                        <span class="progress-description">
                          <a id="EALMDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                        </span>
                  </div>
                 
                  <!-- /.info-box-content -->
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="info-box bg-aqua" id="ECCBox">
                 <a id="UpdECC"><span class="info-box-icon" ><i id="iconECC" class="fa fa-download"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text">ERRORES CONTEO CICLICO</span>
                    <span class="info-box-number" id="nECC">0</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                        <span class="progress-description">
                          <a id="ECCDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                        </span>
                  </div>
                 
                  <!-- /.info-box-content -->
                </div>
              </div>
              <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="info-box bg-aqua" id="LPNMBox">
                 <a id="UpdLPNM"><span class="info-box-icon" ><i id="iconLPNM" class="fa fa-download"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text">ERRORES LPN MODIFICADOS</span>
                    <span class="info-box-number" id="nLPNM">0</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                        <span class="progress-description">
                          <a id="LPNMDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                        </span>
                  </div>
                 
                  <!-- /.info-box-content -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- ./col -->
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
<div id="POPUP_Detalle_DPW" class="grid">
  <div id="gridDetDPW"></div>
</div>
<div id="POPUP_DetErr_DPW" class="grid">
  <div id="gridDetERRDPW"></div>
</div>
<div id="POPUP_Detalle_DCPW" class="grid">
  <div id="gridDetDCPW"></div>
</div>
<div id="POPUP_DetERR_DCPW" class="grid">
  <div id="gridDetERRDCPW"></div>
</div>
<div id="POPUP_ELPND" class="grid">
  <div id="toolbarELPND"></div>
  <div id="gridELPND"></div>
</div>
<div id="POPUP_calendarioDispo">
  <div class="form-group">
    <label>Seleccione una fecha:</label>

    <div class="input-group date">
      <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
      </div>
      <input type="text" class="form-control pull-right" id="datepicker">
    </div>
    <!-- /.input group -->
  </div>
  <div class="form-group">
    <button type="button" id="btnActualizarAlertDispo" class="btn btn-primary">Actualizar</button>
  </div>
</div>
<div id="POPUP_calendarioALM">
  <div class="form-group">
    <label>Seleccione una fecha:</label>

    <div class="input-group date">
      <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
      </div>
      <input type="text" class="form-control pull-right" id="datepickerALM">
    </div>
    <!-- /.input group -->
  </div>
  <div class="form-group">
    <button type="button" id="btnActualizarAlertAlm" class="btn btn-primary">Actualizar</button>
  </div>
</div>
<div id="POPUP_EALM" class="grid">
  <div id="toolbarEALM"></div>
  <div id="gridEALM"></div>
</div>
<div id="POPUP_calendarioECC">
  <div class="form-group">
    <label>Seleccione una fecha:</label>

    <div class="input-group date">
      <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
      </div>
      <input type="text" class="form-control pull-right" id="datepickerECC">
    </div>
    <!-- /.input group -->
  </div>
  <div class="form-group">
    <button type="button" id="btnActualizarAlertECC" class="btn btn-primary">Actualizar</button>
  </div>
</div>
<div id="POPUP_ECC" class="grid">
  <div id="toolbarECC"></div>
  <div id="gridECC"></div>
</div>
<div id="POPUP_calendarioLPNM">
  <div class="form-group">
    <label>Seleccione una fecha:</label>

    <div class="input-group date">
      <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
      </div>
      <input type="text" class="form-control pull-right" id="datepickerLPNM">
    </div>
    <!-- /.input group -->
  </div>
  <div class="form-group">
    <button type="button" id="btnActualizarAlertLPNM" class="btn btn-primary">Actualizar</button>
  </div>
</div>
<div id="POPUP_LPNM" class="grid">
  <div id="toolbarLPNM"></div>
  <div id="gridLPNM"></div>
</div>
</div>
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

<script type="text/javascript" src="<?php echo base_url();?>assets/js/CentroAlertas/PMM/alertasPMM.js" async>
</script>
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
<script type="text/javascript">
  var baseURL= "<?php echo base_url();?>";
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
  .k-grid  .k-grid-header  .k-header  .k-link {
        height: auto;
  }
      
  .k-grid  .k-grid-header  .k-header {
        white-space: normal;
  }
  .k-grid tbody tr {
    line-height: 14px;
  }
  .k-grid {
    font-size: 12px;
  }
</style>
</body>
</html>
s