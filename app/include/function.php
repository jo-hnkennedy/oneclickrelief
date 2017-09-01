<?php
// die(phpinfo());

//SET RUN DATABASE QUERY
function run_db_query( $name='', $query='' ){
    //get global queries
    GLOBAL $queries;
    GLOBAL $db_path;
    GLOBAL $db_cred;
    GLOBAL $db_type;
    //default output
    $output = array();
    //set default
    $db = false;
    $error = false;
    $results = array();

    //check for query
    if( !empty( $query ) ) {
        //open database
        switch( $db_type ) {

            //---------
            // SQLITE
            //---------
            case 'SQLITE': 
                //connect
                $db = new SQLite3( $db_path );
                //check database 
                if( $db ) { 
                    //run query using array
                    if( is_array( $query ) ) {
                        //build query
                        $build = $query[0];
                        //check if we have stuff to bind
                        if( is_array( $query[1] ) ) {
                            //set array
                            $array = array();
                            //loop in each 
                            foreach( $query[1] as $key => $value ) {
                                $array[] = " " . $key . ' = :' . $key;
                            }
                            //build and join
                            if( count( $array ) > 1 ) { 
                                $build .= implode( " AND ", $array );
                            } else {
                                $build .= $array[0];
                            }
                            $build .= ';';
                            //remove double white spaces
                            $build = implode( " ", explode( "  ", $build ) );
                        }
                        //prepare statement
                        $statement = $db->prepare( $build );
                        //do this again for binding
                        if( is_array( $query[1] ) ) {
                            //loop in each 
                            foreach( $query[1] as $key => $value ) {
                                //check if array
                                if( is_array( $value ) ) {
                                    $statement->bindValue( ':'.$key, $value );
                                } else {
                                    $statement->bindValue( ':'.$key, $value, SQLITE_ArgType( $value ) );
                                }
                            }
                        }
                        //execute statement
                        $results = $statement->execute();
                        //check for results
                        if( json_encode( $results ) == '{}'  ) {
                            //loop and replace
                            foreach( $query[1] as $key => $value ) {
                                $build = implode( '"' .$value . '"', explode( ':' . $key, $build ) );
                            }
                            //get query the old eway
                            $results = $db->query( $build );
                        }
                    } elseif( strpos( $query, 'SELECT' ) !== FALSE ) {
                        $results = $db->query( $query );
                    } else {
                        $results = $db->exec( $query );
                    }
                    if( strpos( $query, 'SELECT' ) === FALSE ) {
                        $output = $results;
                    //check if we have results
                    } elseif( count( $results ) > 0 ) {
                        //loop in results and get row
                        while ($row = $results->fetchArray()) {
                            //build output
                            $output[] = $row;
                        }
                    } else {
                        $output = array(0);
                    }
                } else {
                    $error = $db->lastErrorMsg();
                }
                $db->close();
            break;


            //-------
            // MYSQL
            //-------
            case 'MYSQL':
                $db_port=$db_cred['port'];
                $db_host=$db_cred['hostname'];
                $db_name=$db_cred['database'];
                $db_user=$db_cred['username'];
                $db_pass=$db_cred['password'];

                //set database
                $db = new PDO('mysql:host='.$db_host.';port='.$db_port.';dbname='.$db_name, $db_user, $db_pass);

                //$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $args = array_merge( func_get_args(), array() );
                array_shift($args);
                array_shift($args);
                //secure database query
                if( count( $args ) > 0 ){
                    $res = $db->prepare($query);
                    $res->execute($args);
                } else {
                    //insecure database query
                    $res = $db->prepare($query);
                    $res->execute();
                    //$res = $db->query( $query );
                }
                if( $res && strpos( $query, 'SELECT' ) !== FALSE ) {
                    while ($row = $res->fetch(PDO::FETCH_ASSOC) ) {
                        //check if count all
                        if ( isset( $row['COUNT(*)'] ) ) {
                            $total = array_values( $row );
                            $output[] = $total[0];
                        } else {
                            $output[] = $row;
                        }
                    }

                } elseif( 
                    strpos( $query, 'CREATE') !== false || 
                    strpos( $query, 'INSERT' ) !== false 
                ) {
                    $output = $res;
                } else {
                    $output[] = $res;
                }

            break;

            //-------------
            // POSTGRE SQL
            //-------------
            case 'PGSQL':
                //connect
                $db = pg_connect(
                    "dbname=" . $db_cred['database'] . " " .
                    "host=" . $db_cred['hostname'] . " " .
                    "port=" . $db_cred['port'] . " " .
                    "user=" . $db_cred['username'] . " " . 
                    "password=" . $db_cred['password'] . " " .
                    "options='--client_encoding=UTF8'" . " " .
                    "connect_timeout=5"
                ); 
                //check database
                if( $db ) { 
                    //run query
                    $results = pg_query( $db, $query );
                    //check if we have results
                    if( count( $results ) > 0 ) {
                        //loop in results and get row
                        while ($row = pg_fetch_all( $results ) ) {
                            //build output
                            $output[] = $row;
                        }
                    } elseif( strpos( $sql, 'SELECT' ) === FALSE ) {
                        $output = $results;
                    } else {
                        $error = 'Unable to connect';
                    }
                }
                pg_close($db);
            break;

        }

    }
    //check for errors
    if( $error ) {
        die( $error );
    }

    //just dump some data
    return $output;
}

//CREATE NEW USER
function create_user( $post=array() ) {
    //SET GLOBALS
    GLOBAL $user_session;
    //set post data
    $post_data = array();
    //set users head
    $usersHead = explode(', ', 'sourcedId, status, dateLastModified, orgSourcedIds, role, username, userId, givenName, familyName, identifier, email, sms, phone, agents');
    //set users status
    $usersStatus = array('active','inactive','tobedeleted');
    //set users role
    $usersRole = array('student','parent','teacher','administrator','mesa');
    //get update fields from users table
    $getUsersSQL = 'SELECT * from usersSchema;';
    $getUsersHead = run_db_query('getUsersSchema', $getUsersSQL);
    $usersHead = array_column( $getUsersHead, 'field_header' );
    $isRequired = array_combine( $usersHead, array_column( $getUsersHead, 'required' ) );
    $tooltipInfo = array_combine( $usersHead, array_column( $getUsersHead, 'description' ) );

    //check for a given name
    if( isset( $post['givenName'] ) ) {

        //check all required fields
        foreach( $post as $key => $val ) {
            //check if required field found
            if( isset( $isRequired[ $key ] ) && 
                //check if field is required
                $isRequired[ $key ] == 'Yes' && 
                //check if empty
                empty( $val ) 
            ) {
                return array(
                    'status' => 0,
                    'text' => '<strong>OOPS</strong> No '.$key.' was typed. Try typing it ...',
                    'data' => array()
                );
            }
        }

        //check stats if any added
        if( !empty( $post['status'] ) && !in_array( $post['status'], $usersStatus ) ) {
            return array(
                'status' => 0,
                'text' => '<strong>OOPS</strong> User status not supported. Please user a default status types ...',
                'data' => array()
            );
        }

        //check stats if any added
        if( !empty( $post['role'] ) && !in_array( $post['role'], $usersRole ) ) {
            return array(
                'status' => 0,
                'text' => '<strong>OOPS</strong> User role not supported. Please user a default role types ...',
                'data' => array()
            );
        }

        //check users table for fields
        $checkFields = array('sourcedId', 'userId', 'email', 'username');
        foreach( $checkFields as $field ) {
            //check for fields
            if( isset( $post[ $field ] ) ) {
                $checkUsersSQL = 'SELECT * from users WHERE '.$field.' = ?;';
                $checkUsersData = run_db_query('getUsersData', $checkUsersSQL, $post[ $field ] ); 
                //check if we have length
                if( count( $checkUsersData ) > 0 ) {
                    return array(
                        'status' => 0,
                        'text' => '<strong>OOPS</strong> That '.$field.' is already being used. Try typing a different one ...',
                        'data' => $checkUsersData
                    );
                }
            }
        }

        // check if valid email
        if( filter_var($post['email'], FILTER_VALIDATE_EMAIL) == false ){
            return array(
                'status' => 0,
                'text' => '<strong>OOPS</strong> The email address is invalid. Try typing a different one ...',
                'data' => array()
            );
        }
        //set post data
        $post_data = array(
            //set first
            array(
                //sourcedId, status, dateLastModified, orgSourcedIds, role, username, userId, givenName, familyName, identifier, email, sms, phone, agents
                $post['sourcedId'], 
                $post['status'], 
                date('Y-m-d'), 
                $post['orgSourcedIds'], 
                $post['role'], 
                $post['username'],
                $post['userId'],
                $post['givenName'],
                $post['familyName'],
                $post['identifier'],
                $post['email'],
                $post['sms'],
                $post['phone'],
                $post['agents']
            )
        );
    }
    $output = '';
    //check if data exists
    if( count( $post_data ) > 0 ) {
        //set name
        $add_id = false;
        //set table
        $table = 'users';
        //get columns from body if field_header exist
        $head = $usersHead;
        //set mock body
        $body = $post_data;

        //look in each row
        foreach($body as $row) {

            //---------------------------
            // Set New User Database SQL
            //---------------------------
            //set query to add user to 
            $sql = "INSERT INTO ".$table." (".implode(",", $head).") VALUES ('".implode("', '", array_values($row))."');";
            // $insertRows = run_db_query($table, $sql);
            $insertRows = run_db_query( 'createUser', $sql );
            //check query
            if ($insertRows) {
                $output .= 'INSERT execution passed: '.$sql.'<br><br>';
            } else {
                $output .= 'INSERT execution fialed: '.$sql.'<br><br>';
            }

            //---------------------------
            // Set database field values
            //---------------------------
            $role = $row[ array_search('role', $head ) ];
            $email = $row[ array_search('email', $head ) ];
            $name = $row[ array_search('givenName', $head ) ].' ';
            $name .= $row[ array_search('familyName', $head ) ];
            $user = $row[ array_search('username', $head ) ];
            $usid = $row[ array_search('sourcedId', $head ) ];
            $stat = $row[ array_search('status', $head ) ];
            $pswd = $user.$usid;
            $cred = hash( 'sha256', 'mesa'.$email.$pswd );
            $type = 'public';
            $active = true; 

            //check for valid email
            if( filter_var($email, FILTER_VALIDATE_EMAIL)   ) {
                //---------------------------
                // Set Default Account Type
                //---------------------------
                switch( $role ) {
                    case 'administrator': $type = 'campus'; break;
                    case 'district': $type = 'district'; break;
                    case 'student': $type = 'student'; break;
                    case 'teacher': $type = 'campus'; break;
                    case 'parent': $type = 'parent'; break;
                    case 'mesa': $type = 'admin'; break;
                }

                //---------------------------
                // Set user session variables
                //---------------------------
                $session = $user_session;
                $session['date'] = date('Y/m/d h:i:s');
                $session['user']['id'] = $usid;
                $session['user']['name'] = $user;
                $session['user']['email'] = $email;
                $session['user']['status'] = $stat;
                $session['user']['display'] = $name;
                $session['account']['type'] = $type;
                $session['account']['active'] = true;
                $session['session']['reset'] = hash( 'sha256', date('ymd:his').$email );
                $session['session']['hash'] = md5( $email . $pswd );
                $session['session']['name'] = $user;
                $session['session']['id'] = $cred;
                $session['session']['browser'] = $_SERVER['HTTP_USER_AGENT'];
                $session['session']['referer'] = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '';
                $session['session']['ipaddrs'] = $_SERVER['REMOTE_ADDR'];

                //---------------------------
                // Set New User Database SQL
                //---------------------------
                $table = 'sessions';
                $session_head = array('sessionId', 'userId', 'email', 'data');
                $session_body = array( $cred, $usid, $email, json_encode( $session ) );
                $sql = "INSERT INTO ".$table." (".implode(",", $session_head).") VALUES ('".implode("','", array_values($session_body))."');";
                $insertRows = run_db_query( 'createSession', $sql );
                if ($insertRows) {
                    $output .= 'INSERT execution passed: '.$sql.'<br><br>';
                } else {
                    $output .= 'INSERT execution fialed: '.$sql.'<br><br>';
                }
            }
        }
        //output results
        return array( 
            'status' => 1, 
            'text' => '<strong>OKAY</strong> User has been created.',
            'logs' => $output,
            'data' => $session
        );
    }
    //return status
    return array(
        'status' => 0,
        'text' => '<strong>OOPS</strong> Nothing posted so no user was created ...',
        'data' => array()
    );
}

//LOGIN IN USER
function login_user( $post=array() ) {
    //set global
    GLOBAL $crd_snid;
    //check if post exist 
    if( isset( $post['user'] ) && isset( $post['pswd'] ) ) {
        // check if user name exist
        if( !empty( $post['user'] ) ) {
            // check if password exist
            if( !empty( $post['pswd'] ) ) {
                //get cred
                $email = $post['user'];
                $pswd = $post['pswd'];
                //check for user
                $cred = hash( 'sha256', 'mesa'.$email.$pswd );
                //set sql statement
                $sql = 'SELECT * FROM sessions WHERE email = "'.$email.'" LIMIT 1;';
                //die( $sql );
                $user_data = run_db_query('sessions', $sql);
                //check for data
                if( count( $user_data ) > 0 ) {
                    //set user data
                    $udata = $user_data[0];
                    //get current user session
                    $sess = $udata['sessionId'];
                    //check check session
                    if( $sess == $cred ) {
                        //set session dat
                        if( is_string( $udata['data'] ) ) {
                            $sdata = json_decode( $udata['data'], true );
                        } else {
                            $sdata = $udata;
                        }
                        //check for user id
                        $user = $sdata['user'];
                        $account = $sdata['account'];
                        $session = $sdata;
                        //check if account is active
                        if( !$account['active'] ) {
                            $user = false;
                        }
                        //check if make page
                        if( $user ){
                            //set cookie varialbe
                            $cookie = array();
                            //set session id
                            $cookie[ $crd_snid ] = $cred;
                            //set cookie as session
                            foreach($cookie as $key => $value){
                                setcookie($key, $value, strtotime("+2 year"), '/');
                            }
                            //set here
                            return array(
                                'status' => 1,
                                'text' => '<strong>Loading Mesa onTime</strong>',
                                'data' => $user
                            );
                        } else {
                            return array(
                                'status' => 0,
                                'text' => '<strong>OOPS</strong> Account not activated yet ...',
                                'data' => false
                            );
                        }
                    } else {
                        return array(
                            'status' => 0,
                            'text' => '<strong>Invalid user or password</strong>',
                            'data' => false
                        ); 
                    }   
                } else {
                    return array(
                        'status' => 0,
                        'text' => '<strong>OOPS</strong> Email address not found...',
                        'data' => false
                    );   
                }
            } else {
                return array(
                    'status' => 0,
                    'text' => '<strong>OOPS</strong> Missing user password...',
                    'data' => false
                ); 
            }
        } else {
            return array(
                'status' => 0,
                'text' => '<strong>OOPS</strong> Missing user name...',
                'data' => false
            );
        }
    } else {
        return array(
            'status' => 0,
            'text' => '<strong>OOPS</strong> Invalid email addreess or password',
            'data' => false
        );   
    }
}

//DUMP CSV FILE INTO A DATABASE
function csv_to_database($options = array()) {
    /*
       csv_to_database(
          $options = array(
             'add_id' => true,
             'table' => 'schema',
             'db_path' => 'process/db',
             'file' => 'process/schema.csv'
          )
       );
    */
    GLOBAL $db_path;
    //add unique id
    $add_id = true;
    $file = '';
    $table = '';
    //check for options
    if (count($options) > 0) {
        //loop in options
        foreach($options as $key => $value) {
            $ { $key } = $value;
        }

        //set defaults
        $import = array();
        $header = array();

        //check if not empty
        if (!empty($file) && is_file($file)) {
            //get csv file
            $resource = file_get_contents($file);
            $lines = preg_split('/\r/', $resource);

            //look in each lines
            foreach($lines as $index => $line) {
                $rows = array();
                $arr = explode(',', $line);

                //check if first line
                if ($index == 0) {
                    foreach($arr as $name) {
                        if (!empty($name)) {
                            $header[] = strtolower(implode('_', explode(' ', $name)));
                        }
                    }
                } else {
                    //set defaults
                    $idx = 0;
                    $row = array();
                    //look in each header
                    foreach($header as $name) {
                        //check if name exists
                        if (!empty($arr[$idx])) {
                            //ignore the first
                            $row[$name] = implode('', explode('"', $arr[$idx]));
                            //set count
                            $idx++;
                        }
                    }
                    //set import rows
                    $import[] = $row;
                }
            }

            //------------------------------
            // Create table if one not found
            //------------------------------
            //create table
            $sql = 'CREATE TABLE IF NOT EXISTS '.$table.' ( ';
            $keys = array();
            //check if add id
            if ($add_id) {
                $keys[] = $table.'_id INTEGER PRIMARY KEY AUTOINCREMENT';
            }
            foreach($header as $named) {
                $keys[] = $named.' varchar(250)';
            }
            $sql .= implode(', ', $keys).')';
            //create table
            $createTable = run_db_query($table, $sql);
            //---------------------
            // Insert into table
            //---------------------
            foreach($import as $row) {
                    //set query
                    $sql = "INSERT INTO ".$table." (".implode(",", $header).") VALUES ('".implode("','", array_values($row))."')";
                    //die( $sql );
                    $ret = run_db_query($table, $sql);
                    //check query
                    if ($ret) {
                        echo 'SQL execution passed: '.$sql.
                        '<br>';
                    } else {
                        echo 'Could not execute SQL statement<br>';
                    }
                }
                //-------------------------
                // Check for data in table
                //-------------------------
            $check = run_db_query($table, 'SELECT * FROM '.$table);
            //check if found
            if ($check !== FALSE) {
                echo 'Successfully completed inserts<br>';
            } else {
                echo 'Could not insert into table<br>';
            }
        } else {
            die('Missing csv file path in csv_to_database function');
        }
    } else {
        die('Missing options in csv_to_database function');
    }
}

