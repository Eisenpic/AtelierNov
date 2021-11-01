<?php
namespace mf\utils;

class ClassLoader extends AbstractClassLoader{
    
    public function __construct($file_root){
        $this->prefix = $file_root;
    }

    public function loadClass(string $classname){
        $fileName = $this->getFilename($classname);
        $chemin = $this->makePath($fileName);
        if(file_exists($chemin)){
            require_once $chemin;
        }
    }

    protected function makePath(string $filename): string{
        $chaine = $this->prefix . DIRECTORY_SEPARATOR . $filename;
        return $chaine;
    }

    protected function getFilename(string $classname): string{
        $chaine = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
        $chaine = $chaine . ".php";
        return $chaine;
    }

}
?>