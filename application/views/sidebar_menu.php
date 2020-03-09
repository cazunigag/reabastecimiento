<?php 
  $modulos = $this->session->userdata('modulos'); 
  $articuloLocacion = 0;
  $seteo308 = 0;
  $almacenamientolocn = 0;
  $sublineas = 0;
  $redex = 0;
  $cartontype = 0;
  $cartontypearticulo = 0;
  $centroAlertas = 0;
  $LPNDemora = 0;
  $CambioDemora = 0;
  $CambioDemoraCarton = 0;
  $CambioDemoraLOCN = 0;

  foreach ($modulos as $key) {
     if($key->MENU_NAME == "ARTICULO_LOCACION"){
       $articuloLocacion = 1;
     }
     if($key->MENU_NAME == "SETEO_DEPTO"){
       $seteo308 = 1;
     }
     if($key->MENU_NAME == "ALMACENAMIENTO_LOCN"){
       $almacenamientolocn = 1;
     }
     if($key->MENU_NAME == "MANTENEDOR_SUBLINEA"){
       $sublineas = 1;
     }
     if($key->MENU_NAME == "CENTRO_DISTRIBUCION"){
       $redex = 1;
     }
     if($key->MENU_NAME == "CARTON_TYPE_PASILLO"){
       $cartontype = 1;
     }
     if($key->MENU_NAME == "CARTON_TYPE_ARTICULO"){
       $cartontypearticulo = 1;
     }
     if($key->MENU_NAME == "CENTRO_ALERTAS_WMS" || $key->MENU_NAME == "CENTRO_ALERTAS_BT" || $key->MENU_NAME == "CENTRO_ALERTAS_PMM" || $key->MENU_NAME == "CENTRO_ALERTAS_EIS"){
       $centroAlertas = 1;
     }
     if($key->MENU_NAME == "CALENDARIO_DEMORA"){
       $LPNDemora = 1;
     }
     if($key->MENU_NAME == "CAMBIO_DEMORA_LPN"){
       $CambioDemora = 1;
     }
     if($key->MENU_NAME == "CAMBIO_DEMORA_CARTON"){
       $CambioDemoraCarton = 1;
     }
     if($key->MENU_NAME == "CAMBIO_DEMORA_UBICACION"){
       $CambioDemoraLOCN = 1;
     }
  }
  

?>
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
            <a href="<?php echo site_url('home');?>" class="logo">
              <!-- mini logo for sidebar mini 50x50 pixels -->
              <span class="logo-mini"></span>
              <!-- logo for regular state and mobile devices -->
              <span class="logo-lg"><img src="<?php echo base_url();?>assets/img/logo.png" height="60" width="170"/></span>
            </a>  
          </center>
        </li>
        <div style="margin-top: 20px; margin-bottom: 20px"></div>
        <li class="header"><b>MENU</b></li>
        <?php if($articuloLocacion > 0 || $seteo308 > 0 || $almacenamientolocn > 0 || $sublineas > 0){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-refresh"></i>
            <span>Reabastecimiento</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           <?php if($articuloLocacion > 0) {?> <li><a href="<?php echo site_url('articuloLocacion');?>"><i class="fa fa-table"></i> Ingreso Articulo-Locacion</a></li> <?php } ?> 
            <!--<<li><a href="<?php echo site_url('asignacionPedido');?>"><i class="fa fa-table"></i> Asignacion de Pedidos</a></li>-->
           <?php if($seteo308 > 0) {?> <li><a href="<?php echo site_url('seteo308');?>"><i class="fa fa-arrow-down"></i> Seteo Depto</a></li><?php } ?> 
           <?php if($almacenamientolocn > 0) {?> <li><a href="<?php echo site_url('almacenamientolocn');?>"><i class="fa fa-cube"></i> Almacenamiento Locacion</a></li><?php } ?> 
           <?php if($sublineas > 0) {?> <li><a href="<?php echo site_url('sublineas');?>"><i class="fa fa-table"></i> Mantenedor Min-Max Sublinea</a></li><?php } ?> 
          </ul>
        </li>
      <?php } ?>
       <?php if($redex > 0 || $cartontype > 0 || $cartontypearticulo > 0){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-map"></i>
            <span>Centro de Distribucion</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           <?php if($redex > 0) {?><li><a href="<?php echo site_url('redex');?>"><i class="fa fa-map"></i> Centro de Distribucion</a></li><?php } ?>
            <?php if($cartontype > 0 || $cartontypearticulo > 0){ ?>
            <li class="treeview">
              <a href="#"><i class="fa fa-cube"></i> Carton Type
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
               <?php if($cartontype > 0) {?><li><a href="<?php echo site_url('cartontype');?>"><i class="fa fa-cube"></i> Carton Type Pasillo</a></li><?php } ?>
               <?php if($cartontypearticulo > 0) {?> <li><a href="<?php echo site_url('cartontypearticulo');?>"><i class="fa fa-cube"></i> Carton Type Articulo</a></li><?php } ?>
              </ul>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>
        <?php if($centroAlertas > 0) {?>
        <li>
          <a href="<?php echo site_url('centroAlertas');?>">
            <i class="fa fa-sitemap"></i></i><span> Sistemas Ripley</span>
          </a>
        </li>
        <?php } ?>
        <!--<li>
          <a href="<?php echo site_url('seteoAttr');?>">
            <i class="fa fa-check-square-o"></i></i><span>Seteo Atributos Logisticos</span>
          </a>
        </li>-->
        <?php if($LPNDemora > 0 || $CambioDemora > 0 || $CambioDemoraCarton > 0 || $CambioDemoraLOCN > 0){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-calendar"></i>
            <span>LPNs Con Demora</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if($LPNDemora > 0) {?>
            <li>
              <a href="<?php echo site_url('LPNDemora');?>">
                <i class="fa fa-calendar"></i></i><span>Calendario Demora</span>
              </a>
            </li>
            <?php } ?>
            <?php if($CambioDemora > 0) {?>
            <li>
              <a href="<?php echo site_url('CambioDemora');?>">
                <i class="fa fa-edit"></i></i><span>Cambio Demora LPN</span>
              </a>
            </li>
            <?php } ?>
            <?php if($CambioDemoraCarton > 0) {?>
            <li>
              <a href="<?php echo site_url('CambioDemoraCarton');?>">
                <i class="fa fa-edit"></i></i><span>Cambio Demora Carton</span>
              </a>
            </li>
            <?php } ?>
            <?php if($CambioDemoraLOCN > 0) {?>
            <li>
              <a href="<?php echo site_url('CambioDemoraLOCN');?>">
                <i class="fa fa-edit"></i></i><span>Cambio Demora Ubicacion</span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>
        <li>
          <a href="<?php echo site_url('diferenciaInventario');?>">
            <i class="fa fa-line-chart"></i></i><span> Diferencia Inventario</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>