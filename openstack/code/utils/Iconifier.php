<?php
/**
 * Copyright 2014 Openstack Foundation
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
class Iconifier{
	
	/**
	 * Return the relative URL of an icon for the file type,
	 * based on the {@link appCategory()} value.
	 * Images are searched for in "sapphire/images/app_icons/".
	 *
	 * @return String
	 */
	public function getIcon($file){
		
		$ext = $this->getExt($file);
		if(!Director::fileExists("themes/".SSViewer::current_theme()."/images/icons/file_extension_{$ext}.png")) {
			$ext = $this->appCategory($file);
		}
		
		if(!Director::fileExists("themes/".SSViewer::current_theme()."/images/icons/file_extension_{$ext}.png")) {
			$ext = "generic";
		}
		
		return "themes/".SSViewer::current_theme()."/images/icons/file_extension_{$ext}.png";
	}
	
	function getExt($file){
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		return $ext;
	}
	
	public function appCategory($file) {
		$ext =  $this->getExt($file);
		switch($ext) {
			case "aif": case "au": case "mid": case "midi": case "mp3": case "ra": case "ram": case "rm":
			case "mp3": case "wav": case "m4a": case "snd": case "aifc": case "aiff": case "wma": case "apl":
			case "avr": case "cda": case "mp4": case "ogg":
				return "audio";
	
			case "mpeg": case "mpg": case "m1v": case "mp2": case "mpa": case "mpe": case "ifo": case "vob":
			case "avi": case "wmv": case "asf": case "m2v": case "qt":
				return "video";
	
			case "arc": case "rar": case "tar": case "gz": case "tgz": case "bz2": case "dmg": case "jar":
			case "ace": case "arj": case "bz": case "cab":
				return "zip";
	
			case "bmp": case "gif": case "jpg": case "jpeg": case "pcx": case "tif": case "png": case "alpha":
			case "als": case "cel": case "icon": case "ico": case "ps":
				return "img";
			case "key": case "pptx": case "ppt":
				return "ppt";
			case "doc": case "docx":
				return "doc";
			case "xls": case "xlsx":
				return "xls";
		}
	}
	
}