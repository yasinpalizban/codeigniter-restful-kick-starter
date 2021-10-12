<?php namespace Modules\Common\Services;


use Modules\Common\Models\ChatPrivateModel;
use Modules\Common\Models\ChatRoomModel;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;
use Myth\Auth\Authorization\GroupModel;

class  ChatContactService extends MainService
{
    private string $userId;
    private string $userGroupId;
    private string $userGroupName;
    private GroupModel $groupModel;
    private ChatPrivateModel $chatPrivateModel;
    private ChatRoomModel $chatRoomModel;

    public function __construct()
    {
        $this->userId = "";
        $this->userGroupId = "";
        $this->userGroupName = "";
        $this->groupModel = new GroupModel();
        $this->chatPrivateModel = new ChatPrivateModel();
        $this->chatRoomModel = new ChatRoomModel();

    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }


    public function setUserGroupId(string $userGroupId): void
    {
        $this->userGroupId = $userGroupId;
    }


    public function setUserGroupName(string $userGroupName): void
    {
        $this->userGroupName = $userGroupName;
    }


    /**
     * index function
     * @method : GET
     * @param UrlQueryParamInterface $urlQueryParam
     * @return array
     */
    public function index(UrlQueryParamInterface $urlQueryParam)
    {

        helper('chat');


        $notMemberGroup = $this->groupModel->select('id')->where('name', 'member')->findAll();
        $users = $this->groupModel->select(' 
      users.id,
      users.email,
      users.username,
      users.first_name as firstName,
      users.last_name as lastName,
      users.image,
      users.gender,
      users.birthday,
      users.country,
      users.city,
      users.address,
      users.phone,
      auth_groups.name as group')
            ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups.id', 'left')
            ->join('users', 'auth_groups_users.user_id = users.id', 'left')
            ->orderBy('username')
            ->whereNotIn('users.id', [$this->userId])
            ->whereNotIn('auth_groups.id', [$notMemberGroup[0]->id])->findAll();

        $groups = $this->groupModel->where('name', $this->userGroupName)->findAll();

        $users = $this->chatPrivateModel->appendLastChat($users, $this->userId);
        $groups = $this->chatRoomModel->appendLastChat($groups, $this->userGroupId);
        if (is_null($users)) {
            uasort($users, "compareLastChat");
        }

        $users = removeObjectChat($users);

        $data = [
            'users' => $users,
            'groups' => $groups,
        ];
        return $data;

    }


}
