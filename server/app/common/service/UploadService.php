<?php


namespace app\common\service;


use app\common\enum\FileEnum;
use app\common\model\File;
use app\common\service\storage\Driver as StorageDriver;
use EasyWeChat\Factory;
use Exception;
use think\file\UploadedFile;

class UploadService
{
    /**
     * 上传图片
     * @notes
     * @param $cid
     * @param int $source_id  来源id
     * @param int $source 来源
     * @param string $save_dir
     * @return array
     * @throws Exception
     * @author 张无忌
     * @date 2021/7/28 16:48
     */
    public static function image($cid,$source_id = 0,$source = FileEnum::SOURCE_BACKSTAGE, $save_dir='uploads/images')
    {
        try {
            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine'  => ConfigService::get('storage') ?? ['local'=>[]],
            ];

            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            if (!$StorageDriver->upload($save_dir)) {
                throw new Exception($StorageDriver->getError());
            }

            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $file_name = substr($fileInfo['name'], 0, 123);
                $file_end = substr($fileInfo['name'], strlen($fileInfo['name'])-5, strlen($fileInfo['name']));
                $fileInfo['name'] = $file_name.$file_end;
            }

            // 4、写入数据库中
            $file = File::create([
                'cid'         => $cid,
                'type'        => FileEnum::IMAGE_TYPE,
                'name'        => $fileInfo['name'],
                'uri'         => $save_dir . '/' . str_replace("\\","/", $fileName),
                'source'      => $source,
                'source_id'   => $source_id,
                'create_time' => time(),
            ]);

            // 5、返回结果
            return [
                'id'   => $file['id'],
                'cid'  => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri'  => FileService::getFileUrl($file['uri']),
                'url'  => $file['uri']
            ];

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @notes 视频上传
     * @param $cid
     * @param int $user_id
     * @param string $save_dir
     * @return array
     * @throws Exception
     * @author 张无忌
     * @date 2021/7/29 9:41
     */

    public static function video($cid,$source_id = 0,$source = FileEnum::SOURCE_BACKSTAGE,  $save_dir='uploads/video')
    {
        try {
            $config = [
                'default' => ConfigService::get('storage', 'default', 'local'),
                'engine'  => ConfigService::get('storage') ?? ['local'=>[]],
            ];

            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            if (!$StorageDriver->upload($save_dir)) {
                throw new Exception($StorageDriver->getError());
            }

            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $file_name = substr($fileInfo['name'], 0, 123);
                $file_end = substr($fileInfo['name'], strlen($fileInfo['name'])-5, strlen($fileInfo['name']));
                $fileInfo['name'] = $file_name.$file_end;
            }

            // 4、写入数据库中
            $file = File::create([
                'cid'         => $cid,
                'type'        => FileEnum::VIDEO_TYPE,
                'name'        => $fileInfo['name'],
                'uri'         => $save_dir . '/' . str_replace("\\","/", $fileName),
                'source'      => $source,
                'source_id'   => $source_id,
                'create_time' => time(),
            ]);

            // 5、返回结果
            return [
                'id'   => $file['id'],
                'cid'  => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'uri'  => FileService::getFileUrl($file['uri']),
                'url'  => $file['uri']
            ];

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @notes 上传素材
     * @param $url
     * @author cjhao
     * @date 2021/11/22 17:04
     */
    public static function wechatMaterial(UploadedFile $file)
    {
        try {
            $dir = './uploads/excel';
            $original_name = $file->getOriginalName();
            $file->move($dir,$original_name);
            $path = ROOT_PATH . '/uploads/excel/' . $original_name;

            return ['url' => FileService::getFileUrl('uploads/excel/' . $original_name), 'path' => $path];

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
