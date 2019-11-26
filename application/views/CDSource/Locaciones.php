<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reabastecimiento | <?php echo $pasillo; ?></title>
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
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/all.css">
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.common.min.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.material.min.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.material.mobile.min.css" />

    <script src="<?php echo base_url();?>assets/telerik/js/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/telerik/js/jszip.min.js"></script>
    <script src="<?php echo base_url();?>assets/telerik/js/kendo.all.min.js"></script>
      
</head>
<body class="hold-transition skin-purple sidebar-mini fixed"> 
   <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b><?php echo $pasillo ?></b></span>
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
    <ul class="sidebar-menu" id="menuGeneral">
       <li class="header" id="buscarSkuHeader"><h5><b style="color: white;">Buscar Sku</b></h5></li>
       <li id="buscarSku">
          <div class="input-group sidebar-form">
            
            <input type="text" name="q" id="q" class="form-control" placeholder="Buscar...">
                <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat" data-toggle="control-sidebar"><i class="fa fa-search"></i>
                  </button>
                </span> 
                
          </div>
        </li>
        <li class="header" id="AntiguedadHeader"><h5><b style="color: white;">Antiguedad</b></h5></li>
        <li id="Antiguedad">
           <div class="input-group sidebar-form">
            
            <input type="number" name="txtdias" id="txtdias" class="form-control" placeholder="Dias Antiguedad...">
                <span class="input-group-btn">
                  <button type="submit" name="search" id="btnAntiguedad" class="btn btn-flat" data-toggle="control-sidebar"><i class="fa fa-search" ></i>
                  </button>
                </span> 
                
          </div>
        </li>
        <li>
         <div class="input-group sidebar-form">
          
            <li style="margin-left: 20px;">
            <label>
              <input type="radio" name="radioAntiguedad" class="minimal form-control" value="antSku" checked>  Antiguedad Sku
            </label>
            </li>
            <li style="margin-left: 20px;">
            <label>
             <input type="radio" name="radioAntiguedad" class="minimal form-control" value="contCicl" >  Antiguedad Conteo Ciclico
            </label>
          </li>
         
          </div>

         </li>
         <br>
         <li id="cartonType">
          <center > 
            <a class="btn btn-app bg-olive form" id="btnExportarAntiguedad" data-toggle="control-sidebar" style="margin-right: 120px;">
             <i class="fa fa-file-excel-o"></i> Exportar
            </a> 
          </center>
       </li>
       <li class="header" id="headerFiltros"><h5><b style="color: white;">Filtros</b></h5></li>
        <li id="filtros">
           <div class="input-group sidebar-form">
            <center>
            <button type="button" id="btnLimpiarFiltros" class="btn bg-olive margin" data-toggle="control-sidebar"><i class="fa fa-close"></i> Limpiar</button>
            </center>    
          </div>
        </li>
        <li class="header" id="headerLocn"><h5><b style="color: white;">Locaciones</b></h5></li>
        <li id="adminLocn">
           <div class="input-group sidebar-form">
            <center>
            <button type="button" id="btnAdministrarLocn" class="btn bg-olive margin" data-toggle="control-sidebar"><i class="fa fa-gear"></i> Administrar</button>
            </center>    
          </div>
        </li>
    </ul>
    <ul class="sidebar-menu" id="menuAdminLocn">
      <li class="header" id="headerLocn"><h5><b style="color: white;">Administar Locaciones</b></h5></li>
      <li class="header" id="headerLocn"><h5><b style="color: white;">Opciones</b></h5></li>
      <li id="VolverMG">
           <div class="input-group sidebar-form">
            <center>
            <button type="button" id="btnVolverMG" class="btn bg-olive margin" data-toggle="control-sidebar"><i class="glyphicon glyphicon-chevron-left"></i> Volver al Menu General</button>
            </center>    
          </div>
        </li>
    </ul>
  </aside>
  <div class="containerdiv">
 <center>
