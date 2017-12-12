<?php

namespace bizley\contenttools\models;

use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * @author Paweł Bizley Brzozowski
 * @version 1.0
 * @license Apache 2.0
 * https://github.com/bizley-code/yii2-content-tools
 * http://www.yiiframework.com/extension/yii2-content-tools
 * 
 * ContentTools was created by Anthony Blackshaw
 * http://getcontenttools.com/
 * https://github.com/GetmeUK/ContentTools
 * 
 * This model is used by UploadAction to validate and save the image uploaded 
 * through Yii 2 ContentTools editor.
 * 
 * Images are stored in the 'content-tools-uploads' web accessible folder.
 */
class ImageForm extends Model
{
    
    const UPLOAD_DIR = 'content-tools-uploads';
    
    /**
     * @var UploadedFile Uploaded image
     */
    public $image;
    
    /**
     * @var string Web accessible path to the uploaded image
     */
    public $url;
    
    /**
     * @var string Server accessible root to the uploaded image
     */
    public $path;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['image', 'image', 'extensions' => ['png', 'jpg', 'gif'], 'maxWidth' => 1920, 'maxHeight' => 1280, 'maxSize' => 10 * 1024 * 1024]
        ];
    }
    
    /**
     * Validates and saves the image.
     * Creates the folder to store images if necessary.
     * @return boolean
     */
    public function upload()
    {
        try {
            if ($this->validate()) {
                $save_path = Yii::getAlias('@app') . '/../dev.brandmaker.ru' . '/' . 'statics' . '/' . self::UPLOAD_DIR;

                FileHelper::createDirectory($save_path);

                $newName = self::hashName($this->image->baseName);

                $this->path = $save_path . '/' . $newName . '.' . $this->image->extension;
                $this->url  = Yii::getAlias('/statics/' . self::UPLOAD_DIR . '/' . $newName . '.' . $this->image->extension);
            
                return $this->image->saveAs($this->path);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
        return false;
    }

    protected function hashName($name)
    {
        return md5($name);
    }
}
