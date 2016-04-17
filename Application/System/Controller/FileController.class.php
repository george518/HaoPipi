<?php
namespace System\Controller;

class FileController extends SystemController
{
    public $imgFile   = 'Uploads/';
    public $imgType   = array("jpg", "png", "gif");//允许上传文件格式 
    public $imgSize   = 2097152;//上传文件大小 2M2*1024*1024


    public function uploadImg()
    {
        $file   = I('get.file','','htmlspecialchars');
        $width  = I('get.w',0,'intval');
        $height = I('get.h',0,'intval');

        if (!$file) 
        {
            echo json_encode(array("error"=>"上传文件配置有误"));
            exit(); 
        }
        if (isset($_POST)) 
        { 
            $name     = $_FILES['file']['name']; 
            $size     = $_FILES['file']['size']; 
            $name_tmp = $_FILES['file']['tmp_name']; 
           
            if (empty($name)) 
            { 
                echo json_encode(array("error"=>"您还未选择图片")); 
                exit; 
            } 

            $type = strtolower(substr(strrchr($name, '.'), 1)); //获取文件类型 
            
            if (!in_array($type, $this->imgType)) 
            { 
                echo json_encode(array("error"=>"请上传正确类型的图片！")); 
                exit; 
            } 
            if ($size > $this->imgSize) 
            { 
                echo json_encode(array("error"=>"图片大小已超过2M限制！")); 
                exit; 
            } 

            $imageInfo = $this->getImageInfo($name_tmp);

            if($width!=0 && $imageInfo['width']!=$width) 
            {
                echo json_encode(array("error"=>"图片宽度不符合要求！")); 
                exit; 
            }

            if($height!=0 && $imageInfo['height']!=$height) 
            {
                echo json_encode(array("error"=>"图片高度不符合要求！")); 
                exit; 
            }

            $path = $this->imgFile.$file.'/'.date('Y-m-d',time()).'/';
            if(!is_dir($path))
            {
                mkDirs($path);
            }

            $pic_name = time() . rand(10000, 99999) . "." . $type;//图片名称 
            $pic_url = $path . $pic_name;//上传后图片路径+名称

            if (move_uploaded_file($name_tmp, $pic_url)) 
            { 
                //临时文件转移到目标文件夹 
                echo json_encode(array("error"=>"0","pic"=>$pic_url,"name"=>$pic_name)); 
            } else 
            { 
                echo json_encode(array("error"=>"上传有误，请检查服务器配置！")); 
            } 
        }
    }

    /**
     * [getImageInfo 获取图片信息]
     * @Author haodaquan
     * @Date   2016-04-14
     * @param  [type]     $img [图片临时存储地址]
     * @return [type]          [description]
     */
    public function getImageInfo($img)
    {
        $imageInfo = getimagesize($img);
        if ($imageInfo !== false) 
        {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
            $imageSize = filesize($img);
            $info = array(
                "width" => $imageInfo[0],
                "height" => $imageInfo[1],
                "type" => $imageType,
                "size" => $imageSize,
                "mime" => $imageInfo['mime']
            );
            return $info;
        } else 
        {
            return false;
        }
    }
    
}
