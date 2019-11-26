<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reabastecimiento | <?php echo $piso; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/skins/_all-skins.min.css">
  <!-- Pace style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/pace/pace.min.css">

  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/qtip/jquery.qtip.css">
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.common.min.css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.material.min.css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.material.mobile.min.css" />
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/select2/dist/css/select2.min.css">

  <script src="<?php echo base_url();?>assets/telerik/js/jquery.min.js"></script>
  <script src="<?php echo base_url();?>assets/telerik/js/jszip.min.js"></script>
  <script src="<?php echo base_url();?>assets/telerik/js/kendo.all.min.js"></script>    
</head>
<body class="hold-transition skin-purple sidebar-mini fixed" oncontextmenu="return false"> 
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
      <a href="#" role="button"><i class="fa_"></i>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- Control Sidebar Toggle Button -->
           <li>
            <a id="btnsimbologia"><i class="fa fa-question-circle"></i> Simbologia</a>
          </li>
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-search"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="sidebar-menu">
       <li class="header" id="buscarSkuHeader"><h5><b style="color: white;">Buscar Sku</b></h5></li>
       <li id="buscarSku">
          <div class="input-group sidebar-form">
            
            <input type="text" name="q" id="q" class="form-control" placeholder="Buscar...">
                <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search" data-toggle="control-sidebar"></i>
                  </button>
                </span> 
                
          </div>
        </li>
         <li class="header" id="cartonTypeHeader"><h5><b style="color: white;">Tipo Cartones</b></h5></li>
        <li id="cartonType">
           <div class="input-group sidebar-form">
            <center>
            <button type="button" id="btnCartonType" class="btn bg-olive margin" data-toggle="control-sidebar">
              <i class="fa fa-check-square"></i>  Verificacion Tipo Carton</button>
            </center>    
          </div>
          <div class="input-group sidebar-form">
            <center>
            <button type="button" id="btnUPDCartonType" class="btn bg-olive margin" data-toggle="control-sidebar">
              <i class="fa fa-refresh"></i>  Actualizar Tipo Carton</button>
            </center>    
          </div>
        </li>
         <li class="header" id="ClassPasilloHeader"><h5><b style="color: white;">Clasificacion Pasillo</b></h5></li>
        <li id="ClassPasillo">
           <div class="input-group sidebar-form">
            <center>
            <button type="button" id="ActClassPasillo" class="btn bg-olive margin" data-toggle="control-sidebar"><i class="fa fa-pencil"></i> Actualizar</button>
            </center>    
          </div>
        </li>
    </ul>
  </aside>
 <div class="titulo"><center><b><h1><?php echo $piso; ?></h1></b></center></div>
 <div id="subtitulo" class="subtitulo"><center><b><h2><?php echo $piso; ?></h2></b></center></div>
 <div class="containerdiv" id="containerdiv">
  <table>
    <tr>
       <?php foreach ($pasillos as $key) { $idpasillo = $key->PASILLOS; ?>
     
        <td colspan="2" width="75px" align="right" style=" text-align: right; vertical-align: middle;">
          <b><span style="background-color: <?php echo $key->COLOUR; ?>;"><?php echo $key->CLASSIFICATION; ?></span>
        </td>
     <?php   }  ?>
    </tr>
    <tr>
    <?php foreach ($pasillos as $key) { $idpasillo = $key->PASILLOS;?>
     
        <td id="pasilloimg" width="75px" align="right">
          <img height="150px" width="75px" src="<?php echo base_url(); ?>assets/img/estante.png" >
        </td>
        
        <td class="popup" id="<?php echo $key->PASILLOS; ?>" >
           <a href="<?php echo site_url('pasillo/').$idpasillo?> ">
              <span class="label label-success" style="font-size: 14px" id="<?php echo $key->PASILLOS; ?>">
                <?php echo $idpasillo; ?>
               </span>  
            </a>
        </td>
       
    <?php } ?>
    </tr>
  </table>
 </div>
 <div id="POPUP_simbologia">
  <ul>
    <li>
      <i class="fa fa-square text-green"></i> PASILLOS CON LOCACIONES
    </li>
    <li>
      <i class="fa fa-square text-red"></i> PASILLOS SIN LOCACIONES
    </li>
  </ul>
