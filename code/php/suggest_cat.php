<?php

$rs = mysqli_query($conn, "select SName from SubCategory order by SName asc");

	while( $row = mysqli_fetch_array($rs, MYSQL_ASSOC) )
	{
		echo '<option value="' . $row['SName'] . '">' . $row['SName'] . '</option>';
	}


mysqli_free_result($rs);
?>