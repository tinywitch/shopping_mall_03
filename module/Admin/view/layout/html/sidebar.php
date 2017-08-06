<div class="side-menu">
    
    <nav class="navbar navbar-default" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <div class="brand-wrapper">
            <!-- Hamburger -->
            <button type="button" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Brand -->
            <div class="brand-name-wrapper">
                <a class="navbar-brand" href="#">
                    Admin
                </a>
            </div>

            <!-- Search -->
            <a data-toggle="collapse" href="#search" class="btn btn-default" id="search-trigger">
                <span class="glyphicon glyphicon-search"></span>
            </a>

            <!-- Search body -->
            <div id="search" class="panel-collapse collapse">
                <div class="panel-body">
                    <form class="navbar-form" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default "><span class="glyphicon glyphicon-ok"></span></button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Menu -->
        <div class="side-menu-container">
            <ul class="nav navbar-nav">
                <!-- Dropdown-->
                <li class="panel panel-default" id="dropdown">
                    <a href="<?= $this->url('users', ['action'=>'list']); ?>">
                        <span class="glyphicon glyphicon-user"></span> User 
                    </a>
                </li>
                <li class="panel panel-default" id="dropdown">
                    <a href="<?= $this->url('categories', ['action'=>'index']); ?>">
                        <span class="glyphicon glyphicon-list"></span> Category <span class="caret"></span>
                    </a>            
                </li>
                <li class="panel panel-default" id="dropdown">
                    <a href="<?= $this->url('products', ['action'=>'list']); ?>">
                        <span class="glyphicon glyphicon-heart-empty"></span> Product 
                    </a>
                </li>
                <li><a href="#"><span class="glyphicon glyphicon-signal"></span> Link</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
    
</div>
