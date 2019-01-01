<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Database;
use App;
use Carbon\Carbon;
use Lang;
use File;
use View;
use Session;
use Illuminate\Support\Facades\Storage;
class PageController extends Controller {
    const USER = 1;
    const ADMIN = 2;
    const CONFIG = 3;
    private $database;
    private $menuData;
    public function __construct() {
        $this->database = new Database;
        $this->menuData = array();
    }
    public function showUser(Request $request) {
        $routeParam = $request->route()->parameters();
        $slug = isset($routeParam["slug"]) ? $routeParam["slug"] : "";
        $lang = isset($routeParam["lang"]) ? $routeParam["lang"] : "";
        $currPage = isset($routeParam["currPage"]) ? $routeParam["currPage"] : "";
        $this->parseSlug($request, $slug, $lang, $currPage, $isConfig = false);
        $this->getData();
        return view($this->menuData["configMenu"]->customHtml, $this->menuData);
    }
    public function showAdmin(Request $request) {
        if($request->ajax()){
            $this->postData($request);
        }
        $routeParam = $request->route()->parameters();
        $slug = isset($routeParam["slug"]) ? $routeParam["slug"] : "";
        $lang = isset($routeParam["lang"]) ? $routeParam["lang"] : "";
        $currPage = isset($routeParam["currPage"]) ? $routeParam["currPage"] : "";
        $this->parseSlug($request, $slug, $lang, $currPage, $isConfig = false);
        $this->getData(self::ADMIN);
        if (View::exists('admin.pages.' . $this->menuData["menuPage"]->file)) {
            return view('admin.pages.' . $this->menuData["menuPage"]->file, $this->menuData);
        } else {
            if(isset($page)){
                if($this->menuData["configMenu"]->slide){
                    $this->menuData["listSlide"] = $this->database->list_data_where("data", "pos", "ASC", [["data_parent", $page->id],["type", "slide"]]);
                }
                if($this->menuData["configMenu"]->slide2){
                    $this->menuData["listSlide2"] = $this->database->list_data_where("data", "pos", "ASC", [["data_parent", $page->id],["type", "slide2"]]);
                }
                if($this->menuData["configMenu"]->tab){
                    $this->menuData["listData"] = $this->database->list_data_where("data", "pos", "ASC", [["data_parent", $page->id],["type", ""]]);
                }
                $this->menuData["menuFilter"] = $this->database->alone_data_where("menu", [["file", "filter"]]);
                $this->menuData["listMenuFilter"] = $this->database->listMenuChild($this->menuData["menuFilter"]->id);
            }else{
                if(!isset($this->menuData["menuChild"])){
                    $this->menuData["idList"] = $this->menuData["menuPage"]->id;
                    $this->menuData["menuChild"] = $this->menuData["menuPage"];
                }else{
                    $this->menuData["idList"] = $this->menuData["menuChild"]->id;
                }
                $this->menuData["listData"] = $this->database->list_data_where("data", "pos", "ASC", [["menu", $this->menuData["idList"]]]);
                $this->menuData["listMenuChild"] = $this->database->listMenuChild($this->menuData["idList"]);
            }
            return view('admin.pages.edit', $this->menuData);
        }
        
    }
    public function showConfig(Request $request) {
        if($request->ajax()){
            $this->postData($request);
        }
        $routeParam = $request->route()->parameters();
        $slug = isset($routeParam["slug"]) ? $routeParam["slug"] : "";
        $lang = isset($routeParam["lang"]) ? $routeParam["lang"] : "";
        $currPage = isset($routeParam["currPage"]) ? $routeParam["currPage"] : "";
        $this->parseSlug($request, $slug, $lang, $currPage, $isConfig = true);
        $this->getData(self::CONFIG);
        return view("admin.pages.config", $this->menuData);
    }
    public function parseSlug(Request $request, $slug, $lang, $currPage, $isConfig) {
        try {
            if (!$isConfig && $slug == '') {
                $this->menuData["menuPage"] = $this->database->alone_data_where("menu", [["file", "home"]]);
                $this->menuData["currentSlug"] = $this->database->alone_data_where("slug", [["tableName", "menu"], ["idTable", $this->menuData["menuPage"]->id]]);
            } else {
                if ($isConfig) {
                    if($slug !== ''){
                        $currentSlug = $this->database->alone_data_where("slug", [["slugName", $slug]]);
                        if(!isset($currentSlug) || $currentSlug->tableName !== "menu"){
                            throw new Exception("Slug doesn't exist");
                        }
                        $this->menuData["currentSlug"] = $currentSlug;
                        $this->menuData["menuPage"] = $this->database->alone_data_where("menu", [["id", $currentSlug->idTable]]);
                    }else{
                       $this->menuData["menuPage"] = $this->database->alone_data_where("menu", [["file", "config"]]);
                       $this->menuData["currentSlug"] = $this->database->alone_data_where("slug", [["tableName", "menu"], ["idTable", $this->menuData["menuPage"]->id]]);
                   }
               } else {
                $currentSlug = $this->database->alone_data_where("slug", [["slugName", $slug]]);
                if (!isset($currentSlug)) {
                    throw new \Exception("Slug doesn't exist");
                }
                $this->menuData["currentSlug"] = $currentSlug;
                switch ($currentSlug->tableName) {
                    case 'menu':
                    $currentMenu = $this->database->alone_data_where("menu", [["id", $currentSlug->idTable]]);
                    if ($currentMenu->menu_parent != 0) {
                        $menuParent = $this->database->menuParent($currentMenu->id);
                        $this->menuData["menuChild"] = $currentMenu;
                        $this->menuData["menuPage"] = $menuParent;
                        $this->menuData["allListParentMenu"] = $this->database->allListMenuParent($this->menuData["menuChild"]->id);
                    } else {
                        $this->menuData["menuPage"] = $currentMenu;
                        $this->menuData["allListParentMenu"] = $this->database->allListMenuParent($this->menuData["menuPage"]->id);
                    }
                    break;
                    case 'data';
                    $this->menuData["page"] = $this->database->alone_data_where("data", [["id", $currentSlug->idTable]]);
                    $this->menuData["menuPage"] = $this->database->menuParent($this->menuData["page"]->menu);
                    $this->menuData["allListParentMenu"] = $this->database->allListMenuParent($this->menuData["page"]->id);
                    break;
                    default:
                    throw new \Exception("Table doesn't exist");
                    break;
                }
            }
        }
        if (isset($this->menuData["menuPage"])) {
            $configMenu = $this->database->alone_data_where('file', [["file", $this->menuData["menuPage"]->file]]);
            if(!is_null($configMenu)){
                $configMenu = (object) $configMenu->getAttributes();
                $listConfigAdd = $this->database->list_data_where("config", "id", "ASC", [["type", "add"], ["file", "idList"]]);
                foreach ($listConfigAdd as $configAdd) {
                    $nameAdd = $configAdd->name;
                    $configMenu->$nameAdd = $this->database->list_data_where("file_data", "pos", "ASC", [["parent", $configAdd->id], ["group", $nameAdd]]);
                }
                $this->menuData["configMenu"] = $configMenu;
            }
        }
    }
    catch(\Exception $e) {
        abort(404);
    }
}
public function setInfo($data) {
    $this->menuData["title"] = $data->title;
    $this->menuData["name"] = renameTitle($data->title);
    if (strlen($data->img)) $this->menuData["image"] = $data->img;
    if (strlen($data->des)) $this->menuData["des"] = $data->des;
    if (strlen($data->keywords)) $this->menuData["keywords"] = $data->keywords;
}
public function getData($role = self::USER) {
    $this->menuData["allListMenu"] = $this->database->list_data_where('menu', 'pos', 'ASC', [["menu_parent", "<", 1]]);
    foreach($this->menuData["allListMenu"] as $menu){
        $this->menuData["menu".ucfirst($menu->file)] = $menu;
    }
     $listPage = $this->database->list_data_where('page');
       $this->menuData["infoPage"] = new \stdClass();
       foreach ($listPage as $page) {
        $key = $page->name;
        if (strlen($key)) {
            $this->menuData["infoPage"]->$key = $page->content;
        }
    }
    $this->menuData["title"] = $this->menuData["infoPage"]->title;
    $this->menuData["image"] = $this->menuData["infoPage"]->logo;
    $this->menuData["des"] = $this->menuData["infoPage"]->des;
    $this->menuData["keywords"] = $this->menuData["infoPage"]->keywords;
    if (isset($this->menuData["page"])) {
        $this->setInfo($this->menuData["page"]);
    } else if (isset($this->menuData["menuChild"])) {
        $this->setInfo($this->menuData["menuChild"]);
    } else {
        $this->setInfo($this->menuData["menuPage"]);
    }

    if ($role == self::USER) {
        $this->menuData["listMenu"] = $this->database->list_data_where("menu", "pos", "ASC", [["menu_parent", 0], ["hide", 0]]);
                       // continue

} else if ($role == self::ADMIN) {
    $this->menuData["baseUrl"] = url(session::has("lang") ? session("lang") . "/" : "". "admin");
    $this->menuData["currentUrl"] = url(session::has("lang") ? session("lang") . "/" : "" . "admin/" . $this->menuData["currentSlug"]->slugName);
    $this->menuData["listMenuAdmin"] = $this->database->list_data_where("menu", "pos", "ASC", [["menu_parent", 0]]);
    $this->menuData["allListParentMenu"] = $this->database->allListMenuParent($this->menuData["menuPage"]->id);
    switch ($this->menuData["menuPage"]->file) {
        case 'home':
        $listImageHome = $this->database->list_data_where("file_data", "pos", "ASC", [["type", "listImg"]]);
        if (count($listImageHome)) {
            $this->menuData["listImageHome"] = new \stdClass();
            foreach ($listImageHome as $listImg) {
                if (!empty($listImg->name)) {
                    $listName = $listImg->name;
                    $item = new \stdClass();
                    $item->listImg = $this->database->list_data_where("data", "pos", "ASC", [["menu", $this->menuData["menuPage"]->id], ["type", $listName]]);
                    $item->listTitle = $listImg->title;
                    $this->menuData["listImageHome"]->$listName = $item;
                }
            }
        }
        break;
        case 'contact':
        $this->menuData["listData"] = $this->database->list_data_where("data", "id", "DESC", [["menu", $this->menuData["menuPage"]->id]]);
        break;
        case 'info':
        $this->menuData["listData"] = $this->database->list_data_where("page", "type", "ASC");
        default:
        break;
    }
}else if ($role == self::CONFIG){
    $this->menuData["baseUrl"] = url(session::has("lang") ? session("lang") . "/" : "". "admin/cau-hinh");
    $this->menuData["currentUrl"] = url(session::has("lang") ? session("lang") . "/" : "" . "admin/cau-hinh/" . $this->menuData["currentSlug"]->slugName);
    $this->menuData["isConfig"] = true;
    $this->menuData["listMenuAdmin"] = $this->database->list_data_where("menu", "pos", "ASC", [["menu_parent", '0']]);
    $this->menuData["listMenuAdminConfig"] = $this->database->list_data_where("menu", "pos", "ASC", [["menu_parent", '<', '1']]);
    $this->menuData["allListParentMenu"] = $this->database->allListMenuParent($this->menuData["menuPage"]->id);
    $this->menuData["listFile"] = $this->database->list_data_where("file", 'id', 'ASC', [["hide", 0]]);
    if(View::exists("admin.pages.config.". $this->menuData['menuPage']->file)){
        switch ($this->menuData["menuPage"]->file) {
            case 'config':
                $this->menuData["listData"] = $this->database->list_data_where('config', 'type', 'DESC', [["file", "config"]]);
                $this->menuData["listEditor"] = $this->database->list_data_where('config', 'type', 'DESC', [["file", "config"], ["type", "codeEditor"]]);
                break;
            case 'home':
                $this->menuData["listData"] = $this->database->list_data_where("file_data", "pos", "ASC", [["type", "listImg"], ["parent", $this->menuData["menuPage"]->id]]);
                break;
            case  'info': 
                $this->menuData["listData"] = $this->database->list_data_where("page", "type", "ASC");
                $this->menuData["listType"] = array('img','text','content','file', 'switch');
                break;
            case 'configMenu':
                $this->menuData["listMenu"] = $this->database->list_data_where("menu", "pos", "ASC", [["menu_parent", 0]]);
                $this->menuData["listMenu2"] = $this->database->list_data_where("menu", "pos", "ASC", [["menu_parent", 0], ["hide", 0]]);
                $this->menuData["listFile"] = $this->database->list_data_where("menu", "id", "ASC", [["hide", 0]]);
        }
        $this->menuData["template"] = "admin.pages.config.". $this->menuData['menuPage']->file;
    }else{
        $this->menuData["listConfigCheck"] = $this->database->list_data_where("config", "id", "ASC", [["file", "idList"],["type", ""]]);
        $this->menuData["listConfigAdd"] = $this->database->list_data_where("config", "id", "ASC", [["file", "idList"], ["type", "add"]]);
        $this->menuData["listSizeThumbnail"] = array('maxWidth','maxHeight');
        $this->menuData["listType"] = array("text", "file", "content");
        
        $this->menuData["template"] = "admin.pages.config.idList";
    }
}
}
public function postData(Request $request) {
    $input = $request->all();
    unset($input["_token"]);
    if (isset($input["action"]) && isset($input["table"])) {
        $table = $input["table"];
        $action = $input["action"];
        unset($input["table"]);
        unset($input["action"]);
        if ($table == 'data') {
            $input["time"] = Carbon::now()->timestamp;
        }
        switch ($action) {
            case 'add':
            $input["title"] = Lang::get('string.title');
            $this->database->insertData($table, $input);
            if ($table == "menu" || ($table == "data" && isset($input["menu"]) && !isset($input["data"]))) {
                $this->database->insertSlug($input["title"], $table, $this->database->getLastId());
            }
            break;
            case 'del':
            if (isset($input["id"])) {
                $data = $this->database->alone_data_where($table, [["id", $input["id"]]]);
                $this->database->deleteData($table, [["id", $input["id"]]]);
                $this->database->deleteData('slug', [["tableName", $table], ["idTable", $input["id"]]]);
                $imagePath = url('/') . 'public/upload/' . $data->img;
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
                switch ($table) {
                    case 'menu':
                    $allListMenuChild = $this->database->allListMenuChild($input["id"]);
                    $listDataChild = $this->database->allListDataChild([$input["id"]], 0, '', 'id', 'ASC');
                    foreach ($allListMenuChild as $menuChild) {
                        $this->database->deleteData("menu", [["id", $menuChild->id]]);
                        $this->database->deleteData("slug", [["tableName", "menu"], ["idTable", $menuChild->id]]);
                    }
                    foreach ($listDataChild as $dataChild) {
                        $this->database->deleteData("data", [["id", $dataChild]]);
                        $this->database->deleteData("slug", [["tableName", "data"], ["idTable", $dataChild->id]]);
                    }
                    break;
                    case 'data':
                    $this->database->deleteData("data", [["data_parent", $input["id"]]]);
                    break;
                }
            }
            break;
            case 'delAll':
            break;
        }
    } else {
        $arrFiles = $request->allFiles();
        foreach($arrFiles as $key => $value){
            unset($input[$key]);
        }
        if (count($arrFiles)) {
            foreach ($arrFiles as $key => $value) {
                switch ($key) {
                    case 'listImageType':
                    case 'slide':
                    case 'slide2':
                    if (isset($input["table"]) && isset($input["id"])) {
                        foreach($value as $key2 => $listFile){
                            foreach ($listFile as $file) {
                                $fileName = $file->storeAs("upload", $file->getClientOriginalName(), "public");
                                if ($input["table"] == 'data') {
                                    $this->database->insertData("data", ["data_parent" => $input["id"], "type" => $key2, "img" => $fileName]);
                                } else {
                                    $this->database->insertData("data", ["menu" => $input["id"], "type" => $key2, "img" => $fileName]);
                                }
                            }
                        }
                    }
                    break;
                    case 'info':
                    foreach ($value as $key => $file) {
                        $currInfo = $this->database->alone_data_where("page", [["name", $key]]);
                        if (!empty($currInfo)) {
                            if ($currInfo->content !== '') {
                                Storage::disk('public')->delete($currInfo->content);
                            }
                            $fileName = $file->storeAs("upload", $file->getClientOriginalName(), "public");
                            $this->database->updateData("page", ["content" => $fileName], [["name", $key]]);
                        }
                    }
                    break;
                    default:
                    $data = $this->database->alone_data_where($input["table"], [["id", $input["id"]]]);
                    if (!empty($data)) {
                        if ($data->img !== '') {
                            Storage::disk('public')->delete($data->img);
                        }
                        $fileName = $value->storeAs("upload", $value->getClientOriginalName(), "public");
                        $this->database->updateData($input["table"], ["img" => $fileName], [["id", $input["id"]]]);
                    }
                    break;
                }
            }
        }

        if (isset($input["listRow"])) {
            foreach ($input["listRow"] as $table => $row) {
                foreach ($row as $id => $data) {
                    if (isset($data["title"]) && (!isset($data["name"]) || $data["name"] == "")) {
                        $data["name"] = renameTitle($data["title"]);
                    }
                    $this->database->updateData($table, $data, [["id", $id]]);
                }
            }
            unset($input["listRow"]);
        }
        if (isset($input["listSlug"])) {
            foreach ($input["listSlug"] as $id => $slug) {
                $slug = ($slug !== '') ? renameTitle($slug) : Lang::get('string.title') . "-" . Carbon::now()->timestamp;
                $this->database->updateSlug($slug, $id);
            }
            unset($input["listSlug"]);
        }

        if(isset($input["table"])){
            $table = $input["table"];
            $id = $input["id"];
            unset($input["table"]);
            unset($input["id"]);
            $this->database->updateData($table, $input, [["id", $id]]);
        }
    }
}

public function logoutAdmin(Request $request){
    $user = $request->cookie('user');
    $password = $request->cookie('password');
    if(!empty($user) && !empty($password)){
        Cookie::forget('user');
        Cookie::forget('password');
    }
}

public function generateSitemap(){

}
}
