php-password-generate
=====================
To generate the password for 4 modes to use. 

## Supports

PHP 4+

## How To Get Started

 1). 取 100 組 4 位數的純數字密碼 : 
   => $Password->make(100, 4, 'num');
   => Output : 2145
      
 2). 取 100 組 4 位數的英文密碼 :
   => $Password->make(100, 4, 'eng');
   => Output : aBcD   

 3). 取 100 組 4 位數的英數字混合密碼 : 
 	 => $Password->make(100, 4, 'mix');     
 	 => Output : a2C8
    
 4). 取 100 組 4 位數的英數字加特殊符號混合密碼 : 
 	 => $Password->make(100, 4, 'spec');  
 	 => Output : b7*D
  
 5). 測試執行時間( 秒數 ) : 
 	 => $Password->runTime(取得組數, 密碼長度, 密碼模式, 回傳或傾印);  

## Version

Now is V1.0.

## License

Password is available under the MIT license ( or Whatever you wanna do ). See the LICENSE file for more info.
