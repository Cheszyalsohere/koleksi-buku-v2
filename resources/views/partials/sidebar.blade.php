<!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="nav-profile-image">
                  <img src="assets/images/faces/face1.jpg" alt="profile" />
                  <span class="login-status online"></span>
                  <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2">David Grey. H</span>
                  <span class="text-secondary text-small">Project Manager</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.html">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Basic UI Elements</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="pages/ui-features/dropdowns.html">Dropdowns</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="pages/ui-features/typography.html">Typography</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
                <span class="menu-title">Icons</span>
                <i class="mdi mdi-contacts menu-icon"></i>
              </a>
              <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="pages/icons/font-awesome.html">Font Awesome</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#forms" aria-expanded="false" aria-controls="forms">
                <span class="menu-title">Forms</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
              </a>
              <div class="collapse" id="forms">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="pages/forms/basic_elements.html">Form Elements</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
                <span class="menu-title">Charts</span>
                <i class="mdi mdi-chart-bar menu-icon"></i>
              </a>
              <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <span class="menu-title">Tables</span>
                <i class="mdi mdi-table-large menu-icon"></i>
              </a>
              <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <span class="menu-title">User Pages</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-lock menu-icon"></i>
              </a>
              <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="pages/samples/blank-page.html"> Blank Page </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="pages/samples/login.html"> Login </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="pages/samples/register.html"> Register </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="pages/samples/error-404.html"> 404 </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="pages/samples/error-500.html"> 500 </a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="docs/documentation.html" target="_blank">
                <span class="menu-title">Documentation</span>
                <i class="mdi mdi-file-document-box menu-icon"></i>
              </a>
            </li>
            {{-- Week 1: Buku & Kategori --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->is('buku*') || request()->is('kategori*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#week1" aria-expanded="{{ request()->is('buku*') || request()->is('kategori*') ? 'true' : 'false' }}" aria-controls="week1">
                <span class="menu-title">Week 1</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-numeric-1-box menu-icon"></i>
              </a>
              <div class="collapse {{ request()->is('buku*') || request()->is('kategori*') ? 'show' : '' }}" id="week1">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('buku*') ? 'active' : '' }}" href="{{ route('buku.index') }}">Buku</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">Kategori</a>
                  </li>
                </ul>
              </div>
            </li>

            {{-- Week 2: Sertifikat & Undangan --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->is('pdf-sertifikat*') || request()->is('pdf-undangan*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#week2" aria-expanded="{{ request()->is('pdf-sertifikat*') || request()->is('pdf-undangan*') ? 'true' : 'false' }}" aria-controls="week2">
                <span class="menu-title">Week 2</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-numeric-2-box menu-icon"></i>
              </a>
              <div class="collapse {{ request()->is('pdf-sertifikat*') || request()->is('pdf-undangan*') ? 'show' : '' }}" id="week2">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('pdf-sertifikat*') ? 'active' : '' }}" href="{{ url('/pdf-sertifikat') }}">Sertifikat</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('pdf-undangan*') ? 'active' : '' }}" href="{{ url('/pdf-undangan') }}">Undangan</a>
                  </li>
                </ul>
              </div>
            </li>

            {{-- Week 3: Barang --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->is('barang*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#week3" aria-expanded="{{ request()->is('barang*') ? 'true' : 'false' }}" aria-controls="week3">
                <span class="menu-title">Week 3</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-numeric-3-box menu-icon"></i>
              </a>
              <div class="collapse {{ request()->is('barang*') ? 'show' : '' }}" id="week3">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('barang*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Barang</a>
                  </li>
                </ul>
              </div>
            </li>

            {{-- Week 4: Tugas JavaScript & jQuery --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->is('table-biasa*') || request()->is('datatables*') || request()->is('select*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#week4" aria-expanded="{{ request()->is('table-biasa*') || request()->is('datatables*') || request()->is('select*') ? 'true' : 'false' }}" aria-controls="week4">
                <span class="menu-title">Week 4</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-numeric-4-box menu-icon"></i>
              </a>
              <div class="collapse {{ request()->is('table-biasa*') || request()->is('datatables*') || request()->is('select*') ? 'show' : '' }}" id="week4">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('table-biasa') ? 'active' : '' }}" href="{{ route('table-biasa') }}">Table Biasa</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('datatables') ? 'active' : '' }}" href="{{ route('datatables') }}">DataTables</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('select') ? 'active' : '' }}" href="{{ route('select') }}">Select vs Select2</a>
                  </li>
                </ul>
              </div>
            </li>

            {{-- Week 5: AJAX --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->is('wilayah-jquery*') || request()->is('wilayah-axios*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#week5" aria-expanded="{{ request()->is('wilayah-jquery*') || request()->is('wilayah-axios*') ? 'true' : 'false' }}" aria-controls="week5">
                <span class="menu-title">Week 5</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-numeric-5-box menu-icon"></i>
              </a>
              <div class="collapse {{ request()->is('wilayah-jquery*') || request()->is('wilayah-axios*') ? 'show' : '' }}" id="week5">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('wilayah-jquery') ? 'active' : '' }}" href="{{ route('wilayah-jquery') }}">Wilayah (jQuery)</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('wilayah-axios') ? 'active' : '' }}" href="{{ route('wilayah-axios') }}">Wilayah (Axios)</a>
                  </li>
                </ul>
              </div>
            </li>
            {{-- Week 6: Payment Gateway --}}
            <li class="nav-item">
              <a class="nav-link collapsed" data-bs-toggle="collapse" href="#week6" aria-expanded="false" aria-controls="week6">
                <span class="menu-title">Week 6</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-numeric-6-box menu-icon"></i>
              </a>
              <div class="collapse" id="week6">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('customer.order') }}" target="_blank">Pesan Menu (Customer)</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('vendor.login') }}" target="_blank">Kantin Online (Vendor)</a>
                  </li>
                </ul>
              </div>
            </li>
            {{-- Week 7: Barcode, QR Code & Kamera --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->is('barang*') || request()->is('customer-manage*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#week7" aria-expanded="{{ request()->is('barang*') || request()->is('customer-manage*') ? 'true' : 'false' }}" aria-controls="week7">
                <span class="menu-title">Week 7</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-numeric-7-box menu-icon"></i>
              </a>
              <div class="collapse {{ request()->is('barang*') || request()->is('customer-manage*') ? 'show' : '' }}" id="week7">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('barang*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Barcode (Tag Harga)</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('customer.order') }}" target="_blank">QR Code (Pesanan)</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('customer-manage') ? 'active' : '' }}" href="{{ route('customer-manage.index') }}">Data Customer</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('customer-manage/create1') ? 'active' : '' }}" href="{{ route('customer-manage.create1') }}">Tambah Customer 1</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('customer-manage/create2') ? 'active' : '' }}" href="{{ route('customer-manage.create2') }}">Tambah Customer 2</a>
                  </li>
                </ul>
              </div>
            </li>
            {{-- Week 9: Geolocation - Kunjungan Toko --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->is('toko*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#week9geo" aria-expanded="{{ request()->is('toko*') ? 'true' : 'false' }}" aria-controls="week9geo">
                <span class="menu-title">Week 9 — Geolocation</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-map-marker-radius menu-icon"></i>
              </a>
              <div class="collapse {{ request()->is('toko*') ? 'show' : '' }}" id="week9geo">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('toko') ? 'active' : '' }}" href="{{ route('toko.index') }}">Kunjungan Toko</a>
                  </li>
                </ul>
              </div>
            </li>
            {{-- Week 11: NFC Absensi --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->is('nfc*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#week11" aria-expanded="{{ request()->is('nfc*') ? 'true' : 'false' }}" aria-controls="week11">
                <span class="menu-title">Week 11 — NFC</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-nfc menu-icon"></i>
              </a>
              <div class="collapse {{ request()->is('nfc*') ? 'show' : '' }}" id="week11">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('nfc.scanner') }}" target="_blank">Scanner NFC (HP)</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('nfc/mahasiswa') ? 'active' : '' }}" href="{{ route('nfc.mahasiswa') }}">Data Mahasiswa</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('nfc/riwayat') ? 'active' : '' }}" href="{{ route('nfc.riwayat') }}">Riwayat Absensi</a>
                  </li>
                </ul>
              </div>
            </li>
            {{-- Week 8: Barcode & QR Code Reader --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->is('barang/scanner*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#week8" aria-expanded="{{ request()->is('barang/scanner*') ? 'true' : 'false' }}" aria-controls="week8">
                <span class="menu-title">Week 8</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-numeric-8-box menu-icon"></i>
              </a>
              <div class="collapse {{ request()->is('barang/scanner*') ? 'show' : '' }}" id="week8">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link {{ request()->is('barang/scanner') ? 'active' : '' }}" href="{{ route('barang.scanner') }}">Barcode Scanner</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('vendor.login') }}" target="_blank">QR Scanner (Vendor)</a>
                  </li>
                </ul>
              </div>
            </li>
            {{-- Week 10: Sistem Antrian Real-Time (SSE) --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->is('antrian*') ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#week10" aria-expanded="{{ request()->is('antrian*') ? 'true' : 'false' }}" aria-controls="week10">
                <span class="menu-title">Week 10 — Antrian SSE</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-bell-ring menu-icon"></i>
              </a>
              <div class="collapse {{ request()->is('antrian*') ? 'show' : '' }}" id="week10">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('antrian.guest') }}" target="_blank">Daftar Antrian (Guest)</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('antrian.admin') }}" target="_blank">Dashboard Admin</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('antrian.papan') }}" target="_blank">Papan Antrian (Display)</a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </nav>
