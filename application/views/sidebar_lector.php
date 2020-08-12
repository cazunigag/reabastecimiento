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
      <!--<li class="treeview">
        <a href="#">
          <i class="fa fa-barcode"></i>
          <span>Picking</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
        
        </ul>
      </li>-->
      <li>
        <a href="<?php echo site_url('lector');?>">
          <i class="fa fa-barcode"></i></i><span> Picking</span>
        </a>
      </li>
      <li>
        <a href="<?php echo site_url('faltantes');?>">
          <i class="fa fa-search"></i></i><span> Faltantes</span>
        </a>
      </li>
      <li>
        <a href="<?php echo site_url('asignacionManual');?>">
          <i class="fa fa-edit"></i></i><span> Asignacion Manual</span>
        </a>
      </li>
      <li>
        <a href="<?php echo site_url('devueltos');?>">
          <i class="fa fa-arrow-left"></i></i><span> Devoluciones</span>
        </a>
      </li>
      <li>
        <a href="<?php echo site_url('lector/dashboard');?>">
          <i class="fa fa-dashboard"></i></i><span> Dashboard</span>
        </a>
      </li>
      <li>
        <a href="#" id="importarEX">
          <i class="fa fa-file-excel-o"></i></i><span> Cargar Excel</span>
        </a>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>