$(document).ready(function(){
	$(document).on("click", ".saveMarkup", function(e) {
		e.preventDefault();
		var item = [$(this).closest("div").find("input").val(),$(this).attr("rel")];
		$.ajax({ type: 'POST', url: 'ajax/getTreasuryResults.php', data: {"table":"saveMarkup","value":item}});
	});


	$(document).on("change", "#cssChange", function(e) {
		e.preventDefault();
		$("#style").attr("href", "http://www.partytreasury.com/css/"+$("#cssChange option:selected").val());
	});

	$(document).on("click", "#saveCSS", function(e) {
		e.preventDefault();
		$.ajax({ type: 'POST', url: 'ajax/getTreasuryResults.php', data: {"table":"saveCSS","value":$("#cssChange option:selected").val()}});
	});

	$(document).on("click", ".createCurrency", function(e) {
		e.preventDefault();
		$(".modal-title").text("Currency Management");
		$.ajax({
			type: 'POST',
			url: 'ajax/getTreasuryResults.php',
			data: {"table":"currencyMan","value":'0'}
		}).done(function(msg){
			$('#myModal').modal('show');
			$(".modal-body").html(msg);
		});
	});


	$(document).on("click", ".addCurrency", function(e) {
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'ajax/getTreasuryResults.php',
			data: {"table":"addCurrency","value":[$("#currencyName").val(),$("#abbrev").val(),$("#multiplier").val()]}
		}).done(function(msg){
			$(".currencyList").html(msg);
			$.ajax({
				type: 'POST',
				url: 'ajax/getTreasuryResults.php',
				data: {"table":"currencyList","value":"0"}
			});
		});
	});
	$(document).on("click", ".removeCurrency", function(e) {
		e.preventDefault();

		$.ajax({
			type: 'POST',
			url: 'ajax/getTreasuryResults.php',
			data: {"table":"removecurrency","value":$(this).val()}
		}).done(function(msg){
			$(".currencyList").html(msg);
			$.ajax({
				type: 'POST',
				url: 'ajax/getTreasuryResults.php',
				data: {"table":"currencyList","value":"0"}
			});
		});
	});

	$(document).on("change", "#commonCurrency", function(e) {
		$.ajax({ type: 'POST', url: 'ajax/getTreasuryResults.php', data: {"table":"saveComCur","value":$("#commonCurrency option:selected").val()}});
	});
	$(document).on("change", "#baseCurrency", function(e) {
		$.ajax({ type: 'POST', url: 'ajax/getTreasuryResults.php', data: {"table":"saveBaseCur","value":$("#baseCurrency option:selected").val()}});
	});

});