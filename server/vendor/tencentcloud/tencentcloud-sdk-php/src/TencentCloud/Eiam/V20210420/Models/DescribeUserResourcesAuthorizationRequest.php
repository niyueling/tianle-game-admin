<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Eiam\V20210420\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribeUserResourcesAuthorization请求参数结构体
 *
 * @method string getApplicationId() 获取应用ID。
 * @method void setApplicationId(string $ApplicationId) 设置应用ID。
 * @method string getUserId() 获取用户ID。
 * @method void setUserId(string $UserId) 设置用户ID。
 * @method string getUserName() 获取用户名。
 * @method void setUserName(string $UserName) 设置用户名。
 * @method boolean getIncludeInheritedAuthorizations() 获取查询范围是否包括用户关联的用户组、组织机构的应用访问权限。默认为不查询 ，传false表示不查询该范围，传true查询该范围。
 * @method void setIncludeInheritedAuthorizations(boolean $IncludeInheritedAuthorizations) 设置查询范围是否包括用户关联的用户组、组织机构的应用访问权限。默认为不查询 ，传false表示不查询该范围，传true查询该范围。
 */
class DescribeUserResourcesAuthorizationRequest extends AbstractModel
{
    /**
     * @var string 应用ID。
     */
    public $ApplicationId;

    /**
     * @var string 用户ID。
     */
    public $UserId;

    /**
     * @var string 用户名。
     */
    public $UserName;

    /**
     * @var boolean 查询范围是否包括用户关联的用户组、组织机构的应用访问权限。默认为不查询 ，传false表示不查询该范围，传true查询该范围。
     */
    public $IncludeInheritedAuthorizations;

    /**
     * @param string $ApplicationId 应用ID。
     * @param string $UserId 用户ID。
     * @param string $UserName 用户名。
     * @param boolean $IncludeInheritedAuthorizations 查询范围是否包括用户关联的用户组、组织机构的应用访问权限。默认为不查询 ，传false表示不查询该范围，传true查询该范围。
     */
    function __construct()
    {

    }

    /**
     * For internal only. DO NOT USE IT.
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("ApplicationId",$param) and $param["ApplicationId"] !== null) {
            $this->ApplicationId = $param["ApplicationId"];
        }

        if (array_key_exists("UserId",$param) and $param["UserId"] !== null) {
            $this->UserId = $param["UserId"];
        }

        if (array_key_exists("UserName",$param) and $param["UserName"] !== null) {
            $this->UserName = $param["UserName"];
        }

        if (array_key_exists("IncludeInheritedAuthorizations",$param) and $param["IncludeInheritedAuthorizations"] !== null) {
            $this->IncludeInheritedAuthorizations = $param["IncludeInheritedAuthorizations"];
        }
    }
}
