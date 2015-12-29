$(document).ready(function(){


	$(document).on("click", ".addCurrency", function(e){
		var item = [$(this).val(),$(this).closest("div").find("input").val()];
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		$.ajax({ 
			type: 'POST', 
			url: url, 
			data: {"table":"addCurrencyValue","value":item}
		}).done(function(msg){
			$("#coinList").load(url,{"table":"getCoin","value":'0'});
		});
	});


	$(document).on("click", "#split", function(e) {
		e.preventDefault();
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		if( $("#partyNum").val().length != 0){
			$("#partyNum").parent().addClass("has-success");
			$(".modal-title").text("Treasure Split");
			var tVal = $("#treasureInc:checked").val();
			var pVal = $("#partyNum").val();
			$.ajax({
				type: 'POST',
				url: url,
				data: {"table":"newSplit","value":pVal,"tInc":tVal}
			}).done(function(msg){
				$('#myModal').modal('show');
				$(".modal-body").html(msg);
				$("#coinList").load(url,{"table":"getCoin","value":'0'});
			});
		} else {
			$("#partyNum").parent().addClass("has-error");
		}
	});		

});