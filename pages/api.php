<?php

function savenote_query( $bug, $poster, $note ) {
    $now = time();

    $note	= db_prepare_string( $note );

    # Add item to bugnotetext table
    $query = "INSERT INTO {bugnote_text} ( note ) VALUES ( '$note' )";
    $res1 = db_query($query);

    # Get the id fromt the bugnote entry
    $qli = "SELECT id FROM {bugnote_text} WHERE note = '$note'";
    $res = db_query($qli);
    $row = db_fetch_array($res);
    $bugnoteid = $row['id'];

    # Add item to bugnote table
    $query2 = "INSERT INTO {bugnote} ( bug_id, reporter_id, bugnote_text_id, last_modified, date_submitted )
               VALUES ( $bug, $poster, $bugnoteid, $now, $now )";
    $res2 = db_query($query2);
}


// get customfield value
function get_customfield_value($bugid, $field) {
    $q = db_query("SELECT * FROM {custom_field_string} as A, {custom_field} as B WHERE B.id=A.field_id and bug_id = $bugid AND name='$field' ");
    $r = db_fetch_array($q);
    return $r['value'];
    }


//get all customfields in an array
function get_customfields( ){
	$qp = db_query("SELECT name FROM {custom_field} order by name");
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

