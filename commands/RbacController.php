<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use \app\rbac\UserGroupRule;
use \app\models\User;

class RbacController extends Controller
{
    public $adminUsername = 'admin';


    public $roles = [
        'guest'=> [
            'description'=>'Guest Role',
        ],
        'Admin'=> [
            'description'=>'Admin Role',
        ],
        'Organizer'=> [
            'description'=>'Organizer Role',
        ],
    ];

    public $permissions = [
        'default_permission' => 'default permissions for all users',
    ];


    public $routes = [
        '/site/login',
        '/site/logout',
        '/site/error',
        '/site/contact',
        '/site/about',
        '/site/*',
        '/vote/*',
        '/debug/*',
        '/gii/*',
        '/admin/*',
        '/user/*',

        '/poll/*',
        '/poll/create',
        '/poll/update',
        '/poll/delete',
        '/poll/view',
        '/poll/index',

        '/member/*',
        '/member/create',
        '/member/update',
        '/member/delete',
        '/member/view',
        '/member/index',
        '/member/import',
        '/member/clear',
        //'/member/email',


        '/code/*',
        '/code/create',
        '/code/update',
        '/code/delete',
        '/code/view',
        '/code/index',
        '/code/invalidate',

        '/organizer/*',
        '/organizer/create',
        '/organizer/update',
        '/organizer/delete',
        '/organizer/view',
        '/organizer/index',

        '/emails/send',
    ];


