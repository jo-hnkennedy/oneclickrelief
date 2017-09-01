<?php

//check if defined
if( !defined('modules') ) {
    die('Module not loadable ..');
}


GLOBAL $page_options;
$po = $page_options;
                      
?>
<div class="section_text_container <?php echo $po['main_class']; ?>"">
    <div class="text-center">
        <?php if( !empty($po['title']) ) { ?>
        <h3 class="tag-title text-info"><?php echo set_string_html( $po['title'] ); ?></h3>
        <?php } ?>
        <?php if(!empty($po['detail']) ) { ?>
        <hr>
        <div class="bg-gray p20">
            <?php echo set_string_html( $po['detail'] ); ?>
            <div class="clear"></div>    
        </div>
        <?php } ?>
    </div>
    <br>
</div>
<div class="clear"></div>