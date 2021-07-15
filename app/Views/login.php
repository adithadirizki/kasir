<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('template/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- Sweet Alert -->
  <link rel="stylesheet" href="<?= base_url('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('template/dist/css/adminlte.min.css') ?>">
  <!-- Google Font: Source Sans Pro -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="<?= base_url() ?>"><b>Kasir</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <div id="notif-alert"></div>
        <form id="form_login">
          <?= csrf_field(); ?>
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Username" name="username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
            <span class="error invalid-feedback"></span>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            <span class="error invalid-feedback"></span>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= base_url('template/plugins/jquery/jquery.min.js') ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('template/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- Sweet Alert -->
  <script src="<?= base_url('template/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url('template/dist/js/adminlte.min.js') ?>"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#form_login').submit(function() {
        if ($('[name=username]').val().length == 0) {
          $('[name=username]').addClass('is-invalid');
          $('[name=username]').parents().children('.error').text('Username harus diisi')
          return false
        } else {
          $('[name=username]').removeClass('is-invalid');
          $('[name=username]').parents().children('.error').text('')
        }
        if ($('[name=password]').val().length == 0) {
          $('[name=password]').addClass('is-invalid');
          $('[name=password]').parents().children('.error').text('Password harus diisi')
          return false
        } else {
          $('[name=password]').removeClass('is-invalid');
          $('[name=password]').parents().children('.error').text('')
        }
        var data = $('#form_login').serialize()
        $.ajax({
          url: 'login',
          type: 'POST',
          data: data,
          beforeSend: function() {
            // 
          },
          success: function(hasil) {
            var obj = JSON.parse(hasil)
            if (obj.error == 1) {
              Swal.fire({
                title: "Gagal",
                text: obj.msg,
                icon: "warning",
                showConfirmButton: false,
                timer: 2000
              })
            } else if (obj.error == 0) {
              Swal.fire({
                title: "Berhasil",
                text: obj.msg,
                icon: "success",
                showConfirmButton: false,
                timer: 2000
              }).then(function() {
                window.location.href = "<?= base_url() ?>"
              })
            }
          }
        })
        return false
      })
    })
  </script>

</body>

</html>