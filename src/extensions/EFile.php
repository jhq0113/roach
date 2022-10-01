<?php
/**
 * @description:文件帮助类
 * @author: jhq0113@163.com
 * @datetime: 2022/10/1 22:05
 */
namespace roach\extensions;

/**
 * @description:文件帮助类
 * @author: jhq0113@163.com
 * @datetime: 2022/10/1 22:05
 */
class EFile extends IExtension
{
    /**
     * @param string $fileName
     * @return false|int
     * @author: jhq0113@163.com
     * @datetime: 2022/9/16 14:35
     */
    public static function filesize($fileName)
    {
        clearstatcache(true, $fileName);
        return filesize($fileName);
    }

    /**
     * @param string $fileName
     * @param array  $params
     * @return false|string
     * @throws \Exception
     * @datetime 2020/10/30 10:40 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function render($fileName, $params = [])
    {
        if(!file_exists($fileName)) {
            throw new \Exception($fileName.'文件不存在');
        }
        extract($params, EXTR_OVERWRITE);
        ob_start();
        ob_implicit_flush(false);
        require $fileName;
        return ob_get_clean();
    }

    /**压缩文件
     * @param string $zipFileName
     * @param array  $files
     * @param int    $flag
     * @return bool
     * @datetime 2020/10/20 8:17 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function zip($zipFileName, $files, $flag = \ZipArchive::CREATE)
    {
        if(empty($files)) {
            return false;
        }

        $zip = new \ZipArchive();
        if($zip->open($zipFileName, $flag) === true) {
            foreach ($files as $fileName => $zipFile) {
                if(is_numeric($fileName)) {
                    $zip->addFile($zipFile);
                }else {
                    $zip->addFile($fileName, $zipFile);
                }
            }
            $zip->close();
            return true;
        }
        return false;
    }

    /**导出csv文件
     * @param string $csvFileName
     * @param array  $rows
     * @param array  $header
     * @datetime 2020/10/20 8:47 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function outCsv($csvFileName,$rows, $header)
    {
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename={$csvFileName}");
        header('Cache-Control: max-age=0');
        static::csv('php://output', $rows, $header);
    }

    /**
     * @param mixed $value
     * @return false|string
     * @datetime 2020/10/20 8:52 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    protected static function _convert2CsvValue($value)
    {
        if (is_numeric($value) && $value > 1500000000) {
            return $value."\t";
        } else {
            return iconv("UTF-8", "GBK//IGNORE", $value);
        }
    }

    /**生成csv文件
     * @param string $csvFileName
     * @param array  $rows
     * @param array  $header
     * @datetime 2020/10/20 8:47 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function csv($csvFileName, $rows, $header)
    {
        $file = fopen($csvFileName, 'a');

        $isIndex = true;

        $csvHeader = [];
        foreach ($header as $key => $value) {
            array_push($csvHeader, static::_convert2CsvValue($value));
            if (is_string($key)) {
                $isIndex = false;
            }
        }
        fputcsv($file, $csvHeader);

        foreach ($rows as $row) {
            $line = [];
            if ($isIndex) {
                foreach ($row as $value) {
                    array_push($line, static::_convert2CsvValue($value));
                }
                fputcsv($file, $line);
                continue;
            }

            foreach ($header as $field => $value) {
                if (!isset($row[$field])) {
                    array_push($line, '');
                }  else {
                    array_push($line, static::_convert2CsvValue($row[ $field ]));
                }
            }
            fputcsv($file, $line);
        }
    }
}