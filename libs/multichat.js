var id;
function bloc(settings)
{
	switch(settings)
	{
		case "connexion":
		$( "body" ).html("<div class=\"bloc\"><div class=\"title\"></div><div class=\"content\"></div></div>");
		
		$( ".title" ).html("Connexion");
		$( ".content" ).html("Login : <input type=\"text\" id=\"login\" value=\"tom\"><br>Password : <input type=\"password\" id=\"password\" value=\"bou\"></br><button type=\"button\" id=\"connexion\">Connexion</button>");
		break;
		case "convChoice":
		$( ".title" ).html("Choix Conversation");
		showButton();
		$( ".content" ).html("");
		$.ajax({ url: 'data.php',
			data: {action: 'getConversations'},
			type: 'post',
			success: function(output) {
				var obj = jQuery.parseJSON(output);
				var conv = obj.conversations;
				var result = "<select id=\"conv\">";
				$.each(conv, function(i, item) {
					result += "<option id="+item.id+">";
					result += item.theme;
					result += "</option>";
				});
				$( ".content" ).append("Conv:" +result);
				$( ".content" ).append("<button type=\"button\" id=\"selectConv\">Afficher</button>");
			}
		});
		break;
		case "conv":
		$( ".title" ).html("");
		$( ".title" ).html($('#conv').find(":selected").val());
		showButton();
		id = $('#conv').find(":selected").attr("id");
				$( ".content" ).html("");
		$.ajax({ url: 'data.php',
			data: {action: 'getMessages',idConv:id,idLastMessage:"0"},
			type: 'post',
			success: function(output) {
				var obj = jQuery.parseJSON(output);
				var conv = obj.messages;
				var result = "";
				$.each(conv, function(i, item) {
					result += "<p style=\"color:"+ item.couleur+";\">";
					result += "["+ item.auteur +"]";
					result += item.contenu;
					result += "</p>";
				});
				$( ".content" ).append(result);
				$( ".content" ).append("<input type=\"text\" id=\"message\" value=\"\"><br>");
				$( ".content" ).append("<button type=\"button\" id=\"sendMessage\">Send message</button>");
			}
		});
		default:
		break;
	}
}

function showButton()
{
	$( ".title" ).append("<img class=\"icone\" id=\"convIcone\"src=\"ressources/selectConv.png\" alt=\"img select con\">");
		$( ".title" ).append("<img class=\"icone\" id=\"addConvIcone\"src=\"ressources/addConv.png\" alt=\"img add conv\">");
		$( ".title" ).append("<img class=\"icone\" id=\"logOutIcone\"src=\"ressources/logOut.png\" alt=\"img log out\">");
		$( ".title" ).append("<img class=\"icone\" id=\"closeIcone\"src=\"ressources/close.png\" alt=\"img close\">");
}