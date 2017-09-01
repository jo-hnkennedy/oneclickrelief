<?php

include_once '../include/config.php';
include_once '../include/session.php';
include_once '../include/function.php';

//check user
if( !$user ) {
    header('Location: login.php');
}
//set menu type
$menu_type = $account['type'];
//check for other types
if( !in_array( $menu_type, $restrict_types ) ) {
    $menu_type = 'account'; }
//get student id
$studentId = ( isset( $_GET['studentId'] ) ) ? $_GET['studentId'] : $user['id'];



//-------------
//-- TESTING --
//-------------
#$studentId = '1562070';



//set sql statement
$sql = 'SELECT schedule_json FROM schedules_temp WHERE hs_number = "'.$campusId.'" AND student_id LIKE "%'.$studentId.'%" LIMIT 1;';
//die( $sql );
$results = run_db_query( 'ScheduleJson', $sql );
//check query
if ($results) {
    //set output
    $output = array();
    //save as status 
    //die( json_encode( $output ) );
}

//get the source id of the organization from the users table
$orgSourcedId = run_db_query('orgSourcedId', 'SELECT orgSourcedIds AS id FROM users WHERE sourcedId = ?', $user['id'] );
$orgSourcedId = $orgSourcedId[0]['id'];
$orgSourcedId = ( strpos( $orgSourcedId, ',') !== false ) ? explode(',', implode('', explode('"', $orgSourcedId ) ) ) : array( $orgSourcedId );
//get the campus name from the orgs table using the organization source id
$myCampusName = run_db_query('getCampus', 'SELECT name FROM orgs WHERE sourcedId = ?', trim( $orgSourcedId[0] ) );
//set student name
$studentName = $user['display'];
//set cmapus name
$hsName = ( !empty( $myCampusName ) ) ? $myCampusName[0]['name'] : '';

$sql = 'SELECT student_name, hs_name FROM all_students WHERE student_number LIKE "' . $studentId . '" LIMIT 1;';
$results = run_db_query('ScheduleJson', $sql);
if( count( $results ) > 0 && empty( $hsName ) ){ 
    $studentName = $results[0]["student_name"];
    $hsName = $results[0]["hs_name"];
}



$sql = 'SELECT plan_object FROM plan_reports WHERE student_number LIKE "' . $studentId . '" LIMIT 1;';
$results = run_db_query('ScheduleJson', $sql );
$grad_plan = array();
if( count( $results ) > 0 ){ 
    $grad_plan = json_decode($results[0]['plan_object'], true);
}

$sql = 'SELECT plan_object FROM cte_plan_reports WHERE student_number LIKE "' . $studentId . '" LIMIT 1;';
$results = run_db_query('ScheduleJson', $sql );
$cte_grad_plan = array();
if( count( $results ) > 0 ){ 
    $cte_grad_plan = json_decode($results[0]['plan_object'], true);
}

//set dashboard
$page_name = 'gradplan';
$page_title = $studentName;
$page_excerpt = $hsName;
//data attributes
$table_data_attr = array(
    "paging"        => false,
    "lengthChange"  => false,
    "responsive"    => false,
    "searching"     => true,
    "ordering"      => true,
    "processing"    => false,
    "scrollX"       => false,
    "scrollY"       => false,
    "stateSave"     => false,
    "info"          => false,
    "autoWidth"     => false,
    "deferRender"   => false,
    "fixedHeader"   => false,
    "buttons"       => true
);

//set page options
$page_options = array(
    'table_multiple' => array()
);

//set plans
$plans = array( $grad_plan, $cte_grad_plan );

