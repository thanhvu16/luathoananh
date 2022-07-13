<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <!-- search form -->
<!--        <form action="#" method="get" class="sidebar-form">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                    <i class="fa fa-search"></i>
                  </button>
                </span>
          </div>
        </form>-->
    <?php
    use cms\models\Menu;
    use cms\widgets\MenuLte;
    use yii\caching\FileDependency;
    use common\components\CFunction;
    
    echo MenuLte::widget([
        'items' => Menu::getCategoryMenu()
    ]);
    ?>
    
    </section>
  <!-- /.sidebar -->
</aside>