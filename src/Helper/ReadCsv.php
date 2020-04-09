<?php
namespace Ikbal\Datareader\Helper;

use Illuminate\Support\Facades\Log;

class ReadCsv
{

    public function read($file_path, $header = false, $seperator = '|', $remove_last = true){
        $file = fopen($file_path, "r");        
        if($header){
            $line = str_replace("\n","", strtolower(fgets($file))) ;
            $first_column_arr = explode($seperator, $line);
            $first_column_arr = array_map(function($item){
                $string = str_replace(' ', '_', trim($item)); // Replaces all spaces with hyphens.
                return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
            }, $first_column_arr);
        }

        $data_collection = collect();
        $count = 1;
        while(! feof($file)) {
            if($count > 1){
                try {
                    $rest_line = str_replace("\n","",fgets($file));
                    $rest_col_arr = explode($seperator, $rest_line);
                    if($rest_col_arr){
                        if($header){
                            $col_data = $this->mapColumn($first_column_arr, $rest_col_arr);
                            $data_collection->push($col_data);
                        } else {
                            $data_collection->push($rest_col_arr);
                        }
                    } else {
                        $exp_message = 'Proper formated data not found in line: '.$count.PHP_EOL;
                        Log::error($exp_message);
                    }
                } catch (\Exception $ex) {     
                    dd(debug_backtrace(2));               
                    Log::error($ex->getMessage());
                }    
            }
            $count++;            
        }
        fclose($file);
        if($remove_last){
            $data_collection->pop();
        }
        return $data_collection;
    }

    
    /**
     * Map two array and make an associative array
     *
     * @param array $key_arr
     * @param array $val_arr
     * @return void
     */
    private function mapColumn($key_arr, $val_arr){
        $result_arr = [];
        foreach ($key_arr as $key => $value) {
            $result_arr[$value] = !empty($val_arr[$key])?$val_arr[$key]:'';
        }
        return $result_arr;
    }


}