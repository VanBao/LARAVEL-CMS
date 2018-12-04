<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Database;

use App;

use Carbon\Carbon;

use Lang;

use File;

use View;

use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    const USER = 1;

    const ADMIN = 2;

    const CONFIG = 3;

    private $database;

    private $menuData;

    public function __construct(){
        $this->database = new Database;
        $this->menuData = array();
    }

    public function showUser(Request $request){
        $routeParam = $request->route()->parameters();
        $slug = isset($routeParam["slug"]) ? $routeParam["slug"] : "";
        $lang = isset($routeParam["lang"]) ? $routeParam["lang"] : "";
        $currPage = isset($routeParam["currPage"]) ? $routeParam["currPage"] : "";
        $this->parseSlug($request, $slug, $lang, $currPage, $isConfig = false);
        $this->getData();
        return view($this->menuData["configMenu"]->customHtml, $this->menuData);
    }

    public function showAdmin(Request $request){
        $routeParam = $request->route()->parameters();
        $slug = isset($routeParam["slug"]) ? $routeParam["slug"] : "";
        $lang = isset($routeParam["lang"]) ? $routeParam["lang"] : "";
        $currPage = isset($routeParam["currPage"]) ? $routeParam["currPage"] : "";
        $this->parseSlug($request, $slug, $lang, $currPage, $isConfig = false);
        $this->getData(self::ADMIN);
        if (View::exists('admin.pages.'.$this->menuData["menuPage"]->file)){
            return view('admin.pages.'.$this->menuData["menuPage"]->file, $this->menuData);
        }else{
            return view('admin.pages.edit', $this->menuData);
        }
    }

    public function showConfig(Request $request, $slug = ''){

    }

    public function parseSlug(Request $request, $slug, $lang, $currPage, $isConfig){

        try{
            if(!$isConfig && $slug == ''){
                $this->menuData["menuPage"] = $this->database->alone_data_where("menu", [["file", "home"]]);
            }else{
                if($isConfig){

                }else{
                    $currentSlug = $this->database->alone_data_where("slug", [["slugName", $slug]]);
                    if(!isset($currentSlug)){
                        throw new \Exception("Slug doesn't exist");
                    }
                    switch ($currentSlug->tableName) {
                        case 'menu':
                        $currentMenu = $this->database->alone_data_where("menu", [["id", $currentSlug->idTable]]);
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

    public function getData($role = self::USER){
        if($role == self::USER){
         $listPage = $this->database->list_data_where('page');
         $this->menuData["infoPage"] = new \stdClass();
         foreach($listPage as $page){
            $key = $page->name;
            if(strlen($key))
            {
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

    }else if($role == self::ADMIN){
       $this->menuData["listAdminMenu"] = $this->database->list_data_where('menu', 'pos', 'ASC', [["menu_parent", 0]]);
       switch ($this->menuData["menuPage"]->file) {
        case 'home':
        $listImageHome = $this->database->list_data_where("file_data", "pos", "ASC", [["type", "listImg"]]);
        if(count($listImageHome)){
            $this->menuData["list"] = new \stdClass();
            foreach($listImageHome as $listImg){
                if(!empty($listImg->name)){
                    $listName = $listImg->name;
                    $this->menuData["list"]->$listName = $this->database->list_data_where("data", "pos", "ASC", [["menu", $this->menuData["menuPage"]->id], ["type", $listName]]);
                }
            }
        }
        break;
        case 'contact':
        break;
        case 'info':

        default:
        break;
    }
}
}

public function postData(Request $request){
    $input = $request->all();
    if(isset($input["action"]) && isset($input["table"])){
        $table = $input["table"];
        $action = $input["action"];
        unset($input["table"]);
        unset($input["action"]);
        if($table == 'data'){
            $input["time"] = Carbon::now()->timestamp;
        }
        switch ($action) {
            case 'add':
            $input["title"] = Lang::get('string.title');
            $this->database->insertData($table, $input);
            if($table == "menu" || ($table == "data" && isset($input["menu"]) && !isset($input["data"]))){
                $this->insertSlug($input["title"], $table, $this->database->getLastId());
            }
            break;
            case 'del':
            if(isset($input["id"])){
                $data = $this->alone_data_where($table, ["id",$input["id"]]);
                $this->deleteData($table, ["id", $input["id"]]);
                $this->deleteData('slug', [["tableName", $table], ["idTable", $input["id"]]]);
                $imagePath = url('/').'public/upload/'.$data->img;
                if(File::exists($imagePath)){
                    File::delete($imagePath);
                }
                switch ($table) {
                    case 'menu':
                    $allListMenuChild = $this->database->allListMenuChild($input["id"]);
                    $listDataChild = $this->allListDataChild([$input["id"]], 0, '', 'id', 'ASC');
                    foreach($allListMenuChild as $menuChild){
                        $this->deleteData("menu", ["id", $menuChild->id]);
                        $this->deleteData("slug", [["tableName", "menu"], ["idTable", $menuChild->id]]);
                    }
                    foreach($listData as $dataChild){
                        $this->deleteData("data", ["id", $dataChild]);
                        $this->deleteData("slug", [["tableName", "data"], ["idTable", $dataChild->id]]);
                    }

                    break;

                    case 'data':
                    $this->database->deleteData("data", ["data_parent", $input["id"]]);
                    break;
                }
            }
            break;
            case 'delAll':
            break;
        }
    }else{
        $arrFiles = $request->allFiles();
        if(count($arrFiles)){
            foreach($arrFiles as $key => $listFile){
                switch ($key) {
                    case 'slideMenu':
                    case 'slide':
                    case 'slide2':
                    if(isset($input["table"]) && isset($input["id"])){
                        foreach($listFile as $file){
                            $fileName = $file->storeAs("upload", $file->getClientOriginalName(), "public");
                            if($input["table"] == 'data'){
                                $this->database->insertData("data", ["data_parent" => $input["id"], "type" => $key, "img" => $fileName]);
                            }else{
                                $this->database->insertData("data", ["menu" => $input["id"], "type" => $key, "img" => $fileName]);
                            }
                        }
                    }
                    break;
                    case 'info':
                    foreach($listFile as $key => $file){
                        $currInfo = $this->database->alone_data_where("page", [["name", $key]]);
                        if(!empty($currInfo)){
                            if($currInfo->content !== ''){
                                Storage::disk('public')->delete($currInfo->content);
                            }
                            $fileName = $file->storeAs("upload", $file->getClientOriginalName(), "public");
                            $this->database->updateDate("page", ["content" => $fileName], [["name" , $key]]);
                        }
                    }
                    break;
                    default:
                    foreach($listFile as $key => $file){
                        $data = $this->database->alone_data_where($input["table"], [["id", $input["id"]]]);
                        if(!empty($data)){
                            if($data->img !== ''){
                                Storage::disk('public')->delete($data->img);
                            }
                            $fileName = $file->storeAs("upload", $file->getClientOriginalName(), "public");
                            $this->database->updateDate($input["table"], ["img" => $fileName], [["id", $input["id"]]]);
                        }
                    }

                    break;
                }
            }
        }
        if(isset($input["listRow"])){
            foreach($input["listRow"] as $table => $row){
                foreach($row as $id => $data){
                    if(isset($data["title"]) && (!isset($data["name"]) || $data["name"] == "")){
                        $data["name"] = renameTitle($data["title"]);
                    }
                    $this->insertData($table, $data, [["id", $id]]);
                }
            }
        }
        if(isset($input["listSlug"])){
            foreach($input["listSlug"] as $id => $slug){
                $slug = ($slug !== '') ? renameTitle($slug) : Lang::get('string.title') . "-" . Carbon::now()->timestamp;
                $this->database->updateSlug($slug, $id);
            }
        }
    }
}
}
