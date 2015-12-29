$(document).ready(function() {
	var dragOpts = {
			revert:false,
			drag:function () {
				$(this).addClass("active");
				$(this).closest(".treasure-list").addClass("active");
			},
			stop:function () {
				$(this).removeClass("active").closest(".treasure-list").removeClass("active");
			}
		};

	$("[name='treasureInc']").bootstrapSwitch('onColor','success');

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
				if(msg == 1){
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
				if(msg == 1){
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
	var dropOpts = {
			activeClass:"active",
			hoverClass:"hover",
			tolerance:"touch",
			drop:function (event, ui) {

				var basket = $(this);
				var move = ui.draggable;
				var itemId = basket.find("ul li[data-id='" + move.attr("data-id") + "']");
				var item = [$(this).attr("data-id"), move.attr("data-id")];
				$( "<li class='list-group-item' data-id='"+itemId+"'></li>" ).html( ui.draggable.html() ).appendTo( this ).draggable;
				$("#itemList").load('ajax/getTreasuryResults.php',{"table":"moveItem","value":item}, function(){
					$(".treasure-list li").draggable(dragOpts);
					$(".treasure-list").droppable(dropOpts);
				});

			}
		};


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
		});
	});

	$(document).on("click", ".removeBag", function(e) {
		e.preventDefault();

		$.ajax({
			type: 'POST',
			url: 'ajax/getTreasuryResults.php',
			data: {"table":"removeBag","value":$(".removeBag").val()}
		}).done(function(msg){
			$(".bagList").html(msg);
		});
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
		$("#"+$(this).attr("ref")).load('ajax/getTreasuryResults.php',{"table":$(this).attr("ref"),"value":"0"});
	});

	$(document).on("click", "#addItem", function(e) {
		if($("#itemLoc option:selected").val()==0){
			alert("You need to select a location first!");
		} else {
			var item = [$("#itemName").val(),$("#itemValue").val(),$("#itemQty").val(),$("#itemLoc option:selected").val()];
			$("#itemList").load('ajax/getTreasuryResults.php',{"table":"addItem","value":item}, function(){
				$(".treasure-list li").draggable(dragOpts);
				$(".treasure-list").droppable(dropOpts);
			});
		}
	});
	
	$(document).on("click", "#quickItem", function(e) {
		if($("#itemLoc option:selected").val()==0){
			alert("You need to select a location first!");
		} else {
			var item = [$("#qName").val(),$("select#qMod").val(),$("#itemLoc option:selected").val()];
			$("#itemList").load('ajax/getTreasuryResults.php',{"table":"quickItem","value":item}, function(){
				$(".treasure-list li").draggable(dragOpts);
				$(".treasure-list").droppable(dropOpts);
			});
		}
	});


	$(document).on("click", ".increaseItem", function(e) {
		$(this).siblings("span.badge").load('ajax/getTreasuryResults.php',{"table":"increaseItem","value":$(this).val()});
	});

	$(document).on("click", ".decreaseItem", function(e) {
		var me = $(this);
		$.get('ajax/getTreasuryResults.php',{"table":"decreaseItem","value":$(this).val()}, function(response){
			if(response <= 0){
				$("#itemList").load('ajax/getTreasuryResults.php',{"table":"getItems","value":"0"}, function(){
				$(".treasure-list li").draggable(dragOpts);
				$(".treasure-list").droppable(dropOpts);
			});
			} else {
				$(me).siblings("span.badge").text(response);
			}
		});
	});

	$(document).on("click", ".sellItem", function(e) {
		var me = $(this);
		$.get('ajax/getTreasuryResults.php',{"table":"sellItem","value":$(this).val()}, function(response){
			if(response <= 0){
				$("#itemList").load('ajax/getTreasuryResults.php',{"table":"getItems","value":"0"});
			} else {
				$(me).siblings("span.badge").text(response);
			}
			$("#partyCoin").load('ajax/getTreasuryResults.php',{"table":"partyCoin","value":"0"});
		});
	});

	$(document).on("click", ".buyItem", function(e) {
		$(this).siblings("span.badge").load('ajax/getTreasuryResults.php',{"table":"buyItem","value":$(this).val()}, function(){
			$("#partyCoin").load('ajax/getTreasuryResults.php',{"table":"partyCoin","value":"0"}, function(){
				$(".treasure-list li").draggable(dragOpts);
				$(".treasure-list").droppable(dropOpts);
			});
		});
	});

	$(document).on("click", "#ptBtn", function(e) {
		if( $("#platinum").val().length != 0){
			$("#platinum").parent().addClass("has-success");
			var pVal = $("#platinum").val();
			$.get('ajax/getTreasuryResults.php',{"table":"setPt","value":pVal}, function(response){
				if(response <= 0){
					$("#partyCoin").load('ajax/getTreasuryResults.php',{"table":"partyCoin","value":"0"});
				}
			});
		} else {
			$("#platinum").parent().addClass("has-error");
		}
	});		
	$(document).on("click", "#auBtn", function(e) {
		if( $("#gold").val().length != 0){
			$("#gold").parent().addClass("has-success");
			var pVal = $("#gold").val();
			$.get('ajax/getTreasuryResults.php',{"table":"setAu","value":pVal}, function(response){
				if(response <= 0){
					$("#partyCoin").load('ajax/getTreasuryResults.php',{"table":"partyCoin","value":"0"});
				}
			});
		} else {
			$("#gold").parent().addClass("has-error");
		}
	});		
	$(document).on("click", "#agBtn", function(e) {
		if( $("#silver").val().length != 0){
			$("#silver").parent().addClass("has-success");
			var pVal = $("#silver").val();
			$.get('ajax/getTreasuryResults.php',{"table":"setAg","value":pVal}, function(response){
				if(response <= 0){
					$("#partyCoin").load('ajax/getTreasuryResults.php',{"table":"partyCoin","value":"0"});
				}
			});
		} else {
			$("#silver").parent().addClass("has-error");
		}
	});		

	$(document).on("click", "#cuBtn", function(e) {
		if( $("#copper").val().length != 0){
			$("#copper").parent().addClass("has-success");
			var pVal = $("#copper").val();
			$.get('ajax/getTreasuryResults.php',{"table":"setCu","value":pVal}, function(response){
				if(response <= 0){
					$("#partyCoin").load('ajax/getTreasuryResults.php',{"table":"partyCoin","value":"0"});
				}
			});
		} else {
			$("#copper").parent().addClass("has-error");
		}
	});		

	$(document).on("click", "#split", function(e) {
		e.preventDefault();
		if( $("#partyNum").val().length != 0){
			$("#partyNum").parent().addClass("has-success");
			$(".modal-title").text("Treasure Split");
			var tVal = $("#treasureInc:checked").val();
			var pVal = $("#partyNum").val();
			$.ajax({
				type: 'POST',
				url: 'ajax/getTreasuryResults.php',
				data: {"table":"tSplit","value":pVal,"tInc":tVal}
			}).done(function(msg){
				$('#myModal').modal('show');
				$(".modal-body").html(msg);
				$("#partyCoin").load('ajax/getTreasuryResults.php',{"table":"partyCoin","value":"0"});
			});
		} else {
			$("#partyNum").parent().addClass("has-error");
		}
	});		

	$(document).on("click", "#addGJA", function(e) {
		if($("#gjaLoc option:selected").val()==0){
			alert("You need to select a location first!");
		} else {
			var item = [$("#gjaName").val(),$("#gjaValue").val(),$("#gjaQty").val(),$("#gjaLoc option:selected").val()];
			$("#gjaList").load('ajax/getTreasuryResults.php',{"table":"addGJA","value":item}, function(e){
				$(".gjaList li").draggable(dragOpts);
				$(".gjaList").droppable(dropOpts);
			});
		}
	});

	$(document).on("click", "#quickGJA", function(e) {
		if($("#gjaLoc option:selected").val()==0){
			alert("You need to select a location first!");
		} else {
			var item = [$("#gName").val(),$("#gType").val(),$("#gjaLoc option:selected").val()];
			$("#gjaList").load('ajax/getTreasuryResults.php',{"table":"quickGJA","value":item}, function(e){
				$(".gjaList li").draggable(dragOpts);
				$(".gjaList").droppable(dropOpts);
			});
		}
	});

	$(document).on("click", ".increaseGJA", function(e) {
		$(this).siblings("span.badge").load('ajax/getTreasuryResults.php',{"table":"increaseGJA","value":$(this).val()});
	});

	$(document).on("click", ".decreaseGJA", function(e) {
		var me = $(this);
		$.get('ajax/getTreasuryResults.php',{"table":"decreaseGJA","value":$(this).val()}, function(response){
			if(response <= 0){
				$("#gjaList").load('ajax/getTreasuryResults.php',{"table":"getGJA","value":"0"});
				$(".gjaList li").draggable(dragOpts);
				$(".gjaList").droppable(dropOpts);
			} else {
				$(me).siblings("span.badge").text(response);
			}
		});
	});

	$(document).on("click", ".sellGJA", function(e) {
		var me = $(this);
		$.get('ajax/getTreasuryResults.php',{"table":"sellGJA","value":$(this).val()}, function(response){
			if(response <= 0){
				$("#gjaList").load('ajax/getTreasuryResults.php',{"table":"getGJA","value":"0"});
				$(".gjaList li").draggable(dragOpts);
				$(".gjaList").droppable(dropOpts);
			} else {
				$(me).siblings("span.badge").text(response);
			}
			$("#partyCoin").load('ajax/getTreasuryResults.php',{"table":"partyCoin","value":"0"});
		});
	});

	$(document).on("click", ".buyGJA", function(e) {
		$(this).siblings("span.badge").load('ajax/getTreasuryResults.php',{"table":"buyGJA","value":$(this).val()}, function(){
			$("#partyCoin").load('ajax/getTreasuryResults.php',{"table":"partyCoin","value":"0"});
			$(".gjaList li").draggable(dragOpts);
			$(".gjaList").droppable(dropOpts);
		});
	});

	$('ul.gjaList').on('mouseover', 'li:not(.ui-draggable)', function(){
		$(".gjaList li").draggable(dragOpts);
		$(".gjaList").droppable(dropOpts);
	});

	$('ul.treasure-list').on('mouseover', 'li:not(.ui-draggable)', function(){
		$(".treasure-list li").draggable(dragOpts);
		$(".treasure-list").droppable(dropOpts);
	});
		$("#qType").remoteChained("#qClass", "ajax/getChain.php");
		$("#qName").remoteChained("#qType", "ajax/getChain.php");
		$("#qMod").remoteChained("#qName", "ajax/getChain.php");

		$("#gType").remoteChained("#gClass", "ajax/getChain.php");
		$("#gName").remoteChained("#gType", "ajax/getChain.php");
	});