// SET CONTENT IN TABLE
function db_set_contents($path,$data,$table=false){
    //Default Table
    GLOBAL $db_table;
    if(!$table){
        $table = $db_table;
    }
    //old data
    $old_data = db_get_contents($path,$table);
    //check existing
    if(count($old_data) > 0){
        //Query Table
        $result = run_db_query($path, "UPDATE ".$table." SET data='".$data."' WHERE id='".$old_data[0]['id']."'");
    } else {
        //Query Table
        $result = run_db_query($path, "INSERT INTO ".$table." (hash, data) VALUES ('".sha1($path)."','".$data."')");
    }
    return $result;
}

// GET CONTENT FROM TABLE
function db_get_contents($path,$table=false){
    //Default Table
    GLOBAL $db_table;
    if(!$table){
        $table = $db_table;
    }
    //Query Table
    $output = run_db_query($path, "SELECT * FROM ".$table." WHERE hash='".sha1($path)."'");
    return $output;
}

// CREATE DATABASE TABLE
function db_set_table($table=false){
    //Default Table
    GLOBAL $db_table;
    if(!$table){
        $table = $db_table;
    }
    $result = run_db_query($path, 'CREATE TABLE '.$table.' (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        hash VARCHAR(40) NOT NULL,
        data LONGBLOB NOT NULL,
        date TIMESTAMP
    )');
    return $result;
}

// CHECK DATABASE TYPES
function db_check_types(){
    //Default Table
    GLOBAL $DB_TYPES;
    GLOBAL $DB_STORE;
    //set variables
    $store = $DB_STORE;
    $types = $DB_TYPES;
    //set results
    $results = false;
    //check types
    if(in_array($store,$types)){
        $results = true;
    } else {
        if($debug){
            die('Database type not supported');
        }
    }
    return $results;
}

// CHECK DATABASE TABLE
function db_check_table($table=false,$add=false){
    GLOBAL $db_table;
    if(!$table){
        $table = $db_table;
    }
    //set results
    $results = false;
    //check for table
    $result = run_db_query($path, 'SHOW TABLES LIKE "'.$table.'"');
    //check for table
    if( count($result) > 0 ){
        $results=true;
    } 
    //create table
    elseif($add) {
        $results=db_set_table($table);
    }
    
    return $results;
}

// CHECK FILE PATH
function file_check_path($path,$add=false){
    //SET HOST
    GLOBAL $db_cred;
    //Default host
    $host = $db_cred['hostname'];
    //set results
    $results = false;
    //check for table
    if(!is_file($path) && $add){
        //get permission & parts
        $perms=intval("0777",8);
        $parts=explode('/',$path);
        //dir name
        $sname='';
        //loop in directory
        foreach($parts as $dir){
            //check for file ending
            if(strpos($dir,'.')===false){
                //set directory name
                $sname .= $dir.'/';
                //check for directory
                if(!is_dir($sname)){
                    //set permissions
                    @mkdir($sname,$perms);
                    @chown($sname,$host);
                    @chgrp($sname,$host);
                    //create new php index file
                    file_put_contents($sname."/index.php","");
                }
            } else {
                //check if not a file
                if(!is_file($sname.$dir)) {
                    file_put_contents($sname.$dir,'');
                }
            }
        }
    }
    //check for file
    if(is_file($path)){
        $results = true;
    }
    
    return $results;
}

// FILE GET DATA
function file_interpolate($options=array()){
    //get global
    GLOBAL $db_store;
    GLOBAL $db_table;
    GLOBAL $debug;
    //set options
    $o = $options;
    //Default Results
    $results = false;
    //Default Store
    $store = $db_store;
    //set options
    $add = $o['add'];
    $path = $o['path'];
    $data = $o['data'];
    $table = $o['table'];
    //Default Table
    if(!$table){
        $table = $db_table;
    }
    //check database type
    if(db_check_types()){

        //run store operation
        switch($store){
            //run sql 
            case 'sql':
                //check for table
                if(db_check_table($table,$add)){
                    //set
                    if($add){
                        //add contents
                        $results = db_set_contents($path,$data,$table);
                    }
                    //get
                    else {
                        $results = db_get_contents($path,$table);
                    }
                }
            break;

            //run sql 
            case 'file':
                //check file path
                if(file_check_path($path,$add)){
                    //set
                    if($add){
                        //add contents
                        file_put_contents($path,$data);
                        //check contents
                        if(is_file($path)){
                            $results = $data;
                        }
                    }
                    //get
                    else {
                        if(is_file($path)){
                            $results = file_get_contents($path);
                        }
                    }
                }
            break;

        }
    }
    return $results;
}

// FILE GET DATA
function file_get_data($path,$table=false){
    return file_interpolate(array(
        'add' => false,
        'data' => false,
        'path' => $path,
        'table' => $table
    ));
}

// FILE PUT DATA
function file_put_data($path,$data,$table=false){
    return file_interpolate(array(
        'add' => true,
        'data' => $data,
        'path' => $path,
        'table' => $table
    ));
}

//PULL CITY AND STATE
function pull_cist($adr,$ign=false){
    $g = array('City','Park');
    $e = fix_address($adr);
    $b = explode(',',$e);
    $a = explode(' ',trim($b[1]));
    if(count($a) > 2){
        array_pop($a);
    }
    $s = strtoupper(array_pop($a));
    $d = trim(implode(' ',$a)).' '.$s;
    if($ign && $d==$ign){
        return false;
    } else {
        return $d;
    }
}

//PULL KEY FROM ARRAY
function pull_keys($array,$key,$sub=false){
    $o = array();
    $y = $key;
    $b = $sub;
    foreach($array as $k => $v){
        if( isset($v[$y]) ){
            $u = $v[$y];
            if( $b && isset($u[$b]) ){
                $t = $u[$b];
                if(is_array($t)){
                    foreach($t as $w){
                        $w=trim($w);
                        if(!empty($w)){
                            array_push($o,$w);
                        }
                    }
                } else {
                    $t=trim($t);
                    if(!empty($t)){
                        array_push($o,$t);
                    }
                }
            } else {
                $u=trim($u);
                if(!empty($u)){
                    array_push($o,$u);
                }
            }
        }
    }
    $o=array_unique($o);
    return $o;
}

//PULL PART OF EACH STRING IN AN ARRAY
function pull_part($array,$index=0,$delim=' ',$add=''){
    $o = array();
    $i = $index;
    $d = $delim;
    $y = $array;
    if(count($y) > 0){
        foreach($y as $a){
            if(strpos($a,$d)!==false){
                $e = explode($d,$a);
                if( isset($e[$i]) && !empty($e[$i]) ){
                    $s = $e[$i].' '.$add;
                    $v = trim($s);
                    array_push($o,$v);
                }
            }
        }
        if(count($o) > 0){
            $w = array_unique($o);
            $o = $w;
        }
    }
    return $o;
}

//FIND MATCHING FILE IN RESOURCE DIRECTORY
function find_resource_file($file) {
    GLOBAL $debug;
    GLOBAL $res_ddir;
    GLOBAL $res_dpdr;
    //check for resourece directory
    if(!is_dir($res_ddir) ) {
        $resource_dir = $res_dpdr;
    } else {
        $resource_dir = $res_ddir;
    }
    $old_file = $file;
    $file = implode('.tsv',explode('.tsv.tsv',$file));
    $file = implode('',explode(' (Responses)',$file));
    $file = implode('',explode(' (Response)',$file));
    $file = implode('',explode('Sheet1',$file));
    $file = implode('List',explode('List -',$file));
    $file = implode('List',explode('List -',$file));
    $file = implode('List',explode('List -',$file));
    for($x=1;$x < 5;$x++){
        $file = implode('',explode('('.$x.')',$file));
        $file = implode('',explode('Form Responses '.$x,$file));
    }
    $exp = explode('.',$file);
    $extension = $exp[1];
    $filename = $exp[0];
    if(strpos($filename,' - ')!==false){
        $fexp = explode(' - ',$filename);
        $filename = $fexp[0];
    }
    $filename = strtolower(implode('_',explode(' ',trim($filename))));
    $new_file = $filename.'.'.$extension;
    if(is_dir($resource_dir)) {
        if(is_file($resource_dir.$new_file)){
            return array(
                'dir'=>$resource_dir,
                'file'=>$new_file
            );
        } else if($debug){
            echo '<span class="error">No file exists for <b>'.$new_file.'</b></span><br>';
        }
    } else if($debug){
        die('Missing resource path');
    }
    return false;
}

//FIND MATCHING FILE IN EXISTING DIRECOTRY
function find_template_file($file,$addnew=false) {
    GLOBAL $debug;
    GLOBAL $template_dir;
    $old_file = $file;
    $file = implode('',explode('NFRS',$file));
    $file = implode('Agency',explode('County',$file));
    $exp = explode('.',$file);
    $extension = $exp[1];
    $filename = $exp[0];
    $filename = implode('_',explode(' ',ucwords(trim($filename))));
    $pfx = 'Template_';
    $new_file = $pfx.$filename.'.'.$extension;
    if(is_dir($template_dir)) {
        if(is_file($template_dir.$new_file)||$addnew){
            return array(
                'dir'=>$template_dir,
                'file'=>$new_file
            );
        } else if($debug){
            echo '<span class="error">No file exists for <b>'.$new_file.'</b></span><br>';
        }
    } else if($debug){
        die('Missing template path');
    }
    return false;   
}

//REPLACE OLD FILE WITH NEW IN DROP DIR
function drop_files(){
    GLOBAL $debug;
    GLOBAL $drop_dir;
    GLOBAL $archive_dir;
    GLOBAL $ignore_dir;
    $dropped=0;
    if( is_dir($drop_dir) &&
        is_dir($ignore_dir) && 
        is_dir($archive_dir)
    ) {
        $id = $ignore_dir;
        $ad = $archive_dir;
        $dd = $drop_dir;
        $ds = scandir($dd);
        //look at current files
        foreach($ds as $cf) {
            //ignore index and other non essential files
            if( $cf!='index.html' && $cf!='index.php' && $cf!='readme.html' &&
                $cf!='.' && $cf!='..' && is_file($dd.$cf) 
            ) {
                $res = false;
                //unzip any files 
                if( strpos($cf,'.zip')!==false){

                }
                //replace resource file
                if( strpos($cf,'.csv')!==false || strpos($cf,'.tsv')!==false ){
                    $res = find_resource_file($cf);
                }
                //replace template file
                else if( strpos($cf,'.html')!==false){
                    $res = find_template_file($cf);
                }
                //move other file to ignore folder
                else {
                    copy($dd.$cf,$id.$cf);
                    unlink($dd.$cf);
                    if($debug){
                        echo 'File not <b>'.$cf.'</b> movable';
                    }
                }
                //check for resource
                if(is_array($res)){
                    $rf = $res['file'];
                    $rd = $res['dir'];
                    //rename drop file to resource file
                    rename( $dd.$cf, $dd.$rf );
                    //copy old resource file to archive folder
                    if(is_file($rd.$rf)){
                        copy( $rd.$rf, $ad.$rf );
                    }
                    //copy drop file to resource folder
                    copy( $dd.$rf, $rd.$rf );
                    //remove drop file
                    unlink($dd.$rf);
                    //count dropped
                    $dropped++;
                }
            }
        }
        //results
        if($debug){
            echo 'Moved <b>'.$dropped.'</b> files from drop folder.<br>';
        }
    } else {
        if($debug){
            die('Missing direcotry path..');
        }
        return false;
    }
    return true;
}

//RECURSIVELY REMOVE FILES AND DIRECTORY
function remdir($dir) { 
   $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      (is_dir("$dir/$file")) ? remdir("$dir/$file") : unlink("$dir/$file"); 
    } 
    return rmdir($dir);
}

//UNZIP ARCHIVE FILE FROM DROP DIR
function unarchive_files(){
    GLOBAL $debug;
    GLOBAL $drop_dir;
    //check for drop directory 
    if( is_dir($drop_dir)) {
        $dd = $drop_dir;
        $ds = scandir($dd);
        //look at current files
        foreach($ds as $cf) {
            //match zip files
            if( strpos($cf,'.zip')!==false){
                //unzip files
                $zip = new ZipArchive;
                if ($zip->open($dd.$cf) === TRUE) {
                    $zip->extractTo($dd);
                    $zip->close();
                }
                //remove folder content
                $ext = explode('.',$cf);
                $zfdr = $dd.$ext[0];
                if(is_dir($zfdr)){
                    remdir($zfdr);
                }
                unlink($dd.$cf);
                //message
                if($debug){
                    echo 'Unziped file <b>'.$cf.'</b> <br>';
                }
            }
        }
    } else {
        if($debug){
            die('Missing drop path');
        }
        return false;
    }
    return true;
}

//CHECK FOR URL IN STRING
function isURL($str){
    if (!filter_var($str, FILTER_VALIDATE_URL) === false) {
        return true;
    }
    return false;
}

