<?php

use App\Database;

use Illuminate\Support\Facades\Storage;


if (!function_exists('isJapanese')) {
    function isKanji($str){
        return preg_match('/[\x{4E00}-\x{9FBF}]/u', $str) > 0;
    }
}

if (!function_exists('isJapanese')) {
    function isHiragana($str){
        return preg_match('/[\x{3040}-\x{309F}]/u', $str) > 0;
    }
}

if (!function_exists('isJapanese')) {
    function isKatakana($str){
        return preg_match('/[\x{30A0}-\x{30FF}]/u', $str) > 0;
    }
}

if (!function_exists('isJapanese')) {
    function isJapanese($str){
        return isKanji($str) || isHiragana($str) || isKatakana($str);
    }
}

if (!function_exists('renameTitle')) {
    function renameTitle($string){
        if (!isJapanese($string)) {
            $search  = array(
                '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
                '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
                '#(ì|í|ị|ỉ|ĩ)#',
                '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
                '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
                '#(ỳ|ý|ỵ|ỷ|ỹ)#',
                '#(đ)#',
                '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
                '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
                '#(Ì|Í|Ị|Ỉ|Ĩ)#',
                '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
                '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
                '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
                '#(Đ)#',
                '/[^a-zA-Z0-9\-\_]/'
            );
            $replace = array(
                'a',
                'e',
                'i',
                'o',
                'u',
                'y',
                'd',
                'A',
                'E',
                'I',
                'O',
                'U',
                'Y',
                'D',
                '-'
            );
            $string  = preg_replace($search, $replace, $string);
            $string  = preg_replace('/(-)+/', '-', $string);
            $string  = strtolower($string);
        }
        return $string;
    }
}

if (!function_exists('getIcon')) {
    function getIcon($file){
        switch ($file) {
            case 'content':
            $icon = 'edit';
            break;
            case 'config':
            $icon = 'cog';
            break;
            case 'shop':
            $icon = 'shopping-cart';
            break;
            case 'picture':
            $icon = 'picture-o';
            break;
            case 'video':
            $icon = 'video-camera';
            break;
            case 'service':
            $icon = 'list';
            break;
            case 'customer':
            $icon = 'users';
            break;
            case 'backlink':
            $icon = 'link';
            break;
            case 'mod':
            $icon = 'user-secret';
            break;
            case 'home':
            $icon = 'dashboard';
            break;
            case 'lang':
            $icon = 'language';
            break;
            case 'info':
            $icon = 'info';
            break;
            case 'user':
            $icon = 'user';
            break;
            case 'map':
            $icon = 'map-marker';
            break;
            case 'support':
            $icon = 'user';
            break;
            case 'download':
            $icon = 'download';
            break;
            case 'search':
            $icon = 'search';
            break;
            case 'post':
            $icon = 'upload';
            break;
            case 'design':
            $icon = 'paint-brush';
            break;
            case 'news':
            $icon = 'newspaper-o';
            break;
            case 'contact':
            $icon = 'phone';
            break;
            case 'product':
            $icon = 'list-alt';
            break;
            default:
            $icon = 'file-text';
            break;
        }
        return 'fa fa-' . $icon;
    }
}

if (!function_exists('convertLinkYoutube')) {
    function convertLinkYoutube($url){
        if (strpos($url, 'https://www.youtube.com/embed/') !== FALSE) {
            $rt = $url;
        } else if (strpos($url, 'https://www.youtube.com/watch?v=') !== FALSE) {
            $parts = parse_url($url);
            parse_str($parts['query'], $query);
            $rt = 'https://www.youtube.com/embed/' . $query['v'];
        }
        return $rt;
    }
}
if (!function_exists('linkMenu')) {
    function linkMenu($menu, $prefix = ''){
        $db = new Database;
        $slug = $db->alone_data_where("slug", [["tableName", "menu"], ["idTable", $menu->id]]);
        if(!is_null($slug)){
            $lang = session::has("lang") ? session('lang') . '/' : '';
            $link = 'href=' . $lang . $prefix . $slug->slugName . ' ';
            $link .= 'data-name="' . $menu->name . '" ';
            $link .= 'data-title="' . $menu->title . '" ';
            return $link;
        }
        return '';
    }
}

