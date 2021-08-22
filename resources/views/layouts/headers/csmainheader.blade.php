@auth('customer')
  @php
    $cs = auth()->guard('customer')->user();
    if(preg_match('/^\+\d\d(\d{3})(\d{4})(\d{4})$/',$cs->cst_no_telp,$matches)) {
      $cs->cst_no_telp = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
    }
  @endphp
@endauth

<!-- Header -->
<header class="ftco-section">
  <div class="container d-flex justify-content-center">
    <nav class="navbar navbar-expand-lg ftco-navbar-light fly-high">
      <div class="container">
        <a class="navbar-brand" href="index.html"><img src="{{ asset('assets') }}/images/logo.png" alt="logo.png" itemprop="image" /></a>
        <div class="collapse navbar-collapse" id="ftco-nav">
          <ul class="navbar-nav ml-auto mr-md-3">
            <li class="nav-item home active">
              <a href="#" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
              <a href="#about-section" class="nav-link">About</a>
            </li>
            <li class="nav-item">
              <a href="#promo-section" class="nav-link">Promo</a>
            </li>
            <li class="nav-item">
              <a href="#venue-section" class="nav-link">Venue</a>
            </li>
            <li class="nav-item">
              <a href="#produk-section" class="nav-link">Products</a>
            </li>
            <li class="nav-item">
              <a href="#kontak-section" class="nav-link">Contact</a>
            </li>
            {{-- <li class="notif-btn mx-3 pt-3" style="display: inline-block !important;">
              <a href="#"><img src="{{ asset('assets') }}/images/notif.svg" alt="notif" itemprop="image"></a>
            </li> --}}
            @auth('customer')
              <li class="nav-item" style="margin-top: 17px;">
                <a class="btn btn-yellow btn-sm text-white shadow" href="{{ route('myorder') }}" style="font-weight: bold; text-shadow: 1px 1px rgb(0 0 0 / 20%);">MyOrder</a>
              </li>
              <li class="login-btn">
                <button type="button" class="btn btn-danger btn-sm" id="logout-btn">Logout</button>
              </li>
            @endauth
            @guest('customer')
              <li class="login-btn">
                <button type="button" class="btn btn-primary btn-sm shadow" data-bs-toggle="modal" data-bs-target="#login-modal">LOGIN</button>
              </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>
  </div>
</header>

<!-- Header Sticky -->
<div class="" id="tetap">
  <nav class="navbar fixed-top navbar-expand-lg ftco-navbar-light" style="border-radius: 0;">
    <div class="container">
      <a class="navbar-brand" href="index.html"><img src="{{ asset('assets') }}/images/logo.png" alt="logo.png" itemprop="image" /></a>
      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav m-auto">
          <li class="nav-item active">
            <a href="#" class="nav-link">Home</a>
          </li>
          <li class="nav-item">
            <a href="#about-section" class="nav-link">About</a>
          </li>
          <li class="nav-item">
            <a href="#promo-section" class="nav-link">Promo</a>
          </li>
          <li class="nav-item">
            <a href="#venue-section" class="nav-link">Venue</a>
          </li>
          <li class="nav-item">
            <a href="#produk-section" class="nav-link">Products</a>
          </li>
          <li class="nav-item">
            <a href="#kontak-section" class="nav-link">Contact</a>
          </li>
          {{-- <li class="notif-btn mx-3 pt-3" style="display: inline-block !important;">
            <a href="#"><img src="{{ asset('assets') }}/images/notif.svg" alt="notif" itemprop="image"></a>
          </li> --}}
          @auth('customer')
            <li class="nav-item" style="margin-top: 17px;">
              <a class="btn btn-yellow btn-sm text-white shadow" href="{{ route('myorder') }}" style="font-weight: bold; text-shadow: 1px 1px rgb(0 0 0 / 20%);">MyOrder</a>
            </li>
            <li class="login-btn">
              <button type="button" class="btn btn-danger btn-sm" onclick="logout()">Logout</button>
            </li>
          @endauth
          @guest('customer')
            <li class="login-btn">
              <button type="button" class="btn btn-primary btn-sm shadow" data-bs-toggle="modal" data-bs-target="#login-modal">LOGIN</button>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>
</div>

