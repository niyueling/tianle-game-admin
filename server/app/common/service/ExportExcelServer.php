<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------


namespace app\common\service;

use app\common\cache\ExportCache;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * 导出Excel
 * Class ExportExcelServer
 * @package app\common\lists
 */
class ExportExcelServer
{

    protected $fileName = ''; //文件名称

    protected $exportNumber = ['order_sn', 'sn']; // 导出中的数字


    /**
     * @notes 创建Excel
     * @param $excelFields
     * @param $lists
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @author 段誉
     * @date 2022/4/24 9:58
     */
    public function createExcel($excelFields, $lists)
    {
        if (empty($lists)) {
            throw new \Exception('暂无导出数据!');
        }

        $title = array_values($excelFields);

        // 组装导出信息
        $data = [];
        foreach ($lists as $row) {
            $temp = [];
            foreach ($excelFields as $key => $excelField) {
                $temp[$key] = $row[$key];
            }
            $data[] = $temp;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //设置单元格内容
        foreach ($title as $key => $value) {
            // 单元格内容写入
            $sheet->setCellValueByColumnAndRow($key + 1, 1, $value);
        }
        $row = 2; //从第二行开始
        foreach ($data as $item) {
            $column = 1;
            foreach ($item as $k => $value) {
                if (in_array($k, $this->exportNumber)) {
                    $value = "\t" . $value . "\t";
                }
                //单元格内容写入
                $sheet->setCellValueByColumnAndRow($column, $row, $value);
                $column++;
            }
            $row++;
        }

        $getHighestRowAndColumn = $sheet->getHighestRowAndColumn();
        $HighestRow = $getHighestRowAndColumn['row'];
        $column = $getHighestRowAndColumn['column'];
        $titleScope = 'A1:' . $column . '1';//第一（标题）范围（例：A1:D1)

        $sheet->getStyle($titleScope)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID) // 设置填充样式
            ->getStartColor()
            ->setARGB('00B0F0');
        // 设置文字颜色为白色
        $sheet->getStyle($titleScope)->getFont()->getColor()
            ->setARGB('FFFFFF');

//        $sheet->getStyle('B2')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

        $allCope = 'A1:' . $column . $HighestRow;//整个表格范围（例：A1:D5）
        $sheet->getStyle($allCope)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        //创建excel文件
        $exportCache = new ExportCache();
        $src = $exportCache->getSrc();

        if (!file_exists($src)) {
            mkdir($src, 0775, true);
        }

        $fileName = $this->getFileName();

        // 生成文件路径
        $writer->save($src . $fileName);

        //设置本地excel返回下载地址
        $vars = ['file' => $exportCache->setFile($fileName)];
        return (string)url("index/download/export", $vars, false, true);
    }



    /**
     * @notes 设置导出文件名称
     * @param $fileName
     * @return string
     * @author 段誉
     * @date 2022/4/24 10:02
     */
    public function setFileName($fileName)
    {
       return $this->fileName = $fileName . '-' . date('Y-m-d-His') . '.xlsx';
    }


    /**
     * @notes 获取导出文件名
     * @return string
     * @author 段誉
     * @date 2022/4/24 10:03
     */
    public function getFileName()
    {
        if (empty($this->fileName)) {
            return date('Y-m-d-His') . '.xlsx';
        }
        return $this->fileName;
    }


    /**
     * @notes 设置导出列表中的数字
     * @param null $params
     * @return bool
     * @author 段誉
     * @date 2022/4/24 9:42
     */
    public function setExportNumber($params = null): bool
    {
        if (is_null($params)) {
            return false;
        }

        $params = is_array($params) ? $params : [$params];

        foreach ($params as $item) {
            if (in_array($item, $this->exportNumber)) {
                continue;
            }
            array_push($this->exportNumber, $item);
        }

        return true;
    }


}
