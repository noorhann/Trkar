<template>
      <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg custom_nav-container ">
                <a class="navbar-brand" href="index.html"><img width="250"
                        src="'/public/images/logo.svg'" alt="#" /></a>
                <div class="container d-flex justify-content-center align-items-center">

                    <div class="dropdown">
                        <button class="btn btn-outline-danger dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-expanded="false">
                            <span><i class="fa fa-car" aria-hidden="true"></i> Car Parts</span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#"> <i class="fa fa-truck" aria-hidden="true"></i>
                                    Truck parts</a></li>
                        </ul>
                    </div>


                </div>
                <input type="text" class="form-control" placeholder="Recipient's username"
                    aria-label="Enter the part number or name" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2">search</span>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <i class="fa fa-user fa-2x" aria-hidden="true"></i>
                            <span>Account</span>

                        </li>
                        <li class="nav-item">
                            <i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i>
                            <span>Cart</span>

                        </li>
                        <li class="nav-item">
                            <i class="fa fa-star fa-2x" aria-hidden="true"></i>
                            <span>Wishlist</span>

                        </li>
                        <li class="nav-item">
                            <i class="fa fa-car fa-2x" aria-hidden="true"></i>
                            <span>Wishlist</span>

                        </li>

                    </ul>
                </div>
            </nav>
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#"><i class="fa fa-list-alt" aria-hidden="true"></i>
                      All Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa fa-times-circle-o" aria-hidden="true"></i>
                      Tyres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa fa-wrench" aria-hidden="true"></i>
                      Tools</a>
                </li>
           
            </ul>
        </div>
    </header>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
