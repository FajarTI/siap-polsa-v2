<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#link-mahasiswa" aria-expanded="false"
                aria-controls="link-mahasiswa">
                <i class="icon-layout menu-icon"></i>
                <span class="menu-title">Mahasiswa</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="link-mahasiswa">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('mahasiswa.index') }}">Daftar
                            Mahasiswa</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#link-dosen" aria-expanded="false"
                aria-controls="link-dosen">
                <i class="icon-layout menu-icon"></i>
                <span class="menu-title">Dosen</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="link-dosen">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="skydash/dist/pages/ui-features/buttons.html">Daftar
                            Dosen</a></li>
                    <li class="nav-item"> <a class="nav-link" href="skydash/dist/pages/ui-features/buttons.html">Dosen
                            PA</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#link-perkuliahan" aria-expanded="false"
                aria-controls="link-perkuliahan">
                <i class="icon-layout menu-icon"></i>
                <span class="menu-title">Perkuliahan</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="link-perkuliahan">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="skydash/dist/pages/ui-features/buttons.html">Mata
                            Kuliah</a></li>
                    <li class="nav-item"> <a class="nav-link" href="skydash/dist/pages/ui-features/buttons.html">Kelas
                            Kuliah</a></li>
                    <li class="nav-item"> <a class="nav-link" href="skydash/dist/pages/ui-features/buttons.html">Jadwal
                            Kuliah</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>