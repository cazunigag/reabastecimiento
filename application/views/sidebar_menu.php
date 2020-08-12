
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
            <!--<<li><a href="<?php echo site_url('asignacionPedido');?>"><i class="fa fa-table"></i> Asignacion de Pedidos</a></li>-->
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
           <li><a href="<?php echo site_url('workarea');?>"><i class="fa fa-cube"></i> Configurar Work Area</a></li>
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
            </li>
          </ul>
        </li>
        <li>
          <a href="<?php echo site_url('centroAlertas');?>">
            <i class="fa fa-sitemap"></i></i><span> Sistemas Ripley</span>
          </a>
        </li>
        <!--<li>
          <a href="<?php echo site_url('seteoAttr');?>">
            <i class="fa fa-check-square-o"></i></i><span>Seteo Atributos Logisticos</span>
          </a>
        </li>-->
        <li class="treeview">
          <a href="#">
            <i class="fa fa-calendar"></i>
            <span>LPNs Con Demora</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="<?php echo site_url('LPNDemora');?>">
                <i class="fa fa-calendar"></i></i><span>Calendario Demora</span>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url('CambioDemora');?>">
                <i class="fa fa-edit"></i></i><span>Cambio Demora LPN</span>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url('CambioDemoraCarton');?>">
                <i class="fa fa-edit"></i></i><span>Cambio Demora Carton</span>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url('CambioDemoraLOCN');?>">
                <i class="fa fa-edit"></i></i><span>Cambio Demora Ubicacion</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-calendar"></i>
            <span> Diferencia Inventario</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="<?php echo site_url('diferenciaInventario');?>">
                <i class="fa fa-line-chart"></i></i><span> Pix 605</span>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url('PKTCancelados');?>">
                <i class="fa fa-table"></i></i><span> PKT Cancelados</span>
              </a>
            </li> 
          </ul>
        </li>
        <li>
          <a href="<?php echo site_url('AseguramientoCalidad');?>">
            <i class="fa fa-check-circle-o"></i></i><span> Aseguramiento Calidad</span>
          </a>
        </li>
        <li>
          <a href="<?php echo site_url('descuadraturainv');?>">
            <i class="fa fa-calculator"></i></i><span> Descuadratura Inventario</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>