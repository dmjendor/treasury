$(document).ready(function(){
	$(document).on("click", ".btn-danger", function(e) {
		e.preventDefault();
		var del = confirm("Are you sure you wish to permanently delete all the History?");
		if(del == true){
			$.ajax({ type: 'POST', url: 'ajax/getTreasuryResults.php', data: {"table":"logDel","value":$(this).attr("rel")}});
		}
	});


});