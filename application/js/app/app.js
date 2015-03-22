var app = angular.module('Test', ['ngRoute', 'ui.bootstrap']);

	app.config(['$routeProvider',function($routeProvider){
		$routeProvider.when('/',
		{
			templateUrl:'/site/renderIndex',
			controller:'IndexController'
		});
		
		$routeProvider.when('/view',
		{
			templateUrl:'/site/list',
			controller:'ListController'
		});

		$routeProvider.when('/add',
		{
			templateUrl:'/site/form',
			controller:'CreateController'
		});
		
		
		$routeProvider.when('/view/id/:id',
		{
			templateUrl:'/site/view',
			controller:'ViewController'
		});
		
		$routeProvider.when('/update/id/:id',
		{
			templateUrl:'/site/form',
			controller:'CreateController'
		});
		
		$routeProvider.otherwise({
			redirectTo: '/'
		});
	}]);
	
		app.filter('pagination', function() {
		  return function(arr, start, end) {
			return arr.slice(start, end);
		  };
		});