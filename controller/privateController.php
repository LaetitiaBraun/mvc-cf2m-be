<?php

# 32 ) que fait-on ici ?
if (isset($_GET['disconnect'])) {
    // si déconnexion renvoie true
    if (deconnect()) {
        // redirection
        header("Location: ./");
        exit();
    }

// 33 ) à quoi pourrait servir ce bloc de code ?   
}elseif(isset($_GET['postVisible'],$_GET['id'])
    &&ctype_digit($_GET['postVisible'])
    &&ctype_digit($_GET['id'])
    ){
    $postId = (int) $_GET['id'];
    $postVisible = (int) $_GET['postVisible'];

    // 34 ) que fait-on ici ?
    if (postAdminUpdateVisible($connectPDO, $postId, $postVisible)) {
        header("Location: ./?m=L'article dont l'id est $postId a été modifié");
        exit();
    } else {
        header("Location: ./?m=Problème lors de la modification de l'article!");
        exit();
    }

// 34 ) que veut on faire ici ?   
}elseif(isset($_GET['createPost'])){

    // 35) si on a envoyé ... quoi ?
    if(isset($_POST['title'],$_POST['content'],$_POST['user_id'])){
        $UserId = (int) $_POST['user_id']; // si erreur => 0
        // 36 ) que fait-on ici ?
        $postTitle = htmlspecialchars(strip_tags(trim($_POST['title'])),ENT_QUOTES);
        $postContent = htmlspecialchars(strip_tags(trim($_POST['content'])),ENT_QUOTES);
        // ternaire ! si tableau les valeurs et clefs ne sont pas protégée contre une manipulation externe (injection etc...)
        $idCateg = (isset($_POST['category_id'])&&is_array($_POST['category_id']))? $_POST['category_id'] : [];

    if(!empty($UserId)&&!empty($postTitle)&&!empty($postContent)) {
        //  Pouvoir insérer un article AVEC ses catégories
        $insert = postAdminInsert($connectPDO, $UserId, $postTitle, $postContent, $idCateg);
        if($insert===true){
            $message = "Article inséré dans la DB";
        }
    }
    }

    // 37 )Appel des catégories pour .
    $categoryChoice = getAllCategoryMenu($connectPDO);

    // 38 ) On appel qui?
    $userChoice = getAllUsers($connectPDO);

    // 39) que fait-on ici ?
    include "../view/privateView/privateInsertView.php";

// 40 ) que fait-on ici ?  
}elseif(isset($_GET['updatePost'])&&ctype_digit($_GET['updatePost'])){

    // si on a envoyé le formulaire de modification
    if(isset($_POST['title'])){
        // pas de vérification des variables $_POST au niveau du contrôleur !!! -> TOUTES LES Vérification doivent se trouver dans la fonction ! 
        $post = postAdminUpdate($connectPDO,$_POST); 
        // 41 ) quel type de retour pour avoir une erreur
        if(is_string($post)){
            // affichage de l'erreur
            $message = $post;
        }
        // 42 ) quel type de retour pour avoir un succès
        if($post===true){
            $message = "L'article a bien été modifié<script>
            setTimeout(\"location.href = './';\", 2000);
             </script>";
        }
    }

    $idUpdatePost = (int) $_GET['updatePost'];

    # 43 ) que récupère t'on
    $recupPost = postOneById($connectPDO,$idUpdatePost);

    # 44 ) que type de valeur peut-on récupérer ici, et que fait-on ensuite ?
    if(is_bool($recupPost)){
        # récupération du menu pour l'erreur 404
        $recupMenu = getAllCategoryMenu($connectPDO);
        // création de l'erreur pour la 404
        $error = "Cet article n'existe plus";
        // appel de la vue 404
        include_once "../view/publicView/404View.php";
       
    // on a trouvé l'article    
    }else{

    // 45) on appel les ...
    $categoryChoice = getAllCategoryMenu($connectPDO);

    // 46 ) on appel les ...
    $userChoice = getAllUsers($connectPDO);

    // 47 ) on appel la ...
    include "../view/privateView/privateUpdateView.php";
}

// 48 ) que fait-on ici ? 
}elseif(isset($_GET['deletePost'])&&ctype_digit($_GET['deletePost'])){

    $postId = (int) $_GET['deletePost'];

    if(postAdminDeleteById($connectPDO,$postId)){
        header("Location: ./?m=L'article dont l'id est $postId a été supprimé");
        exit();
    }else{
        header("Location: ./?m=Problème lors de la modification de l'article!");
        exit();
    }

    
// 49) quel est cette page  
}else{
    // appel due la méthode (fonction) modèle PostModel pour afficher tous les articles SANS restrictions
    $postAll = postAdminHomepageAll($connectPDO);
    // on compte le nombre d'articles
    $postCount = count($postAll);
    // appel de la vue de l'accueil
    include "../view/privateView/privateHomepageView.php";
}