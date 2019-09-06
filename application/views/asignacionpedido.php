<!DOCTYPE html>
<html>
<head>
    <title>Asignacion Pedidos</title>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.common.min.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.material.min.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.material.mobile.min.css" />

    <script src="<?php echo base_url();?>assets/telerik/js/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/telerik/js/jszip.min.js"></script>
    <script src="<?php echo base_url();?>assets/telerik/js/kendo.all.min.js"></script>
    

</head>
<body>
<div id="example">
  
    <span id="actualizar_span" style="display: none"></span>
    <div id="toolbar"></div>
    <div id="grid"></div>
    <div id="POPUP_seleccionarSKU">
        
            <li>
                <div id="gridActualizar"></div>
            
            </li>
            <br/>
            <li>
                 <button id="btn_send" name="btn_send" class='k-button k-primary' >Actualizar</button>
            </li>

    </div>
    <script type='text/javascript' src="<?php echo base_url();?>assets/js/asignacionpedido.js"> 
        
    </script>
    <script type="text/javascript">
        var baseURL= "<?php echo base_url();?>";
    </script>
</div>
<span id="popupNotification"></span>
<style>
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