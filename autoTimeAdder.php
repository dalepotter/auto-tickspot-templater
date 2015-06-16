<?php
// Show errors
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);


// Set available projects/tasks
	$taskIDs = array(
						5664072 => "IATI/Tools and Utilities (Registry, Datastore, websites, publishing tools, etc.)",
						6894543 => "DIPR Internal meetings",
						7186258 => "DIPR general HR (incl 121s, appraisal, pdp's and objective setting)",			
				    );

// Set template for tasks done during the week
	$weeklyTasks = array(
							array( 'day' => 'Monday', 'task' => "IATI/Tools and Utilities (Registry, Datastore, websites, publishing tools, etc.)", 'hours' => 6 ),
							array( 'day' => 'Monday', 'task' => "DIPR general HR (incl 121s, appraisal, pdp's and objective setting)", 'hours' => 1, 'notes' => "Dale/Joni 1-to-1" ),
							array( 'day' => 'Tuesday', 'task' => "IATI/Tools and Utilities (Registry, Datastore, websites, publishing tools, etc.)", 'hours' => 7 ),
							array( 'day' => 'Wednesday', 'task' => "IATI/Tools and Utilities (Registry, Datastore, websites, publishing tools, etc.)", 'hours' => 7 ),
							array( 'day' => 'Thursday', 'task' => "IATI/Tools and Utilities (Registry, Datastore, websites, publishing tools, etc.)", 'hours' => 7 ),
							array( 'day' => 'Friday', 'task' => "IATI/Tools and Utilities (Registry, Datastore, websites, publishing tools, etc.)", 'hours' => 7 ),
						);


// Set the date for the week beginning
	$weekBeginning = "Monday this week";
	//$weekBeginning = "2015-04-20";	// Enables a specific week start date to be set

// Loop over each element in $weeklyTasks to append the task ID
	foreach($weeklyTasks as $key => $task){
		// Find the relevant task ID and append to the array
			$weeklyTasks[$key]['taskId'] = array_search($task['task'], $taskIDs);
		
		// Set notes to an empty string if not set
			if ( !isset($weeklyTasks[$key]['notes']) ){
				$weeklyTasks[$key]['notes'] = "";
			}
	}


// Add each task to tickspot for this week
	// Get the timestamp for the week that the entries are to be added on
		$timestampWeekStart = strtotime($weekBeginning);

	// Set tickspot API details
		$apiAuthToken = "YOUR API TOKEN";	// Get this from the Tickspot API - will likely be alphanumeric
		$apiEndpoint = "https://www.tickspot.com/26859/api/v2/entries.json";
		$apiUserAgent = "AutoTimeAdder (YOUR EMAIL HERE)";
		$tickspotUserId = "YOUR USER ID"; // - will likely be a large integer

	// Loop over each $weeklyTask and send an API request (via POST) to add 
		foreach($weeklyTasks as $task){
			
			// Build the data array
				$data = array(	'date' => date( 'Y-m-d', strtotime($task['day'], $timestampWeekStart) ),
								'hours' => $task['hours'],
								'notes' => $task['notes'],
								'task_id' => $task['taskId'],
								'user_id' => $tickspotUserId,
							 );

			// Encode data to a JSON format string
				$dataJson = json_encode($data);
			
			// Set the headers for the request
				$headers = array(
				    "Content-Type: application/json",
				    "Authorization: Token token=" . $apiAuthToken,
				    "User-Agent: " . $apiUserAgent
				);

			// Set-up a cURL object
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($curl, CURLOPT_POSTFIELDS, $dataJson);
				curl_setopt($curl, CURLOPT_VERBOSE, true);

			// Execute the request and print result to the browser
				$result = curl_exec($curl);
				echo "Entered:" . $result . "<br/>";

		// End: foreach($weeklyTasks as $task)
		}

?>