<?php
/**
 * public Controller
 */


# 11 ) que récupère-t-on et de quel fichier provient cette fonction ?
$recupMenu = getAllCategoryMenu($connectPDO);

# 12 ) cette condition vérifie quoi ?
if (isset($_GET['postId'])&&ctype_digit($_GET['postId'])) {

    # 13 ) que fait-on à cette ligne ?
    $idpost = (int) $_GET['postId'];
    # 14 ) que récupère-t-on et de quel fichier provient cette fonction ?
    $recupPost = postOneById($connectPDO,$idpost);

    # 15 ) que reçoit'on en cas d'erreur, et que fait-on ensuite ?
    if(is_bool($recupPost)){
        // suite
        $error = "Cet article n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";
       
    // on a trouvé l'article    
    }else{

        // 16 ) qu'appelle-t-on à cette ligne.
        require_once('../view/publicView/detailView.php');
}

// 17 ) cette condition vérifie quoi ?
}elseif(isset($_GET['categoryId'])&&ctype_digit($_GET['categoryId'])){   
    
    $id = (int) $_GET['categoryId'];

    $recupcateg=recupCategoryById($connectPDO,$id);

    // 18 )si on récupère quel type de valeur, on fait quoi ?
    if(is_null($recupcateg)){
        // suite
        $error = "Cet catégorie n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";

    }else{
    # 19 ) que récupère-t-on et de quel fichier provient cette fonction ?    
        $recupAllPost = postByCategoryId($connectPDO, $id);

        # 20 ) que fait-on ici ?

        $nbPost = count($recupAllPost);

        # 21 ) que fait-on ici ?
        include_once("../view/publicView/publicCategorieView.php");
}

# 22) cette condition vérifie quoi ?
}elseif(isset($_GET['userId'])&&ctype_digit($_GET['userId'])){ 

    # 23 ) qu'essaye t'on de récupérer ici, et de quel fichier provient cette fonction ?
    $iduser = (int) $_GET['userId'];
    $user = getOneUserById($connectPDO,$iduser);

    # 24 ) si on récupère quel type de valeur, on fait quoi ?
    if(is_bool($user)){
        // suite
        $error = "Cet utilisateur n'existe plus";
        // suite 2
        include_once "../view/publicView/404View.php";
    }else{
        # 25 ) que récupère-t-on et de quel fichier provient cette fonction ?
        $recupAllPost = postByUserId($connectPDO,$iduser);

        # 26 ) que fait-on ici ?
        $nbPost = count($recupAllPost);

        # 27 ) que fait-on ici ?
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
        // # 28 ) que reçoit-on en cas d'erreur, et que fait-on ensuite ?
        if(is_string($connect)) {
            $message = $connect;
        // #29) sinon, que fait-on ?
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