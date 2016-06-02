<?php

namespace adapt\video\ffmpeg_wrapper {
    
    class ffprobe extends \adapt\base{
        
        public function __construct(){
            parent::__construct();
        }
        
        
        public function probe($file){
            $values = array('container' => array(), 'streams' => array());
            if ($this->has_ffprobe()){
                if (file_exists($file)){
                    $output = shell_exec("ffprobe -v error -show_format -show_streams {$file}");
                    $lines = explode("\n", $output);
                    
                    $item = array();
                    
                    foreach($lines as $line){
                        $line = trim($line);
                        if ($line == "[STREAM]" || $line == "[FORMAT]"){
                            $item = array();
                        }elseif ($line == "[/STREAM]"){
                            $values['streams'][] = $item;
                        }elseif ($line == "[/FORMAT]"){
                            $values['container'] = $item;
                        }else{
                            list($key, $value) = explode("=", $line, 2);
                            $item[$key] = $value;
                        }
                    }
                    
                    return $values;
                }else{
                    $this->error("{$file} not found");
                    return false;
                }
                
            }else{
                $this->error('Unable to find ffprobe');
                return false;
            }
        }
        
        public function has_ffprobe(){
            $output = shell_exec('which ffprobe');
            if ($output){
                return true;
            }
            
            return false;
        }
        
        public function get_formats(){
            $formats = array();
            if ($this->has_ffprobe()){
                $output = shell_exec("ffprobe -formats");
                list($poo, $output) = explode("--", $output);
                $output = trim($output);
                $output = preg_replace("/[ ]+/", " ", $output);
                $lines = explode("\n", $output);
                foreach($lines as $line){
                    //print "L: {$line}\n";
                    if ($line){
                        list($muxing, $type, $description) = explode(" ", trim($line), 3);
                        //print "M: {$muxing}\nT: {$type}\nD: {$description}\n\n";
                        if (stripos($type, ",")){
                            $type = explode(",", $type);
                        }else{
                            $type = array($type);
                        }
                        
                        $formats[] = array(
                            'type' => $type,
                            'description' => $description,
                            'mixing_supported' => stripos($muxing, "E") ? true : false,
                            'demuxing_suppored' => stripos($muxing, "D") ? true : false
                        );
                    }
                }
                return $formats;
            }else{
                $this->error('Unable to find ffprobe');
                return false;
            }
        }
        
        
        
        
        public function get_codecs(){
            $codecs = array();
            if ($this->has_ffprobe()){
                $output = shell_exec("ffprobe -codecs");
                list($poo, $output) = explode("-------", $output);
                $output = trim($output);
                $output = preg_replace("/[ ]+/", " ", $output);
                $lines = explode("\n", $output);
                foreach($lines as $line){
                    //print "L: {$line}\n";
                    if ($line){
                        list($muxing, $type, $description) = explode(" ", trim($line), 3);
                        //print "M: {$muxing}\nT: {$type}\nD: {$description}\n\n";
                        //if (stripos($type, ",")){
                        //    $type = explode(",", $type);
                        //}else{
                        //    $type = array($type);
                        //}
                        
                        $codec_type = "Unknown";
                        if (preg_match("/^[.D][.E][V]/", $muxing)){
                            $codec_type = 'Video';
                        }elseif (preg_match("/^[.D][.E][A]/", $muxing)){
                            $codec_type = 'Audio';
                        }elseif (preg_match("/^[.D][.E][S]/", $muxing)){
                            $codec_type = 'Subtitle';
                        }
                        
                        $can_decode = false;
                        if (preg_match("/^[D]/", $muxing)){
                            $can_decode = true;
                        }
                        
                        $can_encode = false;
                        if (preg_match("/^[.D][E]/", $muxing)){
                            $can_encode = true;
                        }
                        
                        $intra_frame_only = false;
                        if (preg_match("/^[.D][.E][.VAS][I]/", $muxing)){
                            $intra_frame_only = true;
                        }
                        
                        $compression = 'None';
                        if (preg_match("/^[.D][.E][.VAS][.I][L]/", $muxing)){
                            $compression = 'Lossy';
                        }
                        
                        if (preg_match("/^[.D][.E][.VAS][.I][.L][S]/", $muxing)){
                            $compression = 'Lossless';
                        }
                        
                        $codecs[] = array(
                            'codec' => $type,
                            'name' => $description,
                            'decoding_supported' => $can_decode,
                            'encoding_suppored' => $can_encode,
                            'type' => $codec_type,
                            'intra_frame_only_codec' => $intra_frame_only,
                            'compression' => $compression
                        );
                    }
                }
                return $codecs;
            }else{
                $this->error('Unable to find ffprobe');
                return false;
            }
        }
        
        
        public function get_decoders(){
            $decoders = array();
            if ($this->has_ffprobe()){
                $output = shell_exec("ffprobe -decoders");
                list($poo, $output) = explode("------", $output);
                $output = trim($output);
                $output = preg_replace("/[ ]+/", " ", $output);
                $lines = explode("\n", $output);
                foreach($lines as $line){
                    if ($line){
                        list($options, $type, $description) = explode(" ", trim($line), 3);
                        
                        
                        $option_type = "Unknown";
                        if (preg_match("/^[V]/", $options)){
                            $option_type = 'Video';
                        }elseif (preg_match("/^[A]/", $options)){
                            $option_type = 'Audio';
                        }elseif (preg_match("/^[S]/", $options)){
                            $option_type = 'Subtitle';
                        }
                        
                        $frame_level_multithreading = false;
                        if (preg_match("/^[.VAS][F]/", $options)){
                            $frame_level_multithreading = true;
                        }
                        
                        $slice_level_multithreading = false;
                        if (preg_match("/^[.VAS][.F][S]/", $options)){
                            $slice_level_multithreading = true;
                        }
                        
                        $experimental = false;
                        if (preg_match("/^[.VAS][.F][.S][X]/", $options)){
                            $experimental = true;
                        }
                        
                        $horiz_band = false;
                        if (preg_match("/^[.VAS][.F][.S][.X][B]/", $options)){
                            $horiz_band = true;
                        }
                        
                        $direct_rendering = false;
                        if (preg_match("/^[.VAS][.F][.S][.X][.B][D]/", $options)){
                            $direct_rendering = true;
                        }
                        
                        $decoders[] = array(
                            'decoder' => $type,
                            'name' => $description,
                            'type' => $option_type,
                            'frame_level_multithreading' => $frame_level_multithreading,
                            'slice_level_multithreading' => $slice_level_multithreading,
                            'supports_draw_horiz_band' => $horiz_band,
                            'supports_direct_rendering' => $direct_rendering,
                            'experimental' => $experimental
                        );
                    }
                }
                return $decoders;
            }else{
                $this->error('Unable to find ffprobe');
                return false;
            }
        }
        