<!-- Header Responsive -->
<div class="rspn-hdr" id="respon-header">
  <div class="lg-mn">
    <div class="logo">
      <a href="#" title="Logo" itemprop="url">
        <img src="{{ asset('assets') }}/images/logo.png" alt="logo.png" itemprop="image">
      </a>
    </div>
    <span class="rspn-mnu-btn rounded" style="margin-right: 1rem;"><i class="fa fa-align-justify"></i></span>
  </div>
  <div class="rsnp-mnu" id="respon-menu">
    <span class="rspn-mnu-cls bg-danger rounded m-1"><i class="fa fa-times"></i></span>
    <ul>
      @auth('customer')
        <li>
          <div class="card bg-dark" style="flex-direction: row !important;">
            <i class="fa fa-user" style="font-size: 2rem !important;"></i>
            <span style="font-size: 1.2rem !important;">&nbsp;{{ $cs->cst_name }}</span>
          </div>
        </li>
        <li><a class="btn btn-sm btn-yellow text-dark" href="{{ route('myorder') }}" style="font-weight: bold; text-shadow: 1px 1px rgb(0 0 0 / 20%);">My Order</a></li>
      @endauth
      <li><a href="#" title="" itemprop="url">Home</a>
      </li>
      <li><a href="#about-section" title="" itemprop="url">About</a>
      </li>
      <li><a href="#promo-section" title="" itemprop="url">Promo</a>
      </li>
      <li><a href="#venue-section" title="" itemprop="url">Venue</a>
      </li>
      <li><a href="#produk-section" title="" itemprop="url">Product</a>
      </li>
      <li><a href="#kontak-section" title="" itemprop="url">Contact Us</a>
      </li>
      <li class="notif-btn">
        <a href="#">Notification<i class="fa fa-circle notif-dot" aria-hidden="true"></i>
        </a>
      </li>
      @auth('customer')
        <li><a class="btn btn-sm btn-danger" href="#" data-bs-toggle="modal" id="logout-btn-mobile" itemprop="url">Logout</a></li>
      @endauth
      @guest('customer')
        <li><a class="rspn-login-btn btn btn-sm btn-primary" href="#login-modal" title="" data-bs-toggle="modal" data-bs-target="#login-modal" itemprop="url">Login</a>
        </li>
        <li><a class="rspn-regis-btn btn btn-sm btn-success" href="#regis-modal" title="" data-bs-toggle="modal" data-bs-target="#regis-modal" itemprop="url">Sign Up</a>
        </li>
      @endguest
    </ul>
  </div>
</div>

<!-- Modal login -->
<div class="modal fade" id="login-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body pb-5">
        <button type="button" class="btn-close position-absolute top-0 start-100 translate-middle" data-bs-dismiss="modal" aria-label="Close">
          &times;
        </button>
        <p>Hai! Selamat Datang</p>
        <h2>Login</h2>
          <form id="login-form" method="POST">
          @csrf
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="input-login-email" placeholder="name@example.com" required>
            <label for="input-login-email">Email</label>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" id="input-login-password" placeholder="Password" required>
            <label for="input-login-password">Password</label>
            <a onclick="show_pass_login()" class="position-absolute top-50 end-0 translate-middle-y show-pass"><i
                class="fa fa-eye-slash position-absolute top-50 start-50 translate-middle" aria-hidden="true"></i></a>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="input-login-remember">
            <label class="form-check-label" for="input-login-remember"> Remember Me </label>
          </div>
          <div class="row mt-4">
            <div class="col d-flex justify-content-center media-phone">
              <button type="button" class="thm-btn btn-grey mb-10px" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#regis-modal">
                Sign&nbsp;Up
              </button>
            </div>
            <div class="col d-flex justify-content-center">
              <button class="thm-btn" id="input-login-submit" type="submit">&nbsp;Login&nbsp;</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Register -->
<div class="modal fade" id="regis-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close position-absolute top-0 start-100 translate-middle" data-bs-dismiss="modal" aria-label="Close">
          &times;
        </button>
        <p>Mari Bergabung</p>
        <h2>Sign Up</h2>
        <form method="POST" enctype="multipart/form-data" id="regis-form">
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="input-regis-email" placeholder="Email" required>
            <label for="input-regis-email">Email</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="input-regis-password" placeholder="Password" required>
            <label for="input-regis-password">Password</label>
            <a onclick="show_pass_regis('input-regis-password')" class="position-absolute top-50 end-0 translate-middle-y show-pass">
              <i class="fa fa-eye-slash position-absolute top-50 start-50 translate-middle" aria-hidden="true"></i>
            </a>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="input-regis-confirm-password" placeholder="Password" required>
            <label for="input-regis-password">Confirm Password</label>
            <a onclick="show_pass_regis('input-regis-confirm-password')" class="position-absolute top-50 end-0 translate-middle-y show-pass">
              <i class="fa fa-eye-slash position-absolute top-50 start-50 translate-middle" aria-hidden="true"></i>
            </a>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="input-regis-nama" placeholder="Name" required>
            <label for="input-regis-nama">Nama</label>
          </div>
          <div class="form-floating mb-3 col input-group">
            <div class="input-group-text">+62</div>
            <input type="tel" class="form-control" id="input-regis-telepon" placeholder="No.Telp/WA" required>
            <label for="input-regis-telepon">No.Telp/WA</label>
          </div>
          <div class="form-floating mb-3">
            <textarea class="form-control" id="input-regis-alamat" rows="5" placeholder="Alamat"></textarea>
            <label for="input-regis-alamat">Alamat</label>
          </div>
          <a class="link-info pb-5" data-bs-toggle="modal" data-bs-target="#login-modal" data-bs-dismiss="modal">sudah memiliki akun ?</a>
          <div class="pt-5">
            <button class="thm-btn position-absolute bottom-0 start-50 translate-middle-x" id="input-regis-submit" type="submit">
              Sign Up
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>