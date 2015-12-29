$(document).ready(function(){
	$(document).on("click", ".addPerm", function(e) {
		if($(this).attr("rel") == "view"){
			if($("#addAll").is(':checked')){ var all = 1; } else { var all = 0; }
			var item = [$(this).closest("div").find("[type=text]").val(),$(this).attr("rel"),all];
		} else {
			var item = [$(this).closest("div").find("input").val(),$(this).attr("rel")];
		}

		$.ajax({ type: 'POST', url: 'ajax/getTreasuryResults.php', data: {"table":"addPerm","value":item}
		}).done(function(msg){
			if(msg == "fail"){
				alert("The username or email address you entered does not exist.");
			} else if (msg == "dup") {
				alert("This permission already exists for the user.");
			} else {
				var loc = "#"+msg+"PermList";
				$(loc).load('ajax/getTreasuryResults.php',{"table":"getPerms","value":msg});
				if ($("#addAll").is(':checked'))
				{
					$("#coinPermList").load('ajax/getTreasuryResults.php',{"table":"getCoinPerms","value":"0"});
					$("#gjaPermList").load('ajax/getTreasuryResults.php',{"table":"getGJAPerms","value":"0"});
					$("#itemPermList").load('ajax/getTreasuryResults.php',{"table":"getItemPerms","value":"0"});
				}
			}
		});
	});

	$(document).on("click", ".delPerm", function(e) {
		e.preventDefault();
		var cat = $(this).attr('rel');
		var val = $(this).val();
		var loc = "#"+cat+"PermList";
		$.ajax({ type: 'POST', url: 'ajax/getTreasuryResults.php', data: {"table":"delPerm","value":val}
		}).done(function(msg){
			$(loc).load('ajax/getTreasuryResults.php',{"table":"getPerms","value":cat});
		});
	});
});