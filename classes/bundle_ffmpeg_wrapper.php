<?php

namespace adapt\video\ffmpeg_wrapper{
    
    /* Prevent Direct Access */
    defined('ADAPT_STARTED') or die;
    
    class bundle_ffmpeg_wrapper extends \adapt\bundle{
        
        public function __construct($data){
            parent::__construct('ffmpeg_wrapper', $data);
        }
        
    }
}

?>