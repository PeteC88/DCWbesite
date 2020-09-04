<?php
use App\front_office\Controllers\HomeController;
use App\front_office\Controllers\ImageController;
use App\front_office\Controllers\CommentController;
use App\front_office\Controllers\PostController as PostControllerAlias;
use App\front_office\Controllers\ServicesController;
use App\front_office\Controllers\SignInController;
use App\front_office\Controllers\ProjectController;
use App\front_office\Controllers\RecoveryPasswordController;
use App\front_office\Controllers\ContactController;
use App\front_office\Controllers\PageNotFoundController;
use App\front_office\Controllers\AboutController;
use App\back_office\Controllers\AdminImageController;
use App\back_office\Controllers\AdminPostController;
use App\back_office\Controllers\AdminServicesController;
use App\back_office\Controllers\AdminProjectsController;
use App\back_office\Controllers\AdminCommentController;
use App\back_office\Controllers\AdminUserController;
use App\back_office\Controllers\AdminHomeController;
use App\back_office\Controllers\UploadController;

CONST TEMPLATE_PATH = '../view/theme/';

require_once '../vendor/autoload.php';
require '../src/config.php';

if(isset($_GET['route']))
{
    try {
        switch ($_GET['route']) {
            //Home Front Office
            case '':
            case 'home':
                /** @var TYPE_NAME $HomeController */
                $HomeController = new HomeController();
                $HomeController->HomeDisplay();
                break;

            //Home Back Office
            case 'adminHome':
                $AdminHomeController = new AdminHomeController();
                $AdminHomeController->AdminHomeDisplay();
                break;

            //Front Office Blog
            case 'blog':
                {
                    if(isset($_GET['page']) && !empty($_GET['page'])){
                        $currentPage = (int) strip_tags($_GET['page']);
                    }else{
                        $currentPage = 1;
                    }
                    $PostController = new PostControllerAlias();
                    $PostController->listPosts($currentPage);
                }
                break;

            case 'post':
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $PostController = new PostControllerAlias();
                    $PostController->post($_GET['id']);
                } else {
                    throw new Exception('Non esiste un post con questo id');
                }
                break;

            case 'addComment':
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $CommentController = new CommentController();
                    $CommentController->addComment($_GET['id'], $_POST['postTitle'], $_POST['author'], $_POST['comment'], $_POST['email']);
                } else {
                    throw new Exception('Non esiste un post con questo id');
                }
                break;

            //Blog Back Office
            case 'adminBlog':
                if(isset($_GET['page']) && !empty($_GET['page'])){
                    $currentPage = (int) strip_tags($_GET['page']);
                }else{
                    $currentPage = 1;
                }
                $AdminPostController = new AdminPostController();
                $AdminPostController->listAll($currentPage);
                break;

            case 'adminPost':
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $AdminPostController = new AdminPostController();
                    $AdminPostController->adminPost($_GET['id']);
                } else {
                    throw new Exception('Non è stato inviato nessun id del post torna indietro');
                }
                break;

            case 'addPostForm':
                $AdminPostController = new AdminPostController();
                $AdminPostController->addPostShowFormAction();
                break;

            case 'adminAddPost':
                if (!empty($_POST['title']) && !empty($_POST['content']))
                {
                    $AdminPostController = new AdminPostController();
                    $AdminPostController->addPostAction(htmlspecialchars($_POST['title']), $_POST['content']);
                }
                break;

            case 'editPostShowForm':
                if (isset($_GET['id']) && $_GET['id'] > 0)
                {
                    $AdminPostController = new AdminPostController();
                    $AdminPostController->editPostShowForm($_GET['id']);
                }
                else
                {
                    echo 'Non esiste un progetto con questo id';
                }
                break;

            case 'adminEditPost':
                $AdminPostController = new AdminPostController();
                $AdminPostController->editPostAction($_POST['id'], $_POST['id'], $_POST['title'], $_POST['content']);
                break;

            case 'deletePost':
                $AdminPostController = new AdminPostController();
                $AdminPostController->removePostAction($_GET['id']);
                break;

            case 'adminRemoveComment':
                $AdminCommentController = new AdminCommentController();
                $AdminCommentController->removeCommentAction(intval($_GET['id']), $_POST['postId']); //intval to verify that is a int
                break;

            case 'approveComment':
                $AdminCommentController = new AdminCommentController();
                $AdminCommentController->approveComment($_POST['commentId'], $_POST['postId']);
                break;

            //Services Front Office
            case 'services':
                $ServicesController = new ServicesController();
                $ServicesController->listServicesAction();
                break;

            //Services Back Office
            case 'AdminServices':
                $AdminServicesController = new AdminServicesController();
                $AdminServicesController->adminServices();
                break;

            case 'addServiceForm':
                $AdminServicesController = new AdminServicesController();
                $AdminServicesController->addServiceShowFormAction();
                break;

            case 'addService':
                $AdminServicesController = new AdminServicesController();
                $AdminServicesController->AddServiceAction(htmlspecialchars($_POST['title']), htmlspecialchars($_POST['content']), $_FILES['image']['name']);
                break;

            case 'editServiceShowForm':
                if (isset($_GET['id']) && $_GET['id'] > 0)
                {
                    $AdminServicesController = new AdminServicesController();
                    $AdminServicesController->editServiceShowForm($_GET['id']);
                }
                else
                {
                    echo 'Non esiste un servizio con questo id';
                }

                break;

            case 'editService':
                $AdminServicesController = new AdminServicesController();
                $AdminServicesController->editServiceAction($_POST['title'], $_POST['content'], $_FILES['image']['name'], $_POST['id']);
                break;

            case 'deleteService':
                $AdminServicesController = new AdminServicesController();
                $AdminServicesController->deleteServiceAction($_GET['id']);
                break;


            //User Front Office
            case 'connection':
                $SignInController = new SignInController();
                $SignInController->connectForm();
                break;


            //User Back Office
            case 'logout':
                $adminUserController = new AdminUserController();
                $adminUserController->signOut();
                break;

            case 'userConnect':
                $SignInController = new SignInController();
                $SignInController->signIn($_POST['email'], $_POST['password']);
                break;

            case 'adminChangePassword':
                if(empty($_POST))
                {   $AdminUserController = new AdminUserController();
                    $AdminUserController->changePasswordForm();

                }
                else
                {
                    $AdminUserController = new AdminUserController();
                    $AdminUserController->changePasswordAction($_SESSION['user']['email'], $_POST['password'], $_POST['new_pass'], $_POST['re_pass']);
                }
                break;

            case 'recoveryPasswordForm':
                $RecoveryPasswordController = new RecoveryPasswordController();
                $RecoveryPasswordController->RecoveryPasswordForm();

            case 'recoveryPassword':
                {
                    $RecoveryPasswordController = new RecoveryPasswordController();
                    $RecoveryPasswordController->SendMailAction($_POST['email']);
                }
                break;

            case 'resetPassword':
                {
                    $RecoveryPasswordController = new RecoveryPasswordController();
                    $RecoveryPasswordController->ResetPasswordAction($_POST['new_pass'], $_POST['re_pass']);
                }
                break;
            case 'resetPasswordForm':
                {
                    $RecoveryPasswordController = new RecoveryPasswordController();
                    $RecoveryPasswordController->ResetPasswordForm($_GET['reset_token']);
                }
                break;

            //About Front Office
            case 'about':
                $AboutController = new AboutController();
                $AboutController->showAboutPage();
                break;

            //Contact Front Office
            case 'contact':
                $ContactController = new ContactController();
                $ContactController->showContactPage();
                break;

            case 'sendMail':
                $ContactController = new ContactController();
                $ContactController->sendMail($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone'], $_POST['object'], $_POST['content'],);
                break;


            //Project Front Office
            case 'projects':
                $ProjectController = new ProjectController();
                $ProjectController->listProjects();
                break;
            case 'showSingleProject':
                $ProjectController = new ProjectController();
                $ProjectController->showProject($_GET['id']);
                break;

            //Projects Back Office
            case 'adminProjects':
                $AdminProjectsController = new AdminProjectsController();
                $AdminProjectsController->adminListProjects();
                break;

            case 'adminAddProjectShowForm':
                $AdminProjectsController = new AdminProjectsController();
                $AdminProjectsController->addProjectShowFormAction();
                break;

            case 'adminAddProject':
                $AdminProjectsController = new AdminProjectsController();
                $AdminProjectsController->AddProjectAction(htmlspecialchars($_POST['title']), htmlspecialchars($_POST['content']), $_FILES['image']['name'], htmlspecialchars($_POST['city']));
                break;

            case 'editProjectShowForm':
                if (isset($_GET['id']) && $_GET['id'] > 0)
                {
                    $AdminProjectsController = new AdminProjectsController();
                    $AdminProjectsController->editProjectShowForm($_GET['id']);
                }
                else
                {
                    echo 'Non esiste un progetto con questo id';
                }
                break;

            case 'editProjectAction':
                $AdminProjectsController = new AdminProjectsController();
                $AdminProjectsController->editProjectAction($_POST['title'], $_POST['content'], $_POST['city'], $_POST['id']);
                break;

            case 'editProjectThumbnail':
                $AdminProjectsController = new AdminProjectsController();
                $AdminProjectsController->editProjectThumbnail($_FILES['thumbnail']['name'], $_POST['id']);
                break;

            case 'deleteProject':
                $AdminProjectsController = new AdminProjectsController();
                $AdminProjectsController->deleteProjectAction($_GET['id']);
                break;

            //Upload images to TinyMce textarea
            case 'upload':
                $UploadController = new UploadController();
                $UploadController->imageUpload();
                break;

            default:
                throw new \Exception('Pagina non trovata');

        }
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
        $PageNotFoundController = new PageNotFoundController();
        $PageNotFoundController->show404($errorMessage);
    }
}

