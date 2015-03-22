var ListController = app.controller('ListController', 
			   ['$scope', '$rootScope', 'Customers', '$routeParams',
			   function ($scope, $rootScope, Customers, $routeParams) {
			   
	$scope.isNew = true;
	$scope.customers = {};
	$scope.customers = Customers.get();
	$scope.viewproduct = {};
	
	$scope.currentPage = 1;
	$scope.maxSize = 25;
	$scope.totalItems = $scope.customers.length;
	
    $scope.$watch('currentPage', function(newPage){
			
			$scope.watchPage = newPage*$scope.maxSize - $scope.maxSize;
			$scope.maxSizes = newPage*$scope.maxSize;
	});
	
	$rootScope.$on('customers:updated', function() {
	
		if ($scope.customers.length === 0) 
		{
			$scope.customers = Customers.get();
			$scope.totalItems = $scope.customers.length;
		}
	});

	 $scope.del = function(customer){
		Customers.del(customer);
		
		$rootScope.$on('customer:deleted', function() {
		var result = '';
		
		result = Customers.getDel();

			if(result === true)
			{
				angular.forEach($scope.customers, function(prod, key){
					if(prod.id === customer.id)
					{
						$scope.customers.splice(key, 1);
					}
					
				});
			}
		});
	};

}]);