
<?php 
    if($this->session->has_userdata('name')){
        $this->session->sess_destroy();
    }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reabastecimiento | Log in</title>
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
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><h2 class="login-box-msg"><B>REDEX</B></h2></p>
      <div class="form-group has-feedback" id="user">
        <input type="text" class="form-control" placeholder="Rut" name="username" id="username" required="">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        <span class="help-block" id="helpuser"></span>
      </div>
      <div class="form-group has-feedback" id="pass">
        <input type="password" class="form-control" placeholder="Contraseña" name="password" id="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <span class="help-block" id="helppass"></span>
      </div>
      <div class="row">
        <div class="callout callout-danger" id="errmessage">
          <p id="text"></p>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-4">
          <button type="button"  name="submit" id="submit" class="btn btn-primary btn-flat">Ingresar<i id="iconbtn"></i></button>
        </div>
        <!-- /.col -->
      </div>
     </form>
     <div class="form-group has-feedback">
      <div style="margin-top: 30px"></div>
      <p class="login-box-msg"><img src="<?php echo base_url();?>assets/img/logo.png" height="70" width="170"/></p>
     </div>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo base_url();?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url();?>assets/plugins/iCheck/icheck.min.js"></script>

<script src="<?php echo base_url();?>assets/js/Login/login.js?n=1"></script>
<script>
  var baseURL= "<?php echo base_url();?>";
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
