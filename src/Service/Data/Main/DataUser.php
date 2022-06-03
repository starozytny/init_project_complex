<?php


namespace App\Service\Data\Main;


use App\Entity\Main\User;
use App\Service\SanitizeData;

class DataUser
{
    private $sanitizeData;

    public function __construct(SanitizeData $sanitizeData)
    {
        $this->sanitizeData = $sanitizeData;
    }

    public function setData(User $obj, $data): User
    {
        if (isset($data->roles)) {
            $obj->setRoles($data->roles);
        }

        $username = isset($data->username) ? $this->sanitizeData->fullSanitize($data->username) : $data->email;

        return ($obj)
            ->setUsername($this->sanitizeData->trimData($username))
            ->setFirstname(ucfirst($this->sanitizeData->trimData($data->firstname)))
            ->setLastname(mb_strtoupper($this->sanitizeData->trimData($data->lastname)))
            ->setEmail($this->sanitizeData->trimData($data->email))
            ->setManager($this->sanitizeData->trimData($data->manager))
        ;
    }
}