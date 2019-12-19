<!DOCTYPE html>
<html>
	<head>
		<title>Rio's Library</title>
		<script src="jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
		<script src="jquery.dataTables.min.js"></script>
		<script src="angular-datatables.min.js"></script>
		<script src="bootstrap.min.js"></script>
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel="stylesheet" href="datatables.bootstrap.css">
	</head>
	<body ng-app="crudApp" ng-controller="crudController">
		<a style="float:right" href="logout.php">Logout</a>
			<br><br>
		<div class="container" ng-init="fetchData()">
			<br />
				<h3 align="center">Rio's Library</h3>
			<br />
			<div class="alert alert-success alert-dismissible" ng-show="success" >
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				{{successMessage}}
			</div>
			<div align="right">
				<button type="button" name="add_button" ng-click="addData()" class="btn btn-success">Add</button>
			</div>
			<br />
			<div class="table-responsive" style="overflow-x: unset;">
				<table datatable="ng" dt-options="vm.dtOptions" class="table table-bordered table-striped">
					<thead>
						<tr>
							<!-- <th></th> -->
							<th>Title</th>
							<th>Author</th>
							<th>Genre</th>
							<th>Section</th>
							<th>Status</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="book in bookData">
							<!-- <td class="text-center">
									<button type="button" class="btn btn-default btn-flat btn-sm dropdown-toggle" ng-click="tag(book.status)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="caret"></span>
									</button>
									<ul class="dropdown-menu"><li><a href="" ng-click="tagged(book.id,book.status)" class="btn-sm">{{tag}}</a></li></ul>
							</td> -->
							<td>{{book.title}}</td>
							<td>{{book.author}}</td>
							<td>{{book.genre}}</td>
							<td>{{book.section}}</td>
							<td>{{book.status}}</td>
							<td><button type="button" ng-click="fetchSingleData(book.id)" class="btn btn-warning btn-xs">Edit</button></td>
							<td><button type="button" ng-click="deleteData(book.id)" class="btn btn-danger btn-xs">Delete</button></td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>
	</body>
</html>

<div class="modal fade" tabindex="-1" role="dialog" id="crudmodal">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
    		<form method="post" ng-submit="submitForm()">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title">{{modalTitle}}</h4>
	      		</div>
	      		<div class="modal-body">
	      			<div class="alert alert-danger alert-dismissible" ng-show="error" >
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						{{errorMessage}}
					</div>
					<div class="form-group">
						<label>Title</label>
						<input type="text" name="title" ng-model="title" class="form-control" />
					</div>
					<div class="form-group">
						<label>Author</label>
						<input type="text" name="author" ng-model="author" class="form-control" />
					</div>
					<div class="form-group">
						<label>Genre</label>
						<input type="text" name="genre" ng-model="genre" class="form-control" />
					</div>
					<div class="form-group">
						<label>Library Section</label>
						<input type="text" name="section" ng-model="section" class="form-control" />
					</div>
	      		</div>
	      		<div class="modal-footer">
	      			<input type="hidden" name="hidden_id" value="{{hidden_id}}" />
	      			<input type="submit" name="submit" id="submit" class="btn btn-info" value="{{submit_button}}" />
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        	</div>
	        </form>
    	</div>
  	</div>
</div>


<script>

var app = angular.module('crudApp', ['datatables']);
app.controller('crudController', function($scope, $http){

	$scope.success = false;

	$scope.error = false;

	$scope.fetchData = function(){
		$http.get('fetch_data1.php').success(function(data){
			$scope.bookData = data;
		});
	};

	$scope.openModal = function(){
		var modal_popup = angular.element('#crudmodal');
		modal_popup.modal('show');
	};

	$scope.closeModal = function(){
		var modal_popup = angular.element('#crudmodal');
		modal_popup.modal('hide');
	};

	$scope.addData = function(){
		$scope.modalTitle = 'Add a Book';
		$scope.submit_button = 'Submit';
		$scope.openModal();
	};

	$scope.submitForm = function(){
		$http({
			method:"POST",
			url:"insert.php",
			data:{'title':$scope.title, 'author':$scope.author, 'genre':$scope.genre, 'section':$scope.section, 'action':$scope.submit_button, 'id':$scope.hidden_id}
		}).success(function(data){
			if(data.error != '')
			{
				$scope.success = false;
				$scope.error = true;
				$scope.errorMessage = data.error;
			}
			else
			{
				$scope.success = true;
				$scope.error = false;
				$scope.successMessage = data.message;
				$scope.form_data = {};
				$scope.closeModal();
				$scope.fetchData();
			}
		});
	};

	$scope.fetchSingleData = function(id){
		$http({
			method:"POST",
			url:"insert.php",
			data:{'id':id, 'action':'fetch_single_data'}
		}).success(function(data){
			$scope.title = data.title;
			$scope.author = data.author;
			$scope.genre = data.genre;
			$scope.section = data.section;
			$scope.hidden_id = id;
			$scope.modalTitle = 'Edit Data';
			$scope.submit_button = 'Update';
			$scope.openModal();
		});
	};

	$scope.deleteData = function(id){
		if(confirm("Are you sure you want to remove it?"))
		{
			$http({
				method:"POST",
				url:"insert.php",
				data:{'id':id, 'action':'Delete'}
			}).success(function(data){
				$scope.success = true;
				$scope.error = false;
				$scope.successMessage = data.message;
				$scope.fetchData();
			});	
		}
	};

	$scope.tag = function(status){
		var stat = '';
		if(status == 'Borrowed'){
			$scope.tag = 'Return';
			stat = 'Available';
		}else{
			stat = 'Borrowed';
			$scope.tag = 'Borrow';
		}
	};
	var stat = '';

	$scope.tagged = function(id,status){
		var user_id = '<?php echo $_GET['id'] ?>';
		if(status == 'Borrowed'){
			$scope.tag = 'Return';
			stat = 'Available';
			user_id = '';
		}else{
			stat = 'Borrowed';
			$scope.tag = 'Borrow';
		}
		$http({
			method:"POST",
			url:"insert.php",
			data:{'id':id, 'user_id' : user_id, 'stat': stat, 'action':'UpdateStat'}
		}).success(function(data){
			$scope.hidden_id = id;
			$scope.success = true;
			$scope.error = false;
			$scope.successMessage = data.message;
			$scope.fetchData();
		});
	};

});

</script>