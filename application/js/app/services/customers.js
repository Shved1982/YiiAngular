app.factory('Customers', ['$http', '$rootScope', function($http, $rootScope){

	var customers = [];
	
	function getCustomers() {
		$http.get('/site/getList')
			.success(function(data, status, headers, config) {
				customers = data;
				
				$rootScope.$broadcast('customers:updated');
			})
			.error(function(data, status, headers, config) {
				console.log(data);
			});
	}
	
	getCustomers();
	
	var service = {};

	service.get = function() {
		return customers;
	}
	
	var newCustomer = {};
	service.add = function(customer) {
		newCustomer = '';
		
		$http.post('/site/addCustomer/', {data: customer, YII_CSRF_TOKEN : app.csrfToken})
			.success(function(data, status, headers, config) {
				newCustomer = data;
				getCustomers();
				$rootScope.$broadcast('customer:added', data);
			})
			.error(function(data, status, headers, config) {
				$rootScope.$broadcast('customer:error', data);
			});
	}

	service.getNew = function(){
		return newCustomer;
	}
	
	var deletingResult = false;
	
	service.del = function(customer) {
		deletingResult = '';
		$http.post('/site/deleteCustomer',{data: customer.id, YII_CSRF_TOKEN : app.csrfToken})
			.success(function(data, status, headers, config) {
				deletingResult = true;
				$rootScope.$broadcast('customer:deleted', data);
			})
			.error(function(data, status, headers, config) {
				deletingResult = false;
				$rootScope.$broadcast('customer:error', data);
			});

	}
	
	service.getDel = function() {
		return deletingResult;
	}
	
	service.update = function(customer) {
		$http.post('/site/updateCustomer',{data: customer, YII_CSRF_TOKEN : app.csrfToken})
			.success(function(data, status, headers, config) {
				$rootScope.$broadcast('customer:updated', customer);
			})
			.error(function(data, status, headers, config) {
				$rootScope.$broadcast('customer:error', data);
			});
	}
	
	return service;
	}]);