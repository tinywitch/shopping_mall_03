<div class="container">
    <div class=" col-md-12 col-xs-12 col-sm-12 filter">
        <ul>
            <li class="color">Category</li>
                <?php
                    foreach ($this->categories_for_nav_bar as $key => $child_categories) {
                        echo '<li><a href="#">';
                        echo '<span>'.$key.'</span>';
                        echo '<i class="fa fa-sort-desc" aria-hidden="true"></i>';
                        echo '<ul>';
                        foreach ($child_categories as $child_cate ) {
                            echo '<a href="#"><li  class="category">'.$child_cate.'</li></a>';
                        }
                        echo '</ul>';
                        echo '</a></li>';
                     } 
                ?>
        </ul>
    </div>    
</div>