        public function get_encoders(){
            $encoders = array();
            if ($this->has_ffprobe()){
                $output = shell_exec("ffprobe -encoders");
                list($poo, $output) = explode("------", $output);
                $output = trim($output);
                $output = preg_replace("/[ ]+/", " ", $output);
                $lines = explode("\n", $output);
                foreach($lines as $line){
                    if ($line){
                        list($options, $type, $description) = explode(" ", trim($line), 3);
                        
                        
                        $option_type = "Unknown";
                        if (preg_match("/^[V]/", $options)){
                            $option_type = 'Video';
                        }elseif (preg_match("/^[A]/", $options)){
                            $option_type = 'Audio';
                        }elseif (preg_match("/^[S]/", $options)){
                            $option_type = 'Subtitle';
                        }
                        
                        $frame_level_multithreading = false;
                        if (preg_match("/^[.VAS][F]/", $options)){
                            $frame_level_multithreading = true;
                        }
                        
                        $slice_level_multithreading = false;
                        if (preg_match("/^[.VAS][.F][S]/", $options)){
                            $slice_level_multithreading = true;
                        }
                        
                        $experimental = false;
                        if (preg_match("/^[.VAS][.F][.S][X]/", $options)){
                            $experimental = true;
                        }
                        
                        $horiz_band = false;
                        if (preg_match("/^[.VAS][.F][.S][.X][B]/", $options)){
                            $horiz_band = true;
                        }
                        
                        $direct_rendering = false;
                        if (preg_match("/^[.VAS][.F][.S][.X][.B][D]/", $options)){
                            $direct_rendering = true;
                        }
                        
                        $encoders[] = array(
                            'decoder' => $type,
                            'name' => $description,
                            'type' => $option_type,
                            'frame_level_multithreading' => $frame_level_multithreading,
                            'slice_level_multithreading' => $slice_level_multithreading,
                            'supports_draw_horiz_band' => $horiz_band,
                            'supports_direct_rendering' => $direct_rendering,
                            'experimental' => $experimental
                        );
                    }
                }
                return $encoders;
            }else{
                $this->error('Unable to find ffprobe');
                return false;
            }
        }
        
