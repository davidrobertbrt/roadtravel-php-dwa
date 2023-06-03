<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">RoadTravel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo $urlPath . 'home/index'?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlPath . 'booking/index'?>">Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlPath . 'tickets/index'?>">Tickets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlPath . 'bus/index'?>">Bus list</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlPath . 'trip/index'?>">Trip</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlPath . 'discount/index'?>">Discounts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlPath . 'location/index'?>">Locations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlPath . 'contact/index'?>">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlPath . 'user/index'?>">Users</a>
                </li>
                <li class="nav-item">

                </li>
                <li class="nav-item">
                   
                </li>
                <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Conectat ca...
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="./../profile/index">Edit profile</a>
                        <a class="dropdown-item" href="<?php echo $urlPath . 'logout/process'?>">Disconnect</a>
                    </div>
                </li>
                </ul>
            </ul>
        </div>
</nav>