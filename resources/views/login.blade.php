<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Pasar Buah 88</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('asset/vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{asset('asset/vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{asset('asset/vendors/css/vendor.bundle.base.css')}}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="{{asset('asset/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
  <link rel="stylesheet" href="{{asset('asset/vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('asset/js/select.dataTables.min.css')}}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('asset/css/vertical-layout-light/style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('asset/images/favicon.png')}}" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <center>
              <div class="brand-logo">
                <img src="{{asset('asset/images/logo88.png')}}" width="30%" class="h4 text-gray-900 mb-4">
                </div>
                <h4>Sistem Mutasi Barang</h4>
                <h6 class="font-weight-light">Masuk untuk melanjutkan</h6>
                </center>
                @if(session('error'))
                    <div class="alert alert-danger mt-3" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <form class="pt-3" action="/postlogin" method="post">
                @csrf
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" name="email" placeholder="Masukkan Email" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password"  placeholder="Masukkan Password" required>
                </div>
                <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Remember Me
                    </label>
                </div>
                <div class="mt-3">
                  <button type="submit"  class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Masuk</button>
                </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{asset('asset/vendors/js/vendor.bundle.base.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="{{asset('asset/vendors/chart.js/Chart.min.js')}}"></script>
  <script src="{{asset('asset/vendors/datatables.net/jquery.dataTables.js')}}"></script>
  <script src="{{asset('asset/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
  <script src="{{asset('asset/js/dataTables.select.min.js')}}"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{asset('asset/js/off-canvas.js')}}"></script>
  <script src="{{asset('asset/js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('asset/js/template.js')}}"></script>
  <script src="{{asset('asset/js/settings.js')}}"></script>
  <script src="{{asset('asset/js/todolist.js')}}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{asset('asset/js/dashboard.js')}}"></script>
  <script src="{{asset('asset/js/Chart.roundedBarCharts.js')}}"></script>
  <!-- End custom js for this page-->
</body>

</html>
