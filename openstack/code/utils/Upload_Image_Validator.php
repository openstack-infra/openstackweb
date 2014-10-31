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

class Upload_Image_Validator extends  Upload_Validator {

    private $allowedMaxImageWidth = null;

    /**
     * Sets Maximum allowed Width for image
     *
     * @param int $width
     */
    public function setAllowedMaxImageWidth(number $width){
        $this->allowedMaxImageWidth = $width;
    }
    /**
     * Determines if the pixels of an image uploaded
     * file is valid - can be defined on an
     * extension-by-extension basis in {@link $allowedMaxFileSize}
     *
     * @return boolean
     */
    public function isValidWidth() {
	    if(isset($this->tmpFile['tmp_name'])){
		    list($width, $height) = getimagesize($this->tmpFile['tmp_name']);
		    if(isset($this->allowedMaxImageWidth)){
			    return ((int) $width <= $this->allowedMaxImageWidth);
		    }
	    }
        return true;
    }

    public function validate() {
        $res = parent::validate();
        // width validation
        if(!$this->isValidWidth()) {
            $this->errors[] = sprintf("Max. Allowed Image Width is %d px",$this->allowedMaxImageWidth);
            $res = false;
        }
        return $res;
    }
}