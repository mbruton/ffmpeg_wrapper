<?php

namespace extensions\ffmpeg_wrapper{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class ffmpeg extends \frameworks\adapt\base{
        
        protected $_command = "ffmpeg";
        
        
        /* Test function to be removed when bundled */
        public function error($error){
            print "ERROR: {$error}\n";
        }
        
        public function format($format){
            $this->_command .= " -f {$format}";
            return $this;
        }
        
        public function input_file($filename){
            if (file_exists($filename)){
                $this->_command .= " -i \"{$filename}\"";
            }else{
                $this->error("File '{$file}' was not found");
            }
            
            return $this;
        }
        
        public function overwrite_output_file(){
            $this->_command .= " -y";
            return $this;
        }
        
        public function never_overwrite_output_file(){
            $this->_command .= " -n";
            return $this;
        }
        
        public function video_codec($codec = 'copy', $stream_id = null){
            $this->_command .= " -c:v";
            if (!is_null($stream_id) && is_numeric($stream_id)){
                $this->_command .= ":{$stream_id}";
            }
            
            $this->_command .= " {$codec}";
            
            return $this;
        }
        
        public function audio_codec($codec = 'copy', $stream_id = null){
            $this->_command .= " -c:a";
            if (!is_null($stream_id) && is_numeric($stream_id)){
                $this->_command .= ":{$stream_id}";
            }
            
            $this->_command .= " {$codec}";
            
            return $this;
        }
        
        public function duration($time){
            $this->_command .= " -t {$time}";
            return $this;
        }
        
        public function to($time){
            $this->_command .= " -to {$time}";
            return $this;
        }
        
        public function limit_size($size){
            $this->_command .= " -fs {$size}";
            return $this;
        }
        
        public function start_at($time){
            $this->_command .= " -ss {$time}";
            return $this;
        }
        
        public function input_time_offset($time){
            $this->_command .= " -itsoffet {$time}";
            return $this;
        }
        
        public function timestamp($timestamp){
            $this->_command .= " -timestamp {$date}";
            return $this;
        }
        
        public function metadata($key, $value = "", $specifier = null){
            $this->_command .= " -metadata";
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            
            $this->_command .= " {$key}={$value}";
            return $this;
        }
        
        public function target($type){
            $this->_command .= " -target {$type}";
            return $this;
        }
        
        public function data_frames($number){
            $this->_command .= " -dframes {$number}";
            return $this;
        }
        
        public function frames($frame_count, $specifier = null){
            $this->_command .= " -frames";
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            
            $this->_command .= " {$frame_count}";
            return $this;
        }
        
        public function quality_scale($quality, $specifier = null){
            $this->_command .= " -q";
            
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            
            $this->_command .= " {$quality}";
            return $this;
        }
        
        public function filter($filtergraph, $specifier = null){
            $this->_command .= " -filter";
            
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            
            $this->_command .= " {$filtergraph}";
            return $this;
        }
        
        public function preset($preset, $specifier = null){
            $this->_command .= " -pre";
            
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            
            $this->_command .= " {$preset}";
            return $this;
        }
        
        public function track_progress($url){
            $this->_command .= " -progress {$url}";
            return $this;
        }
        
        public function debug_timestamps(){
            $this->_command .= " -debug_ts";
            return $this;
        }
        
        public function attach($filename){
            if (file_exists($filename)){
                $this->_command .= " -attach {$filename}";
            }else{
                $this->error("Unable to attach file '{$file}' does not exist");
            }
            
            return $this;
        }
        
        public function dump_attachment($filename, $stream_id = 0){
            $this->_command .= " -dump_attachment:t{$stream_id} {$filename}";
            return $this;
        }
        
        public function dump_attachments(){
            $this->_command .= " -dump_attachment:t \"\"";
            return $this;
        }
        
        public function disable_auto_rotate(){
            $this->_command .= " -noautorotate";
            return $this;
        }
        
        public function video_frames($frames){
            $this->_command .= " -frames:v {$frames}";
            return $this;
        }
        
        public function video_frame_rate($frames_per_second, $stream_id = null){
            $this->_command .= " -r";
            if (!is_null($stream_id)) $this->_command .= ":{$stream_id}";
            $this->_command .= " {$frames_per_second}";
            return $this;
        }
        
        public function video_frame_size($width, $height, $stream_id = null){
            $this->_command .= " -s";
            if (!is_null($stream_id)) $this->_command .= ":{$stream_id}";
            $this->_command .= " {$width}x{$height}";
            return $this;
        }
        
        public function video_aspect($aspect, $stream_id = null){
            $this->_command .= " -aspect";
            if (!is_null($stream_id)) $this->_command .= ":{$stream_id}";
            $this->_command .= " {$aspect}";
            return $this;
        }
        
        public function bits_per_raw_sample($bits){
            $this->_command .= " -bits_per_sample {$bits}";
            return $this;
        }
        
