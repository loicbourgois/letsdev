<?php
session_start();

	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/bdd.php"; 

	$data["connecte"] = valider("connecte","SESSION");
	$data["action"] = valider("action");

	if (!$data["action"])
	{
		// On ne doit rentrer dans le switch que si on y est autoris�
		$data["feedback"] = "Entrez connexion(login,passe) (eg 'tom','bou')";
	}
	else 
	{
		// si on a une action, on devrait avoir un message classique
		$data["feedback"] = "entrez action: logout, setCouleur(couleur),getConversations, getMessages(idConv,[idLastMessage]), setMessage(idConv,contenu), setConversation(theme), ...";
				
		// si pas connecte et action n'est pas connexion, on refuse
		if ( (!valider("idUser","SESSION")) && ($data["action"] != "connexion" ) ) {
			$data["feedback"] = "Entrez connexion(login,passe) (eg 'user','user')";
		}
		else {
			 
	
			switch($data["action"])
			{
		

				// Connexion //////////////////////////////////////////////////

				case 'connexion' :
					// On verifie la presence des champs login et passe
			

					if 	(
							!($login = valider("login")) 
						|| 	!($passe = valider("passe"))
						||	!($data["connecte"] = verifUser($login,$passe))
					)
					{
						// On verifie l'utilisateur, et on cr�e des variables de session si tout est OK
						$data["feedback"] = "Entrez login,passe (eg 'user<i>','passe<i>')";
					}
				break;

				case 'logout' :
					// On supprime juste la session 
					session_destroy();
					$data["feedback"] = "Entrez login,passe (eg 'user<i>','passe<i>')";
					$data["connecte"] = false;
				break;	

				// Utilisateurs //////////////////////////////////////////////////


				// Manque utilisateurs connectes ou non

				case 'getUsers' : 
					$data["users"] = listerUtilisateurs();
				break;

				case 'blacklist' : 
				// recuperer l'id de l'utilisateur choisi
				 
				if ($idUser = valider("idUser"))
				{
					interdireUtilisateur($idUser);
					// msg par d�faut
				}
				break;


				case 'unBlacklist' :
				if ($idUser = valider("idUser"))
				{	
					autoriserUtilisateur($idUser);
					// msg par d�faut
				}
				break;


				case 'delUser' :
				if ($idUser = valider("idUser"))
				{	
					supprimerUtilisateur($idUser);
					// msg par d�faut
				}
				break;

				case 'setUser' :
				if ($login = valider("login"))
				if ($passe = valider("passe"))
				{	
					$data["idUser"] = ajouterUtilisateur($login,$passe);
					// msg par d�faut
				}
				break;

				// Conversations //////////////////////////////////////////////////

				case 'getConversations' : 
					$data["conversations"] = listerConversations();
				break;

				case 'activer' :
				if ($idConv = valider("idConv"))
				{	
					reactiverConversation($idConv);
					// msg par d�faut
				}
				break;

				case 'archiver' :
				if ($idConv = valider("idConv"))
				{	
					archiverConversation($idConv);
					// msg par d�faut
				}
				break;

				case 'setConversation' :
				if ($theme = valider("theme"))
				{	
					$data["idConv"] = creerConversation($theme);	
					// msg par d�faut
				}
				break;

				case 'delConversation' :
				if ($idConv = valider("idConv"))
				{	
					supprimerConversation($idConv);	
					// msg par d�faut
				}
				break;

				case 'setMessage' :
				if ($idConv = valider("idConv"))
				if ($contenu = valider("contenu"))
				if ($idAuteur = valider("idUser","SESSION"))
				{	
					enregistrerMessage($idConv, $idAuteur, $contenu);
					// on ne renvoie pas tous les messages, car �a d�pend de la valeur du dernier connu par le client

					$idLastMessage = valider("idLastMessage"); // attention : cet argument peut valoir 0 !
					$messages = listerMessagesFromIndex($idConv,$idLastMessage);
					$data["messages"] = $messages; 

					if (count($messages) >0)
					{
						foreach($messages as $dataMessage)
						{
							$lastId = $dataMessage["id"];
						}
						$data["idLastMessage"] = $lastId;
					}
				}
				break;

				case 'delMessage' :
				if ($idMsg = valider("idMsg"))
				{	
					supprimerMessage($idMsg);
					// msg par d�faut
				}
				break;

				case 'getMessages' :
				if ($idConv = valider("idConv"))
				{	
					$idLastMessage = valider("idLastMessage"); 
					// attention : cet argument peut valoir 0 !
					$messages = listerMessagesFromIndex($idConv,$idLastMessage);
					$data["messages"] = $messages; 

					if (count($messages) >0)
					{
						foreach($messages as $dataMessage)
						{
							$lastId = $dataMessage["id"];
						}
						$data["idLastMessage"] = $lastId;
					}	
				}
				break;
		

				default : 				
					$data["action"] = "default";


			}

		}
	}

		
	 
	echo json_encode($data);
?>










