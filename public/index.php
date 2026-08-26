<?php
# 1 ) Contrôleur frontal

# 2) A - Lors du premier session_start, création d'un cookie de session local
# nommé par défaut PHPSESSID et création d'un fichier temporaire 
# côté serveur (ici dans le dossier local C:\wamp64\tmp commençant par ses_{clef secrète})
# Sécurisé : toutes les informations ne se trouve QUE côté serveur
# B - Si une session est en cours et est valide, on la continue
session_start();

# 3) que charge-t-on à cette ligne ?
require_once "../config.php";
# 4) que charge-t-on à ces lignes ?
require_once "../model/PostModel.php";# table post
require_once "../model/CategoryModel.php";# table category
require_once "../model/UserModel.php";# table user


# 5 ) Nous essayons delancer quelle type d'objet, et pourquoi?
try {
    $connectPDO = new PDO(
        DB_TYPE.':host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset='.DB_CHARSET,
        DB_LOGIN,
        DB_PWD
    );
        # 6 ) activation de quoi
        $connectPDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        # 7 ) En quoi voulons-nous que les résultats soient retournés par défaut ? 
        $connectPDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

    
# 8 ) le catch est appelé dans quel cas
}catch(Exception $e){
    # 9 ) et que fait-il?
    die($e->getMessage());

}


# Router

// ici sont redirigés les administrateurs connectés.
if(isset($_SESSION['myID'])&&$_SESSION['myID']==session_id()){
    require_once "../controller/privateController.php";
  
// zone publique, pour les visiteurs
}else{
    require_once "../controller/publicController.php";
}


# 10 )
$connectPDO = null;