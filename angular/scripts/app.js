(function(){
	var app = angular.module('treasury', []);
	app.controller('TreasuryController', function(){
		this.product = gem;
	});

	var gem = {
		name: 'Dodecahedron',
		price: 2.95,
		description: '......',
	}
})();



