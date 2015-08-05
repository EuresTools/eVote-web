<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use \app\rbac\UserGroupRule;
use \app\rbac\OwnerRule;
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
        // 'updatePollOwned',
        // 'updatePoll'
    ];


    public $routes = [

        '/datecontrol/*',
        '/gridview/*',

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

        '/email/*',
        '/email/sendmultiple',
        '/email/sendsingle',
    ];


    public function actionInit()
    {
        $auth = \Yii::$app->authManager;




        $userGroupRule = new UserGroupRule();
        $auth->add($userGroupRule);

        // $ownerRule = new OwnerRule();
        // $auth->add($ownerRule);


        //$guest = $auth->createRole('guest');
        //$guest->ruleName = $userGroupRule->name;

        // Add roles in Yii::$app->authManager
        //$auth->add($guest);


        $index = $auth->createPermission('index');

        if ($this->roles && is_array($this->roles)) {
            foreach ($this->roles as $roleName => $roleSettings) {
                $role = $auth->createRole($roleName);
                $role->description = !empty($roleSettings['description']) ? $roleSettings['description'] : '';

                // Add rule "UserGroupRule" in roles
                $role->ruleName = $userGroupRule->name;
                //$role->ruleName = !empty($roleSettings['ruleName']) ? $roleSettings['ruleName'] : '';

                $auth->add($role);
            }
        }


        if ($this->permissions && is_array($this->permissions)) {
            foreach ($this->permissions as $permissionName => $permissionDescription) {
                $permission = $auth->createPermission($permissionName);
                $permission->description = $permissionDescription;
                $auth->add($permission);
            }
        }

        // Add permissions in Yii::$app->authManager
        //$auth->add($default_permission);


        if ($this->routes && is_array($this->routes)) {
            foreach ($this->routes as $routeName) {
                if ($routeName[0] == '/') {
                    $route = $auth->createPermission($routeName);
                    $auth->add($route);
                }
            }
        }

        // assignments
        $guest=$auth->getRole('guest');

        $admin=$auth->getRole('Admin');
        $Organizer=$auth->getRole('Organizer');



        $default_permission=$auth->getPermission('default_permission');

        $datecontrol=$auth->getPermission('/datecontrol/*');
        $gridview=$auth->getPermission('/gridview/*');

        $login=$auth->getPermission('/site/login');
        $logout=$auth->getPermission('/site/logout');
        $contact=$auth->getPermission('/site/contact');
        $about=$auth->getPermission('/site/about');
        $vote_route=$auth->getPermission('/vote/*');
        $user_route=$auth->getPermission('/user/*');
        $gii_route=$auth->getPermission('/gii/*');
        $admin_route=$auth->getPermission('/admin/*');
        $debug_route=$auth->getPermission('/debug/*');
        $organizer_route=$auth->getPermission('/organizer/*');


        // poll permissions
        $poll_route=$auth->getPermission('/poll/*');
        $poll_index=$auth->getPermission('/poll/index');
        $poll_view=$auth->getPermission('/poll/view');
        $poll_update=$auth->getPermission('/poll/update');
        $poll_delete=$auth->getPermission('/poll/delete');
        $poll_create=$auth->getPermission('/poll/create');


        // // update only owned test start
        // $updatePoll = $auth->createPermission('updatePoll');
        // $updatePoll->description = 'Update poll';
        // $auth->add($updatePoll);
        // $auth->addChild($updatePoll, $poll_update);

        // // add the "updateOwnPoll" permission and associate the rule with it.
        // $updatePollOwned = $auth->createPermission('updatePollOwned');
        // $updatePollOwned->description = 'Update own poll';
        // $updatePollOwned->ruleName = $ownerRule->name;
        // $auth->add($updatePollOwned);

        // // "updateOwnPoll" will be used from "updatePoll"
        // $auth->addChild($updatePollOwned, $updatePoll);
        // $auth->addChild($updatePollOwned, $poll_update);

        // update only owned test end

        // member permissions
        $member_route=$auth->getPermission('/member/*');
        $member_index=$auth->getPermission('/member/index');
        $member_view=$auth->getPermission('/member/view');
        $member_update=$auth->getPermission('/member/update');
        $member_delete=$auth->getPermission('/member/delete');
        $member_create=$auth->getPermission('/member/create');
        $member_import=$auth->getPermission('/member/import');
        $member_clear=$auth->getPermission('/member/clear');
        //$member_email=$auth->getPermission('/member/email');

        $code_invalidate=$auth->getPermission('/code/invalidate');
        $code_create=$auth->getPermission('/code/create');

        $emails_route=$auth->getPermission('/email/*');
        $emails_sendmultiple=$auth->getPermission('/email/sendmultiple');
        $emails_sendsingle=$auth->getPermission('/email/sendsingle');

        // guest
        echo "adding guest permissions\n";
        $auth->addChild($guest, $login);
        $auth->addChild($guest, $contact);
        $auth->addChild($guest, $about);
        $auth->addChild($guest, $vote_route);

        //default_permission permissions
        echo "adding default_permission permissions\n";
        $auth->addChild($default_permission, $logout);
        $auth->addChild($default_permission, $contact);
        $auth->addChild($default_permission, $about);
        $auth->addChild($default_permission, $vote_route);

        $auth->addChild($default_permission, $datecontrol);
        $auth->addChild($default_permission, $gridview);


        // Organizer Role
        $auth->addChild($Organizer, $default_permission);

        // poll actions
        $auth->addChild($Organizer, $poll_create);
        $auth->addChild($Organizer, $poll_update);
        $auth->addChild($Organizer, $poll_delete);
        $auth->addChild($Organizer, $poll_view);
        $auth->addChild($Organizer, $poll_index);

        // member actions
        $auth->addChild($Organizer, $member_create);
        $auth->addChild($Organizer, $member_update);
        $auth->addChild($Organizer, $member_delete);
        $auth->addChild($Organizer, $member_view);
        $auth->addChild($Organizer, $member_index);
        $auth->addChild($Organizer, $member_import);
        $auth->addChild($Organizer, $member_clear);
        //$auth->addChild($Organizer, $member_email);

        // send member emails
        $auth->addChild($Organizer, $emails_sendmultiple);
        $auth->addChild($Organizer, $emails_sendsingle);

        // code actions
        $auth->addChild($Organizer, $code_invalidate);
        $auth->addChild($Organizer, $code_create);


        // admin
        echo "adding admin permissions\n";
        $auth->addChild($admin, $default_permission);
        $auth->addChild($admin, $Organizer);
        $auth->addChild($admin, $gii_route);
        $auth->addChild($admin, $debug_route);
        $auth->addChild($admin, $admin_route);
        $auth->addChild($admin, $user_route);
        $auth->addChild($admin, $organizer_route);



        /* not needed if we are using the UserGroupRule to check is_admin in the User Table
        // assign the "AdminUser" the AdminRole
        $admin_user = User::findByUsername($this->adminUsername);
        if ($admin_user) {
            $auth->assign($admin, $admin_user->id);
        }
        */

        /*
        // Create roles
        $guest = $auth->createRole('guest');
        // $brand = $auth->createRole('BRAND');
        // $talent = $auth->createRole('TALENT');

        $admin = $auth->createRole('admin');
        // Create simple, based on action{$NAME} permissions
        $login = $auth->createPermission('login');
        $login->description = 'Allows Guest Users to login';

        $logout = $auth->createPermission('logout');
        $logout->description = 'Allows Users to logout';

        $error = $auth->createPermission('error');
        $error->description = 'Allows Everyone to display Errors';

        $default_permission = $auth->createPermission('default_permission');
        $default_permission->description = 'default_permissions for all users';



        //$signUp = $auth->createPermission('sign-up');
        //$signUp->description = 'Allows Everyone to use the Sign-Up page';

        $index = $auth->createPermission('index');
        $view = $auth->createPermission('view');
        $update = $auth->createPermission('update');
        $delete = $auth->createPermission('delete');



        //$testroute = $auth->createRoute('testroute');


        // Add permissions in Yii::$app->authManager
        $auth->add($login);
        $auth->add($logout);
        $auth->add($error);
        $auth->add($signUp);
        $auth->add($index);
        $auth->add($view);
        $auth->add($update);
        $auth->add($delete);
        $auth->add($default_permission);

        // Add rule, based on UserExt->group === $user->group
        $userGroupRule = new UserGroupRule();
        $auth->add($userGroupRule);

        // Add rule "UserGroupRule" in roles
        $guest->ruleName = $userGroupRule->name;
        //$brand->ruleName = $userGroupRule->name;
        //$talent->ruleName = $userGroupRule->name;
        $admin->ruleName = $userGroupRule->name;

        // Add roles in Yii::$app->authManager
        $auth->add($guest);
        // $auth->add($brand);
        // $auth->add($talent);
        $auth->add($admin);

        // Add permission-per-role in Yii::$app->authManager
        // Guest
        // $auth->addChild($guest, $login);
        // $auth->addChild($guest, $logout);
        // $auth->addChild($guest, $error);
        // $auth->addChild($guest, $signUp);
        // $auth->addChild($guest, $index);
        // $auth->addChild($guest, $view);

        // // BRAND
        // $auth->addChild($brand, $update);
        // $auth->addChild($brand, $guest);

        // // TALENT
        // $auth->addChild($talent, $update);
        // $auth->addChild($talent, $guest);


        // Admin
        $auth->addChild($admin, $delete);
        // $auth->addChild($admin, $talent);
        // $auth->addChild($admin, $brand);

        */
    }

    public function actionClear()
    {
        $auth = \Yii::$app->authManager;

        $userGroupRule = new UserGroupRule();
        $userGroupRuleItem = $auth->getRule($userGroupRule->name);
        if ($userGroupRuleItem) {
            $auth->remove($userGroupRuleItem);
        }

        $OwnerRule = new OwnerRule();
        $OwnerRuleItem = $auth->getRule($OwnerRule->name);
        if ($OwnerRuleItem) {
            $auth->remove($OwnerRuleItem);
        }



        if ($this->roles && is_array($this->roles)) {
            foreach ($this->roles as $roleName => $roleSettings) {
                $role=$auth->getRole($roleName);
                if ($role) {
                    $auth->remove($role);
                }
            }
        }

        if ($this->permissions && is_array($this->permissions)) {
            foreach ($this->permissions as $permissionName => $permissionDescription) {
                $permission = $auth->getPermission($permissionName);
                if ($permission) {
                    $auth->remove($permission);
                }
            }
        }

        if ($this->routes && is_array($this->routes)) {
            foreach ($this->routes as $routeName) {
                if ($routeName[0] == '/') {
                    $route = $auth->getPermission($routeName);
                    if ($route) {
                        $auth->remove($route);
                    }
                }
            }
        }

        $permission = $auth->getPermission('updatePoll');
        if ($permission) {
            $auth->remove($permission);
        }

        $permission = $auth->getPermission('updatePollOwned');
        if ($permission) {
            $auth->remove($permission);
        }

    }

    public function actionClearAll()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();
    }
}
