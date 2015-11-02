<?php
/**
 * Created by PhpStorm.
 */
namespace Home\Model;
use Common\Model;

class protocolModel extends BaseModel {
    public  function getProtocol($where_field="",$mul=1,$field=''){
        $db=M('nb_protocol')->join("nb_userinfo on  nb_userinfo.name Like nb_protocol.receive_person")
            ->join("nb_client as ins on ins.id=nb_protocol.inspected")
            ->join("nb_client on nb_client.id=nb_protocol.client")
            ->join("nb_common on nb_common.id=nb_protocol.project_id");
        if($field==''){
            $field="nb_protocol.*,nb_client.company as client_name,ins.company as inspected_name,nb_common.name as project_name ";
        }
        $dt=$this->getBaseInfo($db,$where_field,$mul,$field);
        //dump($dt);
        return $dt;
    }
    public function getProtocolInfo($where,$field='',$mul=0){
        $db=M('nb_protocol')
            ->join("nb_client on nb_client.id=nb_protocol.client")
            ->join("nb_client as ins on ins.id=nb_protocol.inspected")
            ->join("nb_common on nb_common.id=nb_protocol.project_id");

        if($field==''){
            $field="nb_protocol.*,nb_client.company as client_name,nb_common.name as project_name ";
        }
        if($mul==0) $dt=$db->where($where)->field($field)->find();
        else $dt=$db->where($where)->field($field)->select();
        return $dt;
    }
} 