//GET CURRENT CLIENT IP ADDRESS
function getClientIP(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else{
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

//FIND STRING BETWEEN TWO BLOCKS 
function codeBlock($start, $end, $string){
    if(strpos($string,$start)!==false && strpos($string,$end)!==false){
        $a = explode($start,$string);
        $b = explode($end, $a[1]);
        return $b[0];
    } else {
        return $string;
    }
}

//FIND ALL STRINGS IN CODE BLOCKS
function rcodeBlock($start, $end, $mixed){
    $array = array();
    if( is_array($mixed) ) {
        $arr = $mixed;
        foreach($arr as $k => $ar) {
            if(strpos($ar,$start)!==false){
                $a = explode($start, $ar);
                $b = explode($end, $a[1]);
                $array[] = $b[0];
            }
        }
    } else {
        if(strpos($mixed,$start)!==false){
            $arr = explode($start, $mixed);
            foreach($arr as $k => $ar) {
                if($k != 0) {
                    $a = explode($end, $ar);
                    $array[] = $a[0];
                }
            }
        }
    }
    return $array;
}

//CLEAN HTML BY REMOVING TAGS
function clean_html($html){
    //remove script tags
    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
    //remove style tags
    $html = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $html);
    //remove link tags
    $html = preg_replace('#<link(.*?)>#is', '', $html);
    //remove html comments
    $html = preg_replace('#<!--(.*?)-->#is', '', $html);
    //remove new lines
    $html = implode('',preg_split("/\r?\n|\r/",$html));
    return $html;
}

//CONVERT AN ELEMENT TO AN OBJECT
function element_to_obj($element){
    $obj = array( "tag" => $element->tagName );
    foreach ($element->attributes as $attribute) {
        $obj[$attribute->name] = $attribute->value;
    }
    foreach ($element->childNodes as $subElement) {
        if ($subElement->nodeType == XML_TEXT_NODE) {
            $obj["html"] = $subElement->wholeText;
        }
        elseif ($subElement->nodeType == XML_CDATA_SECTION_NODE) {
            $obj["html"] = $subElement->data;
        }
        else {
            $obj["children"][] = element_to_obj($subElement);
        }
    }
    return $obj;
}

//CONVERT AN HTML TO AN OBJECT
function html_to_obj($html){
    if(empty($html)) {
        return array();
    }
    $html = clean_html($html);
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    return element_to_obj($dom->documentElement);
}

//CREATE DIRECTORY
function create_dir($path){
    if(!is_dir($path)){
        $p=explode('/',$path);
        $l='';
        foreach($p as $dir){
            if(!empty($dir)){
                $l .= $dir;
                if(!is_dir($l)){
                    mkdir($l,intval("0777",8));
                    @chown($l,"localhost");
                    @chgrp($l,"localhost");
                    $a=@fopen($l."/index.php","w+");
                    @fwrite($a,'<'.'?php  ?'.'>');
                    @chmod($l."/index.php",intval("0644",8));
                    @chown($l."/index.php","localhost");
                    @chgrp($l."/index.php","localhost");
                    @fclose($a);
                    $l .= '/';
                }
            }
        }
    }
    return $path;
}

//MOVE FILES FROM ONE FOLDER TO ANOTHER
function move_files($from,$to,$action='push',$clear=false){
    if($from && $to){
        $scan = scandir($from);
        $total = count($scan);
        foreach($scan as $c => $file){
            if($file != '.' && $file != '..') {
                if(is_file($from.$file)){
                    if($action=='pull'){
                        copy($to.$file,$from.$file);
                    } else {
                        copy($from.$file,$to.$file);
                        if($clear){
                            unlink($from.$file);
                        }
                    }
                }
                if(is_dir($from.$file)){
                    $total-=1;
                }
            }
        }
    }
    return $total;
}

//CHECK FOR VALID USER
function checkUser($user,$key){
    GLOBAL $cred_dir;
    //CHECK FOR USER FILE
    $cred = false;
    $user = strtolower($user);
    $id = md5($user);
    $file = $cred_dir.$id.'.cred';
    if(is_file($file)){
        $cred = json_decode(file_get_contents($file),true);
        if($key!=$cred['key']){
            die('Invalid user credentials');
        }
    } else {
        die('Invalid user data');
    }
    return $cred;
}

//CHECK FOR VALID KEY
function checkServiceKey($key,$service,$force=false){
    GLOBAL $service_list;
    GLOBAL $service_key;
    GLOBAL $service_token;
    $master = getMasterKey();
    if($key!=$master){
        if($force){
            die('Invalid access level');
        }
        if(in_array($service,$service_list)){
            $delim = md5('-'.date('Ymd').'-');
            $check = getServiceKey($service);
            if($check != $key){
                die('Invalid service credential');
                return false;
            }
        } else {
            die('Invalid service requested');
            return false;
        }
    } 
    return true;
}

//SET CACHE NAME
function cacheName(){
    GLOBAL $cache_dir;
    return $cache_dir.getRequestToken('cache','token').'.json';
}

//SET CACHE
function setCache($d){
    $a = cacheName();
    if(is_file($a)){
        unlink($a);
    }
    $b = getServerDetails();
    $b['cache'] = $d;
    $b['request'] = http_build_query($_REQUEST);
    $c = json_encode($b);
    file_put_contents($a,$c);
}

//GET CACHE
function getCache(){
    $c = false;
    $name = cacheName();
    if(is_file($name)){
        $file = json_decode(file_get_contents($name),true);
        $c = $file['cache'];
    }
    return $c;
}

//REMOVE CACHE
function remCache(){
    $rem = false;
    GLOBAL $cache_dir;
    $name = $cache_dir.getRequestToken('cache','token').'.json';
    if(is_file($name)){
        unlink($name);
        if(!is_file($name)){
            $rem = true;
        }
    }
    return $rem;
}

//GET REQUEST LOG
function getLog(){
    GLOBAL $res_ddir;
    GLOBAL $res_dpdr;
    //check for resourece directory
    if(!is_dir($res_ddir) ) {
        $resource_dir = $res_dpdr;
    } else {
        $resource_dir = $res_ddir;
    }
    $log = array();
    $name = $resource_dir.'request_log.txt';
    if(is_file($name)){
        $log = explode("\n",file_get_contents($name));
    }
    return $log;
}

//SET REQUEST LOG
function setLog(){
    GLOBAL $request_log;
    //GET CURRENT LOG
    $log = getLog();
    //SET CURRENT URL
    $s = $_SERVER;
    $use_forwarded_host=false;
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    $url = $protocol . '://' . $host.$s['REQUEST_URI'];
    array_unshift($log,$url);
    //SET LOG
    file_put_contents($request_log.'.txt',implode("\n",$log));
    return $log;
}

//CONVERT ARRAY TO TSV
function tsv($array){
    $keys = implode("\t",array_keys($array));
    $rows = array();
    foreach($array as $k => $v){
        $rows[] = implode("\t",$v);
    }
    return $keys."\n".implode("\n",$rows);
}

//CONVERT ARRAY TO CSV
function csv($array){
    $keys = implode(",",array_keys($array));
    $rows = array();
    foreach($array as $k => $v){
        $rows[] = implode(",",$v);
    }
    return $keys."\n".implode("\n",$rows);
}

//WAIT IF DEBUG
function wait($msg,$stop=false){
    GLOBAL $debug;
    GLOBAL $counter;
    GLOBAL $cache;
    if($debug){
        //echo '<b style="color:#2293a6">'.$counter.')</b> '.;
        echo ucwords(strtolower($msg)).'<br>';
        $counter++;
        //sleep(1);
        if($stop){
            die('-- OPERATION ENDED --');
        }
    }
}

//CLEAN STRING VALUE 
function clean($a,$d='',$c=false){
    if(!$d){
        $d='';
    }
    if(!$c){
        $c=explode(' ','~ ! @ # $ % ^ & * ( ) _ + ` - = < > , . / ? " ; : [ ] { }');
    }
    foreach($c as $b){
        if(strpos($a,$b)!==false){
            $a=implode($d,explode($b,$a));
        }
    }
    return $a;
}

//CHECK IF STRING IS DIRTY
function isdirty($s,$c=false){
    //set default
    $found=false;
    //set value
    if(!$c){
        $c=explode(' ','~ ! @ # $ % ^ & * ( ) _ + ` - = < > , . / ? " ; : [ ] { }');
    }
    //loop in string
    foreach($c as $t){
        //check if string has value
        if(strpos($s,$t)!==false){
            $found=true;
        }
    }
    return $found;
}

//EXPAND ITEMS ARRAY
function expand($items,$county){
    $c=' And ';
    $d='&';
    $h=array();
    $i='delimiter~';
    $j='.';
    $k=' ';
    $l='';
    $q=array();
    $r='';
    $m=explode(',','Estate , Heirs,Unknown ');
    //set sale address
    $a['sale_address'] = $a['address'];
    $a['date_sale'] = $a['date'];
    //loop in each item
    foreach($items as $x => $a){
        //set city from similar items
        $o=$a['cid'];
        $r=clean(trim($a['city']));
        $r=ucwords(strtolower($r));
        if(!isset($q[$o])){
            if(!empty($r)){
                $q[$o]=$r;
            }
        } else {
            if(empty($a['city'])){
                $r=$q[$o];
                $a['city']=$r;
            }
        }
        //set city from county
        if(isset($county[$o])){
            $p=trim($county[$o]['city']);
            if($p!='' && empty($r)){
              $items[$x]['city']=$p;
            }
        }
        //expand and format names
        unset($a[$i]);
        $f=$a;
        $g=$a;
        $b=$g['name'];
        //check
        if($b){
            foreach($m as $n){
                if(strpos($b,$n)!==false){
                    $b=implode($l,explode($n,$b));
                }
            }
            if(strpos($b,$c)!==false){
                $b=implode($d,explode($c,$b));
            }
            if(strpos($b,$j)!==false){
                $b=implode($l,explode($j,$b));
            }
            if(strpos($b,$k.$k)!==false){
                $b=implode($k,explode($k.$k,$b));
            }
            if(strpos($b,$d)!==false){
              $e=explode($d,$b);
              $f['name']=clean(trim($e[0]),$k);
              $g['name']=clean(trim($e[1]),$k);
              $f['relative']=$g['name'];
              $g['relative']=$f['name'];
              array_push($h,$f);
              array_push($h,$g);
            } else {
              $items[$x]['name']=clean(trim($b),$k);
              $items[$x]['relative']='';
              array_push($h,$items[$x]);
            }
        }
    }
    
    //return store
    return $h;
}

//REPEAT STRING
function repeat($s,$c){
    $a = $s;
    for($x=0;$x < $c;$x++){
        $a .= $s;
    }
    return $a;
}

//GENDER
function notify(){
    return array(
        "Always when available",
        "Only one time per day", 
        "No more than twice a week",
        "Never send notifications"
    );
}

//TEST FOR TRUE
function istrue($t){
    if($t==1||$t=='1'||$t=='yes'||$t=='on'||$t==true||$t=='true'){
        return true;
    }
    return false;
}

//TEST FOR FALSE
function isfalse($t){
    if($t==0||$t=='0'||$t=='no'||$t=='off'||$t==false||$t=='false'){
        return true;
    }
    return false;
}

//DECIPHER NAMES IN ITEMS
function decipher($item,$list){
    $d = '';
    $w = ' ';
    $z = false;
    $l = $list;
    $i = $item;
    $c = $i['city'];
    $t = $i['state'];
    $r = $i['relative'];
    $n = explode($w,$i['name']);
    //check relative
    if($r!=''){
        $r=explode($w,$r);
    } else {
        $r=false;
    }
    //look in each list
    foreach($l as $m => $j){
        $j['m']=0;
        $p=$j['name'];
        $q=$i['name'];
        //match full name
        if($p==$q){
            $j['m']+=10;
        }
        //loop in item name
        $u = array();
        //set loop
        foreach($n as $o => $t){
            //add array
            array_push($u,$t);
            $v=implode($w,$u);
            //match somewhere in name
            if(strpos($p,$t)!==false){
              $j['m']++;
            }
            //match first character in name
            if(substr($p,0,1)==substr($t,0,1)){
              $j['m']+=1.5;
            }
            //match second character in name
            if(substr($p,0,2)==substr($t,0,2)){
              $j['m']+=2;
            }
            //match whole parts in name
            if(strpos($p,$t.$w)!==false){
              $j['m']+=2.5;
            }
            //patch name blocks
            if(strpos($p,$v)!==false){
              $j['m']+=3;
            }
        }
        
        //match alias
        $x=$j['alias'];
        //check count
        if(count($x) > 0){
            //match full alias
            if($q==$x[0]){
              $j['m']+=5;
            }
            //match partial alias
            if($x[0] && strpos($x[0],$w)!==false){
                $x=explode($w,$x[0]);
                foreach($x as $y){
                    foreach($n as $o){
                        if($y==$o){
                            $j['m']+=0.25;
                        }
                    }
                }
            }
        }
        //match address
        $a=$j['address'];
        if(count($a) > 0){
            //loop in array
            foreach($a as $b){
                //match city
                if(strpos($b,$c)!==false){
                    $j['m']+=1;
                }
                //match state
                if(strpos($b,$t)!==false){
                    $j['m']+=1;
                }
            }
        }
        //match relative
        $v=$j['relative'];
        if(count($r) > 0 && count($v) > 0){
            foreach($r as $s){
                foreach($v as $u){
                    if(strpos($u,$s)!==false){
                      $j['m']+=1;
                    }
                }
            }
        }
        //set object
        $l[$m]=$j;
        //-------------------
        //-------------------
        //--TEST NAME MATCH--
        //wait('Decipher Match:'.$j['m'].' for '.$p);
        //-------------------
        //-------------------
    }

    //find most matched
    foreach($l as $m){
        if(!$z || $m['m'] > $z['m']){
            $z=$m;
        }
    }

    //just pick the first
    if(!$z && count($l) > 0){
        $z=$l[0];
    }

    //–-----------------------------------------
    //Optimize real name if absolute match found
    if($z){
        $a=strtolower(implode($d,explode($w,$z['name'])));
        $b=strtolower(implode($d,$n));
        if($a==$b){
            //replace global item
            $item['name']=$z['name'];
            //set message
            wait("Search completed with exact matches");
        } else {
            //set as alias
            $items['alias']=array($z['name']);
            //set message
            wait("Search completed with partial matches");
        }
    }
    else {
        //set message
        wait("Search completed but no matches found");      
    }
    
    //remove match property 
    unset($z['m']);
    //return 
    return array($z);
}

//SET CLEAN PARTS
function clean_parts($g){
    $v=' ';
    $w=',';
    $y=', ';
    $z=' ,';
    $g=implode($v,explode($v.$v,$g));
    $g=strtolower($g);
    $a=explode(',',strtolower('APT,GLN,RD,DR,CIR,SW,ST,AVE,CRT,POB,LN,HWY,WALK,PL,MT,BLVD'));
    $b=explode(',','Apartment,Glen,Road,Drive,Circle,South West,Street,Avenue,Court,PO Box,Lane,HighWay,Walkway,Plaza,Mount,Boulevard');
    /*
    preg_match('/\#[0-9a-z]{1,3}/i',$g, $l);
    preg_match('/\#[0-9]{1,6}/i',$g, $m);
    preg_match('/[\s0-9]{1,6}/i',$g, $n);
    preg_match('/[\#\sa-z]*[0-9]{1,6}/i',$g, $o);
    preg_match('/\#[a-z]{1}/i',$g, $p);
    preg_match('/(\#[0-9]*)/i',$g, $q);
    /
    //replace street letter with numbers
    if($l&&count($l)>0){
        if(strpos($g,$v.$l[0])!==false){
            $g=trim(implode($v.$l[0].$v,explode($v.$l[0],$g)));
        }
        if(strpos($g,$l[0].$v)!==false){
            $g=trim(implode($v.$l[0].$v,explode($l[0].$v,$g)));
        }
        $g=trim(implode($v,explode($v.$v,$g)));
    }
    //replace street number only
    elseif($m&&count($m)>0){
        if(strpos($g,$m[0])!==false){
            $g=implode($v.$m[0].$z,explode($m[0],$g));
        }
        elseif(strpos($g,$w.$m[0])!==false){
            $g=implode($v.$m[0].$z,explode($w.$m[0],$g));
        }
        if(strpos($g,$v.$m[0])!==false){
            $g=trim(implode($v.$m[0].$v,explode($v.$m[0],$g)));
        }
        if(strpos($g,$m[0].$v)!==false){
            $g=trim(implode($v.$m[0].$v,explode($m[0].$v,$g)));
        }
        $g=trim(implode($v,explode($v.$v,$g)));
    }
    //replace numbers only
    elseif($n&&count($n)>0){
        if(strpos($g,$w.$n[0])!==false){
            $g=implode($v.$n[0].$z,explode($w.$n[0],$g));
        }
        if(strpos($g,$v.$n[0])!==false){
            $g=trim(implode($v.$n[0].$v,explode($v.$n[0],$g)));
        }
        if(strpos($g,$n[0].$v)!==false){
            $g=trim(implode($v.$n[0].$v,explode($n[0].$v,$g)));
        }
        $g=trim(implode($v,explode($v.$v,$g)));
    }
    //repalce number 1
    elseif($o&&count($o)>0){
        if(strpos($g,$o[0])!==false){
            $g=implode($v.$o[0].$v,explode($o[0],$g));
            $g=trim(implode($v,explode($v.$v,$g)));
        }
    }
    //replace number 2
    elseif($p&&count($p)>0){
        if(strpos($g,$p[0])!==false){
            $g=implode($v.$p[0].$v,explode($p[0],$g));
            $g=trim(implode($v,explode($v.$v,$g)));
        }
    }
    //replace number 3
    elseif($q&&count($q)>0){
        if(strpos($g,$q[0])!==false){
            $g=implode($v.$q[0].$v,explode($q[0],$g));
            $g=trim(implode($v,explode($v.$v,$g)));
        }
    }
    */
    //loop in this
    foreach($a as $h => $i){
        $x=$b[$h];
        $u=strtolower($x);
        //check for string
        if(strpos($g,$i)!==false || strpos($g,$u)!==false){
            //set street prefix 
            if(strpos($g,$u.$v)!==false){
                $g=trim(implode($v.$u.$v,explode($u.$v,$g)));
            }
            if(strpos($g,$u.$w)!==false){
                $g=trim(implode($v.$u.$w,explode($u.$w,$g)));
            }
            //set abbreviated street prefix
            if(strpos($g,$i.$w)!==false){
                $g=trim(implode($v.$i.$w,explode($i.$w,$g)));
            }
            if(strpos($g,$i.$v)!==false){
                $g=trim(implode($v.$i.$v,explode($i.$v,$g)));
            }
            $g=trim(implode($v,explode($v.$v,$g)));
            $g=implode($v.$i.$v,explode($i.$v,$g));
            $g=implode($v.$i.$v,explode($v.$i,$g));
            $g=trim(implode($v,explode($v.$v,$g)));
            $g=implode($i.$z,explode($w.$i,$g));
            $g=implode($i.$z,explode($y.$i,$g));
            $g=implode($i.$z,explode($w.$x,$g));
            $g=implode($x.$z,explode($y.$x,$g));
            /*
            //get regest value
            preg_match('/'.$i.'/i',$g, $j);
            preg_match('/'.$x.'/i',$g, $k);
            //replace first abbreviated street prefix
            if($j&&count($j)>0){
                if(strpos($g,$y.$j[0])!==false){
                    $g=implode($v.$i.$z,explode($y.$j[0],$g));
                }
                if(strpos($g,$j[0].$v)!==false){
                    $g=trim(implode($v.$i.$v,explode($j[0].$v,$g)));
                }
                if(strpos($g,$v.$j[0])!==false){
                    $g=trim(implode($v.$i.$v,explode($j[0].$v,$g)));
                }
            }
            */
            if(strpos($g,'ea st')!==false){
                $g=implode('east',explode('ea st',$g));
            }
            if(strpos($g,'we st')!==false){
                $g=implode('west',explode('we st',$g));
            }
            if(strpos($g,'# ')!==false){
                $g=implode('#',explode('# ',$g));
            }
            $g=trim(implode($v,explode($v.$v,$g)));
            /*
            //add comma
            if(strpos($g,$i)!==false){
                $e=explode(' ',$g);
                $n=false;
                foreach($e as $c => $d){
                    if($n){
                        $e[$c]=$d.',';
                    }
                    elseif($d==$i){
                        preg_match('/[\#0-9]+/i',$e[$c+1], $f);
                        if($f&&count($f)>0){
                            $n=true;
                        } else {
                            $e[$c]=$d.',';
                        }
                    }
                }
                $g=implode(' ',$e);
            }
            //remove comma
            if(strpos($g,$w)!==false){
                $e = clean_array(explode($w,$g));
                $a = array_shift($e);
                $b = implode(' ',$e);
                $g = implode($v,explode($v.$v,$a.', '.$b));
            }
            */
        }
    }
    $g=implode($v,explode($v.$v,$g));
    $g=implode($y,explode($y.$y,$g));
    $g=implode($y,explode($y.$y,$g));
    return ucwords($g);
}

//CLEAN ARRAY VALUES
function clean_array($a){
    $b=array();
    if(is_array($a) && count($a) > 0) {
        foreach($a as $c){
            if(!is_array($c) && !is_object($c)){
                $c=trim($c);
            }
            if(!empty($c)){
                $b[]=$c;
            }
        }
    }
    return $b;
}

//CLEAN NAME
function clean_name($g,$y=''){
    $g=implode('',explode(',',$g));
    $c=explode(',','`,1,2,3,4,5,6,7,8,9,0,-,~,!,@,#,$,%,^,&,*,(,),_,+,=,:,;,",?,/,\,>,<,.,{,},[,],|');
    $d=explode(',','u00e2,u0080,u0093,u00c3,u00b1,u009b,u0083,u00c4,u00c8,u00a4');
    foreach($d as $p){
        if(strpos($g,$p)!==false){
            $g=implode($y,explode($p,$g));
        }   
    }
    foreach($c as $o){
        if(strpos($g,$o)!==false){
            $g=implode($y,explode($o,$g));
        }
    }
    return trim($g);
}

//CLEAN DATA
function clean_data($data){
    $c=$data;
    foreach($c as $d => $e){
        foreach($e as $f => $g){
            $e[$f]=clean_name($g);
        }
        $c[$d]=$e;
    }
    $data = $c;
    return $data;
}

//GET CONTACT LIST PRIMARY DATA FROM SUBMIT CONTACT DETAILS
function contact_list($primary,$fcid,$priority,$fname){
    $results=array();
    $narrow=array();
    $n = explode(' ',$fname);
    $last = array_pop($n);
    $first = array_shift($n);
    $output = NULL;
    //filter by cid, priority and name
    foreach($primary as $d){
        $d['name'] = clean($d['name']);
        if( $d['cid']==$fcid && $d['priority']==$priority && 
          ( strpos($d['name'],$last)!==false || strpos($d['name'],$first)!==false) 
        ){
            $results[] = $d;
        }
    }
    //populate matches in results
    if(count($results) > 0){
        foreach($results as $c => $r){
            $results[$c]['match']=0;
            foreach($n as $nm) {
                if(!empty($nm) && strpos($r['name'],$nm)!==false){
                    $results[$c]['match']++;
                }
            }
        }
        //sort by most matched
        foreach($results as $c => $r){
            if($output == NULL) {
                $output = $r;
            } elseif($r['match'] > $output['match']) {
                $output = $r;
            }
        }
    }
    return $output;
}

//GET TIEM ELAPSE STRING
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

//SET NUMBER OF DAYS LEFT
function days_left($sold_date){
    //set last years date if error
    if(strpos($sold_date,'<')!==false){
        $sold_date=date('m/d/').(intval(date('Y'))-1);
    }
    //track back by 7 days
    $year = 372;
    $crnt = new DateTime();
    $cfmt = $crnt->format('d/m/Y');
    $past = new DateTime($sold_date);
    $pfmt = $past->format('d/m/Y');
    $intv = $past->diff($crnt);
    $elps = intval($intv->format('%R%a'));
    $left = intval(($year-$elps));
    $look = new DateTime('now');
    $days = ($left-3);
    $plum = ($left < 0)?'-':'+';
    $look->modify($plum.$days." days");
    $date = $look->format('m/d/Y');
    return array('days'=>$days,'date'=>$date);
}

//FILTER PHONE ARRAY
function filter_phone($array) {
    $a = $array;
    $b = array();
    $r = array();
    $f = '';
    foreach($a as $c){
        $c = trim(implode('',explode('mobile',$c)));
        $c = implode('-',explode('.',trim($c)));
        $c = implode('-',explode(' ',trim($c)));
        $c = implode('',explode('(',$c));
        $c = implode('',explode(')',$c));
        $d = explode('-',$c);
        $t = array_pop($d);
        $h = array_pop($d);
        $e = array_pop($d);
        $b[] = array($e,$h,$t);
        if(!empty($e) && strlen($e)==3){
            $f = $e;
        }
    }
    foreach($b as $c => $d){
        if(empty($d[0])){
            $d[0] = $f;
        }
        if(count($d)==2){
            array_unshift($f,$d);
        }
        $r[] = implode('-',$d);
    }
    return $r;
}

//FORMAT ADDRESS STRING
function get_address($adr){
    GLOBAL $cities;

    //strings only please
    if(is_array($adr)){
        $adr = implode(',',$adr);
    }
    //parts
    //$adr = clean_parts($adr);
    //address
    $adr=implode('',explode(',',$adr));
    $adr=implode(' ',explode('  ',$adr));
    //defaults
    $o = false;
    $t = $cities;
    $a = explode(' ',trim($adr));
    $z = array_pop($a);
    $s = strtoupper(array_pop($a));
    $c = array_pop($a);
    $k = strtolower($a[count($a)-1]);
    $d = strtolower(implode(' ',$a));
    //format state
    if(strlen($s) > 2){
        $s = strtoupper(state($s)); 
    }
    //format zipcode
    if(strpos($z,'-')!==false){
        $x = explode('-',$z);
        $z = trim($x[0]);
    }
    //format city and address
    if(isset($t[$s])){
        $e=$t[$s];
        $c=ucwords($c);
        $b=array(strtolower($c));
        $j=false;
        //find match in cities
        $n=array();
        foreach($e as $f){
            $g=strtolower($f);
            $h=strtolower($c);
            if(strpos($g,$h)!==false){
                array_push($n,$g);
            }
        }
        //set city and address
        if(count($n) > 1){
            foreach($n as $k){
                if(strpos($k,' ')!==false){
                    $l = explode(' ',$k);
                    //check address for city part
                    if(strpos($d,$l[0])!==false){
                        $d = trim(implode('',explode($l[0],$d)));
                        $c = $k;
                        break;
                    }
                }
            }
        }
        //format address
        $c = strtolower($c);
        $s = strtolower($s);
        if(strpos($d,$c)!==false){
            $e = explode($c,strtolower($d));
            $d = strtolower(trim($e[0]));
        }
        //set output
        $o = array(
            'zipcode' => $z,
            'state' => strtoupper($s),
            'city' => ucwords($c),
            'address' => ucwords($d)
        );
    }
    return $o;
}

//FIX ADDRESS FORMAT
function fix_address($a){
    $i=' ';
    $j=',';
    $k='';
    $o='<br>';
    $p='#';
    $e=false;
    $f=false;
    $d='/[0-9]{1,5}/i';
    $q='/[0-9]{1,5}(th|rd|nd|st)/i';
    $g=explode($j,strtoupper('APT,GLN,RD,DR,CIR,SW,ST,AVE,CRT,POB,LN,HWY,WALK,PL,MT,BLVD'));
    $a=strtoupper($a);
    if(strpos($a,$o)!==false){
        $a=implode($k,explode($o,$a));
    }
    if(strpos($a,$j)!==false){
        $a=implode($k,explode($j,$a));
    }
    if(strpos($a,$i)!==false){
        $b=explode($i,$a);
    } else {
        $b=array($a);
    }
    if(strpos($a,$j.$i)!==false){
        $c=explode($j.$i,$a);
        $c=explode($i,$c[1]);
    } else {
        $c=explode($i,$a);
    }
    foreach($b as $e => $l){
        preg_match($d,$l,$m);
        preg_match($q,$l,$n);
        if( ($e!=0 && $e!=count($b)-1) && 
            count($m) > 0 && count($n)==0
        ){
            $b[$e]=$l.$j;
            $a=implode($i,$b);
            $f=true;
            break;
        }
    }
    if(!$f){
        foreach($b as $e => $l){
            if(strpos($l,$p)!==false){
                $b[$e]=$l.$j;
                $a=implode($i,$b);
                $f=true;
                break;
            }
        }
    }
    if(!$f){
        foreach($g as $h){
            $m=$i.$h.$i;
            $n=$i.$h.$j.$i;
            if(strpos($a,$m)!==false){
                $a=implode($n,explode($m,$a));
                $f=true;
                break;
            }
        }
    }
    if(!$f){
        $c=array_pop($b);
        $d=array_pop($b);
        $e=array_pop($b);
        $f=implode($i,array($e,$d,$c));
        $a=implode($i,$b).$j.$i.$f;
    }
    if(strpos($a,$j.$j)!==false){
        $a=implode($j,explode($j.$j,$a));
    }
    return ucwords(strtolower($a));
}

//FORMAT ADDRESS STRING
function format_address($adr,$break=false){
    $adr=implode(',',explode(',,',$adr));
    $adr=implode(', ',explode('<br>',$adr));
    $adr=trim(implode(' ',explode('  ',$adr)));
    //get address verified
    $adr=getAddressVerified($adr);
    //attempt to get address parts
    $get=get_address($adr);
    if($get){
        $adr=fix_address($get['address'].', '.$get['city'].' '.$get['state'].' '.$get['zipcode']);
    } else {
        $adr=fix_address($adr);
    }
    if($break){
        $adr = implode('<br>',explode(', ',ucwords(strtolower($adr))));
    }
    return $adr;
}

//FILTER ADDRESSES ARRAY
function filter_address_old($array){
    GLOBAL $cities;
    if(count($array) > 0) {
        $add_check = '/[0-9]{1,5}+\s[a-zA-Z\s\,\#]+/';
        $error=0;
        foreach($array as $c => $a) {
            if(strpos($a,'-')!==false || strpos($a,'#NULL')!==false){
                unset($array[$c]);
            }
        }
        foreach($array as $c => $a){
            if(strpos($a,',')===false && $array[($c+1)]){
                $array[$c] = $a.' '.$array[$c+1];
                unset($array[$c+1]);
            }
        }
        foreach($array as $c => $a){
            if(!preg_match($add_check,$a)) {
                unset($array[$c]);
            }
        }
        foreach($array as $count => $adr) {
            $a = explode(',',$adr);
            $b = explode(' ',$a[0]);
            $c = $b[count($b)-1].',';
            $d = explode($c,$adr);
            $g = $d[0].'<br>'.$c.' '.$d[1];
            $k = $g;
            $h = explode('  ',trim($d[0].' '.$c.' '.$d[1]));
            $h = explode(' ',trim(implode(' ',$h)));
            $h = implode('',explode(',',implode(' ',$h)));
            $h = implode(' ',explode('  ',$h));
            $h = ucwords(strtolower($h));
            $h = implode('',explode(',',$h));
            $i = explode(' ',$h);
            $z = array_pop($i);
            $s = array_pop($i);
            $s = strtoupper($s);
            $t = array_pop($i);
            $u = array_pop($i);
            $o = ucwords(strtolower($s));
            $v = $t.' '.$s;
            $w = $u.' '.$t.' '.$s;
            $y = explode(',','City,Park,View,Lake,Heights,Consequences,Paul');
            $h = implode(' '.strtoupper($o).' ',explode(' '.$o.' ',$h));
            $j = false;
            $city = $t;
            if(!empty($city)){
                foreach($y as $z){
                    if($t==$z){
                        $city = $u.' '.$z;
                        $x = $city.' '.$s;
                        if($t=='Consequences'){$city='Truth or '.$t;$x=$city.' '.$s;}
                        if($t=='Paul'){$city = 'Saint '.$t;$x = $city.' '.$s;}
                        if(strpos($h,$x)!==false){
                            $k = implode('<br>'.$x,explode($x,$h));
                            $j=true;
                            break;
                        }
                    }
                }
                if(!$j){
                    if(in_array($w,$cities)){
                        $city = $u.' '.$t;
                        $k = implode('<br>'.$w,explode($w,$h));
                    }else{
                        $k = implode('<br>'.$v,explode($v,$h));
                    }
                }
                if(strpos($k,$city)!==false){
                    $k = implode($city.',',explode($city,$k));
                    $m = explode('<br>',$k);
                    $m[0] = trim($m[0]);
                    $n = explode(',',$m[1]);
                    if(count($n)==3){
                        $m[0]=$m[0].' '.array_shift($n);
                        $m[1]=implode(',',$n);
                    }
                    $k = implode('<br>',$m);
                }
                $array[$count] = $k;
            } else {
                $error++;
            }
        }
    }
    return $array;
}

//FILTER ADDRESSES ARRAY
function filter_address($array,$delim=','){
    GLOBAL $cities;
    GLOBAL $res_ddir;
    GLOBAL $res_dpdr;
    //check for resourece directory
    if(!is_dir($res_ddir) ) {
        $resource_dir = $res_dpdr;
    } else {
        $resource_dir = $res_ddir;
    }
    $cfile = $resource_dir.'cities_states';
    if(count($array) > 0) {
        $part = implode('',explode(';',implode('|',$array)));
        $part = implode('',explode(',',$part));
        $start = explode('|',$part);
        $output = array();
        $string = array();
        foreach($start as $addr){
            //remove phone numbers      
            if(strpos($addr,'(')!==false){
                $a = explode('(',$addr);
                $addr = $a[0];
            }
            $a = explode(' ',trim($addr));
            $z = array_pop($a);
            $s = array_pop($a);
            $c = array_pop($a);
            $t = strtoupper($s);
            $d = implode(' ',$a);
            $o = ucwords($d.', '.$c).' '.$t.' '.substr($z,0,5);
            $r = format_address($o);
            $u = implode('',explode(',',$r));
            $u = implode('',explode('#',$u));
            $u = implode('',explode(' ',$u));
            $u = strtolower($u);
            if(!in_array($u,$string)){
                array_push($string,$u);
                $output[] = implode('<br> ',explode(', ',$r));
            }
        }
    }
    return array($cities,$output);
}

//TAKE PARTS OF ADDRESSES AND NORMALIZE THEM
function normalize_address($list){
    $list = implode(' ',explode("\n",$list));
    $list = implode(' ',explode(';',$list));
    $list = implode(' ',explode('<br>',$list));
    $list = implode(' ',explode(',',$list));
    $list = implode(' ',explode('  ',$list));
    $list = implode(' ',explode('  ',$list));
    $list = implode('|',explode('| ',$list));
    $list = trim($list);
    preg_match('/[a-z\s]+\s[0-9]{3}[\-\s\(\)]+[0-9]{3}[\s\-]+[0-9]{4}/',$list,$nmnb);
    preg_match('/[a-zA-Z]+/',$list, $word);
    preg_match('/[0-9]+/',$list, $nmbr);
    if(empty($list) || count($nmnb) > 0){
        return array();
    }
    //return names, emails, phone numbers or ages
    if (strlen($list) < 20 || strpos($list,'@')!==false || count($word)==0 || count($nmbr)==0){
        return $list;
    }
    $b = explode('|',strtolower($list));
    $ad = array(); 
    $ct = array();
    $st = array();
    $zp = array();
    $un = array();
    //reverse
    $mrv = '/[a-z]+\s[a-z]{2}\s[0-9]{5,6}+/';
    //address
    $mad = '/[0-9]+\s?[a-z\#]+/';
    //po box
    $mob = '/[p\.o]+?(\sbox)*\s?[0-9]+/';
    //state
    $mst = '/\b[a-z]{2}$\b/';
    //zipcode
    $mzc = '/[0-9]{5}/';
    //city
    $mct = '/[a-z]{3,12}+/';

    if(is_array($b)){
        //add up parts
        foreach($b as $c) {
            $c = trim($c);
            //match reverse
            preg_match($mrv,$c, $csz);
            //match address
            preg_match($mad,$c, $adr);
            //match p.o. box
            preg_match($mob,$c, $pob);
            //match state
            preg_match($mst,$c, $sta);
            //match city
            preg_match($mct,$c, $cty);
            //match zipcode
            preg_match($mzc,$c, $zip);
            //add from reverse 
            if(count($csz)>0){
                $e=explode(' ',$csz[0]);
                $f=explode($csz[0],$c);
                $ct[]=trim($e[0]);
                $st[]=trim($e[1]);
                $zp[]=trim($e[2]);
                $ad[]=trim($f[1]);
            //add from normal
            } else {
                //add address
                if(count($adr) > 0) {
                    $ad[]=trim($adr[0]);
                } elseif (count($pob) > 0){
                    $ad[]=trim($pob[0]);
                }
                //add state
                if(count($sta) > 0) {
                    $st[]=trim($sta[0]);
                }
                //add city
                if(count($cty) > 0) {
                    $ct[]=trim($cty[0]);
                }
                //add zipcode
                if(count($zip) > 0) {
                    $zp[]=trim($zip[0]);
                }
            }
        }
    }
    $out = array();
    foreach($ad as $c => $v){
        //match word (quick)
        preg_match('/[a-zA-Z]+/',$v, $adr);
        if(!empty($v) && count($adr) > 0) {
            $a = explode(' ',$v);
            $v = implode(' ',array_unique($a));
            $out[] = ucwords($v.', '.$ct[$c]).' '.strtoupper($st[$c]).' '.$zp[$c];
        //match word (long)
        } elseif(!empty($ct[$c]) && !empty($st[$c]) && !empty($zp[$c])) {
            $a = explode($ct[$c],$b[$c]);
            $v = explode(' ',$a[0]);
            $t = explode(' ',$a[1]);
            $w = '';
            if(count($v)>4){
                $w = array_pop($v).' ';
            }
            $a[0] = implode(' ',$v);
            $out[] = ucwords($a[0].','.$w.$t[0]).' '.strtoupper($t[1]).' '.$t[2];
        }
    }
    if(count($out)==0){
        if(count($ad) > 0){
            die(json_encode(array($ad,$ct,$st,$zp)));
        } else {
            die('Address not parsable: '.$list);
        }
        return false;
    } else {
        return $out;
    }
}

//PARSE ALL CONTACTS FROM CSV TO JSON
function parse_contacts($filename,$cache){
    if($cache && is_file($filename.'.json')){
        return json_decode(file_get_contents($filename.'.json'),true);
    }
    $t = file_get_contents($filename.'.csv');
    $c = implode("|",explode("\n",$t));
    $c = implode("",explode('"',$c));
    $c = explode("~",implode("; ",explode(", ",$c)));
    $cnts = array();
    $keys = array();
    // Parse CSV into JSON
    foreach($c as $b => $a){
        //
        if($b == 0){
            $keys = explode(",",implode('_',explode(' ',strtolower($a))));
        } else {
            //normalize phone numbers
            $a = implode('',explode('(',implode('-',explode(') ',$a))));
            //replace basic words
            $a = implode('',explode('icon',$a));
            //add delimiter between addresses
            $d = explode(",",$a);
            //match address part 1
            $e = array();
            foreach($d as $k => $v){
                if($keys[$k]!='delimiter'){
                    //match cid
                    if($keys[$k]=='cid'){
                        $e[$keys[$k]] = implode('',explode('|',$v));
                    }
                    //match timestamp
                    elseif($keys[$k]=='timestamp'){
                        $e[$keys[$k]] = implode('',explode('|',trim($v)));
                    }
                    //match city or full name
                    elseif($keys[$k]=='city'||$keys[$k]=='full_name'){
                        $e[$keys[$k]] = ucwords(strtolower($v));
                    }
                    elseif(strpos($keys[$k],'phone')!==false || strpos($keys[$k],'email')!==false) {
                        $e[$keys[$k]]=array();
                        $pe = explode('|',trim($v));
                        foreach($pe as $s){
                            $s = trim($s);
                            if(!empty($s)){
                                $e[$keys[$k]][] = $s;
                            }
                        }
                    } 
                    elseif(strpos($keys[$k],'address')!==false) {
                        $result = filter_address(explode('|',$v),';');
                        $cities = $result[0];
                        //match address
                        if(is_array($result[1])){
                            $e[$keys[$k]] = $result[1];
                        }
                    } else {
                        $e[$keys[$k]] = trim($v);
                    }
                }
            }
            //add new data
            $cnts[] = $e;
        }
    }
    file_put_contents($filename.'_raw.json',json_encode($cnts));
    if(is_file($filename.'_raw.json')){
        return $cnts;
    } else {
        die('Could not parse file: '.$filename);
    }
}

//PARSE ASSOCIATIVE ARRAY TO TSV
function array_to_tsv($array){
    //get tsv data
    $tsv = array();
    foreach($array as $k => $j){
        $o = array();
        $h = array();
        foreach($j as $c => $v){
            array_push($o,$v);
            if($k == 0){
                array_push($h,$c);
            }
        }
        if($k==0){
            array_push($tsv,implode("\t",$h));
        }
        array_push($tsv,implode("\t",$o));
    }
    return $tsv;
}

//PARSE ASSOCIATIVE ARRAY TO CSV
function array_to_csv($row){
    $l = array();
    if(is_array($row)){
        foreach($row as $r){
            if(is_array($r)){
                $r = implode(', ',$r);
            }
            $l[]=$r;
        }
    }
    return $l;
}

//PARSE ASSOCIATIVE ARRAY TO XML
function array_to_xml($root_element_name,$ar){ 
    $xml = new SimpleXMLElement("<{$root_element_name}></{$root_element_name}>"); 
    $f = create_function('$f,$c,$a',' 
            foreach($a as $k=>$v) { 
                if(is_array($v)) { 
                    $ch=$c->addChild($k); 
                    $f($f,$ch,$v); 
                } else { 
                    $c->addChild($k,$v); 
                } 
            }'); 
    $f($f,$xml,$ar); 
    return $xml->asXML(); 
}

//CONVERT PDF AND DOC TO TEXT
function file_to_text($path){
    GLOBAL $convert_dir;
    include_once($convert_dir.'pdf-v1/lib/class.filetotext.php');
    $docObj = new Filetotext($path);
    return $docObj->convertToText();
}

//RBG TO HEX
function rgb2hex($rgb){
    //check if not array 
    if ( !is_array($rgb) && strpos($rgb, '(') !== false ) {
        $a = explode('(', $rgb);
        $b = explode(')', $a[1]);
        $c = implode('',explode(' ',$b[0]));
        $d = explode(',', $c);
        $rgb = array(
            'r' => $d[0],
            'g' => $d[1],
            'b' => $d[2],
            'o' => $d[3]
        );
    }
    if(is_array($rgb)){
        return '#' . sprintf('%02x', $rgb['r']) . sprintf('%02x', $rgb['g']) . sprintf('%02x', $rgb['b']);
    } else {
        return '';
    }
}

//HEX TO RGB
function hex2rgb($hex, $opacity='',$as='string') {
    //replace hash with empty string
    $hex = str_replace("#", "", $hex);
    $typ = 'rgb';
    //check for hex
    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    $rgb = array($r, $g, $b);
    //check opacity
    if( !empty($opacity) ) {
        $rgb[] = $opacity;
        $typ = 'rgba';
    }
    //return as string (default)
    if($as == 'string'){
        return $typ.'('.implode(", ", $rgb).')';
    } else {
        return $rgb;
    }
}

//DOWNLOAD DATA FROM LIST AND URL
function downloadData($list,$url,$delay=3000,$callback=''){
    GLOBAL $protocol;
    $done = 'All done';
    $anch = '';
    foreach($list as $l){
        $anch .= '<a href="javascript:void(0);" onClick="fopen(\''.$url.implode('_',explode(' ',$l)).'\')" target="frame" class="links">'.ucwords($l).'</a><br>';
    }
    $style = '#message{text-align:center;font-size:12pt;width:99%;margin-bottom:10px;}.links.done{color:red;}';
    $style .= '#frame{display:inline-block;width:70%;height:500px;float:left;border:1px solid silver;background:#f4f4f4}';
    $style .= '#panel{display:inline-block;width:29%;height:500px;float:left;border:1px solid silver;margin-right:5px;padding:5px;overflow:auto;}';
    $html = '<html><head><link rel="stylesheet" type="text/css" href="'.$protocol.'//nfrs.us/data/css/bootstrap.min.css" media="all">';
    $html .= '<script src="'.$protocol.'//nfrs.us/data/js/jquery-2.1.4.min.js"></script></head>';
    $html .= '<body><div id="message"><b>Running resources from: <a href="'.$url.'" target="_self">'.$url.'</a></b><br></div>';
    $html .= '<style type="text/css">* a{color:#2293a6!important;}'.$style.'</style><div id="panel">'.$anch.'</div><iframe src="" id="frame"></iframe>';
    $html .= '<div style="clear:both;"></div><script type="text/javascript">';
    $html .= 'var frame,interval,links,click,fopen,count=0,getid=function(d){return document.getElementById(d)};';
    $html .= 'click=function(item){var t=document.createEvent("MouseEvents");var l=getid(item);t.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);(t)?l.dispatchEvent(t):(l.click && l.click());};';
    $html .= 'fopen=function(url){frame=getid("frame");frame.src=url;},links=document.getElementsByClassName("links");';
    $html .= 'interval=window.setInterval(function(){if(links[count]){links[count].id="a"+count;links[count].className="links done";click(links[count].id)}';
    $html .= 'else{window.clearInterval(interval);getid("message").innerHTML="All done...";(function(w){'.$callback.'})(window);}count++;},'.$delay.');';
    $html .= '</script></body></html>';
    die($html);
}

//SET HTML FORM DATA
function formData($data,$action,$script='',$hidden=array(),$entry=array(),$value=false){
    //set protocol
    GLOBAL $protocol;
    //-------------
    //set defaults
    //-------------
    $field = '';
    $ids = explode(',','claimid,claim_id,cid,id,alias,nkey,key,sibling,to_name,to_name_upper,your_name,your_first_name,your_last_name,last_modified,priority');
    $textarea = explode(',','notes,comment,summary');
    $numbers = explode(',','amount,value,your_amount,our_amount,total_amount,revision_number,max_percent');
    $view = 'short';
    $views = array(
        'wide'=>array(12,12),
        'short'=>array(3,8)
    );
    $wide = array('progress','comment','status');
    //set as wide
    foreach($wide as $w){
        if(isset($data[$w])){
            $view='wide';
        }
    }
    //set  view
    $e = $views[$view];

    //------------------
    //add hidden fields
    //------------------
    if(count($hidden) > 0){
        foreach($hidden as $k => $v){
            $field .= '<input type="hidden" name="'.$k.'" value="'.$v.'" id="hidden'.ucwords($k).'">';
        }
    }
    //------------------
    //add static fields
    //------------------
    if(count($entry) > 0){
        foreach($entry as $k => $v){
            if(!empty($v)){
                $a = explode(' ',$v);
                foreach($a as $b => $c){
                    if(strpos($c,'http:')!==false||strpos($c,'https:')!==false){
                        $a[$b] = '<a href="'.$c.'" target="_blank">'.$c.'</a>';
                    }
                    elseif(strpos($c,'@')!==false && strlen($c) > 8){
                        $a[$b] = '<a href="mailto:'.$c.'" target="_blank">'.$c.'</a>';
                    }
                }
                $v = implode('<br>',explode('\n',implode(' ',$a)));
                $n = ucwords(implode(' ',explode('_',$k)));
                $field .= '<div class="form-group">';
                $field .= '<label for="input'.ucwords($k).'" class="col-sm-'.$e[0].' control-label">'.$n.'</label> ';
                $field .= '<div class="col-sm-'.$e[1].'">';
                $field .= '<p class="form-control-static">'.$v.'</p>';
                $field .= '</div></div>';
            }
        }
    }
    //----------------
    //add data fields
    //----------------
    foreach($data as $k => $v){
        //format key and values
        $k = trim(strtolower($k));
        //set value as empty if null
        if($v=='null'||$v==null){$v='';}
        //-------------------
        //set select progress
        //-------------------
        if($k=='progress'||$k=='status'){
            $opt='';
            foreach($v as $a){
                if(strpos($a,'<')!==false){
                    $opt.=$a;
                } else {
                    if($value && $a==$value){
                        $s=' selected="selected"';
                    } else {
                        $s='';
                    }
                    $opt.='<option value="'.$a.'"'.$s.'>'.$a.'</option>';
                }
            }
            if(count($v) > 16){
                $w=(count($v)-8);
            } else {
                $w=count($v);
            }
            $field .= '<div class="form-group">';
            //$field .= '<label for="select'.ucwords($k).'" class="col-sm-'.$e[0].' control-label">Set '.ucwords($k).'</label> ';
            $field .= '<div class="col-sm-'.$e[1].'">';
            $field .= '<select name="'.$k.'" id="select'.ucwords($k).'" title="'.$n.'" class="form-control '.$k.'" size="'.$w.'">'.$opt.'</select>';
            $field .= '</div></div>';
        }
        //------------------
        //set as group input
        //------------------
        elseif(is_array($v)){
            $script .= 'var '.$k.'={};';
            $field .= '<div class="section group"><fieldset><legend>'.ucwords($k).':</legend>';
            foreach($v as $a => $b){
                if(is_array($b)){
                    $script .= 'if(!'.$k.'.hasOwnProperty("'.$a.'")){'.$k.'["'.$a.'"]=[];};';
                    foreach($b as $c => $d){
                        $id = array(ucwords($k),ucwords($a),ucwords($c));
                        $n = ucwords(ucwords($a).' '.ucwords($c));
                        //set address
                        $class = array();
                        $address = '';
                        if(strpos($a,'address')!==false){
                            $class[] = 'address';
                            $d = implode(',',explode('<br>',$d));
                        }
                        //extras
                        $extras = '';
                        if(count($class) > 0){
                            $extras = ' class="'.implode(' ',$class).'"';
                        }
                        $script .= $k.'["'.$a.'"].push("'.$d.'");';
                        $field .= '<div class="form-group">';
                        $field .= '<label for="input'.implode('',$id).'" class="col-sm-'.$e[0].' control-label">'.$n.'</label> ';
                        $field .= '<div class="col-sm-'.$e[1].'">';
                        $field .= '<input type="text" name="'.$k.'[\''.$a.'\']['.$c.']" value="'.$d.'" id="input'.implode('',$id).'" placeholder="'.$n.'"'.$extras.'>';
                        $field .= '</div></div>';
                    }
                } else {
                    $id = array(ucwords($k),ucwords($a));
                    $n = ucwords(implode(' ',explode('_',implode(' ',$id))));
                    $script .= $k.'["'.$a.'"]="'.$b.'";';
                    $field .= '<div class="form-group">';
                    $field .= '<label for="input'.implode('',$id).'" class="col-sm-'.$e[0].' control-label">'.$n.'</label> ';
                    $field .= '<div class="col-sm-'.$e[1].'">';
                    $field .= '<input type="text" name="'.$k.'[\''.$a.'\']" value="'.$b.'" id="input'.implode('',$id).'" placeholder="'.$n.'">';
                    $field .= '</div></div>';
                }
            }
            $field .= '</fieldset></div>';
        }
        //---------------
        //set as textarea
        //---------------
        elseif(in_array($k,$textarea)){
            $n = ucwords(implode(' ',explode('_',$k)));
            $field .= '<div class="form-group">';
            $field .= '<label for="input'.ucwords($k).'" class="col-sm-'.$e[0].' control-label">'.$n.'</label> ';
            $field .= '<div class="col-sm-'.$e[1].'">';
            $field .= '<textarea name="'.$k.'" id="textarea'.ucwords($k).'" placeholder="'.$n.'" class="form-control" rows="10">'.$v.'</textarea>';
            $field .= '</div></div>';
        }
        //------------
        //set as input
        //------------
        else{
            //trim value
            $v = trim($v);
            //set disabled
            $class = array();
            $disabled = '';
            if(in_array($k,$ids) || in_array($k,$numbers)){
                $class[] = 'disabled';
                $disabled = ' disabled';
            }
            //set name
            $n = ucwords(implode(' ',explode('_',$k)));
            //set address
            $address = '';
            if(strpos($k,'address')!==false){
                $class[] = 'address';
                $v = implode(',',explode('<br>',$v));
            }
            //extras
            $extras = '';
            if(count($class) > 0){
                $extras = ' class="'.implode(' ',$class).'"';
            }
            //set field
            $field .= '<div class="form-group">';
            $field .= '<label for="input'.ucwords($k).'" class="col-sm-'.$e[0].' control-label">'.$n.'</label> ';
            $field .= '<div class="col-sm-'.$e[1].'">';
            $field .= '<input type="text" name="'.$k.'" value="'.$v.'" id="input'.ucwords($k).'" placeholder="'.$n.'"'.$disabled.$extras.'>';
            $field .= '</div></div>';
        }
    }

    //---------------
    //add repeat html
    //---------------
    $repeat = '<div id="repeat">';
    $repeat .= '<div class="form-group new-group" id="parent[ID]">';
    $repeat .= '<label for="input[ID]" class="col-sm-'.$e[0].' control-label">[TITLE]</label> ';
    $repeat .= '<div class="col-sm-'.$e[1].'"><span class="fa fa-close" id="close[ID]"></span>';
    $repeat .= '<input type="text" name="[KEY]" value="[VALUE]" id="input[ID]" placeholder="[NAME]" class="ignore"></div></div>';
    $repeat .= '</div>';
    //---------------
    //add bulk editor
    //---------------
    $editor = '<div class="form-group" id="field_editor"><h1>Add/Update Fields</h1>';
    $editor .= '<textarea id="edit_text" class="form-control ignore" rows="10" placeholder="field_name: some field values..."></textarea>';
    $editor .= '<div class="button-group"><button type="button" id="update_field" class="btn btn-primary right"><spam class="fa fa-pencil"></span> Update Field</button>';
    $editor .= '<button type="button" id="add_field" class="btn btn-default right"><spam class="fa fa-plus"></span> Add Field</button></div>';
    $editor .= '</div>';
    //--------------
    //add button html
    //--------------
    $button = '<div class="form-group" id="buttons">';
    $button .= '<button type="button" id="redo" class="btn btn-default left"><spam class="fa fa-repeat"></span> Redo</button>';
    $button .= '<button type="button" id="undo" class="btn btn-default left"><spam class="fa fa-undo"></span> Undo</button>';
    $button .= '<button type="button" id="revert" class="btn btn-default left"><spam class="fa fa-recycle"></span> Reset</button>';
    $button .= '<button type="submit" id="submit" class="btn btn-primary right"><spam class="fa fa-paper-plane"></span> Save</button>';
    $button .= '<button type="button" id="refresh" class="btn btn-default right" title="Refresh"><spam class="fa fa-refresh"></span></button>';
    $button .= '<button type="button" id="wide" class="btn btn-default right" title="Wide"><spam class="fa fa-arrows-h"></span></button>';
    $button .= '<button type="button" id="editor" class="btn btn-default right" title="Editor"><spam class="fa fa-th-list"></span></button>';
    $button .= '<div id="success" class="message bg-success"></div><div id="error" class="message bg-danger"></div>';
    $button .= '<div style="clear:both"></div>'.$editor.'</div><div id="clear"></div>';


    //--------------
    //add form html
    //--------------
    $form = '<form action="'.$action.'" method="POST" target="results" id="form" class="form-horizontal">';
    $form .= $button.$field.$repeat;
    $form .= '</form>';
    //--------------
    //add page style
    //--------------
    $style = '.message{display:block;font-size:10pt;text-align:center;padding:8px 10px;margin-top:40px;clear:both;width:100%;max-width:570px!important}.right{float:right;}.left{float:left;}';
    $style .= '.disabled,button[disabled],html input[disabled]{background:rgb(229,228,228);color:gray!important;border:1px solid silver;}';
    $style .= 'input{height:35px;width:100%;padding:5px;}body{background:transparent;}form{border:1px solid silver;background:#f4f4f4;}#clear{clear:both;margin-top:50px;transition: margin-top 2s ease-in-out;}';
    $style .= '#form{display:block;border:1px solid rgba(192, 192, 192, 0.28);margin-right:5px;padding:5px;overflow:auto;transition:width 1s;-webkit-transition:width 1s;}';
    $style .= '#form-wrapper{width:100%;display:block;transition:width 1s;-webkit-transition:width 1s;}#clear.editor{margin-top:300px;transition: margin-top .5s ease-in-out;}';
    $style .= '.message,form{max-width:600px;width:100%;margin:10px 0px:}hr{margin:2px;padding:0px;}select.progress{height:auto;background:#fff}';
    $style .= '#form-wrapper > div{transition:width 1s;-webkit-transition:width 1s;margin:0px auto;max-width:612px;background:rgba(154, 154, 154, 0.17);}';
    $style .= '#buttons{max-height:97px;background:#fff;top:0px;border:1px solid silver;width:100%;max-width:612px;position:fixed;z-index:9;margin:0px -6px!important;padding:10px 10px;z-index:9;transition:width 1s;-webkit-transition:width 1s;}';
    $style .= '.left{margin-right:5px!important;}.right{margin-left:5px!important;}.wide #buttons,.wide .message{width:80%!important;transition:width 1s;-webkit-transition:width 1s;}';
    $style .= '#form-wrapper.wide > div{width:81%!important;transition:width 1s;-webkit-transition:width 1s;}';
    $style .= 'legend{border-bottom: 4px solid rgba(255, 255, 255, 0.76);border-top: 1px solid silver;padding: 2px 10px;background-color: rgba(192, 192, 192, 0.31);}';
    $style .= '.wide #form{width:99.5%!important;transition:width 1s;-webkit-transition:width 1s;}.form-group button{font-size:9pt;}';
    $style .= '#field_editor{display:none;padding:20px;background:rgba(192, 192, 192, 0.42);margin:0px;margin-top:10px;}#field_editor .button-group{padding:10px 0px;}';
    $style .= '#field_editor h1{color:black;font-size:14pt;text-align:center;display:block;padding:0px;margin:0px 0px 10px 0px}';
    $style .= '#field_editor #add_field{background-color:#DAF9D3;color:green;border-color:rgba(39, 105, 59, 0.23);}#add_field:hover{background-color:#CAE6C4;}';
    $style .= '#edit_text{width:100%;height:100px;border:1px solid silver;padding:10px;font-size:10pt;color:black;}';
    $style .= '.form-horizontal{width:100%!important;}.form-horizontal .form-group{margin-left:0px!important;width:99%!important}';
    $style .= '#repeat{display:none;}.new-group{background-color:#DAF9D3;position:relative;padding-top:10px;padding-bottom:10px;}';
    $style .= '.form-group .col-sm-8{width:74%;!important;display:inline-block!important;}';
    $style .= '.form-group .col-sm-3{width:25%!important;text-align:right!important;padding-left:0px!important;}';
    $style .= '.new-group div{position:relative;} .new-group div span.fa{position:absolute;top:10px;right:0px;color:red;cursor:pointer}';
    //small screen size css
    $style .= '@media (max-width:478px){';
    $style .= '#clear{margin-top:100px;}.form-group .col-sm-8{width:70%!important;}#buttons{padding-left:0px;position:fixed;top:0px;}#submit{width:125px;}';
    $style .= '.form-group button{float:right!important;font-size:10pt;margin-left:15px!important;margin-bottom:15px!important;}';
    $style .= '}';
    
    //--------------
    //add body html
    //--------------
    $html = '<html><head><link rel="stylesheet" type="text/css" href="'.$protocol.'//nfrs.us/data/css/bootstrap.min.css" media="all">';
    $html .= '<link rel="stylesheet" type="text/css" href="'.$protocol.'//nfrs.us/data/css/font-awesome.min.css" media="all">';
    $html .= '<style type="text/css">'.$style.'</style><script type="text/javascript">'.$script.'</script>';
    $html .= '<script src="'.$protocol.'//nfrs.us/data/js/jquery-2.1.4.min.js"></script>';
    $html .= '<script src="'.$protocol.'//nfrs.us/data/js/store-editor.js"></script></head>';
    $html .= '<body><div id="form-wrapper"><div id="form-body">'.$form.'</div></div>';
    $html .= '</body></html>';
    //set html
    die($html);
}

//MERGE TWO ARRAYS BY APPENDING VALUES
function append_merge($a,$b){
    $e=array();
    foreach($a as $k => $v){
        //convert object to array
        if(is_object($v)){
            $u = array();
            foreach($v as $x){
                array_push($u,$x);
            }
            $v = $u;
        }
        $e[$k] = $v;
        //check if value is an array
        if( is_array($v) && isset($b[$k]) ){
            $c = $b[$k];
            //convert object to array
            if(is_object($c)){
                $t = array();
                foreach($c as $d){
                    if(!empty($d)){
                        array_push($t,$d);
                    }
                }
                $c = $t;
            }
            //merge array of values
            if(is_array($c)){
                if(count($c) > 0){
                    foreach($c as $w){
                        if(!in_array($w,$v) && !empty($w)){
                            array_push($e[$k],$w);
                        }
                    }
                }
            } 
            //append single value
            elseif(!in_array($c,$v) && !empty($c)){
                array_push($e[$k],$c);
            }
        } else {
            $c = $b[$k];
            //append single values
            if(empty($v) && !empty($c)){
                $e[$k] = $c;
            } 
            //create array if values are mixed
            //elseif ($v != $c){
                //$e[$k] = array_push($v,$c);
            //}
        }
    }
    return $e;
}

//FORMAT NAME BY ASSOCIATIONS
function format_name($s){
    $a = ' Aka ';
    $s = ucwords(clean($s));
    if(strpos($s,$a)!==false){
        $s = implode('',explode($a,$s));
    }
    return $s;
}

//FORMAT NAME KEY WITH A DASHED DELIM
function format_name_key($s){
    $s = implode('-',explode(' ',$s));
    $s = implode('',explode('.',$s));
    $s = strtolower($s);
    return $s;
}

//EXPAND THE VALUES IN LIST DETAILS
function expand_details($list){
    $l = $list;
    foreach($l as $idx => $row){
        $a=array();
        $b=$row['details'];
        foreach($b as $c => $d){
            $d[]=$row[$c];
            $d=clean_array($d);
            $d=array_unique($d);
            if(count($d) > 0){
                foreach($d as $e => $f){
                    $l[$idx][$c.'-'.$e] = $f;
                }
            }
        }
        unset($l[$idx]['details']);
    }
    return $l;
}

//QUERY THE ARRAY USING SQL LIKE OPERATORS
function query_in_array($where='',$array=array()){

    //-----------------------------------------------------
    //every operator is wrapped with pipes ie: |operator|
    //name|like|john,amount|>=|50,date|in|2015,cid|not|4013
    //amount|between|50-100, etc.. using comma for multiple
    //------------------------------------------------------
    
    //operators
    $operators = array('<>','>=','<=','=','>','<','between','like','in','not');

    //set default output
    $out = array();

    //check for where clause
    if(!empty($where)){
        //where 
        $w=$where;
        //set array
        $z=$array;
        //operators
        $o=$operators;
        //new array
        $c=array();
        //list array
        $b=array();
        //where array
        $a=array();
        //where list array
        $x=array($w);
        //clean number
        $u=array(',','$','-');
        //pipes
        $j='|operator|';
        //set number operators
        $n=array('>=','<=','<','>');
        //get multiple conditions
        if(strpos($w,',')!==false){
            $x=explode(',',$w);
        }

        //loop in operator
        foreach($o as $p){
            //loop in where
            foreach($x as $d){
                //shorthand
                $h=$p;
                //piped operator
                $j='|'.$p.'|';
                //check for property syntax
                if(strpos($d,$j)!==false){
                    //get key and value
                    $y = explode($j,$d);
                    //set shorthand like
                    if($p=='like'){
                        $h='lk';
                    }
                    //set shorthand not
                    if($p=='not'){
                        $h='nt';
                    }
                    //set value as range
                    if($p=='between'){
                        //set range value by delimiter
                        if(strpos($y[1],'-')!==false){
                            $g=explode('-',$y[1]);
                            $y[1]=array(
                                floatval(clean($g[0],'',$u)),
                                floatval(clean($g[1],'',$u))
                            );
                        }
                        //set range from zero to value
                        else{
                            $f=floatval(clean($y[1],'',$u));
                            $g=array(0,$f);
                            $y[1]=$g;
                        }
                        //set shorthand name
                        $h='bt';
                    }
                    //set value as number
                    elseif(in_array($p,$n)){
                        $y[1]=floatval(clean($y[1],'',$u));
                    }
                    //set value as string
                    else{
                        $y[1]=strtolower($y[1]);
                    }
                    //set key as lowercase
                    $y[0]=strtolower($y[0]);
                    //push array
                    array_push($a,array(
                        'key'=>$y[0],
                        'val'=>$y[1],
                        'opr'=>$h
                    ));
                }
            }
        }

        //die(json_encode($a));
        //die(json_encode($z));

        //check for array
        if(count($a) > 0){
            //find total
            $tt=count($a);
            //loop in items
            foreach($z as $k => $v){
                //set found
                $fn=0;
                //loop in conditions
                foreach($a as $c => $i){
                    //set operator
                    $op = $i['opr'];
                    $ky = $i['key'];
                    $vl = $i['val'];
                    //check for key in item
                    if(isset($v[$ky])){
                        //set item lowercase
                        $it=strtolower($v[$ky]);
                        //set item number
                        $in=floatval(clean($it,'',$u));
                        //check
                        if( //between range
                            ($op=='bt' && $in >= $vl[0] && $in <= $vl[1]) ||
                            //like string match
                            ($op=='lk' && strpos($it,$vl)!==false) ||
                            //found in string
                            ($op=='in' && strpos($it,$vl)!==false) ||
                            //not found
                            ($op=='nt' && strpos($it,$vl)===false) ||
                            //not equal to
                            ($op=='<>' && $it != $vl) ||
                            //greater than equal to
                            ($op=='>=' && $in >= $vl) ||
                            //less than equal to
                            ($op=='<=' && $in <= $vl) ||
                            //equal to
                            ($op=='=' && $in == $vl) ||
                            //greater than
                            ($op=='>' && $in > $vl) ||
                            //less than
                            ($op=='<' && $in < $vl)
                        ){
                            $fn++;
                        }
                    }
                }
                //add if matched
                if($fn==$tt){
                    array_push($out,$v);
                }
            }
        }
    }

    //return output
    return $out;
}

//RETURN IF NOT FOUND
function find_in_array($key,$array){
    //set found
    $found=false;
    //find in array
    if(in_array($key,$array)){
        $found=true;
    } else{
        foreach($array as $b){
            //match regex asterik
            if(strpos($b,'*')!==false){
                $c=implode('',explode('*',$b));
                if(strpos($key,$c)!==false){
                    $found=true;
                    break;
                }
            }
        }
    }
    return $found;
}

//FIND KEY IN VALUE AS ARRAY OR STRING
function found_in($k,$v){
    if(!empty($k) && !empty($v)){
        if( (is_array($v) && (in_array($k,$v) || array_key_exists($k,$v))) ||
            (is_string($v) && is_string($k) && strpos(strtolower($k),strtolower($v)) !== false) ||
            ($k==$v)
        ){
            return true;
        }
    }
    return false;
}

//FIND KEYS IN ARRAY LIST AND RETURN VALUES
function find_in_list($find,$list){
    $results = array();
    $l = $list;
    $f = $find;
    if(count($l) > 0){
        //loop in list
        foreach($l as $c => $r){
            $b = array();
            //loop in find
            foreach($f as $k => $v){
                if(is_array($v)){
                    if(array_key_exists($k,$r)){
                        $w = $r[$k];
                        if(is_array($w)){
                            foreach($v as $x){
                                if(array_key_exists($x,$w)){
                                    $b[$x]=$w[$x];
                                }
                            }
                        } else {
                            $b[$k]=$w;
                        }
                    }
                } elseif(array_key_exists($v,$r)){
                    $b[$v]=$r[$v];
                }
            }
            if(!empty($b)){
                if(isset($r['claimid']) && isset($r['name'])){
                    $e = format_name_key($r['claimid'].'-'.$r['name']);
                    $results[$e]=$b;
                } else {
                    $results[]=$b;
                }
            }
        }
    }
    if(count($results) > 0){
        $r = array();
        foreach($results as $k => $v){
            $r[] = $v;
        }
        $results = $r;
    }
    return $results;
}

//REMOVE TAG FROM HTML
function removeTag($html,$tag){
    if(strpos($html,'<'.$tag)!==false){
        //et results
        $a = '<'.$tag;
        $c = '</'.$tag.'>';
        //
        if($tag=='meta'){
            $c='>';
        }
        //find data in tag
        $meta = rcodeBlock($a,$c,$html);
        //remove from html
        if(isset($meta[0])){
            foreach($meta as $b){
                $html = implode('',explode($a.$b.$c,$html));
            }
        }
    }
    return $html;
}

//REPLACE ATTRIBUTE IN HTML TAG
function replaceAttr($html,$tag,$attr,$val){
    $x = '<'.$tag;
    $y = '>';
    $z = array(' '.$attr.'="','"');
    //check for tag in html
    if(strpos($html,$x)!==false){
        $a = explode($x,$html);
        $b = explode($y,$a[1]);
        $c = trim($b[0]);
        $g = $z[0].$val.$z[1];
        if(!empty($c)) {
            $c = strtolower($c);
            if(strpos($c,$z[0])!==false){
                $d = explode($z[0],$c);
                $e = explode($z[1],$d[1]);
                $f = $z[0].$e[0].$z[1];
                $html = implode($g,explode($f,$html));
            }
        } else {
            $html = implode($x.$g,explode($x,$html)); 
        }
        
    }
    return $html;
}

//REMOVE SPACES FROM HTML
function removeSpaces($html){
    $rm = array("\n","\t","\r");
    $tg = array('{','}');
    //trim spaces
    foreach($rm as $r){
        $html = implode('',explode($r,$html));
    }
    
    foreach($tg as $t){
        $html = implode($t,explode(' '.$t,$html));
        $html = implode($t,explode($t.' ',$html));
    }
    return $html;
}

//GET RANDOME COLOR
function getRandomColor( $num=1 ) {
    $hash = md5( time() . $num );
    return array(
        hexdec(substr($hash, 0, 2)),
        hexdec(substr($hash, 2, 2)),
        hexdec(substr($hash, 4, 2))
    ); 
}

//GET CHART COLORS FROM CHART DATA
function getChartColors( $data) {
    $color = isset( $data['color'] ) ? $data['color'] : false;
    $color = ( !$color && isset( $data['fillColor'] )  ) ? $data['fillColor'] :  $color; 
    $color = ( !$color && isset( $data['strokeColor'] )  ) ? $data['strokeColor'] :  $color; 
    $color = ( !$color ) ? '#000000' : $color;
    return $color;
}

//MAKE PAGE BY NAME AND ARRAY
function makePage( $name, $array, $type='public' ) {
    //set page type
    $path = array(
        'compute' => array(
            'dir' => '../compute/',
            'name' => 'template',
            'ext' => '.php'
        ),
        'console' => array(
            'dir' => './',
            'name' => 'template',
            'ext' => '.php'
        ),
        'public' => array(
            'dir' => '../',
            'name' => 'template',
            'ext' => '.html'
        ),
        'account' => array(
            'dir' => '../account/',
            'name' => 'template',
            'ext' => '.php'
        ),
        'module' => array(
            'dir' => '../account/modules/',
            'name' => 'template',
            'ext' => '.php'
        )
    );
    //check for page path
    if( isset( $path[ $type ] ) ){
        //set shorthand
        $p = $path[ $type ];
        //set file 
        $temp_file = $p['dir'] . $p['name'] . $p['ext'];
        //check for file
        if( is_file( $temp_file ) ){
            //get template
            $template = file_get_contents( $temp_file );
            //set html
            $html = $template;
            //set defaults
            $defaults = array('page' => 'Template Page');
            //color
            $color = array('white','gray','white','gray','white','gray','blue','dark','white','gray','black');
            //loop 
            for($x=1; $x < 11;$x++) {
                $defaults[ 'title_'.$x ] = '';
                $defaults[ 'link_'.$x ] = '#section_'.$x;
                $defaults[ 'color_'.$x ] = $color[ $x ];
                $defaults[ 'content_'.$x ] = 'A template is a placeholder used to create pages';
            }
            //set count
            $count = 0;
            //set settings
            $settings = array_merge( $defaults, $array );
            //look in each settings
            foreach( $settings as $key => $value ){
                //check for link
                if( strpos( $key, 'link' ) !== false ) {
                    $count++;
                }
                //check if link is empty but not title 
                if( empty( $settings[ 'link_'.$count ] )  && !empty( $settings[ 'title_'.$count ] ) ) {
                    $settings[ 'link_'.$count ] = '#section_'.$count;
                }
                //format key
                $replacer = '{'.strtoupper( $key ) . '}';
                //replac
                $html = implode( $value, explode( $replacer, $html ) );
            }
            //replace section
            for($x=1; $x <= $count; $x++) {
                //set section start & end
                $section = 'SECTION_'.$x.'}';
                $from = '';
                $to = '';
                //check if empty
                if( empty( $settings[ 'title_'.$x ] ) && empty( $settings[ 'content_'.$x ] ) ) {
                    $from = '<!--';
                    $to = '-->';
                }
                //replace section start
                $html = implode( $from, explode( '{'.$section, $html ) );
                //replace section end
                $html = implode( $to, explode( '{/'.$section, $html ) );
            }
            //set file name
            $file_name = strtolower( clean($name,'') );
            //set file
            $file = $p['dir'] . $file_name . $p['ext'];
            //check if file does not exist
            if( !is_file( $file ) ) {
                //save page
                file_put_contents( $file, $html );
            } else {
                return  array( 
                    'path' => $file,
                    'name' => $name,
                    'made' => false
                );
            }
            //check for file
            if( is_file( $file ) ){
                //return
                return array( 
                    'path' => $file,
                    'name' => $name,
                    'made' => true
                );
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//IMPORT MODULE WITH OPTIONS
function importModule( $name ) {
    GLOBAL $user;
    GLOBAL $modules;
    GLOBAL $account;
    GLOBAL $mod_ddir;
    GLOBAL $account_types;
    GLOBAL $console_types;
    GLOBAL $private_types;
    GLOBAL $restrict_types;
    GLOBAL $page_options;
    //set temp options
    $temp_option = $page_options;
    //set module hash
    $hash = super_hash( $account['type'].'.'.$name, 8, '' );
    //set name
    $file = $mod_ddir . $name . '.php';
    //set pass
    $pass = true;
    //set reset
    $rest = false;
    //set message 
    $text = '';
    //filter page option
    if( isset( $page_options[ $name ] ) ) {
        $rest = true;
        //set page option
        $page_options = $page_options[ $name ];
    }
    //CHECK FOR DIRECTORY
    if( is_dir( $mod_ddir ) ) {
        //check for file
        if( is_file( $file ) ) {
            //include file
            include $file;
        } else {
            $text = 'Invalid section ..';
            $pass = false;
        }
    } else {
        $text = 'Invalid section location..';
        $pass = false;
    }
    //set page option
    if( $rest ) {
        $page_options = $temp_option;
    }
    //set if pass
    $modules[ $name ] = array(
        'date' => Date('Y/m/d h:i:s A'),
        'hash' => $hash,
        'pass' => $pass,
        'text' => $text
    );
    //check if not pass
    if( !$pass ) {
        die( $text );
    }
}

//CHECK RESTRCIT VALUD
function isRestrictAccount( $name ) {
    //set allow
    $allow = true;
    //check if valid account
    if( !isValidAccount( 'agents', $name, 'restrict' ) && 
        !isValidAccount( 'retailer', $name, 'restrict' )
    ) {
        $allow = false;
    }
    return $allow;
}

//CHECK ACCOUNT TYPE
function isValidAccount( $service, $name=false, $access=false) {
    GLOBAL $user;
    GLOBAL $account;
    GLOBAL $account_types;
    GLOBAL $console_types;
    GLOBAL $private_types;
    GLOBAL $restrict_types;
    GLOBAL $customer_types;
    //set allow
    $allow = false;
    //set type
    $type = $account['type'];
    //set types
    $types = array_keys( $account_types );
    //set services
    $services = $account['services'];
    //set both, until, public, private, restrict
    $admin = ( $type == 'admin') ? true : false;
    $both = ( $access == 'both' ) ? true : false;
    $until = ( $access == 'until' ) ? true : false;
    $match = ( $access == 'match' ) ? true : false;
    $public = ( $access == 'public' ) ? true : false;
    $private = ( $access == 'private' ) ? true : false;
    $console = ( $access == 'console' ) ? true : false;
    $restrict = ( $access == 'restrict' ) ? true : false;
    $customer = ( $access == 'customer' ) ? true : false;
    //check if any are true
    if( ( $name == 'logout' ) || 
        //check type in service
        ( $type == $service ) || 
        //check logged in user
        ( $user && $private ) ||
        //check if private or both
        ( ( $private || $restrict ) && $admin ) ||
        //check if user type has services
        ( $user && in_array( $type, $services ) ) || 
        //set both types as restrict types
        ( $both && !in_array( $type, $restrict_types ) ) || 
        //private type
        ( $private && in_array( $type, $private_types ) ) || 
        //console type
        ( $console && in_array( $type, $console_types ) ) || 
        //restrict type
        ( $restrict && in_array( $type, $restrict_types ) ) || 
        //customer types
        ( $customer && in_array( $type, $customer_types ) ) || 
        //check logged in user
        ( $user && $match && ( $type == $name || $admin ) ) 
    ) {
        $allow = true;
    }
    //check user until if currently matched or if public
    if( $user && ( $allow && $until ) || $public ) {
        $allow = false;
    }
    return $allow;
}

//SET COMMON MENU
function setMenu( $type, $menus, $active, $account=false, $custom=false ) {
    //set allow 
    $allow = false;
    //set output
    $output = '';
    //set menu
    $menu = $menus[ $type ];
    //look in each service
    foreach( $menu as $name => $object ) {
        //set allow
        $allow = false;
        //set hash or path
        $hpath = $object[0];
        //set menu label 
        $label = $object[1];
        //set page extension
        $exten = $object[2]; 
        //set private page
        $access = $object[3];
        //is logged in
        if( $account && isset( $account['type'] ) ) {
            //check if not account services
            if( !isset( $account['services'] ) ) {
                $account['services'] = array();
            }
            $allow = isValidAccount( $type, $name, $access );
        //do not show if requires user to be logged in
        } elseif( $access == 'private' || $access == 'restrict' || $name == 'logout' ) {
            $allow = false;
        } else {
            $allow = true;
        }
        //check if allow or if console
        if( $allow || $name == $custom ) {
            //set attributes
            $attr = '';
            //check for signup or login
            if( ( $name == 'signup' || $name == 'login' || $name == 'upload' ) && $name != $active ) {
                $attr = ' data-toggle="modal" data-target="#'.$name.'" data-dismiss="modal"';
            }
            //set active
            if( $name == $active ) {
                $output .= '<li class="active"><a class="page-scroll" href="#'.$name.'" '.$attr.'>'.$label.'</a></li>';
            } else {
                $output .= '<li><a class="page-scroll" href="'.$hpath.$name.$exten.'" '.$attr.'>'.$label.'</a></li>';
            }
        }
    }
    return $output;
}

//CREATE A SUPER HASH
function super_hash( $text, $size=8, $wrap='sha256', $all=true ){
    $name = '';
    $hash = $text;
    $mash = $text;
    $find = false;
    foreach (hash_algos() as $v) { 
        $hash = hash($v, $hash, false); 
        if( $size == strlen( $hash ) ) { 
            $name = $v;
            $find = true;
        }
        if( !$all && $find ) {
            $mash = hash($v, $mash, false);
            break;
        }
    }
    if( !empty( $name ) ) {
        $hash = hash( $name, $hash, false );
    }
    if( !empty( $wrap ) ) {
        $hash = hash( $wrap, $hash, false );
    }
    return ($all) ? $hash : $mash;
}

//SAVE WITH STATS
function set_service_content( $name, $directory, $file, $data, $stat ) {
    //check for file
    $all = array();
    //check for old
    $old = false;
    //set analytics
    $anl = array();
    //set data file
    if( !is_dir( $directory ) ) {
        create_dir( $directory );
    }
    //check for analytics
    if( is_file( $stat ) ) {
        $anl = json_decode( file_get_contents( $stat ), true );
    }
    //check for file
    if( is_file( $file ) ) {
        //get all
        $all = json_decode( file_get_contents( $file ), true );
        //check if not file
        if( $all == null || count( $all ) == 0 ) {
            $all = array();
        }
    }
    //check for hash
    if( !isset( $data['hash'] ) ) {
        $data['hash'] = hash( 'sha256', json_encode( $data ), false );
    }
    //set session details
    $data['session'] = array(
        'date'     => date('Y/m/d'),
        'time'     => date('h:i:s A'),
        'browser'  => $_SERVER['HTTP_USER_AGENT'],
        'referer'  => $_SERVER['HTTP_REFERER'],
        'ipaddrs'  => $_SERVER['REMOTE_ADDR'],
        'timestamp'=> microtime(true)
    );
    //check for all
    if( count( $all ) > 0 ) {
        //look for match in hash
        foreach( $all as $index => $item ) {
            //check for hash
            if( isset( $item['hash'] ) && $item['hash'] == $data['hash'] ) {
                //set old
                $old = $index;
            }
        }
        //check if old
        if( $old ) {
            $all[ $old ] = $data;
        }
    }
    if( !$old ){
        //add to data
        array_unshift( $all, $data );
        //add to analytics
        array_unshift( $anl, array( 
            'name' => $name, 
            'hash' => $data['hash'] 
        ) );
    }
    //save file
    file_put_contents( $file, json_encode( $all ) );
    //save file
    file_put_contents( $stat, json_encode( $anl ) );
    //retur n
    return $all;
}

//SET COMMENT HTML 
function set_comment_html( $recent ) {
    $output = '';
    //check for recen
    if( count( $recent ) > 0 ) {
        //look in each recent
        foreach($recent as $r ) {
            //get time elapse
            $time_elapse = time_elapsed_string( date( "Y-m-d H:i:s", $r['session']['timestamp'] ) );
            //get output
            $output .= '<strong class="pull-left primary-font">'.$r['name'].'</strong>';
            $output .= '<small class="pull-right text-muted">'.$time_elapse.'</small></br>';
            $output .= '<li class="ui-state-default text-left">'.$r['comment'].'</li></br>';
        }
    }
    return $output;
}

//CONVERT STRING PARTS INTO ARRAY
function set_string_parts( $string, $name ) {
    $html = $string;
    $part = ( strpos( $html, '{' . $name . ':' ) !== false ) ? explode('{' . $name . ':', $html) : false;
    $part = ( $part ) ? explode( '}', $part[1] ) : false;
    $clss = ( $part ) ? $part[0] : '';
    $html = ( $part ) ? implode('', explode( ':' . $clss, $html ) ) : $html;
    $part = ( !empty( $clss ) && strpos( $clss, '|' ) !== false ) ? explode( '|', $clss ) : false;
    $clss = ( $part ) ? $part[0] : $clss;
    $label = ( $part ) ? $part[1] : $name;
    //return parts
    return array( 
        'html' => $html, 
        'name' => $name,
        'clss' => $clss, 
        'label' => $label 
    );
}

//CONVERT STRING INTO HTML
function set_string_html( $string ) {
    //set default
    $html = $string;
    //check for balls
    if( strpos($string, '{Balls}') !== false ) {
        $a = explode('{Balls}', $string);
        $b = explode('-', $a[1]);
        $html = '<ul class="draw-result list-unstyled list-inline">';
        //set size
        $mize = count( $b );
        //look in each set
        foreach( $b as $count => $win ) {
            //check if last
            if( $count == ( $mize - 1 ) ){
                $html .= '<li class="bonus h1">' . $win . '</li>';
            } else {
                $html .= '<li class="h1">' . $win . '</li>';
            }
        }
        $html .= '</ul>';
    }
    //check for time elapse
    if( strpos($string, '{Elapse}') !== false ) {
        $a = explode('{Elapse}', $string);
        $html = time_elapsed_string( $a[1] );
    }
    //check for time elapse
    if( strpos($string, '{Date}') !== false ) {
        $a = explode('{Date}', $string);
        $html = Date( "l, F j, Y", strtotime( $a[1] ) );
    }
    //check for matching average
    if( strpos($html, '{Match') !== false ) {
        $part = set_string_parts( $html, 'Match' );
        $match = set_match_average( $part['clss'], $part['label'] );
        $html = '<a href="#" data-toggle="modal" data-target="#report" class="btn btn-success show_report">' . $match['data']['combine_percent'] . '%</a>';
        $html .= '<div class="hide report">'.implode('', explode('hide', $match['summary'] ) ).'</div>';
    }
    //check for time elapse
    if( strpos($html, '{Games') !== false ) {
        $part = set_string_parts( $html, 'Games' );
        $html = implode( setGamesMenu( $part['clss'], $part['clss'].'_btn', $part['label']), explode('{Games}', $part['html'] ) );
    }
    //check for time elapse
    if( strpos($html, '{Numbers') !== false ) {
        $part = set_string_parts( $html, 'Numbers' );
        $html = implode( setNumbersInput( 'check_numbers', $part['clss'].'_text', $part['label']), explode('{Numbers}', $part['html'] ) );
    }
    //check for time elapse
    if( strpos($html, '{Remove') !== false ) {
        $part = set_string_parts( $html, 'Remove' );
        $html = implode(
            '<button class="btn btn-danger bg-white remove_btn '.$part['clss'].'" title="' . $part['label'] . '">' . 
            '<i class="fa fa-trash faa-tada animated-hover red"></i> ' . $part['label'] . 
            '</button>', explode('{'.$part['name'].'}', $part['html']) );
    }
    //check for time elapse
    if( strpos($html, '{Add') !== false ) {
        //check if has parts
        $part = set_string_parts( $html, 'Add' );
        $html = implode(
            '<button class="btn btn-success bg-white add_btn '.$part['clss'].'" title="' . $part['label'] . '">' . 
            '<i class="fa fa-plus faa-tada animated-hover green"></i> ' . $part['label'] . 
            '</button>', explode('{'.$part['name'].'}', $part['html']) );
    }
    //check for time elapse
    if( strpos($html, '{Shuffle') !== false ) {
        $part = set_string_parts( $html, 'Shuffle' );
        $html = implode(
            '<button class="btn btn-warning bg-white shuffle_btn '.$part['clss'].'" title="' . $part['label'] . '">' . 
            '<i class="fa fa-random faa-tada animated-hover blue"></i> ' . $part['label'] . 
            '</button>', explode('{'.$part['name'].'}', $part['html']) );
    }
    //check for time elapse
    if( strpos($html, '{Edit') !== false ) {
        $part = set_string_parts( $html, 'Edit' );
        $html = implode(
            '<button class="btn btn-warning bg-white edit_btn '.$part['clss'].'" title="' . $part['label'] . '">' . 
            '<i class="fa fa-pencil faa-tada animated-hover blue"></i> ' . $part['label'] . 
            '</button>', explode('{'.$part['name'].'}', $part['html']) );
    }
    //check for time elapse
    if( strpos($html, '{Save') !== false ) {
        //check if has parts
        $part = set_string_parts( $html, 'Save' );
        $html = implode(
            '<button class="btn btn-success add_btn '.$part['clss'].'" title="' . $part['label'] . '">' . 
            '<i class="fa fa-check faa-tada animated-hover"></i> ' . $part['label'] . 
            '</button>', explode('{'.$part['name'].'}', $part['html']) );
    }
    //return string
    return $html;
}

//SET NUMBER POSITION AS ARRAY
function set_number_position( $array, $word=false ){
    $pos_word = array(
        'first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth', 'eleventh', 'twelfth'
    );
    $pos_num = array(
        '1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th'
    );
    $output = array();
    foreach( $array as $idx => $num ) {
        $output[] = ( $word ) ? $pos_word[ $idx ] : $pos_num[ $idx ];
    }
    return $output;
}

//set arg type
function SQLITE_ArgType($arg) {
    switch (gettype($arg)) {
        case 'double':  return SQLITE3_FLOAT;
        case 'integer': return SQLITE3_INTEGER;
        case 'boolean': return SQLITE3_INTEGER;
        case 'NULL':    return SQLITE3_NULL;
        case 'string':  return SQLITE3_TEXT;
        default:
            throw new \InvalidArgumentException('Argument is of invalid type '.gettype($arg));
    }
}

//GET CTE BY GRADE
function cte_by_grade() {

   $sql =<<<EOF
    SELECT DISTINCT student_grade FROM student_courses;
EOF;
   $grade_names = array(
        9 => 'freshman',
        10 => 'sophomore',
        11 => 'junior',
        12 => 'senior'
    ); 
    $ret = run_db_query( '', $sql );
    $json_out = array();
    foreach( $ret as $row ){
        $grade = $row["student_grade"];

        $nextQ = "SELECT COUNT(*) FROM group_credit WHERE credit_away < 1 AND grade=?";
        $res = run_db_query( 'getGroupCreditAway', $nextQ, $grade );
        $ontrack = $res[0];

        $nextQ = "SELECT COUNT(*) FROM group_credit WHERE credit_away = 1 AND grade=?";
        $res = run_db_query( 'getGroupCreditAway', $nextQ, $grade );
        $atrisk = $res[0];

        $nextQ = "SELECT COUNT(*) FROM group_credit WHERE credit_away > 1 AND grade=?";
        $res = run_db_query( 'getGroupCreditAway', $nextQ, $grade );
        $offtrack = $res[0];

        #   print "$grade: [$ontrack, $atrisk, $offtrack]";
        #   print "For grade $grade Ontrack = $ontrack Atrisk = $atrisk Offtrack = $offtrack\n";
        $named = $grade_names[ $grade ];
        $json_out[ $named ] = array( $ontrack, $atrisk, $offtrack );
    }
    return $json_out;
}

//GET ONTRACK BY GRADE
function ontrack_by_grade( $status='On track', $group_filter = '' ){


   $sql = "SELECT DISTINCT student_grade FROM student_courses";

   $ret = run_db_query( 'getStudentGrade', $sql );
  
   $json = array();
 
   foreach( $ret as $row ){
        $grade = $row["student_grade"];
    
    if (empty($group_filter)) {
            $nextQ = "SELECT COUNT(*) FROM ontrack WHERE status=? AND grade=?";
    }
    else {
            $nextQ = "SELECT COUNT(*) FROM ontrack WHERE status=? AND grade=? AND groups LIKE '%" . $group_filter[0] . "%'";
    }
        $res = run_db_query( 'getOntrackStatus', $nextQ, $status, $grade );
        $json[] = $res[0];
   }
   return $json;
}

function cte_ontrack_by_grade( $status='On track', $group_filter = '' ){


   $sql = "SELECT DISTINCT student_grade FROM student_courses";

   $ret = run_db_query( 'getStudentGrade', $sql );
  
   $json = array();
 
   foreach( $ret as $row ){
        $grade = $row["student_grade"];
    
        if (empty($group_filter)) {
                $nextQ = "SELECT COUNT(*) FROM cte_ontrack WHERE status=? AND grade=?";
        }
        else {
                $nextQ = "SELECT COUNT(*) FROM cte_ontrack WHERE status=? AND grade=? AND groups LIKE '%" . $group_filter[0] . "%'";
        }
        $res = run_db_query( 'getCTEOntrackGrade', $nextQ, $status, $grade );
        $json[] = $res[0];
   }
   return $json;
}

function ontrack( $status='On Track', $group_filter = array() ){
    $json = array();

    if (empty($group_filter)) {
            $nextQ = "SELECT COUNT(*) FROM ontrack WHERE status=?";
    }
    else {
            $nextQ = "SELECT COUNT(*) FROM ontrack WHERE status=? AND groups LIKE '%" . $group_filter[0] . "%'";
    }

    $res = run_db_query( 'getOntrackStatus', $nextQ, $status );

    return array($res[0]);
}

function cte_ontrack( $status='On Track', $group_filter = array() ){
    $json = array();

    if (empty($group_filter)) {
            $nextQ = "SELECT COUNT(*) FROM cte_ontrack WHERE status=?";
    }
    else {
            $nextQ = "SELECT COUNT(*) FROM cte_ontrack WHERE status=? AND groups LIKE '%" . $group_filter[0] . "%'";
    }
    $res = run_db_query( 'getCTEOntrackStatus', $nextQ, $status );
    return array($res[0]);
}

//SET PERCENT ONTRACK BY GRADE
function percent_ontrack_by_grade ( $status='On track', $group_filter=array() ) {


    $sql = "SELECT DISTINCT student_grade FROM student_courses";

   $ret = run_db_query( 'getStudentGrade', $sql );
  
   $json = array();
 
   foreach( $ret as $row ){
        $grade = $row["student_grade"];

        if (empty($group_filter)) {
                $nextQ = "SELECT COUNT(*) FROM ontrack WHERE status=? AND grade=?";
        }
        else {
                $nextQ = "SELECT COUNT(*) FROM ontrack WHERE status=? AND grade=? AND groups LIKE '%" . $group_filter[0] . "%'";
        }
        $count = run_db_query( 'getOntrackStatus', $nextQ, $status, $grade );
        $count = intval( $count[0] );

        if (empty($group_filter)) {
                $nextQ = "SELECT COUNT(*) FROM ontrack WHERE grade=?";
        }
        else {
                $nextQ = "SELECT COUNT(*) FROM ontrack WHERE grade=? AND groups LIKE '%" . $group_filter[0] . "%'";
        }
        $total = run_db_query( 'getOntrackGrade', $nextQ, $grade );
        $total = intval( $total[0] );
        if( $total > 0 ){
            $json[] = round(100*(($count) / $total), 2);
        }   
   }
   return $json;
}

function cte_percent_ontrack_by_grade ( $status='On track', $group_filter=array() ) {

    $sql = "SELECT DISTINCT student_grade FROM student_courses";

   $ret = run_db_query( 'getStudentGrade', $sql );
   $json = array();
 
   foreach( $ret as $row ){
        $grade = $row["student_grade"];

        if (empty($group_filter)) {
                $nextQ = "SELECT COUNT(*) FROM cte_ontrack WHERE status=? AND grade=?";
        }
        else {
                $nextQ = "SELECT COUNT(*) FROM cte_ontrack WHERE status=? AND grade=? AND groups LIKE '%" . $group_filter[0] . "%'";
        }
        $count = run_db_query( 'getCTEOntrackStatus', $nextQ, $status, $grade );
        $count = intval( $count[0] );

        if (empty($group_filter)) {
                $nextQ = "SELECT COUNT(*) FROM cte_ontrack WHERE grade=?";
        }
        else {
                $nextQ = "SELECT COUNT(*) FROM cte_ontrack WHERE grade=? AND groups LIKE '%" . $group_filter[0] . "%'";
        }
        $total = run_db_query( 'getCTEOntrackGrade', $nextQ, $grade );
        $total = intval( $total[0] );

        $json[] = round(100*(($count) / $total), 2);
   }
   return $json;
}

//SET FAIL RATE BY SUBJECT
function fail_rate_subject ($group_filter='') {


    $sql = "SELECT DISTINCT subject FROM student_courses WHERE credit < 0.5";

    $ret = run_db_query( 'getStudentCourses', $sql );
    $json_out = array();

    $highest_total = 0;

    $subjects_percentages = array();
    $subjects_totals = array();


    foreach( $ret as $row ) {
        $subject = $row["subject"];

        if (empty($group_filter)) {
            $nextQ = "SELECT COUNT(*) FROM student_courses WHERE credit < 0.5 AND subject=?";
        }
        else {
            $nextQ = "SELECT COUNT(*) FROM student_courses WHERE credit < 0.5 AND subject=? AND groups LIKE '%" . $group_filter[0] . "%'"; 
        }   
        $failed = run_db_query( 'getStudentCourseCredit', $nextQ, $subject );
        $failed = $failed[0];

        if (empty($group_filter)) {
            $nextQ = "SELECT COUNT(*) FROM student_courses WHERE subject=?";
        }
        else {
            $nextQ = "SELECT COUNT(*) FROM student_courses WHERE subject=? AND groups LIKE '%" . $group_filter[0] . "%'"; 
        }   
        $total = run_db_query( 'getStudentCourseSubject', $nextQ, $subject );

        $total = intval( $total[0] );

        if( $total > 0 ){
            $percent = 100*($failed / ($total + 0.0));

            $subjects_percentages[$subject] = $percent;
            $subjects_totals[$subject] = $total;

            if ($total > $highest_total) { $highest_total = $total;
            }
        }
    }

    $output_subjects = array();
    $json_data = array();
    foreach (array_keys($subjects_totals) as $subject) {

        if ($subjects_totals[$subject] > 0.3*($highest_total)) {
            #$json_row = array($subjects_percentages[$subject]);
            $json_data[$subject] = array($subjects_percentages[$subject]);
            #array_push($json_data, array($subject => array($subjects_percentages[$subject])));
            #array_push($json_data, $json_row);
            array_push($output_subjects, $subject);
        }
    }

    array_push($json_out, array("labels" => $output_subjects));
    array_push($json_out, array("data" => $json_data));

    return json_encode($json_out);
}

//GET USER MENUFROM USER DATA
function get_sidebar_menu( $user_type ) {

    GLOBAL $left_menu;

    //set default menu
    $menu = $left_menu[ $user_type ];
    //set default
    $sql = '';
    
    //check if menu has group
    if( isset( $menu['groups'] ) ) {
        $sql = "SELECT DISTINCT group_name FROM groups";
        $key = 'group_name';
    }

    //check for sql
    if( !empty( $sql ) ) {
        //set query
        $ret = run_db_query( 'groupName'.$user_type, $sql );
        $names = array();
        foreach($ret as $row ) {
            array_push($names, $row[ $key ]);
        }

        //check for group name
        if( $key == 'group_name' ) {
            //reset subnav menu
            $menu['groups']['subnav'] = array();
            //add new menu items
            foreach( $names as $name ) {
                $menu['groups']['subnav'][] = array( 'groups.php?filter='.$name, $name, '');
            }
        }
    }
    //die( json_encode( $menu ) );
    return $menu;
}

//get campus id from uer id
function get_campus_id( $User=array() ){
    GLOBAL $user;
    //check if user object
    if( empty( $User) ) {
        $User = $user;
    }
    $campusSQL = 'SELECT orgSourcedIds from users WHERE sourcedId = "'.$User['id'].'"';
    $getCampusID = run_db_query('getCampusId', $campusSQL);
    $campusId = $getCampusID[0]['orgSourcedIds'];
    if( strpos( $campusId, ',' ) !== false ) {
        $exp = explode(',', implode('',explode('"', $campusId ) ) );
        $campusId = $exp[0];
    }
    return $campusId;
}

//GET CHART TABLE DATA 
function get_chart_table_data( $title, $filter, $header ){
    GLOBAL $campusId;
    $filtr = ( !empty( $filter ) ) ? implode( ',', $filter ) : '';
    $query = run_db_query( 'getJsonCharts', 'SELECT json FROM charts WHERE title = "'.$title.'" AND groups = "'.$filtr.'"' );
    // $query = run_db_query( 'getJsonCharts', 'SELECT json FROM charts WHERE hs_number = "'.$campusId.'" AND title = "'.$title.'" AND groups = "'.$filtr.'"' );
    $datas = json_decode( $query[0]['json'], true );
    $table = json_decode( $datas['data'], true );
    array_unshift( $table, $header );
    return $table;
}


//SET FLEX CHARTS
function set_flex_charts( $data, $page, $filter=array() ) {

    /*
    //set_flex_charts( $chart_data, 'home', $chart_filter )
    //get request data
    $chart_data = array(
        'doughnut_chart' => array(
            'cols' => '6',
            'type' => 'doughnut',
            'title' => 'The Doughnut Chart',
            'label' => 'Chart label if any',
            'excerpt' => 'Description of the chart',
            'data' => array(
                'first' => array(82,56,73,90,87,54,78,90,78),
                'second' => array_reverse( array(82,56,73,90,87,54,76,97) ),
                'third' => array_unique( array(82,56,73,90,87,54,56,78) )
            )
        ),
        'bar_chart' => array(
            'cols' => '6',
            'type' => 'bar',
            'title' => 'The Bar Chart',
            'label' => 'Chart label if any',
            'excerpt' => 'Description of the chart',
            'labels' => array('first', 'second', 'third', 'fourth'),
            'data' => array(
                'first_bar_chart_trends' => array(82,43,25,84),
                'second_bar_chart_trends' => array(53,23,26,12)
            ),
            'table' => array(
                array('name','age','race'),
                array('Fortune', '29', 'black')
            )
        ),
        'line_chart_one' => array(
            'cols' => '6',
            'type' => 'line',
            'fill' => 'origin', //false, origin, start, end
            'title' => 'The First Line Chart',
            'label' => 'Chart label if any',
            'excerpt' => 'Description of the chart',
            'labels' => array('first', 'second', 'third', 'fourth'),
            'data' => array(
                'first' => array(82,56,73,90,87,54,78,90,78),
                'second' => array_reverse( array(82,56,73,90,87,54,76,97) ),
                'third' => array_unique( array(82,56,73,90,87,54,56,78) )
            )
        ),
        'line_chart_two' => array(
            'cols' => '6',
            'type' => 'line',
            'title' => 'The Second Line Chart',
            'label' => 'Chart label if any',
            'excerpt' => 'Description of the chart',
            'labels' => array('first', 'second', 'third', 'fourth'),
            'colors' => array(
                'first' => '#f39c12',
                'second' => '#bf0e75',
                'third' => '#f56954'
            ),
            'data' => array(
                'first' => array(82,56,73,90,87,54,78,90,78),
                'second' => array_reverse( array(82,56,73,90,87,54,76,97) ),
                'third' => array_unique( array(82,56,73,90,87,54,56,78) )
            )
        )
    );
    */
    //set charts
    $charts = array();
    $output = array();
    //set color list
    $colors = array(
        '#f56954','#00a65a','#f39c12','#00c0ef','#9769ff','#f36bba',
        '#105837','#1a73a7','#4b26a0','#bf0e75','#19778c','#901300'
    );
    //make sure we have data
    if( count( $data ) > 0 ) {
        //loop in all data
        foreach( $data as $key => $chart ) {
            //shuffle colors
            shuffle( $colors );
            //loop in each data
            $exp = explode('_', $key );
            //set index
            $idx = 0;
            //check if pie
            $pie = false;
            //check if fill
            $fill = false;
            //set type
            $type = $chart['type'];
            //set title
            $title = $chart['title'];
            //set columns
            $columns = $chart['cols'];
            //set excerpt
            $excerpt = $chart['excerpt'];
            //set chart data
            $chart_data = $chart['data'];
            //set chart data
            $fill = $chart['data'];
            //check for chart colors
            if( isset( $chart['fill'] ) ) {
                $fill = $chart['fill'];
            }
            //set chart colors
            $chart_colors = array();
            //check for chart colors
            if( isset( $chart['colors'] ) ) {
                $chart_colors = $chart['colors'];
            }
            //set labels
            $labels = array_keys( $chart_data );
            //check for labels 
            if( isset( $chart['labels'] ) ) {
                $labels = $chart['labels'];
            }
            //data attributes
            $table_data_attr = array(
                "paging"        => false,
                "lengthChange"  => false,
                "responsive"    => true,
                "searching"     => false,
                "ordering"      => true,
                "processing"    => false,
                "scrollX"       => false,
                "scrollY"       => false,
                "stateSave"     => true,
                "info"          => false,
                "autoWidth"     => true,
                "deferRender"   => false,
                "fixedHeader"   => false,
                "buttons"       => true
            );
            //set table head
            $table_head = $labels;
            array_unshift( $table_head, '');
            //set chart basic setup
            $charts[ $key ] = array(
                'title' => $title,
                'description' => $excerpt,
                'main_class' => 'col-md-'.$columns.' text-left bg-white flex-charts',
                'header_class' => '',
                'footer_class' => 'hide',
                //canvas data attributes
                'type' => $type,
                "scales" => array('Number','Date'),
                "hover" => 'nearest',
                "tooltip" => 'index',
                'data' => array(
                    'labels' => $labels,
                    'datasets' => array()
                ),
                'table' => array(
                    'title' => $title.' Table',
                    'description' => '<div>'.$excerpt.'</div>',
                    'main_class' => 'col-md-12 text-left bg-white middle',
                    'table_class' => 'text-left table table-striped table-responsive table-condensed w100',
                    'thead_class' => array('bold text-left'),
                    'tdata_class' => array('bold text-left'),
                    'table_head' => explode(', ',ucwords(implode(', ',$table_head) ) ),
                    'table_data' => array(),
                    'data_attr' => $table_data_attr
                )
            );
            //set default table labels
            $table_labels = $charts[ $key ]['data']['labels'];

            //check for table in chart
            if( isset( $chart['table'] ) ) {
                $table_labels = array_shift( $chart['table']  );
                $charts[ $key ]['table']['table_head'] = $table_labels;
                $charts[ $key ]['table']['table_data'] = $chart['table'];
                $charts[ $key ]['table']['thead_class'] = array();
                $charts[ $key ]['table']['tdata_class'] = array();
            }
            //loop in labels
            foreach( $table_labels as $label ) {
                $charts[ $key ]['table']['thead_class'][] = 'text-left';
                $charts[ $key ]['table']['tdata_class'][] = 'text-left';
            }
            //check for chart colors
            if( isset( $chart['options'] ) ) {
                $charts[ $key ]['options'] = $chart['options'];
            }
            //check if type is 'pie' or 'doughnut'
            if( $type == 'pie' || $type == 'doughnut' ) {
                //set charts array
                $charts[ $key ]['data'] = array();
                //set is pie
                $pie = true;
            }
            // set data at array
            foreach( $chart_data as $label => $list ){
                //set label parts
                $load = ucwords( implode(' ', explode('_', $label ) ) );
                //set randome string
                $randm = rand() . $label . Date('i');
                //set random color
                $color = implode(', ', getRandomColor( $randm ) );
                //check for user defined color
                if( isset( $colors[ $idx ] ) ) {
                    $color = implode(', ', hex2rgb($colors[ $idx ], '', false) ); 
                }
                //check for chart colors
                if( !empty( $chart_colors ) && isset( $chart_colors[ $label ] ) ){
                    $color = implode(', ', hex2rgb( $chart_colors[ $label ], '', false) ); 
                }
                //loop in each data
                if( $pie ) {
                    $charts[ $key ]['data'][] = array(
                        'value' => ( count( $list ) > 0) ? $list[0] : $list,
                        'color' => "rgba(".$color.", 1)",
                        'highlight' =>  "rgba(".$color.", .5)",
                        'label' =>  $load
                    );
                    //only if table not present in chart
                    if( !isset( $chart['table'] ) ) {
                        $charts[ $key ]['table']['table_head'] = array('', $title );
                    }
                } else {
                    $charts[ $key ]['data']['datasets'][] = array(
                        "label" => $load,
                        "fill" => $fill,
                        "fillColor" => "rgba(".$color.", 1)",
                        "strokeColor" => "rgba(".$color.", .5)",
                        "pointColor" => "rgba(".$color.", 1)",
                        "pointStrokeColor" => "rgba(".$color.", .5)",
                        "pointHighlightFill" => "rgba(".$color.", .5)",
                        "pointHighlightStroke" => "rgba(".$color.", 1)",
                        "data" => array_values( $list )
                    );
                }
                //only if table not present
                if( !isset( $chart['table'] ) ) {
                    $first = ucwords(implode(' ',explode('_',$label ) ) );
                    $tdata = array( $first );
                    foreach( $list as $ldat ) {
                        $tdata[] = ( is_array( $ldat) ) ? $ldat[0] : $ldat;
                    }
                    $charts[ $key ]['table']['table_data'][] = $tdata;
                }
                //increment index
                $idx++;
            }
        }
        //check size
        if( count( $filter ) > 0 ) {
            //loop in each charts
            foreach( $charts as $key => $value ) {
                //set allowed
                $allow = false;
                //look in each filter
                foreach( $filter as $find ) {
                    //check if match
                    if( strpos( $key, $find ) !== false ) {
                        $allow = true;
                    }
                }
                //check if exist
                if( $allow ) {
                    $output[ $key ] = $value;
                }
            }
        } else {
            $output = $charts;
        }
    }
    //return charts
    return $output;
}

?>
