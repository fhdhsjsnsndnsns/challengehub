<?php
declare(strict_types=1);
require_once __DIR__."/BaseController.php";
require_once APP_PATH."/models/User.php";
require_once APP_PATH."/models/Submission.php";
class UserController extends BaseController {
    private User $userModel;
    public function __construct(){parent::__construct();$this->userModel=new User();}
    public function login(): void {
        if($this->isLoggedIn()){$this->redirect("home");return;}
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $this->verifyCsrfToken();
            $email=trim($_POST["email"]??"");$password=$_POST["password"]??"";$errors=[];
            if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]="Email invalide.";
            if(empty($password))$errors[]="Mot de passe requis.";
            if(empty($errors)){
                $user=$this->userModel->findByEmail($email);
                if($user&&password_verify($password,$user["password"])){
                    session_regenerate_id(true);
                    $_SESSION["user_id"]=$user["id"];$_SESSION["user_name"]=$user["name"];
                    $this->flash("success","Bienvenue, ".$user["name"]." !");
                    $this->redirect("home");return;
                }else{$errors[]="Email ou mot de passe incorrect.";}
            }
            $this->render("user/login",["pageTitle"=>"Connexion","errors"=>$errors,"csrf"=>$this->generateCsrfToken()]);return;
        }
        $this->render("user/login",["pageTitle"=>"Connexion","errors"=>[],"csrf"=>$this->generateCsrfToken()]);
    }
    public function register(): void {
        if($this->isLoggedIn()){$this->redirect("home");return;}
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $this->verifyCsrfToken();
            $name=trim($_POST["name"]??"");$email=trim($_POST["email"]??"");$password=$_POST["password"]??"";$confirm=$_POST["confirm"]??"";$errors=[];
            if(strlen($name)<2)$errors[]="Le nom doit contenir au moins 2 caractères.";
            if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]="Email invalide.";
            if(strlen($password)<8)$errors[]="Mot de passe trop court (min 8 caractères).";
            if($password!==$confirm)$errors[]="Les mots de passe ne correspondent pas.";
            if(empty($errors)&&$this->userModel->emailExists($email))$errors[]="Cet email est déjà utilisé.";
            if(empty($errors)){$this->userModel->register($name,$email,$password);$this->flash("success","Compte créé ! Connectez-vous.");$this->redirect("user","login");return;}
            $this->render("user/register",["pageTitle"=>"S'inscrire","errors"=>$errors,"old"=>$_POST,"csrf"=>$this->generateCsrfToken()]);return;
        }
        $this->render("user/register",["pageTitle"=>"S'inscrire","errors"=>[],"old"=>[],"csrf"=>$this->generateCsrfToken()]);
    }
    public function logout(): void { session_destroy();header("Location: ".APP_URL);exit; }
    public function profile(): void {
        $this->requireLogin();
        $user=$this->userModel->findById($this->getCurrentUserId());
        $submissions=(new Submission())->findByUser($this->getCurrentUserId());
        $this->render("user/profile",["pageTitle"=>"Mon profil","user"=>$user,"submissions"=>$submissions,"csrf"=>$this->generateCsrfToken()]);
    }
    public function edit(): void {
        $this->requireLogin();$userId=$this->getCurrentUserId();$user=$this->userModel->findById($userId);
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $this->verifyCsrfToken();
            $name=trim($_POST["name"]??"");$email=trim($_POST["email"]??"");$errors=[];
            if(strlen($name)<2)$errors[]="Nom trop court.";
            if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]="Email invalide.";
            if($this->userModel->emailExists($email,$userId))$errors[]="Email déjà utilisé.";
            $avatar=null;
            if(!empty($_FILES["avatar"]["tmp_name"])){$avatar=$this->uploadImage($_FILES["avatar"],"avatar");if(!$avatar)$errors[]="Image invalide.";}
            $newPwd=$_POST["new_password"]??"";$oldPwd=$_POST["old_password"]??"";
            if(!empty($newPwd)){if(!password_verify($oldPwd,$user["password"]))$errors[]="Ancien mot de passe incorrect.";if(strlen($newPwd)<8)$errors[]="Nouveau mot de passe trop court.";}
            if(empty($errors)){$this->userModel->update($userId,$name,$email,$avatar);if(!empty($newPwd))$this->userModel->updatePassword($userId,$newPwd);$_SESSION["user_name"]=$name;$this->flash("success","Profil mis à jour.");$this->redirect("user","profile");return;}
            $this->render("user/edit",["pageTitle"=>"Modifier mon profil","user"=>$user,"errors"=>$errors,"csrf"=>$this->generateCsrfToken()]);return;
        }
        $this->render("user/edit",["pageTitle"=>"Modifier mon profil","user"=>$user,"errors"=>[],"csrf"=>$this->generateCsrfToken()]);
    }
    public function delete(): void {
        $this->requireLogin();
        if($_SERVER["REQUEST_METHOD"]==="POST"){$this->verifyCsrfToken();$this->userModel->delete($this->getCurrentUserId());session_destroy();header("Location: ".APP_URL);exit;}
        $this->redirect("user","profile");
    }
}
