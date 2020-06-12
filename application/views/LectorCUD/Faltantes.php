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

  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <style type="text/css">
    td{
      text-align: center;
    }
  </style>

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
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
      
</head>
<body class="hold-transition skin-purple  sidebar-collapse sidebar-mini fixed">
<!-- Site wrapper -->
<div class="wrapper">

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
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" id="togglenavigation" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- Control Sidebar Toggle Button -->
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
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <?php if($this->session->userdata('modulo') == 'lector'){
           $this->load->view("sidebar_lector");
        }else{
           $this->load->view("sidebar_menu");
        } 
  ?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-barcode"/>  Faltantes</i></h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-lg-2 col-xs-4">
              <div class="form-group">
                <label>Fecha de Despacho:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker" autocomplete="off">
                </div>
                <!-- /.input group -->
              </div>
            </div>
            <div class="col-lg-2 col-xs-4">
              <div >
                <label>Tienda</label>
                <select id="selectTienda" data-placeholder="Seleccione..."
                        style="width: 100%;">
                </select>
              </div>  
            </div>
            <div class="col-lg-2 col-xs-4" id="ruta">
              <label>ID Transporte:</label>
              <select id="selectId" data-placeholder="Seleccione..."
                      style="width: 100%;">
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="box box-primary" id="boxscanner">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-search"/>  Scanner</i></h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-xs-4">
              <input id="cud" class="form-control input-lg" type="text" placeholder="CUD">

            </div>
            <div class="col-xs-4">
              <p style="font-size: 40px; font-weight: bold; margin-left: 30px;" id="totales"></p>
            </div>
            <div class="col-xs-2">
              <div class="col-lg-1 col-xs-2">
                <a class="btn btn-app" id="cerrarCarga">
                  <i class="fa fa-check-square-o"></i> Cerrar Carga
                </a>
              </div>
            </div>
            <div class="col-xs-2">
              <div class="col-lg-1 col-xs-2">
                <a class="btn btn-app" id="Faltantes">
                  <i class="fa fa-search"></i> Resumen Faltantes
                </a>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <table class="table table-hover">
                <tr style="font-size: 40px; ">
                    <th style="text-align: center; width: 30%;">ID TRANSPORTE</th>
                    <th style="text-align: center; width: 40%;">CONDUCTOR</th>
                    <th style="text-align: center; width: 30%;">PATENTE</th>
                  <tbody id="boosmapinfo"></tbody>
                </tr>
              </table>
            </div>
          </div>
        </div>
      <!--<div id="sourceSelectPanel" style="display:none">
          <label for="sourceSelect">Change video source:</label>
          <select id="sourceSelect" style="max-width:400px">
          </select>
      </div>-->
      
    </section>
    <!-- /.content -->
  </div>
  <div id="POPUP_Cierre_Carga">
    <div id="Toolbar"></div>
    <div id="grid"></div>
  </div>
  <div id="POPUP_det_Cierre_Carga">
    <div id="gridDet"></div>
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
</div>
<div id="POPUP_Id">
    ID TRANSPORTE: 
    <select id="selectId" data-placeholder="Seleccione..."
            style="width: 100%;">
    </select>
    <p style="padding-top: 1em; text-align: right">
        <button type="submit" id="Seleccionar" name="Seleccionar" class="k-button k-primary">Seleccionar</button>
    </p>
 
</div>
<div id="POPUP_Importar">
  <form method="post" id="import_form" enctype="multipart/form-data">
      <input name="files" id="files" type="file" aria-label="files" accept=".xls, .xlsx"/>
      <p style="padding-top: 1em; text-align: right">
          <button type="submit" id="importar" name="importar" class="k-button k-primary">Importar</button>
      </p>
  </form>
</div>
<div class="modalloading" style="display: none">
    <div class="center">
        <img alt="" style="opacity: 1;" src="<?php echo base_url();?>assets/img/loader.gif"/>
    </div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 --> 
