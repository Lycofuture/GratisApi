<?php

include_once('Reader.php');
require_once('BinaryBitmap.php');
require_once('common/detector/MathUtils.php');
require_once('common/BitMatrix.php');
require_once('common/BitSource.php');
require_once('common/BitArray.php');
require_once('common/CharacterSetEci.php');//
require_once('common/AbstractEnum.php');//
require_once('BinaryBitmap.php');
include_once('LuminanceSource.php');
include_once('GDLuminanceSource.php');
include_once('IMagickLuminanceSource.php');
include_once('common/customFunctions.php');
include_once('common/PerspectiveTransform.php');
include_once('common/GridSampler.php');
include_once('common/DefaultGridSampler.php');
include_once('common/DetectorResult.php');
require_once('common/reedsolomon/GenericGFPoly.php');
require_once('common/reedsolomon/GenericGF.php');
include_once('common/reedsolomon/ReedSolomonDecoder.php');
include_once('common/reedsolomon/ReedSolomonException.php');

include_once('qrcode/decoder/Decoder.php');
include_once('ReaderException.php');
include_once('NotFoundException.php');
include_once('FormatException.php');
include_once('ChecksumException.php');
include_once('qrcode/detector/FinderPatternInfo.php');
include_once('qrcode/detector/FinderPatternFinder.php');
include_once('ResultPoint.php');
include_once('qrcode/detector/FinderPattern.php');
include_once('qrcode/detector/AlignmentPatternFinder.php');
include_once('qrcode/detector/AlignmentPattern.php');
include_once('qrcode/decoder/Version.php');
include_once('qrcode/decoder/BitMatrixParser.php');
include_once('qrcode/decoder/FormatInformation.php');
include_once('qrcode/decoder/ErrorCorrectionLevel.php');
include_once('qrcode/decoder/DataMask.php');
include_once('qrcode/decoder/DataBlock.php');
include_once('qrcode/decoder/DecodedBitStreamParser.php');
include_once('qrcode/decoder/Mode.php');
include_once('common/DecoderResult.php');
include_once('Result.php');
include_once('Binarizer.php');
include_once('common/GlobalHistogramBinarizer.php');
include_once('common/HybridBinarizer.php');


final class QrReader
{
    const SOURCE_TYPE_FILE = 'file';
    const SOURCE_TYPE_BLOB = 'blob';
    const SOURCE_TYPE_RESOURCE = 'resource';

    public $result;

    function __construct($imgsource, $sourcetype = QrReader::SOURCE_TYPE_FILE, $isUseImagickIfAvailable = true)
    {

        try {
            switch($sourcetype) {
                case QrReader::SOURCE_TYPE_FILE:
                    if($isUseImagickIfAvailable && extension_loaded('imagick')) {
                        $im = new Imagick();
                        $im->readImage($imgsource);
                    }else {
                        $image = teacher_curl($imgsource,['refer'=>1]);
                        $im = imagecreatefromstring($image);
                    }

                    break;

                case QrReader::SOURCE_TYPE_BLOB:
                    if($isUseImagickIfAvailable && extension_loaded('imagick')) {
                        $im = new Imagick();
                        $im->readimageblob($imgsource);
                    }else {
                        $im = imagecreatefromstring($imgsource);
                    }

                    break;

                case QrReader::SOURCE_TYPE_RESOURCE:
                    $im = $imgsource;
                    if($isUseImagickIfAvailable && extension_loaded('imagick')) {
                        $isUseImagickIfAvailable = true;
                    }else {
                        $isUseImagickIfAvailable = false;
                    }

                    break;
            }

            if($isUseImagickIfAvailable && extension_loaded('imagick')) {
                $width = $im->getImageWidth();
                $height = $im->getImageHeight();
                $source = new \Zxing\IMagickLuminanceSource($im, $width, $height);
            }else {
                $width = imagesx($im);
                $height = imagesy($im);
                $source = new \Zxing\GDLuminanceSource($im, $width, $height);
            }
            $histo = new \Zxing\Common\HybridBinarizer($source);
            $bitmap = new \Zxing\BinaryBitmap($histo);
            $reader = new \Zxing\Qrcode\QRCodeReader();

            $this->result = $reader->decode($bitmap);
        }catch (\Zxing\NotFoundException $er){
            $this->result = false;
        }catch( \Zxing\FormatException $er){
            $this->result = false;
        }catch( \Zxing\ChecksumException $er){
            $this->result = false;
        }
    }

    public function text()
    {
        if(method_exists($this->result,'toString')) {
            return  ($this->result->toString());
        }else{
            return $this->result;
        }
    }

    public function decode()
    {
        return $this->text();
    }
}

function get($url) {
    //初使化curl
    $ch = curl_init();
    //请求的url，由形参传入
    curl_setopt($ch, CURLOPT_URL, $url);
    //将得到的数据返回
    curl_setopt($ch, CURLOPT_HTTPGET, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:image/*',"Connection: Keep-Alive","Accept: text/html, application/xhtml+xml, */*", "Pragma: no-cache", "Accept-Language: zh-Hans-CN,zh-Hans;q=0.8,en-US;q=0.5,en;q=0.3","User-Agent: Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0)"));
//    curl_setopt($ch, CURLOPT_REFERER, 'https://www.moestack.com/');
    $Header[] = 'Appect: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 11; PCLM10 Build/RKQ1.200928.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.106 Mobile Safari/537.36');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    //关闭资源
    curl_close($ch);
    //返回内容
    return $output;
}