if (!function_exists('linkMenuChild')) {
    function linkMenuChild($menu, $name, $prefix = ''){
        $db = new Database;
        $slug = $db->alone_data_where("slug", [["tableName", "menu"], ["idTable", $menu->id]]);
        if(!is_null($slug)){
            $lang = session::has("lang") ? session('lang') . '/' : '';
            $link = 'href=' . $lang . $prefix . $slug->slugName . ' ';
            $link .= 'data-name="' . $name . '" ';
            $link .= 'data-idMenuChild="' . $menu->id . '" ';
            $link .= 'data-title="' . $menu->title . '" ';
            return $link;
        }
        return '';
    }
}

if (!function_exists('linkId')) {
    function linkId($data, $name, $prefix = ''){
        $db = new Database;
        $slug = $db->alone_data_where("slug", [["tableName", "data"], ["idTable", $data->id]]);
        if(!is_null($slug)){
         $lang = session::has("lang") ? session('lang') . '/' : '';
         $link = 'href=' . $lang . $prefix . $slug->slugName . ' ';
         $link .= 'data-id="' . $data->id . '" ';
         $link .= 'data-name="' . $name . '" ';
         $link .= 'data-title="' . $data->title . '" ';
         $link .= 'title="' . $data->title . '" ';
         $link .= 'alt="' . $data->title . '" ';
         return $link;
     }
     return '';
 }
}

if(!function_exists('srcImg')){
    function srcImg($data, $name = 'img', $resize = ''){
        if(isset($data->$name) && ($data->$name !== '')){
            $img = URL::asset('storage/app/public/'.$data->$name);
        }else{
            $img = URL::asset('public/admin/assets/images/404.png');
        }
        $resizeImage = '';
        if(is_array($resize)){
            $resizeImage = '?';
            foreach($resize as $key => $value){
                if(strlen($value) >= 1){
                    $resizeImage .= $key.'='.$value;
                    if($key != count($resize) - 1){
                        $resizeImage .= '&';
                    }
                }
            }
        }
        return 'src="'.$img.$resizeImage.'" alt="'.$data->title.'" title="'.$data->title.'" ' ;
    }
}

if(!function_exists('linkDelId')){
    function linkDelId($id,$table = 'data'){
        $link = 'data-action="del" ';
        $link.= 'data-table="'.$table.'" ';
        $link.= 'data-id="'.$id.'" ';
        $link.= 'class="btn btn-danger btn-sm btnAjax confirm" ';
        $link.= 'type="button" ';
        return $link;
    }
}

if(!function_exists('linkAdd')){
    function linkAdd($table,$parent = '',$id = ''){
        $link = 'data-action="add" ';
        $link.= 'data-table="'.$table.'" ';
        if($parent !== '' && $id !== ''){
            $link.= 'data-'.$parent.'="'.$id.'" ';
        }
        $link.= 'class="btn btn-info btn-sm btnAjax" ';
        $link.= 'type="button" ';
        return $link;
    }
}

if(!function_exists('linkAddMenu')){
    function linkAddMenu($id){
        $link = 'data-action="add" ';
        $link.= 'data-table="menu" ';
        $link.= 'data-menu_parent="'.$id.'" ';
        $link.= 'class="btn btn-info btn-sm btnAjax" ';
        $link.= 'type="button" ';
        return $link;
    }
}
if(!function_exists('checkObject')){
    function checkObject($object,$key,$value){
        foreach($object as $data){
            if($value == $data->$key){
                return true;
            }
        }
    }
}
if(!function_exists('returnWhereArray')){
    function returnWhereArray($active,$dataActive,$array){
        $myArray = explode(',',$array);
        if (in_array($dataActive, $myArray)){
            return $active;
        }
    }
}
if(!function_exists('returnWhere')){
    function returnWhere($string,$data,$where){
        if($data == $where){
            return $string;
        }
    }
}

