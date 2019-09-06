<!DOCTYPE html>
<html>
<head>
    <title></title>
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
   
</div>
<span id="popupNotification"></span>
<script type='text/javascript' src="<?php echo base_url();?>assets/js/articulolocn.js"></script>
<script type="text/javascript">
    var baseURL= "<?php echo base_url();?>";
</script>
</body>
</html>