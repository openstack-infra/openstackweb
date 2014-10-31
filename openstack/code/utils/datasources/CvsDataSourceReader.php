<?php
/**
 * Copyright 2014 Openstack.org
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/

class CvsDataSourceReader implements SequentialDataSourceReader{

    private $handle;
    private $data;
    private $fields_number;
    private $fields;
    private $delimiter;

    public function __construct($delimiter){
        $this->delimiter = $delimiter;
    }

    public function getFieldsInfo()
    {
        $this->fields = array();
        $this->data = fgetcsv ($this->handle,null,$this->delimiter);
        if($this->data=== FALSE)
            throw new Exception("CvsDataSourceReader: Could not get file header!");
        $this->fields_number = count($this->data);
        for($i=0;$i<$this->fields_number;$i++){
            $this->data[$i] = trim($this->data[$i]);
            if( empty($this->data[$i])) continue;
            $this->fields[$this->data[$i]]=$i;
        }
        return $this->fields;
    }

    public function getNextRow()
    {
        $this->data = fgetcsv ($this->handle,null,$this->delimiter);
        if($this->data === FALSE)
            return false;
        if($this->data === FALSE)
            return false;
        $res = array();
        for($i=0;$i<$this->fields_number;$i++){
            $this->data[$i] = trim($this->data[$i]);
            if( empty($this->data[$i])) continue;
            $res[$i]=$this->data[$i];
        }
        return $res;
    }

    public function Open($path)
    {
        ini_set("auto_detect_line_endings", "1");
        $this->handle = fopen($path, "r");
        if($this->handle=== FALSE)
            throw new Exception("CvsDataSourceReader: Could not open file ".$path);
    }

    public function Close()
    {
        fclose($this->handle);
    }
}