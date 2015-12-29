$(document).ready(function(){


	$(document).on("click", ".prepList", function(e){
		$(".prepList").removeClass("bg-warning");
		$(this).addClass("bg-warning");
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/getTreasuryResults.php', 
			data: {"table":"getPrepItems","value":$(this).attr("data-id")}
		}).done(function(msg){
			$(".prepItems").html(msg);
		});
	});

	$(".prepList").on({
		mouseenter: function () {
			$(this).addClass("bg-info");
		},
		mouseleave: function () {
			$(this).removeClass("bg-info");
		}

	});
	

});