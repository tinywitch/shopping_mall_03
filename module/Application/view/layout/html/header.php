<nav class="top-bar animate-dropdown">
    <div class="container">
        <div class="col-xs-12 col-sm-6 no-margin">
            <ul>
                <li><a href="/">trang chủ</a></li>
                <?php if ($this->user == null) { ?>         
                    <li><a href="/login">đăng nhập</a></li>
                    <li><a href="/register">đăng ký</a></li>
                <?php } else { ?>
                    <li><a href="/user/view"><?php echo $this->user['name']; ?></a></li>
                    <li><a href="/logout">Log out</a></li>
                <?php } ?>
            </ul>
        </div><!-- /.col -->
    </div><!-- /.container -->
</nav><!-- /.top-bar -->
