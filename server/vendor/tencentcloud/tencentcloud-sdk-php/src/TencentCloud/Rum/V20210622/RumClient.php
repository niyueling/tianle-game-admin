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

namespace TencentCloud\Rum\V20210622;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Rum\V20210622\Models as Models;

/**
 * @method Models\CreateProjectResponse CreateProject(Models\CreateProjectRequest $req) 创建项目（归属于某个团队）
 * @method Models\DescribeDataPerformancePageResponse DescribeDataPerformancePage(Models\DescribeDataPerformancePageRequest $req) 获取PerformancePage信息
 * @method Models\DescribeErrorResponse DescribeError(Models\DescribeErrorRequest $req) 获取首页错误信息
 */

class RumClient extends AbstractClient
{
    /**
     * @var string
     */
    protected $endpoint = "rum.tencentcloudapi.com";

    /**
     * @var string
     */
    protected $service = "rum";

    /**
     * @var string
     */
    protected $version = "2021-06-22";

    /**
     * @param Credential $credential
     * @param string $region
     * @param ClientProfile|null $profile
     * @throws TencentCloudSDKException
     */
    function __construct($credential, $region, $profile=null)
    {
        parent::__construct($this->endpoint, $this->version, $credential, $region, $profile);
    }

    public function returnResponse($action, $response)
    {
        $respClass = "TencentCloud"."\\".ucfirst("rum")."\\"."V20210622\\Models"."\\".ucfirst($action)."Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