        public function get_bitstream_filters(){
            if ($this->has_ffprobe()){
                
                $output = shell_exec("ffprobe -bsfs");
                list($poo, $output) = explode("Bitstream filters:", $output);
                $output = trim($output);
                return explode("\n", $output);
                
            }else{
                $this->error('Unable to find ffprobe');
                return false;
            }
        }
        
        public function get_file_protocols(){
            if ($this->has_ffprobe()){
                
                $output = shell_exec("ffprobe -protocols");
                list($poo, $output) = explode("Supported file protocols:", $output);
                $output = trim($output);
                return explode("\n", $output);
                
            }else{
                $this->error('Unable to find ffprobe');
                return false;
            }
        }
        
        public function get_pixel_formats(){
            $formats = array();
            
            if ($this->has_ffprobe()){
                $output = shell_exec('ffprobe -pix_fmts');
                list($poo, $output) = explode("-----", $output);
                $output = trim($output);
                
                $output = preg_replace("/[ ]+/", " ", $output);
                $lines = explode("\n", $output);
                foreach($lines as $line){
                    if ($line){
                        list($options, $name, $componets, $bits_per_pixel) = explode(" ", trim($line), 4);
                        
                        $input = false;
                        if (preg_match("/^[I]/", $options)){
                            $input = true;
                        }
                        
                        $output = false;
                        if (preg_match("/^[.I][O]/", $options)){
                            $output = true;
                        }
                        
                        $hardware = false;
                        if (preg_match("/^[.I][.O][H]/", $options)){
                            $hardware = true;
                        }
                        
                        $paletted_format = false;
                        if (preg_match("/^[.I][.O][.H][P]/", $options)){
                            $paletted_format = true;
                        }
                        
                        $bitstream_format = false;
                        if (preg_match("/^[.I][.O][.H][.P][B]/", $options)){
                            $bitstream_format = true;
                        }
                        
                        $formats[] = array(
                            'name' => $name,
                            'number_of_components' => $componets,
                            'bits_per_pixel' => $bits_per_pixel,
                            'supported_input_conversion' => $input,
                            'supported_output_conversion' => $output,
                            'hardware_accelerated' => $hardware,
                            'palatted_format' => $paletted_format,
                            'bitstream_format' => $bitstream_format
                        );
                    }
                }
                
                return $formats;
                
            }else{
                $this->error('Unable to find ffprobe');
                return false;
            }
        }
        
        public function get_sample_formats(){
            $out = array();
            if ($this->has_ffprobe()){
                
                $output = shell_exec('ffprobe -sample_fmts');
                list($poo, $output) = explode('depth', $output, 2);
                $output = trim($output);
                
                $output = preg_replace("/[ ]+/", " ", $output);
                $lines = explode("\n", $output);
                foreach($lines as $line){
                    if ($line){
                        list($name, $depth) = explode(" ", trim($line), 2);
                        $out[] = array('name' => $name, 'depth' => $depth);
                    }
                }
                
                
                return $out;
            }else{
                $this->error('Unable to find ffprobe');
                return false;
            }
        }
        
        public function get_layouts(){
            $layouts = array();
            if ($this->has_ffprobe()){
                
                $output = shell_exec("ffprobe -layouts");
                list($poo, $output) = explode("DECOMPOSITION", $output);
                $output = trim($output);
                
                $output = preg_replace("/[ ]+/", " ", $output);
                $lines = explode("\n", $output);
                foreach($lines as $line){
                    if ($line){
                        list($name, $layout) = explode(" ", trim($line), 2);
                        $layouts[] = array('name' => $name, 'layout' => $layout);
                    }
                }
                
                return $layouts;
                
            }else{
                $this->error('Unable to find ffprobe');
                return false;
            }
        }
    }
    
}

?>