        public function video_bitrate($rate){
            $this->_command .= " -b:v {$rate}";
            return $this;
        }
        
        public function audio_bitrate($rate){
            $this->_command .= " -b:a {$rate}";
            return $this;
        }
        
        public function disable_video_recording(){
            $this->_command .= " -vn";
            return $this;
        }
        
        public function pass($pass, $stream_id = null){
            $this->_command .= " -pass";
            if (!is_null($stream_id)) $this->_command .= ":{$stream_id}";
            $this->_command .= " {$pass}";
            return $this;
        }
        
        public function pass_log($filename_prefix = 'ffmpeg2pass', $stream_id = null){
            $this->_command .= " -passlogfile";
            if (!is_null($stream_id)) $this->_command .= ":{$stream_id}";
            $this->_command .= " {$filename_prefix}";
            return $this;
        }
        
        public function video_filter($filtergraph, $stream_id = null){
            $this->_command .= " -filter:v";
            
            if (!is_null($stream_id)){
                $this->_command .= ":{$stream_id}";
            }
            
            $this->_command .= " {$filtergraph}";
            return $this;
        }
        
        public function pixel_format($format, $stream_id = null){
            $this->_command .= " -pix_fmt";
            
            if (!is_null($stream_id)){
                $this->_command .= ":{$stream_id}";
            }
            
            $this->_command .= " {$format}";
            return $this;
        }
        
        public function sws_flags($flags){
            $this->_command .= " -sws_flags {$flags}";
            return $this;
        }
        
        public function vdt($threshold){
            $this->_command .= " -vdt {$threshold}";
            return $this;
        }
        
        public function rate_controller_override($override, $specifier = null){
            $this->_command .= " -rc_override";
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            $this->_command .= " {$override}";
            return $this;
        }
        
        public function ilme(){
            $this->_command .= " -ilme";
            return $this;
        }
        
        public function psnr(){
            $this->_command .= " -pnsr";
            return $this;
        }
        
        public function video_statistics(){
            $this->_command .= " -vstats";
            return $this;
        }
        
        public function video_statistics_log($file){
            $this->_command .= " -vstats_file {$file}";
            return $this;
        }
        
        public function top($value, $specifier = null){
            $this->_command .= " -top";
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            $this->_command .= " {$value}";
            return $this;
        }
        
        public function dc($precision){
            $this->_command .= " -dc {$precision}";
            return $this;
        }
        
        public function video_tag($tag){
            $this->_command .= " -tag:v {$tag}";
            return $this;
        }
        
        public function show_qp_histogram(){
            $this->_command .= " -qphist";
            return $this;
        }
        
        public function force_key_frames($expr, $specifier = null){
            $this->_command .= " -force_key_frames";
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            $this->_command .= " {$expr}";
            return $this;
        }
        
        public function copyinkf($specifier = null){
            $this->_command .= " -copyinkf";
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            return $this;
        }
        
        public function hardware_acceleration($type, $specifier = null){
            if (in_array($type, array('none', 'auto', 'vda', 'vdpau', 'dxva2'))){
                $this->_command .= " -hwaccel";
                if (!is_null($specifier)){
                    $this->_command .= ":{$specifier}";
                }
                $this->_command .= " {$type}";
            }else{
                $this->error("Unable to use hardware acceleration, unknown method '{$type}'");
            }
            
            return $this;
        }
        
        public function hardware_acceleration_device($device, $specifier = null){
            if (in_array($type, array('vdpau', 'dxva2'))){
                $this->_command .= " -hwaccel_device";
                if (!is_null($specifier)){
                    $this->_command .= ":{$specifier}";
                }
                $this->_command .= " {$device}";
            }else{
                $this->error("Unable to use '{$device}' for hardware acceleration");
            }
            
            return $this;
        }
        
        public function audio_frames($frames){
            $this->_command .= " -frames:a {$frames}";
            return $this;
        }
        
        public function audio_sampling_frequency($freq, $specifier = null){
            $this->_command .= " -ar";
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            $this->_command .= " {$freq}";
            return $this;
        }
        
        public function audio_quality($quality){
            $this->_command .= " -q:a {$quality}";
            return $this;
        }
        
        public function audio_channels($channels, $specifier = null){
            $this->_command .= " -ac";
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            $this->_command .= " {$channels}";
            return $this;
        }
        
        public function disable_audio(){
            $this->_command .= " -an";
        }
        
        public function sample_format($format, $specifier = null){
            $this->_command .= " -sample_fmt";
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            $this->_command .= " {$format}";
            return $this;
        }
        
        public function audio_filter($filtergraph, $stream_id = null){
            $this->_command .= " -filter:a";
            
            if (!is_null($stream_id)){
                $this->_command .= ":{$stream_id}";
            }
            
            $this->_command .= " {$filtergraph}";
            return $this;
        }
        
