<?php
/******************************
 * $File: getdir.class.php
 * $Description: ��ȡ�ļ�Ŀ¼�����ļ�����
 * $Author: ahui 
 * $Time:2010-06-06
 * $Update:Ahui
 * $UpdateDate:2012-06-10  
 * Copyright(c) 2010 - 2012 by deayou.com. All rights reserved
******************************/

if (!defined('DEAYOU_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

class getdirClass{  
  
    //��������  
    private $DirArray  = array();  
    private $FileArray = array();  
    private $DirFileArray = array();  
  
    private $Handle,$Dir,$File;  
  
    //��ȡĿ¼�б�  
    public function getDir( & $Dir ){  
        if( is_dir($Dir) ){  
            if( false != ($Handle = opendir($Dir)) ){  
                while( false != ($File = readdir($Handle)) ){  
                    if( $File!='.' && $File!='..' && !strpos($File,'.') ){  
                        $DirArray[] = $File;  
                    }  
                }  
                closedir( $Handle );  
            }  
        }else{  
            $DirArray[] = '[Path]:\''.$Dir.'\' is not a dir or not found!';  
        }  
        return $DirArray;  
    }  
  
    //��ȡ�ļ��б�  
    public function getFile( & $Dir ){  
        if( is_dir($Dir) ){  
            if( false != ($Handle = opendir($Dir)) ) {  
                while( false != ($File = readdir($Handle)) ){  
                    if( $File!='.' && $File!='..' && strpos($File,'.') ){  
                        $FileArray[] = $File;  
                    }  
                }  
                closedir( $Handle );  
            }  
        }else{  
            $FileArray[] = '[Path]:\''.$Dir.'\' is not a dir or not found!';  
        }  
        return $FileArray;  
    }  
  
    //��ȡĿ¼/�ļ��б�  
    public function getDirFile( & $Dir ){  
        if( is_dir($Dir) ){  
            $DirFileArray['DirList'] = $this->getDir( $Dir );  
            if( $DirFileArray ){  
                foreach( $DirFileArray['DirList'] as $Handle ){  
                    $File = $Dir.DS.$Handle;  
                    $DirFileArray['FileList'][$Handle] = $this->getFile( $File );  
                }  
            }  
        }else{  
            $DirFileArray[] = '[Path]:\''.$Dir.'\' is not a dir or not found!';  
        }  
        return $DirFileArray;  
    }  
  
}  
?>   