</div>
<div id="POPUP_ClassPasillo">
   <div >
      <label>Pasillos:</label>
      <select id="selectPasillos" data-placeholder="Seleccione..."
              style="width: 100%;">
      <?php foreach ($pasillos as $key) { ?>
        <option><?php echo $key->PASILLOS; ?></option>
       <?php  } ?>
      </select>
    </div>
    <div >
      <label>Clasificacion:</label>
      <select id="selectClasificacion" data-placeholder="Seleccione..."
              style="width: 100%;">
      <?php foreach ($clasificaciones as $key) { ?>
        <option><?php echo $key->CLASSIFICATION; ?></option>
       <?php  } ?>
      </select>
    </div>
    <br>
    <div >
       <button class="k-button k-primary" id="btnActualizarClass" name="btnActualizarClass" >Actualizar</button>
    </div>
</div>
<div id="POPUP_CartonType">
   <div >
      <label>Carton Type:</label>
      <select id="selectCartonType" data-placeholder="Seleccione..."
              style="width: 100%;">
      </select>
    </div>
    <br>
    <div >
       <button class="k-button k-primary" id="btnActCartonType" name="btnActCartonType" >Actualizar</button>
    </div>
</div>
<div id="POPUP_CartonType2">
  <div >
      <label>Pasillo:</label>
      <select id="selectPasillos2" data-placeholder="Seleccione..."
              style="width: 100%;">
      <?php foreach ($pasillos as $key) { ?>
        <option><?php echo $key->PASILLOS; ?></option>
       <?php  } ?>
      </select>
  </div>
   <div >
      <label>Carton Type:</label>
      <select id="selectCartonType2" data-placeholder="Seleccione..."
              style="width: 100%;">
      </select>
    </div>
    <br>
    <div >
       <button class="k-button k-primary" id="btnActCartonType2" name="btnActCartonType2" >Actualizar</button>
    </div>
</div>
<div class="modal modal-success fade" id="modal-success">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Informacion</h4>
      </div>
      <div class="modal-body">
        <p>Clasificacion Actualizada Correctamente&hellip;</p>
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
<!-- <img class="loading" src="<?php echo base_url(); ?>assets/img/loading.gif"> -->

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

<script src="<?php echo base_url();?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>

<script src="<?php echo base_url();?>assets/bower_components/qtip/jquery.qtip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/pasillos.js">
</script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/centrodistribucion.js">
</script>
<div class="modalloading" style="display: none">
    <div class="center">
        <img alt="" style="opacity: 1;" src="<?php echo base_url();?>assets/img/loader.gif"/>
    </div>
</div>
</body>
<script type="text/javascript">
  var idpasillorc = '';
  var pasillospiso = [];
  var nivel = 'pasillos';
  <?php foreach ($pasillos as $key) { 
    if(next($pasillos)!= null){ ?>
      pasillospiso = pasillospiso+'<?php echo $key->PASILLOS; ?>'+"','"; 
  <?php } else{ ?>
        pasillospiso = pasillospiso+'<?php echo $key->PASILLOS; ?>';
  <?php } } ?>
  var baseURL= "<?php echo base_url();?>";
  var pasillo = '';
  var persistencialocn= [];
  var persistenciasku= [];
  localStorage.clear();
</script>
<style type="text/css">
  td{
    vertical-align: bottom;
    text-align: center;
  }
  .containerdiv {
    width: 30em;
    height: 240px;
    overflow-x: auto;
    white-space: nowrap;
    position: absolute;
    top: 200px;
    left: 30px;
    width: 95%;
  }
  .loading{
    display: none;
    position: relative;
  }
  .titulo{
  top: 45px;
  width: 100%;
  position: absolute;
  text-align: center;
}
.subtitulo{
  top: 90px;
  position: absolute;
  text-align: center;
  left: 47%;
  display: none;
}
.modalloading
{
    position: fixed;
    z-index: 999;
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
    background-color: Black;
    filter: alpha(opacity=60);
    opacity: 0.6;
    -moz-opacity: 0.6;
}
.center
{
    z-index: 1000;
    margin: 300px auto;
    padding: 10px;
    width: 130px;
    background-color: White;
    border-radius: 10px;
    filter: alpha(opacity=100);
    opacity: 1;
    -moz-opacity: 1;
}
.center img
{
    opacity: 1;
    height: 120px;
    width: 120px;
}
</style>
</html>