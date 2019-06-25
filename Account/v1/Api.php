<?php 

	require_once '../includes/DbOperation.php';

	function isTheseParametersAvailable($params){

		$available = true; 
		$missingparams = ""; 
		
		foreach($params as $param){
			if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
				$available = false; 
				$missingparams = $missingparams . ", " . $param; 
			}
		}
		
		if(!$available){
			$response = array(); 
			$response['error'] = true; 
			$response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';
			
			echo json_encode($response);
			
			die();
		}
	}

	$response = array();
	
	if(isset($_GET['apicall'])){
		
		switch($_GET['apicall']){
			
			case 'createUser':
				isTheseParametersAvailable(array('FirstName','LastName','Email','Password'));
				$db = new DbOperation();
				$result = $db->createUser(
					$_POST['FirstName'],
					$_POST['LastName'],
					$_POST['Email'],
					$_POST['Password']
				);
				if($result){
					$response['error'] = false; 
					$response['message'] = 'User addedd successfully';
					$response['user'] = $db->getUser($_POST['Email']);
					$response['NoUser'] = false;
				}else{
					$response['error'] = true;
					$response['message'] = 'Some error occurred please try again';
				}
			break; 
			

			case 'getUser':
				if(isset($_GET['Email']))
				{
					$db = new DbOperation();
					$user = $db->getUser($_GET['Email']);

					if(!empty($user))
					{
							$response['error'] = false; 
							$response['message'] = 'Request successfully completed';
							$response['user'] = $db->getUser($_GET['Email']);
							$response['NoUser'] = false;
					}
					else if(empty($user)){
							$response['error'] = true; 
							$response['message'] = 'User do not exist';
							$response['NoUser'] = true;
					}
					else{
							$response['error'] = true; 
							$response['message'] = 'Some error occurred please try again';
						}
				}else{
					$response['error'] = true; 
					$response['message'] = 'Nothing to select, provide an email please';
				}
			break; 
			
			
			case 'updateUser':
				isTheseParametersAvailable(array('id','FirstName','LastName','Email','Password'));
				$db = new DbOperation();
				$result = $db->updateUser(
					$_POST['id'],
					$_POST['FirstName'],
					$_POST['LastName'],
					$_POST['Email'],
					$_POST['Password']
				);
				
				if($result){
					$response['error'] = false; 
					$response['message'] = 'User updated successfully';
					$response['user'] = $db->getUser($_POST['Email']);
					$response['NoUser'] = false;
				}else{
					$response['error'] = true; 
					$response['message'] = 'Some error occurred please try again';
				}
			break; 
			

			case 'deleteUser':
				if(isset($_GET['id'])){
					$db = new DbOperation();
					if($db->deleteUser($_GET['id'])){
						$response['error'] = false; 
						$response['message'] = 'User deleted successfully';
						$response['NoUser'] = true;
					}else{
						$response['error'] = true; 
						$response['message'] = 'Some error occurred please try again';
					}
				}else{
					$response['error'] = true; 
					$response['message'] = 'Nothing to delete, provide an id please';
				}
			break; 
		}
		
	}else{
		$response['error'] = true; 
		$response['message'] = 'Invalid API Call';
	}
	
	echo json_encode($response);


