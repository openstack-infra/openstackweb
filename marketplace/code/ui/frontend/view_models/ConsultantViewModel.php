<?php

/**
 * Class ConsultantViewModel
 */
final class ConsultantViewModel {

	public static function getOfficesLocationsJson(IConsultant $consultant){
        $res = array();
        $color = strtoupper(dechex(rand(0,10000000)));
        $office_index = 1;
        foreach($consultant->getOffices() as $office){
            $data_office = array();
            $data_office['color']   = is_null($consultant->getCompany()->Color)?$color:$consultant->getCompany()->Color;
            $address = $office->getAddress();
            $data_office['address'] = '';
            if(!empty($address))
                $data_office['address'] = trim($address.' '.$office->getAddress1());
            $state = $office->getState();
            if(!empty($state)){
                $data_office['address'] .= ', '.$state;
            }
            $data_office['address'] .=((empty($data_office['address']))?'': ', ').$office->getCity();
            $data_office['address'] .= ', '.$office->getCountry();
            $data_office['lat']     = $office->getLat();
            $data_office['lng']     = $office->getLng();
            $data_office['owner']   = $consultant->getName();
            $data_office['name']    = sprintf('Office #%s',$office_index);
            ++$office_index;
            array_push($res,$data_office);
        }
        return json_encode($res);
	}

} 