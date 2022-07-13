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
	
	$sql = "SELECT * FROM clip WHERE migrate = 0";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$id =  $row["id"];
			$msisdn = add_quotes($row["msisdn"]);
			$type = $row["type"];
			$live = $row["live"];
			$privacy = $row["privacy"];
			$remoteaddr = $row["remoteaddr"];
			$lat = $row["lat"];
			$long = $row["long"];
			$upload_id = $row["upload_id"];
			$member_id = $row["member_id"];
			$ms_id = $row["ms_id"];
			$title_vi = add_quotes($conn->real_escape_string($row["title_vi"]));
			$title_vn = add_quotes($conn->real_escape_string($row["title_vn"]));
			$title_bb = add_quotes($conn->real_escape_string($row["title_bb"]));
			$description_vi = add_quotes($conn->real_escape_string($row["description_vi"]));
			$description_vn = add_quotes($conn->real_escape_string($row["description_vn"]));
			$description_bb = add_quotes($conn->real_escape_string($row["description_bb"]));
			$duration = intval($row["duration"]);
			
			$category_id = $row["category_id"];
			if($row["category_id"] == 1 && $row["spec_page_id"] ==2)
				$category_id = 147;
			
			$thumbnail_small_url = $row["thumbnail_small_url"];
			$media_path = $row["media_path"];
			$created_time = $row["created_time"];
			$updated_by = intval($row["updated_by"]);
			$updated_time = !empty($row["updated_time"]) ? $row["updated_time"]: $created_time;
			$thumbnail_large_url = $row["thumbnail_large_url"];
			$import_item_id = $row["import_item_id"];
			$source = $row["source"];
			$converted = $row["converted"];
			$deleted = $row["deleted"];
			$approved = $row["approved"];
			$deleted_by = $row["deleted_by"];
			$thumbnail_version = $row["thumbnail_version"];
			$price = $row["price"];
			$stream_price = $row["stream_price"];
			$is_media_copied = $row["is_media_copied"];
			$sms_id = $row["sms_id"];
			$tag = add_quotes(addslashes($conn->real_escape_string($row["tag"])));
			$provider_id = $row["provider_id"];
			$source_id = $row["source_id"];
			$source_video_id = intval($row["source_video_id"]);
			$source_url = $row["source_url"];
			$content_right = $row["content_right"];
			$extra_info = $row["extra_info"];
			$hq = $row["hq"];
			$sex = $row["sex"];
			$artist_id = $row["artist_id"];
			$downloaded = $row["downloaded"];
			$top = $row["top"];
			$random = $row["random"];
			$spec_page_id = $row["spec_page_id"];
			$active_time = $row["active_time"];
			$is_free = $row["is_free"];
			$is_web = $row["is_web"];
			$status_web = $row["status_web"];
			$order = $row["order"];
			$is_latest = $row["is_latest"];
			$muc_free = $row["muc_free"];
			$slug = $row["slug"];
			$meta_title = $row["meta_title"];
			$meta_keyword = $row["meta_keyword"];
			$meta_description = $row["meta_description"];

			$sql = "INSERT INTO clip (
					id,
					title_1,
					title_2,
					title_3,
					brief_1,
					brief_2,
					brief_3,
					description_1,
					description_2,
					description_3,
					tag,
					duration,
					deleted,
					active,
					streaming_price,
					download_price,
					created_time,
					updated_time,
					created_by,
					updated_by,
					approved_time,
					approved_by,
					upload_id,
					category_id,
					source_id,
					converted,
					user_id,
					page_id,
					ms_id,
					source
			)
			VALUES (
				$id,
				$title_vi, 
				'',
				'',
				$description_vi,
				'',
				'',
				$description_vi,
				'',
				'',
				$tag,
				$duration,
				$deleted,
				$approved,
				$stream_price,
				0,
				'$created_time',
				'$updated_time',
				$updated_by,
				$updated_by,
				'$updated_time',
				$updated_by,
				'$upload_id',
				$category_id,
				$source_video_id,
				$converted,
				$msisdn,
				$spec_page_id,
				$ms_id,
				'$source'
				);";		
			if ($conn_new->query($sql) === TRUE) {
				echo "New record created successfully";
				$conn->query("UPDATE clip set migrate = 1 WHERE id = ".$id );
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
