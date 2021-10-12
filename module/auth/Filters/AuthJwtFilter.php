<?php namespace Modules\Auth\Filters;

use Modules\Auth\Config\Services;
use Modules\Auth\Config\ModuleAuthConfig;
use Modules\Auth\Models\GroupsPermissionModel;
use Modules\Auth\Models\GroupsUsersModel;
use Modules\Auth\Models\UsersPermissionModel;
use Modules\Shared\Enums\FilterErrorType;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Authorization\PermissionModel;
use Myth\Auth\Models\UserModel;


class  AuthJwtFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $roleRoute = Services::roleRoute();
        $response = \CodeIgniter\Config\Services::response();
        $authorize = \Myth\Auth\Config\Services::authorization();

        $groupsUsersModel = new GroupsUsersModel();
        $groupsPermissionModel = new GroupsPermissionModel();
        $usersPermissionModel = new UsersPermissionModel();
        $permissionModel = new PermissionModel();
        $groupModel = new GroupModel();
        $userModel = new UserModel();
        helper('jwt');
        $authConfig = new  ModuleAuthConfig();
        $requestWithUser = Services::requestWithUser();
        $explodeUri = explode('/', uri_string());
        $controllerName = strtolower($explodeUri[1]);

        try {

            $jwtHeader = $request->getServer('HTTP_AUTHORIZATION');
            $jwtCookie = $request->getCookie($authConfig->jwt['name']);

            if (is_null($jwtHeader) && is_null($jwtCookie)) {
                return $response->setJSON(['success' => false,
                    'type' => FilterErrorType::Login,
                    'error' => lang('Authenticate.filter.jwt')])->setContentType('application/json')
                    ->setStatusCode(Response::HTTP_UNAUTHORIZED, lang('Authenticate.filter.login'));
            }
            if (!empty($jwtHeader))
                $jwtToken = getJWTHeader($jwtHeader);
            else
                $jwtToken = $jwtCookie;
            $jwtUser = validateJWT($jwtToken, $authConfig->jwt['secretKey']);

            // user with request call to pass dataUser to it
            $userGroup = $groupModel->getGroupsForUser($jwtUser->userId);
            $userData = $userModel->find($jwtUser->userId);
            $userData->groupId = $userGroup[0]['group_id'];
            $userData->groupName = $userGroup[0]['name'];
            $requestWithUser->setUser($userData);

            //get permission for  controller
            $controllerPermission = $permissionModel->asObject()->where([
                "name" => $controllerName,
                "active" => 1
            ])->first();


            // if there is not permission for controller check by roles
            // other wise check by permission by user or group

            if (empty($controllerPermission)) {


                // Check each requested roles

                $controllerRoles = $roleRoute->getRoleAccess($controllerName);

                if (empty($controllerRoles)) {
                    return;
                }
                foreach ($controllerRoles as $group) {
                    if ($authorize->inGroup($group, $jwtUser->userId)) {
                        return;
                    }
                }

            } else {

                // Check each requested permission

                $typeMethod = $request->getMethod();

                // get group of user
                $groupOfUser = $groupsUsersModel->asObject()->where('user_id', $jwtUser->userId)
                    ->first();

                $permissionOfGroup = $groupsPermissionModel->asObject()->where([
                    "permission_id" => $controllerPermission->id,
                    "group_id" => $groupOfUser->group_id
                ])->first();

                $permissionOfUser = $usersPermissionModel->asObject()->where([
                    "permission_id" => $controllerPermission->id,
                    "user_id" => $jwtUser->userId
                ])->first();


                if (!empty($permissionOfGroup) && strstr($permissionOfGroup->actions, $typeMethod)) {
                    return;

                }

                if (!empty($permissionOfUser) && strstr($permissionOfUser->actions, $typeMethod)) {
                    return;
                }

            }

            return $response->setJSON(['success' => false,
                'type' => FilterErrorType::Permission,
                'error' => lang('Auth.notEnoughPrivilege')])->setContentType('application/json')
                ->setStatusCode(Response::HTTP_UNAUTHORIZED, lang('Auth.notEnoughPrivilege'));


        } catch (\Exception $e) {


            return $response->setJSON(['success' => false,
                'type' => FilterErrorType::Login,
                'error' => lang('Authenticate.filter.jwt')])->setContentType('application/json')
                ->setStatusCode(Response::HTTP_UNAUTHORIZED, lang('Authenticate.filter.login'));


        }


    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here

    }
}
