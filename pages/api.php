<?php
$b_table = db_get_table('bugnote');
$bt_table = db_get_table('bugnote_text');
$c_table =db_get_table('custom_field');
$cv_table = db_get_table('custom_field_string');

function savenote_query( $bug, $poster, $note ) {
    global $b_table;
    global $bt_table;

    $now = time();

    $note	= db_prepare_string( $note );

    # Add item to bugnotetext table
    $query = "INSERT INTO $bt_table ( note ) VALUES ( '$note' )";
    $res1 = db_query($query);

    # Get the id fromt the bugnote entry
    $qli = "SELECT id FROM $bt_table WHERE note = '$note'";
    $res = db_query($qli);
    $row = db_fetch_array($res);
    $bugnoteid = $row['id'];

    # Add item to bugnote table
    $query2 = "INSERT INTO $b_table ( bug_id, reporter_id, bugnote_text_id, last_modified, date_submitted )
               VALUES ( $bug, $poster, $bugnoteid, $now, $now )";
    $res2 = db_query_bound($query2);
}


// get customfield value
function get_customfield_value($bugid, $field) {
	global $c_table;
	global $cv_table;
    $q = db_query("SELECT * FROM $cv_table as A, $c_table as B WHERE B.id=A.field_id and bug_id = $bugid AND name='$field' ");
    $r = db_fetch_array($q);
    return $r['value'];
    }


//get all customfields in an array
function get_customfields( ){
	global $c_table;
	$qp = db_query("SELECT name FROM $c_table order by name");
	$rp = "";
	while($row =  db_fetch_array($qp)){
		$rp .= $row['name'];
		$rp .= ",";
	}
	if ( $rp<>"") {
		$rp .= "D_u_m_m_y";
	}
	return $rp;
}

