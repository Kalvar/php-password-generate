<?php
/*
 * @author  : Kuo-Ming Lin
 * @mail    : ilovekalvar@gmail.com 
 * @version : 1.0
 * 
 * Samples :
 *   1). 取 100 組 4 位數的純數字密碼 : 
 *       => $Password->make(100, 4, 'num');
 *       => 2145
 *        
 *   2). 取 100 組 4 位數的英文密碼 :
 *       => $Password->make(100, 4, 'eng');
 *       => aBcD   
 * 
 *   3). 取 100 組 4 位數的英數字混合密碼 : 
 *       => $Password->make(100, 4, 'mix');     
 *       => a2C8
 *   
 *   4). 取 100 組 4 位數的英數字加特殊符號混合密碼 : 
 *       => $Password->make(100, 4, 'spec');  
 *       => b7*D
 *  
 *   5). 測試執行時間( 秒數 ) : 
 *       => $Password->runTime(取得組數, 密碼長度, 密碼模式, 回傳或傾印);  
 *        
 * 注意事項 : 
 *   1). 一般 PHP 執行的 Memory 有 24 MB (25165824 Bytes) 的限制。
 *       => 最安全的取法是 : 1萬組 + 6 位數 + 英數混合模式
 *       => 執行時間約為   : 1.5 ( i7 2630 ) ~ 2.5 ( Core-2 6550 ) 秒。    
 * 
*/
#密碼製作
class Password{
  #建構子
  public function __construct(){
    //遞迴模式計數器 : 要宣告成 Public :: 遞迴時才能正確取用 
    $this->cycleCounter = 0;     
    //最大遞迴次數 : 預設 10 ^ 2 = 100 次
    $this->cycleMax     = pow(10, $this->cycleCounter + 2); 
  }
  
  /*
   *  
   * $catchNumber 共要取得並回傳幾組密碼 :
   *   => 1 代表 : 回傳 1 組密碼
   *   => 2 代表 : 回傳 2 組密碼
   *   => .... 以此類推         
   * 
   * $passLength 取得的每一組密碼長度 : 目前最長至 68 碼
   * 
   * $passMode 密碼模式 :
   *   1). NUM  數字模式 : 
   *       => 2387
   *    
   *   2). ENG  英文模式 : 
   *       -> aBCd       
   *              
   *   3). MIX  英數字混合模式 :
   *       => a23B
   *             
   *   4). SPEC 特殊符號混合模式 : 
   *       => a*-2                  
   *                           
  */
  #密碼製作
  public function make($catchNumber = 1, $passLength = 4, $passMode = 'NUM'){
    #先轉大寫
    $passMode  = strtoupper($passMode);
    #演算法運算的最大次數
    //$maxLength = pow(10, $passLength) - 1;
    $maxLength = pow(2, $passLength + 1);
    #52個大小寫英文字母欄位
    $englishArray = array(
      'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',  
      'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
      'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',  
      'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
    );
    #10個數字
    $numberArray  = array(
      0, 1, 2, 3, 4, 5, 6, 7, 8, 9
    );      
    #特殊符號
    $simpleArray  = array(
      '*', '.', '+', '-', '~', '!'
    );
    #選擇模式 : 製作使用的陣列
    $useArray     = array();
    switch($passMode){
      case 'ENG':
        $useArray = $englishArray;
        break;
      case 'MIX':
        $useArray = array_merge($numberArray, $englishArray);
        break;
      case 'SPEC':
        $useArray = array_merge($numberArray, $englishArray, $simpleArray);
        break;   
      default:
        //NUM
        $useArray = $numberArray;
        break;  
    }
    #使用的陣列長度
    $useLength    = count($useArray) - 1;
    #儲存的陣列
    $saveArray    = array();
    #開始洗牌演算法
    //以現在時間的百萬分之一秒當亂數種子
    mt_srand( (double) microtime() * 1000000 );
    for($x=0; $x<$catchNumber; $x++):
      //進行牌組交換
      for($i=0; $i<$maxLength; $i++){
        //取得 $useArray 長度範圍亂數
        $rand1 = mt_rand(0, $useLength);
        $rand2 = mt_rand(0, $useLength);
        //如果亂數重複 : 則長度減去亂數值
        ($rand1 === $rand2) && $rand2 = $useLength - $rand2;
        //將陣列資料進行交換（打散）
        $tempUse          = $useArray[$rand1];
        $useArray[$rand1] = $useArray[$rand2];
        $useArray[$rand2] = $tempUse;
      }    
      #取出亂數密碼 : 隨機取出開頭後 N 長度的密碼
      $saveArray[] = implode( '', array_slice( $useArray, mt_rand(0, $useLength - $passLength), $randX + $passLength ) );    
    endfor;
    #進行亂數內容檢查
    //去除重複值
    $saveArray = array_unique($saveArray);
    //去除重複值後的長度
    $uniLength = count($saveArray);
    //不重複的長度 < 要取得的密碼總組數
    if($uniLength < $catchNumber){
      //遞迴計數器 + 1
      ++$this->cycleCounter;
      //遞迴製作密碼 : 遞迴次數不超過最大限制次數
      if($this->cycleCounter < $this->cycleMax){
        $reArray = $this->make($catchNumber - $uniLength, $passLength, $passMode);
        //重新放入密碼陣列
        foreach($reArray as $rePass) $saveArray[] = $rePass;
      }//endif  
    }//endif 
    #回傳密碼陣列
    return $saveArray; 
  }
  
  /*
   * $Password->runTime(取得組數, 密碼長度, 密碼模式, 回傳或傾印); 
  */
  #運算時間測試
  public function runTime($catchNumber = 1, $passLength = 4, $passMode = 'NUM', $echo = true){
    $startTime = array_sum(explode(' ',microtime()));
    //print_r( $this->make($catchNumber, $passLength, $passMode) );
    $passArray = $this->make($catchNumber, $passLength, $passMode);
    $runTime   = round( ( array_sum( explode( ' ', microtime() ) ) - $startTime ), 6 );
    if($echo === true):
      echo '程式執行時間：'.$runTime.' sec.';
    else:
      return $runTime;
    endif;          
  } 
  
}
?>