<?php
    $alto = $dimensiones[0]->ALTO;
    $largo = $dimensiones[0]->LARGO;
    $count = 0;
   if(is_array($locacionesimpar)){ foreach ($locacionesimpar as $key) {

?>
  <table border="2" style="display: inline-block;">
  <?php for($x=0; $x<=($alto-1); $x++){ ?>
    <tr>
      <?php for($i=0; $i<=($largo-1); $i++){ ?>
      <td style="background-color: #66ff66" id="<?php if(isset($key[$x][$i]['BRCD'])){ echo $key[$x][$i]['LOCNID'];}else{echo ' ';} ?>" onclick="detalleLocn('<?php if(isset($key[$x][$i]['BRCD'])){ echo $key[$x][$i]['LOCNID'];}else{echo ' ';} ?>')">
           <b><?php if(isset($key[$x][$i]['BRCD'])){
                      echo $key[$x][$i]['BRCD'];
                    }
                    else{
                      echo ' ';

                    } ?></b>
      </td>
      <?php } ?>
    </tr>
  <?php  }  ?>
  </table>

<?php } } ?>
</center>
<br>
 <center>
<?php
    $alto = $dimensiones[0]->ALTO;
    $largo = $dimensiones[0]->LARGO;
    if(is_array($locacionespar)){ foreach ($locacionespar as $key) {

?>
  <table border="2" style="display: inline-block;">
  <?php for($x=0; $x<=($alto-1); $x++){ ?>
    <tr>
      <?php for($i=0; $i<=($largo-1); $i++){ ?>
      <td style="background-color: #66ff66" id="<?php if(isset($key[$x][$i]['BRCD'])){ echo $key[$x][$i]['LOCNID'];}else{echo ' ';} ?>" onclick="detalleLocn('<?php if(isset($key[$x][$i]['BRCD'])){ echo $key[$x][$i]['LOCNID'];}else{echo ' ';} ?>')">
           <b><?php if(isset($key[$x][$i]['BRCD'])){
                      echo $key[$x][$i]['BRCD'];
                    }
                    else{
                      echo ' ';
                    }
                    ?></b>
      </td>
      <?php } ?>
    </tr>
  <?php } ?>
  </table>

<?php } }?>
</center>
</div>
<div id="POPUP_Detalle_LOCN" class="grid">
  <div id="grid"></div>
</div>
<div id="POPUP_img" style="text-align: center; vertical-align: middle; height: 400px;">
  
</div>
<div id="POPUP_simbologia">
  <ul>
    <li>
      <i class="fa fa-square text-green"></i> LOCACION CON STOCK 
    </li>
    <li>
      <i class="fa fa-square text-red"></i> LOCACION SIN ARTICULOS 
    </li>
    <li>
      <i class="fa fa-square text-orange"></i> LOCACION SIN STOCK 
    </li>
    <li>
      <i class="fa fa-square text-yellow"></i> CONSULTA ARTICULO 
    </li>
    <li>
      <i class="fa fa-square text-brown"></i> CONSULTA ANTIGUEDAD SKU
    </li>
    <li>
      <i class="fa fa-square text-cclic"></i> CONSULTA ANTIGUEDAD CONTEO CICLICO
    </li>
  </ul>
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
        <p>No puede administrar ubicaciones que se encuentren con articulos (Colores <strong>VERDE</strong> y <strong>NARANJO</strong>)</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">CERRAR</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- PACE -->
<script src="<?php echo base_url();?>assets/bower_components/PACE/pace.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url();?>assets/plugins/iCheck/icheck.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url();?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>assets/dist/js/demo.js"></script>
</body>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/locaciones.js">
</script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/detlocaciones.js">
</script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/centrodistribucion.js">
</script>

<script type="text/javascript">
  nivel = 'locaciones';
  var flagAnt = '';
  var baseURL= "<?php echo base_url();?>";
  var idpasillo = '<?php echo $pasillo; ?>';
  var vsku = '';
  var busquedaAnterior = [];
  var selectedLocn = [];
  var dias = 0;
  if(localStorage.getItem("persistenciasku") != null){
        var persistenciasku = JSON.parse(localStorage.getItem("persistenciasku"));
    }
  else{
    var persistenciasku = [];
  }
  //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
</script>

<style type="text/css">
  table{
    table-layout: fixed;
  }
  td{
    vertical-align: bottom;
    text-align: center;
    word-wrap: break-word;
    white-space: normal;
    width: 50px;
    height: 50px;
    vertical-align: middle;
  }
  td:hover{
    cursor: pointer;
  }
  .containerdiv {
      overflow-x: auto;
      white-space: nowrap;
      position: relative;
      top: 80px;
      left: 20px;
      width: 97%;
  }
  .direcciondiv {
      overflow-x: auto;
      white-space: nowrap;
      position: relative;
      top: 110px;
      left: 20px;
      width: 75%;
  }
li:nth-child(6) {
   list-style-type: none; 
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
.grid{
  overflow: auto;
}

</style>
</html>