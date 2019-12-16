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
<body class="hold-transition skin-purple sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo site_url('home/home');?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <!-- logo for regular state and mobile devices -->
      <span class="logo-mini"><b>WMS</b></span>
      <span class="logo-lg"><b>WMS</b></span>
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
  
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-warning"/>  Alertas INPT</i></h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
              <div class="row">
                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="info-box bg-green" id="pktBox">
                   <a id="PKTBajados"><span class="info-box-icon" ><i id="iconPKT" class="glyphicon glyphicon-ok"></i></span></a>
                    <div class="info-box-content">
                      <span class="info-box-text">PICK TICKET BAJADOS</span>
                      <span class="info-box-number" id="npkt">0</span>
                      <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                      </div>
                          <span class="progress-description">
                            <a id="pktDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                          </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                  <div class="info-box bg-green" id="POBox">
                   <span class="info-box-icon"><i id="iconPO" class="glyphicon glyphicon-ok"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">PO BAJADOS</span>
                      <span class="info-box-number" id="nPO">0</span>
                      <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                      </div>
                          <span class="progress-description">
                            <a id="PODetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                          </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                  <div class="info-box bg-green" id="BRCDBox">
                   <span class="info-box-icon"><i id="iconBRCD" class="glyphicon glyphicon-ok"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">BARCODE (XREF)</span>
                      <span class="info-box-number" id="nBRCD">0</span>
                      <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                      </div>
                          <span class="progress-description">
                            <a id="BRCDDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                          </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                  <div class="info-box bg-green" id="ARTBox">
                   <span class="info-box-icon"><i id="iconART" class="glyphicon glyphicon-ok"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">ERRORES ARTICULOS</span>
                      <span class="info-box-number" id="nART">0</span>
                      <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                      </div>
                          <span class="progress-description">
                            <a id="ARTDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                          </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                </div>
                <!-- ./col -->
              </div>

              <div class="row">
                <div class="col-lg-3 col-xs-6">
                  <div class="info-box bg-green" id="CITABox">
                   <a id="CITASBajadas"><span class="info-box-icon" ><i id="iconCITA" class="glyphicon glyphicon-ok"></i></span></a>
                    <div class="info-box-content">
                      <span class="info-box-text">CITAS BAJADAS</span>
                      <span class="info-box-number" id="nCITA">0</span>
                      <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                      </div>
                          <span class="progress-description">
                            <a id="CITADetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                          </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                  <div class="info-box bg-green" id="ASNBox">
                   <a id="ASNBajados"><span class="info-box-icon" ><i id="iconASN" class="glyphicon glyphicon-ok"></i></span></a>
                    <div class="info-box-content">
                      <span class="info-box-text">ASN BAJADOS</span>
                      <span class="info-box-number" id="nASN">0</span>
                      <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                      </div>
                          <span class="progress-description">
                            <a id="ASNDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                          </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                </div> 
                <div class="col-lg-3 col-xs-6">
                  <div class="info-box bg-green" id="LPNBox">
                   <a id="LPNBajados"><span class="info-box-icon" ><i id="iconLPN" class="glyphicon glyphicon-ok"></i></span></a>
                    <div class="info-box-content">
                      <span class="info-box-text">LPN BAJADOS</span>
                      <span class="info-box-number" id="nLPN">0</span>
                      <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                      </div>
                          <span class="progress-description">
                            <a id="LPNDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                          </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                  <div class="info-box bg-green" id="DISTROBox">
                   <span class="info-box-icon" ><i id="iconDISTRO" class="glyphicon glyphicon-ok"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">ERRORES DISTRO</span>
                      <span class="info-box-number" id="nDISTRO">0</span>
                      <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                      </div>
                          <span class="progress-description">
                            <a id="DISTROSDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                          </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                </div>  
              </div>
            </div>
          </div>
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-warning"/>  Alertas OUTPT</i></h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-lg-3 col-xs-6">
                <div class="info-box bg-green" id="CARGABox">
                 <a id="CARGASEjecutadas"><span class="info-box-icon" ><i id="iconCARGA" class="glyphicon glyphicon-ok"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text">CARGAS ENVIADAS</span>
                    <span class="info-box-number" id="nCARGA">0</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                        <span class="progress-description">
                          <a id="CARGADetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                        </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
              </div>
            </div>
          </div>
        </div>
      <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-warning"/>  Alertas Tablas Finales</i></h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-lg-3 col-xs-6">
                <div class="info-box bg-green" id="OLABox">
                 <a id="OLASEjecutadas"><span class="info-box-icon" ><i id="iconOLA" class="glyphicon glyphicon-ok"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text">OLAS EJECUTADAS</span>
                    <span class="info-box-number" id="nOLA">0</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                        <span class="progress-description">
                          <a id="OLADetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                        </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
              </div>
              <div class="col-lg-3 col-xs-6">
                <div class="info-box bg-green" id="FASNBox">
                 <a id="FASNEjecutadas"><span class="info-box-icon" ><i id="iconFASN" class="glyphicon glyphicon-ok"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text">ERRORES ASN</span>
                    <span class="info-box-number" id="nFASN">0</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                        <span class="progress-description">
                          <a id="FASNDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                        </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
              </div>
              <div class="col-lg-3 col-xs-6">
                <div class="info-box bg-green" id="PSTBox">
                 <a id="PST"><span class="info-box-icon" ><i id="iconPST" class="glyphicon glyphicon-ok"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text">PASILLOS SIN WORK GROUP</span>
                    <span class="info-box-number" id="nPST">0</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                        <span class="progress-description">
                          <a id="PSTDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                        </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
              </div>
              <div class="col-lg-3 col-xs-6">
                <div class="info-box bg-green" id="INTFBox">
                 <a id="INTF"><span class="info-box-icon" ><i id="iconINTF" class="glyphicon glyphicon-ok"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text">INVN NEED TYPE FALTANTES</span>
                    <span class="info-box-number" id="nINTF">0</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 100%"></div>
                    </div>
                        <span class="progress-description">
                          <a id="INTFDetalles" class="small-box-footer">Detalles <i class="fa fa-arrow-circle-right"></i></a>
                        </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
              </div>
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
<div id="POPUP_Verificar_PO" class="grid">
  <div id="gridVerPO"></div>
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
<div id="POPUP_Verificar_ASN" class="grid">
  <div id="gridVerASN"></div>
</div>
<div id="POPUP_UNDENV_ASN" class="grid">
  <div id="gridUndEnvASN"></div>
</div>
<div id="POPUP_Resumen_LPN" class="grid">
  <div id="gridResLPN"></div>
</div>
<div id="POPUP_Verificar_LPN" class="grid">
  <div id="gridVerLPN"></div>
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
<div id="POPUP_Detalle_FASN" class="grid">
  <div id="toolbarFASN"></div>
  <div id="gridDetFASN"></div>
</div>

<div id="POPUP_Detalle_PST" class="grid">
  <div id="gridDetPST"></div>
</div>

<div id="POPUP_Resumen_PST" class="grid">
  <div id="gridResPST"></div>
</div>

<div id="POPUP_Detalle_INTF" class="grid">
  <div id="gridDetINTF"></div>
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
<div class="modal modal-warning fade" id="modal-warning">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-warning"></i>  Alerta</h4>
      </div>
      <div class="modal-body">
        <p id="warning-modal"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">CERRAR</button>
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
<script type="text/javascript" src="<?php echo base_url();?>assets/js/CentroAlertas/WMS/alertasWMS.js" async>
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
