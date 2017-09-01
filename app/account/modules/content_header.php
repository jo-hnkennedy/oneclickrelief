<?php

//check if defined
if( !defined('modules') ) {
    die('Module not loadable ..');
}

//check for user
if( !isset( $user ) || !$user ) {
    die('No use for request ..');
}


GLOBAL $page_name;
GLOBAL $page_title;
GLOBAL $page_options;
GLOBAL $page_excerpt;
$po = $page_options;

//set description
if( !isset( $po['excerpt'] ) ) {
    $po['excerpt'] = $page_excerpt;
}
//set title
if( !isset( $po['title'] ) ) {
    $po['title'] = $page_title;
}
//set breadcrum
if( !isset( $po['breadcrumb'] ) ) {
    $po['breadcrumb'] = array();
}
?>
<section class="content-header">
    <h1>
        <?php echo $po['title']; ?>
        <small><?php echo $po['excerpt']; ?></small>
    </h1>
    <?php if( !empty( $po['actions'] ) ) { ?>
    <div class="breadcrumb">
        <?php foreach( $po['actions'] as $name => $action ) { ?>
            <div class="nav-actions m5 p5 right inline action-<?php echo $name; ?>">
            <?php if( isset( $action['data'] ) ) { ?>
                <div class="dropdown inline">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                        <?php if( isset( $action[3] ) && !is_array( $action[3]) ) { ?>
                        <i class="fa fa-<?php echo $action[3]; ?>"></i> 
                        <?php } ?>
                        <?php echo $action[0]; ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php foreach( $action['data'] as $list ) { ?>
                        <li>
                            <a href="<?php echo $list[1]; ?>" target="<?php echo $action[2]; ?>">
                                <?php if( isset( $action[3] ) ) { ?>
                                <i class="fa fa-<?php echo $list[3]; ?>"></i> 
                                <?php } ?>
                                <?php echo $list[0]; ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } else { ?>
                <a href="<?php echo $action[1]; ?>" target="<?php echo $action[2]; ?>">
                    <?php if( isset( $action[3] ) ) { ?>
                    <i class="fa fa-<?php echo $action[3]; ?>"></i> 
                    <?php } ?>
                    <?php echo $action[0]; ?>    
                </a>
            <?php } ?>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
</section>