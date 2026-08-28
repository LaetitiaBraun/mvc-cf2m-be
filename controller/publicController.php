<?php
/**
 * public Controller
 */


# 11 ) La liste de toutes les catégories pour récupérer le menu, elle vient de CategoryModel.php.
$recupMenu = getAllCategoryMenu($connectPDO);

# 12 ) Si il existe la variable get nommée 'postId' et qu'il n'y a que des digit [0-9] dans la variable get (qui est un string par défaut).
if (isset($_GET['postId'])&&ctype_digit($_GET['postId'])) {

    # 13 ) On créer une variable locale nommée $idpost auquel on attribut la valeur de $_GET['postId'] qu'on convertit en entier.
    $idpost = (int) $_GET['postId'];
    # 14 ) On récupère un ou zéro article depuis postModel.php.
    $recupPost = postOneById($connectPDO,$idpost);

    # 15 ) On reçoit un booléen (false) on créer la variable d'erreur puis on charge la vue 404.
    if(is_bool($recupPost)){
        // suite
        $error = "Cet article n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";
       
    // on a trouvé l'article    
    }else{

        // 16 ) On importe la vue affichant l'article détaillé.
        require_once('../view/publicView/detailView.php');
}

// 17 ) Si il existe la variable get nommé 'postId' et qu'il n'y a que des digit [0-9] dans la variable get (qui est un string par défaut).
# get (qui est un string par deféfaut)
}elseif(isset($_GET['categoryId'])&&ctype_digit($_GET['categoryId'])){   
    
    $id = (int) $_GET['categoryId'];

    $recupcateg=recupCategoryById($connectPDO,$id);

    // 18 ) On reçoit un booléen (false) on créer la variable d'erreur puis on charge la vue 404.
    if(is_bool($recupcateg)){
        // suite
        $error = "Cet catégorie n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";

    }else{
    # 19 ) On récupère les post d'une catégorie depuis postModel.php.  
        $recupAllPost = postByCategoryId($connectPDO, $id);

        # 20 ) On compte le nombre de post récupéré.

        $nbPost = count($recupAllPost);

        # 21 ) On importe la vue de publicCategorieView.php.
        include_once("../view/publicView/publicCategorieView.php");
}

# 22) Si il existe la variable get nommée 'postId' et qu'il n'y a que des digit [0-9] dans la variable get (qui est un string par défaut).
}elseif(isset($_GET['userId'])&&ctype_digit($_GET['userId'])){ 

    $iduser = (int) $_GET['userId'];
    # 23 ) On essaie de récupérer l'utilisateur via son id (1 ou 0 utilisateur) depuis UserModel.php.
    $user = getOneUserById($connectPDO,$iduser);

    # 24 ) Si c'est un booléen l'utilisateur n'existe pas/plus, on charge la vue 404.
    if(is_bool($user)){
        // suite
        $error = "Cet utilisateur n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";
    }else{
        # 25 ) On créer la variable nommée $recupAllPost qui contient les posts écrit par cet utilisateur depuis PostModel.php.
        $recupAllPost = postByUserId($connectPDO,$iduser);

        # 26 ) On compte le nombre de posts récupérés.
        $nbPost = count($recupAllPost);

        # 27 ) On charge la vue de publicUserView.php.
        include_once "../view/publicView/publicUserView.php";
    }

// si on veut se connecter
}elseif(isset($_GET['connect'])){ 

    // si la personne a envoyé le formulaire
    if(isset($_POST['username'],$_POST['userpwd'])){
        // on essaye de connecter l'utilisateur
        $connect = connectUserByUsername($connectPDO,
                                $_POST['username'],
                                $_POST['userpwd']
                            );
        // # 28 ) On reçoit un string (une chîne de caractère) avec le message d'erreur, on met cette erreur dans la variable $message.
        if(is_string($connect)) {
            $message = $connect;
        // #29) Redirection vers l'accueil ou à la racine, l'exit quitte le script (pour évitrt un bug sur certains serveurs).
        }else{
            header("Location: ./");
            exit();
        }
    }

    # 30 ) que fait-on ici ?
    include "../view/publicView/connectView.php";

# 31 ) sinon, où sommes nous ?
}else{
    # homepage's datas from MODEL
    $recupAllPost = postHomepageAll($connectPDO);

    # Post count
    $nbPost = count($recupAllPost);


    # homepage's view from VIEW
    require "../view/publicView/publicHomepageView.php";
}