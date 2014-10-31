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
tinyMCE.init({
    theme : "advanced",
    mode: "exact",
    elements : "EditProfileForm_EditProfileForm_Bio, CandidateApplicationForm_CandidateApplicationForm_Bio, CandidateApplicationForm_CandidateApplicationForm_RelationshipToOpenStack, CandidateApplicationForm_CandidateApplicationForm_Experience, CandidateApplicationForm_CandidateApplicationForm_BoardsRole, CandidateApplicationForm_CandidateApplicationForm_TopPriority, EditSpeakerProfileForm_EditSpeakerProfileForm_Bio",
    theme_advanced_toolbar_location : "top",
    theme_advanced_buttons1 : "bold,italic,underline,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,undo,redo",
    theme_advanced_buttons2 : "",
    theme_advanced_buttons3 : "",
    valid_styles : { '*' : 'font-weight,font-style' },
    height:"200px",
    width:"600px"
});