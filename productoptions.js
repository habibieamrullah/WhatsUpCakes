var moptions = []

function addnewoptiontitle(){
	
	var nottl = $("#newoptiontitle").val()
	if(nottl != ""){
		moptions.push({ title : nottl , options : [] })
		updatemovisual()
		closemoform()
		$("#moformbutton").hide()
		editmop(0)
	}
	
}

function editmop(i){
	$("#moformedit").show()
	$("#motitletoedit").html(moptions[i].title)
}

function addcurrentmoitem(){
	var moitem = $("#moitem").val()
	var moprice = $("#moprice").val()
	if(moitem != "" && moprice > 0){
		moptions[0].options.push({ title : moitem, price : moprice})
		$("#moitem").val("").focus()
		$("#moprice").val(0)
		updatemovisual()
	}
}

function showmoform(){
	$("#moformbutton").hide()
	$("#moform").show()
}
function closemoform(){
	$("#moformbutton").show()
	$("#moform").hide()
}
function closemoeditform(){
	$("#moformedit").hide()
}
function updatemovisual(){
	if(moptions.length == 1){
		$("#moreoptionsvisual").html("")
		var mocontent = ""
		for(var i = 0; i < moptions.length; i++){
			mocontent += "<div class='categoryblock'><div><i class='fa fa-check-square-o'></i> " + moptions[i].title + "<span onclick='editmop("+i+")'style='cursor: pointer; margin-left: 20px; font-size: 12px; color: black;'><i class='fa fa-edit'></i> <?php echo uilang("Edit") ?></span></div>"
			if(moptions[i].options.length > 0){
				for(var x = 0; x < moptions[i].options.length; x++){
					mocontent += "<div style='font-size: 12px; padding: 10px;'><i class='fa fa-arrow-right'></i> " +moptions[i].options[x].title+ " (" +tSep(moptions[i].options[x].price)+ ")</div>"
				}
			}
			mocontent += "</div>"
		}
		$("#moreoptionsvisual").append(mocontent)
		$("#newoptiontitle").val("")
		$("#moform").hide()
		$("#moformbutton").hide()
		$("#moreoptions").val(JSON.stringify(moptions))
	}else{
		$("#moreoptionsvisual").html("<?php echo uilang("There is no option has been added.") ?>")
		$("#moformbutton").show()
	}
}
updatemovisual()