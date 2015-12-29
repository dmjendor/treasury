$(document).ready(function(){
	$(document).on("click", ".addBag", function(e) {
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'ajax/getTreasuryResults.php',
			data: {"table":"addBag","value":$("#bagName").val()}
		}).done(function(msg){
			$(".bagList").html(msg);
			$.ajax({
				type: 'POST',
				url: 'ajax/getTreasuryResults.php',
				data: {"table":"bagList","value":"0"}
			}).done(function(msg1){
				$("#gjaLoc").html(msg1);
				$("#itemLoc").html(msg1);
			});
		});
	});

	$(document).on("click", ".editBag", function(e) {
		e.preventDefault();
		$(".modal-title").text("Bag Management");
		$.ajax({
			type: 'POST',
			url: 'ajax/getTreasuryResults.php',
			data: {"table":"bagMan","value":'0'}
		}).done(function(msg){
			$('#myModal').modal('show');
			$(".modal-body").html(msg);
		});
	});


	$(document).on("click", ".quickBag", function(e) {
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'ajax/getTreasuryResults.php',
			data: {"table":"quickBag","value":$("#quickBag option:selected").val()}
		}).done(function(msg){
			$(".bagList").html(msg);
			$.ajax({
				type: 'POST',
				url: 'ajax/getTreasuryResults.php',
				data: {"table":"bagList","value":"0"}
			}).done(function(msg1){
				$("#gjaLoc").html(msg1);
				$("#itemLoc").html(msg1);
			});
		});
	});

	
	$(document).on("click", ".removeBag", function(e) {
		e.preventDefault();

		$.ajax({
			type: 'POST',
			url: 'ajax/getTreasuryResults.php',
			data: {"table":"removeBag","value":$(this).val()}
		}).done(function(msg){
			$(".bagList").html(msg);
			$.ajax({
				type: 'POST',
				url: 'ajax/getTreasuryResults.php',
				data: {"table":"bagList","value":"0"}
			}).done(function(msg1){
				$("#gjaLoc").html(msg1);
				$("#itemLoc").html(msg1);
			});
		});
	});
});