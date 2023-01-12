<?
require_once(dirname(__FILE__)."/Comment.php");

class Page extends CI_Controller {

	private $m_pagesize=20;	//每頁筆數
	private $mysql;
	private $total;
	private $MaxNum=5;  	//顯示頁數
	private $CenterNum=3;	//中間第幾位
	public function __construct(){
		$this->CI =& get_instance();
		//$this->cf=$cf;
	}
	//設定每頁筆數
	function SetPagSize($pagesize)
	{
		$this->m_pagesize=$pagesize;
	}
	//設定資料庫連線
	function SetMySQL($mysql)
	{
		$this->m_mysql=$mysql;
	}
	//若有值 為特殊處理資料-資料總數
	function SetTotal($total)
	{
		$this->m_total=$total;
	}
	// 分頁設置
	function PageStar($sql,$ToPage="",$Where=""){

		$PageBox=array();
		if($ToPage==""){
			$ToPage=(integer)comment::Set_GET("ToPage");
		}
		if($ToPage==""){
			$ToPage=(integer)comment::SetValue("ToPage");
		}
		if(!empty($this->CI->autoful->PageNum)){
			$ToPage=$this->CI->autoful->PageNum;
		}
		$_SESSION[CCODE::ADMIN]['PageNum1']=$ToPage;
		// print_r($ToPage);
		//目前頁數
		if(empty($ToPage)){
			$PageBox["CurrectPage"]=1;
		}
		else{
			if(preg_match("/[0-9]{".strlen($ToPage).",}/",$ToPage)){
				if($ToPage<1){
					$PageBox["CurrectPage"]=1;
				}
				else{
					$PageBox["CurrectPage"]=$ToPage;
				}
			}
			else{
				$PageBox["CurrectPage"]=1;
			}
		}
		// $command="SELECT count(*) as num FROM ".$sql." ";
		$command="SELECT * FROM ".$sql." ";
		// 進階搜尋
		if(!empty($Where)){
			$command.=$Where;
		}
		$result=$this->m_mysql->query($command);
		
		$total_num=0;
		// foreach ($result->result_array() as $record){
		// 	$total_num=$record["num"];
		// }
		foreach ($result->result_array() as $record){
			$total_num++;
		}
		if(!empty($this->m_total)){
			$total_num=$this->m_total;
		}
		//總筆數
		$PageBox["TotalRecord"]=$total_num;
		
		//全顯示
		if($this->m_pagesize=="all"){
			$this->m_pagesize=$PageBox["TotalRecord"];
		}
		$PageBox["pagesize"]=$this->m_pagesize;
		//總頁數
		if(empty($total_num) or empty($this->m_pagesize)){
			$PageBox["TotalPage"]=0;
		}
		else{
			$PageBox["TotalPage"]=ceil($total_num/$this->m_pagesize);
		}
		
		if($PageBox["TotalPage"]!=0){
			if($PageBox["TotalPage"]<$PageBox["CurrectPage"]){
				$PageBox["CurrectPage"]=$PageBox["TotalPage"];
			}
		}
		else{
			$PageBox["CurrectPage"]=1;
		}
		//開始記錄
		$PageStar=(($PageBox["CurrectPage"]-1)*$this->m_pagesize);
		//sql語法
		$PageBox["result"]=" limit ".$PageStar.",".$this->m_pagesize."";
		//顯示資訊
		$PageBox["PageView"]="共".$PageBox["TotalPage"]."頁  共 ".$PageBox["TotalRecord"]."筆  目前在第".$PageBox["CurrectPage"]."頁";
		//到第幾頁內容
		//$PageBox["PageTo"]=$this->PageTo($PageBox["TotalRecord"]);
        $pt=$this->PageTo($PageBox["TotalRecord"]);
		$PageBox["PageTo"]=$pt["PageTo"];
		$PageBox["PageTo1"]=$pt["PageTo1"];
		//連結到第幾頁內容
		$PageBox["PageToLink"]=$this->PageToLink($PageBox);
		//Ajax跳頁資料
		//$PageBox["AjaxPage"]=$this->AjaxPage($PageBox);
		return $PageBox;
	}
	//每頁幾筆
	function PageTo(&$TotalRecord=0){
		$PageNumber=0;
		$string="";
		if($TotalRecord>100){
			$PageNumber=ceil($TotalRecord/100)+5;
			if($TotalRecord>=500){
				$PageNumber=10;
			}
		}
		elseif($TotalRecord<=50){
			$PageNumber=ceil($TotalRecord/10);
		}
		for($i=1;$i<=$PageNumber;$i++){
			if($i>5){
				$num=($i-5)*100;
			}
			else{
				$num=$i*10;
			}
			$string.="<option value=\"".$num."\">每頁".$num."筆</option>";
		}
		//return $string;
		return array("PageTo"=>$string,"PageTo1"=>$PageNumber);
	}
	function PageToLink($PageBox){
		if($PageBox["TotalPage"]==1){
			$a_array["prev_one"] = 1;
			$a_array["next_one"] = 1;
		}
		elseif($PageBox["CurrectPage"]==1){
			$a_array["prev_one"] = 1;
			$a_array["next_one"] = $PageBox["CurrectPage"] + 1;
		}
		elseif($PageBox["CurrectPage"]==$PageBox["TotalPage"]){
			$a_array["prev_one"] = $PageBox["CurrectPage"] - 1;
			$a_array["next_one"] = $PageBox["TotalPage"];
		}
		else{
			$a_array["prev_one"] = $PageBox["CurrectPage"] - 1;
			$a_array["next_one"] = $PageBox["CurrectPage"] + 1;
		}
		if($this->MaxNum<=$this->CenterNum){$this->CenterNum=ceil(($this->MaxNum+1)/2);}
		if($a_array["prev_one"]<1){$a_array["prev_one"]=1;}//上一頁
		if(($a_array["next_one"]>$PageBox["TotalPage"]) and ($PageBox["TotalPage"]!=0)){$a_array["next_one"]=$PageBox["TotalPage"];}//下一頁
		$a_array["pstar"]=$PageBox["CurrectPage"]-$this->CenterNum+1;//開始頁數
		$a_array["pend"]=$PageBox["CurrectPage"]+$this->MaxNum-$this->CenterNum;//結束頁數
	
		if($a_array["pstar"]<1){$a_array["pend"]-=($a_array["pstar"]-1);}
		if($a_array["pend"]>$PageBox["TotalPage"]){$a_array["pstar"]-=($a_array["pend"]-$PageBox["TotalPage"]);}
	
		if(($a_array["pstar"]<1) or ($PageBox["TotalPage"]<$a_array["pstar"])){$a_array["pstar"]=1;}
		if($a_array["pend"]>$PageBox["TotalPage"]){$a_array["pend"]=$PageBox["TotalPage"];}
		return $a_array;
	}
}