<style>
        /* Custom CSS to move user information upwards */
        .navbar .navbar-nav .nav-item .nav-link {
            margin-top: -32px; /* Adjust this value as needed to move it up */
        }
    </style>
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <!-- <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button> -->

    <!-- Nav Item - User Information -->
    <ul class="navbar-nav ml-auto"> <!-- Pushes this content to the right -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <!-- <a href="#" class="btn btn-outline-primary btn-sm"> -->
                            <!-- <i class="ti-user"></i> Hi, <?php echo htmlspecialchars($_SESSION['username']); ?> -->
                        </a>
                        <a href="../logout.php" class="btn btn-outline-danger btn-sm ml-2">
                            <i class="ti-power-off"></i> Logout
                        </a>
                    <?php else : ?>
                        <a href="../login.php" class="btn btn-outline-success btn-sm">
                            <i class="ti-user"></i> Login
                        </a>
                    <?php endif; ?>
                </span>
                <!-- <img class="img-profile rounded-circle"
                    src="img/undraw_profile.svg"> -->
            </a>
           
        </li>
    </ul>
</nav>
<!-- End of Topbar -->