        public function audio_tag($tag){
            $this->_command .= " -tag:a {$tag}";
            return $this;
        }
        
        public function guess_auto_layout_max($channels){
            $this->_command .= " -guess_layout_max {$channels}";
            return $this;
        }
        
        public function subtitle_codec($codec){
            $this->_command .= " -codec:s {$codec}";
            return $this;
        }
        
        public function disable_subtitles(){
            $this->_command .= " -sn";
            return $this;
        }
        
        public function fix_subtitle_duration(){
            $this->_command .= " -fix_sub_duration";
            return $this;
        }
        
        public function subtitle_cavas_size($size){
            $this->_command .= " -canvas_size {$size}";
            return $this;
        }
        
        public function map($mapping){
            $this->_command .= " -map {$mapping}";
            return $this;
        }
        
        public function map_channel($mapping){
            $this->_command .= " -map_channel {$mapping}";
            return $this;
        }
        
        public function map_metadata($mapping){
            $this->_command .= " -map_metadata {$mapping}";
            return $this;
        }
        
        public function map_chapters($input_file_index){
            $this->_command .= " -map_chapters {$input_file_index}";
            return $this;
        }
        
        public function benchmark(){
            $this->_command .= " -benchmark";
            return $this;
        }
        
        public function benchmark_all(){
            $this->_command .= " -benchmark_all";
            return $this;
        }
        
        public function timelimit($seconds){
            $this->_command .= " -timelimit {$seconds}";
            return $this;
        }
        
        public function dump(){
            $this->_command .= " -dump";
            return $this;
        }
        
        public function hex(){
            $this->_command .= " -hex";
            return $this;
        }
        
        public function re(){
            $this->_command .= " -re";
            return $this;
        }
        
        public function loop_input(){
            $this->_command .= " -loop_input";
            return $this;
        }
        
        public function loop_output($times){
            $this->_command .= " -loop_output {$times}";
            return $this;
        }
        
        public function vsync($param){
            $this->_command .= " -vsync {$param}";
            return $this;
        }
        
        public function frame_drop_threshold($param){
            $this->_command .= " -frame_drop_threshold {$param}";
            return $this;
        }
        
        public function async($param){
            $this->_command .= " -async {$param}";
            return $this;
        }
        
        public function copy_timestamps(){
            $this->_command .= " -copyts";
            return $this;
        }
        
        public function start_at_zero(){
            $this->_command .= " -start_at_zero";
            return $this;
        }
        
        public function copy_timebase($mode){
            $this->_command .= " -copytb {$mode}";
            return $this;
        }
        
        public function shortest(){
            $this->_command .= " -shortest";
            return $this;
        }
        
        public function dts_delta_threshold(){
            $this->_command .= " -dts_delta_threshold";
            return $this;
        }
        
        public function max_demux_delay($seconds){
            $this->_command .= " -muxdelay {$seconds}";
            return $this;
        }
        
        public function change_stream_id($old_id, $new_id){
            $this->_command .= " -streadid {$old_id}:{$new_id}";
            return $this;
        }
        
        public function bitstream_filter($filters, $specifier = null){
            $this->_command .= " -bsf";
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            $this->_command .= " {$filters}";
            return $this;
        }
        
        public function tag($tag, $specifier = null){
            $this->_command .= " -tag";
            if (!is_null($specifier)){
                $this->_command .= ":{$specifier}";
            }
            $this->_command .= " {$tag}";
            return $this;
        }
        
        public function timecode($time){
            $this->_command .= " -timecode {$time}";
            return $this;
        }
        
        public function filter_complex($filtergraph){
            $this->_command .= " -filter_complex {$filtergraph}";
            return $this;
        }
        
        public function lavfi($filtergraph){
            $this->_command .= " -lavfi {$filtergraph}";
            return $this;
        }
        
        public function filter_complex_script($filename){
            $this->_command .= " -filter_complex {$filename}";
            return $this;
        }
        
        public function accurate_seek(){
            $this->_command .= " -accurate_seek";
            return $this;
        }
        
        public function seek_timestamp(){
            $this->_command .= " -seek_timestamp";
            return $this;
        }
        
        public function thread_queue_size($size){
            $this->_command .= " -thread_queue_size {$size}";
            return $this;
        }
        
        public function override_ffserver(){
            $this->_command .= " -override_ffserver";
            return $this;
        }
        
        public function dump_sdp($file){
            $this->_command .= " -sdp_file {$file}";
            return $this;
        }
        
        public function discard(){
            $this->_command .= " -discard";
            return $this;
        }
        
        public function output_file($file){
            $this->_command .= " {$file}";
            return $this;
        }
        
        public function add($key, $value){
            $this->_command .= " -{$key} {$value}";
            return $this;
        }
        
        public function __toString(){
            return $this->_command;
        }
        
        public function execute(){
            return shell_exec($this->_command);
        }
    }
    
}


?>