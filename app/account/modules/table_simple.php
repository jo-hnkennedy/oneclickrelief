<?php

//check if defined
if( !defined('modules') ) {
    die('Module not loadable ..');
}


GLOBAL $page_options;
$po = $page_options;

$ao = array( $po );

if( count( $ao ) > 0 ) {
    foreach( $ao as $idx => $pt ) {
        //set animate
        $animate = ( count( $pt['table_data'] ) < 20 ) ? 'sr-list' : ''; 
?>
<div class="table_container <?php echo $pt['main_class']; ?>" id="table_simple_<?php echo $idx; ?>">
    <div class="text-center">
        <?php if( !empty($pt['title']) ) { ?>
        <h1 class="tag-title text-info"><?php echo set_string_html( $pt['title'] ); ?></h1>
        <?php } ?>
        <?php if(!empty($pt['description']) ) { ?>
        <hr>
        <div class="bg-gray p20">
            <?php echo set_string_html( $pt['description'] ); ?>
            <div class="clear"></div>    
        </div>
        <?php } ?>
    </div>
    <br>
    <table class="<?php echo $pt['table_class']; ?>" 
           id="table_simple_<?php echo $idx; ?>_table"
            <?php 
                if( isset( $pt['data_attr'] ) ) { 
                    foreach( $pt['data_attr'] as $key => $bool ) { 
                        echo 'data-' . $key . '="' . ( $bool ? 'true' : 'false' ) . '" ';
                    } 
                }
           ?>
    >
        <thead class="thead-inverse"> 
            <tr class="<?php echo $animate; ?>">
            <?php 
                foreach( $pt['table_head'] as $index => $thead ) { 
                    //check if we hvae class
                    if( isset($pt[ 'thead_class'] ) && 
                        !empty($pt[ 'thead_class'][ $index ] ) 
                    ) {
                        echo '<th class="'.$pt[ 'thead_class'][ $index ].'">';
                    } else {
                        echo '<th>';
                    }
                    echo set_string_html( $thead );
                    echo '</th>';
                }
            ?>
            </tr>
        </thead>
        <tbody class="small">
        <?php
            foreach( $pt['table_data'] as $tdata ) { 
        ?>
            <tr class="<?php echo $animate; ?>">
            <?php
                foreach( $tdata as $index => $data ) { 
                    //check if we hvae class
                    if( isset($pt[ 'tdata_class'] ) && 
                        !empty($pt[ 'tdata_class'][ $index ] ) 
                    ) {
                        echo '<td class="'.$pt[ 'tdata_class'][ $index ].'">';
                    } else {
                        echo '<td>';
                    }
                    echo set_string_html( $data );
                    echo '</td>';
                }
            ?>
            </tr>
        <?php 
            }
        ?>
        </tbody>
        <tfoot class="bg-silver">
            <tr>
            <?php 
                //loop in table head
                foreach( $pt['table_head'] as $index => $thead ) { 
                    //check if we hvae class
                    if( isset($pt[ 'thead_class'] ) && 
                        !empty($pt[ 'thead_class'][ $index ] ) 
                    ) {
                        echo '<th class="'.$pt[ 'thead_class'][ $index ].'">';
                    } else {
                        echo '<th>';
                    }
                    echo set_string_html( $thead ).'</th>';
                }
            ?>
            </tr>
        </tfoot>
    </table>
</div>
<?php 
    }
}
?>
<div class="clear"></div>