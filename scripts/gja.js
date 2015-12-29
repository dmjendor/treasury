$(document).ready(function(){
	$(document).on("click", "#addGJA", function(e) {
		if($("#gjaLoc option:selected").val()==0){
			alert("You need to select a location first!");
		} else {
			if($("#gjaSwitch").is(":checked")){ var buy = 1;} else { var buy = 0; }
			var item = [$("#gjaName").val(),$("#gjaValue").val(),$("#gjaQty").val(),$("#gjaLoc option:selected").val(),buy];
			var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
			$("#gjaList").load(url,{"table":"addGJA","value":item}, function(e){
				$(".gem-list li").draggable(dragOptsGem);
				$(".gem-list").droppable(dropOptsGem);
			});
		}
	});

	$(document).on("click", "#quickGJA", function(e) {
		if($("#gjaLoc option:selected").val()==0){
			alert("You need to select a location first!");
		} else {
			var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
			var item = [$("#gName").val(),$("#gType").val(),$("#gjaLoc option:selected").val()];
			$("#gjaList").load(url,{"table":"quickGJA","value":item}, function(e){
				$(".gem-list li").draggable(dragOptsGem);
				$(".gem-list").droppable(dropOptsGem);
			});
		}
	});

	$(document).on("click", ".increaseGJA", function(e) {
		$(this).siblings("span.badge").load('ajax/getTreasuryResults.php',{"table":"increaseGJA","value":$(this).val()});
	});

	$(document).on("click", ".decreaseGJA", function(e) {
		var me = $(this);
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		$.get(url,{"table":"decreaseGJA","value":$(this).val()}, function(response){
			if(response <= 0){
				$("#gjaList").load(url,{"table":"getGJA","value":"0"});
				$(".gem-list li").draggable(dragOptsGem);
				$(".gem-list").droppable(dropOptsGem);
			} else {
				$(me).siblings("span.badge").text(response);
			}
		});
	});

	$(document).on("click", ".sellGJA", function(e) {
		var me = $(this);
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		$.get(url,{"table":"sellGJA","value":$(this).val()}, function(response){
			if(response <= 0){
				$("#gjaList").load(url,{"table":"getGJA","value":"0"});
				$(".gem-list li").draggable(dragOptsGem);
				$(".gem-list").droppable(dropOptsGem);
			} else {
				$(me).siblings("span.badge").text(response);
			}
			$("#partyCoin").load(url,{"table":"getCoin","value":"0"});
		});
	});

	$(document).on("click", ".buyGJA", function(e) {
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		$(this).siblings("span.badge").load(url,{"table":"buyGJA","value":$(this).val()}, function(){
			$("#partyCoin").load('ajax/getTreasuryResults.php',{"table":"partyCoin","value":"0"});
			$(".gem-list li").draggable(dragOptsGem);
			$(".gem-list").droppable(dropOptsGem);
		});
	});

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
	$('ul.gem-list').on('mouseover', 'li:not(.ui-draggable)', function(){
		$(".gem-list li").draggable(dragOptsGem);
		$(".gem-list").droppable(dropOptsGem);
	});

	$("#gjaSwitch").bootstrapSwitch('onText','Buy');
	$("#gjaSwitch").bootstrapSwitch('offText','Add');

	$("#gjaSwitch").on('switchChange.bootstrapSwitch', function (event, state) {
		if ($("#addGJA").text() == "Buy"){
			$("#addGJA").text("Add");
			$("#quickGJA").text("Add");
		} else {
			$("#addGJA").text("Buy");
			$("#quickGJA").text("Buy");
		}
	});


	$(document).on("dblclick",".gjaGridItem", function(e){
		e.preventDefault();
		$(".modal-title").text("Edit Gems, Jewelry & Art");
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		$.ajax({
			type: 'POST',
			url: url,
			data: {"table":"editGJA","value":$(this).attr("data-id")}
		}).done(function(msg){
			$('#myModal').modal('show');
			$(".modal-body").html(msg);
		});
	});

	$(document).on("click", "#saveGJA", function(e){
		e.preventDefault();
		var url = 'ajax/getTreasuryResults.php?tId='+$("#vaultID").val();
		var item = [$(this).val(),$("#newGJAName").val(),$("#newGJAQty").val(),$("#newGJAValue").val(),$("#newGJALoc option:selected").val()]
		$.get(url,{"table":"saveGJA","value":item}, function(response){
			$("#gjaList").load(url,{"table":"getGJA","value":"0"}, function(e){
				$(".gem-list li").draggable(dragOptsGem);
				$(".gem-list").droppable(dropOptsGem);
			});
			$('#myModal').modal('hide');
		});
	});
});