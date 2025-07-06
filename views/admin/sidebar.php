<div class="sidebar">
  <div class="user-panel mt-3 pb-3 d-flex">
    <div class="info">
     <a href="#" class="d-block">👤 <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></a>


    </div>
  </div>

  <nav>
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
      <li class="nav-item">
        <a href="/nhahang/views/admin/dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
          <i class="nav-icon fas fa-home"></i>
          <p>Trang chủ</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="/nhahang/views/admin/reservations.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'reservations.php' ? 'active' : '' ?>">
          <i class="nav-icon fas fa-calendar-check"></i>
          <p>Quản lý đặt bàn</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="/nhahang/views/admin/tables.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'tables.php' ? 'active' : '' ?>">
          <i class="nav-icon fas fa-chair"></i>
          <p>Quản lý bàn</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="/nhahang/views/admin/users.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : '' ?>">
          <i class="nav-icon fas fa-users"></i>
          <p>Quản lý người dùng</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="/nhahang/views/admin/dishes.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dishes.php' ? 'active' : '' ?>">
          <i class="nav-icon fas fa-utensils"></i>
          <p>Quản lý món ăn</p>
        </a>
      </li>
    </ul>
  </nav>
</div>
