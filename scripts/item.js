$(document).ready(function(){
	$(document).on("click", "#addItem", function(e) {
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		if($("#itemLoc option:selected").val()==0){
			alert("You need to select a location first!");
		} else {

			if($("#itemSwitch").is(":checked")){ var buy = 1; } else { var buy = 0; }
			var item = [$("#itemName").val(),$("#itemValue").val(),$("#itemQty").val(),$("#itemLoc option:selected").val(),buy];
			$("#itemList").load(url,{"table":"addItem","value":item}, function(){
				$(".treasure-list li").draggable(dragOptsItem);
				$(".treasure-list").droppable(dropOptsItem);
			});
		}
	});
	
	$(document).on("click", "#quickItem", function(e) {
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		if($("#itemLoc option:selected").val()==0){
			alert("You need to select a location first!");
		} else {
			var item = [$("#qName").val(),$("select#qMod").val(),$("#itemLoc option:selected").val()];
			$("#itemList").load(url,{"table":"quickItem","value":item}, function(){
				$(".treasure-list li").draggable(dragOptsItem);
				$(".treasure-list").droppable(dropOptsItem);
			});
		}
	});


	$(document).on("click", ".increaseItem", function(e) {
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		$(this).siblings("span.badge").load(url,{"table":"increaseItem","value":$(this).val()});
	});

	$(document).on("click", ".decreaseItem", function(e) {
		var me = $(this);
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		$.get(url,{"table":"decreaseItem","value":$(this).val()}, function(response){
			if(response <= 0){
				$("#itemList").load(url,{"table":"getItems","value":"0"}, function(){
				$(".treasure-list li").draggable(dragOptsItem);
				$(".treasure-list").droppable(dropOptsItem);
			});
			} else {
				$(me).siblings("span.badge").text(response);
			}
		});
	});

	$(document).on("click", ".sellItem", function(e) {
		var me = $(this);
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		$.get(url,{"table":"sellItem","value":$(this).val()}, function(response){
			if(response <= 0){
				$("#itemList").load(url,{"table":"getItems","value":"0"});
			} else {
				$(me).siblings("span.badge").text(response);
			}
			$("#partyCoin").load(url,{"table":"partyCoin","value":"0"});
		});
	});

	$(document).on("click", ".buyItem", function(e) {
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		$(this).siblings("span.badge").load(url,{"table":"buyItem","value":$(this).val()}, function(){
			$("#partyCoin").load(url,{"table":"partyCoin","value":"0"}, function(){
				$(".treasure-list li").draggable(dragOptsItem);
				$(".treasure-list").droppable(dropOptsItem);
			});
		});
	});

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

	$('ul.treasure-list').on('mouseover', 'li:not(.ui-draggable)', function(){
		$(".treasure-list li").draggable(dragOptsItem);
		$(".treasure-list").droppable(dropOptsItem);
	});

	$("#qType").remoteChained("#qClass", "ajax/getChain.php");
	$("#qName").remoteChained("#qType", "ajax/getChain.php");
	$("#qMod").remoteChained("#qName", "ajax/getChain.php");

	$("#gType").remoteChained("#gClass", "ajax/getChain.php");
	$("#gName").remoteChained("#gType", "ajax/getChain.php");

	$("#itemSwitch").bootstrapSwitch('onText','Buy');
	$("#itemSwitch").bootstrapSwitch('offText','Add');

	$("#itemSwitch").on('switchChange.bootstrapSwitch', function (event, state) {
		if ($("#addItem").text() == "Buy"){
			$("#addItem").text("Add");
			$("#quickItem").text("Add");
		} else {
			$("#addItem").text("Buy");
			$("#quickItem").text("Buy");
		}
	});

	$(document).on("dblclick",".itemGridItem", function(e){
		e.preventDefault();
		$(".modal-title").text("Edit Item");
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		$.ajax({
			type: 'POST',
			url: url,
			data: {"table":"editItems","value":$(this).attr("data-id")}
		}).done(function(msg){
			$('#myModal').modal('show');
			$(".modal-body").html(msg);
		});
	});

	$(document).on("click", "#saveItem", function(e){
		e.preventDefault();
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		var item = [$(this).val(),$("#newTName").val(),$("#newTQty").val(),$("#newTValue").val(),$("#newTLoc option:selected").val()]
		$.get(url,{"table":"saveItem","value":item}, function(response){
			$("#itemList").load(url,{"table":"getItems","value":"0"}, function(){
				$(".treasure-list li").draggable(dragOptsItem);
				$(".treasure-list").droppable(dropOptsItem);
			});
			$('#myModal').modal('hide');
		});
	});

});