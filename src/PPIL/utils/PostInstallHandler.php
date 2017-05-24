<?php
namespace PPIL\utils;
use Composer\Script\Event;

class PostInstallHandler
{

    static public function postInstall(Event $event)
    {
        self::installBootstrap($event);
        self::makeImportDir($event);
    }

    static private function installBootstrap(Event $event)
    {
        $event->getIO()->write('<info>Copying bootstrap assets</info>');

        $options = self::getOptions($event);
        $webDir = $options['assets_dir'] ;

        if (!is_dir($webDir)) {
            self::createDirectory("$webDir");
        }

        $webDir = $options['assets_dir'] . "/bootstrap" ;

        if (!is_dir($webDir)) {
            self::createDirectory("$webDir");
        }

        $bootstrapDir = "vendor/twbs/bootstrap/dist";

        self::createDirectory("$webDir/css");
        self::createDirectory("$webDir/fonts");
        self::createDirectory("$webDir/js");
        self::copyFile($bootstrapDir."/css", $bootstrapDir."/css/", $webDir."/css/");
        self::copyFile($bootstrapDir."/fonts", $bootstrapDir."/fonts/", $webDir."/fonts/");
        self::copyFile($bootstrapDir."/js", $bootstrapDir."/js/", $webDir."/js/");
        $event->getIO()->write('<info>Finished copying bootstrap assets</info>');
    }

    static private function copyFile($files, $source, $destination){
        foreach ( scandir($files) as $file ) {
            if (!in_array($file, array(".",".."))){
                copy($source.$file, $destination.$file);
            }
        }
    }

    static private function createDirectory($name)
    {
        if (!is_dir($name)) {
            mkdir($name, 0755, true);
        }
    }

    static protected function getOptions(Event $event)
    {
        $options = $event->getComposer()->getPackage()->getExtra();

        return $options;
    }

    static private function makeImportDir(Event $event)
    {
        $event->getIO()->write('<info>Create empty import directory</info>');

        $webDir = "imports" ;

        if (!is_dir($webDir)) {
            self::createDirectory("$webDir");
        }

    }

}