<!--<script type="text/javascript">
    var baseURL= "<?php echo base_url();?>";
    window.addEventListener('load', function () {
        var baseURL= "<?php echo base_url();?>";
        var barcode = "";
        var excel;
        let selectedDeviceId;
        const codeReader = new ZXing.BrowserBarcodeReader()
        console.log('ZXing code reader initialized')
        codeReader.getVideoInputDevices()
            .then((videoInputDevices) => {
                const sourceSelect = document.getElementById('sourceSelect')
                selectedDeviceId = videoInputDevices[0].deviceId
                if (videoInputDevices.length > 1) {
                    videoInputDevices.forEach((element) => {
                        const sourceOption = document.createElement('option')
                        sourceOption.text = element.label
                        sourceOption.value = element.deviceId
                        sourceSelect.appendChild(sourceOption)
                    })

                    sourceSelect.onchange = () => {
                        selectedDeviceId = sourceSelect.value;
                    }

                    const sourceSelectPanel = document.getElementById('sourceSelectPanel')
                    sourceSelectPanel.style.display = 'block'
                }

                document.getElementById('startButton').addEventListener('click', () => {
                    codeReader.decodeOnceFromVideoDevice(selectedDeviceId, 'video').then((result) => {
                        console.log(result)
                        document.getElementById('result').textContent = result.text
                        barcode = result.text;
                        console.log(barcode);
                        var grid = $("#grid");
                        grid.data("kendoGrid").dataSource.read();

                    }).catch((err) => {
                        console.error(err)
                        document.getElementById('result').textContent = err
                    })
                    console.log(`Started continous decode from camera with id ${selectedDeviceId}`)
                })

                document.getElementById('resetButton').addEventListener('click', () => {
                    document.getElementById('result').textContent = '';
                    codeReader.reset();
                    console.log('Reset.')
                })

            })
            .catch((err) => {
                console.error(err)
            })

      var dataSource = new kendo.data.DataSource({
          transport: {
              read: onRead
          },
          schema: {
              model: {
                  id: "LPN",
                  fields: {
                          LPN: {type: "string", editable: false}, // number - string - date
                          TIPO: {
                              type: "number",
                              validation: {
                                  min: 1,
                                  max: 3
                              }
                          },
                          FECHA: {type: "date"}, // number - string - date
                          SYSFECHA: {type: "date", editable: false}
                      }
              }
          },
          pageSize: 100
      });

      var ventana_importar = $("#POPUP_Importar");
      ventana_importar.kendoWindow({
          width: "300px",
          title: "Importar Archivo Excel",
          visible: false,
          actions: [
              "Close"
          ]
      }).data("kendoWindow").center();

      $("#import_form").on('submit' ,function(e){
          excel = new FormData(this);
          console.log(excel);
          e.preventDefault();
          $.ajax({
              type: "POST",
              url: baseURL + 'lector/cargar',
              dataType: 'json',
              data: excel,
              contentType: false,
              cache: false,
              processData: false,
              success: function(result){
                  if(result.length > 0){
                      e.success(result);
                  }
              },
              error: function(result){
                  alert(JSON.stringify(result));
              }
          });
      });

      $("#files").kendoUpload({
          multiple: false
      });

      $("#importarEX").click(function(){
          var POPUPImportar = $("#POPUP_Importar");
          POPUPImportar.data("kendoWindow").open();
      });

      $("#importar").click(function(){
          var popupfactor = $("#POPUP_Importar");
          popupfactor.data("kendoWindow").close(); 
      });

      $("#grid").kendoGrid({
          autoBind: false,
          dataSource: dataSource,
          width: "100%",
          sortable: true, 
          filterable: true,
          scrollable: true,
          pageable: {
                      refresh: true,
                      pageSizes: true,
                      buttonCount: 5
          },
          columns: [
              {field: "BODEGA",title: "BODEGA",width: 60, filterable:false},
              {field: "PKT",title: "PKT",width:100, filterable:false},
              {field: "BATCHNBR",title: "BATCH NBR",width:110,filterable: false},
              {field: "LPN",title: "LPN",width: 135,filterable: false},
              {field: "OLA",title: "OLA",width:100,filterable: false},
              {field: "DESC_OLA",title: "DESC OLA",width:70,filterable: false},
              {field: "SKU",title: "SKU",width:70,filterable: false},
              {field: "SKU_DESC",title: "SKU DESC",width:70,filterable: false},
              {field: "CANT",title: "CANT",width:40,filterable: false},
              {field: "FECHA_OLA",title: "FECHA OLA",width:70,filterable: false},
              {field: "USUARIO",title: "USUARIO",width:70,filterable: false},
              {field: "DISP_CASE_PICK",title: "CASE PICK",width:60,filterable: false},
              {field: "DISP_ACTIVO",title: "ACTIVO",width:60,filterable: false},
              {field: "RESERVA",title: "RESERVA",width:60,filterable: false},
              {field: "PP",title: "PP",width:40,filterable: false},
              {field: "TOTAL",title: "TOTAL",width:70,filterable: false}
          ]
      });

      function onRead(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'lector/buscar',
            dataType: 'json',
            data: {barcode: barcode},
            success: function(result){
                if(result.length > 0){
                    e.success(result);
                }
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
      }
    })

  
</script>-->
<style type="text/css">
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
<script src="<?php echo base_url();?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url();?>assets/js/LectorCUD/Faltantes/faltantes.js?n=4"></script>
<script src="<?php echo base_url();?>assets/js/LectorCUD/importarExcel.js?n=3"></script>
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
</body>
</html>
