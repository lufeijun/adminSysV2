<?php
namespace App\System;


/**
 *
 */
class Image
{

      /**
         * @param $path
         * @param string $type
         * @return \Illuminate\Contracts\Routing\UrlGenerator|string
         */
      public static function getImageFromSerivce($path , $type = 'user-image')
      {
          $url = '';

          switch ( $type ) {
              case 'user-image': // 用户头像
                  if ( $path ) {
                      $url = url('sys_images/' . $path);
                  } else {
                      $url = url('img/0.jpg');
                  }
                  break;
              default :
                  $url = url('img');
                  if ( $path != 'NORES' ) {
                      $url .= '/'.trim($path).'.jpg';
                  }
          }

          return $url;
      }





}



