<?php


namespace app\index\controller;


use app\common\cache\ExportCache;

class DownloadController
{
    /**
     * @notes  导出文件下载
     * @return \think\response\File
     * @author 令狐冲
     * @date 2021/7/28 14:15
     */
    public function export()
    {
        //获取文件缓存的key
        $fileKey = request()->get('file');

        //通过文件缓存的key获取文件储存的路径
        $exportCache = new ExportCache();
        $fileInfo = $exportCache->getFile($fileKey);

        if (empty($fileInfo)) {
            abort(404, '下载文件不存在');
        }

        //下载前删除缓存
        $exportCache->delete($fileKey);


        return download($fileInfo['src'] . $fileInfo['name'], $fileInfo['name']);
    }
}