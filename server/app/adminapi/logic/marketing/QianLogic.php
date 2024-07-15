<?php

namespace app\adminapi\logic\marketing;

use app\common\enum\CaiShenEnum;
use app\common\logic\BaseLogic;
use MongoDB\BSON\ObjectId;
use PhpOffice\PhpSpreadsheet\IOFactory;
use think\facade\Db;
use think\file\UploadedFile;

/**
 * 助威管理
 * Class CenterLogic
 * @package app\adminapi\logic
 */
class QianLogic extends BaseLogic
{
    /**
     * @notes 抽签管理
     * @return array
     * @author Tab
     * @date 2021/9/27 10:31
     */
    public static function lists($params)
    {
        $count = Db::connect('mongodb')->table('luckyqians')->count();
        $goodList = Db::connect('mongodb')->table('luckyqians')
            ->page($params["page_no"], $params["page_size"])->select()->all();

        foreach ($goodList as $key => $value) {
            $goodList[$key]["_id"] = reset($goodList[$key]["_id"]);
            $goodList[$key]["contentStr"] = implode("、", $goodList[$key]["content"]);
            $goodList[$key]["summaryStr"] = implode("、", $goodList[$key]["summary"]);
        }

        return [
            "count" => $count,
            "lists" => $goodList,
            "page_no" => $params["page_no"],
            "page_size" => $params["page_size"]
        ];
    }

    /**
     * @notes 抽签删除
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function del(array $params):bool
    {
        Db::connect('mongodb')->table('luckyqians')->where(['_id' => new ObjectId($params['_id'])])->delete();
        return true;

    }

    /**
     * @notes 抽签编辑
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function setInfo(array $params):bool
    {
        Db::connect('mongodb')->table('luckyqians')->where(['_id' => new ObjectId($params['_id'])])->update([
            "qianId" => intval($params["qianId"]),
            "name" => $params["name"],
            "position" => $params["position"],
            "content" => $params["content"],
            "luckyLevel" => $params["luckyLevel"],
            "bless" => intval($params["bless"]),
            "summary" => $params["summary"]
        ]);

        return true;
    }

    /**
     * @notes 抽签新增
     * @param array $params
     * @return bool
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function add(array $params):bool
    {
        $count = Db::connect('mongodb')->table('luckyqians')
            ->where("name", $params["name"])->count();
        if($count > 0) return false;

        Db::connect('mongodb')->table('luckyqians')->insert([
            "qianId" => intval($params["qianId"]),
            "name" => $params["name"],
            "position" => $params["position"],
            "content" => $params["content"],
            "luckyLevel" => $params["luckyLevel"],
            "bless" => intval($params["bless"]),
            "summary" => $params["summary"]
        ]);
        return true;
    }

    /**
     * @notes 导入excel
     * @param array $params
     * @return array
     * @author cjhao
     * @date 2021/8/18 17:21
     */
    public static function upload(UploadedFile $file)
    {
        $dir = env('ENV') == 'dev' ? './public/uploads/excel' : './uploads/excel';
        $original_name = $file->getOriginalName();
        $file->move($dir,$original_name);
        $path = ROOT_PATH . '/uploads/excel/' . $original_name;
        $data = [];

        // 读取 Excel 文件数据
        $spreadsheet = IOFactory::load($path);
        $worksheet = $spreadsheet->getActiveSheet();

        // 获取列名
        foreach ($worksheet->getRowIterator() as $row) {
            if ($row->getRowIndex() == 1) {
                foreach ($row->getCellIterator() as $cell) {
                    $columnName = $cell->getValue();
                    $data[0][$columnName] = null;
                }
                break;
            }
        }

        // 构建 insertData 数组
        $insertData = [];
        foreach ($worksheet->getRowIterator() as $row) {
            if ($row->getRowIndex() == 1) {
                continue;
            }
            // 获取数据
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                if(!empty($cell->getValue())) $rowData[] = $cell->getValue();
            }

            if(count($rowData) === 0) break;

            // 将数据与列名对应起来
            $rowData = array_combine(array_filter(array_keys($data[0])), $rowData);

            // 将字符串按照 "," 或 "、" 分隔符转成数组
            $rowData['content'] = explode(' ', $rowData['content']);
            $rowData['summary'] = explode('。', $rowData['summary']);
            $luckyLevel = explode('。', $rowData['luckyLevel']);
            $rowData["luckyLevel"] = $luckyLevel[1] . '签';
            $luckyLevel[1] = str_split($luckyLevel[1], 3);
            if (!empty($rowData['name'])) {
                $rowData["position"] = CaiShenEnum::CaiShenPosition[$luckyLevel[1][0]] . ',' .
                    CaiShenEnum::CaiShenPosition[$luckyLevel[1][1]];
                $rowData["bless"] = CaiShenEnum::BlessList[trim($luckyLevel[0])];
                $insertData[] = $rowData;
            }
        }

        foreach ($insertData as $data) {
            $count = Db::connect('mongodb')->table('luckyqians')
                ->where("qianId", $data["qianId"])->count();

            if($count > 0) {
                Db::connect('mongodb')->table('luckyqians')
                    ->where("qianId", $data["qianId"])->update($data);
            } else {
                Db::connect('mongodb')->table('luckyqians')->insert($data);
            }
        }

        return $insertData;
    }
}
