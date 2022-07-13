<?php
	
	function add_quotes($str) {
		return sprintf("'%s'", $str);
	}
	$servername = "192.168.241.88";
	$username = "vlive";
	$password = "vlive123312";
	$dbname_old = "vlive";
	
	$username_new = "vclip_new";
	$password_new = "g5rDM287Oj";	
	$dbname_new = "vclip";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname_old);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	/* change character set to utf8 */
	if (!$conn->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $conn->error);
	} else {
		printf("Current character set: %s\n", $conn->character_set_name());
	}

	

	// Create connection
	$conn_new = new mysqli($servername, $username_new, $password_new, $dbname_new);
	// Check connection
	if ($conn_new->connect_error) {
		die("Connection failed: " . $conn_new->connect_error);
	} 
	
	/* change character set to utf8 */
	if (!$conn_new->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $conn_new->error);
	} else {
		printf("Current character set: %s\n", $conn_new->character_set_name());
	}
	
	$sql = "SELECT * FROM user WHERE package_id = 2 ";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$user_id =  $row["msisdn"];			
			$package_id = 1;
			$expired_time = add_quotes($row["expired_time"]);
			$created_time = add_quotes($row["created_time"]);
			$subscribed_time = add_quotes($row["created_time"]);
			$charged_time =  add_quotes($row["updated_time"]);
			$unsubscribed_time =  add_quotes(" ");
			$status = $row["subscribe_status"];
			if($status==-2 || $status==-1)
			{
				$status = 2;
				$unsubscribed_time =  add_quotes($row["updated_time"]);
			}				
			
			$updated_time =  add_quotes($row["updated_time"]);
			$extend_fail_count =  $row["extend_fail_count"];
			$page_id =  0;
			$script_request_id= "";
			
			$sql = "INSERT INTO user_package (
					user_id,
					package_id,
					expired_time,
					created_time,
					subscribed_time,
					charged_time,
					unsubscribed_time,
					status,
					updated_time,
					extend_fail_count,
					page_id
					)
			VALUES (
				$user_id,
				$package_id,
				$expired_time,
				$created_time,
				$subscribed_time,
				$charged_time,
				$unsubscribed_time,
				$status,
				$updated_time,
				$extend_fail_count,
				$page_id				
				);";		
			if ($conn_new->query($sql) === TRUE) {
				echo "New record created successfully";
			} else {
				echo "Error: " . $sql . "<br>" . $conn_new->error;
			}
		}
	} else {
		echo "0 results";
	}
	$conn->close();
	$conn_new->close();


?>