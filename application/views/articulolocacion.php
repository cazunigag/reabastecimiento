<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.common.min.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.default.min.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/telerik/styles/kendo.default.mobile.min.css" />

    <script src="<?php echo base_url();?>assets/telerik/js/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/telerik/js/jszip.min.js"></script>
    <script src="<?php echo base_url();?>assets/telerik/js/kendo.all.min.js"></script>
    

</head>
<body>
<div id="example">
    <div id="spreadsheet" style="width: 100%;"></div>
    <div id="POPUP_filtrar">
        <div class="col-md-6 mb-3">
            <label for="Codtemporada" class="required">Rango de Fechas:</label>
            <div style=" margin-top: 10px"></div>
            <input id="DPFechaIni" name="DPFechaIni" placeholder="Desde" title="DPFechaIni" style="width: 100%"/>
            <div style=" margin-top: 10px"></div>
            <input id="DPFechaFin" name="DPFechaFin" placeholder="Hasta" title="DPFechaFin" style="width: 100%" />
            <div style=" margin-top: 10px"></div>
            <span class="k-invalid-msg" data-for="Codtemporada"></span>
        </div>

        <div class="col-md-6 mb-3">
            <button class="k-button k-primary" id="btn_filtrar" name="btn_filtrar" >Filtrar</button>
        </div>
    </div>
    <div id="POPUP_importar">
        <form method="post" id="import_form" enctype="multipart/form-data">
            <input name="files" id="files" type="file" aria-label="files" accept=".xls, .xlsx"/>
            <p style="padding-top: 1em; text-align: right">
                <button type="submit" id="importar" name="importar" class="k-button k-primary">Importar</button>
            </p>
        </form>    
    </div>
   
</div>
<span id="popupNotification"></span>
<script type='text/javascript' src="<?php echo base_url();?>assets/js/articulolocn.js"></script>
<script type="text/javascript">
    var baseURL= "<?php echo base_url();?>";
</script>
<style type="text/css">
    .k-spreadsheet-cell.k-state-disabled div {
      color: black;
      font-weight: bold;
    }
</style>
</body>
</html>