<?php 
namespace App;

use App\Page;

use App\User;

use App\Menu;

use App\Slug;

use App\Province;

use App\Config;

use App\Counter;

use App\District;

use App\Data;

use App\File;

use App\FileData;

use Session;

class Database 
{

	private $model;

	public function __construct(){
		$this->model = null;
	}

	private function setModel($modelName){
		switch ($modelName) {
			case 'page':
				$this->model = new Page;
				break;
			case 'user':
				$this->model = new User;
				break;
			case 'menu':
				$this->model = new Menu;
				break;
			case 'slug':
				$this->model = new Slug;
				break;
			case 'province':
				$this->model = new Province;
				break;	
			case 'config':
				$this->model = new Config;
				break;
			case 'counter':
				$this->model = new Counter;
				break;	
			case 'district':
				$this->model = new District;
				break;
			case 'data':
				$this->model = new Data;
				break;
			case 'file':
				$this->model = new File;
				break;
			case 'file_data':
				$this->model = new FileData;
				break;		
		}

		$this->model = $this->model::on((session::has("lang")) ? "mysql_".session("lang") : "mysql_vn");

	}

	public function alone_data_where($model, $arrWhere){
		$this->setModel($model);
		return $this->model->where($arrWhere)->first();
	}

	public function list_data_where($model, $by = 'id', $order = 'ASC', $arrWhere = array()){
		$this->setModel($model);
		if(count($arrWhere) != 0){
			$this->model->where($arrWhere);
		} 
		return $this->model->orderBy($by, $order)->get();
	}

	public function delete($model, $where, $operator, $value){
		$this->setModel($model);
		return $this->model->where($where, $operator, $value)->delete();
	}

	public function allListDataChild($arrMenu, $start = 0, $limit = '', $by = 'id', $order = 'ASC', $arrWhere = array()){
		$this->setModel("data");
		$this->model = $this->model->where(function($query) use ($arrMenu){
			foreach($arrMenu as $menu){
				$query->orWhere("menu", $menu);
			}
		});
		if(count($arrWhere) != 0){
			$this->model = $this->model->where($arrWhere);
		}
		$this->model->orderBy($by, $order);
		if(is_numeric($limit)){
			$this->model = $this->model->offset($start)->limit($limit);
		}
		return $this->model->get();
	}

	public function allListMenuParent($menuId, $allListMenuParent = array()){
		$currentMenu = $this->alone_data_where("menu", "id", "=", $menuId);
		if(!is_null($currentMenu) && $currentMenu->menu_parent != '0'){
			$menuParent = $this->alone_data_where("menu", "id", $currentMenu->menu_parent);
			$allListMenuParent[] = $menuParent;
			$allListMenuParent = $this->allListMenuParent($currentMenu->menu_parent, $allListMenuParent);
		}
		return $allListMenuParent;
	}

	public function menuParent($menuId){
		$allListMenuParent = $this->allListMenuParent($menuId);
		return end($allListMenuParent);
	}

	public function listMenuChild($menuId){
		return $this->list_data_where("menu", "id", "ASC", ["menu", $menuId]);
	}

	public function allListMenuChild($menuId){

	}

}

?>