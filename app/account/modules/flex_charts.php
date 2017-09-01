<?php

//check if defined
if( !defined('modules') ) {
    die('Module not loadable ..');
}

//check for user
if( !isset( $user ) || !$user ) {
    die('No use for request ..');
}


GLOBAL $page_options;
$po = $page_options;


if( count( $po ) > 0 ) {
    //loop in charts
    foreach( $po as $key => $chart ) {
        //set chart data
        $chart_data = ( isset( $chart['data']['datasets'] ) ) ? $chart['data']['datasets'] : $chart['data'];
?>
<div class="chart_container <?php echo $chart['main_class']; ?>"" id="flex_chart_<?php echo $key; ?>">
    <div class="text-center">
        <?php if( !empty($chart['title']) ) { ?>
        <h1 class="tag-title text-info"><?php echo set_string_html( $chart['title'] ); ?></h1>
        <?php } ?>
        <?php if(!empty($chart['description']) ) { ?>
        <hr>
        <div class="bg-gray p20">
            <?php echo set_string_html( $chart['description'] ); ?>
            <div class="clear"></div>    
        </div>
        <?php } ?>
    </div>
    <br>
    <div class="box box-default">
        <div class="box-header with-border <?php echo $chart['header_class']; ?>">
            <?php 
                if( isset( $chart['table'] ) ) {
                    //set table data
                    $pt = $chart['table'];
                    //set animate
                    $animate = ( count( $pt['table_data'] ) < 20 ) ? 'sr-list' : ''; 
            ?>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-default export-chart" 
                        data-type="csv" data-source="chart_table_<?php echo $key; ?>_table" 
                        data-target="chart_table_<?php echo $key; ?>" 
                        data-name="<?php echo $key; ?>"
                > 
                    <i class="fa fa-file-text"></i> Export CSV 
                </button> 
                <button type="button" class="btn btn-default export-chart" data-type="pdf" 
                        data-source="chart_table_<?php echo $key; ?>_table" 
                        data-target="chart_table_<?php echo $key; ?>" 
                        data-name="<?php echo $key; ?>"
                > 
                    <i class="fa fa-file-pdf-o"></i> Export PDF 
                </button> 
                <span class="gray status inline p5"></span>
            </div>
            <div class="table_container hide <?php echo $pt['main_class']; ?>" id="chart_table_<?php echo $key; ?>">
                <table class="<?php echo $pt['table_class']; ?>" 
                       id="chart_table_<?php echo $key; ?>_table"
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
                        /*
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
                        */
                        ?>
                        </tr>
                    <?php 
                        }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php 
                }
            ?>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <!-- 
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                -->
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="chart-responsive">
                        <canvas class="flexChart" 
                                data-title="<?php echo $chart['title']; ?>" 
                                data-type="<?php echo $chart['type']; ?>" 
                                data-data='<?php echo json_encode( $chart['data'] ); ?>' 
                                <?php if( isset( $chart['scales'] ) ) { ?> 
                                data-scales='<?php echo json_encode( $chart['scales'] ); ?>' 
                                <?php } ?>
                                <?php if( isset( $chart['hover'] ) ) { ?> 
                                data-hover='<?php echo json_encode( $chart['hover'] ); ?>' 
                                <?php } ?>
                                <?php if( isset( $chart['tooltip'] ) ) { ?> 
                                data-tooltip='<?php echo json_encode( $chart['tooltip'] ); ?>' 
                                <?php } ?>
                                <?php if( isset( $chart['options'] ) ) { ?> 
                                data-options='<?php echo json_encode( $chart['options'] ); ?>' 
                                <?php } ?>
                                id="canvas<?php echo ucwords($key); ?>" 
                                width="1648" 
                                height="824"
                        ></canvas>
                    </div>
                    <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-12">
                    <ul class="chart-legend clearfix">
                    <?php foreach( $chart_data as $index => $data ) { ?>
                        <li class="inline m20">
                            <i class="fa fa-circle-o" style="color:<?php echo getChartColors( $data ); ?>"></i> 
                            <?php 
                                if( isset( $data['label'] ) ) { 
                                    echo $data['label'];
                                } elseif( isset( $data['title'] ) ) { 
                                    echo $data['title'];
                                } else {
                                    echo 'Item ' . ( $index + 1 );
                                }

                            ?>
                        </li>
                    <?php 
                        } 
                    ?>
                    </ul>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer no-padding <?php echo $chart['footer_class']; ?>">
            <ul class="nav nav-pills nav-stacked">
               <?php foreach( $chart_data as $index => $data ) { ?>
                <li>
                    <a href="#" class="text-black">
                        <?php echo ( isset( $data['label'] ) ) ? $data['label'] : $chart_data[ $index ]['label']; ?>
                        <span class="pull-right" style="color:<?php echo getChartColors( $data ); ?>">
                            <i class="fa fa-angle-down"></i> 
                            <?php echo isset( $data['value'] ) ? $data['value'] : implode(', ', $data['data']); ?>
                        </span>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
        <!-- /.footer -->
    </div>
</div>
<?php 
    }
} else {
?>
<div class="chart_container col-md-12">
    <div class="row text-center bg-white p40">
        No data available to display chart.
    </div>
</div>
<?php
}
?>
<div class="clear"></div>