    public function actionInit()
    {
        $authManager = \Yii::$app->authManager;




        $userGroupRule = new UserGroupRule();
        $authManager->add($userGroupRule);

        //$guest = $authManager->createRole('guest');
        //$guest->ruleName = $userGroupRule->name;

        // Add roles in Yii::$app->authManager
        //$authManager->add($guest);


        $index = $authManager->createPermission('index');

        if ($this->roles && is_array($this->roles)) {
            foreach ($this->roles as $roleName => $roleSettings) {
                $role = $authManager->createRole($roleName);
                $role->description = !empty($roleSettings['description']) ? $roleSettings['description'] : '';

                // Add rule "UserGroupRule" in roles
                $role->ruleName = $userGroupRule->name;
                //$role->ruleName = !empty($roleSettings['ruleName']) ? $roleSettings['ruleName'] : '';

                $authManager->add($role);
            }
        }


        if ($this->permissions && is_array($this->permissions)) {
            foreach ($this->permissions as $permissionName => $permissionDescription) {
                $permission = $authManager->createPermission($permissionName);
                $permission->description = $permissionDescription;
                $authManager->add($permission);
            }
        }

        // Add permissions in Yii::$app->authManager
        //$authManager->add($default_permission);


        if ($this->routes && is_array($this->routes)) {
            foreach ($this->routes as $routeName) {
                if ($routeName[0] == '/') {
                    $route = $authManager->createPermission($routeName);
                    $authManager->add($route);
                }
            }
        }

        // assignments
        $guest=$authManager->getRole('guest');

        $admin=$authManager->getRole('Admin');
        $Organizer=$authManager->getRole('Organizer');



        $default_permission=$authManager->getPermission('default_permission');

        $login=$authManager->getPermission('/site/login');
        $logout=$authManager->getPermission('/site/logout');
        $contact=$authManager->getPermission('/site/contact');
        $about=$authManager->getPermission('/site/about');
        $vote_route=$authManager->getPermission('/vote/*');
        $user_route=$authManager->getPermission('/user/*');
        $gii_route=$authManager->getPermission('/gii/*');
        $admin_route=$authManager->getPermission('/admin/*');
        $debug_route=$authManager->getPermission('/debug/*');
        $organizer_route=$authManager->getPermission('/organizer/*');


        // poll permissions
        $poll_route=$authManager->getPermission('/poll/*');
        $poll_index=$authManager->getPermission('/poll/index');
        $poll_view=$authManager->getPermission('/poll/view');
        $poll_update=$authManager->getPermission('/poll/update');
        $poll_delete=$authManager->getPermission('/poll/delete');
        $poll_create=$authManager->getPermission('/poll/create');


        // member permissions
        $member_route=$authManager->getPermission('/member/*');
        $member_index=$authManager->getPermission('/member/index');
        $member_view=$authManager->getPermission('/member/view');
        $member_update=$authManager->getPermission('/member/update');
        $member_delete=$authManager->getPermission('/member/delete');
        $member_create=$authManager->getPermission('/member/create');
        $member_import=$authManager->getPermission('/member/import');
        $member_clear=$authManager->getPermission('/member/clear');
        //$member_email=$authManager->getPermission('/member/email');

        $code_invalidate=$authManager->getPermission('/code/invalidate');
        $code_create=$authManager->getPermission('/code/create');

        $emails_send=$authManager->getPermission('/emails/send');

        // guest
        echo "adding guest permissions\n";
        $authManager->addChild($guest, $login);
        $authManager->addChild($guest, $contact);
        $authManager->addChild($guest, $about);
        $authManager->addChild($guest, $vote_route);

        //default_permission permissions
        echo "adding default_permission permissions\n";
        $authManager->addChild($default_permission, $logout);
        $authManager->addChild($default_permission, $contact);
        $authManager->addChild($default_permission, $about);
        $authManager->addChild($default_permission, $vote_route);


        // Organizer Role
        $authManager->addChild($Organizer, $default_permission);

        // poll actions
        $authManager->addChild($Organizer, $poll_create);
        $authManager->addChild($Organizer, $poll_update);
        $authManager->addChild($Organizer, $poll_delete);
        $authManager->addChild($Organizer, $poll_view);
        $authManager->addChild($Organizer, $poll_index);

        // member actions
        $authManager->addChild($Organizer, $member_create);
        $authManager->addChild($Organizer, $member_update);
        $authManager->addChild($Organizer, $member_delete);
        $authManager->addChild($Organizer, $member_view);
        $authManager->addChild($Organizer, $member_index);
        $authManager->addChild($Organizer, $member_import);
        $authManager->addChild($Organizer, $member_clear);
        //$authManager->addChild($Organizer, $member_email);

        // send member emails
        $authManager->addChild($Organizer, $emails_send);

        // code actions
        $authManager->addChild($Organizer, $code_invalidate);
        $authManager->addChild($Organizer, $code_create);


        // admin
        echo "adding admin permissions\n";
        $authManager->addChild($admin, $default_permission);
        $authManager->addChild($admin, $Organizer);
        $authManager->addChild($admin, $gii_route);
        $authManager->addChild($admin, $debug_route);
        $authManager->addChild($admin, $admin_route);
        $authManager->addChild($admin, $user_route);
        $authManager->addChild($admin, $organizer_route);




        // assign the "AdminUser" the AdminRole
        $admin_user = User::findByUsername($this->adminUsername);
        if ($admin_user) {
            $authManager->assign($admin, $admin_user->id);
        }

        /*
        // Create roles
        $guest = $authManager->createRole('guest');
        // $brand = $authManager->createRole('BRAND');
        // $talent = $authManager->createRole('TALENT');

        $admin = $authManager->createRole('admin');
        // Create simple, based on action{$NAME} permissions
        $login = $authManager->createPermission('login');
        $login->description = 'Allows Guest Users to login';

        $logout = $authManager->createPermission('logout');
        $logout->description = 'Allows Users to logout';

        $error = $authManager->createPermission('error');
        $error->description = 'Allows Everyone to display Errors';

        $default_permission = $authManager->createPermission('default_permission');
        $default_permission->description = 'default_permissions for all users';



        //$signUp = $authManager->createPermission('sign-up');
        //$signUp->description = 'Allows Everyone to use the Sign-Up page';

        $index = $authManager->createPermission('index');
        $view = $authManager->createPermission('view');
        $update = $authManager->createPermission('update');
        $delete = $authManager->createPermission('delete');



        //$testroute = $authManager->createRoute('testroute');


        // Add permissions in Yii::$app->authManager
        $authManager->add($login);
        $authManager->add($logout);
        $authManager->add($error);
        $authManager->add($signUp);
        $authManager->add($index);
        $authManager->add($view);
        $authManager->add($update);
        $authManager->add($delete);
        $authManager->add($default_permission);

        // Add rule, based on UserExt->group === $user->group
        $userGroupRule = new UserGroupRule();
        $authManager->add($userGroupRule);

        // Add rule "UserGroupRule" in roles
        $guest->ruleName = $userGroupRule->name;
        //$brand->ruleName = $userGroupRule->name;
        //$talent->ruleName = $userGroupRule->name;
        $admin->ruleName = $userGroupRule->name;

        // Add roles in Yii::$app->authManager
        $authManager->add($guest);
        // $authManager->add($brand);
        // $authManager->add($talent);
        $authManager->add($admin);

        // Add permission-per-role in Yii::$app->authManager
        // Guest
        // $authManager->addChild($guest, $login);
        // $authManager->addChild($guest, $logout);
        // $authManager->addChild($guest, $error);
        // $authManager->addChild($guest, $signUp);
        // $authManager->addChild($guest, $index);
        // $authManager->addChild($guest, $view);

        // // BRAND
        // $authManager->addChild($brand, $update);
        // $authManager->addChild($brand, $guest);

        // // TALENT
        // $authManager->addChild($talent, $update);
        // $authManager->addChild($talent, $guest);


        // Admin
        $authManager->addChild($admin, $delete);
        // $authManager->addChild($admin, $talent);
        // $authManager->addChild($admin, $brand);

        */
    }

    public function actionClear()
    {
        $authManager = \Yii::$app->authManager;

        $userGroupRule = new UserGroupRule();
        $userGroupRuleItem = $authManager->getRule($userGroupRule->name);
        if ($userGroupRuleItem) {
            $authManager->remove($userGroupRuleItem);
        }

        if ($this->roles && is_array($this->roles)) {
            foreach ($this->roles as $roleName => $roleSettings) {
                $role=$authManager->getRole($roleName);
                if ($role) {
                    $authManager->remove($role);
                }
            }
        }

        if ($this->permissions && is_array($this->permissions)) {
            foreach ($this->permissions as $permissionName => $permissionDescription) {
                $permission = $authManager->getPermission($permissionName);
                if ($permission) {
                    $authManager->remove($permission);
                }
            }
        }

        if ($this->routes && is_array($this->routes)) {
            foreach ($this->routes as $routeName) {
                if ($routeName[0] == '/') {
                    $route = $authManager->getPermission($routeName);
                    if ($route) {
                        $authManager->remove($route);
                    }
                }
            }
        }


    }
}

