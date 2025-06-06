<div class="nav-item dropdown text-end col-md-3">
    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="bi bi-person-circle" style="font-size: 2rem; color: <?php echo(getAvatarColor()) ?>;"></i>
    </a>
    <div class="dropdown-menu text-small dropdown-menu-end">
        <h6 class="dropdown-header"><?php echo(getUserFullName()) ?>
            <?php
              if (isAdmin()) {
                  echo '<span class="badge text-bg-danger">Admin</span>';
              }
              ?>
        </h6>
        <a class="dropdown-item" href="settings.php">Settings</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="logout.php">Sign out</a>
    </div>
</div>
