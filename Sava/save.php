<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/5
 * Time: 12:30
 */
        $fs = new COM("PageOfficeASP.FileSaver");
       /**/ $fs->SaveToFile="Public/File/test/".$fs->FileName;
        //保存成功后，设置返回值，此处设置为：OK
        $fs->CustomSaveResult = "OK";
        $fs->Close();