foreach( $plans as $the_plan ) {
    //look at each grad plan
    foreach( $the_plan as $plan ) {
        //get the plan name and courses
        foreach( $plan as $name => $courses ) {
            //clean the table name
            $tname = clean_name( $name, '_' );
            //set table name
            $page_options['table_multiple'][ $tname ] = array(
                'title' => $name,
                'description' => '<div>'.$name.' Graduation Plan</div><br>
                    <span class="green bg-white p10">*Completed</span> 
                    <span class="bg-white orange p10">*Incomplete</span>
                    <span class="bg-white red p10">*Required</span>
                ',
                'data_attr' => $table_data_attr,
                'main_class' => 'col-md-10 text-left bg-white middle p20',
                'table_class' => 'text-left table table-striped table-responsive table-condensed ',
                'thead_class' => array('text-left','text-left'),
                'tdata_class' => array('bold text-left','text-left'),
                'table_head' => array('Subject','Course'),
                'table_data' => array()
            );
            foreach( $courses as $course ) {
                foreach( $course as $cname => $classes ) {
                    foreach( $classes as $class ) {
                        $page_options['table_multiple'][ $tname ]['table_data'][] = array(
                            '<strong style="color:'.$class[1]['color'].'" rel="tooltip" title="'.$class[2]['tooltip'].'">'.$cname.'</strong>',
                            '<strong style="color:'.$class[1]['color'].'" data-toggle="tooltip" title="'.$class[2]['tooltip'].'">'.$class[0]['name'].'</strong>'
                        );
                    }
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<?php importModule('main_header'); ?> 

<body id="page-top" class="loggedin hold-transition skin-<?php echo $account['layout']['skin_color']; ?> sidebar-mini">
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
                            <?php importModule('navbar_header'); ?>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <?php echo setMenu( $menu_type,  $top_menu, $page_name, $account ); ?>
                </ul>
            </div>
        </div>
    </nav>
    <header class="main-header">
        <a href="#" class="logo"><?php echo ucwords($page_name); ?></a>
        <?php importModule('section_topnav'); ?>
    </header>
    <div class="wrapper">

        <?php importModule('sidebar_left'); ?> 
        <div class="content-wrapper">
            <?php importModule('content_header'); ?> 
            <section class="content">
                <?php importModule('table_multiple'); ?> 
	    </section>

            <section class="content">
	    <center>
	<h1 class="tag-title text-info">CTE Pathway</h1>
                        <hr>
        <div class="bg-gray p20">
            <div>Information Technology Pathway</div>
                <span class="green bg-white p10">*Completed</span> 
                <span class="bg-white orange p10">*Incomplete</span>
                <span class="bg-white red p10">*Required</span>
                        <div class="clear"></div>    
        </div>
	<table class="text-left table table-striped table-responsive table-condensed " 
           id="table_multiple_Distinguished Achievement Plan_table"
            data-paging="false" data-lengthChange="false" data-responsive="false" data-searching="true" data-ordering="true" data-processing="false" data-scrollX="false" data-scrollY="false" data-stateSave="false" data-info="false" data-autoWidth="false" data-deferRender="false" data-fixedHeader="false" data-buttons="true"     >
        <thead class="thead-inverse"> 
            <tr class="">
            <th class="text-left">
		    Personal Grad Plan Sequence<br>(Computer Maintenance and Technician)
</th><th class="text-left">
		    Mesa Generated Sequence<br>(Computer Maintenance and Technician)
</th>            </tr>
        </thead>
		<tr>
		  </tr>
		  <tr>
		    <td style="color:#008000">Principles of Information Technology</td>
		    <td style="color:#008000">Principles of Information Technology</td>
		  </tr>
		  <tr>
		    <td style="color:#FF0000">Computer Maintenance</td>
		    <td style="color:#FF0000">Computer Maintenance</td>
		  </tr>
		  <tr>
		    <td style="color:#FFA500">Telecommunications and Networking</td>
		    <td style="color:#FFA500">Telecommunications and Networking</td>
		  </tr>
		  <tr>
		    <td style="color:#FFA500">Computer Technician</td>
		    <td style="color:#FFA500">Computer Technician</td>
		  </tr>
		</table>
	     </center>		

	    </section>
        </div>
        <?php importModule('section_footer'); ?> 
        <?php importModule('sidebar_right'); ?> 
    </div>

    <?php importModule('main_footer'); ?> 

</body>

</html>
