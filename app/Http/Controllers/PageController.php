<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Database;

use App;

class PageController extends Controller
{

    private $database;

    private $menuData;

    public function __construct()
    {
        $this->database = new Database;
        $this->menuData = array();
    }

    public function showUser(Request $request, $slug = '', $currPage = '')
    {
        var_dump($request->query("aa"));
        $this->parseSlug($request, $slug, $lang = '', $currPage, $isAdmin = false, $isConfig = false);
        $this->getUserMenuData();
        return view($this->menuData["configMenu"]->customHtml, $this->menuData);
    }

    public function showUserWithLang(Request $request, $lang, $slug = '', $currPage = '')
    {
        $this->parseSlug($request, $slug, $lang, $currPage, $isAdmin = false, $isConfig = false);
        $this->getUserMenuData();
        return view($this->menuData["configMenu"]->customHtml, $this->menuData);
    }

    public function showAdmin(Request $request, $slug = '')
    {
        $this->parseSlug($request, $slug, $lang = '', $currPage = '', $isAdmin = false, $isConfig = false);


    }
    public function showAdminWithLang(Request $request, $lang, $slug = '')
    {
        $this->parseSlug($request, $slug, $lang, $currPage = '', $isAdmin = false, $isConfig = false);


    }

    public function showConfig(Request $request, $slug = '')
    {

    }

    public function showConfigWithLang(Request $request, $lang, $slug = '')
    {

    }

    public function parseSlug(Request $request, $slug, $lang, $currPage, $isAdmin, $isConfig)
    {

        try{
            if(!$isConfig && $slug == ''){
                $this->menuData["menuPage"] = $this->database->alone_data_where("menu", [["file", "=", "home"]]);
            }else{
                if($isConfig){

                }else{
                    $currentSlug = $this->database->alone_data_where("slug", [["slugName", "=", $slug]]);
                    if(!isset($currentSlug)){
                        throw new \Exception("Slug doesn't exist");
                    }
                    switch ($currentSlug->tableName) {
                        case 'menu':
                        $currentMenu = $this->database->alone_data_where("menu", [["id", "=", $currentSlug->idTable]]);
                        if($currentMenu->menu_parent != 0){
                            $menuParent = $this->database->menuParent($currentMenu->id);
                            $this->menuData["menuChild"] = $currentMenu;
                            $this->menuData["menuPage"] = $menuParent;

                        }else{
                            $this->menuData["menuPage"] = $currentMenu;
                        }
                        break;
                        case 'data';
                        $this->menuData["page"] = $this->database->alone_data_where("data", [["id", $currentSlug->idTable]]);
                        $this->menuData["menuPage"] = $this->database->menuParent($this->page->menu);
                        break;
                        default:
                        throw new \Exception("Table doesn't exist");
                        break;
                    }
                }
            }
            if(isset($this->menuData["menuPage"])){
                $configMenu = $this->database->alone_data_where('file', [["file", $this->menuData["menuPage"]->file]]);
                if(isset($configMenu)){
                    $listConfigAdd = $this->database->list_data_where("config", "id", "ASC", [["type", "add"], ["file", "idList"]]);
                    foreach($listConfigAdd as $configAdd){
                        $nameAdd = $configAdd->name;
                        $configMenu->$nameAdd = $this->database->list_data_where("file_data", "pos", "ASC",[["parent", $configAdd->id], ["group", $nameAdd]]);
                    }
                }
                $this->menuData["configMenu"] = $configMenu;
            }
        }catch(\Exception $e){
            abort(404);
        }
    }

    public function setInfo($data){
        $this->menuData["title"] = $data->title;
        if(strlen($data->img)) $this->menuData["image"] = $data->img;
        if(strlen($data->des)) $this->menuData["des"] = $data->des;
        if(strlen($data->keywords)) $this->menuData["keywords"] = $data->keywords;
    }

    public function getUserMenuData(){
        $listPage = $this->database->list_data_where('page');
        $this->menuData["infoPage"] = new \stdClass();
        foreach($listPage as $page){
            $key = $page->name;
            if(strlen($key)){
                $this->menuData["infoPage"]->$key = $page->content;
            }
        }
        $this->menuData["listMenu"] = $this->database->list_data_where("menu", "pos", "ASC", [["menu_parent", 0], ["hide", 0]]); 
        $this->menuData["title"] = $this->menuData["infoPage"]->title;
        $this->menuData["image"] = $this->menuData["infoPage"]->logo;
        $this->menuData["des"] = $this->menuData["infoPage"]->des;
        $this->menuData["keywords"] = $this->menuData["infoPage"]->keywords;
        if(isset($this->menuData["page"])){
            $this->setInfo($this->menuData["page"]);
        }else if(isset($this->menuData["menuChild"])){
            $this->setInfo($this->menuData["menuChild"]);
        }else{
            $this->setInfo($this->menuData["menuPage"]);
        }
        // continue
    }

    public function postData();
}
