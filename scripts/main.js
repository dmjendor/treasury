$(document).ready(function() {
	$.fn.bootstrapSwitch.defaults.size = 'small';
	$.fn.bootstrapSwitch.defaults.onColor = 'success';
	$.fn.bootstrapSwitch.defaults.offColor = 'primary';
	$("[name='treasureInc']").bootstrapSwitch();

	var dragOptsGem = {
			revert:false,
			drag:function () {
				$(this).addClass("active");
				$(this).closest(".gem-list").addClass("active");
			},
			stop:function () {
				$(this).removeClass("active").closest(".gem-list").removeClass("active");
			}
		};

	var dropOptsGem = {
			activeClass:"active",
			hoverClass:"hover",
			tolerance:"touch",
			drop:function (event, ui) {
				var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
				var basket = $(this);
				var move = ui.draggable;
				var itemId = basket.find("ul li[data-id='" + move.attr("data-id") + "']");
				var item = [$(this).attr("data-id"), move.attr("data-id")];
				$( "<li class='list-group-item' data-id='"+itemId+"'></li>" ).html( ui.draggable.html() ).appendTo( this ).draggable;
				$("#gjaList").load(url,{"table":"moveGJA","value":item}, function(){
					$(".gem-list li").draggable(dragOptsGem);
					$(".gem-list").droppable(dropOptsGem);
				});

			}
		};

	var dragOptsItem = {
			revert:false,
			drag:function () {
				$(this).addClass("active");
				$(this).closest(".treasure-list").addClass("active");
			},
			stop:function () {
				$(this).removeClass("active").closest(".treasure-list").removeClass("active");
			}
		};

	var dropOptsItem = {
			activeClass:"active",
			hoverClass:"hover",
			tolerance:"touch",
			drop:function (event, ui) {
				var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
				var basket = $(this);
				var move = ui.draggable;
				var itemId = basket.find("ul li[data-id='" + move.attr("data-id") + "']");
				var item = [$(this).attr("data-id"), move.attr("data-id")];
				$( "<li class='list-group-item' data-id='"+itemId+"'></li>" ).html( ui.draggable.html() ).appendTo( this ).draggable;
				$("#itemList").load(url,{"table":"moveItem","value":item}, function(){
					$(".treasure-list li").draggable(dragOptsItem);
					$(".treasure-list").droppable(dropOptsItem);
				});

			}
		};

	$(document).on("click","#commit", function(e){
		e.preventDefault;
		var user = $("#user_username").val();
		var pass = $("#user_password").val();
		var rmbr = $("#user_remember_me").val();
		$.ajax({
			type: "POST",
			url: "ajax/logauth.php",
			data: {"user":user,"pass":pass,"rmbr":rmbr},
			success: function(msg){
				if(msg == "SUCCESS"){
					location.reload(true);
				} else {
					var loginBox = '					<div style="padding: 15px; padding-bottom: 0px; width: 300px; margin: 0 auto;" >'+
									'						<input id="user2_username" style="margin-bottom: 15px;" type="text" name="user_username" placeholder="Username" size="30" />'+
									'						<input id="user2_password" placeholder="Password" style="margin-bottom: 15px;" type="password" name="user_password" size="30" />'+
									'						<label class="string optional" for="user2_remember_me"> Remember me'+
									'						<input id="user2_remember_me" style="float: left; margin-right: 10px;" type="checkbox" name="user_remember_me" value="1" />'+
									'						</label>'+
									'						<button class="btn btn-primary" style="clear: left; width: 100%; height: 32px; font-size: 13px;" name="commit2" id="commit2">Sign In</button>'+
									'					</div>';
					$(".modal-title").text("Login Failure");
					$(".modal-body").html("User name or password incorrect please try again.<br />"+loginBox);
					$('#myModal').modal('show');
				}
			}
		});
	});

	$(document).on("click","#commit2", function(e){
		e.preventDefault;
		var user = $("#user2_username").val();
		var pass = $("#user2_password").val();
		var rmbr = $("#user2_remember_me").val();
		$.ajax({
			type: "POST",
			url: "ajax/logauth.php",
			data: {"user":user,"pass":pass,"rmbr":rmbr},
			success: function(msg){
				if(msg == "SUCCESS"){
					location.reload(true);
				} else {
					var loginBox = '					<div style="padding: 15px; padding-bottom: 0px; width: 300px; margin: 0 auto;" >'+
									'						<input id="user2_username" style="margin-bottom: 15px;" type="text" name="user_username" placeholder="Username" size="30" />'+
									'						<input id="user2_password" placeholder="Password" style="margin-bottom: 15px;" type="password" name="user_password" size="30" />'+
									'						<label class="string optional" for="user2_remember_me"> Remember me'+
									'						<input id="user2_remember_me" style="float: left; margin-right: 10px;" type="checkbox" name="user_remember_me" value="1" />'+
									'						</label>'+
									'						<button class="btn btn-primary" style="clear: left; width: 100%; height: 32px; font-size: 13px;" name="commit2" id="commit2">Sign In</button>'+
									'					</div>';
					$(".modal-title").text("Login Failure");
					$(".modal-body").html("User name or password incorrect please try again.<br />"+loginBox);
					$('#myModal').modal('show');
				}
			}
		});
	});

	// Fix input element click problem
	$('.dropdown-menu input, .dropdown-menu label').click(function(e) {
		e.stopPropagation();
	});


	$(document).on("click", ".minimize", function(e) {
		$(this).addClass("restore");
		$(this).removeClass("minimize");
		$(this).html("<span class='glyphicon glyphicon-chevron-down'>");
		$(this).parent().parent().siblings(".panel-body").toggle();
	});

	$(document).on("click", ".restore", function(e) {
		$(this).removeClass("restore");
		$(this).addClass("minimize");
		$(this).html("<span class='glyphicon glyphicon-chevron-up'>");
		$(this).parent().parent().siblings(".panel-body").toggle();
	});

	$(document).on("click", ".refresh", function(e) {
		var loc = "#"+$(this).attr("rel");
		var ref = $(this).attr("ref");
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		$(loc).load(url,{"table":$(this).attr("ref"),"value":$("#vaultID").val()}, function(){
			if(ref == "getGJA"){
				$(".gem-list li").draggable(dragOptsGem);
				$(".gem-list").droppable(dropOptsGem);
			}
			if(ref == "getItems"){
				$(".treasure-list li").draggable(dragOptsItem);
				$(".treasure-list").droppable(dropOptsItem);
			}
		});

	});


	$(document).on("click", "#createVault", function(e) {
		e.preventDefault();
		if($("#vaultName").val()== ""){
			alert("You need to enter a Vault Name first!");
		} else {
			$.ajax({ type: 'POST', url: 'ajax/createVault.php', data: {"value":$("#vaultName").val()}
			}).success(function(msg){
				location.reload(true);
			});
		}
	});

});