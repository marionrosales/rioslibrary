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
		
		<div class="container" ng-init="fetchData()">
			<br />
				<h3 align="center">Rio's Library</h3>
			<br />
			<div class="alert alert-success alert-dismissible" ng-show="success" >
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				{{successMessage}}
			</div>
			<br />
			<a href="borrowed.php?id=<?php echo $_GET['id']?>">See Borrowed Books</a>
			<a float="right" href="logout.php" style="float:right">Logout</a>
			<br><br>
			<div class="table-responsive" style="overflow-x: unset;">
				<table datatable="ng" dt-options="vm.dtOptions" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Title</th>
							<th>Author</th>
							<th>Genre</th>
							<th>Section</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="book in bookData">
							<td>{{book.title}}</td>
							<td>{{book.author}}</td>
							<td>{{book.genre}}</td>
							<td>{{book.section}}</td>
							<td><button type="button" ng-click="borrow(book.id)" class="btn btn-info btn-xs">Borrow</button></td>
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
		var id = '<?php echo $_GET['id']?>';
		$http.get('fetch_data.php').success(function(data){
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

	$scope.borrow = function(id){
		var user_id = '<?php echo $_GET['id'] ?>';
		var stat = 'Borrowed';
		if(confirm("Are you sure you want to borrow this book?"))
		{
		$http({
			method:"POST",
			url:"insert.php",
			data:{'id':id, 'user_id': user_id, 'stat': stat, 'action':'UpdateStat'}
		}).success(function(data){
			$scope.hidden_id = id;
			$scope.success = true;
			$scope.error = false;
			$scope.successMessage = data.message;
			$scope.fetchData();
		});
	};
	}

});

</script>