<?php
namespace console\commands\helpers;

use compact\repository\json\JsonRepository;
use compact\repository\DefaultModelConfiguration;

/**
 * @author eaboxt
 */
class AliasHelper
{
    const FIELD_ALIAS = "alias";
    const FIELD_COMMAND = "command";
        
    private static $repository;
    
    
    /**
     * Add a command to the alias list
     *
     * @param string $alias
     * @param string $command
     */
    public static function add($alias, $command){
       $repository = self::getRepository();
       $sc = $repository->createSearchCriteria();
       $sc->where(self::FIELD_ALIAS, $alias);
       
       if ($repository->search($sc)->count() === 0){
           $model = $repository->createModel();
           $model->set(self::FIELD_ALIAS, $alias);
           $model->set(self::FIELD_COMMAND, $command);
           
           $repository->save($model);
       }
    }
    
    
    /**
     * Returns the repository for the aliasses 
     * 
     * @return compact\repository\IModelRepository
     */
    public static function getRepository(){
        if (self::$repository === null){
            self::$repository = new JsonRepository(new DefaultModelConfiguration('\\compact\\mvvm\impl\\Model', 'guid'), new \SplFileInfo(__DIR__."/phpconsole-aliasses.json"));
        }
        
        return self::$repository;
    }
    
    
    /**
     * 
     * @param string $alias The alias
     * 
     * @return string the command mapped to the alias or null when nothing was found
     */
    public static function getCommandFor($alias){

        $command = null;
        
        $repository = self::getRepository();
        $sc = $repository->createSearchCriteria();
        $sc->where(self::FIELD_ALIAS, $alias);
       
        $result = $repository->search($sc);
        if ($result->count() > 0){
            $command = $result->offsetGet(0)->get(self::FIELD_COMMAND);
        }
       
        return $command;
    }
}
