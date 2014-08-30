<?php
/**
 * @author {Stanislav Boyko}
 * @version 0.1
 * */
class Curl{
  //Опции
  /**
  * Записывает полученные данные в переменную
  */
  const CL_TRANSFER = CURLOPT_RETURNTRANSFER;
  const CL_URL = CURLOPT_URL;
  /**
  * Возвращает заголовки
  */
  const CL_HEADER = CURLOPT_HEADER;
  //Возвращает заголовки без тела
  const CL_NOBODY = CURLOPT_NOBODY;
  /**
   * Записывает в файл
   * */
  const CL_FILE = CURLOPT_FILE;
   /**
    * Запись заголовков
    * */
  const CL_WHEADER = CURLOPT_WRITEHEADER;
   /**
    * Пересылка данных методом POST
    * */
  const CL_POST = CURLOPT_POST;
  const CL_POST_FIELDS = CURLOPT_POSTFIELDS;
  
  /**
   * Определять редиирект
   * */
  const CL_FOLLOWLOCATION = CURLOPT_FOLLOWLOCATION;
    
  private $_url;
  private $_init;

  public function __construct(){}

  public function setUrl($url){
    $this->_url = $url;
  }
  public function getUrl(){
    return $this->_url;
  }
  private static function init($url = null){
  	return curl_init($url);
  } 
  private static function close($ch){
  	curl_close($ch);
  }
  /**
   * @param <resource> $ch - ссылка на курл инит
   * @param <string> $const - Имя константы
   * @param <boolean>  $mode (true| false | string)
   **/
  private static function setopt($ch, $const, $mode = true){
  	curl_setopt($ch, self::$const,$mode);
  }
  private static  function exec($ch){
  	return curl_exec($ch);
  }
  /**
  * Простой запрос
  */
  public function init(){
   $init = self::init($this->_url);
   curl_setopt($init, self::CL_HEADER, true);
   curl_setopt($init, self::CL_TRANSFER, true);
   $this->_init = curl_exec($init);
   self::close($init);
   
   return $this->_init;
  }
  /**
   * Запись выбранной строки в файл
   * @param <string> $path - путь до файла
   * @param <string> $mode - мод, режим открытия файла
   * */
   public function ToFile($file, $mode = "w"){
   	$init = self::init();
	$fp = fopen($file, $mode);
	self::setopt($init, 'CL_URL', $this->_url);
	self::setopt($init, 'CL_FILE', $fp);
	//execute
	self::exec($init);
	//close
	self::close($init);
   }
  /**
   * Запись выбранной строки в файл + заголовки
   * @param <array> $path = array('path_to_file' => 'путь до файла куда запишется тело', 
   *                              'path_to_headers' => 'путь до файла, куда запишутся заголовки');
   * @param <string> $mode - мод, режим открытия файла
   * */ 
   public function ToFileAndHeaders($file, $mode = "w"){
   	 $init = self::init();
	 self::setopt($init, 'CL_URL', $this->_url);
	 self::setopt($init, 'CL_FILE', $file['path_to_file']);
	 self::setopt($init, 'CL_WHEADER', $file['path_to_headers']);
	 //execute
	 self::exec($init);
	 //close
	 self::close($init); 
   }
   /**
    * Посылаем данные на сервер методом POST
    * @param <string> $params - Параметры, для передачи в формате. "class=curl&name=Stanislav" - где name(ключ) - Stanislav (значение) 
    **/
    public function initPost($params){
    	$init = self::init($this->_url);
		self::setopt($init, 'CL_POST');
		self::setopt($init, 'CL_POST_FIELDS', $params);
		self::exec($init);
		self::close($init);
    }
    
}


?>
