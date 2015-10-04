<?php
	session_start();
	require 'connect_db.php';
	$logged=$_SESSION['logged'];
	if(empty($logged)&&$logged==false){
		header("Location: clogin.php");
	}
	$query = "SELECT * from `engineer2015` WHERE 1 ORDER BY `rfor` ASC";
	$query_run = mysql_query($query);
	$pre='dummy';
	$arr=array();
	$count = array();
	$final = array();
	while($var = mysql_fetch_assoc($query_run)){
		if($var['rfor']!=''){
			if($pre!=$var['rfor']){
				array_push($arr, $var['rfor']);
			}
			array_push($final,$var);
		}
		$pre = $var['rfor'];
	}
	for($i=0;$i<sizeof($arr);$i++){
		$query2 = 'SELECT `id` from `engineer2015` WHERE `rfor`= "'.$arr[$i].'" ';
		$query_run2 = mysql_query($query2);
		array_push($count,mysql_num_rows($query_run2));
	}
?>
	<html ng-app="Register">
		<head>
			<script type="text/javascript" src="plugins/angular/angular.min.js"></script>
			<script type="text/javascript" src="plugins/angular/ng-router.js"></script>
			<link rel="stylesheet" type="text/css" href="plugins/bootstrap/bootstrap.min.css">
			<style>
				html,body{
					font-size: 14px;
				}
			</style>
		</head>
		<body ng-controller="RegisterController">
				<div class="row">
					<div class="col-md-11">
						<h2>Total registrations : {{details.length}}</h2>
					</div>
					<div class="col-md-1">
						<a href="logout.php">Logout</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-xs-12">
						<h3>See committee wise</h3>
						<button ng-click="toggleCommittee();" class="btn {{committee?'btn-danger':'btn-primary'}}">{{committee?'Hide':'Show'}}</button>
					</div>
					<div class="col-md-6 col-xs-12">
						<h3>Event/workshop wise(details)</h3>
						<select ng-model='selected' ng-options="event for event in events" ng-init="selected=events[0]">
						</select>
						<button ng-click="toggleEvent();" class="btn {{wise?'btn-danger':'btn-primary'}}">{{wise?'Hide':'Show'}}</button>
					</div>
				</div>
				<div ng-show="committee">
					<table class="table table-striped">
						<tr>
							<th>slno.</th>
							<th>committee</th>
							<th>count</th>
						</tr>
						<tr ng-repeat='event in events'>
							<td>{{$index+1}}</td>
							<td>{{event}}</td>
							<td>{{count[$index]}}</td>
						</tr>
					</table>
				</div>
				<div ng-show="wise">
					<h4>Registrants for : <b>{{selected}}</b></h4>
					<h4>Total : <b>{{participants.length}}</b></h4>
					<h5>Email Selections:</h5>
					<div>
						<span ng-repeat="item in mailList">{{participants[item]+','}}</span>
					</div>
					<table class="table table-striped">
						<tr>
							<th>Sl No</th>
							<th>Name</th>
							<th>College</th>
							<th>Branch</th>
							<th>email</th>
							<th>number</th>
							<th>location</th>
							<th>Time Registered</th>
							<th>Year</th>
							<th>Add/Remove</th>
						</tr>
						<tr ng-repeat="participant in participants">
							<td>{{$index+1}}</td>
							<td>{{participant.name}}</td>
							<td>{{participant.college}}</td>
							<td>{{participant.branch}}</td>
							<td>{{participant.email}}</td>
							<td>{{participant.mobile}}</td>
							<td>{{participant.location}}</td>
							<td>{{participant.time}}</td>
							<td>{{participant.year}}</td>
							<td><button class="btn btn-primary" ng-click="AddRemove($index);">Add/Remove<button></td>
						</tr>
					</table>
				</div>
		</body>
		<script type="text/javascript">
			 Register = angular.module('Register', []);
			 Register.controller('RegisterController', function($scope, $location,$http) {
				$scope.events = <?php echo json_encode($arr); ?>;
				$scope.details = <?php echo json_encode($final); ?>;
				$scope.count = <?php echo json_encode($count); ?>;
				$scope.committee = false;
				$scope.wise = false;
				$scope.toggleCommittee = function(){
					$scope.committee = !(angular.copy($scope.committee));
					$scope.wise = false;
				}
				$scope.toggleEvent = function(){
					$scope.wise = !(angular.copy($scope.wise));
					$scope.committee = false;
				}
				$scope.participants = [];
				$scope.mailList = [];
				for(var i=0;i<$scope.details.length;i++){
					if($scope.details[i].rfor==$scope.events[0]){
						$scope.participants.push($scope.details[i]);
					}
				}
				$scope.$watch('selected',function(n,o){
					if(n!=o){
						$scope.participants=[];
						$scope.mailList=[];
						for(var i=0;i<$scope.details.length;i++){
							if($scope.details[i].rfor==$scope.selected){
								$scope.participants.push($scope.details[i]);
							}
						}
					}
				});
				var inList =function(arr,item){
					for(var i=0;i<arr.length;i++){
						if(arr[i]==item){
							return true;
						}
					}
					return false;
				}
				var removeItem = function(arr,item){
					for(var i=0; i<arr.length; i++) {
						if(arr[i] == item) {
							arr.splice(i, 1);
							break;
						}
					}
				}
				var addItem = function(arr,item){
					arr.push(item);
				}
				$scope.AddRemove = function(item){
					if (inList($scope.mailList,item))
						removeItem($scope.mailList,item);
					else
						addItem($scope.mailList,item);
				}

			 });
		</script>
	</html>